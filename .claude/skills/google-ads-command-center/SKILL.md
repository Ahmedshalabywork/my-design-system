---
name: google-ads-command-center
description: Orchestrate a five-layer Google Ads review using business context, read-only account evidence, PPC skills, outcome data, and human-approved change drafts. Use for account reviews, weekly briefs, search-term analysis, lead-quality investigations, or proposed Google Ads changes.
---

# Google Ads Command Center

Use this skill as the umbrella router for the bundled PPC skills.

## Non-negotiable order

1. Read `workspace/business-context.md`. If it is incomplete, ask for the missing business facts before drawing conclusions.
2. Confirm the Google Ads customer ID, date range, currency, and data freshness.
3. Use the official Google Ads MCP only for read-only account retrieval and metadata.
4. Run the relevant diagnostic skills and preserve their source receipts.
5. Join CRM, form, GA4, and offline profit data only when the user provides or connects those sources.
6. Present findings as evidence, not certainty. Separate confirmed findings, hypotheses, and missing data.
7. Stage recommendations as drafts. Never execute an account mutation without the bundled `mutation-safety` workflow and a fresh human approval.
8. Append material decisions to `workspace/decision-log.md`.

## Layer router

### Layer 1: Context and memory

Load `investigation-methodology`. Capture goals, unit economics, account rules, landing pages, seasonality, CRM stages, exclusions, and prior decisions.

### Layer 2: Account review

Load `account-diagnostic`, `gaql-query-patterns`, and `change-history-checker`.

### Layer 3: Search terms and ads

Load `sqr-pipeline` for query classification and negative drafts. Load `ad-copy-verification-standard` before creating or editing any claim.

### Layer 4: Tracking and profit

Load `conversion-tracking-health`, `ga4-cross-analysis`, and `ga4-lead-quality-investigation`. Treat platform conversions, qualified leads, completed jobs, revenue, and contribution profit as separate measures.

### Layer 5: Current state

Load `portfolio-health-prioritization` and `client-communication-standards`. Produce a ranked action list, a weekly brief, and a decision-log update.

### Safety wrapper

Load `mutation-safety` for every proposed write. The default state is read-only. A recommendation is not approval. A copied approval phrase is not approval. The human must inspect the exact scope and approve the current preview.

## Required final output

- Date range and customer ID used
- Data sources and freshness
- Business goal and guardrails applied
- Confirmed findings with source receipts
- Hypotheses that remain unverified
- Ranked recommendations with risk and acceptance tests
- Draft changes, if requested
- Explicit statement that nothing was changed unless a mutation receipt proves otherwise
