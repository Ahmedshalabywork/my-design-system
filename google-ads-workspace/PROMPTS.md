# Copy-paste setup and operating prompts

Replace bracketed placeholders. Keep credentials out of prompts.

## Prompt 1: Create client workspace

```text
Set up this repository as a Google Ads decision workspace for [BUSINESS NAME].

First read AGENTS.md or CLAUDE.md and the google-ads-command-center umbrella skill. Then interview me to complete workspace/business-context.md. Gather the business goal, monthly budget, target cost per qualified lead, average contribution profit, protected campaigns, budget limits, approved ad claims with source URLs, excluded locations, CRM stages, join keys, seasonality, and prior decisions that must not be reversed.

Ask only for business facts. Do not ask me to paste credentials into chat. Do not connect accounts or recommend changes yet. Save the completed context locally, list any missing fields, and wait for my approval before continuing.
```

## Prompt 2: Connect the official Google Ads MCP

```text
Help me connect the official googleads/google-ads-mcp server to this client in read-only mode.

Verify that pipx is available, that the Google Ads API is enabled in my Google Cloud project, and that Application Default Credentials include the adwords and cloud-platform scopes. Use the example in the mcp folder for this client. Tell me where to set GOOGLE_APPLICATION_CREDENTIALS, GOOGLE_PROJECT_ID, GOOGLE_ADS_DEVELOPER_TOKEN, and GOOGLE_ADS_LOGIN_CUSTOMER_ID if a manager account is involved.

Do not ask me to paste secret values into chat. Do not write secrets into the repository. After configuration, restart the client, list the available MCP tools, call list_accessible_customers, report the accessible customer IDs, and stop. Do not run account queries or mutations yet.
```

## Prompt 3: Verify the skill package

```text
Inspect the local Google Ads skill package in this repository.

Confirm that the google-ads-command-center umbrella skill and these 12 sub-skills are available: investigation-methodology, account-diagnostic, gaql-query-patterns, change-history-checker, sqr-pipeline, ad-copy-verification-standard, conversion-tracking-health, ga4-cross-analysis, ga4-lead-quality-investigation, portfolio-health-prioritization, client-communication-standards, and mutation-safety.

Return a five-layer skill map, flag missing files or script prerequisites, and confirm that the default operating mode is read-only. Do not install packages or change the account without asking first.
```

## Prompt 4: Run the baseline account diagnostic

```text
Run a read-only baseline review for Google Ads customer ID [CUSTOMER ID] over [DATE RANGE].

Read workspace/business-context.md first. Load google-ads-command-center, investigation-methodology, account-diagnostic, gaql-query-patterns, and change-history-checker. Use the official MCP to retrieve the evidence required for the 42-point diagnostic. Inspect recent changes before explaining performance.

Return the date range, data freshness, queries used, GREEN/YELLOW/RED checks, estimated waste, confirmed findings, open hypotheses, and the five highest-priority investigations. Include source receipts. Do not create or execute mutations.
```

## Prompt 5: Review search terms and ad claims

```text
Review search terms and ad claims for Google Ads customer ID [CUSTOMER ID] over the last [30] complete days.

Load sqr-pipeline and ad-copy-verification-standard. Pull search terms with a GAQL source receipt. Classify each term in three independent passes as high intent, low intent, informational, or off-brand. Only place terms with three-run consensus into a human review table. Check geographic conflicts before recommending a negative.

For every ad claim, retrieve the relevant landing page and quote the exact supporting sentence with its URL. If the website does not support the claim, mark it blocked and leave replacement fields empty until verified copy exists.

Return review tables only. Do not upload negatives or edit ads.
```

## Prompt 6: Investigate tracking and profit

```text
Investigate tracking and business outcomes for Google Ads customer ID [CUSTOMER ID] over [DATE RANGE].

Load conversion-tracking-health, ga4-cross-analysis, and ga4-lead-quality-investigation. Read the business goal and economics from workspace/business-context.md. Audit conversion actions first. Then compare Google Ads clicks and conversions with GA4 sessions and events, form submissions, CRM qualified stages, completed sales or jobs, revenue, and contribution profit.

Document the source, freshness, join key, matched rows, and unmatched rows for every dataset. Compare campaign types using qualified rate and contribution profit, not platform CPA alone. Separate confirmed findings from hypotheses and missing data. Return prioritized recommendations with acceptance tests. Do not change tracking or campaigns.
```

## Prompt 7: Build the current-state brief

```text
Build the weekly Google Ads current-state brief for [BUSINESS NAME].

Load portfolio-health-prioritization and client-communication-standards. Use only evidence already gathered in this workspace. Rank the work by profit risk, confidence, urgency, and reversibility. Structure the brief as Background, Analysis, and Conclusions. Include what was checked, what changed, what is confirmed, what remains uncertain, and the top five actions with owners and review dates.

Append material decisions to workspace/decision-log.md. Do not claim that any recommendation was applied unless a mutation receipt exists.
```

## Prompt 8: Stage a change safely

```text
Stage, but do not execute, the following proposed Google Ads change: [CHANGE].

Load mutation-safety. Resolve the exact customer and entity IDs. Produce a dry-run preview with the current value, proposed value, entity count, evidence, risk, expected effect, acceptance test, monitoring window, and exact rollback. State which write-capable tool or script would be used.

Stop after the preview and wait for my fresh approval. Do not invent or reuse an approval phrase. Do not execute a broader scope than the preview.
```

## Prompt 9: Create a weekly read-only check

```text
Create a recurring read-only Google Ads review every Monday at 09:00 in [TIMEZONE].

Each run should read the saved business context, confirm data freshness, run the account diagnostic, inspect recent changes, review search-term waste, audit conversion health, compare qualified-lead and profit outcomes when those sources are available, and update the current-state brief.

Stay quiet when nothing material changed. Notify me only for a new high-risk finding, stale or failed data, a decision that needs human review, or a completed brief. Never execute Google Ads mutations from the recurring task.
```
