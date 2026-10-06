# Claude Ads Audit Report

> Run completeness: **Partial** · Evidence status: **Insufficient evidence**

## Run summary

- Run ID: 2026\-10\-06\-google\-mecca\-limo\-reaudit
- Started: 2026\-10\-06T20:15:00Z
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

### [FAIL] G\-AUTO1 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Auto\-apply recommendations were ON for 21 types, including adding keywords, switching to broad match, display expansion and bid\-strategy/target changes\. 15 were paused via the API on 2026\-10\-06; 6 newer types are not exposed by the API and remain on\.

**Diagnosis:** Unreviewed automatic changes could undo the exact/phrase structure, re\-enable Display, or change bidding\.

**Recommended action:** Owner: Google Ads \> Recommendations \> Auto\-apply \> turn off every remaining item\.

**Evidence:**

1.

        {"paused_now":15,"ref":"gaql:recommendation_subscription","still_enabled_unknown_types":6}

### [PASS] G\-CT1 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** GTM 'Call' \(fires on tel clicks and form submits\) is now secondary\. New 'Phone number tap \(website\)' 7825674138 fires on tel:/sms: taps only; quotes count once via 'Quote form submitted'\.

**Diagnosis:** Double counting removed\.

**Recommended action:** Confirm on 2026\-10\-09 that 7825674138 records conversions and 'Call' no longer counts in Conversions\.

**Evidence:**

1.

        {"primary":["7825674138","7824071425","6575000075"],"ref":"gaql:conversion_action readback 2026-10-06","secondary":["6562074192","7825674141"]}

2.

        {"fires_on_load":false,"label":"R5yzCJqfyZMdEKD84vQp","ref":"playwright:tap-test 2026-10-06"}

### [PASS] G\-CT2 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** Two Google product links exist \(type not exposed by the API; one is the GA4 property G\-972DVZ8XMK seen firing on the site\)\.

**Diagnosis:** Linked\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ids":["1009608290","1828980294"],"ref":"gaql:product_link"}

2.

        {"ga4":"G-972DVZ8XMK","ref":"playwright network"}

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

**Observation:** The Cruise ad group's 7 keywords are all NOT\_ELIGIBLE \(rarely served\)\. Keyword Planner shows transport\-intent cruise searches at about 10/month; the high\-volume cruise queries are cruise shoppers \(5,400/mo\) or port lookups \(2,400/mo\)\.

**Diagnosis:** Low volume by nature, not a setup error\. Adding the informational terms would pull budget from Airport/Wedding\.

**Recommended action:** Leave as is; cruise riders also reach the Airport and Black Car groups\. Revisit only if the owner wants a dedicated cruise push\.

**Evidence:**

1.

        {"ref":"gaql:ad_group_criterion 203915984314"}

2.

        {"charleston airport to cruise port":10,"charleston cruise port":2400,"cruises from charleston":5400,"ref":"keyword-planner 2026-10-06"}

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

### [PASS] G05 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Brand keywords moved to 'Brand \- Mecca Limo' 202405291802 and paused in Black Car & Corporate\.

**Diagnosis:** Brand and non\-brand are separated\.

**Recommended action:** None\.

**Evidence:**

1.

        {"brand_ad_group":"202405291802","keywords":7,"ref":"gaql:ad_group_criterion readback"}

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
- Confidence: High
- Source classification: Practitioner

**Observation:** 90\-day data: area\-of\-interest \(out\-of\-area\) clicks produced 173 conversions for $3,122 \(~$18 each\); people located in the area produced 46 for $922 \(~$20 each\)\.

**Diagnosis:** Interest targeting brings travellers who convert at least as well\. Keep PRESENCE\_OR\_INTEREST\.

**Recommended action:** Re\-check after M2 when conversions are real calls and quotes\.

**Evidence:**

1.

        {"AREA_OF_INTEREST":{"clicks":1441,"conv":173,"cost":3122},"LOCATION_OF_PRESENCE":{"clicks":581,"conv":46,"cost":922},"ref":"gaql:geographic_view 2026-07-08..10-05"}

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
- Confidence: High
- Source classification: Practitioner

**Observation:** 75 campaign negatives checked against 127 positive keywords: no conflicts\.

**Diagnosis:** Negatives are not blocking your own keywords\.

**Recommended action:** Keep adding only from search\-term evidence\.

**Evidence:**

1.

        {"conflicts":0,"negatives":75,"positives":127,"ref":"negative-conflict check 2026-10-06"}

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

**Observation:** All 13 RSAs have 15 headlines \(Cruise RSA raised from 12\)\.

**Diagnosis:** Full\.

**Recommended action:** None\.

**Evidence:**

1.

        {"ref":"changeId 1399798"}

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

### [PASS] G46 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Primary actions use a 30\-day click\-through window\.

**Diagnosis:** Appropriate for event bookings\.

**Recommended action:** None\.

**Evidence:**

1.

        {"click_through_days":30,"ref":"gaql:conversion_action"}

### [FAIL] G47 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** The primary signal is still a phone\-number tap \(micro\-conversion\), now clean and single\-counted\. Real calls \(60s\+ via forwarding number\) and ad calls are tracked, with the forwarding\-number calls kept secondary\.

**Diagnosis:** Improved, not finished\. Bidding still optimizes to taps rather than completed calls\.

**Recommended action:** Apply M2 \(make taps secondary, real calls primary\) once real calls plus quotes reach about 15 in 30 days\.

**Evidence:**

1.

        {"note":"No other action recorded a conversion in 30 days (Quote form submitted: 0, Calls from ads: 0)","ref":"gaql:campaign x segments.conversion_action:LAST_30_DAYS","rows":[{"action":"Call","all_conversions":67,"conversions":67}]}

2.

        {"biddable_goals":["PHONE_CALL_LEAD/CALL_FROM_ADS","SUBMIT_LEAD_FORM/WEBSITE","CONTACT/WEBSITE","CONTACT/CALL_FROM_ADS"],"primary_in_conversions":[{"category":"CONTACT","counting":"ONE_PER_CLICK","fires_on":"GTM: tel: link clicks AND any form submit","id":"6562074192","name":"Call","type":"WEBPAGE"},{"category":"SUBMIT_LEAD_FORM","counting":"ONE_PER_CLICK","fires_on":"quote-email success only (installed 2026-10-05)","id":"7824071425","name":"Quote form submitted","type":"WEBPAGE"},{"category":"PHONE_CALL_LEAD","counting":"ONE_PER_CLICK","id":"6575000075","name":"Calls from ads","type":"AD_CALL"},{"id":"6559826815","name":"Smart campaign ad clicks to call","type":"SMART_CAMPAIGN_AD_CLICKS_TO_CALL"},{"id":"6559828291","name":"Calls from Smart Campaign Ads","type":"SMART_CAMPAIGN_TRACKED_CALLS"}],"ref":"gaql:conversion_action"}

3.

        {"latest":"2026-10-06 13:15 UTC, GOOGLE_ADS_WEB_CLIENT, target_spend.cpc_bid_ceiling_micros (bid strategy switched in the UI to Maximize Conversions)","ref":"gaql:change_event:CAMPAIGN"}

### [PASS] G48 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Primary actions use data\-driven attribution\.

**Diagnosis:** Appropriate\.

**Recommended action:** None\.

**Evidence:**

1.

        {"attribution":"GOOGLE_SEARCH_ATTRIBUTION_DATA_DRIVEN","ref":"gaql:conversion_action"}

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
- Confidence: High
- Source classification: Practitioner

**Observation:** Account call reporting and all call assets now report to 'Calls from ads' 6575000075\. Previously 2 assets pointed at a stale action \(179\) and a Smart\-campaign action\. The 8 ad calls in 30 days were all 3\-48s, below the 60s threshold\.

**Diagnosis:** Fixed routing\. Short calls are a business signal worth reviewing\.

**Recommended action:** Owner: check whether short calls are hang\-ups or voicemail; if many real bookings happen in under 60s, lower the threshold\.

**Evidence:**

1.

        {"ref":"gaql:customer.call_reporting_setting + asset readback"}

2.

        {"calls":8,"over_60s":0,"ref":"gaql:call_view 30d"}

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

### [PASS] G56 — Unclassified

- Severity: Informational
- Confidence: Medium
- Source classification: Practitioner

**Observation:** 5 in\-market/affinity user\-interest segments are attached in observation \(bid\-only\) mode\.

**Diagnosis:** Observation audiences give bidding signals without limiting reach\.

**Recommended action:** None\.

**Evidence:**

1.

        {"count":5,"ref":"gaql:campaign_criterion USER_INTEREST","target_restriction":"AUDIENCE bid_only=true"}

### [PASS] G59 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** Mobile Lighthouse 99 on / and /charleston\-airport\-transportation/ \(was 65\)\. The host\-injected tccl script is neutralised; the Meta pixel is off\.

**Diagnosis:** Fast mobile landing pages\.

**Recommended action:** Keep wp\-content/novamira\-sandbox/mecca\-host\-script\-optout\.php enabled\.

**Evidence:**

1.

        {"lcp_ms":[1655,1850],"performance":99,"ref":"lighthouse-local 2026-10-06","tbt_ms":[101,0]}

### [PASS] G60 — Unclassified

- Severity: Informational
- Confidence: High
- Source classification: Practitioner

**Observation:** All 7 final URLs return 200 with a matching title and H1 and the quote form \(wedding, kiawah, airport, night\-out, cruise\-trips, service, home\)\. Ad claims \(20 min wait, flight tracking, Sprinter for 14, SUVs for 6, 24/7, golf bags\) appear on the site\.

**Diagnosis:** Message match is good\.

**Recommended action:** None\.

**Evidence:**

1.

        {"pages":7,"ref":"http fetch 2026-10-06"}

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
- The auto\-apply subscriptions MAXIMIZE\_CONVERSIONS\_OPT\_IN and TARGET\_CPA\_OPT\_IN were enabled\. The 2026\-10\-06 bid\-strategy switch was logged from the web client under the owner's login, so it was most likely a manual change, not auto\-apply\.

## Prioritized actions

1. Turn off the 6 remaining auto\-apply items in the Google Ads UI \(Confidence: high; Control Id: G\-AUTO1; Owner: Owner; Timing: Today\)
2. Send average booking value per service and starting prices \(for conversion values and a price asset\) \(Confidence: high; Control Id: G49; Owner: Owner; Timing: This week\)
3. Review why ad calls are short \(3\-48s\): missed or voicemail versus quick bookings \(Confidence: medium; Control Id: G54; Owner: Owner; Timing: This week\)
4. Hold budget, bids and goals while Maximize Conversions learns \(Confidence: high; Control Id: G38; Owner: Everyone; Timing: Until 2026\-10\-20\)
5. Check\-in: tap conversions recording, brand ad approved, search terms, impression share \(Confidence: high; Control Id: G\-CT1 / G13; Owner: Claude; Timing: 2026\-10\-09\)
6. Apply M2 once real calls plus quotes reach about 15 in 30 days \(Confidence: medium; Control Id: G47; Owner: Owner approves; Claude applies; Timing: Gated on data\)

---

Generated deterministically from ReportBundle JSON. Scores were not recalculated.
