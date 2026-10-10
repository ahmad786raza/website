<?php
/**
 * Content Library — seed articles + admin control panel.
 *
 * Adds: Benders Hub → Content Library
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Admin menu entry.
 */
function bdh_content_library_menu() {
	add_submenu_page(
		'bdh-dashboard',
		__( 'Content Library', 'benders-hub' ),
		__( 'Content Library', 'benders-hub' ),
		'manage_options',
		'bdh-content-library',
		'bdh_content_library_render'
	);
}
add_action( 'admin_menu', 'bdh_content_library_menu', 11 );

/**
 * Seed categories — created on demand.
 */
function bdh_seed_categories() {
	return array(
		'guides'             => array( 'Buying Guides',         'Workshop-tested buying guides for tube benders, dies, and fabrication tools.' ),
		'tutorials'          => array( 'Tutorials',             'Step-by-step fabrication walkthroughs.' ),
		'reviews'            => array( 'Reviews',               'Hands-on equipment reviews.' ),
		'fabrication-theory' => array( 'Fabrication Theory',    'Engineering theory: bend radius, K-factor, springback, material science.' ),
		'safety'             => array( 'Safety & Standards',    'Workshop safety, OSHA practices, certification standards.' ),
		'workshop'           => array( 'Workshop Setup',        'Building a fabrication shop from scratch.' ),
	);
}

/**
 * The 12 seed articles.
 */
function bdh_seed_articles() {
	return array(

		'best-hydraulic-tube-bender' => array(
			'title'    => 'The Complete Guide to Hydraulic Tube Benders: Choosing Your First Workshop Bender',
			'cat'      => 'guides',
			'tags'     => array( 'hydraulic bender', 'tube bender', 'buying guide', 'workshop' ),
			'excerpt'  => 'A hydraulic tube bender is the single biggest force multiplier in a fabrication shop. Here\'s how to pick the right one — capacity, dies, tonnage, and the mistakes that cost first-time buyers thousands.',
			'kw'       => 'hydraulic tube bender',
			'content'  => <<<HTML
<p>A hydraulic tube bender is the single biggest force multiplier in a fabrication shop. The difference between a junk import and a workshop-grade hydraulic bender is the difference between fighting your tooling every job and producing repeatable, wrinkle-free bends in minutes. This guide walks through exactly what to look for.</p>

<h2>Capacity: Match the Bender to the Heaviest Tubing You'll Actually Bend</h2>
<p>The first specification on every hydraulic bender's data sheet is capacity — usually expressed as a range like "1/2\" to 2\" OD" or "Schedule 40 pipe up to 3\"." That number is the largest tube the machine can bend in mild steel. Stainless steel and chromoly drop that capacity by roughly 25–30%. Don't buy a bender rated for exactly your maximum tubing size — you want a 25% margin so the machine isn't running at its physical limit on every job.</p>

<h2>Tonnage and Power: Why 12-Ton Sells Garages and 50-Ton Sells Shops</h2>
<p>Tonnage is the hydraulic force the ram delivers. A 12-ton bender is the entry sweet spot — enough to bend 1.75\" DOM with the right die, compact enough to fit on a workbench, and runs on standard 110V or a manual hand pump. The 20-ton class is the workshop standard for chassis and exhaust work. 50-ton and above is for production pipe benders working schedule 40 or schedule 80 in 3-4\" sizes.</p>

<h2>Dies: The Hidden Cost That Doubles Your Investment</h2>
<p>Tube benders are sold with a starter die set — typically three or four sizes. Every additional OD/CLR combination is a separate die that can run anywhere from $80 for basic mild-steel rotary draw dies to $1,200 for hardened mandrel-ball assemblies. Before you buy a bender, list every tube diameter you plan to bend, check the manufacturer's die catalog, and budget for the dies — not the machine.</p>

<h2>Rotary Draw vs Press vs Roll</h2>
<p>Hydraulic benders come in three process classes:</p>
<ul>
<li><strong>Rotary draw</strong> — the gold standard for accuracy. The tube is clamped against the die and the die rotates, drawing the tube around itself. This is what mandrel benders use.</li>
<li><strong>Press / ram bending</strong> — a hydraulic ram pushes the tube against two outer dies. Fast and cheap but causes ovaling on thin wall.</li>
<li><strong>Roll bending</strong> — three rollers progressively form the tube into a large radius. Best for handrail and architectural work, not tight bends.</li>
</ul>

<h2>Real-World Picks</h2>
<p>For a single-bay garage, a 20-ton manual hydraulic bender with a multi-die starter pack is the highest-leverage purchase you can make. For a professional shop running exhaust or chassis work daily, a 12-ton mandrel rotary draw is the upgrade. For industrial pipe work, the 50-ton vertical hydraulic is the floor standard.</p>

<p>Whatever you buy, run the <a href="/#bdh-calculator">bend radius calculator</a> with your typical tube specs before ordering — it predicts wrinkle risk and tells you whether you need a mandrel before you commit.</p>
HTML
		),

		'bend-radius-explained' => array(
			'title'    => 'Bend Radius Explained: CLR, K-Factor, and Why Springback Will Bite You',
			'cat'      => 'fabrication-theory',
			'tags'     => array( 'bend radius', 'CLR', 'springback', 'K-factor', 'engineering' ),
			'excerpt'  => 'CLR, bend allowance, springback, K-factor — the math behind every clean bend. Master this and you stop ruining tubing.',
			'kw'       => 'bend radius calculator',
			'content'  => <<<HTML
<p>Every fabricator eventually meets the moment when a bend comes out 4° short, the tube wrinkles on the inside of the curve, or the cut length is two inches off the drawing. The cause is almost never the bender — it's the math nobody taught you. Here's the engineering theory behind every bend, in the language of a working shop.</p>

<h2>Centerline Radius (CLR): The Number That Defines Your Bend</h2>
<p>CLR is the distance from the center of the bend to the centerline of the tube. It is NOT the outside or inside radius. Every die in your shop is specified by CLR. If you don't match CLR to your tubing wall thickness, you'll wrinkle. The rule of thumb every chassis fabricator follows: <strong>CLR should be at least 2× the outside diameter</strong> of the tube for clean rotary draw bends. Drop below 1.5× and you need a mandrel or you'll collapse the inside wall.</p>

<h2>K-Factor: Where the Neutral Axis Actually Lives</h2>
<p>When a tube bends, the outside stretches and the inside compresses. Somewhere between them is the neutral axis — the imaginary line that neither stretches nor compresses. K-factor expresses where that neutral axis sits as a ratio of wall thickness. Mild steel sits around K=0.41. Stainless is around K=0.40. Aluminum is K=0.33. The exact number determines your cut length:</p>
<p><strong>Bend Allowance = θ × (R + K·t)</strong></p>
<p>Where θ is the bend angle in radians, R is your CLR, t is wall thickness, and K is the K-factor. The bend allowance is how much tube material is consumed by the bend — subtract it from your part length to get the right cut length.</p>

<h2>Springback: The Tube Always Wants to Be Straight</h2>
<p>Metal is elastic. When you release a bent tube from the die, it springs back toward straight. The amount depends on the material:</p>
<ul>
<li>Mild steel: ~1.5% of bend angle</li>
<li>Chromoly 4130: ~2.5% — significantly more</li>
<li>T304 stainless: ~3% — the worst offender</li>
<li>Aluminum 6061: ~1.3% — easiest</li>
</ul>
<p>To compensate, over-bend by the springback amount. For a 90° bend in stainless, you bend to about 92.7° and let it spring back to 90°. Every shop that's any good keeps a springback chart on the wall next to the bender for each material they run.</p>

<h2>D/T Ratio: The Predictor Nobody Mentions</h2>
<p>Diameter-to-thickness ratio (D/T) tells you whether your tube can handle a bend without collapsing. Below 30, you have plenty of margin. Above 50, you're in mandrel-only territory. Above 70, even mandrels struggle. This is why thin-wall exhaust tubing — say 2.5\" × .049\" — has a D/T of 51 and absolutely requires a mandrel to bend cleanly.</p>

<h2>Run the Numbers Before You Cut</h2>
<p>Our <a href="/#bdh-calculator">bend radius calculator</a> takes OD, wall, material, and bend angle and spits back wrinkle risk, springback compensation, bend allowance, and cutting length in one shot. Use it before every new tubing job — it has saved more sticks of chromoly than any other tool in this site.</p>
HTML
		),

		'mandrel-vs-rotary' => array(
			'title'    => 'Mandrel vs Rotary Draw vs Press Bending: Which Process for Your Project',
			'cat'      => 'fabrication-theory',
			'tags'     => array( 'mandrel bender', 'rotary draw', 'press bender', 'process' ),
			'excerpt'  => 'Three bending processes, three completely different results. Picking wrong wastes tubing and money. Here\'s the decision framework.',
			'kw'       => 'mandrel tube bender',
			'content'  => <<<HTML
<p>Three bending processes dominate the fabrication world: mandrel, rotary draw without mandrel, and press (ram) bending. Each produces a fundamentally different bend, and choosing the wrong one for the job is the single most expensive mistake a new fabricator makes.</p>

<h2>Press / Ram Bending: Cheap, Fast, Ovaled</h2>
<p>A hydraulic ram pushes the tube against two stationary outer dies. The tube bends but the cross-section deforms — the inside of the bend compresses, the tube ovals at the apex of the bend, and the ID restriction can hit 15–20%. Press bending is fine for handrail, decorative work, or anywhere the tube is hidden. It is not acceptable for exhaust, charge piping, or any application where flow matters.</p>

<h2>Rotary Draw (No Mandrel): The Workshop Standard</h2>
<p>The tube is clamped against a rotating bend die. As the die rotates, the tube is drawn around it, supported by a counter die on the outside. With CLR ≥ 2× OD and proper lubrication, this produces clean bends with minor ovaling (5–8%) in most mild steel and chromoly applications. This is the process every chassis fabricator and most pipe shops use.</p>

<h2>Mandrel Bending: The Premium Finish</h2>
<p>A mandrel — a chain of articulating steel balls — sits inside the tube at the bend point, supporting the inside wall as it's drawn around the die. The mandrel prevents ovaling and ID restriction almost entirely. Mandrel bends preserve nearly 100% of the original cross-section, which is why every high-end exhaust shop and every charge piping fabricator uses mandrel benders exclusively.</p>

<h2>The Decision Framework</h2>
<table>
<tr><th>Application</th><th>Process</th><th>Why</th></tr>
<tr><td>Exhaust, headers, charge piping</td><td>Mandrel</td><td>Flow critical — no ID restriction</td></tr>
<tr><td>Roll cages, chassis</td><td>Rotary draw</td><td>Strength critical, not flow</td></tr>
<tr><td>Handrail, decorative</td><td>Press or roll</td><td>Cosmetic, hidden ID</td></tr>
<tr><td>Conduit, EMT</td><td>Rotary draw</td><td>Wire pull-through matters</td></tr>
<tr><td>Production pipe runs</td><td>Rotary draw or CNC</td><td>Speed + repeatability</td></tr>
</table>

<h2>The Hidden Cost: Cycle Time</h2>
<p>Mandrel bending is slower than rotary draw. Each bend can take 2-3× longer because the mandrel must be inserted, the bend executed slowly to avoid collapsing the mandrel, and the mandrel extracted. For one-off exhausts that's fine. For production runs over 50 pieces, the cycle time becomes a real cost. CNC mandrel benders mitigate this with automatic mandrel insertion.</p>

<p>For your project, run the <a href="/#bdh-compatibility">compatibility generator</a> — input your tubing, material, and goal, and it'll tell you which process to use and what dies to buy.</p>
HTML
		),

		'chromoly-fabrication' => array(
			'title'    => 'Chromoly 4130 Fabrication: Roll Cage Best Practices',
			'cat'      => 'tutorials',
			'tags'     => array( 'chromoly', '4130', 'roll cage', 'chassis', 'TIG' ),
			'excerpt'  => 'Chromoly 4130 is the chassis fabricator\'s gold standard — but only if you treat it right. Heat treatment, springback compensation, and weld preparation matter more than the bender.',
			'kw'       => 'chromoly tube bender',
			'content'  => <<<HTML
<p>Chromoly 4130 is the lightweight high-strength alloy that defines modern race chassis. It's also the alloy that most punishes shortcuts. Get the bending and welding right and you have a chassis that meets NHRA, SCCA, and FIA specs. Get it wrong and you have a cage that cracks the first hard hit.</p>

<h2>Material Selection: 4130 vs 4130 N (Normalized)</h2>
<p>4130 is a chromium-molybdenum alloy steel. Most racing tubing is sold as 4130N (normalized), which means it's been heat-treated to a uniform grain structure after rolling. Normalized 4130 has a tensile strength of around 97,000 psi versus DOM mild steel's 70,000 psi — that's why you can use thinner walls (typically 0.083\" or 0.095\") for the same strength.</p>

<h2>Bending: CLR, Springback, and the Tonnage Trap</h2>
<p>Chromoly demands a CLR of at least 5× OD and ideally 6× OD for chassis-grade bends. The material has roughly 40% more springback than mild steel — a 90° target requires over-bending to about 92.5°. Don't try to use a 12-ton bender on 1.75\" × .095\" chromoly — you need at least 20 tons of capacity, and 25 is more comfortable. Lubricate every die surface with high-pressure assembly lube.</p>

<h2>Notching: The Fishmouth Determines the Weld</h2>
<p>Every tube-to-tube joint in a chassis needs a fishmouth notch. The notch must follow the OD of the receiving tube exactly. Use bi-metal hole saws sized to the receiving tube's OD — a 1.75\" receiving tube wants a 1.75\" hole saw. Set the notcher's pivot angle to match the joint angle. Anything more than 1/16\" gap between joined tubes is an automatic reject under SFI inspection.</p>

<h2>Welding: TIG Only, With Documented Procedure</h2>
<p>Chromoly chassis welding must be TIG with ER70S-2 or ER80S-D2 filler rod. MIG-welded chromoly chassis fail SFI inspection. The base material is preheated to 300°F before welding, the weld is run continuously around the joint, and the joint is allowed to cool slowly. Quenching a chromoly weld causes brittleness and hairline cracks.</p>

<h2>Heat Treatment After Welding (Or Not)</h2>
<p>Contrary to old shop folklore, post-weld stress-relieving is NOT required for properly preheated chromoly joints on standard chassis applications. Top builders rely on preheat + slow cool. For aerospace or extreme racing applications, stress relief at 1100°F for one hour does measurably improve fatigue life.</p>

<h2>The Tooling Stack</h2>
<ul>
<li>20-ton hydraulic rotary draw bender (minimum)</li>
<li>Chromoly-rated dies — bend radius 6× OD</li>
<li>Tube notcher with chromoly-rated bi-metal hole saws</li>
<li>TIG welder, 200+ amp, foot pedal control</li>
<li>Pyrometer or infrared thermometer for preheat verification</li>
<li>Chassis fab table with magnetic squares</li>
</ul>

<p>For a full kit reference, see the <a href="/#bdh-bundles">Roll Cage Workshop Setup</a> bundle.</p>
HTML
		),

		'custom-exhaust-fabrication' => array(
			'title'    => 'Custom Exhaust Fabrication: A Step-by-Step Mandrel Workflow',
			'cat'      => 'tutorials',
			'tags'     => array( 'exhaust', 'mandrel', 'header', 'fabrication', 'tutorial' ),
			'excerpt'  => 'Custom exhaust work is where mandrel benders earn their keep. Walk through a full cat-back fabrication from measurement to weld-out.',
			'kw'       => 'mandrel exhaust bender',
			'content'  => <<<HTML
<p>A custom cat-back exhaust is one of the highest-impact upgrades you can fabricate yourself — and one of the projects where shortcut tubing kits look amateur. This walkthrough covers the full mandrel workflow from car-on-jack-stands to final weld.</p>

<h2>Step 1: Measurement and Mockup</h2>
<p>Park the car, jack it up, secure on stands, and remove the existing exhaust from the cat-back. Lay it on the floor — this is your reference. Measure total length from the cat to the tailpipe exit. Measure every bend angle with a digital angle finder. Note the routing around obstacles: driveshaft, axle, frame rails, heat shields.</p>

<h2>Step 2: Choose Tubing Diameter</h2>
<p>For naturally aspirated street cars under 350hp, 2.5\" OD is the standard. 300+ hp turbocharged or 400+ hp NA cars step up to 3\". Going larger than needed actually loses low-end torque — bigger isn't better. The wall thickness is almost always 0.049\" (16ga) or 0.065\" (14ga) for street exhausts. Race exhausts go thinner for weight.</p>

<h2>Step 3: Cut Pre-Bent Sections to Length</h2>
<p>Most exhaust shops keep a stock of pre-bent mandrel sections — 90°, 60°, 45°, 30°, and straight pieces. Cut each pre-bent piece to mock up against the car using a chop saw with a cutoff wheel rated for stainless. Leave 1/4\" extra on every joint for fitment trimming.</p>

<h2>Step 4: Tack-Weld and Mock In Place</h2>
<p>With the pieces cut, get back under the car and clamp them together with exhaust clamps. The goal is to verify routing, ground clearance (minimum 3.5\" at the lowest point), and clearance from heat-sensitive components (fuel lines, brake lines, plastic bumpers). Mark every joint with a Sharpie before disassembling.</p>

<h2>Step 5: Tack-Weld Joints, Recheck Fitment</h2>
<p>Bring the assembled exhaust to the bench. Tack-weld each joint at 12 o'clock, 4 o'clock, and 8 o'clock. Don't fully weld yet. Bring the tacked exhaust back under the car and verify every joint is still in alignment, every clearance is still met. This is the last chance to adjust before the weld locks geometry in.</p>

<h2>Step 6: Full Weld-Out</h2>
<p>Back to the bench. TIG weld every joint with ER308L filler on T304 stainless. MIG weld is acceptable on mild-steel exhausts but TIG is the visible mark of a quality build. Run the weld continuously around the joint — stop-start welds are stress risers that fail under thermal cycling.</p>

<h2>Step 7: Hangers, V-Bands, Final Install</h2>
<p>Locate the OEM hanger points and weld matching brackets to the new exhaust. Install V-band flanges at any service joints where you'll need to remove sections (rear muffler, mid-pipe). Install with new gaskets and verify final clearances under load — engine running, transmission in gear with the brake held.</p>

<p>For the full tooling list, see the <a href="/#bdh-bundles">Exhaust Fabrication Bundle</a>.</p>
HTML
		),

		'cnc-tube-bending' => array(
			'title'    => 'CNC Tube Bending for Small Production Shops: The Buyer\'s Reality Check',
			'cat'      => 'guides',
			'tags'     => array( 'CNC bender', 'production', 'automation', 'investment' ),
			'excerpt'  => 'A CNC tube bender will pay for itself in 18 months — if you have the volume to justify it. Here\'s how to know whether you do.',
			'kw'       => 'CNC tube bender',
			'content'  => <<<HTML
<p>A CNC tube bender is the productivity weapon that lets a small shop compete with much larger production facilities. It also costs anywhere from $25,000 to $250,000. Before you commit, run the math.</p>

<h2>When CNC Pays For Itself</h2>
<p>The break-even calculation is volume × labor cost saved per bend. A typical chassis fabricator runs a manual hydraulic bender at roughly 90 seconds per bend (set angle, position tube, bend, release, reposition). A CNC bender runs the same bend in 6-12 seconds with no operator labor between bends. On a 200-bend production run, that's nearly 5 hours of operator time saved. At $50/hour shop rate, that's $250 per 200-bend run.</p>
<p>A $40,000 CNC bender pays back at 200 bends/week in about 160 weeks — three years. If you can run 500 bends/week, it pays back in 14 months.</p>

<h2>What "CNC" Actually Means in Tube Bending</h2>
<p>CNC tube benders control three axes: rotation (the bend angle), feed (how far the tube moves between bends), and Y-rotation (the orientation of subsequent bends around the tube axis). Together those three control any multi-bend tubular geometry. The control unit imports a part program (typically from CAD/CAM tubing software) and the operator just loads tube stock and presses cycle.</p>

<h2>Compact CNC vs Industrial CNC</h2>
<p>Compact CNC benders ($25-60K class) handle up to ~2.5\" OD, fit in a single bay, and run on 220V single-phase. They're the right buy for a shop graduating from manual hydraulic. Industrial CNC benders ($80-250K class) handle 4\"+ OD, require 3-phase power, and include features like multi-stack die changeover, mandrel ball assemblies, and robotic loading.</p>

<h2>The Software is Half the Investment</h2>
<p>You're not really buying a machine — you're buying a programming workflow. The CAD/CAM tubing software (Bend-Tech, VTube, or proprietary OEM packages) takes parts from CAD (or hand measurements via a tube measurement arm) and outputs the bender program. Budget $3,000-15,000 for the software stack. Free trial software exists but production shops pay for the full versions.</p>

<h2>The Operator Reality</h2>
<p>A CNC bender does NOT eliminate the operator — it changes the job. The operator is now a programmer, loader, and quality inspector. Programming a new part takes 1-3 hours. Loading and running takes minutes per piece. Set this expectation before the machine arrives or you'll have a $40K paperweight while someone learns the control.</p>

<h2>Floor and Power Requirements</h2>
<ul>
<li>Compact CNC: 8' × 12' floor space, 220V/50A single phase, level concrete</li>
<li>Industrial CNC: 12' × 20' floor space, 480V 3-phase 100A+, reinforced slab</li>
<li>Both: compressed air at 80+ psi, clean material handling area, lighting</li>
</ul>

<p>Run our <a href="/#bdh-prioritizer">Workshop Efficiency Prioritizer</a> with high marks for precision and automation — it will tell you exactly which CNC class fits your shop profile.</p>
HTML
		),

		'diy-garage-workshop' => array(
			'title'    => 'DIY Garage Workshop: 12 Tools Every Fabricator Needs',
			'cat'      => 'workshop',
			'tags'     => array( 'garage', 'workshop setup', 'tools', 'beginner' ),
			'excerpt'  => 'Walking into an empty single-bay garage with a tubing project in mind? Here\'s exactly what to buy — and the order to buy it.',
			'kw'       => 'garage fabrication tools',
			'content'  => <<<HTML
<p>Setting up a fabrication workshop in a one-car garage is one of the most rewarding builds you can attempt. It's also one of the easiest to over-spend. This is the prioritized list of every tool a working garage fabricator actually uses — and the order to buy them in.</p>

<h2>1. Workbench (Steel, Heavy)</h2>
<p>The foundation. A 6' × 3' steel bench with a 1/4\" plate top, 4-leg pedestal, and a 6\" bench vise welded or bolted to the corner. Skip the wood-top hobby benches — you'll be grinding, welding, and clamping. Spend the money once.</p>

<h2>2. Bench Vise (6\" or 8\" Jaw)</h2>
<p>Goes on the workbench. Buy heavy. The vise lives on every job from bender setup to weld jigs. A 6\" jaw is the minimum for tubing work, 8\" if you have room.</p>

<h2>3. Hydraulic Tube Bender (20-Ton Manual)</h2>
<p>The centerpiece. A 20-ton manual hydraulic bender with a multi-die starter pack covers tubing from 1/2\" to 2\" — which is 90% of garage projects. Bench-mounted, hand-pumped, runs no electricity. The right starter bender for most builders.</p>

<h2>4. Chop Saw (14\" Cold Cut)</h2>
<p>Cold-cut saws (also called dry-cut) cut tubing cleanly without the burrs and discoloration of abrasive saws. The blade lasts thousands of cuts on tubing. Worth 3× the price of an abrasive chop saw.</p>

<h2>5. Tube Notcher (Drill-Press Style)</h2>
<p>For any tube-to-tube joint, you need fishmouth notches. A drill-press-mounted tube notcher with a bi-metal hole saw is the right entry point. Adjustable 0–60° pivot.</p>

<h2>6. Welder (MIG 140A 110V to Start, TIG 200A Later)</h2>
<p>For mild steel garage work, a 140-amp MIG welder running off 110V is the right starter. Upgrade to 220V MIG (180-200A) when you start chassis work. TIG welder when you start exhaust or chromoly. Don't buy all three at once.</p>

<h2>7. Angle Grinder (4.5\" + 7\")</h2>
<p>Two of them. A 4.5\" with cutoff wheels for trimming and a 7\" with grinding wheels for weld dressing. The most-used tools in the shop after the welder.</p>

<h2>8. Plasma Cutter (40A, Optional Tier 2)</h2>
<p>Not strictly necessary in the first six months but transformative once you have it. A 40A plasma cuts 1/2\" plate cleanly. Buy after the welder is paid for.</p>

<h2>9. Drill Press (12\" Floor Standing)</h2>
<p>Hosts the tube notcher when you need it, drills clean holes when you don't. A 12\" floor-standing drill press is the right scale for tubing work.</p>

<h2>10. Digital Angle Finder + Calipers + Tape</h2>
<p>The measurement trio. Digital angle finder for bend angles. 6\" digital calipers for tubing OD and wall. 25' tape for layout. Cheap, used constantly.</p>

<h2>11. PPE: Auto-Darkening Helmet, Gloves, Boots</h2>
<p>Auto-darkening welding helmet (not fixed-shade), leather welding gloves, steel-toe boots, safety glasses, hearing protection. Spend the money on the helmet — you'll wear it for thousands of hours.</p>

<h2>12. Compressor (60-Gallon, 220V)</h2>
<p>For air tools, cleaning parts, and powering pneumatic clamps. 60-gallon vertical tank, 220V single-phase. Bigger than you think you need.</p>

<p>For a full kit list with Amazon picks, see our <a href="/#bdh-bundles">Compact Garage Fabrication Kit</a>.</p>
HTML
		),

		'tube-wrinkling-prevention' => array(
			'title'    => 'Tube Wrinkling and Collapse: Diagnosis and Prevention',
			'cat'      => 'fabrication-theory',
			'tags'     => array( 'wrinkling', 'tube collapse', 'troubleshooting', 'mandrel' ),
			'excerpt'  => 'A wrinkled tube is the most common fabrication failure. Here\'s why it happens, how to predict it, and how to prevent it.',
			'kw'       => 'tube wrinkle mandrel',
			'content'  => <<<HTML
<p>You pull a tube out of the bender and the inside of the curve looks like a stack of poker chips — concentric ridges marching down the bend. That's wrinkling. It's the most common fabrication failure on the planet, and once you understand why it happens, it stops happening to you.</p>

<h2>Why Tubes Wrinkle</h2>
<p>The inside of a bent tube has to compress to accommodate the shorter inside curve length. If the wall is too thin, the CLR is too tight, or the material is too strong, that compression force exceeds the wall's column strength and the wall buckles. Buckling shows as the regular ridges you see on a wrinkled bend.</p>

<h2>The D/T Ratio Predictor</h2>
<p>Diameter-to-thickness ratio (outside diameter divided by wall thickness) predicts wrinkle risk:</p>
<ul>
<li>D/T below 20: bullet-proof, never wrinkles in mild steel</li>
<li>D/T 20-30: clean bends with standard rotary draw</li>
<li>D/T 30-50: needs careful die selection and CLR ≥ 3×OD</li>
<li>D/T 50-70: mandrel territory — wrinkles without one</li>
<li>D/T above 70: mandrel required, slow feed rate</li>
</ul>
<p>For 2.5\" × 0.049\" exhaust tubing, D/T is 51 — squarely in mandrel territory. That's why mandrel benders dominate exhaust work.</p>

<h2>The CLR Trap</h2>
<p>The other variable is CLR/OD ratio. A common rookie mistake: buying a die that's too tight for the tubing. CLR/OD below 1.5 wrinkles almost any tubing without a mandrel. CLR/OD of 2 is the standard "tight" bend. CLR/OD of 3+ is conservative. When in doubt, go larger.</p>

<h2>Material Matters More Than People Realize</h2>
<p>Chromoly and stainless are stronger than mild steel — and that strength works against you in bending. Stronger material resists compression but the resistance comes out as buckling instead of smooth flow. That's why chromoly chassis builders insist on 5×OD or 6×OD CLRs even when the geometry could accept tighter bends.</p>

<h2>The Lubrication Often-Forgotten Factor</h2>
<p>Even with perfect CLR and the right mandrel, a dry die surface causes friction that creates wrinkles. Use high-pressure assembly lube (Bostick Never-Seez, Lubri-Plate, or equivalent) on every die surface that contacts the tube. The lubricant lets the tube flow smoothly around the die instead of dragging.</p>

<h2>Mandrel Setup: The Last 10%</h2>
<p>Even with a mandrel, wrinkles can appear if:</p>
<ul>
<li>The mandrel ball is too far behind the bend point (push it forward 1/8")</li>
<li>The wiper die is worn or out of alignment</li>
<li>The bend speed is too fast (slow down — mandrel bends are not race events)</li>
</ul>

<h2>Diagnosis Decision Tree</h2>
<table>
<tr><th>Symptom</th><th>Likely Cause</th><th>Fix</th></tr>
<tr><td>Ridges on inside of bend</td><td>Wall too thin for CLR</td><td>Larger CLR or mandrel</td></tr>
<tr><td>Tube ovaled at apex</td><td>No mandrel + thin wall</td><td>Add mandrel</td></tr>
<tr><td>Drag marks on outside</td><td>Insufficient lubrication</td><td>Lube die</td></tr>
<tr><td>Random wrinkles</td><td>Tube spinning in clamp</td><td>Increase clamp pressure</td></tr>
</table>

<p>Run our <a href="/#bdh-calculator">bend radius calculator</a> with your specs — it predicts wrinkle risk in real time before you bend.</p>
HTML
		),

		'stainless-bending' => array(
			'title'    => 'Stainless Steel Bending: Tonnage, Dies, and Lubrication',
			'cat'      => 'tutorials',
			'tags'     => array( 'stainless', 'T304', 'T321', 'lubrication', 'tonnage' ),
			'excerpt'  => 'Stainless behaves nothing like mild steel in the bender. Higher tonnage, more springback, special dies. Here\'s the working playbook.',
			'kw'       => 'stainless tube bender',
			'content'  => <<<HTML
<p>Stainless tubing in the shop forces every assumption from mild steel work to be re-examined. The tonnage is wrong, the springback is wrong, the lubrication is wrong, and the die wear is faster. Get it dialed and stainless work becomes a profit center. Skip the adjustments and you'll wreck tubing all day.</p>

<h2>Material Grades You'll Encounter</h2>
<ul>
<li><strong>T304</strong> — the most common austenitic stainless. Good corrosion resistance. Standard for exhaust, food-grade applications, charge piping.</li>
<li><strong>T304L</strong> — low-carbon version. Better for welded applications. More expensive.</li>
<li><strong>T316</strong> — adds molybdenum for marine corrosion resistance. Significantly more expensive.</li>
<li><strong>T321</strong> — titanium-stabilized stainless for high-temperature exhaust headers. The premium choice for race headers.</li>
<li><strong>T409</strong> — ferritic stainless, common for OEM exhausts. Cheaper, less corrosion-resistant.</li>
</ul>

<h2>Tonnage: Add 30%</h2>
<p>Where mild steel needs 12 tons to bend 1.75\" × 0.095\", T304 stainless needs roughly 15-16 tons to bend the same wall. T321 needs 18+. Don't try to bend stainless on a bender at its mild-steel capacity limit — you'll stall the ram and produce inconsistent bends. Up-rate your bender capacity by 30% when planning stainless work.</p>

<h2>Springback: Plan to Over-Bend</h2>
<p>T304 stainless springs back 60-80% more than mild steel. A 90° bend target needs to be bent to about 93°. T321 is even worse — about 94°. Keep a springback chart on the wall for every material and angle combination you commonly run.</p>

<h2>Dies: Hardened Tool Steel, Polished</h2>
<p>Standard rotary draw dies for mild steel will bend stainless, but they wear fast. The stainless work-hardens against the die surface. Use hardened tool steel dies (D2 or A2) for any stainless production. Polish the die contact surface to a near-mirror finish — surface imperfections transfer to the tubing.</p>

<h2>Lubrication: The Hidden Variable</h2>
<p>Standard assembly lube does not work for stainless bending. Use a high-pressure molybdenum disulfide grease (Bostick Never-Seez Nickel grade or equivalent) on every die surface and on the OD of the tube at the bend point. Without proper lubrication, stainless galls against the die and produces visible drag marks on the tube finish.</p>

<h2>Feed Rate</h2>
<p>Slow down. Mild steel bends fine at full ram speed. Stainless wants 60-70% of full ram speed to allow the material to flow without buckling. CNC operators reduce the feed rate parameter accordingly. Manual hydraulic operators just don't pump the handle as fast.</p>

<h2>Heat Considerations</h2>
<p>Heavy-section stainless bending generates significant heat at the die contact point. For production runs, schedule cooling cycles every 8-10 bends to keep the die under 200°F. Hot dies accelerate wear and degrade dimensional accuracy.</p>

<h2>Welding Integration</h2>
<p>Almost every stainless bending job is followed by welding. TIG with ER308L or ER309L filler (for dissimilar joints). Backpurge with argon for exhaust applications — sugaring on the ID destroys flow performance. Allow stainless to cool slowly after welding to prevent carbide precipitation in the heat-affected zone.</p>

<p>For the bender + die + lubricant combo, see the <a href="/#bdh-bundles">Exhaust Fabrication Bundle</a>.</p>
HTML
		),

		'roll-cage-specifications' => array(
			'title'    => 'Roll Cage Tubing Specifications: NHRA, SCCA, FIA Comparison',
			'cat'      => 'safety',
			'tags'     => array( 'roll cage', 'NHRA', 'SCCA', 'FIA', 'specifications', 'safety' ),
			'excerpt'  => 'Three sanctioning bodies, three different rulebooks. Building to the wrong spec fails tech. Here\'s exactly what each requires.',
			'kw'       => 'roll cage tubing',
			'content'  => <<<HTML
<p>Building a competition roll cage requires hitting the exact tubing specification mandated by the sanctioning body. The three big ones — NHRA, SCCA, and FIA — each have their own rulebook with non-overlapping requirements. Building to the wrong spec means failing inspection.</p>

<h2>NHRA: Drag Racing Standard</h2>
<p>NHRA splits cage requirements by elapsed time bracket:</p>
<ul>
<li><strong>11.00-9.99 ET</strong> — 1-5/8\" × 0.118\" mild steel OR 1-5/8\" × 0.083\" chromoly</li>
<li><strong>9.99-8.50 ET</strong> — 1-5/8\" × 0.134\" mild steel OR 1-3/4\" × 0.083\" chromoly</li>
<li><strong>8.50 and quicker</strong> — 1-3/4\" × 0.118\" mild steel OR 1-3/4\" × 0.083\" chromoly. SFI 25.5 chassis cert required.</li>
</ul>
<p>NHRA chromoly requires SFI 25.5 inspection and certification, renewed every 3 years.</p>

<h2>SCCA: Road Racing Standard</h2>
<p>SCCA's general competition rules (GCR) require:</p>
<ul>
<li>Closed cars (production class): 1-1/2\" × 0.120\" DOM or 1-3/4\" × 0.083\" chromoly</li>
<li>Open cars: 1-3/4\" × 0.095\" DOM minimum</li>
<li>Spec Miata: tubing must follow Spec Miata Rules Section 5</li>
</ul>
<p>SCCA emphasizes joint quality and triangulation more than NHRA. All main hoop bends must be CLR ≥ 3× OD.</p>

<h2>FIA: International Standard (Article 253)</h2>
<p>FIA Appendix J Article 253 is the standard for international competition. Requirements include:</p>
<ul>
<li>Main rollbar: 50 × 2.5 mm (1.97\" × 0.098\") cold-drawn seamless mild steel OR 45 × 2.5 mm (1.77\" × 0.098\") chromoly 25CrMo4 (4130)</li>
<li>FIA homologation tag on the cage</li>
<li>Welding documentation by certified welder</li>
<li>Diagonal brace mandatory in main hoop plane</li>
</ul>

<h2>The Practical Comparison</h2>
<table>
<tr><th>Body</th><th>Mild Steel</th><th>Chromoly</th><th>Cert Required</th></tr>
<tr><td>NHRA 11s</td><td>1-5/8 × 0.118</td><td>1-5/8 × 0.083</td><td>SFI 25.1</td></tr>
<tr><td>NHRA 8s</td><td>1-3/4 × 0.118</td><td>1-3/4 × 0.083</td><td>SFI 25.5</td></tr>
<tr><td>SCCA Closed</td><td>1-1/2 × 0.120</td><td>1-3/4 × 0.083</td><td>SCCA tech</td></tr>
<tr><td>FIA</td><td>50 × 2.5 mm</td><td>45 × 2.5 mm</td><td>FIA homologation</td></tr>
</table>

<h2>Common Build Errors That Fail Tech</h2>
<ul>
<li>Using DOM (not "seamless cold-drawn") where the rulebook specifies seamless</li>
<li>Welding chromoly with MIG instead of TIG</li>
<li>Notch gaps exceeding 1/16\"</li>
<li>Main hoop bends with CLR below specified minimum</li>
<li>Missing diagonal brace</li>
<li>Mounting plates thinner than rule book requirement (typically 3/16\" minimum)</li>
</ul>

<h2>Material Sourcing</h2>
<p>Buy from chassis-supply specialists, not from generic steel yards. Chassis suppliers stock material with mill certs (proving the alloy spec) and the right diameters and walls in stock. Generic steel yards may sell "DOM" that doesn't meet the seamless specification.</p>

<p>For a complete roll cage tooling list, see our <a href="/#bdh-bundles">Roll Cage Workshop Setup</a>.</p>
HTML
		),

		'tube-notching-guide' => array(
			'title'    => 'Tube Notching for Chassis Builds: Fishmouth, Saddle, and V-Cut',
			'cat'      => 'tutorials',
			'tags'     => array( 'notching', 'fishmouth', 'chassis', 'joint preparation' ),
			'excerpt'  => 'A clean notch is the difference between a weld that holds and a weld that fails. Three notch types every chassis fabricator must master.',
			'kw'       => 'tube notcher fishmouth',
			'content'  => <<<HTML
<p>Every tube-to-tube joint in a chassis lives or dies by the quality of the notch. A fishmouth that's 1/16\" off creates a gap the welder has to fill — and a filled gap is a stress concentration that cracks under load. This is the working knowledge every chassis fabricator builds in their first year.</p>

<h2>The Three Notch Types</h2>
<p><strong>Fishmouth (saddle) notch:</strong> The most common. The end of one tube is shaped to fit the OD of another tube it joins to. Used wherever one tube meets the side of another.</p>
<p><strong>Coped notch:</strong> The tube end is cut to fit inside or around another tube. Used for tee joints where the joining tube enters at less than 90°.</p>
<p><strong>V-cut / mitre cut:</strong> The tube is cut at an angle to make a corner joint with another tube cut at a matching angle. Used for picture-frame corner joints in jigs and fixtures.</p>

<h2>Fishmouth Geometry</h2>
<p>For a fishmouth, the saw size must match the receiving tube's OD exactly. A 1.75\" receiving tube wants a 1.75\" hole saw. Common mistake: using a 1-3/4\" Lennox saw on a 1.750\" tube assuming they're the same — they're not. Lennox bi-metal saws are nominally sized; verify with calipers.</p>
<p>For a fishmouth at an angle other than 90° (every diagonal in a chassis), the notcher's tube clamp pivots to that angle. The saw stays vertical; the tube tilts. This produces an elongated saddle that wraps further around the receiving tube.</p>

<h2>Tooling: Drill-Press Notcher vs Stand-Alone</h2>
<p>The drill-press-mounted tube notcher is the entry tool. It uses your existing drill press as the saw drive. Cheap, effective, requires a sturdy drill press. The downside: drill press chucks max out around 1/2\" saw arbor, limiting saw size.</p>
<p>Stand-alone tube notchers have an integrated motor and heavy-duty chuck. They handle larger saws and higher duty cycles. The right buy if you cut more than 20 notches a week.</p>

<h2>Hole Saw Selection</h2>
<p>Bi-metal hole saws (Lennox Diemaster 2, Milwaukee Big Hawg) work for mild steel and DOM. For chromoly, step up to carbide-tipped hole saws — they last 5-10× longer cutting hard alloys. Diameter range: 1\" through 3\" covers 95% of chassis work.</p>

<h2>Process: A Clean Notch in 90 Seconds</h2>
<ol>
<li>Mark the tube where the notch starts</li>
<li>Set the notcher pivot angle to match the joint angle</li>
<li>Clamp the tube — verify it cannot rotate or slide</li>
<li>Start the saw, advance slowly to avoid grabbing</li>
<li>Cut through with steady pressure, withdraw the saw, retract the tube</li>
<li>Deburr the notch edges with a file or rotary tool</li>
</ol>

<h2>Quality Check</h2>
<p>Bring the notched tube to the receiving tube and mate them. Hold up to a light. Any visible gap is too much. The notch should make full 360° contact around the receiving tube. If you see daylight, refit by filing the high spots or recut with the saw advanced slightly further.</p>

<h2>Pitfalls and Fixes</h2>
<ul>
<li><strong>Notch is offset</strong> — tube wasn't clamped centered. Recut with proper centering.</li>
<li><strong>Saw walks during cut</strong> — saw is dull or clamp pressure is too low.</li>
<li><strong>Gap on one side</strong> — pivot angle was set wrong by 1-2°. Verify with digital angle finder.</li>
<li><strong>Burrs inside the notch</strong> — feed rate too aggressive. Slow down and deburr with rotary tool.</li>
</ul>

<p>For a chassis-grade notcher and bi-metal saw stack, see the <a href="/#bdh-bundles">Roll Cage Workshop Setup</a>.</p>
HTML
		),

		'conduit-bending-electricians' => array(
			'title'    => 'Conduit Bending for Electricians: EMT, IMC, and Rigid',
			'cat'      => 'tutorials',
			'tags'     => array( 'conduit', 'EMT', 'IMC', 'electrical', 'field work' ),
			'excerpt'  => 'Conduit bending is its own discipline — different tooling, different tolerances, different physics. The walkthrough for electricians and fab shops crossing over.',
			'kw'       => 'EMT conduit bender',
			'content'  => <<<HTML
<p>Conduit bending lives in a different universe from chassis tubing — different tooling, different precision standards, different field conditions. If you're a fabricator picking up electrical work, or an electrician trying to up your bending game, here's the working knowledge that bridges both worlds.</p>

<h2>The Three Conduit Materials</h2>
<ul>
<li><strong>EMT (Electrical Metallic Tubing)</strong> — thin-wall steel conduit. The lightest, cheapest, easiest to bend. Used indoor and protected outdoor.</li>
<li><strong>IMC (Intermediate Metal Conduit)</strong> — thicker wall, threaded. Heavier than EMT, used outdoor and in industrial environments.</li>
<li><strong>Rigid Metal Conduit (RMC)</strong> — thickest wall, fully threaded. Used in industrial, underground, and where physical protection is paramount.</li>
</ul>

<h2>Hand Bender (The Field Standard)</h2>
<p>The Klein or Ideal hand bender is the iconic electrician's tool. Cast iron or aluminum head, integrated protractor, foot pedal for leverage. Sizes: 1/2\", 3/4\", 1\" EMT in the standard kit. Most field bends — 90°s, offsets, saddles — happen on hand benders.</p>

<h2>Hydraulic Bender (For 1-1/4\" and Up)</h2>
<p>Above 1\" conduit, hand bending becomes hard. Hydraulic conduit benders (Greenlee 880, Enerpac equivalents) handle 1-1/4\" through 4\" EMT, IMC, and rigid. Powered by integrated hand pump, electric pump, or air pump.</p>

<h2>The Four Standard Bends</h2>
<p><strong>90° stub-up:</strong> Single 90° bend. Calculate stub length: distance from end to back of conduit at the 90.</p>
<p><strong>Back-to-back 90°:</strong> Two 90° bends in opposite directions. Critical: measure between bends carefully.</p>
<p><strong>Offset:</strong> Two equal-angle bends that step the conduit up or sideways. Calculate offset distance and multiplier.</p>
<p><strong>Saddle (3-bend):</strong> Goes over an obstacle. Two outer bends at half the angle of the center bend.</p>

<h2>The Math: Multipliers and Take-Up</h2>
<p>Every bend has a "take-up" — how much the bend consumes the conduit length. For a 1/2\" EMT 90° hand bend, take-up is 5\" from end to bend mark. For 3/4\", it's 6\". For 1\", it's 8\". Multipliers for offsets: 22.5° = 2.6×, 30° = 2×, 45° = 1.4×, 60° = 1.15×.</p>
<p>Example: A 30° offset to clear a 6\" obstacle: 6 × 2 = 12\". Mark first bend, measure 12\" along the conduit, mark second bend. Both bends 30°.</p>

<h2>Common Field Mistakes</h2>
<ul>
<li>Bending against the protractor — the bender's protractor reads bend angle on the WORK side, not the tube</li>
<li>Forgetting take-up on first bend — every subsequent bend's reference point shifts</li>
<li>Over-rotating on saddles — the center bend is twice the outer bend angles</li>
<li>Bending EMT outdoor without realizing it isn't UV-rated</li>
</ul>

<h2>Code Requirements (NEC 2023)</h2>
<ul>
<li>Maximum 360° total bend between pull points (no more than four 90°s)</li>
<li>Bend radius minimum: 4× internal diameter for circuits over 600V</li>
<li>Cannot crush, deform, or wrinkle the inside diameter (NEC 300.34)</li>
<li>EMT bends must use approved bender — not "free-hand" wrench bends</li>
</ul>

<h2>Tooling Investment Levels</h2>
<ul>
<li><strong>Service van:</strong> Hand benders 1/2\"-1\", offset multiplier card, level</li>
<li><strong>Shop:</strong> Add 1-1/4\" hand bender, 880-class hydraulic for 1-1/4\"+ work</li>
<li><strong>Industrial:</strong> Electric/pneumatic hydraulic bender, programmable head for repeat bends</li>
</ul>

<p>For the field bender kit, see the <a href="/#bdh-database">Portable Field Conduit Bender</a> in our equipment database.</p>
HTML
		),

	);
}

/**
 * Status of installed seed articles.
 */
function bdh_seed_status() {
	$status = array();
	foreach ( bdh_seed_articles() as $slug => $info ) {
		$existing = get_page_by_path( $slug, OBJECT, 'post' );
		$status[ $slug ] = $existing ? (int) $existing->ID : 0;
	}
	return $status;
}

/**
 * Ensure category exists.
 */
function bdh_ensure_category( $slug ) {
	$cats = bdh_seed_categories();
	if ( ! isset( $cats[ $slug ] ) ) { return 0; }
	$term = term_exists( $slug, 'category' );
	if ( ! $term ) {
		$res = wp_insert_term( $cats[ $slug ][0], 'category', array( 'slug' => $slug, 'description' => $cats[ $slug ][1] ) );
		if ( is_wp_error( $res ) ) { return 0; }
		return (int) $res['term_id'];
	}
	return (int) ( is_array( $term ) ? $term['term_id'] : $term );
}

/**
 * Install a single seed article.
 */
function bdh_install_seed_article( $slug ) {
	$articles = bdh_seed_articles();
	if ( ! isset( $articles[ $slug ] ) ) { return new WP_Error( 'not_found', 'Article slug not found.' ); }
	$a = $articles[ $slug ];

	// Already exists?
	$existing = get_page_by_path( $slug, OBJECT, 'post' );
	if ( $existing ) { return $existing->ID; }

	$cat_id = bdh_ensure_category( $a['cat'] );

	$post_id = wp_insert_post( array(
		'post_title'   => $a['title'],
		'post_name'    => $slug,
		'post_status'  => 'publish',
		'post_type'    => 'post',
		'post_content' => $a['content'],
		'post_excerpt' => $a['excerpt'],
		'post_category'=> $cat_id ? array( $cat_id ) : array(),
		'tags_input'   => isset( $a['tags'] ) ? $a['tags'] : array(),
		'meta_input'   => array(
			'_bdh_seed' => 1,
			'_bdh_amazon_keyword' => isset( $a['kw'] ) ? $a['kw'] : '',
		),
	), true );

	if ( is_wp_error( $post_id ) ) { return $post_id; }
	return $post_id;
}

/**
 * Delete a seed article.
 */
function bdh_delete_seed_article( $slug ) {
	$existing = get_page_by_path( $slug, OBJECT, 'post' );
	if ( ! $existing ) { return false; }
	// Safety: only delete if it's marked as a seed.
	if ( get_post_meta( $existing->ID, '_bdh_seed', true ) ) {
		wp_delete_post( $existing->ID, true );
		return true;
	}
	return false;
}

/**
 * Admin page.
 */
function bdh_content_library_render() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }

	$notices = array();

	if ( isset( $_POST['bdh_lib_action'] ) && check_admin_referer( 'bdh_lib', 'bdh_lib_nonce' ) ) {
		$action = sanitize_key( $_POST['bdh_lib_action'] );
		$slug   = isset( $_POST['slug'] ) ? sanitize_title( $_POST['slug'] ) : '';

		if ( $action === 'install' && $slug ) {
			$res = bdh_install_seed_article( $slug );
			$notices[] = is_wp_error( $res ) ? 'Failed: ' . $res->get_error_message() : 'Installed: ' . $slug;
		} elseif ( $action === 'delete' && $slug ) {
			$ok = bdh_delete_seed_article( $slug );
			$notices[] = $ok ? 'Deleted: ' . $slug : 'Delete failed (not a seed?) : ' . $slug;
		} elseif ( $action === 'reinstall' && $slug ) {
			bdh_delete_seed_article( $slug );
			$res = bdh_install_seed_article( $slug );
			$notices[] = is_wp_error( $res ) ? 'Reinstall failed' : 'Reinstalled: ' . $slug;
		} elseif ( $action === 'install_all' ) {
			$count = 0;
			foreach ( array_keys( bdh_seed_articles() ) as $s ) {
				$res = bdh_install_seed_article( $s );
				if ( ! is_wp_error( $res ) && $res ) { $count++; }
			}
			$notices[] = $count . ' articles processed (existing ones skipped).';
		} elseif ( $action === 'delete_all' ) {
			$count = 0;
			foreach ( array_keys( bdh_seed_articles() ) as $s ) {
				if ( bdh_delete_seed_article( $s ) ) { $count++; }
			}
			$notices[] = $count . ' seed articles deleted.';
		} elseif ( $action === 'reinstall_all' ) {
			$count = 0;
			foreach ( array_keys( bdh_seed_articles() ) as $s ) {
				bdh_delete_seed_article( $s );
				$res = bdh_install_seed_article( $s );
				if ( ! is_wp_error( $res ) && $res ) { $count++; }
			}
			$notices[] = $count . ' articles reinstalled fresh.';
		} elseif ( $action === 'ensure_cats' ) {
			$count = 0;
			foreach ( array_keys( bdh_seed_categories() ) as $slug ) {
				if ( bdh_ensure_category( $slug ) ) { $count++; }
			}
			$notices[] = $count . ' categories verified.';
		}
	}

	$articles = bdh_seed_articles();
	$status   = bdh_seed_status();
	$installed_count = count( array_filter( $status ) );
	$total = count( $articles );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Benders Hub — Content Library', 'benders-hub' ); ?></h1>
		<p><?php esc_html_e( '12 curated fabrication articles. Install individually or in bulk. Each article includes excerpt, body content, category, tags, and an Amazon keyword.', 'benders-hub' ); ?></p>

		<?php foreach ( $notices as $n ) : ?>
			<div class="notice notice-success"><p><?php echo esc_html( $n ); ?></p></div>
		<?php endforeach; ?>

		<div style="background:#fff;border:1px solid #c3c4c7;padding:16px;margin:16px 0;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
			<div>
				<strong style="font-size:1.2rem;"><?php echo (int) $installed_count; ?> / <?php echo (int) $total; ?></strong>
				<span style="color:#646970;"><?php esc_html_e( 'articles currently installed', 'benders-hub' ); ?></span>
			</div>
			<form method="post" style="display:flex;gap:8px;flex-wrap:wrap;">
				<?php wp_nonce_field( 'bdh_lib', 'bdh_lib_nonce' ); ?>
				<button class="button button-primary" name="bdh_lib_action" value="install_all"><?php esc_html_e( 'Install All', 'benders-hub' ); ?></button>
				<button class="button" name="bdh_lib_action" value="reinstall_all" onclick="return confirm('Reinstall ALL seed articles? Existing seed posts will be deleted and replaced.');"><?php esc_html_e( 'Reinstall All (overwrites)', 'benders-hub' ); ?></button>
				<button class="button" name="bdh_lib_action" value="delete_all" onclick="return confirm('Delete ALL seed articles?');"><?php esc_html_e( 'Delete All', 'benders-hub' ); ?></button>
				<button class="button" name="bdh_lib_action" value="ensure_cats"><?php esc_html_e( 'Verify Categories', 'benders-hub' ); ?></button>
			</form>
		</div>

		<table class="widefat fixed striped" style="margin-top:20px;">
			<thead>
				<tr>
					<th style="width:32%;"><?php esc_html_e( 'Title', 'benders-hub' ); ?></th>
					<th style="width:14%;"><?php esc_html_e( 'Category', 'benders-hub' ); ?></th>
					<th style="width:18%;"><?php esc_html_e( 'Tags', 'benders-hub' ); ?></th>
					<th style="width:14%;"><?php esc_html_e( 'Amazon Keyword', 'benders-hub' ); ?></th>
					<th style="width:10%;"><?php esc_html_e( 'Status', 'benders-hub' ); ?></th>
					<th style="width:12%;"><?php esc_html_e( 'Actions', 'benders-hub' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $articles as $slug => $a ) :
				$installed = (int) ( $status[ $slug ] ?? 0 );
				$cats = bdh_seed_categories();
				$cat_label = isset( $cats[ $a['cat'] ] ) ? $cats[ $a['cat'] ][0] : $a['cat'];
				?>
				<tr>
					<td>
						<strong><?php echo esc_html( $a['title'] ); ?></strong>
						<?php if ( $installed ) : ?>
							<br><a href="<?php echo esc_url( get_permalink( $installed ) ); ?>" target="_blank"><?php esc_html_e( 'View →', 'benders-hub' ); ?></a> ·
							<a href="<?php echo esc_url( get_edit_post_link( $installed ) ); ?>"><?php esc_html_e( 'Edit', 'benders-hub' ); ?></a>
						<?php endif; ?>
						<br><small style="color:#646970;"><?php echo esc_html( wp_trim_words( $a['excerpt'], 24 ) ); ?></small>
					</td>
					<td><?php echo esc_html( $cat_label ); ?></td>
					<td><small><?php echo esc_html( implode( ', ', $a['tags'] ) ); ?></small></td>
					<td><code><?php echo esc_html( $a['kw'] ); ?></code></td>
					<td>
						<?php if ( $installed ) : ?>
							<span style="color:#00a32a;font-weight:600;">● <?php esc_html_e( 'Installed', 'benders-hub' ); ?></span>
						<?php else : ?>
							<span style="color:#999;">○ <?php esc_html_e( 'Not installed', 'benders-hub' ); ?></span>
						<?php endif; ?>
					</td>
					<td>
						<form method="post" style="display:inline;">
							<?php wp_nonce_field( 'bdh_lib', 'bdh_lib_nonce' ); ?>
							<input type="hidden" name="slug" value="<?php echo esc_attr( $slug ); ?>">
							<?php if ( $installed ) : ?>
								<button class="button button-small" name="bdh_lib_action" value="reinstall" onclick="return confirm('Reinstall this article? Existing seed copy is replaced.');"><?php esc_html_e( 'Reinstall', 'benders-hub' ); ?></button>
								<button class="button button-small button-link-delete" name="bdh_lib_action" value="delete" onclick="return confirm('Delete this seed article?');"><?php esc_html_e( 'Delete', 'benders-hub' ); ?></button>
							<?php else : ?>
								<button class="button button-primary button-small" name="bdh_lib_action" value="install"><?php esc_html_e( 'Install', 'benders-hub' ); ?></button>
							<?php endif; ?>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<h2 style="margin-top:32px;"><?php esc_html_e( 'Categories that will be created', 'benders-hub' ); ?></h2>
		<table class="widefat striped">
			<thead><tr><th><?php esc_html_e( 'Slug', 'benders-hub' ); ?></th><th><?php esc_html_e( 'Name', 'benders-hub' ); ?></th><th><?php esc_html_e( 'Description', 'benders-hub' ); ?></th><th><?php esc_html_e( 'Status', 'benders-hub' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( bdh_seed_categories() as $slug => $info ) :
				$term = term_exists( $slug, 'category' );
				?>
				<tr>
					<td><code><?php echo esc_html( $slug ); ?></code></td>
					<td><?php echo esc_html( $info[0] ); ?></td>
					<td><?php echo esc_html( $info[1] ); ?></td>
					<td><?php echo $term ? '<span style="color:#00a32a;">●</span>' : '<span style="color:#999;">○</span>'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}
