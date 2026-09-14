"""Read/smoke probes for the live OCHA API. Never calls /archive."""

from __future__ import annotations

from typing import Any
from urllib.parse import urlencode

from common import Client, Report, as_list


def first_id(items: list[Any], *keys: str) -> str | None:
    if not items:
        return None
    key_order = keys or ("value", "id", "label", "year", "@id")
    for item in items:
        if isinstance(item, str) and item.strip():
            return item
        if isinstance(item, dict):
            for k in key_order:
                if k in item and item[k] is not None and item[k] != "":
                    val = item[k]
                    if isinstance(val, str) and val.startswith("/api/"):
                        return val.rstrip("/").split("/")[-1]
                    return str(val)
    return None


def pick_filter_values(items: list[Any]) -> tuple[str | None, str | None]:
    for item in items:
        if not isinstance(item, dict):
            continue
        iso3 = item.get("iso3")
        year = item.get("year")
        if iso3 and year:
            return str(iso3), str(year)
    return None, None


def run_ocha_chain(client: Client, report: Report, root: str) -> None:
    code, data = client.check(report, "GET", f"/{root}/ocha-presences")
    items = as_list(data)
    presence_id = first_id(items, "id", "value", "label")
    if not presence_id:
        report.add("SKIP", "GET", f"/{root}/ocha-presences/{{id}}", "-", "no ocha presence data")
        return

    client.check(report, "GET", f"/{root}/ocha-presences/{presence_id}")
    code, years = client.check(report, "GET", f"/{root}/ocha-presences/{presence_id}/years")
    year_items = as_list(years)
    year = first_id(year_items, "id", "value", "label", "year")
    if not year:
        report.add("SKIP", "GET", f"/{root}/ocha-presences/{presence_id}/{{year}}/figures", "-", "no years")
        return
    client.check(
        report,
        "GET",
        f"/{root}/ocha-presences/{presence_id}/{year}/figures?itemsPerPage=5",
    )


def run_key_figures_root(client: Client, report: Report, root: str) -> None:
    client.check(report, "GET", f"/{root}/years")
    client.check(report, "GET", f"/{root}/countries")
    code, data = client.check(report, "GET", f"/{root}?itemsPerPage=5")
    items = as_list(data)
    kid = first_id(items, "id")
    if kid:
        client.check(report, "GET", f"/{root}/{kid}")
        iso3, year = pick_filter_values(items)
        if iso3 and year:
            qs = urlencode({"iso3": iso3, "year": year, "itemsPerPage": 5})
            client.check(report, "GET", f"/{root}?{qs}", note="filter iso3+year")
    else:
        report.add("SKIP", "GET", f"/{root}/{{id}}", "-", "empty collection")
    run_ocha_chain(client, report, root)


def run_read(client: Client, *, jsonld: bool = False) -> int:
    report = Report()

    print(f"BASE={client.base}")
    print(f"APP_NAME={client.app_name}")
    print(f"JSONLD={jsonld}")
    print()

    code, providers = client.check(report, "GET", "/providers?itemsPerPage=100", note="discovery")
    if code != 200:
        print("Fail-fast: cannot list providers")
        return report.summary()

    provider_rows = as_list(providers)
    id_to_prefix: dict[str, str] = {}
    for p in provider_rows:
        if isinstance(p, dict) and p.get("id") and p.get("prefix"):
            id_to_prefix[str(p["id"])] = str(p["prefix"])

    code, me_providers = client.check(report, "GET", "/me/providers", note="discovery")
    if code != 200:
        print("Fail-fast: cannot list me/providers (auth?)")
        return report.summary()

    granted = as_list(me_providers)
    granted_prefixes: list[str] = []
    for g in granted:
        if not isinstance(g, dict):
            continue
        pid = str(g.get("id") or "")
        prefix = str(g.get("prefix") or id_to_prefix.get(pid) or "")
        if prefix:
            granted_prefixes.append(prefix)
    granted_prefixes = sorted(set(granted_prefixes))
    print(f"Granted prefixes: {', '.join(granted_prefixes) or '(none)'}")
    print()

    code, me, _raw = client.request("GET", "/me")
    if code == 200 and isinstance(me, dict) and (
        me.get("id") is not None or me.get("username") or me.get("email")
    ):
        report.add("PASS", "GET", "/me", code, "user object")
    elif code == 200:
        report.add(
            "FAIL",
            "GET",
            "/me",
            code,
            f"expected user object, got {type(me).__name__}: {str(me)[:80]}",
        )
    else:
        report.add("FAIL", "GET", "/me", code, "expected 200 user object")

    client.check(
        report,
        "GET",
        "/me/providers",
        expect={401},
        include_api_key=False,
        note="no API-KEY",
    )
    bad = Client(client.base, "definitely-not-a-valid-token", client.app_name)
    bad.check(report, "GET", "/me/providers", expect={401}, note="bad API-KEY")
    client.check(
        report,
        "GET",
        "/key_figures?itemsPerPage=1",
        expect={400},
        include_app_name=False,
        note="json without APP-NAME",
    )

    docs_client = Client(
        client.base, client.api_key, client.app_name, accept="application/vnd.openapi+json"
    )
    docs_client.check(
        report,
        "GET",
        "/docs",
        expect={200},
        include_app_name=False,
        note="openapi",
    )

    if provider_rows:
        pid = first_id(provider_rows, "id")
        if pid:
            client.check(report, "GET", f"/providers/{pid}")

    code, countries = client.check(report, "GET", "/countries?itemsPerPage=5")
    cid = first_id(as_list(countries), "id")
    if cid:
        client.check(report, "GET", f"/countries/{cid}")
    else:
        report.add("SKIP", "GET", "/countries/{id}", "-", "no countries")

    code, op = client.check(report, "GET", "/ocha_presences?itemsPerPage=5")
    op_items = as_list(op)
    oid = first_id(op_items, "id", "value")
    if oid:
        client.check(report, "GET", f"/ocha_presences/{oid}")
    elif isinstance(op, list) and op and not op_items:
        report.add("FAIL", "GET", "/ocha_presences/{id}", "-", "collection items empty/unusable")
    else:
        report.add("SKIP", "GET", "/ocha_presences/{id}", "-", "no ocha_presences")

    code, ope = client.check(report, "GET", "/ocha_presence_external_ids?itemsPerPage=5")
    ope_items = as_list(ope)
    eid = first_id(ope_items, "id", "value")
    if eid:
        client.check(report, "GET", f"/ocha_presence_external_ids/{eid}")
    elif isinstance(ope, list) and ope and not ope_items:
        report.add(
            "FAIL", "GET", "/ocha_presence_external_ids/{id}", "-", "collection items empty/unusable"
        )
    else:
        report.add("SKIP", "GET", "/ocha_presence_external_ids/{id}", "-", "empty")

    code, el = client.check(report, "GET", "/external_lookups?itemsPerPage=5")
    el_items = as_list(el)
    elid = first_id(el_items, "id", "value")
    if elid:
        client.check(report, "GET", f"/external_lookups/{elid}")
        client.check(report, "GET", f"/external_lookups/{elid}/versions")
    else:
        report.add("SKIP", "GET", "/external_lookups/{id}", "-", "empty")

    client.check(
        report,
        "GET",
        "/n8n/health",
        expect={200},
        include_app_name=False,
        note="legacy Route attribute may 404",
    )
    for path in (
        "/n8n/templates/categories?itemsPerPage=5",
        "/n8n/templates/collections?itemsPerPage=5",
        "/n8n/templates/workflows?itemsPerPage=5",
    ):
        client.check(report, "GET", path, include_app_name=False)

    run_key_figures_root(client, report, "key_figures")
    for prefix in granted_prefixes:
        run_key_figures_root(client, report, prefix)

    if jsonld:
        print()
        print("--- JSON-LD subset ---")
        ld = Client(client.base, client.api_key, client.app_name, accept="application/ld+json")
        ld.check(report, "GET", "/me/providers", include_app_name=False, note="jsonld")
        ld.check(report, "GET", "/providers?itemsPerPage=5", include_app_name=False, note="jsonld")
        if granted_prefixes:
            ld.check(
                report,
                "GET",
                f"/{granted_prefixes[0]}?itemsPerPage=2",
                include_app_name=False,
                note="jsonld",
            )
        ld.check(
            report,
            "GET",
            "/key_figures?itemsPerPage=1",
            include_app_name=False,
            expect={200},
            note="jsonld no APP-NAME ok",
        )

    return report.summary()
