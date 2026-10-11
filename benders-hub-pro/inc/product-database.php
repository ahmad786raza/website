<?php
/**
 * Equipment knowledge base.
 *
 * v1 shipped ten fictional products ("Pro Workshop 20-Ton Hydraulic Bender",
 * "Industrial Multi-Stack CNC Bender" …) with invented scores, repeatability
 * figures and cycle times. v2 replaces them with:
 *
 *  1. bdh_default_equipment()  — equipment TYPE profiles (what a class of
 *     machine does and where it falls short), no scores, no invented specs.
 *  2. bdh_equipment_needs()    — editorial "best for" categories for the homepage.
 *  3. bdh_reference_benders()  — real models with specifications copied from
 *     the manufacturers' own pages, each with its source URL and any conflicts.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Equipment type profiles. Used by the Equipment Types explorer and AJAX filter.
 * "capacity" values are typical ranges for the class — always check the specific machine.
 */
function bdh_default_equipment() {
	return array(
		array(
			'id'        => 'lever',
			'name'      => __( 'Lever-type hand tube bender', 'benders-hub' ),
			'type'      => __( 'Manual · small tube', 'benders-hub' ),
			'category'  => 'manual',
			'capacity'  => __( 'Small OD, thin wall (brake, fuel and instrument lines; soft copper)', 'benders-hub' ),
			'tonnage'   => __( 'Hand lever', 'benders-hub' ),
			'workshop'  => __( 'Any bench or vehicle', 'benders-hub' ),
			'beginner'  => 'yes',
			'use_case'  => __( 'Brake and fuel lines, small frames, HVAC and plumbing tube', 'benders-hub' ),
			'tubing'    => __( 'Copper, steel and stainless line within the tool\'s rating', 'benders-hub' ),
			'price'     => __( 'Lowest', 'benders-hub' ),
			'keyword'   => 'lever tube bender',
			'image'     => 'manual-bender',
			'pros'      => array( __( 'Inexpensive and portable', 'benders-hub' ), __( 'Clean, repeatable bends on small tube', 'benders-hub' ), __( 'Angle marks on the form', 'benders-hub' ) ),
			'cons'      => array( __( 'Each tool fits one or a few tube sizes', 'benders-hub' ), __( 'Not for structural tube', 'benders-hub' ) ),
		),
		array(
			'id'        => 'conduit',
			'name'      => __( 'EMT conduit hand bender', 'benders-hub' ),
			'type'      => __( 'Manual · electrical conduit', 'benders-hub' ),
			'category'  => 'manual',
			'capacity'  => __( '1/2"–1" EMT, one shoe per trade size', 'benders-hub' ),
			'tonnage'   => __( 'Hand and foot pressure', 'benders-hub' ),
			'workshop'  => __( 'Job site', 'benders-hub' ),
			'beginner'  => 'yes',
			'use_case'  => __( 'Electrical conduit runs: stubs, offsets, saddles and back-to-backs', 'benders-hub' ),
			'tubing'    => __( 'EMT (some shoes also rated for rigid/IMC)', 'benders-hub' ),
			'price'     => __( 'Lowest', 'benders-hub' ),
			'keyword'   => 'EMT conduit bender',
			'image'     => 'conduit-bender',
			'pros'      => array( __( 'Offset and multiplier marks cast into the shoe', 'benders-hub' ), __( 'Fast on site', 'benders-hub' ) ),
			'cons'      => array( __( 'Made for conduit only', 'benders-hub' ), __( 'Accuracy depends on technique', 'benders-hub' ) ),
		),
		array(
			'id'        => 'rotary_manual',
			'name'      => __( 'Manual rotary-draw bender', 'benders-hub' ),
			'type'      => __( 'Rotary draw · ratchet or lever', 'benders-hub' ),
			'category'  => 'manual',
			'capacity'  => __( 'Typically up to about 2"–2.5" OD; thick-wall and larger sizes often need the hydraulic upgrade', 'benders-hub' ),
			'tonnage'   => __( 'Manual ratchet or long lever', 'benders-hub' ),
			'workshop'  => __( 'Home garage or small shop (needs a solid floor mount)', 'benders-hub' ),
			'beginner'  => 'partial',
			'use_case'  => __( 'Roll cages, chassis, bumpers, sliders, frames', 'benders-hub' ),
			'tubing'    => __( 'Round tube, pipe and square tube with matching dies; mild steel and 4130 within rated wall', 'benders-hub' ),
			'price'     => __( 'Moderate (dies extra per size and radius)', 'benders-hub' ),
			'keyword'   => 'rotary draw tube bender',
			'image'     => 'roll-cage',
			'pros'      => array( __( 'True rotary-draw bends with a consistent CLR', 'benders-hub' ), __( 'Dies available for many OD/CLR combinations', 'benders-hub' ), __( 'Many models accept a hydraulic upgrade later', 'benders-hub' ) ),
			'cons'      => array( __( 'Physically demanding on larger or thicker tube', 'benders-hub' ), __( 'Each tube size and radius needs its own die', 'benders-hub' ) ),
		),
		array(
			'id'        => 'rotary_hydraulic',
			'name'      => __( 'Hydraulic rotary-draw bender', 'benders-hub' ),
			'type'      => __( 'Rotary draw · hydraulic, air-over-hydraulic or electric pump', 'benders-hub' ),
			'category'  => 'hydraulic',
			'capacity'  => __( 'Commonly 2"–2.5" OD including heavier walls; see each manufacturer\'s capacity chart', 'benders-hub' ),
			'tonnage'   => __( 'Hydraulic cylinder (manual, air or electric pump)', 'benders-hub' ),
			'workshop'  => __( 'Serious home shop to professional fab shop', 'benders-hub' ),
			'beginner'  => 'partial',
			'use_case'  => __( 'Cages and chassis in volume, heavy-wall DOM and 4130, off-road armour', 'benders-hub' ),
			'tubing'    => __( 'Round, square and pipe with matching dies', 'benders-hub' ),
			'price'     => __( 'Moderate to high', 'benders-hub' ),
			'keyword'   => 'hydraulic tube bender rotary draw',
			'image'     => 'hydraulic-bender',
			'pros'      => array( __( 'Much less physical effort', 'benders-hub' ), __( 'Handles heavier walls than manual operation', 'benders-hub' ), __( 'Repeatable with degree stops or a degree ring', 'benders-hub' ) ),
			'cons'      => array( __( 'Higher cost', 'benders-hub' ), __( 'Still limited by die and frame ratings', 'benders-hub' ) ),
		),
		array(
			'id'        => 'ram',
			'name'      => __( 'Hydraulic ram ("pipe") bender', 'benders-hub' ),
			'type'      => __( 'Ram / press · bottle-jack style', 'benders-hub' ),
			'category'  => 'hydraulic',
			'capacity'  => __( 'Schedule 40/80 pipe, typically 1/2"–2" nominal', 'benders-hub' ),
			'tonnage'   => __( 'Hand-pumped hydraulic ram', 'benders-hub' ),
			'workshop'  => __( 'Garage, farm, site', 'benders-hub' ),
			'beginner'  => 'yes',
			'use_case'  => __( 'Heavy-wall pipe for gates, railings, brackets and non-structural work', 'benders-hub' ),
			'tubing'    => __( 'Heavy-wall schedule pipe — not thin-wall tube', 'benders-hub' ),
			'price'     => __( 'Low', 'benders-hub' ),
			'keyword'   => 'hydraulic pipe bender 12 ton',
			'image'     => 'industrial-pipe',
			'pros'      => array( __( 'Low cost for heavy pipe', 'benders-hub' ), __( 'No die-per-radius investment', 'benders-hub' ) ),
			'cons'      => array( __( 'Tends to flatten or kink thin-wall tube', 'benders-hub' ), __( 'Inconsistent radius; unsuitable for roll cages or exhaust', 'benders-hub' ) ),
		),
		array(
			'id'        => 'mandrel',
			'name'      => __( 'Mandrel bender', 'benders-hub' ),
			'type'      => __( 'Rotary draw with internal mandrel and wiper die', 'benders-hub' ),
			'category'  => 'mandrel',
			'capacity'  => __( 'Thin-wall tube at tight radii (exhaust and intake bends of roughly 1–1.5× OD are common)', 'benders-hub' ),
			'tonnage'   => __( 'Hydraulic or electric', 'benders-hub' ),
			'workshop'  => __( 'Exhaust shop, performance fabricator', 'benders-hub' ),
			'beginner'  => 'no',
			'use_case'  => __( 'Exhaust systems, headers, turbo and intake piping', 'benders-hub' ),
			'tubing'    => __( 'Mild steel, aluminised steel, stainless, aluminium', 'benders-hub' ),
			'price'     => __( 'High', 'benders-hub' ),
			'keyword'   => 'mandrel tube bender',
			'image'     => 'mandrel',
			'pros'      => array( __( 'Keeps thin-wall tube round through tight bends', 'benders-hub' ), __( 'Smooth bore for flow', 'benders-hub' ) ),
			'cons'      => array( __( 'Tooling for each size is expensive', 'benders-hub' ), __( 'Setup and lubrication are critical', 'benders-hub' ) ),
		),
		array(
			'id'        => 'cnc',
			'name'      => __( 'CNC / electric rotary-draw bender', 'benders-hub' ),
			'type'      => __( 'Programmable production machine', 'benders-hub' ),
			'category'  => 'cnc',
			'capacity'  => __( 'Machine-specific — from small tube to large industrial sizes', 'benders-hub' ),
			'tonnage'   => __( 'Electric servo and/or hydraulic', 'benders-hub' ),
			'workshop'  => __( 'Production shop; dedicated power and floor space', 'benders-hub' ),
			'beginner'  => 'no',
			'use_case'  => __( 'Batch production of multi-bend parts', 'benders-hub' ),
			'tubing'    => __( 'Depends on tooling', 'benders-hub' ),
			'price'     => __( 'Capital equipment', 'benders-hub' ),
			'keyword'   => 'CNC tube bender',
			'image'     => 'cnc-bender',
			'pros'      => array( __( 'Programmable angles and rotation between bends', 'benders-hub' ), __( 'Springback compensation stored per material', 'benders-hub' ) ),
			'cons'      => array( __( 'High investment and training', 'benders-hub' ), __( 'Only pays off with volume', 'benders-hub' ) ),
		),
		array(
			'id'        => 'roller',
			'name'      => __( 'Ring / section roller', 'benders-hub' ),
			'type'      => __( 'Three-roll bending', 'benders-hub' ),
			'category'  => 'roller',
			'capacity'  => __( 'Large-radius curves, arches and full rings', 'benders-hub' ),
			'tonnage'   => __( 'Hand crank or motorised', 'benders-hub' ),
			'workshop'  => __( 'Fab shop, architectural metalwork', 'benders-hub' ),
			'beginner'  => 'partial',
			'use_case'  => __( 'Hoops, arches, handrail curves, rings', 'benders-hub' ),
			'tubing'    => __( 'Tube, pipe, flat bar, angle (with suitable rolls)', 'benders-hub' ),
			'price'     => __( 'Moderate', 'benders-hub' ),
			'keyword'   => 'tube roller bender',
			'image'     => 'tube-stock',
			'pros'      => array( __( 'Any large radius from one set of rolls', 'benders-hub' ) ),
			'cons'      => array( __( 'Cannot make tight bends', 'benders-hub' ), __( 'Several passes and trial-and-error to hit a radius', 'benders-hub' ) ),
		),
		array(
			'id'        => 'notcher',
			'name'      => __( 'Tube notcher', 'benders-hub' ),
			'type'      => __( 'Joint preparation (not a bender)', 'benders-hub' ),
			'category'  => 'notcher',
			'capacity'  => __( 'Hole-saw notchers sized to the receiving tube OD', 'benders-hub' ),
			'tonnage'   => __( 'Drill or drill-press driven; belt and mill-style notchers also exist', 'benders-hub' ),
			'workshop'  => __( 'Any fab shop', 'benders-hub' ),
			'beginner'  => 'yes',
			'use_case'  => __( 'Fish-mouth (coped) joints for cages and chassis', 'benders-hub' ),
			'tubing'    => __( 'Round tube', 'benders-hub' ),
			'price'     => __( 'Low to moderate', 'benders-hub' ),
			'keyword'   => 'tube notcher',
			'image'     => 'chassis',
			'pros'      => array( __( 'Tight fit-up for strong welds', 'benders-hub' ), __( 'Adjustable joint angle', 'benders-hub' ) ),
			'cons'      => array( __( 'Hole saws wear quickly in 4130 and stainless', 'benders-hub' ) ),
		),
	);
}

/**
 * Equipment categories used in the explorer UI.
 */
function bdh_equipment_categories() {
	return array(
		'all'       => __( 'All types', 'benders-hub' ),
		'manual'    => __( 'Manual', 'benders-hub' ),
		'hydraulic' => __( 'Hydraulic', 'benders-hub' ),
		'mandrel'   => __( 'Mandrel', 'benders-hub' ),
		'cnc'       => __( 'CNC', 'benders-hub' ),
		'roller'    => __( 'Roller', 'benders-hub' ),
		'notcher'   => __( 'Notcher', 'benders-hub' ),
	);
}

/**
 * Homepage Section E — "best for" editorial categories (types of equipment, not product rankings).
 */
function bdh_equipment_needs() {
	return array(
		array(
			'id'      => 'beginner',
			'icon'    => 'wrench',
			'label'   => __( 'Best for beginners & small tube', 'benders-hub' ),
			'pick'    => __( 'Lever-type hand bender', 'benders-hub' ),
			'why'     => __( 'Brake lines, fuel lines and small frames need clean bends in small, thin tube — not a big machine.', 'benders-hub' ),
			'look'    => array( __( 'Forms for the exact tube ODs you use', 'benders-hub' ), __( 'Readable degree marks', 'benders-hub' ), __( 'Rated for the material (copper, steel or stainless line)', 'benders-hub' ) ),
			'caution' => __( 'Not suitable for structural tube.', 'benders-hub' ),
			'keyword' => 'lever tube bender',
			'image'   => 'manual-bender',
			'posts'   => array( 'diy-garage-workshop', 'bend-radius-explained' ),
		),
		array(
			'id'      => 'small-shop',
			'icon'    => 'workshop',
			'label'   => __( 'Best for a small home workshop', 'benders-hub' ),
			'pick'    => __( 'Manual rotary-draw bender', 'benders-hub' ),
			'why'     => __( 'One machine covers bumpers, sliders, frames and cages, and many models can take a hydraulic upgrade later.', 'benders-hub' ),
			'look'    => array( __( 'Published capacity chart (OD × wall × material)', 'benders-hub' ), __( 'Die availability in the OD/CLR you need', 'benders-hub' ), __( 'Degree ring or stops for repeatability', 'benders-hub' ) ),
			'caution' => __( 'Budget for dies — each size and radius is a separate set.', 'benders-hub' ),
			'keyword' => 'rotary draw tube bender',
			'image'   => 'workshop',
			'posts'   => array( 'best-hydraulic-tube-bender', 'diy-garage-workshop' ),
		),
		array(
			'id'      => 'exhaust',
			'icon'    => 'flame',
			'label'   => __( 'Best for exhaust fabrication', 'benders-hub' ),
			'pick'    => __( 'Pre-bent mandrel U/J-bends (or a mandrel bender for a shop)', 'benders-hub' ),
			'why'     => __( 'Thin-wall exhaust tube needs mandrel support to stay round at tight radii. Most home builders cut and weld pre-made mandrel bends.', 'benders-hub' ),
			'look'    => array( __( 'Bends in your exact OD, wall and material', 'benders-hub' ), __( 'Stated CLR so pieces line up', 'benders-hub' ), __( 'Matching clamps, V-bands and hangers', 'benders-hub' ) ),
			'caution' => __( 'Ram ("crush") benders reduce the bore through the bend.', 'benders-hub' ),
			'keyword' => 'mandrel bend exhaust tubing',
			'image'   => 'exhaust',
			'posts'   => array( 'custom-exhaust-fabrication', 'mandrel-vs-rotary' ),
		),
		array(
			'id'      => 'rollcage',
			'icon'    => 'shield',
			'label'   => __( 'Best for roll cage & chassis', 'benders-hub' ),
			'pick'    => __( 'Rotary-draw bender + tube notcher', 'benders-hub' ),
			'why'     => __( 'Cage work needs consistent radii and tight joint fit-up. Your sanctioning body\'s rules set tube size, material and minimum bend radius.', 'benders-hub' ),
			'look'    => array( __( 'Die for your rulebook tube size (often 1.5"–1.75" OD)', 'benders-hub' ), __( 'CLR that meets the minimum in your rules', 'benders-hub' ), __( 'Capacity for the wall thickness you must run', 'benders-hub' ) ),
			'caution' => __( 'Safety-critical: confirm requirements in the current rulebook.', 'benders-hub' ),
			'keyword' => 'roll cage tube bender',
			'image'   => 'roll-cage',
			'posts'   => array( 'roll-cage-specifications', 'chromoly-fabrication', 'tube-notching-guide' ),
		),
		array(
			'id'      => 'pro',
			'icon'    => 'factory',
			'label'   => __( 'Best for professional & production work', 'benders-hub' ),
			'pick'    => __( 'Hydraulic rotary-draw, or CNC for volume', 'benders-hub' ),
			'why'     => __( 'When bends are daily work, hydraulic power and repeatable stops save time; CNC only pays off with batch volume.', 'benders-hub' ),
			'look'    => array( __( 'Duty rating and power supply you can provide', 'benders-hub' ), __( 'Tooling lead times and support', 'benders-hub' ), __( 'Springback compensation (angle stops or controller)', 'benders-hub' ) ),
			'caution' => __( 'CNC machines are capital purchases — get a demonstration on your parts.', 'benders-hub' ),
			'keyword' => 'hydraulic tube bender',
			'image'   => 'hydraulic-bender',
			'posts'   => array( 'cnc-tube-bending', 'best-hydraulic-tube-bender' ),
		),
		array(
			'id'      => 'budget-pipe',
			'icon'    => 'gear',
			'label'   => __( 'Budget option for heavy pipe', 'benders-hub' ),
			'pick'    => __( 'Hydraulic ram pipe bender', 'benders-hub' ),
			'why'     => __( 'For schedule 40/80 pipe in gates, railings and brackets where appearance and exact radius matter less.', 'benders-hub' ),
			'look'    => array( __( 'Shoes for your nominal pipe sizes', 'benders-hub' ), __( 'Stated schedule rating', 'benders-hub' ), __( 'Stable frame for the ram force', 'benders-hub' ) ),
			'caution' => __( 'Not for thin-wall tube, exhaust or roll cages.', 'benders-hub' ),
			'keyword' => 'hydraulic pipe bender',
			'image'   => 'industrial-pipe',
			'posts'   => array( 'conduit-bending-electricians', 'best-hydraulic-tube-bender' ),
		),
	);
}

/**
 * Real benders with specifications as published by their manufacturers.
 * Research date: October 2026. Where a manufacturer's own pages disagree the
 * conflict is shown instead of picking a number. Re-verify before relying on it.
 * 'amazon' may be filled with a direct product URL once availability is confirmed.
 */
function bdh_reference_benders() {
	return array(
		array(
			'model'  => 'JD Squared (JD2) Model 32',
			'drive'  => __( 'Manual ratchet; optional hydraulic adapter (10-ton cylinder recommended)', 'benders-hub' ),
			'od'     => '1/2"–2-1/2" OD round',
			'wall'   => __( 'Manual: over 2" × .120" round needs hydraulics. Hydraulic: rated to 2" × .250" DOM.', 'benders-hub' ),
			'angle'  => __( 'Not stated on the pages reviewed (180° die sets sold)', 'benders-hub' ),
			'source' => 'https://www.jd2.com/model-32-bender',
			'source2'=> 'https://support.jd2.com/MiscDown/dl/M32BDirections.pdf',
			'amazon' => '',
		),
		array(
			'model'  => 'Pro-Tools 105HD',
			'drive'  => __( 'Manual ratchet; hydraulic package available', 'benders-hub' ),
			'od'     => '1/2"–2" OD round; NPS 1/4–1-1/2 pipe',
			'wall'   => __( 'Round tube to .134" wall; 2" × .134" 4130 with the extension handle', 'benders-hub' ),
			'angle'  => '180°',
			'source' => 'https://pro-tools.com/products/manual-tube-and-pipe-bender-105-heavy-duty-package',
			'source2'=> '',
			'amazon' => '',
		),
		array(
			'model'  => 'Rogue Fabrication M605',
			'drive'  => __( 'Vertical rotary draw; manual hydraulic, air-over-hydraulic or electric', 'benders-hub' ),
			'od'     => '2.00" OD',
			'wall'   => __( 'Manufacturer pages disagree: .188" (catalogue) vs .250" DOM (FAQ) — confirm with Rogue Fab', 'benders-hub' ),
			'angle'  => __( 'About 94° per stroke; continues past 180°', 'benders-hub' ),
			'source' => 'https://www.roguefab.com/product/metal-tube-bender-m605-1-die/',
			'source2'=> 'https://www.roguefab.com/faqs/',
			'amazon' => '',
		),
		array(
			'model'  => 'Rogue Fabrication M625',
			'drive'  => __( 'As M605 (same dies), heavier frame', 'benders-hub' ),
			'od'     => '2.00" OD',
			'wall'   => __( '2.00" × .250" DOM', 'benders-hub' ),
			'angle'  => __( 'As M605', 'benders-hub' ),
			'source' => 'https://www.roguefab.com/product/metal-tube-bender-m625-1-die/',
			'source2'=> 'https://www.roguefab.com/tube-capacity-chart/',
			'amazon' => '',
		),
		array(
			'model'  => 'Baileigh RDB-050',
			'drive'  => __( 'Manual, 3-speed ratchet; anti-springback mechanism', 'benders-hub' ),
			'od'     => '3/4"–2.5" OD mild steel',
			'wall'   => __( '2" OD × .120" 4130', 'benders-hub' ),
			'angle'  => __( 'Manufacturer page states both 190° and 200°', 'benders-hub' ),
			'source' => 'https://www.baileigh.com/manual-tube-bender-rdb-050',
			'source2'=> '',
			'amazon' => '',
		),
		array(
			'model'  => 'Central Machinery 12-ton hydraulic pipe bender (Harbor Freight #32888)',
			'drive'  => __( 'Bottle-jack hydraulic ram (press bender)', 'benders-hub' ),
			'od'     => '1/2"–2" Schedule 40/80 pipe',
			'wall'   => __( 'Manual says it is not intended for thin-wall pipe such as conduit or exhaust', 'benders-hub' ),
			'angle'  => __( 'Up to 90°', 'benders-hub' ),
			'source' => 'https://www.harborfreight.com/12-ton-hydraulic-pipe-bender-32888.html',
			'source2'=> 'https://manuals.harborfreight.com/manuals/32000-32999/32888-193175527103.pdf',
			'amazon' => '',
		),
	);
}
