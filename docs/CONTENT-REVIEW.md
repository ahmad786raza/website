# Published content to review

The theme cannot edit posts already in your database. If the v1 **Content Library** articles or **Repair Tool** pages are published on bendershub.com, they still contain the passages below. v2 corrected the same passages in the bundled starter library (`inc/seed-content.php`), so you can copy the corrected wording from there.

Priority: **High** = wrong or safety-critical, fix first. **Medium** = unsupported numbers that should be sourced or softened. **Low** = rules of thumb that need labelling.

## Articles

| Post (slug) | Passage | Problem | Priority |
|---|---|---|---|
| `roll-cage-specifications` | NHRA ET-bracket tube sizes, "SFI 25.5 … renewed every 3 years", SCCA closed/open-car sizes, FIA 50 × 2.5 mm / 45 × 2.5 mm, comparison table | Unverified and internally inconsistent: the table says SFI 25.1 while the text says 25.5. A 2019 NHRA excerpt found in research gives 1-3/4" × .118" mild steel or .083" 4130, which conflicts with the article. **Safety-critical.** Replace with pointers to the current rulebooks (v2 starter text does this). v2 already shows a "check the current rulebook" notice on this post. | High |
| `bend-radius-explained` | "Bend Allowance = θ × (R + K·t)" with K-factors | This is a sheet-metal formula. For tube, CLR is measured to the centreline, so arc length = CLR × θ (radians). | High |
| `bend-radius-explained` | "CLR should be at least 2× the outside diameter … below 1.5× you need a mandrel" | Manufacturers give about 3× OD as the practical minimum without a mandrel (Pro-Tools), and common cage dies are about 3.1–3.7× OD. | High |
| `bend-radius-explained` | Springback list (mild 1.5%, 4130 2.5%, 304 3%, 6061 1.3%) and "92.7°" | No published tube springback table supports these figures. Teach the test-bend method instead. JD2 says 4130 is about 2× mild steel. | High |
| `bend-radius-explained` | "D/T below 30 … above 50 mandrel-only … above 70 even mandrels struggle"; closing paragraph about the calculator predicting "wrinkle risk … cutting length" | Thresholds are unsourced. The calculator now reports D-of-bend, wall factor, arc, tangent and cut length. | Medium |
| `chromoly-fabrication` | "CLR of at least 5× OD and ideally 6× OD" | Contradicts common practice (about 3.1–3.7D cage dies) and the SCCA Time Trials ≥ 3× rule. | High |
| `chromoly-fabrication` | "roughly 40% more springback … 92.5°"; "you need at least 20 tons … 25 is more comfortable" | Unsourced. JD2: about 2× mild steel. Bender capacity is set by the manufacturer's chart, not tonnage alone. | High |
| `chromoly-fabrication` | "MIG-welded chromoly chassis fail SFI inspection"; "1/16" gap … automatic reject under SFI"; "preheated to 300°F"; filler-rod requirement | These attribute rules to SFI without a source. The welding method comes from the sanctioning body (NHRA has required TIG for 4130). Point to the rulebook and a qualified welding procedure. | High |
| `chromoly-fabrication` | "4130N … around 97,000 psi versus DOM … 70,000 psi" | Needs a material-spec source or removal. | Medium |
| `stainless-bending` | "12 tons … 15-16 tons … T321 needs 18+"; "springs back 60-80% more … 93° … 94°"; "60-70% of full ram speed"; "cooling cycles every 8-10 bends … under 200°F" | Unsourced numbers. | Medium |
| `tube-wrinkling-prevention` | D/T bands (below 20 "never wrinkles" … above 70); "CLR/OD below 1.5 wrinkles almost any tubing"; "CLR/OD of 2 is the standard tight bend" | Unsourced thresholds stated as fact. Refer readers to the die maker's mandrel/wiper chart (wall factor × D of bend). | Medium |
| `mandrel-vs-rotary` | Press bending "ID restriction can hit 15–20%"; rotary draw "minor ovaling (5–8%)"; mandrel "preserves nearly 100%"; "2-3× longer" cycle | Unsourced percentages. | Medium |
| `best-hydraulic-tube-bender` | "12-ton … enough to bend 1.75" DOM"; "stainless and chromoly drop capacity by roughly 25–30%"; die prices "$80 … $1,200"; "20-ton manual hydraulic bender … highest-leverage purchase" | Unsourced. The last point recommends a ram-style class that often flattens thin-wall tube; clarify rotary-draw versus ram. | Medium |
| `cnc-tube-bending` | Price ranges ($25k–$250k), payback maths, cycle times, floor/power specs | Unsourced. Label them as illustrative or cite dealers. | Medium |
| `custom-exhaust-fabrication` | "2.5" for NA under 350 hp … 3" for …"; "minimum 3.5" ground clearance"; "Most exhaust shops keep a stock of pre-bent mandrel sections" | Rules of thumb; label them as such. | Low |
| `diy-garage-workshop`, `tube-notching-guide`, `conduit-bending-electricians` | Product and tonnage suggestions | Not fact-checked in this pass. Review before relying on them. | Low |

## Pages created by the v1 Repair Tool

| Page | Problem | Fix |
|---|---|---|
| About, Editorial Standards, Methodology, Affiliate Disclosure, Safety, Glossary | One-line placeholders. Methodology claimed "How we workshop-test every tube bender". | **Benders Hub → Upgrade to v2 → Replace untouched placeholders.** Pages you have edited are skipped, and the old version stays in Revisions. |
| Privacy policy, Terms, Cookie policy, Contact | Placeholders | Not auto-generated (legal and contact details are yours to provide). Use Settings → Privacy and add a real contact method. |
| Ten "equipment" posts (`/equipment/eq-*`) | Fictional products ("Pro Workshop 20-Ton Hydraulic Bender" …) with invented scores, published with Product + Review schema. | v2 already removes the schema, hides the scores, labels them "category profile" and sets noindex. You can also **Move to Draft** on the Upgrade page; their URLs then 301-redirect to `/equipment/`. |

## Customizer text

If you saved Customizer text in v1, check **Appearance → Customize → Benders Hub** for claims you cannot evidence. v2 ignores v1's untouched default strings automatically: "researched, tested, and reviewed by working fabricators", "Join 12,000+ Fabricators" and "Every bender … is reviewed by working fabricators". Text you wrote yourself is kept as is.
