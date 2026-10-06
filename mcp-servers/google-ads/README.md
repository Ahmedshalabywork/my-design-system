# Google Ads MCP (Mecca Limo)

Local MCP server for the Google Ads API (REST, `v23`), using the owner's own OAuth credentials.

## Credentials (environment variables only, never in git or chat)

| Variable | Required | Notes |
|---|---|---|
| `GOOGLE_ADS_CLIENT_ID` | yes | OAuth client (Google Cloud project `mecca-ads-api`) |
| `GOOGLE_ADS_CLIENT_SECRET` | yes | |
| `GOOGLE_ADS_REFRESH_TOKEN` | yes | from OAuth Playground, scope `https://www.googleapis.com/auth/adwords` |
| `GOOGLE_ADS_LOGIN_CUSTOMER_ID` | recommended | manager account `3003725833` |
| `GOOGLE_ADS_CUSTOMER_ID` | optional | default client account, e.g. `2227770856` |
| `GOOGLE_ADS_DEVELOPER_TOKEN` | optional | ignored by Google since Sept 9, 2026; sent if present |

Production accounts only work once the Cloud project has Explorer (or higher) API access.

## Tools

- `check_connection`: which credentials are present (booleans only) + accessible accounts
- `gaql_search`: read-only GAQL query
- `list_conversion_actions`
- `set_conversion_action_primary`: Primary / Secondary
- `create_website_conversion_action`: returns the gtag snippet after a real create
- `mutate`: any service, REST operation shape

All write tools default to `validate_only=true` (Google checks the change without applying it).

## Run

Registered in the repo's `.mcp.json`; Claude Code starts it automatically. Manual run:

```
python3 mcp-servers/google-ads/server.py
```

Needs `mcp` and `httpx` (`pip install -r mcp-servers/google-ads/requirements.txt`).
