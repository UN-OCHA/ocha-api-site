"""Shared HTTP client and helpers for local OCHA API probes."""

from __future__ import annotations

import json
import os
import ssl
from dataclasses import dataclass, field
from pathlib import Path
from typing import Any
from urllib.error import HTTPError, URLError
from urllib.parse import urljoin
from urllib.request import Request, urlopen

SCRIPTS_DIR = Path(__file__).resolve().parent
DEFAULT_ENV_FILE = SCRIPTS_DIR / ".env.api"
DEFAULT_BASE = "https://ocha-api-local.test/api/v1"
DEFAULT_APP = "test-api"

# Local TLS via reverse proxy often uses a local CA; skip verify for probes only.
SSL_CTX = ssl._create_unverified_context()


def load_env_file(path: Path) -> dict[str, str]:
    values: dict[str, str] = {}
    if not path.is_file():
        return values
    for raw in path.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, _, val = line.partition("=")
        key = key.strip()
        val = val.strip().strip("'").strip('"')
        if key:
            values[key] = val
    return values


def resolve_credentials(
    *,
    env_file: Path,
    base_url: str | None = None,
    api_key: str | None = None,
    app_name: str | None = None,
) -> tuple[str, str, str, Path]:
    """CLI > process env > env file > defaults. Returns (base_url, api_key, app_name, env_file)."""
    file_env = load_env_file(env_file)
    resolved_base = base_url or os.environ.get("BASE_URL") or file_env.get("BASE_URL") or DEFAULT_BASE
    resolved_key = api_key or os.environ.get("API_KEY") or file_env.get("API_KEY") or ""
    resolved_app = app_name or os.environ.get("APP_NAME") or file_env.get("APP_NAME") or DEFAULT_APP
    return resolved_base, resolved_key, resolved_app, env_file


@dataclass
class Result:
    status: str  # PASS | FAIL | SKIP
    method: str
    path: str
    http: int | str
    note: str = ""


@dataclass
class Report:
    rows: list[Result] = field(default_factory=list)

    def add(self, status: str, method: str, path: str, http: int | str, note: str = "") -> Result:
        row = Result(status, method, path, http, note)
        self.rows.append(row)
        mark = {"PASS": ".", "FAIL": "F", "SKIP": "s"}.get(status, "?")
        print(f"{mark} {status:4}  {method:6}  {path}  [{http}]  {note}".rstrip())
        return row

    def summary(self) -> int:
        counts = {"PASS": 0, "FAIL": 0, "SKIP": 0}
        for r in self.rows:
            counts[r.status] = counts.get(r.status, 0) + 1
        print()
        print(f"PASS={counts['PASS']}  FAIL={counts['FAIL']}  SKIP={counts['SKIP']}  TOTAL={len(self.rows)}")
        return 1 if counts["FAIL"] else 0


class Client:
    def __init__(self, base: str, api_key: str, app_name: str, accept: str = "application/json"):
        self.base = base.rstrip("/")
        self.api_key = api_key
        self.app_name = app_name
        self.accept = accept

    def request(
        self,
        method: str,
        path: str,
        *,
        json_body: Any = None,
        headers: dict[str, str] | None = None,
        accept: str | None = None,
        include_api_key: bool = True,
        include_app_name: bool | None = None,
        content_type: str | None = None,
    ) -> tuple[int, Any, str]:
        accept = accept or self.accept
        if include_app_name is None:
            include_app_name = accept == "application/json"

        url = path if path.startswith("http") else urljoin(self.base + "/", path.lstrip("/"))
        hdrs: dict[str, str] = {
            "Accept": accept,
            "Cache-Control": "no-cache",
            "Pragma": "no-cache",
        }
        if include_api_key and self.api_key:
            hdrs["API-KEY"] = self.api_key
        if include_app_name and self.app_name:
            hdrs["APP-NAME"] = self.app_name
        body_bytes = None
        if json_body is not None:
            body_bytes = json.dumps(json_body).encode("utf-8")
            hdrs["Content-Type"] = content_type or "application/json"
        if headers:
            hdrs.update(headers)

        req = Request(url, data=body_bytes, headers=hdrs, method=method.upper())
        try:
            with urlopen(req, context=SSL_CTX, timeout=60) as resp:
                raw = resp.read().decode("utf-8", errors="replace")
                code = resp.getcode()
        except HTTPError as e:
            raw = e.read().decode("utf-8", errors="replace")
            code = e.code
        except URLError as e:
            return 0, None, str(e.reason)

        data: Any = None
        if raw.strip():
            try:
                data = json.loads(raw)
            except json.JSONDecodeError:
                data = raw
        return code, data, raw

    def check(
        self,
        report: Report,
        method: str,
        path: str,
        *,
        expect: set[int] | None = None,
        ok: set[int] | None = None,
        note: str = "",
        soft_skip_on: set[int] | None = None,
        **kwargs: Any,
    ) -> tuple[int, Any]:
        expect = expect or ok or {200, 201, 204}
        code, data, raw = self.request(method, path, **kwargs)
        if code == 0:
            report.add(
                "FAIL",
                method,
                path,
                "ERR",
                note or (raw[:120] if isinstance(raw, str) else "network error"),
            )
            return code, data
        if soft_skip_on and code in soft_skip_on:
            report.add("SKIP", method, path, code, note or "soft-skip")
            return code, data
        if code in expect:
            report.add("PASS", method, path, code, note)
        else:
            detail = ""
            if isinstance(data, dict):
                detail = str(
                    data.get("detail") or data.get("description") or data.get("title") or ""
                )[:120]
            elif isinstance(raw, str) and not isinstance(data, (dict, list)):
                detail = raw[:120]
            report.add("FAIL", method, path, code, (note + " " + detail).strip())
        return code, data


def as_list(data: Any) -> list[Any]:
    if data is None:
        return []
    if isinstance(data, list):
        return [x for x in data if x not in (None, [], {}, "")]
    if isinstance(data, dict):
        for key in ("member", "hydra:member", "data", "results"):
            if isinstance(data.get(key), list):
                return as_list(data[key])
        if data and all(isinstance(v, (str, dict, int, float)) for v in data.values()):
            return [
                {"id": k, "value": v} if not isinstance(v, dict) else {"id": k, **v}
                for k, v in data.items()
            ]
    return []
