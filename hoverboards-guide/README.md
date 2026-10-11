# Hoverboards Guide — WordPress theme v2.0 ("Rich Magazine" edition)

A post-driven magazine theme for hoverboard, electric-scooter and e-bike review sites.

## What's new in 2.0

**Magazine homepage (built from your real posts)**
- Featured stories module: 1 lead story + 4 secondary stories. Uses **sticky posts** first, then the latest (change under *Customize → Hoverboards Guide → Homepage Sections*).
- Category tiles with bundled photos (edit slugs under *Homepage Categories*).
- Latest reviews & guides grid with AJAX **Load more** + sidebar.
- Two category story blocks (Hoverboards / E Scooters by default), a Guides & how-tos row and an extra category row (eBikes by default).
- Editor's top picks, product grids, 60-second quiz, comparison table, buying-guide cards, newsletter banner, FAQ, trust section, final CTA — every section can be toggled off.

**Engagement**
- Sticky header with search overlay (`/` or `Ctrl/Cmd+K`), mega-style dropdowns, mobile drawer, dark-mode toggle (remembers the choice).
- Top bar with trending ticker (latest or most-viewed posts) and social icons.
- Single posts: breadcrumbs, author line, updated date, reading time, view counter, share buttons (sticky on wide screens), auto table of contents, affiliate disclosure, tags, newsletter box, author box, previous/next, related articles, styled comments.
- View counter (`hbg_views` post meta) powers "Most read" lists everywhere; counts are collected by an AJAX beacon so they work behind page caches.
- Newsletter capture stored in WordPress (*Appearance → Subscribers*, CSV export) or forwarded to Mailchimp/ConvertKit via an action URL.
- Widgets: HBG Popular Posts, HBG Category Posts, HBG Top Picks, HBG Newsletter. Widget areas: Sidebar, Homepage After Featured, Homepage Before Footer, Post After Content, Footer.
- Shortcodes for rich review content: `[hbg_product id="hiboy-s2-pro"]`, `[hbg_product name="…" search="…" image="…" score="8.4" range="25 mi" speed="20 mph" weight="220 lb"]`, `[hbg_pros_cons pros="a|b" cons="c"]`, `[hbg_box type="tip|warning|note|success" title="…"]…[/hbg_box]`, `[hbg_verdict score="8.8"]…[/hbg_verdict]`, `[hbg_button url="…" text="…"]`, `[hbg_top_picks]`, `[hbg_newsletter]`.

**Products & affiliate**
- All demo products are real, currently-sold models (Segway Ninebot MAX G2, Hiboy S2 Pro, Gotrax GXL V2, Varla Eagle One, Hover-1 Helios/Titan, Swagtron T6, Ninebot S, Ninebot ZING E10).
- Buttons use tagged Amazon **model-name search links** (`/s?k=…&tag=…`) so they never resolve to a dead ASIN. Put a verified ASIN (starting with `B0`) in a product's `search` field to link a listing directly.
- Prices are shown as tiers ("$250–$500") instead of fixed numbers to stay accurate and Amazon-compliant.
- Every Amazon link inside post content is automatically given `rel="sponsored nofollow noopener"` and `target="_blank"`.
- Edit products in `inc/theme-data.php` or via filters: `hbg_products`, `hbg_top_pick_ids`, `hbg_hoverboard_ids`, `hbg_scooter_ids`, `hbg_compare_ids`, `hbg_quiz_rules`, `hbg_faqs`, `hbg_buying_guide`, `hbg_category_presets`.

**Images**
- 29 royalty-free photos (Unsplash / Pexels / Pixabay licenses — free for commercial use, no attribution required) in `assets/images/` as compact WebP files (960 px for hero/category shots, 640 px for product cards) so the whole theme stays under typical 2 MB upload limits. Credits in `assets/images/CREDITS.md`. If your host allows larger uploads and you want sharper hero images, drop higher-resolution versions in with the same file names.
- Posts without a featured image automatically get a category-matched photo, so no card is ever blank.

## Setup checklist

1. Upload and activate the theme (Appearance → Themes → Add New → Upload).
2. Settings → Reading: set **Your homepage displays: A static page** and pick any page as Homepage (the theme's `front-page.php` takes over). Optionally set a Posts page for `/blog/`.
3. Appearance → Menus: create a menu with your categories and assign it to **Primary Menu (header)**. Without a menu the theme builds one from your categories automatically. Second-level items become dropdowns.
4. Mark 1–5 posts as **Sticky** to feature them (Quick Edit → Make this post sticky).
5. Customize → Hoverboards Guide: accent colour, social profiles, homepage sections, newsletter, single-post options, trust stats, footer.
6. Optional: run a thumbnail regeneration plugin once so older uploads get the new `hbg-card` sizes (the theme falls back to WordPress' built-in sizes, so this is cosmetic).
7. The sidebar shows the theme's curated set by default (search, most read, newsletter, top picks, categories). To use your own widgets instead, add them under Appearance → Widgets → Sidebar and set *Customize → Hoverboards Guide → Single Post → Sidebar content* to "Widgets".

## Requirements

WordPress 6.0+, PHP 7.4+. No plugins required. Works with Yoast / Rank Math (the theme skips its own Open Graph tags when one is active).

## Changelog

### 2.1.0 — "Convert" homepage (September 2026)
- New hero: headline, two calls to action (top picks / quiz) and a **quick picks** panel with the top products, scores, price tiers and "Check price" buttons above the fold.
- Editor's top picks moved directly under the category tiles; each card gains a **Read our review** link when the site has a review of that model (title match, cached daily).
- **Browse by topic** tabs (Hoverboards / E-Scooters / Guides / eBikes) replace the four stacked story rows; **Shop by use case** tabs replace the two separate product rows. The v2.0 rows remain as "Legacy" toggles.
- **Most read** strip replaces the homepage sidebar (which left an empty column). The sidebar can be re-enabled per Customizer.
- 16:9 card frames (no cropping of the new featured photos), loading shimmer on image frames, gradient CTAs, softer radii, `content-visibility` hints for faster rendering.
- Anchors that point into a tab (e.g. `#scooters`) open the right tab automatically.
- Popular / trending queries cached for 15 minutes; "All articles" links to the Posts page (or a custom URL) and hides otherwise; the theme TOC is skipped when the post already contains one.
