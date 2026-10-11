# BendersHub — theme upgrade report (Benders Hub Pro 1.0.0 → 2.0.0)

Date: 11 October 2026

## 1. What was and was not possible

| Item | Status |
|---|---|
| Your Chrome session / WordPress admin | **Not reachable.** This work ran in a cloud container that cannot control your browser. |
| bendershub.com (front end) | **Not reachable.** The container's network policy blocks the host, and the web-fetch tool could not resolve the domain. The live site's posts, plugins, analytics, Search Console and affiliate links were therefore **not inspected**. |
| Theme supplied | The uploaded zip is **"Benders Hub Pro" 1.0.0**, a custom theme. It is not a theme called "Rich Magazine". I upgraded this theme into a magazine theme (v2.0.0) rather than switching themes. |
| Testing | I installed WordPress 7.1.3 locally, recreated the site's likely content with the theme's own Repair Tool and Content Library, and tested v1 and v2 side by side. |
| Images | Image hosts were blocked from the container, so the photos are **licence-checked and imported from your server** using the new Image Library tool. Nothing has been imported yet. |
| Keyword metrics | Ahrefs (plan) and Semrush (API units) returned no data. **No search volumes or difficulty scores are claimed**; difficulty is a labelled qualitative estimate. |

**Completed:** the code audit, competitor and keyword research, the full theme rebuild, fixes to the tools, honesty and SEO, the image importer and the upgrade tools, local QA (56 automated browser checks pass, axe shows 0 WCAG A/AA violations, lab performance measured) and packaging.

**Still to do on your site:** steps 2–7 in §11, the published-content corrections in `CONTENT-REVIEW.md`, and the image import.

## 2. Original problems found in v1

### Trust and compliance (highest risk for an Amazon affiliate site)
- **Fictional products presented as tested picks.** Ten made-up products ("Pro Workshop 20-Ton Hydraulic Bender", "Industrial Multi-Stack CNC Bender" …) were shown as "Three benders we trust this season — workshop-tested … no paid placements". They carried invented scores (9.4/10 …) and specs ("repeatability ±0.1°", "cycle times under 8s"). The Repair Tool also published them as posts.
- **False structured data:** Product + Review schema with a default 9/10 rating on every equipment post; an ItemList of the fictional products; FAQ answers claiming review by "working fabricators".
- **Unsupported claims site-wide:** "Workshop Tested", "Reviewed by Fabricators" and "Updated 2026" badges on every page; "researched, tested, and reviewed by working fabricators"; "We only recommend tools we would use in our own workshop"; "Join 12,000+ Fabricators". The Methodology page said "How we workshop-test every tube bender".
- **The newsletter discarded email addresses** but replied "You're in. Check your inbox." whenever no endpoint was set.

### Tool and logic bugs
- **Calculator:** used the sheet-metal K-factor bend-allowance formula for tube (wrong); "cutting length" added a fixed 4" "mock" allowance; "wrinkle risk %" and springback came from invented coefficients; "ideal CLR" was simply max(2×OD, input).
- **Visualizer:** fixed 28 px per inch, so large CLRs fell off the canvas and the tube width wasn't proportional; printed invented "ID restriction 10–18%" values.
- **Quiz:** answering "Professional" anywhere always returned a CNC machine; tube diameter and the manual/hydraulic answer were ignored; results quoted "CLR ≥ 5× OD" for cages and "under-8s cycle times"; arrow-key navigation auto-advanced questions (keyboard users couldn't choose); auto-advance could double-skip questions.
- All public AJAX endpoints required a nonce, which breaks under full-page caching after 12–24 h.

### SEO and technical
- **Duplicate tags:** a second `rel=canonical` on every post (core already prints one), a duplicate search-page robots tag, and meta/OG output that would double up with Yoast or Rank Math.
- **Speed:** a hard-coded font preload URL that returns 404; four Google Font families; 7 CSS files and 11 JS files on every page; an animation script that hid every section until it scrolled into view.
- **Mobile:** horizontal overflow of 25 px on the homepage and 81 px on articles at 390 px wide. The homepage was 34,600 px tall on mobile (17 stacked sections).
- **Navigation:** primary and footer links pointed to pages that may not exist; there were no breadcrumbs, no table of contents and no related posts.
- **Admin bugs:** the System Audit page crashed (fatal error) once closure shortcodes existed; the Repair Tool's "schema check" printed "OK" without checking anything.

### Content (in your database, not fixable by a theme)
The starter articles contain wrong formulas and unverified safety-critical rule tables. `docs/CONTENT-REVIEW.md` lists each passage by priority.

## 3. Competitors researched

There are 14 rows; 12 sites were confirmed relevant. Full matrix, per-site notes and URLs: `docs/research/competitors-keywords-factcheck.md`.

- **Manufacturers:** Rogue Fabrication, Pro-Tools, JD Squared, Baileigh, Mittler Bros, Woodward Fab, SWAG Off Road, Eastwood.
- **Retailers:** Trick-Tools, Burns Stainless, Stainless Headers.
- **Trade press:** The Fabricator / Tube & Pipe Journal.
- **Forums:** Garage Journal and others.
- **Affiliate aggregators and parasite pages:** ShopSavvy and others.

Competitor pages could only be read through search-index summaries. Design, photography and page speed are therefore recorded as unknown.

**Main finding:** the commercial searches ("best tube bender", "…for roll cage", "best hydraulic tube bender", "best tube notcher") return mostly parasite or spam pages and an aggregator that lists a rebar bender as a tube bender. No independent specialist site appears there.

Gaps BendersHub can own:
- buying guides split by application, with a published method;
- a cross-brand capacity and die-compatibility table;
- calculators that show their formulas;
- notes reconciling contradictory manufacturer specs;
- roll-cage pages that point to current rulebooks.

## 4. What changed in the theme

### Design system
- **Colours and type:** light editorial layout with graphite text and one safety-orange accent (`#c2410c`, AA contrast on white). Steel blue is reserved for technical diagrams.
- **Fonts:** Archivo (headings), Inter (body) and JetBrains Mono (numbers). All are **self-hosted** (SIL OFL), so pages make no requests to Google.
- **Components:** one stylesheet, consistent 3:2 and 16:9 crops, quiet borders and shadows, no decorative animation, and respect for reduced-motion settings.
- **Logo:** a scalable SVG mark (a tube bent around its die) with the wordmark. It works on light and dark backgrounds, and the Custom Logo setting still overrides it.

### Homepage (sections reorderable under Customizer → Benders Hub → Homepage: sections)
- **A. Header:** compact sticky header with the primary menu, search, a "Find my bender" button and an accessible mobile menu. The disclosure strip states the exact Amazon Associates sentence.
- **B. Lead story:** your chosen post, else the newest sticky post, else the newest post. Beside it sit the H1 and introduction, two CTAs, project chips and three secondary stories.
- **C. Tools console:** tabbed access to the quiz, calculator, visualizer, comparison and five more tools. **Every v1 anchor still works** (`/#bdh-quiz`, `/#bdh-calculator`, …), including links inside your articles and menus.
- **D. Buying guides:** from your "guides" category.
- **E. Equipment by need:** six editorial categories (beginner, home shop, exhaust, cage/chassis, pro/production, heavy pipe). Each says what to look for and what to watch out for, links to a guide and Amazon, and is labelled "not hands-on tests".
- **F. Latest articles:** cards with a progressive "Load more".
- **G. Learn by project:** seven pathways plus a quiz card. Each links to existing guides and opens the quiz preset for that profile.
- **H. Knowledge hub:** nine definitions with formulas (CLR, D of bend, wall factor, springback, arc length, tangent, ovality, processes, tube vs pipe).
- **I. Essential reads:** an editor's selection. It is not presented as "most popular", because there is no analytics data behind it.
- **J. FAQ** (rewritten for accuracy), the **newsletter** and the **editorial policy**. The footer has tools, topics, trust pages and the full disclosure.

### Articles
- **Header:** breadcrumbs, category, H1, standfirst and a byline with author, published and meaningful updated dates, and reading time.
- **Navigation:** a sticky table of contents on desktop and a collapsible one on mobile.
- **Disclosure and safety:** a disclosure box appears automatically when the post contains affiliate links. A safety notice appears on roll-cage and rulebook topics.
- **Typography:** formulas, tables (scroll inside the column on phones, with sticky first column), callouts and figure credits are all styled.
- **After the article:** a "Put it to work" box with tools chosen by topic, tags, an author box (only when a real bio exists) and related posts.
- **New shortcodes for editors:**
  - `[bdh_product]`: product card with verified specs, advantages, limitations, compatibility, who should and shouldn't buy, the Amazon link and the disclosure. No ratings, prices or stock.
  - `[bdh_comparison]`, `[bdh_callout type="safety|tip|note|warning"]`, `[bdh_formula expr="…" vars="…"]`, `[bdh_disclosure]` and `[bdh_image_credits]`.

### Tools (logic changed only where v1 was wrong; see §8 for tests)
- **Calculator:** exact geometry in inches or mm.
  - Arc = CLR × θ; tangent = CLR × tan(θ/2); cut length from leg dimensions plus trim.
  - D-of-bend, wall factor, inside and outside radius, and theoretical outer-wall stretch.
  - Input validation and difficulty notes labelled as rules of thumb with sources.
  - A **springback helper that works from your own test bend**.
- **Visualizer:** draws the bend to true scale and auto-fits any CLR. Shows tangent points, intersection and the bore, and handles invalid values.
- **Quiz:** every answer is scored across nine equipment types. The result explains why, offers a runner-up and lists what to check before buying. It stays keyboard-accessible, keeps answers when you go back, and links to Amazon with the tag.
- **Comparison:**
  - A qualitative bender-type table.
  - A published-spec table for JD2 Model 32, Pro-Tools 105HD, Rogue Fab M605/M625, Baileigh RDB-050 and the Harbor Freight 12-ton. Each row links its manufacturer source and flags spec conflicts.
- **Other tools:** the equipment database became an honest equipment-types explorer; the tooling checklist and prioritizer were reworded so they no longer invent tonnages.

## 5. Images

Image hosts were unreachable from here, so I built a **licence-checked importer** (Benders Hub → Image Library) and a list of **33 candidate photos** from Wikimedia Commons and Pexels. All candidates and their records are in `docs/research/image-candidates.md`.

- **Licence check:** your server re-checks each Commons file's licence against the Commons API before downloading. NC, ND and missing files are refused; tested with simulated API responses.
- **Approval:** you approve each preview.
- **Media Library records:** each import gets a descriptive filename, your alt text, a credit caption, and source, creator, licence, licence URL and date fields.
- **Use in the theme:** imported photos become topic images automatically. A post without a featured image shows a topic-matched photo, rotated so neighbouring cards differ, never an unrelated machine. You can then set them as featured images in one step.
- **Gaps:** CNC machine, dies, bent-tube close-up and measuring. **Your own workshop photos would be the strongest choice for these**, especially for product reviews.
- **Amazon product images** are not downloaded. Use Amazon's own image tools only as the Associates programme permits.

## 6. SEO changes
- **Plugin detection:** if Yoast, Rank Math, AIOSEO, SEOPress or The SEO Framework is active, the theme prints no meta tags or schema. Otherwise it prints a description, OG and Twitter tags, and a canonical only where core doesn't.
- **Schema:**
  - Always: Organization, WebSite + SearchAction, BlogPosting (with author Person, dates, image and section) and BreadcrumbList.
  - FAQPage only where the FAQ is visible.
  - Product + Review only for equipment you mark "hands-on tested" with a score.
- **Indexing:** noindex is applied to the fictional v1 equipment entries. Archive titles lose the "Category:" prefix. Breadcrumbs appear on all non-home pages.
- **Headings:** one H1 per page, logical H2 and H3 below it, and stable heading IDs for the table of contents.
- **URLs:** no permalink or slug changes. v1's custom post types (`/equipment/`, `/guides/`), shortcodes and anchors are all kept.

## 7. Affiliate changes
- **Tracking tag:** `rcblogs-20` is preserved. Content links get the tag only if they have none; **an existing tag is never replaced**. Links get `rel="nofollow sponsored noopener"`, merged with any existing rel (v1 wrote duplicate attributes).
- **Disclosure:** the exact text "As an Amazon Associate I earn from qualifying purchases." appears in the header strip, beside every CTA group, at the top of articles with affiliate links and in the footer. The footer also says the site is not affiliated with or endorsed by Amazon.
- **Link wording:** search links say "See options on Amazon". "Check price on Amazon" is used only for real product URLs. No prices, ratings, discounts or scarcity are shown.
- **Equipment editor:** new fields for evaluation type (research vs hands-on), the Amazon product URL, the spec source URL, compatibility, who should buy and who should consider an alternative.

## 8. Interactive features tested (local WordPress 7.1.3, Chromium)

**56/56 automated checks pass.** Highlights:

- **Calculator:** 6" CLR at 90° gives arc 9.425", tangent 6.000", cut 39.675" for 24"/18" legs plus 0.25" trim, D = 3.43, wall factor 14.6. A 45° tangent gives 2.485". Wall ≥ OD/2 and legs shorter than the tangent are rejected. 180° is handled. Switching units converts the inputs.
- **Springback helper:** a 90° bend reading 86.5° gives 3.5° of springback and suggests 46.8–48.5° to finish at 45°.
- **Visualizer:** rescales for large CLRs, computes D = 11.43 for 20/1.75, and flags a CLR below OD/2.
- **Quiz:** eight scenarios return sensible types. A "professional" answer no longer forces CNC. Back keeps answers, an answer is required, arrow keys don't auto-advance, a pointer click advances exactly one step, and `?profile=` presets work. Amazon links carry the tag.
- **Console and site:** every tool tab and v1 deep link works.
  - Load more appends posts with no duplicates.
  - Newsletter: validation works and sign-ups are stored.
  - Mobile menu and search work, and Escape closes them.
  - The table of contents resolves every link, there is one canonical tag and the JSON-LD parses.
  - Fictional equipment is noindex, has no schema and shows no score.
  - No unsupported claims are left in the templates, all Amazon links on the homepage are tagged and sponsored, and there are no JS errors.
- **Admin:** dashboard, Upgrade, Image Library, Content Library, Audit, Subscribers, equipment editor and Customizer all load without PHP errors. The Upgrade actions work and skip pages you've already edited.
- **Accessibility:** axe-core shows 0 WCAG 2 A/AA violations on the homepage (desktop and mobile), an article, the quiz, a category page and the equipment page.
- **Layout:** no horizontal overflow at 1440, 820 or 390 px on any tested page.

## 9. Performance (lab only, local server, mobile 390 px, 4× CPU slowdown, ~1.6 Mbps, 150 ms latency, median of 3)

| Page | Theme | LCP | CLS | Requests | DOM nodes |
|---|---|---|---|---|---|
| Homepage | v1 | 1.12 s | 0 | 23 | 2,809 |
| Homepage | v2 | 1.13 s | 0 | 17 | 2,443 |
| Article | v1 | 1.01 s | 0 | 25 | 403 |
| Article | v2 | 1.04 s | 0.02 | 7 | 546 |

Both versions are within the targets (LCP ≤ 2.5 s, CLS ≤ 0.1). v1's figures are flattered: its Google Fonts requests could not load in this sandbox, but on the live site they add a third-party connection and four font families. v2 sends far fewer requests, especially on articles (7 vs 25). The homepage mobile height fell from 34,600 px to 21,900 px.

These are **lab results only**. There is no field (CrUX) data. Measure the live site with PageSpeed Insights after deploying.

## 10. Plugins

No plugins were installed, removed or required. The theme does not depend on any plugin.

## 11. Install, then finish on your site

1. **Back up.** Take a host snapshot or a full backup (files + database) and confirm it can be restored. Keep your original `benders-hub-pro.zip`.
2. **Install the theme.** Go to Appearance → Themes → Add New → Upload Theme → `dist/benders-hub-pro-2.0.0.zip` and choose **"Replace current with uploaded"**.
   - WordPress offers "Replace" only if your current theme folder is named `benders-hub-pro`.
   - If it installs as a separate theme instead, your Customizer settings (stored per theme folder) won't carry over. In that case, tell me the folder name and I'll rebuild the zip under it.
3. **Benders Hub → Upgrade to v2:** replace the placeholder trust pages, optionally draft the fictional equipment, and create the Magazine Menu.
4. **Benders Hub → Image Library:** check licences, review and import, set featured images, and create the Image credits page.
5. **Customizer → Benders Hub → Magazine:** choose the lead story, the buying-guides category and the essential reads.
6. **Fix the published content** listed in `docs/CONTENT-REVIEW.md`, starting with the High items.
7. **Purge caches,** then test the homepage, an article, `/#bdh-calculator` and the quiz on your phone.

## 12. Rollback

| Change | How to undo |
|---|---|
| Theme | Re-upload the original zip with "Replace current with uploaded". v1's settings were never overwritten: v2 stores its homepage layout under a new key and leaves v1's untouched. |
| Trust pages | Each page's Revisions screen has the previous version. |
| Equipment entries | Upgrade page → "Restore (publish again)". |
| Menu | Upgrade page → "Switch back to previous menu". |
| Imported images | Ordinary Media Library items; delete them there. |

## 13. Needs your decision or input
- **Contact, privacy, terms and cookie pages** need your own details and legal text.
- **About page:** add who runs the site, with only credentials you can evidence (there is a hidden note in the draft).
- **Hands-on testing:** only mark products "Hands-on tested" if you test them, and describe the test.
- **Direct Amazon product links:** whether JD2, Rogue Fab, Pro-Tools and Baileigh machines are sold on Amazon was **not verified**. Their comparison rows link manufacturer pages; add Amazon URLs in `bdh_reference_benders()` only after checking.
- **Optional data:** Search Console and analytics access (to choose essential reads from real data), and Semrush API units or an Ahrefs plan (for real keyword volumes).

## 14. Recommended next content (from the research; P1 order)
1. Explainers that make the tools credible: CLR explained, bend-length and cut-length formulas, and "pipe bender vs tube bender".
2. Flagship comparison of six home-shop benders (JD2 32 / M605 / RDB-050 / 105HD / HF 12-ton / WFB2-Eastwood), plus a cross-brand capacity table and a die-compatibility matrix.
3. Roundups where search results are weakest: best bender for roll cages, best hydraulic bender, best exhaust bender for a home garage, best tube notcher.
4. Safety-checked roll-cage reference pages (rulebook pointers with edition dates), a springback guide using the test-bend method, and a wrinkle/kink/flattening troubleshooting guide.

Keep each guide honest about research vs testing, add your own workshop photos where you can, and link to the matching tool.

## 15. Live installation (11 October 2026)

After `bendershub.com` was added to this environment's allowed domains, the live site was inspected and the theme installed.

**What the live site contained before installation:**
- A fresh WordPress 7.1.3 install with Twenty Twenty-Five active (Twenty Twenty-Four and Twenty Twenty-Three also installed).
- One post ("Hello world!"), no pages and no media.
- Site title "My Blog" and permalinks `/%year%/%monthnum%/%day%/%postname%/`.
- Plugins: Classic Editor, W3 Total Cache, Loginizer (+Pro) and FileOrganizer (+Pro) active; Akismet and Hello Dolly inactive.
- Cloudflare in front, not caching HTML. `wp-login.php` has a JavaScript "Checking your browser" gate.
- The v1 Benders Hub content described in sections 2 and `CONTENT-REVIEW.md` is **not** on the live site, so that list only applies if v1 content is installed later.

**Done:**
- Uploaded `benders-hub-pro-2.0.0.zip`, activated "Benders Hub Pro" and purged the W3 Total Cache.
- No content, settings, menus, users or plugins were changed.

**Verified on the live site:**
- Homepage at desktop and mobile: no PHP errors and no horizontal overflow.
- Calculator: 9.425" arc and 39.675" cut for the default inputs.
- Visualizer and quiz work; quiz Amazon links carry `rcblogs-20`.
- JSON-LD parses; the existing post, search and 404 pages render.
- Theme admin pages (dashboard, Upgrade, Image Library, Audit, Customizer) load.
- The only browser error was Cloudflare's Web Analytics beacon, which this test environment blocks; real visitors are unaffected.

**Rollback:** Appearance → Themes → activate Twenty Twenty-Five.

**Still needed before launch** (with the owner's go-ahead):
- Site title and tagline.
- A public display name: the admin username currently shows as the author name.
- Remove "Hello world!".
- Trust pages, tool pages and menu: Repair Setup, then Upgrade to v2.
- Photos: Image Library.
- Real articles: none exist yet. Content Library starter articles are created as drafts for review.
