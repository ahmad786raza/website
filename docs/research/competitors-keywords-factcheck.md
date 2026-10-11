# BendersHub.com: competitor, keyword gap and technical fact-check research

Prepared: 2026-10-10. Scope: competitors only. bendershub.com itself was not researched.

---

## 0. Method, access limits and how to read this file

**What worked**
- **WebSearch (US results)** worked. It returns the result URLs plus a summary drawn from the indexed page text. Every observation below comes from those results. I also ran site-restricted searches (for example, `allowed_domains: roguefab.com`) to pull content from specific competitor pages.

**What did not work. Please read before relying on design or UX notes.**
- **WebFetch failed for every host** (`getaddrinfo ENOTFOUND`, including wikipedia.org). Direct `curl` was refused by the session's egress policy (HTTP 403 CONNECT). **No competitor page was opened directly.** That means:
  - Visual design, layout, real-photo use, mobile behaviour and page speed **could not be observed**. These are marked "unknown" unless the indexed text described them (for example, "video", "PDF manual", "chart").
  - Where a note says "page has X", it means the indexed text of that page says so.
- **motortrend.com / hotrod.com and popularmechanics.com are blocked** for the search tool's user agent. Hot Rod/MotorTrend tech content could not be assessed.
- **Ahrefs:** "Insufficient plan". No keyword volume or difficulty data was available.
- **Semrush:** separate notice, reported as returned. The Semrush account has an active subscription, but it does not have enough API units to complete this request. Additional API units are required, and the options are at https://www.semrush.com/mcp-access.
- As a result, **no search volume or keyword difficulty figure in this file comes from a tool.** Volume is "not verified" everywhere. Difficulty is a **qualitative estimate (QE)** based on the kind of sites the search tool returned for the query (forums and spam = easier; manufacturer, trade press and big retailer = harder).
- **"Ranks"** means "was returned by the search tool for that query in October 2026". It is not a Google position from rank tracking.
- No traffic, revenue or conversion numbers are given anywhere, because none could be verified.

---

## PART 1: Competitor research

### 1.1 SERP pattern: who actually shows up

| Query tested | What the search tool returned (summary) |
|---|---|
| best tube bender | shopsavvy.com/best-picks/best-tube-benders; thedrive.com/reviews/37615/tube-bender (dated 2023); then **parasite/spam pages**: findingdulcinea.com, peggyomara.com, sophiecress.com, electric-pipe-and-tube-bending-machine.onrender.com |
| best hydraulic tube bender | shopsavvy; onrender spam; narcosis-the-game.com ("8 Best Hydraulic Pipe Benders (September 2026)"); projectwet.org node spam; a grassrootsmotorsports.com forum thread |
| roll cage tube bender / best tube bender for roll cage | Almost entirely spam or hijacked domains: i.building.co.uk, community.internxt.com, wiki.dronebotworkshop.com, montecitofire.com, visitoverlandpark.com, ovrclock.com, aerovisiondrones.com, rosenberryrooms.com. One forum (pickuptrucktalk.com) |
| best tube notcher for roll cage | peggyomara.com, findingdulcinea, farmstandapp.com, affordanything / g-form subdomain spam, **roguefab.com notching guide**, speedwaymotors.com Speedy Notcher, garagejournal |
| tube bender buying guide | surplusrecord.com, trick-tools.com/tube-bender-buyers-guide, revelationmachinery.com, tubeformsolutions.com checklist, roguefab.com, thedrive.com, alibaba smartbuy |
| manual vs hydraulic tube bender | whatispiping.com/tube-benders, vevor.com category, cormak.pl blog, tubes-international, raptorsupplies, mfgrobots |
| mandrel bending exhaust | viperperformance.co.uk blog, dragzine.com / enginelabs.com (Burns Stainless video), mfgrobots, jegs.com tech article, motoiq.com, roguefab.com/exhaust-pipe-benders |
| centerline radius tube bending | thefabricator.com/glossary/centerline-radius, aisc.org glossary, blog.blmgroup.com, h-pproducts.com CLR calculator, timelesstube.com, fsae.com forum |
| tube bending calculator | stainlessheaders.com (cut-length and J-bend optimiser), everycalculators.com, CAD-forum pages, PDF spam |

**Key finding:** the main commercial head terms ("best tube bender", "best tube bender for roll cage", "best hydraulic tube bender", "best tube notcher") are **dominated by thin AI or parasite affiliate pages and an aggregator (ShopSavvy)**. Specialist manufacturers rank for technical and educational queries. No independent, test-led, specialist editorial site appeared for the commercial queries. This is the clearest opening for BendersHub.

### 1.2 Competitor matrix

| Competitor | Main audience | Strongest content types | Best design features (observable via index only) | Useful tools / resources | Weaknesses or content gaps | Opportunities for BendersHub |
|---|---|---|---|---|---|---|
| **Rogue Fabrication** (roguefab.com) | Home and pro fabricators, off-road, motorsport, cage builders | Large "Tech Index": Bending 101, Types of Bending, How to Build a Roll Cage, Exhaust Pipe Benders, Stainless / Aluminium / Titanium bending pages, "Best Tube Benders Compared", notching guide, FAQs, videos | Clear Tech / Design Index menu that splits learning from shopping; product → capacity chart → tech page cross-links; Bender Configurator | Tube Capacity Chart (min/max wall by OD, CLR and material); Design Index calculators (bend-in-middle, double-90/U, rolled radius, bump bend); Tube Strength Calculator; Bend Quality calculator; 2025 PDF catalogue | Vendor-biased comparisons in which its own M6xx always wins. **Internal inconsistencies:** M605 max wall listed as .188" and as .250"; prices differ between FAQ, catalogue and store; capacity chart about 915 days old with "TBD" cells; some catalogue tables garbled | Neutral, cross-brand comparisons with normalised specs; a "spec discrepancy" note on vendor claims; an independent cross-brand capacity table; date-stamped spec verification |
| **Pro-Tools** (pro-tools.com) | Home/pro fabricators, schools, industrial maintenance | Blog: Tube & Pipe Bending Basics, Springback, Wall Factor & D of Bend, Choosing CLR, eliminating Twist. Die pages with structured specs | Shopify-style die pages with consistent spec fields (OD, CLR, max degree "after springback") | Die charts (min wall by OD and CLR); Bend-Tech software resale; collection pages for manual and powered benders | Blog posts roughly 11–13 years old; no free calculator; content rated for mild-steel Sch 40 with "contact us" for other materials; no chromoly-specific springback data | Up-to-date explainers with worked numeric examples and an interactive D-of-bend / wall-factor checker; material-specific guidance (4130, 304, 6061) |
| **JD Squared / JD2** (jd2.com, support.jd2.com) | Hobby and pro fabricators, off-road | PDF assembly and operating manuals (Model 32, 3, 4, 6), Template Bending Tutorial PDF | Unknown (pages index with the generic title "JD Squared, Inc.", which suggests weak on-page SEO titles) | Manuals with bend-length formula (CLR × deg × .0175), springback advice, cylinder spec (10-ton, 14" stroke); die-set catalogue | Little HTML educational content; Bend-Tech products listed as discontinued; max bend angle not clearly stated on product page; no comparison or buying content | Turn manual-only knowledge into searchable guides; "JD2 Model 32 setup and first bends" style guides; spec sheets that state bend angle explicitly |
| **Baileigh Industrial** (baileigh.com, blog.baileigh.com) | Industrial and job shops, education, serious hobbyists | Product pages with structured specs; operator manuals (RDB-175, RDB-480, RDB-325); blog (Basic Bending Concepts, RDB-050, off-road tube bending) | Faceted category hierarchy (manual rotary draw, hydraulic rotary draw, CNC/programmable, tooling) | Manuals with springback procedure and arc-length constant; die-set pages listing CLR; Bend-Tech Pro resale | Blog is old and thin; spec conflicts (RDB-050: "up to 200°" in copy vs "190" in spec table); little comparative content | Industrial-to-hobby bridge content (when an RDB-250 / CNC makes sense); explaining the price gap between manual and programmable machines |
| **Mittler Bros** (mittlerbros.com) | Racers, chassis builders | Product-led pages for hydraulic 180° bender, shoes, Bend-Tech, notcher accessories | Unknown | Bend-Tech EZ / EZ-3D / Pro pages (cage design); accessory catalogue (degree wheels, stops) | Little educational content; specs scattered; no comparisons | Chassis-specific tooling guides (degree readouts, bend stops, vise jaws for bent tube) |
| **Woodward Fab** (woodwardfab.com) | Budget hobbyists, DIY | Blog how-tos: assembling the WFB2, "how to use a pipe bender" parts I/II, tips posts; product videos | Unknown | Assembly guide; general bending tips | Generic, beginner-level, light on numbers; the WFB2's bend-angle limit (~120° on many dies) is not foregrounded | Honest WFB2 owner-style review, upgrade paths (hydraulic, stand, dies), and "WFB2-compatible die clones" explainer |
| **SWAG Off Road** (swagoffroad.com) | Off-road DIY, budget cage builders | Product pages: REV 2 bender, 50+ die sizes, roller dies for HULK / Harbor Freight roller | Unknown | Die listings with OD, degree and CLR in title (good spec hygiene) | Vendor content only; "no warranty on dies"; dies proprietary (not cross-compatible) | Die cost-of-ownership comparisons across brands; HF tube-roller upgrade guide (rolled hoops) |
| **Eastwood** (eastwood.com, /garage/) | Restorers and hot-rod DIY | Product pages; instruction PDFs (High-Capacity Bender #21115, Hydraulic #22199); "The Garage" articles (for example, café-racer bars) | Unknown | PDFs with capacity limits (2" × .120 mild steel; chromoly .090 wall; 120°/240° dies → 90°/180° max recommended) | No standalone tube-bending tech article found; content sits in PDFs | Searchable guides for Eastwood owners; brake-line bending cluster (small-tube tools) |
| **The Fabricator / Tube & Pipe Journal** (thefabricator.com) | Industrial engineers, shop managers | Deep trade articles: Tube bending basics, 4 essential modes, Selecting the right mandrel and wiper, Wipe out the wrinkles, Managing rotary-draw bending, glossary | Unknown (strong glossary and article taxonomy implied) | Glossary (CLR definition); mandrel/wiper selection chart via wall factor × D of bend | Industrial voice, not written for home-shop buyers; no product recommendations or affiliate guidance; many articles predate current machines | Translate the engineering into hobby/pro-shop language with calculators; cite these as authority sources |
| **Trick-Tools** (trick-tools.com) | Pro and hobby fabricators (retailer) | "Tube Bender Buyer's Guide", "Common Issues with Tube Bending" (8 defects: causes and fixes), Q&A pages (die compatibility, electric vs air pumps) | Unknown | Q&A knowledge base; mandrel bending PDF | Retailer bias; buyer's guide depth not confirmed (not in index text) | Defect troubleshooting with photos and diagrams; independent die-compatibility matrix |
| **Burns Stainless** (burnsstainless.com) | Performance exhaust builders | Blog articles: Welded vs Seamless (Tubing Basics), Welding Exhaust Systems, Yield Strength; product pages with OD, gauge, CLR and leg lengths | Unknown | Spec-rich product pages | Exhaust only; no bender-buying content | Exhaust-fabrication pillar that links materials → bends → tools; "buy mandrel bends vs own a bender" economics |
| **Stainless Headers** (stainlessheaders.com) | Header and exhaust fabricators | Calculators & Guides page; Tube Cut Length / Bend Allowance calculator; J-Bend Cut Optimizer; header mock-up kits | Unknown | **Two calculators that rank for "tube bending calculator"** | Narrow (headers); calculators are utility pages with little teaching around them | Better calculator UX with geometry visualiser and explained formulas; embed tangent / setback / developed length |
| **Garage Journal forum** (garagejournal.com) and other forums (grassrootsmotorsports, pirate-style off-road forums) | Hobbyists | Real-owner threads ("Tube bender", "Looking to buy a tubing bender", "Good tube bender?") | n/a (UGC) | Anecdotal ownership experience | Old, contradictory, unstructured; no specs normalisation | Synthesise owner consensus into structured "what owners say" boxes (cite threads, don't copy) |
| **ShopSavvy and parasite affiliate pages** (shopsavvy.com, findingdulcinea.com, peggyomara.com, ovrclock.com, aerovisiondrones.com, etc.) | Generic shoppers | "Best X" roundups | n/a | ShopSavvy price tracking | ShopSavvy is roughly 511 days old by its own metadata, mixes a rebar bender and a RIDGID copper bender into the "tube bender" list, gives thin methodology; parasite pages invent products (for example, "TubeMaster 3000", "Hydrotech") | **Biggest gap:** application-segmented, test-led roundups (cage / exhaust / brake line / chassis) with real photos, a methodology page and dated spec verification |
| **Retail tech: JEGS, Speedway Motors, Summit Racing, BangShift (media)** | Racers and hot-rodders | JEGS "A Guide to Roll Cages: Designs and Tubing"; BangShift video posts (Rogue Fab layout video, Cage Gage, McAmis bending and notching) | Unknown | Speedway / Summit product Q&A and reviews | JEGS says nothing on bending yourself; BangShift is embed-and-comment; Summit had no buying guide in results | Original roll-cage bending workflow content with diagrams; tie rulebook data to bender and die choice |

Industrial vendors **Hines Bending Systems** (hinesbending.com: mandrel buyer's spec guide, thin-wall defects) and **Tube Form Solutions** (tubeformsolutions.com: buying checklist, "Basics of Tube Bending" PDF, formula sheet) also showed up for technical and CNC queries. They are worth citing for D-of-bend / wall-factor content. They are not consumer competitors.

### 1.3 Cross-competitor assessment by criterion

- **Homepage and editorial design:** unknown for all (no direct fetch). From the index, only Rogue Fab clearly separates a learning hub (Tech Index / Design Index) from the store. The others are store-first.
- **Navigation and category hierarchy:** Baileigh has the clearest machine taxonomy (manual rotary draw / hydraulic rotary draw / programmable / tooling). Rogue Fab has the clearest education taxonomy. JD2 page titles are generic ("JD Squared, Inc."), which suggests weak title tags. This is an inference, not a measurement.
- **Technical article and buying-guide quality:** strongest are The Fabricator (engineering), Pro-Tools blog (concise concepts, but old) and Rogue Fab (broad, current, vendor-biased). Buying guides that rank are either vendor-biased (Rogue Fab, Trick-Tools) or thin (ShopSavvy, parasites).
- **Real machinery photos and diagrams:** unknown (not observable). The Rogue Fab exhaust page's indexed image alt text ("2.75 inch mandrel bent exhaust tube 1D bend 0.9 percent deformation") suggests it uses real measured-bend photos.
- **Product comparison methods:** Rogue Fab sorts by machine type (mandrel, ram, vertical rotary draw, horizontal arm-frame, bottle-jack). ShopSavvy uses an opaque score. No one publishes a reproducible test protocol (for example, measured ovality and springback on the same 1.75 × .120 DOM across machines).
- **Specs and compatibility info:** Rogue Fab capacity chart; Pro-Tools die charts; Swag and Baileigh die listings with CLR. **There is no cross-brand die-compatibility resource.** Trick-Tools Q&A states JD2 dies generally do not line up on a Pro-Tools 105HD (pin pattern / zero indexing).
- **Calculators and educational resources:** Rogue Fab (several), Stainless Headers (cut length, J-bend), H&P Products (CLR calculator), everycalculators (generic). Bend-Tech is paid software resold by Pro-Tools, Baileigh, Mittler and JD2 (JD2 lists it as discontinued).
- **Mobile and page speed:** unknown for all.
- **Internal linking (guides ↔ products):** Rogue Fab is the strongest (tech pages → product pages → capacity chart). Others are mostly product-only.
- **Affiliate and commercial CTAs:** manufacturers sell direct. ShopSavvy and parasite sites use affiliate/price-comparison CTAs. Forum anecdote says JD2 sells direct only (unverified). Amazon availability of specific models was **not verified**.
- **Trust signals, authorship and sourcing:** The Fabricator has bylined experts. BangShift has a named author. Vendor blogs are mostly unbylined. ShopSavvy and the parasite pages have no meaningful authorship or test evidence.

### 1.4 Per-competitor notes and URLs surfaced

All URLs below were surfaced and summarised by the search tool. None was opened directly; see section 0.

**Rogue Fabrication**
- https://www.roguefab.com/ · https://www.roguefab.com/tech/ · https://www.roguefab.com/design-index/ · https://www.roguefab.com/bending-101/ · https://www.roguefab.com/tube-pipe-bending-types/ · https://www.roguefab.com/best-tube-benders-compared/ · https://www.roguefab.com/exhaust-pipe-benders/ · https://www.roguefab.com/stainless-steel-tube-bender/ · https://www.roguefab.com/aluminum-pipe-benders/ · https://www.roguefab.com/titanium-tube-bender/ · https://www.roguefab.com/building-roll-cage/ · https://www.roguefab.com/tube-capacity-chart/ · https://www.roguefab.com/die-selection/ · https://www.roguefab.com/bender-configurator/ · https://www.roguefab.com/tube-calculator/ · https://www.roguefab.com/product-category/tubing-benders/ · https://www.roguefab.com/product/metal-tube-bender-m605-1-die/ · https://www.roguefab.com/product/metal-tube-bender-m625-1-die/ · https://www.roguefab.com/product/mandrelbender/ · https://www.roguefab.com/product/m600-elechydro/ · https://www.roguefab.com/reviews/ · https://www.roguefab.com/faqs/ · https://www.roguefab.com/wp-content/uploads/2025/04/2025-Product-Catalog.pdf
- Notes: the comparison page groups machines by type and positions JD2 / Pro-Tools as needing floor anchoring and the Woodward WFB2 as cheapest but often limited to about 120°. Its exhaust guidance says ram benders suit repair work, not performance exhaust. Its aluminium guidance recommends about 3.5–4D minimum CLR and warns that 3D is marginal for 6061-T6.

**Pro-Tools**
- https://pro-tools.com/blogs/protoolsusa · https://pro-tools.com/blogs/protoolsusa/18621927-tube-and-pipe-bending-basics · https://pro-tools.com/blogs/protoolsusa/42288387-wall-factor-and-d-of-bend-getting-to-the-center-of-the-centerline-radius-debate · https://pro-tools.com/blogs/protoolsusa/19923715-springback-to-basics-defining-springback-and-how-to-compensate-for-it · https://pro-tools.com/blogs/protoolsusa/19180859-dont-get-it-twisted-how-to-eliminate-twist-when-bending-tube-or-pipe · https://pro-tools.com/blogs/protoolsusa/19204299-choosing-the-right-centerline-radius-for-your-next-project · https://pro-tools.com/products/manual-tube-and-pipe-bender-105-heavy-duty-package · https://pro-tools.com/products/hydraulic-tube-and-pipe-bender-105-heavy-duty · https://pro-tools.com/products/hydraulic-tube-and-pipe-bender-brute · https://pro-tools.com/collections/manual-tube-and-pipe-benders
- Notes: strongest concise concept explainers in the niche, but old. Die pages state "max degree of bend: 180, after springback".

**JD Squared (JD2)**
- https://www.jd2.com/model-32-bender · https://www.jd2.com/model-32-tube-bender-hydraulic · https://www.jd2.com/model-32-tube-bender-hydraulic-adapter-kit · https://www.jd2.com/model-32-bender-dies · https://www.jd2.com/frequently-asked-questions · https://www.jd2.com/bend-tech-pro · https://support.jd2.com/MiscDown/dl/M32BDirections.pdf · https://support.jd2.com/MiscDown/dl/TemplateBendingTutorial.pdf · https://support.jd2.com/MiscDown/dl/M6BDirections.pdf
- Notes: valuable technical knowledge is locked in PDFs. FAQ says Model 32 dies are not interchangeable with other current JD2 models.

**Baileigh Industrial**
- https://www.baileigh.com/manual-tube-bender-rdb-050 · https://baileigh.com/manual-tube-bender-rdb-25 · https://www.baileigh.com/metalworking/tube-pipe-benders/manual-rotary-draw-benders · https://www.baileigh.com/metalworking/tube-pipe-benders/hydraulic-rotary-draw-benders · https://www.baileigh.com/programmable-tube-bender-rdb-350-ts · https://baileigh.com/tube-and-pipe-bender-rdb-250 · https://blog.baileigh.com/basic-bending-concepts-commonly-discussed/ · https://blog.baileigh.com/baileigh-industrial-rdb-050-tubing-bender/ · https://blog.baileigh.com/bending-tube-for-off-road-vehicles-off-roading-and-tube-bending/ · https://www.baileigh.com/media/akeneo_connector/asset_files/R/D/RDB_175_08_2020_b9b5.pdf · https://www.baileigh.com/media/akeneo_connector/asset_files/B/A/BA9_RDB480220_MAN_08_2020_0926.pdf
- Notes: the off-road blog names 1.5" and 1.75" dies with about 5.5" / 6.5" CLR as the most popular. The RDB-175 manual's springback example (bend to 105° to get 90°) is machine-specific.

**Mittler Bros**
- https://www.mittlerbros.com/mittler-bros/tube-benders.html · https://www.mittlerbros.com/heavy-duty-180-hydraulic-tube-bender.html · https://www.mittlerbros.com/tube-bending-software · https://www.mittlerbros.com/bent-tube-vise-jaw.html
- Notes: 25-ton hydraulic 180° bender, stated for 2" OD × .125" wall, with 1° digital readout (vendor claim).

**Woodward Fab**
- https://www.woodwardfab.com/product/manual-tube-and-pipe-bender/ · https://www.woodwardfab.com/blog/woodward-fabs-step-by-step-guide-to-assembling-the-manual-pipe-tube-bending-machine/ · https://www.woodwardfab.com/blog/how-to-use-a-pipe-bender-effectively-part-i/ · https://www.woodwardfab.com/blog/7-simple-tips-to-improve-bending-processes-with-a-pipe-bender/ · https://www.woodwardfab.com/product/air-hydraulic-pipe-and-tubing-bender-wfb2-ahp/

**SWAG Off Road**
- https://www.swagoffroad.com/products/swag-rev-2-tubing-bender · https://www.swagoffroad.com/products/swag-off-road-tubing-roller-dies-compatible-with-hulk-or-harbor-freight-tube-roller · REV 2 die pages (for example, https://www.swagoffroad.com/products/1-75-round-tube-dies-180-degrees-5-inch-clr)

**Eastwood**
- https://www.eastwood.com/high-capacity-tubing-benders · https://www.eastwood.com/eastwood-electro-hydraulic-high-capacity-tubing-bender.html · https://www.eastwood.com/eastwood-s-pro-former-tubing-bender.html · https://www.eastwood.com/images/library/21115Q.pdf · https://www.eastwood.com/images/library/22199Q.pdf · https://www.eastwood.com/garage/build-bars/

**The Fabricator / Tube & Pipe Journal**
- https://www.thefabricator.com/glossary/centerline-radius · https://www.thefabricator.com/thefabricator/article/tubepipefabrication/tube-bending-basics-how-to-bend-tubing-and-metal-pipe · https://www.thefabricator.com/thefabricator/article/tubepipefabrication/4-essential-modes-of-tube-bending · https://www.thefabricator.com/tubepipejournal/article/tubepipefabrication/selecting-the-right-mandrel-and-wiper · https://www.thefabricator.com/thefabricator/article/tubepipefabrication/wipe-out-the-wrinkles-of-tube-bending · https://www.thefabricator.com/tubepipejournal/article/tubepipefabrication/managing-rotary-draw-tube-bending · https://www.thefabricator.com/tubepipejournal/article/tubepipefabrication/lean-times-call-for-mean-tactics-part-1

**Trick-Tools**
- https://www.trick-tools.com/tube-bender-buyers-guide · https://www.trick-tools.com/common-issues-with-tube-bending · https://answers.trick-tools.com/3491871/Will-JD2-dies-fit-the-Pro-Tools-MB-105HD-pro-tools-tube-bender · https://answers.trick-tools.com/4371289/What-is-the-speed-difference-between-electric-over-hydraulic-vs-Air-over-hydraulic · https://www.trick-tools.com/common/documentation/mandrel_bending.pdf

**Burns Stainless / Stainless Headers / Vibrant (exhaust)**
- https://burnsstainless.com/collections/tubing · https://burnsstainless.com/blogs/articles-1/welded-vs-seamless-tubing-basics-part-2 · https://burnsstainless.com/blogs/articles-1/welding-exhaust-systems-part-2 · https://burnsstainless.com/blogs/articles-1/yield-strength
- https://www.stainlessheaders.com/ · https://www.stainlessheaders.com/p-35900-tube-cut-length.html · https://www.stainlessheaders.com/p-35901-j-bend-optimizer.html
- https://vibrantperformance.com/tubing-and-bends/ · https://vibrantperformance.com/304-stainless-steel-90-brushed-mandrel-bends/ (product-only; no tech guide found)

**Forums, aggregators and media**
- https://www.garagejournal.com/forum/threads/tube-bender.500469/ · https://www.garagejournal.com/forum/threads/looking-to-buy-a-tubing-bender.437848/ · https://shopsavvy.com/best-picks/best-tube-benders · https://www.thedrive.com/reviews/37615/tube-bender (seen in results, dated 2023; a site search on thedrive.com did not resurface it, so its current status is unknown) · https://www.jegs.com/tech-articles/building-a-roll-cage-design-tips-tricks/ · https://bangshift.com/bangshift1320/bangshift1320-tech-stories/roll-bar-tech-learn-how-to-measure-layout-and-bend-a-roll-bar-or-roll-cage-from-the-guys-at-rogue-fab/ · https://nasaspeed.news/tech/fabrication/roll-cages-form-the-foundation-of-a-proper-racecar/

### 1.5 Where BendersHub can win (synthesis)

1. **Independent, application-segmented buying guides** (roll cage / exhaust / chassis / brake-line / home-shop budget) to displace parasite pages. Each needs a published test methodology, real photos of real bends, dated spec checks and a disclosure.
2. **Cross-brand capacity and die-compatibility database.** No one offers this. Each vendor charts only its own dies, and compatibility answers are scattered in Q&As and forums.
3. **Calculator + visualiser + explanation in one place.** Competitors' calculators are bare utilities (Stainless Headers) or tied to one machine (Rogue Fab offsets). BendersHub's bend calculator and geometry visualiser should show the formula, worked example and diagram.
4. **"Spec reconciliation" notes.** Vendors contradict themselves (Rogue Fab M605 wall; Baileigh RDB-050 190° vs 200°; JD2 bend angle unstated). A neutral site that flags and dates these builds trust.
5. **Safety-critical roll-cage content done responsibly.** Point to current rulebooks (NHRA, SCCA, SFI) with edition dates. Never present forum numbers as rules. Add a "verify with your tech inspector" box. Forum and secondary sources conflict, for example on NHRA 1-5/8" vs 1-3/4".
6. **Modernise the classic explainers** (Pro-Tools 2012–2015 era; The Fabricator industrial) into hobby- and pro-shop language with diagrams.

---

## PART 2: Keyword and content-gap research

**Legend.**
- **Vol.** = search volume. "NV" means not verified; no citable tool data was available (see section 0).
- **Diff.** = qualitative estimate (QE) only, from observed SERP composition. L = SERP mostly forums or spam; M = mix with manufacturer or trade press; H = strong brands, retailers or trade press throughout.
- **Links** = internal links to add: **[Quiz]** bender-selection quiz, **[Calc]** bend calculator, **[Viz]** geometry visualiser, **[Cmp]** comparison tool or pages.
- **Comm.** = commercial / affiliate relevance (High / Med / Low).
- **Priority:** P1 = build first, P2 = next, P3 = later.
- "Pages seen" = URLs returned by the search tool for that or a very close query.

### Cluster A: Tube bender selection

| # | Primary query | Intent | Related terms | Pages seen ranking | Vol. | Diff. (QE) | Page type | Links | Comm. | Priority and rationale |
|---|---|---|---|---|---|---|---|---|---|---|
| A1 | best tube bender | Commercial investigation | best tubing bender 2026, tube bender for fabrication, rotary draw bender | shopsavvy.com/best-picks/best-tube-benders; thedrive.com/reviews/37615/tube-bender; findingdulcinea.com; peggyomara.com; roguefab.com/best-tube-benders-compared/ | NV | M (weak content, but some strong domains) | Pillar roundup, segmented by use case, with test methodology | Quiz, Cmp, Calc | High | **P1.** Head term with a spammy SERP; hub for all F pages |
| A2 | manual vs hydraulic tube bender | Info → commercial | electric over hydraulic, air over hydraulic, ratchet bender | whatispiping.com/tube-benders/; vevor.com; cormak.pl; tubes-international | NV | L–M | Explainer with decision table (wall, OD, volume, budget) | Quiz, Cmp | High | **P1.** Decision-stage query that feeds the quiz |
| A3 | tube bender buying guide / how to choose a tube bender | Commercial investigation | capacity, CLR, die cost, floor mounting | trick-tools.com/tube-bender-buyers-guide; surplusrecord.com; revelationmachinery.com; tubeformsolutions.com checklist | NV | M | Evergreen buying guide plus printable checklist | Quiz, Calc, Cmp | High | **P1.** Vendor-biased SERP; independent checklist is a differentiator |
| A4 | best tube bender for home garage (under $1,000) | Commercial | budget tube bender, hobby tube bender | garagejournal.com threads ("Good tube bender?", "Cheap tube bender?") | NV | L | Budget roundup (WFB2, Model 32, M601, Speedway 3rd Gen, Pro-Tools) | Quiz, Cmp | High | **P1.** Forum-dominated SERP; high Amazon fit for WFB2-class tools |
| A5 | tube bender capacity chart / what wall thickness can my bender bend | Info / commercial | max wall, DOM .120, .188, .250, min wall by CLR | roguefab.com/tube-capacity-chart/; pro-tools die charts | NV | M | Cross-brand capacity database (interactive table) | Calc, Cmp, Quiz | Med–High | **P1.** Unique asset; earns links; feeds comparisons |
| A6 | are tube bender dies interchangeable / die compatibility | Info / commercial | JD2 dies on Pro-Tools, WFB2-compatible dies, die pin size | answers.trick-tools.com Q&A; nc4x4.com forum | NV | L | Compatibility matrix plus explainer | Cmp, Quiz | High (die purchases) | **P1.** Clear gap; high-intent accessory sales |
| A7 | pipe bender vs tube bender (why a pipe bender kinks tubing) | Informational | Harbor Freight pipe bender roll cage, ram bender, bottle-jack bender | garagejournal, forums.off-road.com, classicmotorsports forum; harborfreight manual | NV | L | Explainer with diagrams (pipe NPS vs tube OD, ram vs rotary draw) | Viz, Quiz | Med | **P1.** Prevents bad purchases; safety angle; links to F5 |
| A8 | best tube bender for chromoly (4130) | Commercial | chromoly springback, bending 4130 manual | Rogue Fab and Pro-Tools product pages; forums | NV | L–M | Use-case roundup | Calc, Cmp | Med–High | **P2.** Narrower; can be a section of A1 first |

### Cluster B: Exhaust fabrication

| # | Primary query | Intent | Related terms | Pages seen ranking | Vol. | Diff. (QE) | Page type | Links | Comm. | Priority and rationale |
|---|---|---|---|---|---|---|---|---|---|---|
| B1 | mandrel bending vs crush bending exhaust | Informational | press bend, ram bend, mandrel bent exhaust | viperperformance.co.uk/blog/?p=3175; dragzine/enginelabs Burns video; jegs.com tech article; motoiq.com; roguefab.com/exhaust-pipe-benders/ | NV | M | Explainer with cross-section diagrams (ovality, flow area) | Viz, Calc | Med | **P1.** Top-of-funnel for the exhaust cluster |
| B2 | best exhaust pipe bender for home garage | Commercial | exhaust tubing bender, muffler bender, hydraulic exhaust bender | texasdiamondgarage.com; vevor.com; aerovisiondrones.com (spam); bestbuyautoequipment.com | NV | L | Roundup that separates ram (repair) from rotary-draw/mandrel (performance) | Quiz, Cmp | High | **P1.** Weak SERP; high Amazon fit (VEVOR / Happybuy class) |
| B3 | exhaust bend radius 1D vs 1.5D vs 2D | Informational | CLR exhaust, tight radius mandrel bend | mfgrobots.com; vibrantperformance product pages; trealperformance.com | NV | L | Explainer plus visualiser preset | Viz, Calc | Med | **P2.** Strong tool tie-in |
| B4 | aluminized vs stainless exhaust tubing (304 vs 409 vs 321 vs titanium) | Info / commercial | best exhaust tubing material | burnsstainless.com yield-strength blog; cjponyparts.com guide; playtimeautoparts.com.au | NV | M | Materials guide with bendability notes | Calc | Med | **P2.** Ties materials to bending behaviour (springback, wrinkling) |
| B5 | exhaust tube cut length calculator / J-bend cut | Transactional (tool) | bend allowance, developed length | stainlessheaders.com/p-35900-tube-cut-length.html; p-35901-j-bend-optimizer.html | NV | L–M | Calculator page plus worked examples | Calc, Viz | Low–Med | **P2.** Direct calculator competitor |
| B6 | bending stainless exhaust tubing (wrinkles, springback) | Informational | 304 work hardening, thin wall stainless bending | roguefab.com/stainless-steel-tube-bender/; thefabricator.com | NV | M | How-to | Calc | Med | **P2** |
| B7 | buy mandrel bends vs bend your own exhaust | Commercial | mandrel bend kit, U-bend, J-bend | motoiq.com; forums | NV | L | Cost comparison guide | Cmp | High (kits on Amazon) | **P2.** Affiliate for bend kits |
| B8 | bending titanium exhaust tubing | Informational | grade 2 vs grade 9, Ti mandrel bending | roguefab.com/titanium-tube-bender/; vendor product pages | NV | L | Advanced guide | Calc | Low–Med | **P3.** Niche |

### Cluster C: Roll cage and chassis (**safety-critical: see notes**)

> **Safety flags for every C page:** (1) cite the sanctioning body's **current** rulebook with edition and year, and link it; (2) say plainly that BendersHub is not a tech inspector or engineer; (3) never present forum-sourced tube specs as rules; (4) cover welding requirements (for example, NHRA requires TIG for 4130); (5) no "pipe bender for cages" recommendations.

| # | Primary query | Intent | Related terms | Pages seen ranking | Vol. | Diff. (QE) | Page type | Links | Comm. | Priority and rationale |
|---|---|---|---|---|---|---|---|---|---|---|
| C1 | best tube bender for roll cage | Commercial | roll cage bender, cage bender kit, 1.75 DOM bender | i.building.co.uk; community.internxt.com; ovrclock.com; aerovisiondrones.com; findingdulcinea.com (all spam/parasite) | NV | **L** | Use-case roundup tied to rulebook tube sizes | Quiz, Cmp, Calc | High | **P1.** Weakest high-intent SERP found |
| C2 | roll cage tubing size and wall thickness (1.75 x .120 DOM) | Informational, **YMYL-like** | NHRA roll cage tubing, SCCA cage tubing by weight, SFI | forums (nc4x4, hybridz, grassrootsmotorsports); jegs.com tech article; NHRA rulebook PDFs (promod.nhra.com) | NV | M | Reference page: rule digest with edition dates plus links to rulebooks | Calc, Quiz | Med | **P1.** Authority builder; must be meticulously sourced |
| C3 | DOM vs 4130 chromoly roll cage | Informational | CDS, Docol R8, TIG chromoly | nasaspeed.news; weldmongerstore.com blog; forums; roguefab.com/building-roll-cage/ | NV | M | Comparison explainer | Cmp, Calc | Med | **P1.** Bends differ: 4130 springs back more |
| C4 | roll cage bend radius rule (CLR at least 3x tube diameter) | Informational | minimum bend radius roll cage, die CLR for cage | timetrials.scca.com/pages/safety-level-3; fsae.com forum; forums | NV | L–M | Short reference plus calculator preset ("is my die legal?") | Calc, Viz | Med | **P1.** Tool-driven, unique |
| C5 | how to build a roll cage (bending and notching) | Informational | cage layout, templates, fishmouth | roguefab.com/building-roll-cage/; bangshift.com videos; therangerstation.com; offroadxtreme.com | NV | M–H | Long-form pillar (series) | Viz, Calc, Quiz | Med | **P2.** Big effort; Rogue Fab strong |
| C6 | best tube notcher for roll cage | Commercial | hole-saw notcher, VersaNotcher, Speedy Notcher | peggyomara.com; findingdulcinea.com; farmstandapp.com; roguefab notching guide; speedwaymotors.com | NV | L | Roundup | Cmp, Calc (cope angle) | High | **P1.** Weak SERP, Amazon-friendly products |
| C7 | how to measure and lay out tube bends (bend start, roll bar) | Informational | bend marks, gain/loss, template bending | roguefab.com Bending 101; JD2 TemplateBendingTutorial.pdf; bangshift; classicmotorsports forum | NV | L–M | How-to with interactive layout | Viz, Calc | Med | **P2.** Showcase for the visualiser |

### Cluster D: Hydraulic and CNC

| # | Primary query | Intent | Related terms | Pages seen ranking | Vol. | Diff. (QE) | Page type | Links | Comm. | Priority and rationale |
|---|---|---|---|---|---|---|---|---|---|---|
| D1 | best hydraulic tube bender | Commercial | hydraulic pipe bender, electric hydraulic bender | shopsavvy.com; narcosis-the-game.com (spam); onrender spam; grassrootsmotorsports forum | NV | L | Roundup that separates rotary-draw hydraulic from bottle-jack ram | Quiz, Cmp | High | **P1.** Spammy SERP |
| D2 | electric vs air-over-hydraulic bender pump (110V vs 220V) | Info / commercial | bender power unit, duty cycle, pump speed | answers.trick-tools.com; roguefab.com/product/m600-elechydro/; directindustry (Ercolina) | NV | L | Explainer with power-requirement table | Quiz | Med–High | **P2.** Practical gap; installation and power questions |
| D3 | CNC tube bender for small shop | Commercial (high ticket) | programmable rotary draw bender, RDB-250, NC vs CNC | baileigh.com RDB-250 / RDB-350-TS; thefabricator.com product news; dealer listings | NV | M | Buyer's guide (when NC/CNC pays off; repeatability) | Quiz, Cmp | Med (low Amazon relevance) | **P2.** Authority and B2B; lead-gen potential |
| D4 | mandrel tube bender for home shop (cost) | Commercial | M600 mandrel, home mandrel bender | roguefab.com/product/mandrelbender/; forums | NV | L–M | Explainer plus cost table | Cmp, Calc | Med | **P2** |
| D5 | hydraulic cylinder for tube bender (10-ton vs 15-ton, stroke) | Info / commercial | bender cylinder, SPX 10 ton 14" stroke | jd2.com 10-ton 14" cylinder page; pro-tools SPX cylinder page | NV | L | Short guide | Quiz | Med | **P3** |

### Cluster E: Technical education

| # | Primary query | Intent | Related terms | Pages seen ranking | Vol. | Diff. (QE) | Page type | Links | Comm. | Priority and rationale |
|---|---|---|---|---|---|---|---|---|---|---|
| E1 | centerline radius tube bending (CLR explained) | Informational | CLR vs inside radius, how to measure CLR, die CLR | thefabricator.com/glossary/centerline-radius; aisc.org glossary; blog.blmgroup.com; h-pproducts.com CLR calc; timelesstube.com | NV | M | Definitive explainer with diagram and measuring method | Viz, Calc, Quiz | Med | **P1.** Foundation page for every tool |
| E2 | springback in tube bending (how to compensate) | Informational | overbend, springback by material | research papers (ntnuopen, patents, formingworld); pro-tools springback blog | NV | L–M (SERP is academic, not practical) | How-to with measured examples by material | Calc | Med | **P1.** SERP lacks a practical, current page |
| E3 | tube bend length formula / how to calculate cut length | Info / tool | arc length, developed length, tangent, setback | stainlessheaders.com calculator; everycalculators.com; chapmanelectric (conduit) | NV | L–M | Formula guide plus embedded calculator | Calc, Viz | Low–Med | **P1.** Core tool page |
| E4 | wall factor and D of bend | Informational | mandrel selection chart, bend ratio | pro-tools blog; thefabricator "Selecting the right mandrel and wiper"; tubeformsolutions PDF; hinesbending.com | NV | M | Explainer plus interactive feasibility checker | Calc | Low–Med | **P2.** Authority content |
| E5 | ovality in tube bending (measure / acceptable limits) | Informational | flattening percentage, out of round | engineersedge.com; calculator.academy; eng-tips; imeko.org paper | NV | L | Explainer plus mini-calculator | Calc | Low | **P2** |
| E6 | tube bending wrinkles, kinks and flattening (causes and fixes) | Informational / problem | wiper die, pressure die, slipping clamp | trick-tools.com/common-issues-with-tube-bending; thefabricator "Wipe out the wrinkles"; "When a good tube bends bad" | NV | M | Troubleshooting guide with photos | Quiz, Cmp | Med (dies, tooling) | **P1.** Problem-solving traffic converts to tooling |
| E7 | bending aluminium tube without cracking (6061-T6) | Informational | anneal 6061, T4 vs T6 bending | thefabricator (press-brake focus); roguefab.com/aluminum-pipe-benders/ | NV | M | How-to | Calc | Med | **P2** |
| E8 | wall thinning in tube bending | Informational | outside wall thinning percentage | patents; globalspec; blog.baileigh.com | NV | L | Explainer (qualitative plus examples) | Calc | Low | **P3** |
| E9 | twist / plane-of-bend rotation in multi-bend parts | Informational | bend rotation, degree wheel | pro-tools twist blog | NV | L | How-to | Viz | Low | **P3** |

### Cluster F: Comparisons and reviews

| # | Primary query | Intent | Related terms | Pages seen ranking | Vol. | Diff. (QE) | Page type | Links | Comm. | Priority and rationale |
|---|---|---|---|---|---|---|---|---|---|---|
| F1 | JD2 Model 32 vs Rogue Fab M605 | Commercial | horizontal vs vertical bender | garagejournal tube-bender thread; xs400.com forum; roguefab.com/best-tube-benders-compared/ | NV | L | Head-to-head comparison | Cmp, Quiz | High | **P1.** The two most-discussed hobby/pro machines |
| F2 | Pro-Tools 105HD vs JD2 Model 32 | Commercial | 105HD review | forums; trick-tools Q&A | NV | L | Head-to-head | Cmp | High | **P1** |
| F3 | Baileigh RDB-050 review | Commercial | RDB-050 vs JD2 | baileigh.com product and blog; shopsavvy | NV | L–M | Review (spec reconciliation: 190° vs 200°) | Cmp | High | **P2** |
| F4 | Woodward Fab WFB2 review | Commercial | WFB2 vs ECOTRIC, WFB2 dies | shopsavvy reviews-tldr; 4lowmagazine.com; retailers | NV | L | Review plus upgrade path | Cmp, Quiz | High (strong Amazon fit) | **P1** |
| F5 | Harbor Freight 12-ton pipe bender: can it bend roll cage / exhaust? | Commercial / problem | Central Machinery 32888, bottle-jack bender | harborfreight.com product and manual; forums | NV | L | Honest review / "what it is for" | Cmp, Quiz | Med | **P1.** Huge awareness; safety-relevant |
| F6 | Rogue Fab M605 vs M625 | Commercial | M601 vs M605 vs M625 | roguefab.com pages only | NV | L | Comparison | Cmp | High (direct sale; affiliate status unknown) | **P2** |
| F7 | 6-way "home-shop bender comparison": JD2 32 vs M605 vs RDB-050 vs 105HD vs HF 12-ton vs WFB2 / Eastwood | Commercial | tube bender comparison chart | none found (gap) | NV | L | Master comparison table plus test results | Cmp, Quiz, Calc | High | **P1.** Flagship; no competitor offers it |

**Count:** 44 topics (8 + 8 + 7 + 5 + 9 + 7): 24 P1, 16 P2 and 4 P3. To cut this to 38, merge A8 into A1, B8 into B6, D5 into D2, E8 into E6, E9 into C7 and F6 into F7.

### Suggested build order (P1 only)

1. E1 CLR explained, E3 bend-length formulas and A7 pipe vs tube benders. These make the tools credible.
2. F7 master comparison plus A5 capacity database and A6 die compatibility. These are flagship data assets.
3. C1 roll-cage bender roundup, A1 best tube bender, D1 hydraulic and B2 exhaust benders. These target the weakest commercial SERPs.
4. C2 / C3 / C4 roll-cage reference pages (safety-checked), E2 springback and E6 defects.
5. F1, F2, F4, F5 reviews and comparisons; C6 notchers; A2 / A3 / A4 decision guides; B1 mandrel vs crush.

---

## PART 3: Technical fact-check notes

Source type is labelled: **[MFR]** manufacturer, **[TRADE]** trade press, **[RULE]** sanctioning body, **[ACAD]** academic or standard, **[2nd]** secondary or forum. All were accessed via search-index summaries, not direct reads. Re-verify before publishing.

### 3.1 Centerline radius (CLR)
- **Definition:** CLR is the distance from the centre of curvature to the centreline (axis) of the tube. It is often expressed as a multiple of tube size; for example, a 5×D bend on 2" tube has a 10" CLR. Sources: [TRADE] https://www.thefabricator.com/glossary/centerline-radius. The same definition appears in [MFR] Baileigh RDB-480 operator's manual: https://www.baileigh.com/media/akeneo_connector/asset_files/B/A/BA9_RDB480220_MAN_08_2020_0926.pdf
- **Typical CLR/OD ("D") ratios**
  - **Roll cage, rule floor:** SCCA Time Trials says the radius of all cage bends, measured at tube centreline, must be **at least 3× the tube diameter** [RULE] https://timetrials.scca.com/pages/safety-level-3. A Canadian SCCA-derived document words it as 3× the **outside** diameter, so check wording per rulebook.
  - **Roll cage, common practice:** Baileigh says the most popular off-road dies are 1.5" and 1.75" OD with about 5.5" and 6.5" CLR [MFR] https://blog.baileigh.com/bending-tube-for-off-road-vehicles-off-roading-and-tube-bending/. Rogue Fab's largest common die is 6.0" CLR; 1.75" OD on 6.0" CLR is about 3.4D [MFR] https://www.roguefab.com/tube-capacity-chart/. In practice, 1.75" cage tube is typically bent at about 3.1–3.7D. This figure is derived from the listed dies.
  - **Non-mandrel rule of thumb:** minimum CLR is about **3 × OD** [MFR] Pro-Tools wall-factor blog https://pro-tools.com/blogs/protoolsusa/42288387-wall-factor-and-d-of-bend-getting-to-the-center-of-the-centerline-radius-debate; Pro-Tools 105 series lists min CLR ≈ 3×OD. Baileigh suggests **4 × OD** as a starting estimate [MFR] https://blog.baileigh.com/basic-bending-concepts-commonly-discussed/. For 6061-T6 aluminium, Rogue Fab recommends about 3.5–4D and calls 3D marginal [MFR] https://www.roguefab.com/aluminum-pipe-benders/.
  - **Exhaust (mandrel):** **1D to 1.5D** is common in aftermarket mandrel bends. Vibrant sells 1D tight-radius 304 bends [MFR] https://vibrantperformance.com/304-stainless-steel-90-brushed-mandrel-bends/. 1.5D listings come from retailers [2nd]. Burns Stainless encodes CLR per part; for example, 0.75" OD with 2" CLR [MFR] https://burnsstainless.com/collections/tubing.
  - **Industrial limit:** rotary-draw with mandrel can reach about **0.7×OD** in precision work [TRADE] https://www.thefabricator.com/thefabricator/article/tubepipefabrication/tube-bending-basics-how-to-bend-tubing-and-metal-pipe. BLM Group calls ratios below 1 "extreme" [MFR] https://blog.blmgroup.com/when-is-it-useful-to-bend-a-tube-with-a-small-bending-radius.

### 3.2 Springback

**No authoritative published table of typical tube springback by material was found.** Present ranges only as examples, and teach the test-bend method.

| Material | What sources say | Source |
|---|---|---|
| Mild steel (ERW/DOM) | Examples: Pro-Tools illustrates 7° on a 90° target (illustrative) and 2° on 180°. Rogue Fab example: 90° reads 86.5° after release (3.5°). JD2 template tutorial: 3–4° "should be sufficient" for its example material. | [MFR] https://pro-tools.com/blogs/protoolsusa/19923715-springback-to-basics-defining-springback-and-how-to-compensate-for-it · https://www.roguefab.com/bending-101/ · https://support.jd2.com/MiscDown/dl/TemplateBendingTutorial.pdf |
| 4130 chromoly | About **2× the springback of the same size and wall welded mild steel** (JD2). One home-shop example: 7/8" × .049" 4130 needed 98° to get 90° (≈8°). | [MFR] https://support.jd2.com/MiscDown/dl/M32BDirections.pdf · [2nd] https://www.kitplanes.com/home-shop-machinist-45/ |
| Stainless (304/316) | Springs back more than mild steel; work-hardens; overbend required; measure per material/die combo. | [MFR] https://www.roguefab.com/stainless-steel-tube-bender/ · [TRADE] thefabricator.com tube bending basics |
| Aluminium (6061) | No tube-specific numbers found. Temper strongly affects bendability (annealed > T4 > T6); T6 cracks on tight radii. | [MFR] https://www.roguefab.com/aluminum-pipe-benders/ · [TRADE] https://www.thefabricator.com/thefabricator/article/bending/bending-aluminum-101-how-to-bend-6061-t6-aluminum |
| Titanium (3Al-2.5V) | More springback than mild steel; use annealed stock; dial in on scrap. | [MFR] https://www.roguefab.com/titanium-tube-bender/ |

- **General rule:** harder tube and smaller CLR mean more springback and radial growth [TRADE] thefabricator.com "Tube bending basics". Springback is neither a constant number of degrees nor a fixed percentage; it varies by batch. Pro-Tools recommends logging the heat or batch number from the mill cert [MFR] Pro-Tools springback blog.
- **Do not reuse sheet-metal figures for tube.** The Fabricator's 304 = 2–3°, mild aluminium = 1.5–2°, CRS = 0.75–1° are **sheet at 1:1 radius/thickness**, not tube: https://www.thefabricator.com/article/bending/bending-basics-the-hows-and-whys-of-springback-and-springforward
- **How benders compensate:**
  - Overbend by the measured amount, using a degree ring or stop.
  - Machine-specific calibration. Baileigh RDB-175 manual: bend until a true 90° is achieved, record the machine reading (example 105°, i.e. 15° including machine deflection), and add that offset to future bends [MFR] RDB-175 manual https://www.baileigh.com/media/akeneo_connector/asset_files/R/D/RDB_175_08_2020_b9b5.pdf.
  - Anti-springback mechanisms (Baileigh RDB-050 [MFR]).
  - Software calibration from two test bends with interpolation (Bend-Tech via Rogue Fab, Pro-Tools).
  - CNC controllers that store a springback value per material (Baileigh RDB-350-TS [MFR] https://www.baileigh.com/programmable-tube-bender-rdb-350-ts).

### 3.3 Bend geometry formulas
- **Developed (arc) length of a bend** = CLR × θ (radians) = CLR × θ° × π/180 ≈ CLR × θ° × 0.017453. Sources:
  - [MFR] Stainless Headers cut-length calculator https://www.stainlessheaders.com/p-35900-tube-cut-length.html (uses 0.01745; example 4.5" CLR at 90° → about 7.07").
  - [MFR] JD2 Model 6 manual (uses 0.0175) https://support.jd2.com/MiscDown/dl/M6BDirections.pdf.
  - [MFR] Rogue Fab Bending 101 (6.0" CLR = 0.105" per degree; exact value is 0.1047).
  - [MFR] Baileigh RDB-480 manual (constant × radius; their 90° × 6" example gives 9.45", while the exact value is 9.425". The difference comes from rounding the constant to 0.0175).
  - **Editorial note:** use 0.017453 in the BendersHub calculator, and explain that vendors' rounded constants add about 0.3% error. At least one indexed layout page states the constant as "0.001745", which is off by 10×. Avoid repeating it.
- **Tangent length** (tangent point to the intersection of the extended straights) = CLR × tan(θ/2). For a 90° bend, tangent = CLR. This is standard circular geometry, verified mathematically. Web sources seen were generic calculator sites (https://everycalculators.com/pipe-bend-radius-calculator.html), not manufacturers. Cite a textbook or The Fabricator if possible.
- **Terminology warning:** "setback" is used inconsistently. Some sources use it for the tangent length above; others use R × (1 − cos(θ/2)) or a bisector distance (everycalculators.com pages disagree). Define terms explicitly on BendersHub pages and in the visualiser.
- **Cut length** = sum of straights (between tangent points) + sum of arc lengths, plus trim allowance. Stainless Headers' J-bend optimiser suggests about 1/4" for fit-up trimming [MFR]. Machine "gain/loss" (stretch) is machine- and die-specific; calibrate it with a test bend (Rogue Fab offset tables; Pro-Tools "calibrated CLR" for Bend-Tech).

### 3.4 D of bend and wall factor
- **D of bend** = CLR ÷ tube OD. The Fabricator notes "D" uses **nominal**, not true, OD. Larger D means an easier bend.
- **Wall factor** = OD ÷ wall thickness. Larger WF means a harder bend.
- Sources: [MFR] Pro-Tools wall-factor blog (URL above); [TRADE] https://www.thefabricator.com/tubepipejournal/article/tubepipefabrication/selecting-the-right-mandrel-and-wiper. The worked example there is 2" OD × 0.065" wall on 4" CLR, giving WF ≈ 30.8 and D = 2.0. The chart lookup gives a regular-pitch two-ball mandrel with wiper. Charts typically assume mild steel bent to 180°; WF axis about 10–200, D axis about 1D–3.5D.
- Also: [TRADE] https://www.thefabricator.com/tubepipejournal/article/tubepipefabrication/lean-times-call-for-mean-tactics-part-1 · [MFR] Tube Form Solutions "Basics of Tube Bending" PDF https://www.tubeformsolutions.com/web/content/9713?download=true

### 3.5 Ovality
- **Definition:** ovality % = (Dmax − Dmin) ÷ D_nominal × 100. Sources: [TRADE] The Fabricator ("4 essential modes of tube bending" / tube bending articles); [ACAD] IMEKO TC14 2007 paper https://imeko.org/publications/tc14-2007/IMEKO-TC14-2007-51.pdf. Engineers Edge also lists the alternative (Dmax − Dmin) ÷ (Dmax + Dmin) × 100, which is less common in bending: https://ns1.engineersedge.com/calculators/ovality_roundness_tube_15572.htm (URL as surfaced in search results)
- **Limits:** application-specific. The Fabricator cites tolerances of about 1.5% to 8% depending on end use. Forum figures of 8% below 600 psi and 5% above 600 psi are anecdotal [2nd]; use a governing code instead.
- **Vendor data points:** Rogue Fab says a thin-wall roller cuts deformation to about 5–7% vs about 10% without [MFR] capacity chart. Rogue Fab shows a 2.75" 1D mandrel exhaust bend at "0.9% deformation" [MFR] exhaust-pipe-benders page (image alt text).
- **Wall thinning:** outside wall thins and inside thickens. No validated simple closed-form formula was found. A patent example shows .035" → about .028" (≈20%). One trade summary cites up to about 33% depending on radius. Present as qualitative or with examples only.

### 3.6 Bender spec sheet (popular models)

| Model | Drive | Max round tube OD | Wall / capacity notes | Max bend angle | Verification status and sources |
|---|---|---|---|---|---|
| **JD2 Model 32** | Manual ratchet handle (about 36"); optional hydraulic (adapter kit SKU 302003; recommended 10-ton, 14" stroke cylinder) | 2-1/2" OD round tube/pipe (range 1/2"–2-1/2"); square 1/2"–2" | Rated to bend 2" OD × .250" wall DOM (hydraulic). Manual: round over 2" × .120" wall, square over 1-1/2" or solid over 1" requires hydraulics. Solid to 1-1/2". Manual: average pressure usually below 1,300 psi (1-3/4" × .095 mild steel ≈ 900 psi). | **Not stated on manufacturer pages found.** JD2 sells 180° die sets; resale/auction listings say 180° (unverified) | [MFR] https://www.jd2.com/model-32-bender · https://support.jd2.com/MiscDown/dl/M32BDirections.pdf · https://www.jd2.com/model-32-tube-bender-hydraulic-adapter-kit. Note: hydraulic listing title says "Includes Cylinder" while others say not included; check before publishing. |
| **Rogue Fab M605** | Vertical rotary draw. Manual hydraulic, air-over-hydraulic, or 1.5 / 2.0 HP electric-hydraulic | 2.00" OD | **Conflict:** category page and catalogue say 2.00" × .188" wall; FAQ says up to .250" DOM. 1-1/2" solid bar. CLR options 2.5 / 3.5 / 4.5 / 6.0". | About 94° per ram stroke; continues past 180°; one page states 194° max | [MFR] https://www.roguefab.com/product/metal-tube-bender-m605-1-die/ · https://www.roguefab.com/product-category/tubing-benders/ · https://www.roguefab.com/faqs/. Wall-limit conflict: verify with Rogue Fab. |
| **Rogue Fab M625** | As M605 (same dies) | 2.00" OD | 2.00" × .250" wall DOM "daily under warranty"; 1-3/4" solid; 5/16" frame plate (vs 3/16" M601/M605). With mandrel attachment: down to about .065" wall on 1.75" OD. Two pressure dies needed at ≥ .156" wall. | Same family (up to 194°) | [MFR] https://www.roguefab.com/product/metal-tube-bender-m625-1-die/ · https://www.roguefab.com/tube-capacity-chart/ · 2025 catalogue PDF |
| **Pro-Tools 105HD** | Manual ratchet (hydraulic package with 10-ton cylinder available) | 2" OD round (1/2"–2"); pipe NPS 1/4–1-1/2; square 1/2"–1-1/2" (.065–.125 wall) | Max round wall .134". 36" extension handle allows manual bending to 2" × .134" chromoly. Min CLR ≈ 3×OD, max CLR 7". Rated on mild-steel Sch 40. (105 "Brute" hydraulic: 2-1/2" OD, NPS 2, 15-ton.) | 180° (die-dependent; "after springback" on die pages) | [MFR] https://pro-tools.com/products/manual-tube-and-pipe-bender-105-heavy-duty-package · https://pro-tools.com/products/hydraulic-tube-and-pipe-bender-105-heavy-duty · https://pro-tools.com/products/hydraulic-tube-and-pipe-bender-brute |
| **Baileigh RDB-050** | Manual, 3-speed ratchet; anti-springback mechanism | 2.5" OD mild steel tube; 2" OD 4130 at .120" wall | Min OD 3/4". Net weight 310 lb. Listed $2,309; 1-year warranty (prices change). | **Conflict:** copy says up to 200°; spec table says 190° | [MFR] https://www.baileigh.com/manual-tube-bender-rdb-050. Manual PDF not read. |
| **Baileigh RDB-25** (the brief said "RDB-025"; Baileigh's model is RDB-25) | Manual, bench-mount | 7/8" round; 1" square | .047" wall; capacities based on A53 Grade A mild steel. Ships with 8 die sets (for example, 7/8" OD at 4.25" CLR). | 180° | [MFR] https://baileigh.com/manual-tube-bender-rdb-25 plus resellers (penntoolco.com, aircraftspruce.com). Partly reseller-sourced; verify. |
| **Harbor Freight 12-ton hydraulic pipe bender** | Bottle-jack hydraulic **ram** (press) bender | 1/2"–2" **Schedule 40/80 pipe** (not tube OD) | 12 tons (24,000 lb); 9-1/2" stroke; six cast dies. **Manual: not intended for thin-wall pipe such as conduit or exhaust.** Listed $149.99 (in-store only; changes). | Up to 90° | [MFR] https://www.harborfreight.com/12-ton-hydraulic-pipe-bender-32888.html · manual https://manuals.harborfreight.com/manuals/32000-32999/32888-193175527103.pdf. **Brand correction:** listed as **Central Machinery** (item 32888; alt SKUs 62539 / 86013), not Pittsburgh. No Pittsburgh-branded 12-ton pipe bender was found. |
| Woodward Fab WFB2 (bonus) | Manual (air/hydraulic WFB2-AHP variant exists) | About 2" | Dies sold separately | About 120° per retail listings / Rogue Fab comparison | [MFR] https://www.woodwardfab.com/product/manual-tube-and-pipe-bender/ plus retailers. Angle not confirmed on Woodward page. |
| Eastwood High-Capacity (#21115) (bonus) | Manual handle, or air / electric hydraulic ram | 2" (range 1"–2") | Max 2" × .120" mild steel, aluminium or copper; chromoly limited to .090" wall | 90° max recommended with 120° dies; 180° with 240° dies | [MFR] https://www.eastwood.com/images/library/21115Q.pdf |

### 3.7 Roll-cage rules (**safety-critical**: use only as pointers to primary documents)
- **NHRA (2019 General Regulations excerpt):** "1-3/4" OD × .118" mild steel or .083" 4130", with a class "A" exception at 1-1/4". 4130 must be TIG welded; mild steel MIG or TIG. [RULE] http://promod.nhra.com/userfiles/file/Tech/2019%20General%20Regulations.pdf
  - Section context was not fully visible.
  - Forums commonly cite 1-5/8" for cages and 1-3/4" for bars [2nd].
  - **Must check the current NHRA rulebook before publishing.**
- **SCCA:**
  - Time Trials safety rules: bend radius ≥ 3× tube diameter at centreline. Allowed materials are seamless or DOM mild steel, SAE 4130 or Docol R8; ERW not allowed; wall minus tolerance 0.010". [RULE] https://timetrials.scca.com/pages/safety-level-3 · https://timetrials.scca.com/pages/full-safety-rules
  - Club racing GCR tube-size-by-weight tables: only forum-quoted versions were found, and they **differ between editions**. Not verified.
- **SFI:** a 10.4B notice says nominal wall .118" for round tube ≥ 1-3/8" and .108" below that. The spec scope was not confirmed. [RULE] https://www.sfifoundation.com/wp-content/pdfs/SFI10-4BNotice12-15-21.pdf

### 3.8 Items that could not be verified (do not publish as fact without a primary source)
- Any search volume, keyword difficulty, traffic, revenue or conversion figures.
- JD2 Model 32 maximum bend angle from a JD2 page.
- Rogue Fab M605 max wall (.188" vs .250"), and Baileigh RDB-050 max angle (190° vs 200°).
- Typical springback ranges by tube material (only examples exist).
- Current NHRA / SCCA GCR tube tables (only a 2019 NHRA excerpt and the SCCA Time Trials page were seen).
- A "Pittsburgh"-branded 12-ton pipe bender.
- Amazon availability or affiliate eligibility of JD2, Rogue Fab, Pro-Tools or Baileigh machines.
- Design, photography, mobile and page-speed quality of every competitor (no direct page access).
