# Claude Ads Audit Report

> Run completeness: **Partial** · Evidence status: **Insufficient evidence**

## Run summary

- Run ID: 2026\-10\-06\-google\-mecca\-limo
- Started: 2026\-10\-06T19:30:00Z
- Platform: Google
- Account: Mecca Limo Charleston car services
- Window: 2026\-09\-06 to 2026\-10\-06
- Privacy class: Internal

## Decision status

- Run completeness: **Partial**
- Evidence status: **Insufficient evidence**
- Health score: **Not scored**
- Evidence coverage: **0.00%**

> WARNING: Required work did not complete; this report must not be presented as a complete audit.

> WARNING: Evidence is insufficient for a defensible health score.

## Category health

No category scores were supplied.

## Findings

### [PASS] G\-AD1 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** All RSAs were created 2026\-10\-05/06\.

**Diagnosis:** Fresh\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [FAIL] G\-CT1 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** The 'Call' website conversion \(primary\) fires on every tel: click AND on every form submit; 'Quote form submitted' \(primary\) also fires on the quote form\.

**Diagnosis:** Once quotes start recording, one quote request counts as two primary conversions, and a tap on the phone number counts the same as a booked lead\.

**Recommended action:** In Google Tag Manager, restrict the 'Call' tag trigger to tel: link clicks only \(remove the form\-submit trigger\)\. Leave 'Quote form submitted' as the only form conversion\.

**Evidence:**

1.

        {"biddable_goals":["PHONE_CALL_LEAD/CALL_FROM_ADS","SUBMIT_LEAD_FORM/WEBSITE","CONTACT/WEBSITE","CONTACT/CALL_FROM_ADS"],"primary_in_conversions":[{"category":"CONTACT","counting":"ONE_PER_CLICK","fires_on":"GTM: tel: link clicks AND any form submit","id":"6562074192","name":"Call","type":"WEBPAGE"},{"category":"SUBMIT_LEAD_FORM","counting":"ONE_PER_CLICK","fires_on":"quote-email success only (installed 2026-10-05)","id":"7824071425","name":"Quote form submitted","type":"WEBPAGE"},{"category":"PHONE_CALL_LEAD","counting":"ONE_PER_CLICK","id":"6575000075","name":"Calls from ads","type":"AD_CALL"},{"id":"6559826815","name":"Smart campaign ad clicks to call","type":"SMART_CAMPAIGN_AD_CLICKS_TO_CALL"},{"id":"6559828291","name":"Calls from Smart Campaign Ads","type":"SMART_CAMPAIGN_TRACKED_CALLS"}],"ref":"gaql:conversion_action"}

2.

        {"event":"AW-11250744864/WbwNCIG255IdEKD84vQp","ref":"site:wp-content/novamira-sandbox/mecca-ads-quote-conversion.php","tested":"fires only after a real quote email","user_data":"hashed-by-Google email/phone via gtag set user_data"}

### [UNKNOWN] G\-CT2 — Unclassified

- Severity: Informational
- Confidence: None
- Source classification: Practitioner

**Observation:** GA4 link status was not checked\.

**Diagnosis:** Not evaluated\.

**Recommended action:** Confirm the GA4 property is linked so landing\-page engagement can be compared by ad group\.

**Evidence:**

No evidence was supplied.

### [PASS] G\-CT3 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** The 'Call' tag records conversions\. The quote conversion tag was tested and fires only on a successful quote email\.

**Diagnosis:** The tags are working\. 'Quote form submitted' has not recorded yet because no real quote has come through since install\.

**Recommended action:** Confirm the first real quote shows up as a conversion within 24 hours\.

**Evidence:**

1.

        {"note":"No other action recorded a conversion in 30 days (Quote form submitted: 0, Calls from ads: 0)","ref":"gaql:campaign x segments.conversion_action:LAST_30_DAYS","rows":[{"action":"Call","all_conversions":67,"conversions":67}]}

2.

        {"event":"AW-11250744864/WbwNCIG255IdEKD84vQp","ref":"site:wp-content/novamira-sandbox/mecca-ads-quote-conversion.php","tested":"fires only after a real quote email","user_data":"hashed-by-Google email/phone via gtag set user_data"}

### [FAIL] G\-KW1 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** About 14 keywords are RARELY\_SERVED \(low search volume\) in the Wedding and Party Bus groups\.

**Diagnosis:** Harmless clutter\. They do not spend\.

**Recommended action:** Leave them for now\. Pause any still rarely served on 2026\-10\-20\.

**Evidence:**

1.

        {"avg_qs":3.0,"broad":0,"exact":58,"phrase":63,"rarely_served":"14 in sampled rows (Party Bus 8, Wedding 6)","ref":"gaql:ad_group_criterion:KEYWORD","total_positive":121,"with_quality_score":2}

### [NOT APPLICABLE] G\-WS1 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** The new keywords started serving on 2026\-10\-06\.

**Diagnosis:** Too new to judge zero\-conversion keywords\.

**Recommended action:** Evaluate on 2026\-10\-20\.

**Evidence:**

1.

        {"2026-10-06":{"Airport - CHS":{"clicks":1,"cost":3.37,"impr":65},"Black Car & Corporate":{"clicks":9,"cost":22.99,"impr":184},"Cruise Port Transportation":{"impr":0},"Kiawah & Seabrook":{"clicks":3,"cost":9.06,"impr":26},"Party Bus & Bachelorette":{"clicks":2,"cost":8.81,"impr":21},"Wedding":{"impr":1}},"enabled":{"199546322383":"Party Bus & Bachelorette","200715740053":"Wedding","203721150471":"Black Car & Corporate (+ brand keywords)","203915984314":"Cruise Port Transportation","206477164768":"Kiawah & Seabrook","209278444748":"Airport - CHS"},"first_serving_day":"2026-10-06","old_catch_all_156718849648":"paused; carried all traffic through 2026-10-05","ref":"gaql:ad_group:2026-10-01..06"}

### [PASS] G03 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Six themed ad groups: Airport, Wedding, Party Bus, Kiawah/Seabrook, Black Car/Corporate, Cruise\.

**Diagnosis:** Coherent themes with matching RSAs\.

**Recommended action:** No change\.

**Evidence:**

1.

        {"2026-10-06":{"Airport - CHS":{"clicks":1,"cost":3.37,"impr":65},"Black Car & Corporate":{"clicks":9,"cost":22.99,"impr":184},"Cruise Port Transportation":{"impr":0},"Kiawah & Seabrook":{"clicks":3,"cost":9.06,"impr":26},"Party Bus & Bachelorette":{"clicks":2,"cost":8.81,"impr":21},"Wedding":{"impr":1}},"enabled":{"199546322383":"Party Bus & Bachelorette","200715740053":"Wedding","203721150471":"Black Car & Corporate (+ brand keywords)","203915984314":"Cruise Port Transportation","206477164768":"Kiawah & Seabrook","209278444748":"Airport - CHS"},"first_serving_day":"2026-10-06","old_catch_all_156718849648":"paused; carried all traffic through 2026-10-05","ref":"gaql:ad_group:2026-10-01..06"}

2.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [PASS] G04 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** One Search campaign holds all the budget\.

**Diagnosis:** Not fragmented; good for the learning signal\.

**Recommended action:** No change\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [FAIL] G05 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Brand keywords \(mecca limo, etc\.\) sit in the 'Black Car & Corporate' ad group with non\-brand terms, under Maximize Conversions\.

**Diagnosis:** Cheap brand conversions blend into non\-brand performance and can make the ad group look better than it is\.

**Recommended action:** Move brand keywords into their own 'Brand \- Mecca Limo' ad group with a brand RSA \(draft mutation M1\)\.

**Evidence:**

1.

        {"avg_qs":3.0,"broad":0,"exact":58,"phrase":63,"rarely_served":"14 in sampled rows (Party Bus 8, Wedding 6)","ref":"gaql:ad_group_criterion:KEYWORD","total_positive":121,"with_quality_score":2}

2.

        {"2026-10-06":{"Airport - CHS":{"clicks":1,"cost":3.37,"impr":65},"Black Car & Corporate":{"clicks":9,"cost":22.99,"impr":184},"Cruise Port Transportation":{"impr":0},"Kiawah & Seabrook":{"clicks":3,"cost":9.06,"impr":26},"Party Bus & Bachelorette":{"clicks":2,"cost":8.81,"impr":21},"Wedding":{"impr":1}},"enabled":{"199546322383":"Party Bus & Bachelorette","200715740053":"Wedding","203721150471":"Black Car & Corporate (+ brand keywords)","203915984314":"Cruise Port Transportation","206477164768":"Kiawah & Seabrook","209278444748":"Airport - CHS"},"first_serving_day":"2026-10-06","old_catch_all_156718849648":"paused; carried all traffic through 2026-10-05","ref":"gaql:ad_group:2026-10-01..06"}

### [PASS] G06 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** PMax\-3 is paused\. It spent $620\.56 on 11,882 clicks \(~$0\.05 CPC\) and reported 311 conversions\.

**Diagnosis:** Its traffic pattern matched junk placements, so pausing it was correct\.

**Recommended action:** Keep PMax paused until real\-lead conversions are clean \(G47\)\. If relaunched, use a lead\-only goal and brand exclusions\.

**Evidence:**

1.

        {"clicks":11882,"conversions":311,"cost_usd":620.56,"note":"avg CPC about $0.05; paused 2026-10-05 as low-quality traffic","ref":"gaql:campaign:23494000473:LAST_30_DAYS","status":"PAUSED"}

### [NOT APPLICABLE] G07 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** PMax is paused\.

**Diagnosis:** No overlap\.

**Recommended action:** None\.

**Evidence:**

1.

        {"clicks":11882,"conversions":311,"cost_usd":620.56,"note":"avg CPC about $0.05; paused 2026-10-05 as low-quality traffic","ref":"gaql:campaign:23494000473:LAST_30_DAYS","status":"PAUSED"}

### [PASS] G08 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Single active campaign\.

**Diagnosis:** Allocation is trivially aligned\.

**Recommended action:** None\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [PASS] G10 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Ads run 06:00 to 23:00 daily\.

**Diagnosis:** Reasonable\. Early airport pickups \(before 06:00\) are not covered\.

**Recommended action:** Review hour\-of\-day call data after 30 days before widening the schedule\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [PASS] G11 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Geo targets the Charleston area with PRESENCE\_OR\_INTEREST\.

**Diagnosis:** Interest targeting deliberately captures out\-of\-town travellers who book airport and wedding transport in advance\.

**Recommended action:** Check location reports on 2026\-10\-20 for far\-away clicks that did not convert\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [PASS] G12 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Search Partners and Display are off\.

**Diagnosis:** Clean Search\-only delivery\.

**Recommended action:** None\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [PASS] G13 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** The last\-7\-day search terms were reviewed on 2026\-10\-06\.

**Diagnosis:** Recent\.

**Recommended action:** Re\-review on 2026\-10\-13 once the new ad groups have a week of traffic\.

**Evidence:**

1.

        {"examples":["competitor names (aaa royal coach charleston, atlantic limo of charleston, blacklane)","cab/taxi terms","black truck"],"ref":"gaql:search_term_view:LAST_7_DAYS","terms":43,"visible_cost_usd":14.47}

### [PASS] G14 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** 80\+ campaign negatives were added from search\-term evidence \(rental, u\-haul, equipment, etc\.\)\. Overblocking terms \('rental', 'myrtle beach'\) were removed\.

**Diagnosis:** Governed and evidence\-based\.

**Recommended action:** Keep adding only from search\-term evidence; do not add competitor names, because those searchers are in\-market\.

**Evidence:**

1.

        {"examples":["competitor names (aaa royal coach charleston, atlantic limo of charleston, blacklane)","cab/taxi terms","black truck"],"ref":"gaql:search_term_view:LAST_7_DAYS","terms":43,"visible_cost_usd":14.47}

### [UNKNOWN] G16 — Unclassified

- Severity: Informational
- Confidence: Low
- Source classification: Practitioner

**Observation:** The 7\-day search\-term pull showed only $14\.47 of $310\.75 spend\. The visible terms are mostly relevant \(competitor brands, cab/taxi\)\.

**Diagnosis:** Not enough visible spend to judge waste\.

**Recommended action:** Re\-pull a full 14\-day search term report on 2026\-10\-13 covering only the new ad groups\.

**Evidence:**

1.

        {"examples":["competitor names (aaa royal coach charleston, atlantic limo of charleston, blacklane)","cab/taxi terms","black truck"],"ref":"gaql:search_term_view:LAST_7_DAYS","terms":43,"visible_cost_usd":14.47}

2.

        {"budget_lost_is":0.234,"clicks":187,"conversions":12,"cost_usd":310.75,"impressions":7526,"rank_lost_is":0.528,"ref":"gaql:campaign:LAST_7_DAYS","search_is":0.238}

### [PASS] G17 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** 0 broad\-match keywords \(58 exact, 63 phrase\)\.

**Diagnosis:** Match types are controlled\.

**Recommended action:** No change\.

**Evidence:**

1.

        {"avg_qs":3.0,"broad":0,"exact":58,"phrase":63,"rarely_served":"14 in sampled rows (Party Bus 8, Wedding 6)","ref":"gaql:ad_group_criterion:KEYWORD","total_positive":121,"with_quality_score":2}

### [UNKNOWN] G20 — Unclassified

- Severity: Informational
- Confidence: Low
- Source classification: Practitioner

**Observation:** Only 2 keywords have a Quality Score \(avg 3\.0\); the rest are too new\.

**Diagnosis:** Insufficient data\.

**Recommended action:** Read Quality Scores on 2026\-10\-20\.

**Evidence:**

1.

        {"avg_qs":3.0,"broad":0,"exact":58,"phrase":63,"rarely_served":"14 in sampled rows (Party Bus 8, Wedding 6)","ref":"gaql:ad_group_criterion:KEYWORD","total_positive":121,"with_quality_score":2}

### [UNKNOWN] G21 — Unclassified

- Severity: Informational
- Confidence: Low
- Source classification: Practitioner

**Observation:** Both scored keywords are at 4 or below\. They likely belong to the previous structure\.

**Diagnosis:** Insufficient data\. The high rank\-lost share \(52\.8%\) suggests ad rank is weak\.

**Recommended action:** If Quality Scores stay at 4 or below after 14 days, inspect expected CTR and landing\-page components per ad group\.

**Evidence:**

1.

        {"avg_qs":3.0,"broad":0,"exact":58,"phrase":63,"rarely_served":"14 in sampled rows (Party Bus 8, Wedding 6)","ref":"gaql:ad_group_criterion:KEYWORD","total_positive":121,"with_quality_score":2}

2.

        {"budget_lost_is":0.234,"clicks":187,"conversions":12,"cost_usd":310.75,"impressions":7526,"rank_lost_is":0.528,"ref":"gaql:campaign:LAST_7_DAYS","search_is":0.238}

### [PASS] G26 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** 2 RSAs per ad group\.

**Diagnosis:** Meets the guidance\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [PASS] G27 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** 15 headlines per RSA \(one Cruise RSA has 12\)\.

**Diagnosis:** Sufficient\.

**Recommended action:** Optionally add 3 headlines to the 12\-headline Cruise RSA\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [PASS] G28 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** 4 descriptions per RSA\.

**Diagnosis:** Sufficient\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [UNKNOWN] G29 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Ad Strength is PENDING \(new ads\)\.

**Diagnosis:** Not yet rated\.

**Recommended action:** Re\-check in 3 to 5 days\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [PASS] G30 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** No pins\.

**Diagnosis:** Full rotation flexibility\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [PASS] G35 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Each ad group's RSAs are written to its theme\.

**Diagnosis:** Relevant\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [PASS] G36 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Maximize Conversions is active\.

**Diagnosis:** Appropriate, but the strategy is only as good as its conversion signal \(root cause recorded under G47, not double\-counted here\)\.

**Recommended action:** Keep it, and fix the signal per G47\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [NOT APPLICABLE] G37 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** No target CPA is set on the Search campaign\.

**Diagnosis:** None\.

**Recommended action:** Consider a tCPA only after 30\+ real\-lead conversions\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [UNKNOWN] G38 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** The campaign is LEARNING \(BIDDING\_STRATEGY\_LEARNING\) after the switch to Maximize Conversions on 2026\-10\-06\.

**Diagnosis:** Expected after a strategy change\.

**Recommended action:** Avoid budget, bidding or conversion\-goal edits for 7 to 14 days except the tracking fixes in G\-CT1\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

2.

        {"latest":"2026-10-06 13:15 UTC, GOOGLE_ADS_WEB_CLIENT, target_spend.cpc_bid_ceiling_micros (bid strategy switched in the UI to Maximize Conversions)","ref":"gaql:change_event:CAMPAIGN"}

### [FAIL] G39 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** 23\.4% of impression share was lost to budget in the last 7 days \(28\.1% over 30 days\) and 52\.8% was lost to rank\.

**Diagnosis:** The account is limited by both budget and rank, but rank is the larger constraint, so a budget raise alone buys little\.

**Recommended action:** Do not raise the budget during learning\. Re\-evaluate on 2026\-10\-20: if budget\-lost share stays above 15% with a CPA on real leads that you accept, raise $60 to $75/day\.

**Evidence:**

1.

        {"budget_lost_is":0.234,"clicks":187,"conversions":12,"cost_usd":310.75,"impressions":7526,"rank_lost_is":0.528,"ref":"gaql:campaign:LAST_7_DAYS","search_is":0.238}

2.

        {"budget_lost_is":0.281,"clicks":600,"conversions":67,"cost_usd":1346.13,"impressions":18424,"rank_lost_is":0.491,"ref":"gaql:campaign:LAST_30_DAYS","search_is":0.228,"top_is":0.165}

### [NOT APPLICABLE] G40 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Not manual CPC\.

**Diagnosis:** None\.

**Recommended action:** None\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [NOT APPLICABLE] G41 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** No portfolio strategies are used\.

**Diagnosis:** None\.

**Recommended action:** None\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

### [PASS] G42 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Lead conversion actions exist for website quote forms, calls from ads and website contact taps\.

**Diagnosis:** Coverage of lead types is adequate; quality and duplication are covered by G\-CT1 and G47\.

**Recommended action:** No change\.

**Evidence:**

1.

        {"biddable_goals":["PHONE_CALL_LEAD/CALL_FROM_ADS","SUBMIT_LEAD_FORM/WEBSITE","CONTACT/WEBSITE","CONTACT/CALL_FROM_ADS"],"primary_in_conversions":[{"category":"CONTACT","counting":"ONE_PER_CLICK","fires_on":"GTM: tel: link clicks AND any form submit","id":"6562074192","name":"Call","type":"WEBPAGE"},{"category":"SUBMIT_LEAD_FORM","counting":"ONE_PER_CLICK","fires_on":"quote-email success only (installed 2026-10-05)","id":"7824071425","name":"Quote form submitted","type":"WEBPAGE"},{"category":"PHONE_CALL_LEAD","counting":"ONE_PER_CLICK","id":"6575000075","name":"Calls from ads","type":"AD_CALL"},{"id":"6559826815","name":"Smart campaign ad clicks to call","type":"SMART_CAMPAIGN_AD_CLICKS_TO_CALL"},{"id":"6559828291","name":"Calls from Smart Campaign Ads","type":"SMART_CAMPAIGN_TRACKED_CALLS"}],"ref":"gaql:conversion_action"}

### [PASS] G43 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Enhanced conversions for web were enabled in the UI by the owner \(Google tag method\)\. The quote tag sends email and phone via gtag user\_data\.

**Diagnosis:** Applicable and configured\. Diagnostics take several days to populate\.

**Recommended action:** Check Goals \> Conversions \> Diagnostics for 'Quote form submitted' after 7 days\.

**Evidence:**

1.

        {"ref":"owner-ui-confirmation:2026-10-05","status":"Enhanced conversions for web enabled via Google tag"}

2.

        {"event":"AW-11250744864/WbwNCIG255IdEKD84vQp","ref":"site:wp-content/novamira-sandbox/mecca-ads-quote-conversion.php","tested":"fires only after a real quote email","user_data":"hashed-by-Google email/phone via gtag set user_data"}

### [NOT APPLICABLE] G44 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Small single\-site lead\-gen account with web and call conversions\.

**Diagnosis:** Server\-side tagging is not needed at this scale\.

**Recommended action:** None\.

**Evidence:**

No evidence was supplied.

### [UNKNOWN] G45 — Unclassified

- Severity: Informational
- Confidence: Low
- Source classification: Practitioner

**Observation:** US\-only targeting \(South Carolina\)\. No consent\-mode evidence was collected\.

**Diagnosis:** Lower exposure for US\-only traffic; still unverified\.

**Recommended action:** No action unless EU/UK traffic is targeted\.

**Evidence:**

No evidence was supplied.

### [UNKNOWN] G46 — Unclassified

- Severity: Informational
- Confidence: None
- Source classification: Practitioner

**Observation:** Conversion windows were not read in this run\.

**Diagnosis:** Not evaluated\.

**Recommended action:** Read click\-through windows in the next run; 30 days is typical for event bookings\.

**Evidence:**

No evidence was supplied.

### [FAIL] G47 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** All 67 conversions in the last 30 days came from 'Call' \(tel\-link tap / form submit\)\. Calls from ads and Quote form submitted recorded 0\. The campaign switched to Maximize Conversions today, so this tag is now the only thing Google optimizes toward\.

**Diagnosis:** A tap on the phone number is a micro\-conversion \(not a completed call\)\. Smart Bidding will chase cheap taps, including accidental ones, rather than real bookings\. Reported CPA \(~$20\) is overstated in quality\.

**Recommended action:** Add a real call conversion: 'Calls from a website' using a Google forwarding number with a 60\-second minimum\. When real calls plus quotes reach roughly 15 in 30 days, make the tel\-click 'Call' secondary \(draft mutation M2\)\. Until then, keep the current setup so bidding has some signal\.

**Evidence:**

1.

        {"note":"No other action recorded a conversion in 30 days (Quote form submitted: 0, Calls from ads: 0)","ref":"gaql:campaign x segments.conversion_action:LAST_30_DAYS","rows":[{"action":"Call","all_conversions":67,"conversions":67}]}

2.

        {"biddable_goals":["PHONE_CALL_LEAD/CALL_FROM_ADS","SUBMIT_LEAD_FORM/WEBSITE","CONTACT/WEBSITE","CONTACT/CALL_FROM_ADS"],"primary_in_conversions":[{"category":"CONTACT","counting":"ONE_PER_CLICK","fires_on":"GTM: tel: link clicks AND any form submit","id":"6562074192","name":"Call","type":"WEBPAGE"},{"category":"SUBMIT_LEAD_FORM","counting":"ONE_PER_CLICK","fires_on":"quote-email success only (installed 2026-10-05)","id":"7824071425","name":"Quote form submitted","type":"WEBPAGE"},{"category":"PHONE_CALL_LEAD","counting":"ONE_PER_CLICK","id":"6575000075","name":"Calls from ads","type":"AD_CALL"},{"id":"6559826815","name":"Smart campaign ad clicks to call","type":"SMART_CAMPAIGN_AD_CLICKS_TO_CALL"},{"id":"6559828291","name":"Calls from Smart Campaign Ads","type":"SMART_CAMPAIGN_TRACKED_CALLS"}],"ref":"gaql:conversion_action"}

3.

        {"latest":"2026-10-06 13:15 UTC, GOOGLE_ADS_WEB_CLIENT, target_spend.cpc_bid_ceiling_micros (bid strategy switched in the UI to Maximize Conversions)","ref":"gaql:change_event:CAMPAIGN"}

### [UNKNOWN] G48 — Unclassified

- Severity: Informational
- Confidence: None
- Source classification: Practitioner

**Observation:** Attribution model was not read in this run\.

**Diagnosis:** Not evaluated\.

**Recommended action:** Confirm data\-driven attribution on the primary actions\.

**Evidence:**

No evidence was supplied.

### [UNKNOWN] G49 — Unclassified

- Severity: Informational
- Confidence: Low
- Source classification: Practitioner

**Observation:** No conversion values are assigned to any lead action\.

**Diagnosis:** Values would let bidding favor quotes and calls over taps, but the average booking value has not been confirmed by the owner\.

**Recommended action:** Provide the average booking value \(airport transfer vs wedding/party bus\)\. Then assign relative values, e\.g\. quote = 1\.0x, real call = 1\.0x, tel tap = 0\.1x\.

**Evidence:**

No evidence was supplied.

### [PASS] G50 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** 20 sitelinks \(the campaign limit\)\.

**Diagnosis:** Full\.

**Recommended action:** None\.

**Evidence:**

1.

        {"business_name":true,"call":2,"callouts":15,"images":18,"location":"GBP-linked location asset","logo":true,"ref":"gaql:campaign_asset+customer_asset","sitelinks":20,"structured_snippets":3}

### [PASS] G51 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** 15 callouts\.

**Diagnosis:** Full\.

**Recommended action:** None\.

**Evidence:**

1.

        {"business_name":true,"call":2,"callouts":15,"images":18,"location":"GBP-linked location asset","logo":true,"ref":"gaql:campaign_asset+customer_asset","sitelinks":20,"structured_snippets":3}

### [PASS] G52 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** 3 structured snippets\.

**Diagnosis:** Good\.

**Recommended action:** None\.

**Evidence:**

1.

        {"business_name":true,"call":2,"callouts":15,"images":18,"location":"GBP-linked location asset","logo":true,"ref":"gaql:campaign_asset+customer_asset","sitelinks":20,"structured_snippets":3}

### [PASS] G53 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** 18 images plus logo and business name\.

**Diagnosis:** Good\.

**Recommended action:** None\.

**Evidence:**

1.

        {"business_name":true,"call":2,"callouts":15,"images":18,"location":"GBP-linked location asset","logo":true,"ref":"gaql:campaign_asset+customer_asset","sitelinks":20,"structured_snippets":3}

### [PASS] G54 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Call assets are present \(2\), but 'Calls from ads' recorded 0 conversions in 30 days\.

**Diagnosis:** The asset is present; check call reporting and the minimum call length\.

**Recommended action:** In Settings \> Call reporting, confirm call reporting is ON and the 'Calls from ads' minimum length is 60s\.

**Evidence:**

1.

        {"business_name":true,"call":2,"callouts":15,"images":18,"location":"GBP-linked location asset","logo":true,"ref":"gaql:campaign_asset+customer_asset","sitelinks":20,"structured_snippets":3}

2.

        {"note":"No other action recorded a conversion in 30 days (Quote form submitted: 0, Calls from ads: 0)","ref":"gaql:campaign x segments.conversion_action:LAST_30_DAYS","rows":[{"action":"Call","all_conversions":67,"conversions":67}]}

### [UNKNOWN] G55 — Unclassified

- Severity: Informational
- Confidence: Low
- Source classification: Practitioner

**Observation:** No lead form asset\.

**Diagnosis:** Optional for this business\.

**Recommended action:** Consider testing one only after website conversions are clean\.

**Evidence:**

1.

        {"business_name":true,"call":2,"callouts":15,"images":18,"location":"GBP-linked location asset","logo":true,"ref":"gaql:campaign_asset+customer_asset","sitelinks":20,"structured_snippets":3}

### [UNKNOWN] G56 — Unclassified

- Severity: Informational
- Confidence: None
- Source classification: Practitioner

**Observation:** Audience observation segments were not read\.

**Diagnosis:** Not evaluated\.

**Recommended action:** Add observation audiences \(in\-market: wedding services, travel\) to learn bid signals without restricting reach\.

**Evidence:**

No evidence was supplied.

### [FAIL] G59 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Mobile Lighthouse performance score is 65; the Facebook pixel adds about 600 ms of blocking time\.

**Diagnosis:** A slow mobile landing page affects Quality Score and conversion rate \(82% of spend is mobile\)\.

**Recommended action:** Delay\-load the Facebook pixel until user interaction, and opt out of the host\-injected GoDaddy script\.

**Evidence:**

1.

        {"accessibility":100,"best_practices":79,"main_cost":"Facebook pixel ~600 ms total blocking time; host-injected GoDaddy script","performance":65,"ref":"lighthouse-local:www.meccalimo.com:mobile:2026-10-05","seo":100}

2.

        {"desktop":{"conv":10,"cost":286.13},"mobile":{"conv":55,"cost":1036.58},"ref":"gaql:campaign x device:LAST_30_DAYS","tablet":{"conv":1,"cost":23.14}}

### [PASS] G60 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Ad groups link to matching service pages on meccalimo\.com\.

**Diagnosis:** Relevant\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [NOT APPLICABLE] G96 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** No DSA ad groups\.

**Diagnosis:** None\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ad_strength":"PENDING (new)","approval":"all APPROVED","descriptions":4,"headlines":"15 (one Cruise ad 12)","per_ad_group":2,"pins":0,"ref":"gaql:ad_group_ad","rsas_enabled":12}

### [NOT APPLICABLE] G97 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** One active Search campaign\.

**Diagnosis:** No cross\-campaign duplication possible\.

**Recommended action:** None\.

**Evidence:**

1.

        {"bidding":"MAXIMIZE_CONVERSIONS","budget_usd_day":60,"display":false,"geo":"Charleston DMA area (200519), PRESENCE_OR_INTEREST","primary_status":"LEARNING","primary_status_reason":"BIDDING_STRATEGY_LEARNING","ref":"gaql:campaign:settings","schedule":"06:00-23:00 daily (7 windows)","search_partners":false,"tablet_bid_modifier":"-100%","target_cpa":null}

## Contradictions

- Earlier session notes said the 'Call' action was secondary\. Live data on 2026\-10\-06 shows it is primary, included in Conversions, and the only source of conversions\.
- Earlier notes recorded Maximize Clicks with a $6 CPC ceiling\. Live data shows Maximize Conversions, changed in the web UI on 2026\-10\-06 13:15 UTC\.
- PMax\-3 reported 311 conversions at ~$0\.05 CPC\. Those conversions are not trusted because the traffic pattern matched junk placements\.

## Prioritized actions

1. Narrow the GTM 'Call' tag to tel: clicks only \(remove form\-submit trigger\) \(Confidence: high; Control Id: G\-CT1; Owner: Owner in GTM \(Claude can guide step by step\); Timing: This week\)
2. Turn on website call tracking \(Google forwarding number, 60s minimum\) and confirm call reporting is on \(Confidence: high; Control Id: G47 / G54; Owner: Owner in Google Ads UI; Timing: This week\)
3. Approve draft M1: split brand keywords into their own ad group \(Confidence: medium; Control Id: G05; Owner: Owner approves; Claude applies; Timing: After approval \(low learning impact\)\)
4. Hold budget, bids and goals steady while the campaign is LEARNING \(Confidence: high; Control Id: G38; Owner: Everyone; Timing: Until 2026\-10\-20\)
5. Delay\-load the Facebook pixel and remove the GoDaddy injected script \(Confidence: medium; Control Id: G59; Owner: Claude \(site\) \+ owner \(GoDaddy\); Timing: Next 2 weeks\)
6. Re\-audit: search terms, Quality Score, budget\-lost share, Calls from ads volume \(Confidence: high; Control Id: G13 / G20 / G39; Owner: Claude; Timing: 2026\-10\-13 and 2026\-10\-20\)
7. Approve draft M2 \(make tel\-click 'Call' secondary\) once real calls \+ quotes reach about 15 in 30 days \(Confidence: medium; Control Id: G47; Owner: Owner approves; Claude applies; Timing: Gated on data\)

---

Generated deterministically from ReportBundle JSON. Scores were not recalculated.
