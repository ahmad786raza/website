# hoverboardsguide.com — theme upgrade report (Hoverboards Guide 2.1.0 → 3.0.0 "Magazine")

Date: 11 October 2026

> **Status (12 October 2026, later): the live site runs theme 3.5.3 — the Amazon Associates tag is now `hoverboardsgu-20`.**
> The owner asked to replace `rcblogs-20` with `hoverboardsgu-20`. Done in three layers, none of which
> edits stored post content:
>
> - **Customizer:** Hoverboards Guide → Affiliate → “Amazon Associates tag” saved as `hoverboardsgu-20`
>   on the live site (it had been saved as `rcblogs-20`); the theme default is the new tag too. All
>   theme-generated links (picks, comparison table, finder, cards) follow it.
> - **Article links:** a new Customizer field “Retired tags to replace” (default `rcblogs-20`). At display
>   time any Amazon link carrying a retired tag is shown with the current tag, including links written
>   with `&amp;` or `&#038;`, Amazon URLs inside schema JSON an article carries, and disclosure sentences
>   that name the tag (“As an Amazon Associate (tag: …)”). Links with no tag get the current tag, as
>   before; a link with any other tag is left alone. The 544 old-tag links in 106 posts therefore switch
>   without a database edit, and reverting is a Customizer change.
> - **Checks:** home page and 15 posts fetched live after the deploy — 384 Amazon links, all
>   `hoverboardsgu-20`, no doubled tags, no remaining mention of the old tag. Local 52/52; live 48/49
>   (Site Kit scripts). Rollback: `dist/hoverboards-guide-3.5.0.zip` plus setting the Customizer tag back.
>
> **Previous (12 October 2026): 3.5.0 — 3.4.1 plus a rebuilt comparison table.**
> The owner's screenshot showed the home-page table clipping its “Check price” buttons on a wide
> monitor and asked for pictures and a more attractive section. 3.5.0:
>
> - **Fits without clipping** at 1100px and above (one column of width was recovered by moving the
>   “Review” button under the product name and giving the price button its own column); between 901
>   and 1099px it scrolls sideways with the hint shown; at 900px and below each board becomes a card
>   with a three-column spec grid (two columns on phones) and a full-width price button.
> - **Pictures:** every row gets a 68px product tile. There are no licensed product photos in the
>   media library (260 attachments, none of a product), and review featured images are rider stock
>   photos or title cards, so the tile is an original illustration drawn from the board's own data:
>   wheel diameter to scale, knobby ring for pneumatic/off-road tyres, deck colour by role. The table
>   note says they are illustrations. Upload a real photo for a product under Hoverboards Guide →
>   Products and the tile is replaced by it automatically.
> - **Row detail:** “Verified specs” tick only on verified rows (the per-row “Advertised specs” line is
>   gone; the heading and note already say figures are manufacturer claims), “Read review →” link,
>   “Highest” tags sit under their figure instead of widening the column, pick row keeps its accent.
> - Tests: local 52/52 (two runs), live 48/49 (Site Kit scripts, as before). Live screenshots at
>   1920, 1366 and 390px: `docs/screenshots/hbg-v35-compare-*.jpg`. Rollback: `dist/hoverboards-guide-3.4.1.zip`.
>
> **Previous (11 October 2026, late): 3.4.1, “3.3.0 plus cleaner article pages”.**
> The owner asked for better presentation of internal pages, using
> `/hoverboard-buying-guide/` as the example. 3.4.x changes only how imported article markup is
> rendered; no post content was edited and the home page is unchanged from 3.3.0.
>
> - **Imported product boxes** (“Rich Product Box” style, inline-styled): orange/purple palette remapped
>   to the site palette (also the green “Check Price” gradient), text inside the dark footer and the
>   gradient header kept readable, spec strip and bullet ticks kept.
> - **Claims the site cannot stand behind are no longer printed:** star ratings and review counts baked
>   into the boxes (“★★★★☆ 4.4 (5,120 reviews)”, “★ ★ ★ ★ ☆”, “⭐ 4.7/5”, “(2,840+ reviews)”) and the
>   availability strip (“In stock · Ships within 24 hours · Free Prime delivery · 30-day returns”).
>   Both are Customizer switches under Articles (on by default). Qualitative star matrices that compare
>   features in a table (drop protection ★★★★☆ vs ★★☆☆☆) are left alone: they are the author's own
>   comparison, not a review count.
> - **Leaked source text removed:** seven posts had a stylesheet pasted into the body without its
>   `<style>` tag (rendered as a paragraph of CSS rules after the first product box on the buying guide);
>   three had schema JSON pasted as text. Both are dropped at render time; the theme styles those
>   `hb-*` classes itself and prints its own schema.
> - **Article furniture:** the in-body “Table of Contents” box becomes the theme's contents panel,
>   emoji that prefixed headings are dropped, “Internal linking suggestions” authoring notes are hidden,
>   callouts (`hb-tip`, `hb-warn`, `hb-quote`, `hb-pro`, `hb-highlight`, `hb-faq`, `hb-checklist`) get
>   the theme's panel styles, body text is set at 17.5px/1.78 on a 70ch measure.
> - **Standfirst and previews:** the article standfirst shows only a hand-written excerpt; scraped
>   product-card strings (“Best Overall‧Hoverboards · Buying Guide Picks ★★★★☆…”) are never shown.
>   Card previews prefer the article's own lede under its title, then the first real paragraph.
>
> Tests: local replica 52/52 (axe-core included); live 48/49 after 3.4.1 — the one miss is the home
> page's six external scripts from Site Kit, not the theme (same as 3.3.0). A sweep of all 289 local
> posts and pages after the change found no CSS or JSON text in any body, no review counts, no
> availability strips. Live spot checks on the buying guide and six other affected posts match.
> Screenshots: `docs/screenshots/hbg-v34-guide-*.jpg` (live, desktop and mobile).
> Rollback: re-upload `dist/hoverboards-guide-3.3.0.zip` (previous live version),
> `dist/hoverboards-guide-3.2.0.zip` or `dist/hoverboards-guide-3.0.0.zip` (the original) under
> Appearance → Themes → Add New → Upload → Replace.
>
> Still true from 3.3.0: hero pick cards, immersive guide band, lead-story grid, spec icons and
> “Highest” tags, method tiles, sticky “On this page” list, reading-progress bar, back-to-top, category
> lead story and topic chips, soft reveal on scroll, SEO output when Rank Math is silent. What stays
> from the install session, all reversible from Hoverboards Guide → Setup: the eight hoverboard hub
> categories and two *draft* pages (Affiliate Disclosure, Editorial Policy). The header uses the theme's
> built-in menu; the WordPress menu created by Setup exists under Appearance → Menus, un-assigned.
> 3.1.0 and 4.0.0 remain in git history and as reference zips only.

## 1. What was and was not possible

| Item | Status |
|---|---|
| Your Chrome session / WordPress admin | **Not reachable.** This work ran in a cloud container that cannot control your browser, and no WordPress credentials were available to it. Nothing on the live site was changed. |
| hoverboardsguide.com (front end) | **Reachable, read-only.** The live homepage, the five reference pages, robots.txt, the sitemap and the public REST API were inspected: 283 published posts, 16 categories, 4 pages, 384 media items. |
| Theme supplied | The zip is **"Hoverboards Guide" 2.1.0**, the custom theme the live site already runs (its "Rich Magazine" edition). There is no separate "Rich Magazine" theme to install; this theme was upgraded in place to v3.0.0 under the same folder name, so it installs over the current one and keeps the settings that still apply. |
| Testing | WordPress 7.1 was installed locally with the SQLite driver, all 283 live posts, 4 pages, 16 categories and 262 featured images were imported from the public API, and v3 was tested against that replica in Chromium (desktop and phone widths). |
| Images | Photo libraries, Amazon and Wikimedia were blocked from the container, so no new photographs could be downloaded. The theme's 23 bundled royalty-free photos were re-assigned, 7 original SVG illustrations were drawn for topics with no suitable photo, and every image slot is now replaceable from the dashboard. |
| Keyword data | Semrush returned "no API units"; Ahrefs was not charged. **No search volumes are claimed.** |

**Completed:** audit, competitor research, full theme rebuild (design system, header, footer, nine homepage sections, article/archive/search/404 templates, product cards, comparison table, finder, topic hubs, shortcodes, widgets), honesty and SEO fixes, dashboard Setup tools and Products editor, local QA (51 of 52 automated checks pass; the one remaining finding is an inline colour inside a post body, see §8), lab performance, packaging and documentation.

**Still to do on your site (15–30 minutes):** install the zip, run the three Setup actions, review the two draft trust pages, set hero/topic photos if you have licensed ones, verify product figures in the Products editor, and work through `HOVERBOARDS-CONTENT-REVIEW.md`.

## 2. What the audit found (live site, 11 October 2026)

### Trust and compliance
- Homepage copy claimed "Updated weekly", "Prices re-checked weekly", "200+ guides & reviews", "60+ models researched in 2026", "0 sponsored placements", "Weekly price & stock checks", "Independent editors", "Written by riders … never sponsored", "ride what we can", "tested shortlist", "every one is UL 2272 certified and in stock on Amazon", "Free Prime shipping on most picks · 30-day returns". None of these can be substantiated by the site.
- Every product card showed an **editor score out of 10 and five stars** and a price tier; the registry's top picks were mostly electric scooters (Ninebot MAX G2, Hiboy S2 Pro, Varla Eagle One) on a site named for hoverboards.
- The newsletter form stored email addresses in a WordPress option and replied "Check your inbox to confirm" although no email was sent.
- Inside posts: fake-looking star ratings and review counts ("★★★★☆ 4.4 (5,120 reviews)"), "hands-on" and "Independent Testing" wording, and specifications that disagree between articles (details in `HOVERBOARDS-CONTENT-REVIEW.md`).

### Structure and SEO
- **279 of 283 posts had two H1s** (the title plus a copy inside the body).
- **No meta descriptions** on any page (no SEO plugin; the theme printed none).
- **Zero contextual internal links** inside post bodies.
- Information architecture: a 16-category flat list (E Scooters 103, Hoverboards 100, Buying Guide 24, Guide 20, Skateboards 20 …) with duplicates (`charger`/`chargers`, `scooters`/`e-scooters`) and no kids / adults / all-terrain / budget / safety / troubleshooting destinations; the header menu was "Hoverboards, E-Scooters, E-Bikes, Guides, More rides, Top Picks, Compare".
- Related posts were random within a category; no "read next".
- Search results, author and date archives were indexable.
- A JSON-LD FAQPage with the invented answers above was printed on the homepage; some posts carry their own Article schema, which the theme duplicated.
- `/blog/` and `/affiliate-disclosure/` returned 404 (linked from the footer logic).

### Design and performance
- Orange accent on cream, dark-mode toggle, an auto-scrolling "trending" ticker in a top bar, reveal animations, shimmer placeholders, a sticky mobile "Shop today's top picks" bar, a 17-section homepage (34,000+ px tall on a phone), and a header that shifts in a 15-link fallback menu.
- Google Fonts (two families, seven weights), one stylesheet, one script, Site Kit (GA4 `G-7T8K5K3BT1`, AdSense) — the latter two are kept untouched.
- 196 featured images are generated "title cards" with the headline baked into the picture (files named `*-featured.png`), so cards showed the title twice.

## 3. Competitors

See `docs/research/hoverboards-competitors.md`. In short: ERideHero is the only specialist that publishes tested figures; everything else ranking for "best hoverboard …" is a retailer page or a month-stamped affiliate listicle. The gaps HoverboardsGuide can own are decide-by-rider navigation, one honest comparison table with labelled advertised/verified specs, a finder that explains its limits, and CPSC-aligned UL 2272 guidance.

## 4. What changed in the theme (v3.0.0)

### Design system
- White background, navy headings (`#0b1f3a`), charcoal body text, one blue accent (`#1d4ed8`, 6.7:1 on white), light-grey alternate sections, quiet 1 px borders, 6–14 px radii, no gradients, no decorative animation, respects reduced-motion.
- Typography: Manrope (headings) + Inter (body) from Google Fonts in one request, or **system fonts with zero font requests** (Customizer switch).
- Removed: dark mode, top bar and ticker, year badge, shimmer, reveal animations, sticky mobile shop bar, compare drawer, stars, scores, price tiers, trust statistics.

### Header and navigation
- Compact 64 px sticky header (toggle), wordmark or custom logo, search button opening an accessible overlay (`/` shortcut, Escape closes), "Best hoverboards" button, hamburger drawer below 1180 px with accordion sub-menus, focus management and Escape.
- Built-in menu (used until you assign one): **Best Hoverboards · For Kids · For Adults · All-Terrain · Reviews ▾ (Budget, Bluetooth) · Buying Guides ▾ (buying guide, finder, compare) · Safety & How-To ▾ (troubleshooting, how to ride) · More ▾ (scooters, e-bikes, skateboards, unicycles, karts)**. Items point to the topic category when it exists, otherwise to the lead article, so nothing links to an empty page. The Setup tool can turn this into an editable WordPress menu.

### Homepage (each section is a Customizer toggle)
- **A Hero**: "Find the Right Hoverboard for Your Ride", the supporting sentence from your brief, primary button to the buying guide, secondary link to the safety guide, three plain points, one photo (bundled LED-hoverboard photo by default; upload your own in Customizer → Homepage: Hero; the hero image is preloaded and never lazy-loaded).
- **B Quick recommendations**: six cards, one per need (best overall, budget, kids, adults, all-terrain, beginners), each with an evidence-based sentence drawn from your own roundups, "Best for", three specs, "View full review" and "Check price on Amazon", plus a compact disclosure line above the grid. No prices, ratings or availability. Figures are labelled "as advertised" until you verify them.
- **C Browse by topic**: eight hubs — Hoverboards for Kids, for Adults, All-Terrain, Budget, Bluetooth, Reviews, Safety, Troubleshooting & Maintenance — each a curated list of 7–30 existing articles. Before the Setup tool runs they link to the lead article; after it they are real child categories of "Hoverboards" with descriptions.
- **D Featured buying guide**: your `/hoverboard-buying-guide/` with the seven decisions it covers (wheel size, terrain, weight capacity, battery/range, certification, charging, budget).
- **E Reviews & comparisons**: nine curated evergreen articles (editable list), then "Load more" from the Hoverboards category.
- **Compare specifications**: all seven registry hoverboards; filter by rider (kids & beginners / adults / all-terrain), sort by max rider weight, wheel size, speed or range; columns: wheels, max rider, top speed, range, certification, Bluetooth, review link, Amazon link; sticky first column and a visible "scroll sideways" hint on phones; note that speed and range are manufacturer claims.
- **F Learn before you buy**: how to ride the first time, choosing wheel size, grass and gravel, checking certification and spotting fakes, charging and battery care, common problems — all existing articles.
- **H Hoverboard finder** (replaces the newsletter slot): who / where / budget / what matters → the guides to read first plus the matching pick, with the caveat that age alone does not make a board safe and that manufacturer age and weight limits, rider ability and protective gear come first. Without JavaScript a decision table is shown instead.
- **FAQ**: seven rewritten answers (UL 2272 covers the whole board; check the mark on the box and the underside; wheel sizes; ages are manufacturer guidance; advertised range vs reality; adults; hoverboard vs scooter), also emitted as FAQPage schema.
- **G Editorial transparency**: what the site covers and how advice is developed, stated honestly ("desk research … we do not run a test lab"), with links to About, Contact, Affiliate Disclosure, Editorial Policy, Privacy and Terms where those pages exist.
- **More rides**: a one-line strip to the scooter, e-bike, skateboard, unicycle and kart categories (140 posts stay reachable but secondary).
- **Newsletter**: shown only when a real service URL (Mailchimp, ConvertKit, MailerLite…) is set; the v2 capture that stored emails without sending anything is gone (the old list remains exportable under Hoverboards Guide → Subscribers (legacy)).

### Articles (buying guides, reviews, comparisons, how-tos)
- Breadcrumbs (Home › Hoverboards › Child category › Title), category label, H1, standfirst from the excerpt, author, published/updated dates, reading time, share buttons (no third-party scripts), hero image, affiliate disclosure box **only on articles that contain affiliate links**, table of contents (theme's or the article's own, restyled), read-next (curated by topic hub), author box with an honest default description, previous/next **within the same category**, related guides (hub neighbours first, then the same category — never random, never another ride type).
- Output normalisation for the 166 imported articles (no database changes, switchable): the duplicate in-body H1 is removed, the purple "UPDATED …" header block becomes a plain lede paragraph, the inline "#ad" strip is dropped in favour of the theme's disclosure, the inline purple "Table of Contents" box is restyled, inline fonts/colours on headings, paragraphs and lists are stripped (layout styles on boxes are kept). Every Amazon link gets `rel="sponsored nofollow noopener"`, a new tab, and the site's tag only if it has none; existing tags are never changed. Tables scroll inside the column on phones.
- Generated "title card" featured images are skipped on cards and heroes (Customizer switch) so headlines are not shown twice; the topic photo is used instead.
- **Review components (shortcodes)** for consistent review, comparison and safety layouts: `[hbg_product]` (verdict-style box: why, best for, specs with source note, advantages, limitations, Amazon + review buttons), `[hbg_specs]` (key specification table with source and checked date), `[hbg_compare]` (comparison table for chosen products), `[hbg_verdict]`, `[hbg_pros_cons]`, `[hbg_box type="safety|warning|tip|note"]`, `[hbg_steps]`, `[hbg_button]`, `[hbg_finder]`, `[hbg_picks]`, `[hbg_disclosure]`. See `docs/screenshots/hbg-v3-review-components.jpg`.

### Dashboard tools (no code editing needed)
- **Hoverboards Guide → Setup**: status table; "Create topic categories and assign articles" (creates the 8 child categories under Hoverboards and adds the matching articles — 105 assignments on the replica — without removing anything; Undo removes exactly those assignments); "Create and assign the primary menu" (Undo restores the previous assignment); "Create draft trust pages" (Affiliate Disclosure, Editorial Policy, as drafts with plain wording for your review).
- **Hoverboards Guide → Products**: edit every recommended product — name, role, badge, why, best-for, wheels, max rider, speed, range, certification, Bluetooth, advantages, limitations, ASIN or search words, review slug, photo from the Media Library, **Verified** flag and date. Hide, add, restore defaults.
- **Customize → Hoverboards Guide**: 12 sections (brand, header, hero, homepage sections, topic images, affiliate, newsletter, articles, editorial transparency, SEO, social, footer).

### Affiliate conversion
- Tag `rcblogs-20` preserved everywhere at the time of 3.0.0 (replaced by `hoverboardsgu-20` in 3.5.1+, see the status note); theme links use tagged ASIN links where your own articles already use that ASIN (Gyroor Warrior `B07T63VSMC`, Hover-1 Helix `B0BXCVL6KD`, Swagtron T6 `B01N6NQOA6`) and tagged model-name searches otherwise (they never 404).
- Affiliate actions in the natural places: quick recommendations, comparison table, finder result, product boxes, verdict boxes, sidebar picks, and the article's own links. Wording: "Check price on Amazon", "View full review", "Compare specifications", "Read the buying guide".
- No fake urgency, prices, ratings, review counts, Prime claims or stock claims; Amazon trademark notice in the footer; disclosure in the footer, above the recommendations grid, and on every article with affiliate links.
- Measurement: when Site Kit's `gtag` is present, the theme sends GA4 events `affiliate_click` (product, placement, page), `finder_complete`, `compare_filter`, `compare_sort` and `finder_guide` clicks. No new tags, no duplicate analytics.

### SEO
- Meta description on every page (homepage text editable; posts use their excerpt; hubs use their description), Open Graph/Twitter tags, canonical on the homepage and archives (core already prints it on posts), `noindex,follow` on search results, author, date and attachment pages, homepage title override.
- Schema: Organization, WebSite + SearchAction, BlogPosting (author is the Organization when the fallback name is used — no invented person), BreadcrumbList, CollectionPage on hubs, FAQPage on the homepage only. The theme skips its Article/Breadcrumb output when a post already carries its own, and strips duplicated Article/Organization/WebSite objects from in-body JSON-LD while keeping FAQ/HowTo data the author added.
- Internal linking: every article now has read-next (3), related guides (3), hub links in the sidebar, breadcrumbs and same-category prev/next; the homepage links every hub, the key guides and the roundup; the footer lists hubs, guides and policies.
- URLs: **no slugs, permalinks or category URLs were changed**. Child categories created by the Setup tool add new URLs (`/category/hoverboards/hoverboards-for-kids/` …) without altering existing ones.

## 5. Images

- Bundled, licence-cleared photos (Unsplash / Pexels / Pixabay, credits in `hoverboards-guide/assets/images/CREDITS.md`) are used for the hero (LED-wheel hoverboard, Pixabay/Schäferle), kids (Pexels/Borishamer), adults, all-terrain (Pexels/Shuaizhi Tian), reviews (Pexels/Geoyul), beginners (Pexels/Borishamer), premium (Pexels/Gustavo Fring).
- Seven original SVG illustrations (budget, Bluetooth, safety, maintenance, charging, finder, terrain) replace photos where none of the bundled ones fit; they carry no licence obligations.
- Product cards show a labelled "Representative photo" or "Illustration" until you set a product photo in the Products editor. **Amazon product images were not downloaded**; use Amazon's Associates tools or manufacturer press material and keep the licence note in the media item's description.
- Every topic card and the hero accept an uploaded photo from the Customizer; cards keep a 4:3 frame and never shift the layout.
- Responsive `srcset`/`sizes`, lazy loading below the fold, eager + preload for the hero and article hero, `alt` on every image (empty for decorative ones).

## 6. Tests performed (local replica, Chromium, 11 October 2026)

52 automated checks (`tools/hoverboards-qa/qa.js`, results in `docs/hoverboards-qa-results.json`): **51 pass**.

- Homepage: one H1; meta description; one canonical; JSON-LD parses with FAQPage/WebSite/Organization; all 13 affiliate links tagged `rcblogs-20`, `rel="sponsored nofollow"`, new tab; no image without `alt`; hero not lazy-loaded; none of the v2 trust claims present; no stars or scores; no horizontal overflow; header fits at 1280, 1366, 1440 and 1920 px; 1 script, 2 stylesheets; all sections present.
- Interactions: search overlay opens with focus and closes on Escape; FAQ expands with correct ARIA; comparison filter "Kids & beginners" shows Helix, G13 and Edge only; sort by weight orders 420 › 265 › 265 › 220 › 220 › 176 › 176; finder child/rough → Gyroor G13 with 3 guides and a tagged link, adult/rough/high → Swagtron T6, teen/smooth/low → Hover-1 Helix; "Load more" appends 6 cards without duplicates.
- Article (Hover-1 Titan review): one H1 (duplicate removed); one canonical; one BlogPosting and one BreadcrumbList; breadcrumbs; disclosure shown and inline "#ad" strip removed; purple header removed and lede kept; table-of-contents links all resolve; 3 related + 3 read-next; content Amazon link tagged and sponsored; 6 tables wrapped for scrolling; no overflow; author box.
- Category: one H1, cards, canonical, description, pagination. Search: `noindex` + results. 404: status 404 with search and topic links.
- Mobile (390 px): drawer opens with 8 items and focus on the close button; no overflow on home or article; tables scroll inside; body text 18.5 px; smallest text 10.5 px (image labels only).
- Keyboard: first Tab lands on the skip link; dropdowns open with Arrow Down and close with Escape.
- axe-core 4.10 (WCAG 2 A/AA): **0 violations** on the homepage and the category page; **1 finding on the article page**, an inline `color:#94a3b8` caption inside the post body ("Price & availability on Amazon.com") — content, listed in the content review.
- Dashboard: Setup page loads; topic categories created (8) with 105 assignments; menu created and assigned, header follows it; draft trust pages created; Products editor saves (verified flag shows "checked against the manufacturer listing" on the homepage card); child category pages render; undo restores the menu and removes the categories.

Not tested: real-world CrUX data, PageSpeed Insights (blocked from the container), Site Kit's gtag events on the live domain, the live caching layer.

## 7. Performance (lab only, local server, phone 390 px, 4× CPU slowdown, ~1.6 Mbps, 150 ms latency)

| Page | LCP | CLS | Requests | DOM nodes |
|---|---|---|---|---|
| Homepage v3 | 1.64 s | 0 | 6 | 1,321 |
| Article v3 | 1.45 s | 0 | 8 | 864 |

Both inside the targets (LCP ≤ 2.5 s, CLS ≤ 0.1). v2's homepage loaded 57 images and two font families; v3 loads 16 images, one stylesheet and one deferred script. Measure the live site with PageSpeed Insights after installing and purging caches.

## 8. Plugins

None installed, removed or required. Site Kit (Analytics + AdSense) is untouched. If you later install Yoast, Rank Math, AIOSEO or SEOPress, the theme stops printing its own meta tags and schema automatically.

## 9. Install, then finish on your site

1. **Back up** (host snapshot or full files + database backup) and keep your `hoverboards-guide-v2.1.zip`.
2. Appearance → Themes → Add New → **Upload Theme** → `dist/hoverboards-guide-3.0.0.zip` → **Replace current with uploaded** (offered because the folder name is unchanged). Activate if asked.
3. **Hoverboards Guide → Setup**: run "Create topic categories and assign articles", then "Create and assign the primary menu", then "Create draft trust pages". Review the two drafts under Pages and publish them.
4. **Hoverboards Guide → Products**: open each product, check the figures against the current Amazon/manufacturer listing, correct them, tick Verified with today's date. Add a licensed product photo if you have one.
5. **Customize → Hoverboards Guide**: hero photo and copy, topic photos, affiliate disclosure text, newsletter service URL (only if you use one), editorial transparency paragraph, homepage meta description.
6. Users → Profile: display name and description.
7. Purge any cache (Cloudflare, caching plugin), then check the homepage, `/hover-1-titan-hoverboard/`, `/category/hoverboards/`, the finder and the comparison table on your phone.
8. Work through `docs/HOVERBOARDS-CONTENT-REVIEW.md`, starting with the High items.

## 10. Rollback

- Theme: re-upload the v2.1 zip with "Replace current with uploaded". v3 writes its products to a new option (`hbg_products_v3`) and reads the same Customizer keys only where they mean the same thing, so v2's settings are not overwritten.
- Setup actions: each has an Undo button on the Setup page (assignments removed, empty categories deleted, previous menu restored). Draft pages can be trashed.

## 11. Needs your decision or input

- **Credentials for me to deploy**: this session could not reach your admin. If you want the install and Setup steps done for you, add your WordPress login (or an application password) as environment secrets for this cloud environment rather than pasting them in chat.
- **Product facts**: the seven registry products come from your own roundups; their figures conflict between articles (see content review). Verify before ticking "Verified".
- **Photos**: licensed product photos for the six recommendation cards and the model reviews; a stronger hero photo (a rider with a helmet and pads would match the brief best).
- **Trust pages**: the drafted Affiliate Disclosure and Editorial Policy state that reviews are desk research. If you do test boards, say how.
- **Newsletter**: only enable it with a real service URL.
- **Secondary rides**: 103 scooter posts are reachable through "More rides" and the footer; if analytics show they matter more, promote the category in the Customizer menu.

## 12. Recommended next steps (traffic and affiliate clicks)

1. Fix the High items in the content review (ratings, "tested" wording) — Associates compliance first.
2. Verify the seven products and add licensed photos; then add `[hbg_product]` or `[hbg_specs]` boxes to the top of each single-model review so every review has the same verdict → specs → pros/cons → link order.
3. Add 2–4 in-copy links per roundup to the single-model reviews and from reviews back to the buying guide and the relevant hub.
4. Merge or refresh the stale-year and duplicate posts listed in the content review; redirect merged URLs with 301s (never to the homepage).
5. Watch GA4 for `affiliate_click` by placement and `finder_complete` to see which sections convert, then tune the quick-recommendation order.
6. Content gaps worth filling, based on what competitors rank for and you lack: a UL 2272 certificate-checking guide with photos of the marks, a "hoverboard weight limit by model" table, a go-kart attachment guide, and model-vs-model pages for the pairs people compare (Gyroor G2 vs Warrior, Hover-1 Titan vs Helix).
