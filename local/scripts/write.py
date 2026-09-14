"""Write probes for real provider prefixes. Never calls /archive."""

from __future__ import annotations

import uuid
from typing import Any

from common import Client, Report, as_list

TARGET_PROVIDER_IDS = (
    "cbpf",
    "cerf",
    "fts",
    "hdx",
    "idps",
    "inform",
    "inform_risk",
    "oct",
    "rw_crisis",
)

FALLBACK_BODY = {
    "iso3": "afg",
    "country": "Afghanistan",
    "year": "2099",
    "name": "WriteTestIndicator",
    "value": "1",
    "figure_id": "write_test_figure",
    "source": "test-api",
    "description": "disposable test-api write row",
}

SAMPLE_KEYS = (
    "iso3",
    "country",
    "year",
    "name",
    "value",
    "tags",
    "unit",
    "value_type",
    "source",
    "description",
    "url",
)


def usable_sample(item: Any) -> bool:
    """Reject rows that would fail KeyFigures validation (e.g. OCT legacy iso3 'a')."""
    if not isinstance(item, dict):
        return False
    iso3 = str(item.get("iso3") or "").strip().lower()
    year = str(item.get("year") or "").strip()
    if len(iso3) != 3 or not iso3.isalpha():
        return False
    if len(year) != 4 or not year.isdigit():
        return False
    if item.get("name") is None:
        return False
    return True


def sample_body_from_items(items: list[Any]) -> dict[str, Any]:
    for item in items:
        if not usable_sample(item):
            continue
        body: dict[str, Any] = {}
        for key in SAMPLE_KEYS:
            if key in item and item[key] is not None:
                body[key] = item[key]
        if "value" not in body:
            body["value"] = "1"
        body["iso3"] = str(body["iso3"]).strip().lower()
        body["year"] = str(body["year"]).strip()
        body["country"] = body.get("country") or body["iso3"].upper()
        return body
    return dict(FALLBACK_BODY)


def disposable_body(base: dict[str, Any], *, name_suffix: str) -> dict[str, Any]:
    body = dict(base)
    body["name"] = f"WriteTest_{name_suffix}"
    body["figure_id"] = f"write_test_{name_suffix}"
    body["source"] = body.get("source") or "test-api"
    body["description"] = "disposable test-api write row"
    if not body.get("year"):
        body["year"] = "2099"
    return body


def run_writes_for_prefix(
    client: Client,
    report: Report,
    prefix: str,
    *,
    do_patch: bool,
    do_batch: bool,
) -> None:
    code, data = client.check(
        report,
        "GET",
        f"/{prefix}?itemsPerPage=50",
        expect={200},
        soft_skip_on={403, 404},
        note="sample",
    )
    if code in (403, 404):
        return
    if code != 200:
        return

    base = sample_body_from_items(as_list(data))
    token = uuid.uuid4().hex[:12]
    smoke_id = f"write_test_{token}"
    path = f"/{prefix}/{smoke_id}"
    put_body = disposable_body(base, name_suffix=token)

    code, _ = client.check(
        report,
        "PUT",
        path,
        expect={200, 201},
        json_body=put_body,
        soft_skip_on={403},
        note="disposable create",
    )
    if code == 403:
        return
    if code not in (200, 201):
        return

    client.check(report, "GET", path, note="after put")

    if do_patch:
        client.check(
            report,
            "PATCH",
            path,
            expect={200},
            json_body={"value": "2", "description": "patched by test-api"},
            content_type="application/merge-patch+json",
            note="disposable patch",
        )

    client.check(report, "DELETE", path, expect={200, 204}, note="disposable delete")

    if not do_batch:
        return

    batch_token = uuid.uuid4().hex[:8]
    batch_body = {
        "data": [disposable_body(base, name_suffix=f"batch_{batch_token}")]
    }
    batch_body["data"][0]["year"] = "2098"

    code, batch = client.check(
        report,
        "POST",
        f"/{prefix}/batch",
        expect={200, 201},
        json_body=batch_body,
        soft_skip_on={403},
        note="disposable batch",
    )
    if code not in (200, 201) or not isinstance(batch, dict):
        return

    successful = batch.get("successful") or {}
    if isinstance(successful, dict):
        created_ids = list(successful.keys())
    elif isinstance(successful, list):
        created_ids = [
            str(x.get("id")) for x in successful if isinstance(x, dict) and x.get("id")
        ]
    else:
        created_ids = []

    for created_id in created_ids[:5]:
        client.check(
            report,
            "DELETE",
            f"/{prefix}/{created_id}",
            expect={200, 204},
            note="cleanup batch row",
        )


def resolve_targets(
    only: set[str] | None,
    id_to_prefix: dict[str, str],
    writable_ids: set[str],
    report: Report,
    *,
    is_admin: bool,
) -> list[tuple[str, str]]:
    """Prefixed provider routes only (matches n8n / production writes)."""
    targets: list[tuple[str, str]] = []

    for pid in TARGET_PROVIDER_IDS:
        if only and pid not in only and id_to_prefix.get(pid, "") not in only:
            continue
        if pid not in id_to_prefix:
            report.add("SKIP", "—", f"/{pid}", "-", "provider not in GET /providers")
            continue
        prefix = id_to_prefix[pid]
        if not is_admin and pid not in writable_ids:
            report.add("SKIP", "—", f"/{prefix}", "-", f"not in can_write ({pid})")
            continue
        targets.append((pid, prefix))

    seen: set[str] = set()
    unique: list[tuple[str, str]] = []
    for label, prefix in targets:
        if prefix in seen:
            continue
        seen.add(prefix)
        unique.append((label, prefix))
    return unique


def run_country_write(client: Client, report: Report) -> None:
    """Disposable PUT/GET/DELETE for /countries (n8n Ref - countries).

    Uses a random 3-letter id so re-runs do not collide with country_version
    rows left after DELETE (version PK is id+version; delete only soft-flags).
    """
    # Avoid real ISO-3166 alpha-3 codes: prefix with 'x' + 2 hex chars.
    smoke_id = "x" + uuid.uuid4().hex[:2]
    path = f"/countries/{smoke_id}"
    body = {
        "id": smoke_id,
        "name": f"WriteTest Country {smoke_id.upper()}",
        "iso2": smoke_id[:2],
        "iso3": smoke_id,
        "code": "999",
    }

    print(f"--- countries (/countries) id={smoke_id} ---")

    # Best-effort cleanup if a prior run left the live row (version table may remain).
    client.check(
        report,
        "DELETE",
        path,
        expect={200, 204, 404},
        soft_skip_on={403},
        note="pre-clean",
    )

    code, _ = client.check(
        report,
        "PUT",
        path,
        expect={200, 201},
        json_body=body,
        soft_skip_on={403},
        note="disposable create",
    )
    if code == 403:
        return
    if code not in (200, 201):
        return

    client.check(report, "GET", path, note="after put")

    updated = dict(body)
    updated["name"] = f"WriteTest Country {smoke_id.upper()} Updated"
    client.check(
        report,
        "PUT",
        path,
        expect={200, 201},
        json_body=updated,
        note="disposable update",
    )

    client.check(report, "DELETE", path, expect={200, 204}, note="disposable delete")


def run_write(
    client: Client,
    *,
    only: set[str] | None = None,
    do_batch: bool = True,
    do_patch: bool = True,
) -> int:
    report = Report()

    print(f"BASE={client.base}")
    print(f"APP_NAME={client.app_name}")
    print(f"ONLY={','.join(sorted(only)) if only else '(all targets)'}")
    print(f"PATCH={do_patch}  BATCH={do_batch}")
    print()

    code, providers = client.check(report, "GET", "/providers?itemsPerPage=100", note="discovery")
    if code != 200:
        print("Fail-fast: cannot list providers")
        return report.summary()

    id_to_prefix: dict[str, str] = {}
    for p in as_list(providers):
        if isinstance(p, dict) and p.get("id") and p.get("prefix"):
            id_to_prefix[str(p["id"])] = str(p["prefix"])

    code, me_providers = client.check(report, "GET", "/me/providers", note="discovery")
    if code != 200:
        print("Fail-fast: cannot list me/providers (auth?)")
        return report.summary()

    granted_ids: set[str] = set()
    for g in as_list(me_providers):
        if not isinstance(g, dict):
            continue
        pid = str(g.get("id") or "")
        if pid:
            granted_ids.add(pid)

    code, me = client.check(report, "GET", "/me", note="discovery")
    roles: list[str] = []
    can_write: set[str] = set()
    if code == 200 and isinstance(me, dict):
        raw_roles = me.get("roles") or me.get("Roles") or []
        if isinstance(raw_roles, list):
            roles = [str(r) for r in raw_roles]
        raw_write = me.get("can_write") or me.get("canWrite") or []
        if isinstance(raw_write, list):
            can_write = {str(x) for x in raw_write if x}
    is_admin = "ROLE_ADMIN" in roles
    writable_ids = set(can_write) if can_write else set(granted_ids)
    if is_admin:
        writable_ids |= set(id_to_prefix)

    print(f"Providers in DB: {', '.join(sorted(id_to_prefix)) or '(none)'}")
    print(f"can_read (/me/providers): {', '.join(sorted(granted_ids)) or '(none)'}")
    print(f"can_write: {', '.join(sorted(can_write)) or '(none — using can_read)'}")
    print(f"Admin: {is_admin}")
    print()

    run_country_write(client, report)
    print()

    targets = resolve_targets(
        only,
        id_to_prefix,
        writable_ids,
        report,
        is_admin=is_admin,
    )
    if not targets:
        report.add("SKIP", "—", "/{prefix}", "-", "no writable targets")
        return report.summary()

    print(
        "Writing against: "
        + ", ".join(f"{label}→/{prefix}" for label, prefix in targets)
    )
    print()

    for label, prefix in targets:
        print(f"--- {label} (/{prefix}) ---")
        run_writes_for_prefix(
            client,
            report,
            prefix,
            do_patch=do_patch,
            do_batch=do_batch,
        )

    return report.summary()
