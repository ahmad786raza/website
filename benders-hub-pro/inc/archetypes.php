<?php
/**
 * Fabrication pathways ("archetypes") — the reader profiles used by the
 * "Learn by project" section, the 404 page and the quiz presets.
 *
 * v1's twelve archetypes carried precision promises ("CNC Grade ±0.1°") and a
 * roll-cage radius of "5×–6× OD" that contradicts common practice. The
 * profiles below describe typical work and point to guides and tools.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_archetypes() {
	return array(
		'diy_garage' => array(
			'id'        => 'diy_garage',
			'name'      => __( 'DIY garage builder', 'benders-hub' ),
			'tagline'   => __( 'Project cars, off-road armour, brackets and frames in a home garage.', 'benders-hub' ),
			'icon'      => 'wrench',
			'typical'   => __( 'Mild steel / DOM, roughly 3/4"–1.75" OD', 'benders-hub' ),
			'equipment' => __( 'Manual rotary-draw bender, notcher, MIG welder', 'benders-hub' ),
			'posts'     => array( 'diy-garage-workshop', 'bend-radius-explained', 'tube-notching-guide' ),
			'profile'   => 'garage',
			'keyword'   => 'rotary draw tube bender',
			'cta'       => __( 'Find my garage bender', 'benders-hub' ),
		),
		'exhaust' => array(
			'id'        => 'exhaust',
			'name'      => __( 'Exhaust fabricator', 'benders-hub' ),
			'tagline'   => __( 'Cat-backs, downpipes, headers and intercooler piping.', 'benders-hub' ),
			'icon'      => 'flame',
			'typical'   => __( 'Thin-wall mild steel, aluminised or 304 stainless, 1.5"–3.5" OD', 'benders-hub' ),
			'equipment' => __( 'Mandrel bends or a mandrel bender, cut-off saw, TIG welder', 'benders-hub' ),
			'posts'     => array( 'custom-exhaust-fabrication', 'mandrel-vs-rotary', 'stainless-bending' ),
			'profile'   => 'exhaust',
			'keyword'   => 'mandrel bend exhaust tubing',
			'cta'       => __( 'Plan an exhaust build', 'benders-hub' ),
		),
		'rollcage' => array(
			'id'        => 'rollcage',
			'name'      => __( 'Roll cage builder', 'benders-hub' ),
			'tagline'   => __( 'Cages and bars built to a sanctioning body\'s rules.', 'benders-hub' ),
			'icon'      => 'shield',
			'typical'   => __( 'DOM or 4130 to rulebook size — often 1.5"–1.75" OD', 'benders-hub' ),
			'equipment' => __( 'Rotary-draw bender with rule-compliant CLR die, notcher, TIG/MIG per rules', 'benders-hub' ),
			'posts'     => array( 'roll-cage-specifications', 'chromoly-fabrication', 'tube-notching-guide' ),
			'profile'   => 'rollcage',
			'keyword'   => 'roll cage tube bender',
			'cta'       => __( 'Choose a cage bender', 'benders-hub' ),
		),
		'motorsport' => array(
			'id'        => 'motorsport',
			'name'      => __( 'Motorsport fabricator', 'benders-hub' ),
			'tagline'   => __( 'Chassis, suspension links, turbo and fluid plumbing for race cars.', 'benders-hub' ),
			'icon'      => 'car',
			'typical'   => __( '4130, stainless and aluminium in many sizes', 'benders-hub' ),
			'equipment' => __( 'Hydraulic rotary-draw bender, mandrel bends, notcher, TIG welder', 'benders-hub' ),
			'posts'     => array( 'chromoly-fabrication', 'stainless-bending', 'tube-wrinkling-prevention' ),
			'profile'   => 'motorsport',
			'keyword'   => 'hydraulic tube bender',
			'cta'       => __( 'Spec a race-shop bender', 'benders-hub' ),
		),
		'hydraulic_pro' => array(
			'id'        => 'hydraulic_pro',
			'name'      => __( 'Hydraulic & pipe bending operator', 'benders-hub' ),
			'tagline'   => __( 'Handrail, gates, structural pipe and daily shop production.', 'benders-hub' ),
			'icon'      => 'gear',
			'typical'   => __( 'Schedule 40/80 pipe and heavy-wall tube', 'benders-hub' ),
			'equipment' => __( 'Hydraulic rotary-draw bender or ram pipe bender, section roller for large radii', 'benders-hub' ),
			'posts'     => array( 'best-hydraulic-tube-bender', 'stainless-bending' ),
			'profile'   => 'pipe',
			'keyword'   => 'hydraulic pipe bender',
			'cta'       => __( 'Compare hydraulic options', 'benders-hub' ),
		),
		'cnc_operator' => array(
			'id'        => 'cnc_operator',
			'name'      => __( 'CNC production shop', 'benders-hub' ),
			'tagline'   => __( 'Repeat parts in batches, programmed bend sequences.', 'benders-hub' ),
			'icon'      => 'factory',
			'typical'   => __( 'Specified by the part drawing', 'benders-hub' ),
			'equipment' => __( 'CNC / electric rotary-draw bender, tube measuring, bending software', 'benders-hub' ),
			'posts'     => array( 'cnc-tube-bending', 'mandrel-vs-rotary' ),
			'profile'   => 'production',
			'keyword'   => 'CNC tube bender',
			'cta'       => __( 'Is CNC worth it?', 'benders-hub' ),
		),
		'industrial' => array(
			'id'        => 'industrial',
			'name'      => __( 'Industrial pipe fabricator', 'benders-hub' ),
			'tagline'   => __( 'Process piping, spools and site work under a governing code.', 'benders-hub' ),
			'icon'      => 'workshop',
			'typical'   => __( 'Carbon and stainless pipe by NPS and schedule', 'benders-hub' ),
			'equipment' => __( 'Industrial pipe benders, induction or roll bending, code-qualified welding', 'benders-hub' ),
			'posts'     => array( 'conduit-bending-electricians', 'stainless-bending', 'best-hydraulic-tube-bender' ),
			'profile'   => 'pipe',
			'keyword'   => 'pipe bender',
			'cta'       => __( 'Pipe bending basics', 'benders-hub' ),
		),
	);
}
