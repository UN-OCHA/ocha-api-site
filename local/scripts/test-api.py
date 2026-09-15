#!/usr/bin/env python3
"""
Local OCHA API probes (read / write). Not for CI/PHPUnit.

Usage (from repo root):

  cp local/scripts/.env.api.example local/scripts/.env.api
  # set API_KEY

  python3 local/scripts/test-api.py read
  python3 local/scripts/test-api.py read --jsonld
  python3 local/scripts/test-api.py write [--only fts,cbpf] [--no-batch] [--no-patch]
  python3 local/scripts/test-api.py write --api-key YOUR_ADMIN_TOKEN
  python3 local/scripts/test-api.py all --api-key YOUR_ADMIN_TOKEN

Never calls /archive.
"""

from __future__ import annotations

import argparse
import sys
from pathlib import Path

# Allow `python3 local/scripts/test-api.py` without installing a package.
sys.path.insert(0, str(Path(__file__).resolve().parent))

from common import Client, DEFAULT_ENV_FILE, resolve_credentials  # noqa: E402
from read import run_read  # noqa: E402
from write import run_write  # noqa: E402


def _credentials_parser() -> argparse.ArgumentParser:
    """Shared flags so they work after the subcommand (e.g. write --api-key …)."""
    creds = argparse.ArgumentParser(add_help=False)
    creds.add_argument(
        "--env-file",
        type=Path,
        default=argparse.SUPPRESS,
        help="Env file with BASE_URL/API_KEY/APP_NAME",
    )
    creds.add_argument("--base-url", default=argparse.SUPPRESS, help="Override BASE_URL")
    creds.add_argument("--api-key", default=argparse.SUPPRESS, help="Override API_KEY")
    creds.add_argument("--app-name", default=argparse.SUPPRESS, help="Override APP_NAME")
    return creds


def build_parser() -> argparse.ArgumentParser:
    creds = _credentials_parser()
    parser = argparse.ArgumentParser(
        description="Local OCHA API read/write probes.",
        parents=[creds],
    )

    sub = parser.add_subparsers(dest="command", required=True)

    p_read = sub.add_parser(
        "read",
        parents=[creds],
        help="GET discovery and collection smoke",
    )
    p_read.add_argument("--jsonld", action="store_true", help="Also probe a JSON-LD subset")

    p_write = sub.add_parser(
        "write",
        parents=[creds],
        help="Disposable PUT/PATCH/batch/DELETE per provider",
    )
    p_write.add_argument(
        "--only",
        default="",
        help="Comma-separated provider ids or prefixes (e.g. fts,cbpf)",
    )
    p_write.add_argument("--no-batch", action="store_true")
    p_write.add_argument("--no-patch", action="store_true")

    p_all = sub.add_parser("all", parents=[creds], help="Run read then write")
    p_all.add_argument("--jsonld", action="store_true")
    p_all.add_argument("--only", default="")
    p_all.add_argument("--no-batch", action="store_true")
    p_all.add_argument("--no-patch", action="store_true")

    return parser


def main(argv: list[str] | None = None) -> int:
    parser = build_parser()
    args = parser.parse_args(argv)

    base_url, api_key, app_name, env_file = resolve_credentials(
        env_file=getattr(args, "env_file", DEFAULT_ENV_FILE),
        base_url=getattr(args, "base_url", None),
        api_key=getattr(args, "api_key", None),
        app_name=getattr(args, "app_name", None),
    )
    if not api_key:
        print(
            "Missing API_KEY. Copy local/scripts/.env.api.example to local/scripts/.env.api",
            file=sys.stderr,
        )
        print("and set API_KEY, or pass --api-key / export API_KEY.", file=sys.stderr)
        return 2

    print(f"ENV_FILE={env_file} ({'found' if env_file.is_file() else 'missing'})")
    client = Client(base_url, api_key, app_name)

    if args.command == "read":
        return run_read(client, jsonld=args.jsonld)

    only = {x.strip() for x in getattr(args, "only", "").split(",") if x.strip()} or None
    do_batch = not getattr(args, "no_batch", False)
    do_patch = not getattr(args, "no_patch", False)

    if args.command == "write":
        return run_write(client, only=only, do_batch=do_batch, do_patch=do_patch)

    # all
    read_rc = run_read(client, jsonld=args.jsonld)
    print()
    print("=== WRITE ===")
    print()
    write_rc = run_write(client, only=only, do_batch=do_batch, do_patch=do_patch)
    return 1 if (read_rc or write_rc) else 0


if __name__ == "__main__":
    sys.exit(main())
