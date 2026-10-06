"""Mecca Limo Google Ads MCP server.

A small MCP server that talks to the Google Ads API (REST) with the owner's own
OAuth credentials. Credentials come only from environment variables and are
never returned by any tool:

    GOOGLE_ADS_CLIENT_ID
    GOOGLE_ADS_CLIENT_SECRET
    GOOGLE_ADS_REFRESH_TOKEN
    GOOGLE_ADS_LOGIN_CUSTOMER_ID   (manager account, digits only; optional)
    GOOGLE_ADS_DEVELOPER_TOKEN     (optional since Sept 2026; sent if present)
    GOOGLE_ADS_CUSTOMER_ID         (default client account, digits only; optional)
    GOOGLE_ADS_API_VERSION         (default v23)

Every write tool defaults to validate_only=True: Google checks the change
without applying it. Pass validate_only=False to apply.
"""

from __future__ import annotations

import json
import os
import re
import time
from typing import Any

import httpx
from mcp.server.fastmcp import FastMCP

API_VERSION = os.environ.get("GOOGLE_ADS_API_VERSION", "v23")
BASE = f"https://googleads.googleapis.com/{API_VERSION}"
TOKEN_URL = "https://oauth2.googleapis.com/token"

mcp = FastMCP("google-ads")

_token: dict[str, Any] = {"value": None, "expires": 0.0}


class AdsError(Exception):
    pass


def _env(name: str, required: bool = True) -> str | None:
    value = os.environ.get(name, "").strip()
    if required and not value:
        raise AdsError(f"Missing environment variable {name}. Add it in the environment settings and start a new session.")
    return value or None


def _digits(value: str | None) -> str | None:
    return re.sub(r"\D", "", value) if value else None


def _customer(customer_id: str | None) -> str:
    cid = _digits(customer_id) or _digits(_env("GOOGLE_ADS_CUSTOMER_ID", required=False))
    if not cid:
        raise AdsError("No customer_id given and GOOGLE_ADS_CUSTOMER_ID is not set.")
    return cid


def _access_token() -> str:
    if _token["value"] and time.time() < _token["expires"] - 60:
        return _token["value"]
    resp = httpx.post(
        TOKEN_URL,
        data={
            "client_id": _env("GOOGLE_ADS_CLIENT_ID"),
            "client_secret": _env("GOOGLE_ADS_CLIENT_SECRET"),
            "refresh_token": _env("GOOGLE_ADS_REFRESH_TOKEN"),
            "grant_type": "refresh_token",
        },
        timeout=30,
    )
    if resp.status_code != 200:
        # Never echo credentials; Google's error body does not contain them.
        raise AdsError(f"OAuth token refresh failed ({resp.status_code}): {resp.text[:300]}")
    data = resp.json()
    _token["value"] = data["access_token"]
    _token["expires"] = time.time() + int(data.get("expires_in", 3600))
    return _token["value"]


def _headers() -> dict[str, str]:
    headers = {"Authorization": f"Bearer {_access_token()}", "Content-Type": "application/json"}
    dev = _env("GOOGLE_ADS_DEVELOPER_TOKEN", required=False)
    if dev:
        headers["developer-token"] = dev
    login = _digits(_env("GOOGLE_ADS_LOGIN_CUSTOMER_ID", required=False))
    if login:
        headers["login-customer-id"] = login
    return headers


def _request(method: str, path: str, body: dict | None = None) -> Any:
    resp = httpx.request(method, f"{BASE}/{path}", headers=_headers(), json=body, timeout=60)
    try:
        data = resp.json()
    except ValueError:
        data = {"raw": resp.text[:2000]}
    if resp.status_code >= 400:
        raise AdsError(json.dumps({"status": resp.status_code, "error": data}, indent=2)[:4000])
    return data


def _search(customer_id: str, query: str, max_rows: int = 1000) -> list[dict]:
    rows: list[dict] = []
    body: dict[str, Any] = {"query": query}
    while True:
        data = _request("POST", f"customers/{customer_id}/googleAds:search", body)
        rows.extend(data.get("results", []))
        token = data.get("nextPageToken")
        if not token or len(rows) >= max_rows:
            return rows[:max_rows]
        body = {"query": query, "pageToken": token}


def _mutate(customer_id: str, service: str, operations: list[dict], validate_only: bool) -> Any:
    return _request(
        "POST",
        f"customers/{customer_id}/{service}:mutate",
        {"operations": operations, "validateOnly": validate_only, "partialFailure": False},
    )


def _result(payload: Any) -> str:
    return json.dumps(payload, indent=2, default=str)


@mcp.tool()
def check_connection() -> str:
    """Check which credentials are present (never their values), refresh an access
    token, and list the customer accounts these credentials can reach."""
    present = {
        name: bool(os.environ.get(name, "").strip())
        for name in (
            "GOOGLE_ADS_CLIENT_ID",
            "GOOGLE_ADS_CLIENT_SECRET",
            "GOOGLE_ADS_REFRESH_TOKEN",
            "GOOGLE_ADS_LOGIN_CUSTOMER_ID",
            "GOOGLE_ADS_DEVELOPER_TOKEN",
            "GOOGLE_ADS_CUSTOMER_ID",
        )
    }
    try:
        data = _request("GET", "customers:listAccessibleCustomers")
        return _result({"credentials_present": present, "api_version": API_VERSION, "accessible_customers": data.get("resourceNames", [])})
    except AdsError as exc:
        return _result({"credentials_present": present, "api_version": API_VERSION, "error": str(exc)})


@mcp.tool()
def gaql_search(query: str, customer_id: str | None = None, max_rows: int = 500) -> str:
    """Run a read-only GAQL query, e.g.
    SELECT campaign.id, campaign.name, metrics.cost_micros FROM campaign WHERE segments.date DURING LAST_30_DAYS"""
    try:
        return _result(_search(_customer(customer_id), query, max_rows))
    except AdsError as exc:
        return f"ERROR: {exc}"


@mcp.tool()
def list_conversion_actions(customer_id: str | None = None) -> str:
    """List conversion actions with type, category, status, primary/secondary and counting."""
    query = (
        "SELECT conversion_action.id, conversion_action.name, conversion_action.type, "
        "conversion_action.category, conversion_action.status, conversion_action.primary_for_goal, "
        "conversion_action.counting_type, conversion_action.origin "
        "FROM conversion_action WHERE conversion_action.status != 'REMOVED'"
    )
    try:
        return _result(_search(_customer(customer_id), query))
    except AdsError as exc:
        return f"ERROR: {exc}"


@mcp.tool()
def set_conversion_action_primary(conversion_action_id: str, primary: bool, customer_id: str | None = None, validate_only: bool = True) -> str:
    """Make a conversion action Primary (used for bidding) or Secondary (reporting only).
    validate_only=True only checks the change; pass False to apply it."""
    try:
        cid = _customer(customer_id)
        op = {
            "update": {"resourceName": f"customers/{cid}/conversionActions/{_digits(conversion_action_id)}", "primaryForGoal": primary},
            "updateMask": "primaryForGoal",
        }
        return _result({"validate_only": validate_only, "response": _mutate(cid, "conversionActions", [op], validate_only)})
    except AdsError as exc:
        return f"ERROR: {exc}"


@mcp.tool()
def create_website_conversion_action(
    name: str,
    category: str = "SUBMIT_LEAD_FORM",
    counting_type: str = "ONE_PER_CLICK",
    primary: bool = True,
    click_through_days: int = 30,
    customer_id: str | None = None,
    validate_only: bool = True,
) -> str:
    """Create a WEBPAGE conversion action (e.g. 'Quote form submitted').
    After a real (validate_only=False) create, returns the gtag event snippet so it can be
    added to the website. Categories: SUBMIT_LEAD_FORM, CONTACT, BOOK_APPOINTMENT, REQUEST_QUOTE, PHONE_CALL_LEAD..."""
    try:
        cid = _customer(customer_id)
        op = {
            "create": {
                "name": name,
                "type": "WEBPAGE",
                "category": category,
                "status": "ENABLED",
                "countingType": counting_type,
                "primaryForGoal": primary,
                "clickThroughLookbackWindowDays": click_through_days,
                "valueSettings": {"defaultValue": 0, "alwaysUseDefaultValue": False},
            }
        }
        response = _mutate(cid, "conversionActions", [op], validate_only)
        out: dict[str, Any] = {"validate_only": validate_only, "response": response}
        if not validate_only:
            resource = response["results"][0]["resourceName"]
            rows = _search(
                cid,
                "SELECT conversion_action.tag_snippets FROM conversion_action "
                f"WHERE conversion_action.resource_name = '{resource}'",
            )
            out["tag_snippets"] = rows[0]["conversionAction"].get("tagSnippets") if rows else None
        return _result(out)
    except AdsError as exc:
        return f"ERROR: {exc}"


@mcp.tool()
def mutate(service: str, operations: list[dict], customer_id: str | None = None, validate_only: bool = True) -> str:
    """Generic Google Ads mutate for any service, e.g. service='campaigns', 'adGroups',
    'adGroupCriteria', 'campaignCriteria', 'conversionActions', 'campaignBudgets', 'assets'.
    `operations` uses the REST JSON shape: [{"update": {...}, "updateMask": "..."}] or [{"create": {...}}]
    or [{"remove": "customers/.../..."}]. validate_only=True only checks; pass False to apply."""
    try:
        cid = _customer(customer_id)
        return _result({"validate_only": validate_only, "response": _mutate(cid, service, operations, validate_only)})
    except AdsError as exc:
        return f"ERROR: {exc}"


if __name__ == "__main__":
    mcp.run()
