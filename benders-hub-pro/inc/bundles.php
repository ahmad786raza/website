<?php
/**
 * Kit Bundles (8).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_bundles() {
	return array(
		'beginner' => array(
			'id'      => 'beginner',
			'name'    => __( 'Beginner Tube Bending Kit', 'benders-hub' ),
			'icon'    => 'tube',
			'archetype' => 'beginner_tube',
			'summary' => __( 'Everything to safely start bending tube under 1.5" at home.', 'benders-hub' ),
			'badge'   => 'entry',
			'items'   => array(
				array( 'label' => __( 'Manual hydraulic 12-ton bender', 'benders-hub' ),    'keyword' => 'manual hydraulic tube bender 12 ton' ),
				array( 'label' => __( '3-die starter set (1/2", 3/4", 1")', 'benders-hub' ), 'keyword' => 'tube bender die set' ),
				array( 'label' => __( 'Tube cutter rotary', 'benders-hub' ),                'keyword' => 'rotary tube cutter' ),
				array( 'label' => __( 'Tubing deburring tool', 'benders-hub' ),             'keyword' => 'tubing deburring tool' ),
				array( 'label' => __( 'Anti-fatigue shop mat', 'benders-hub' ),             'keyword' => 'anti-fatigue shop mat' ),
				array( 'label' => __( 'Welding gloves + safety glasses', 'benders-hub' ),   'keyword' => 'welding gloves safety glasses' ),
			),
		),
		'exhaust' => array(
			'id'      => 'exhaust',
			'name'    => __( 'Exhaust Fabrication Bundle', 'benders-hub' ),
			'icon'    => 'flame',
			'archetype' => 'exhaust',
			'summary' => __( 'Build mandrel-quality exhausts and headers.', 'benders-hub' ),
			'badge'   => 'pro',
			'items'   => array(
				array( 'label' => __( '2.5" mandrel bender', 'benders-hub' ),                'keyword' => '2.5 inch mandrel tube bender' ),
				array( 'label' => __( 'T304 stainless tubing kit', 'benders-hub' ),          'keyword' => 'T304 stainless tubing' ),
				array( 'label' => __( 'Pipe notcher hole saw set', 'benders-hub' ),          'keyword' => 'pipe notcher hole saw' ),
				array( 'label' => __( 'TIG welding consumables', 'benders-hub' ),            'keyword' => 'TIG welding kit stainless' ),
				array( 'label' => __( 'Exhaust hanger kit', 'benders-hub' ),                 'keyword' => 'exhaust hanger kit' ),
				array( 'label' => __( 'Heat-resistant gloves', 'benders-hub' ),              'keyword' => 'heat resistant welding gloves' ),
			),
		),
		'rollcage' => array(
			'id'      => 'rollcage',
			'name'    => __( 'Roll Cage Workshop Setup', 'benders-hub' ),
			'icon'    => 'shield',
			'archetype' => 'rollcage',
			'summary' => __( 'Chassis-grade cage fabrication for race builds.', 'benders-hub' ),
			'badge'   => 'pro',
			'items'   => array(
				array( 'label' => __( '1.75" rotary draw bender', 'benders-hub' ),           'keyword' => '1.75 rotary draw tube bender' ),
				array( 'label' => __( 'Chromoly 4130 tube stock', 'benders-hub' ),           'keyword' => 'chromoly 4130 tubing' ),
				array( 'label' => __( 'Pipe fishmouth notcher', 'benders-hub' ),             'keyword' => 'tube notcher fishmouth' ),
				array( 'label' => __( 'Cage building jig set', 'benders-hub' ),              'keyword' => 'roll cage fabrication jig' ),
				array( 'label' => __( 'Chassis table sheet', 'benders-hub' ),                'keyword' => 'chassis fabrication table' ),
				array( 'label' => __( 'TIG welder 200A', 'benders-hub' ),                    'keyword' => 'TIG welder 200 amp' ),
			),
		),
		'hydraulic' => array(
			'id'      => 'hydraulic',
			'name'    => __( 'Hydraulic Bender Package', 'benders-hub' ),
			'icon'    => 'gear',
			'archetype' => 'hydraulic_pro',
			'summary' => __( 'Production-ready hydraulic bending for pipe & conduit.', 'benders-hub' ),
			'badge'   => 'pro',
			'items'   => array(
				array( 'label' => __( '50-ton vertical hydraulic bender', 'benders-hub' ),    'keyword' => '50 ton hydraulic pipe bender' ),
				array( 'label' => __( 'Schedule 40 die set', 'benders-hub' ),                'keyword' => 'schedule 40 pipe bender dies' ),
				array( 'label' => __( 'Hydraulic pump kit', 'benders-hub' ),                 'keyword' => 'hydraulic shop pump' ),
				array( 'label' => __( 'Pipe stand jacks', 'benders-hub' ),                   'keyword' => 'pipe stand jack' ),
				array( 'label' => __( 'Digital angle finder', 'benders-hub' ),               'keyword' => 'digital angle finder bender' ),
			),
		),
		'cnc' => array(
			'id'      => 'cnc',
			'name'    => __( 'CNC Bending Workflow', 'benders-hub' ),
			'icon'    => 'gear',
			'archetype' => 'cnc_operator',
			'summary' => __( 'Programmable, repeatable, production-grade bending.', 'benders-hub' ),
			'badge'   => 'cnc',
			'items'   => array(
				array( 'label' => __( 'Compact CNC tube bender', 'benders-hub' ),            'keyword' => 'compact CNC tube bender' ),
				array( 'label' => __( 'Multi-radius stack dies', 'benders-hub' ),            'keyword' => 'CNC bender stack dies' ),
				array( 'label' => __( 'CAD/CAM tubing software', 'benders-hub' ),            'keyword' => 'CAD CAM tube bending software' ),
				array( 'label' => __( 'Calipers + bore gauges', 'benders-hub' ),             'keyword' => 'digital bore gauge calipers' ),
				array( 'label' => __( '3-phase converter', 'benders-hub' ),                  'keyword' => 'rotary phase converter' ),
			),
		),
		'garage' => array(
			'id'      => 'garage',
			'name'    => __( 'Compact Garage Fabrication Kit', 'benders-hub' ),
			'icon'    => 'wrench',
			'archetype' => 'diy_garage',
			'summary' => __( 'Maximum capability in a single-bay garage footprint.', 'benders-hub' ),
			'badge'   => 'pick',
			'items'   => array(
				array( 'label' => __( '20-ton bench hydraulic bender', 'benders-hub' ),       'keyword' => '20 ton bench hydraulic tube bender' ),
				array( 'label' => __( 'Multi-die hobby set', 'benders-hub' ),                 'keyword' => 'tube bender die multi pack' ),
				array( 'label' => __( 'Folding garage workbench', 'benders-hub' ),            'keyword' => 'folding garage workbench' ),
				array( 'label' => __( 'Tubing notcher portable', 'benders-hub' ),             'keyword' => 'portable tube notcher' ),
				array( 'label' => __( 'MIG welder 140A 110V', 'benders-hub' ),                'keyword' => 'MIG welder 140 amp 110v' ),
				array( 'label' => __( 'Shop vac + magnet sweep', 'benders-hub' ),             'keyword' => 'shop vacuum magnetic sweeper' ),
			),
		),
		'motorsport' => array(
			'id'      => 'motorsport',
			'name'    => __( 'Motorsport Fabrication Bundle', 'benders-hub' ),
			'icon'    => 'flame',
			'archetype' => 'motorsport',
			'summary' => __( 'Race-spec mandrel bending for chassis, turbos, intercoolers.', 'benders-hub' ),
			'badge'   => 'pro',
			'items'   => array(
				array( 'label' => __( '3" mandrel tube bender', 'benders-hub' ),              'keyword' => '3 inch mandrel tube bender' ),
				array( 'label' => __( 'Charge piping kit', 'benders-hub' ),                   'keyword' => 'aluminum charge piping kit' ),
				array( 'label' => __( 'V-band flange set', 'benders-hub' ),                   'keyword' => 'V-band exhaust clamp flange' ),
				array( 'label' => __( 'TIG torch + foot pedal', 'benders-hub' ),              'keyword' => 'TIG torch foot pedal' ),
				array( 'label' => __( 'Tubing bend tape', 'benders-hub' ),                    'keyword' => 'tubing bend layout tape' ),
			),
		),
		'industrial' => array(
			'id'      => 'industrial',
			'name'    => __( 'Professional Industrial Setup', 'benders-hub' ),
			'icon'    => 'workshop',
			'archetype' => 'industrial',
			'summary' => __( 'Heavy-gauge, high-throughput shop floor.', 'benders-hub' ),
			'badge'   => 'industrial',
			'items'   => array(
				array( 'label' => __( '100-ton hydraulic shop bender', 'benders-hub' ),       'keyword' => '100 ton hydraulic bender' ),
				array( 'label' => __( 'Industrial CNC bender', 'benders-hub' ),               'keyword' => 'industrial CNC tube bender' ),
				array( 'label' => __( 'Overhead crane 1-ton', 'benders-hub' ),                'keyword' => 'shop overhead crane 1 ton' ),
				array( 'label' => __( 'Tubing rack system', 'benders-hub' ),                  'keyword' => 'industrial tubing storage rack' ),
				array( 'label' => __( 'OSHA-compliant signage kit', 'benders-hub' ),          'keyword' => 'OSHA shop safety signs' ),
			),
		),
	);
}
