# Business context: Mecca Limo

## Business

- Business name: Mecca Limo (Mecca Limo of Charleston, SC)
- Website: https://www.meccalimo.com/
- Markets served: Charleston, North Charleston, Mount Pleasant, Daniel Island, Summerville, Kiawah and Seabrook Islands, Folly Beach, Isle of Palms, Sullivan's Island, plus longer trips (Georgetown, Pawleys Island, Edisto, Bluffton) on request
- Primary offer: Private chauffeured rides: airport transfers (CHS, LRO, JZI), weddings, Sprinter party bus / bachelorette, corporate, hourly charter
- Sales model: Local service, booked by phone call, text or website quote form; no public prices
- Currency and timezone: USD, America/New_York

## Goals and economics

- Primary business goal: Booked rides (qualified calls and quote forms that turn into bookings)
- Monthly Google Ads budget: about $1,215 (Search at $40/day); total cap across all ad platforms $1,500/month including Local Services Ads (under $300/month)
- Target cost per qualified lead: well under $50 (owner says average booking is under $200)
- Average gross revenue per sale: under $200 (owner estimate)
- Average contribution profit per sale: unknown
- Lead to qualified rate: unknown
- Qualified lead to sale rate: unknown

## Account rules

- Protected brand campaigns: none separate (brand terms "mecca limo" run inside the Search campaign)
- Budget floors or ceilings: never exceed $1,500/month total across Google Search, Local Services Ads and Meta
- Locations to exclude: Myrtle Beach area (blocked by negative keyword "myrtle beach"); Columbia, Seneca
- Claims the ads may use: 5.0 stars / 149 Google reviews (HOTH report 2026-10-06); family-owned; open 24/7; flight tracking; 20 minutes free airport wait (meccalimo.com/airport/ and /policy/); executive sedan 3 pax, luxury SUV 6 pax, Mercedes Sprinter 14 pax (meccalimo.com/charleston-limo-fleet/); stretch limo available for weddings (meccalimo.com/wedding/)
- Claims the ads must never use: any price or discount, brewery tours, "funerals", Savannah/Myrtle Beach airports, competitor names, phone number in ad text, star symbols
- Required legal or brand wording: none
- Mutation policy: owner delegated authority on 2026-10-06 ("do whatever you think is best, including budget, tell me after"), within the $1,500/month cap; every change is recorded in decision-log.md with a rollback note

## Data sources

- Google Ads customer ID: 2227770856
- Manager customer ID, if used: none known
- GA4 property: tags G-0LNFNFPZ9T and G-972DVZ8XMK on the site (via GTM-MLRT4TR)
- Form source: Mecca Quote Form plugin on /get-a-quote/ and the homepage quote section
- CRM and pipeline stages: none (bookings handled by phone/text)
- Offline outcome source: none yet
- Join keys available: GCLID (GTM gclid tag present), date, campaign ID
- Data connection used by Claude: Windsor.ai connector (read and write); the official googleads/google-ads-mcp is not connected (needs a Google Cloud developer token)

## Known context

- Seasonality: spring and fall wedding season; summer beach and bachelorette weekends
- Recent launches or outages: site redesigned Oct 2026; Core Web Vitals field data still reflects the old site
- Prior decisions that should not be reversed: no prices on the website or ads; no brewery tours; Performance Max paused (spent ~$1,850/90d mostly on YouTube/app clicks)
- Current questions: what the GTM "Call" conversion should count (fires on any tel: tap and any form submit); whether to target Murrells Inlet / Pawleys area
