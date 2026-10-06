# Safety model

## What the official MCP can do

Google's official [Google Ads MCP](https://github.com/googleads/google-ads-mcp) currently exposes these tools:

- `search` for read-only Google Ads queries
- `get_resource_metadata` for resource fields and schemas
- `list_accessible_customers` for customer discovery

It does not expose a general campaign mutation tool. This repository never claims that the official server can push budget, targeting, keyword, ad, or conversion changes.

## What this package does with changes

The Command Center can recommend and stage changes. A real execution path requires a separate, explicitly configured write-capable tool or script. Some bundled PPC skill folders include such scripts.

Before any write:

1. Identify the exact customer ID and entity IDs.
2. Produce a dry-run preview with before and after values.
3. State the evidence, risk, acceptance test, and rollback.
4. Stop and wait for a fresh human approval of that exact preview.
5. Execute only the approved scope.
6. Read the affected entities back from Google Ads.
7. Save the mutation receipt in the decision log.

Never treat a broad request such as “optimize the account” as approval to write.

## Credential rules

- Never paste developer tokens, OAuth secrets, credential JSON, or refresh tokens into an AI chat.
- Never commit secrets to this repository.
- Use environment variables or a local secret manager.
- Keep OAuth credential files outside the repository.
- Start with a test account or a low-risk account you control.
- Use the least Google Ads access required.
- Remove former users and manager accounts from Google Ads access regularly.

## Data rules

- Google Ads data is not the same as business outcome data.
- GA4, form, CRM, revenue, and profit sources need their own access controls.
- Document every join key and unmatched row rate.
- Remove personal data before sharing exports with an AI system.
- Treat low match rates as uncertainty, not proof of poor campaign performance.

## Repository disclosure

The included browser Command Center uses illustrative values and does not contact a real Google Ads account. It is a working interface and filming artifact, not a live connector.
