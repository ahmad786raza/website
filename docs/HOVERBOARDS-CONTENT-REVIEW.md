# hoverboardsguide.com — published content that needs the owner's attention

These are passages inside posts (the database), which a theme cannot change. The v3 theme neutralises the layout problems on output (duplicate H1, purple header blocks, "#ad" strips, inline fonts, in-content "Table of Contents" boxes) but the wording below is still published. Checked against the public REST export of 11 October 2026.

## High — claims the site cannot back up (Amazon Associates and consumer-protection risk)

| Where | What is published | Why it matters | Suggested fix |
|---|---|---|---|
| `/hoverboard-buying-guide/` (post 262), product box at the top | "★★★★☆ 4.4 (5,120 reviews)" next to the EPIKGO Sport Plus; "the smoothest self-balance system we've tested"; "30-day returns" | Looks like an Amazon rating and review count (Associates rules only allow those through the API, refreshed), and claims testing | Remove the stars/count and "we've tested"; keep the description and the tagged link |
| `/hoverboard-buying-guide/` product cards for Gotrax E4, 80SIX helmet, 187 Killer Pads | "⭐ 4.4 Stars", "⭐ 4.5 Stars", "⭐ 4.6 Stars" badges | Same as above | Remove the star badges |
| `/best-hoverboards-under-200/` (post 267) | "⭐ 4.4 Stars", "⭐ 4.3 Stars", "⭐ 4.6 / 5" badges; "30-day returns" | Same | Remove |
| `/hover-1-titan-hoverboard/` (post 700) | Excerpt says "Our hands-on Hover-1 Titan hoverboard review"; a table headed "Independent Testing" with measured speed/range/incline; body says "hands-on" | Presents desk research as testing unless you actually rode the board | Reword to "our research", drop the "Independent Testing" column or cite the source |
| Most 2026 articles (166 posts) | Header block "UPDATED JUNE 2026" (or similar) inside the body | A static badge that does not match the real modified date; the theme hides it and shows the real dates | No action needed while v3 is active; remove when editing |
| `/the-best-hoverboard-reviews/` and others | "Real reviews, real specs", "chosen by real riders" | Only keep if true | Soften to "researched picks" |

## Medium — conflicting specifications between articles (the product registry quotes one value and notes the conflict)

| Model | Values published | Where |
|---|---|---|
| Gyroor Warrior / Warrior G2 | top speed 9, 9.3, 9.9 and "up to 10" mph; rider limit 220 lb and 265 lb; tires "8.5″ solid" and "8.5″ pneumatic" | posts 262, 267, 656, 677, 700, 712, 807, 838 |
| Gyroor G13 | top speed 7.75 mph and 7.2 mph; range 5.5 mi and "up to 8" | 262, 712 |
| Hover-1 Titan | top speed "7 mph", "7–8 mph"; rider limit 264 and 265 lb | 677, 700, 807, 838 |
| Hover-1 Dream | rider limit 160 lb and 220 lb; range 3 mi | 262, 712 |
| Hover-1 Helix | range 7 mi and 9 mi; Bluetooth "yes" (656) and "no" (267) | 267, 656 |
| Swagtron T6 | motor 600 W and 700 W | 262, 807, 838 |

Check each model's current listing, pick one figure per spec, and update the posts and the product editor (Hoverboards Guide → Products → tick "Verified").

## Medium — structure and SEO

- **279 posts repeat the title as an in-body `<h1>`** (two H1s per page). The theme removes the duplicate on output; when editing a post, delete the header block so the fix is permanent.
- **No internal links in post bodies** (0 contextual links across 283 posts). The theme adds read-next, related and topic links around each article; adding 2–4 in-copy links from roundups to the single-model reviews, and from reviews back to the buying guide, is the next biggest internal-linking win.
- **Every post uses a hand-built "Table of Contents" box** with inline purple styling; the theme restyles it. Fine to leave.
- **Featured images**: 196 are generated "title card" graphics with the headline baked in (files named `*-featured.png`). The theme skips them on cards and article heroes (Customizer → Articles) and shows the topic photo instead. Replacing them with real photos is a content task; model reviews should get product photos obtained through Amazon's Associates tools or the manufacturer's press kit.
- **Single-model reviews use generic stock photos** that do not show the model (e.g. the Hover-1 Titan review's featured image shows a Ninebot-type scooter). Replace with product-specific imagery when a licensed source is available.
- Titles with stale years: "Glyro Hoverboard Review in 2021", "New Laws for Hoverboard 2020", "5 Best Metallic Hoverboards in 2020", "Hoverboard Halloween Costume 2021", "comparison … in 2024". Refresh or merge.
- Near-duplicate pairs to merge or differentiate: `how-fast-does-hoverboard-go` / `how-fast-does-a-hoverboard-go`; `glyro-hoverboard-review-in-2021` / `…-in-2024`; `charger` / `chargers` categories; `scooters` / `e-scooters` categories; `hoverboard-troubleshooting` / `how-to-troubleshoot-a-hoverboard-and-fix-the-issues` / `hoverboard-problems-and-solutions`.
- Off-topic posts under hoverboard/guide categories (e.g. "How to use hoverboards in Subway Surfers", "7 interesting facts about hoverboards found on Instagram", "Best hoverboard spots in Los Angeles", "Hoverboard games simulator") dilute the hubs; consider a "Culture" category or pruning.

## Low

- Pages `about`, `contact`, `privacy-policy`, `terms` exist; `affiliate-disclosure` and `editorial-policy` do not (the Setup tool drafts them).
- The site has no tags at all; that is fine with the hub categories.
- Author shows as the admin account; set a display name and description under Users → Profile.
