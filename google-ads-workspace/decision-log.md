# Decision log: Mecca Limo Google Ads (account 222-777-0856)

| Date (UTC) | Change | Why | Rollback |
|---|---|---|---|
| 2026-10-06 | Paused campaign "Performance Max-3" (23494000473) | $1,848/90d, 47,876 YouTube clicks, 3,237 fake "Call" conversions | enable_campaign 23494000473 |
| 2026-10-06 | Added 38 phrase negatives to Search campaign 20347533486 (uber, cab, taxi, black truck, columbia, myrtle beach, rentals, jobs, competitor names...) | Search terms report: wasted spend on irrelevant queries | remove_negative_keywords |
| 2026-10-06 | Created 5 ad groups (Airport - CHS, Party Bus & Bachelorette, Wedding, Kiawah & Seabrook, Black Car & Corporate) with phrase+exact keywords (228 total) and 2 RSAs each | One broad ad group sent all traffic to the homepage | pause the new ad groups |
| 2026-10-06 | Budget $65/day, then $40/day | Owner cap $1,500/month all platforms | set_campaign_budget |
| 2026-10-06 | Bidding Maximize Conversions -> Maximize Clicks, CPC ceiling $4.00 | 3,012 of 3,014 conversions are a GTM "Call" event (tel tap / any form submit), not real calls | switch back to maximize_conversions after tracking fix |
| 2026-10-06 | Added 8 callouts, 6 sitelinks, 1 structured snippet | No extensions present; competitor gap: review count, free airport wait, private rides | remove assets |
| Pending | Pause old broad ad group 156718849648 once new ads are approved | Avoid double-serving broad match | enable_ad_group |
