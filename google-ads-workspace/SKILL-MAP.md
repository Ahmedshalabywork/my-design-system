# The five-layer skill map

The repository includes one local umbrella skill and 12 complete skill folders from [fourteenwm/ppc-ai-skills](https://github.com/fourteenwm/ppc-ai-skills). The upstream folders are included at commit `90f0e419f2d820789d5e50eee1237ab9fd14dfc6` under both `.agents/skills` and `.claude/skills`.

## Umbrella

| Skill | Role |
| --- | --- |
| `google-ads-command-center` | Routes work through the five layers, insists on business context, keeps the official MCP read-only, and sends every proposed write through Mutation Safety. |

## Layer 1: Context and memory

| Skill | What it contributes |
| --- | --- |
| `investigation-methodology` | Defines the problem, forms hypotheses before analysis, gathers evidence one layer at a time, and separates confirmed root causes from guesses. |
| Workspace files | `workspace/business-context.md` holds goals, economics, rules, data sources, and prior decisions. |

## Layer 2: Account review

| Skill | What it contributes |
| --- | --- |
| `account-diagnostic` | A 42-point read-only account inspection across 14 categories, with a verdict and estimated waste. |
| `gaql-query-patterns` | Copy-ready GAQL patterns for campaign, search-term, conversion, geography, device, and account analysis. |
| `change-history-checker` | Queries up to 90 days of change history and helps distinguish what changed from who changed it. |

## Layer 3: Search terms and ads

| Skill | What it contributes |
| --- | --- |
| `sqr-pipeline` | Pulls search terms, runs three independent intent classifications, builds a consensus review set, and stages approved negatives. |
| `ad-copy-verification-standard` | Requires every ad claim to have a source URL. If the website does not support a claim, the field stays empty. |

## Layer 4: Tracking and profit

| Skill | What it contributes |
| --- | --- |
| `conversion-tracking-health` | Finds conversion actions that are inactive, stale, or unsafe as primary bidding signals. |
| `ga4-cross-analysis` | Structures Google Ads plus GA4 evidence for landing-page, audience, and conversion-path analysis. |
| `ga4-lead-quality-investigation` | Compares ad settings with GA4 and downstream lead outcomes, then ranks evidence-backed fixes. |

The Google Ads MCP does not provide GA4, form, CRM, revenue, or profit data. Connect or import those sources separately and document the join keys used.

## Layer 5: Current state

| Skill | What it contributes |
| --- | --- |
| `portfolio-health-prioritization` | Ranks accounts and actions into five priority tiers. |
| `client-communication-standards` | Converts evidence into a Background, Analysis, Conclusions brief with source attribution. |
| Decision log | `workspace/decision-log.md` records proposed, approved, rejected, applied, and rolled-back decisions. |

## Wrapper: Mutation Safety

| Skill | What it contributes |
| --- | --- |
| `mutation-safety` | Requires an exact dry-run preview, a separate human approval, scope verification, execution receipt, acceptance test, and rollback. |

Some upstream skill folders contain scripts capable of changing Google Ads. Their presence does not grant permission to run them. Read [SAFETY.md](SAFETY.md) before using any mutation path.
