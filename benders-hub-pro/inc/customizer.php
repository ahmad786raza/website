<?php
/**
 * Customizer — Appearance → Customize → Benders Hub.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_customize_register( $wp_customize ) {

	/**
	 * Panel: Benders Hub
	 */
	$wp_customize->add_panel( 'bdh_panel', array(
		'title'       => __( 'Benders Hub', 'benders-hub' ),
		'description' => __( 'Premium fabrication theme controls. Edit hero, homepage sections, tools, calculators, kit bundles, archetypes, comparison, FAQ, newsletter, trust block, and footer.', 'benders-hub' ),
		'priority'    => 5,
	) );

	/**
	 * --- HERO ---
	 */
	$wp_customize->add_section( 'bdh_hero', array(
		'title'       => __( 'Cinematic Hero', 'benders-hub' ),
		'panel'       => 'bdh_panel',
		'priority'    => 10,
	) );

	$hero_fields = array(
		'hero_eyebrow'      => array( 'label' => __( 'Eyebrow Tag', 'benders-hub' ),       'type' => 'text',     'default' => __( 'Premium Fabrication Engineering Hub', 'benders-hub' ) ),
		'hero_headline'     => array( 'label' => __( 'Headline', 'benders-hub' ),          'type' => 'textarea', 'default' => __( 'Find the Best Tube Benders & Fabrication Tools for Precision Projects.', 'benders-hub' ) ),
		'hero_sub'          => array( 'label' => __( 'Subheadline', 'benders-hub' ),       'type' => 'textarea', 'default' => __( 'Build professional fabrication setups for exhausts, roll cages, hydraulic shops, CNC bending, and industrial workflows — researched, tested, and reviewed by working fabricators.', 'benders-hub' ) ),
		'hero_cta_primary'  => array( 'label' => __( 'Primary CTA Label', 'benders-hub' ), 'type' => 'text',     'default' => __( 'Find My Tube Bender', 'benders-hub' ) ),
		'hero_cta_primary_url' => array( 'label' => __( 'Primary CTA URL/Anchor', 'benders-hub' ), 'type' => 'text', 'default' => '#bdh-quiz' ),
		'hero_cta_secondary'=> array( 'label' => __( 'Secondary CTA Label', 'benders-hub' ),'type' => 'text',    'default' => __( 'Open Bend Calculator', 'benders-hub' ) ),
		'hero_cta_secondary_url' => array( 'label' => __( 'Secondary CTA URL/Anchor', 'benders-hub' ), 'type' => 'text', 'default' => '#bdh-calculator' ),
		'hero_image'        => array( 'label' => __( 'Hero Background Image URL', 'benders-hub' ), 'type' => 'image',  'default' => '' ),
		'hero_video'        => array( 'label' => __( 'Hero Background Video URL (MP4 — optional)', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
	);
	bdh_register_section_fields( $wp_customize, 'bdh_hero', $hero_fields );

	/**
	 * --- QUICK START PATHS ---
	 */
	$wp_customize->add_section( 'bdh_quickstart', array(
		'title' => __( 'Quick Start Paths', 'benders-hub' ),
		'panel' => 'bdh_panel',
		'priority' => 20,
	) );
	bdh_register_section_fields( $wp_customize, 'bdh_quickstart', array(
		'quickstart_title'   => array( 'label' => __( 'Section Title', 'benders-hub' ),    'type' => 'text', 'default' => __( 'Pick your fabrication path', 'benders-hub' ) ),
		'quickstart_sub'     => array( 'label' => __( 'Section Subtitle', 'benders-hub' ), 'type' => 'text', 'default' => __( 'Six entry points to the right tools, dies, and workflow for your shop.', 'benders-hub' ) ),
	) );

	/**
	 * --- ARCHETYPES ---
	 */
	$wp_customize->add_section( 'bdh_archetypes_section', array(
		'title' => __( 'Fabrication Archetypes', 'benders-hub' ),
		'panel' => 'bdh_panel',
		'priority' => 30,
	) );
	bdh_register_section_fields( $wp_customize, 'bdh_archetypes_section', array(
		'archetypes_title' => array( 'label' => __( 'Title', 'benders-hub' ),    'type' => 'text', 'default' => __( 'Which fabricator are you?', 'benders-hub' ) ),
		'archetypes_sub'   => array( 'label' => __( 'Subtitle', 'benders-hub' ), 'type' => 'text', 'default' => __( 'Pick an archetype — we tune the entire site to match your shop.', 'benders-hub' ) ),
	) );

	/**
	 * --- TOOLS ---
	 */
	$tool_sections = array(
		'bdh_quiz_section'         => array( __( 'Quiz: Tube Bender Match', 'benders-hub' ),         40 ),
		'bdh_calc_section'         => array( __( 'Tool: Bend Radius Calculator', 'benders-hub' ),    50 ),
		'bdh_visualizer_section'   => array( __( 'Tool: Tube Geometry Visualizer', 'benders-hub' ),  60 ),
		'bdh_workflow_section'     => array( __( 'Tool: Workflow Planner', 'benders-hub' ),          70 ),
		'bdh_compat_section'       => array( __( 'Tool: Compatibility Generator', 'benders-hub' ),   80 ),
		'bdh_priority_section'     => array( __( 'Tool: Efficiency Prioritizer', 'benders-hub' ),    90 ),
		'bdh_tracker_section'      => array( __( 'Tool: Project Tracker', 'benders-hub' ),          100 ),
		'bdh_database_section'     => array( __( 'Equipment Database', 'benders-hub' ),             110 ),
		'bdh_compare_section'      => array( __( 'Comparison Tables', 'benders-hub' ),              120 ),
	);
	foreach ( $tool_sections as $sid => $info ) {
		$wp_customize->add_section( $sid, array(
			'title' => $info[0],
			'panel' => 'bdh_panel',
			'priority' => $info[1],
		) );
		$slug = str_replace( array( 'bdh_', '_section' ), '', $sid );
		bdh_register_section_fields( $wp_customize, $sid, array(
			$slug . '_title' => array( 'label' => __( 'Title', 'benders-hub' ),    'type' => 'text', 'default' => $info[0] ),
			$slug . '_sub'   => array( 'label' => __( 'Subtitle', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
		) );
	}

	/**
	 * --- FAQ ---
	 */
	$wp_customize->add_section( 'bdh_faq_section', array(
		'title'    => __( 'FAQ', 'benders-hub' ),
		'panel'    => 'bdh_panel',
		'priority' => 130,
	) );
	bdh_register_section_fields( $wp_customize, 'bdh_faq_section', array(
		'faq_title' => array( 'label' => __( 'Title', 'benders-hub' ),    'type' => 'text',     'default' => __( 'Fabrication FAQ', 'benders-hub' ) ),
		'faq_sub'   => array( 'label' => __( 'Subtitle', 'benders-hub' ), 'type' => 'text',     'default' => __( 'Common bender, tooling, and workflow questions.', 'benders-hub' ) ),
		'faqs_json' => array( 'label' => __( 'FAQ JSON Override', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
	) );

	/**
	 * --- NEWSLETTER ---
	 */
	$wp_customize->add_section( 'bdh_newsletter_section', array(
		'title'    => __( 'Newsletter CTA', 'benders-hub' ),
		'panel'    => 'bdh_panel',
		'priority' => 140,
	) );
	bdh_register_section_fields( $wp_customize, 'bdh_newsletter_section', array(
		'newsletter_title' => array( 'label' => __( 'Title', 'benders-hub' ),    'type' => 'text', 'default' => __( 'Get the Workshop Brief', 'benders-hub' ) ),
		'newsletter_sub'   => array( 'label' => __( 'Subtitle', 'benders-hub' ), 'type' => 'textarea', 'default' => __( 'Bender reviews, fabrication guides, and shop tips every Friday — no spam.', 'benders-hub' ) ),
		'newsletter_cta'   => array( 'label' => __( 'Button Label', 'benders-hub' ), 'type' => 'text', 'default' => __( 'Join 12,000+ Fabricators', 'benders-hub' ) ),
		'newsletter_endpoint' => array( 'label' => __( 'Newsletter POST Endpoint (optional)', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
	) );

	/**
	 * --- TRUST ---
	 */
	$wp_customize->add_section( 'bdh_trust_section', array(
		'title'    => __( 'Editorial Trust Section', 'benders-hub' ),
		'panel'    => 'bdh_panel',
		'priority' => 150,
	) );
	bdh_register_section_fields( $wp_customize, 'bdh_trust_section', array(
		'trust_title' => array( 'label' => __( 'Title', 'benders-hub' ),    'type' => 'text', 'default' => __( 'How we test and review fabrication equipment', 'benders-hub' ) ),
		'trust_sub'   => array( 'label' => __( 'Subtitle', 'benders-hub' ), 'type' => 'textarea', 'default' => __( 'Every bender, die, and accessory is reviewed by working fabricators against documented standards. No paid placements.', 'benders-hub' ) ),
	) );

	/**
	 * --- FOOTER ---
	 */
	$wp_customize->add_section( 'bdh_footer_section', array(
		'title'    => __( 'Footer', 'benders-hub' ),
		'panel'    => 'bdh_panel',
		'priority' => 160,
	) );
	bdh_register_section_fields( $wp_customize, 'bdh_footer_section', array(
		'footer_tagline'   => array( 'label' => __( 'Footer Tagline', 'benders-hub' ), 'type' => 'textarea', 'default' => __( 'Premium fabrication engineering ecosystem. Researched, tested, and reviewed by working fabricators.', 'benders-hub' ) ),
		'show_top_disclosure' => array( 'label' => __( 'Show top affiliate disclosure bar', 'benders-hub' ), 'type' => 'checkbox', 'default' => 1 ),
	) );

	/**
	 * --- HOMEPAGE SECTIONS (order + visibility) ---
	 */
	$wp_customize->add_section( 'bdh_homepage_layout', array(
		'title'       => __( 'Homepage: Order & Visibility', 'benders-hub' ),
		'panel'       => 'bdh_panel',
		'priority'    => 170,
		'description' => __( 'Toggle homepage sections and set display order.', 'benders-hub' ),
	) );

	$defaults = bdh_default_homepage_sections();
	foreach ( $defaults as $key => $def ) {
		$wp_customize->add_setting( 'bdh_homepage_sections[' . $key . '][enabled]', array(
			'default'           => $def['enabled'],
			'sanitize_callback' => 'absint',
			'type'              => 'theme_mod',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'bdh_homepage_sections_' . $key . '_enabled', array(
			'label'    => sprintf( __( 'Show: %s', 'benders-hub' ), $def['label'] ),
			'section'  => 'bdh_homepage_layout',
			'settings' => 'bdh_homepage_sections[' . $key . '][enabled]',
			'type'     => 'checkbox',
		) );

		$wp_customize->add_setting( 'bdh_homepage_sections[' . $key . '][order]', array(
			'default'           => $def['order'],
			'sanitize_callback' => 'absint',
			'type'              => 'theme_mod',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'bdh_homepage_sections_' . $key . '_order', array(
			'label'    => sprintf( __( 'Order: %s', 'benders-hub' ), $def['label'] ),
			'section'  => 'bdh_homepage_layout',
			'settings' => 'bdh_homepage_sections[' . $key . '][order]',
			'type'     => 'number',
		) );
	}

	/**
	 * --- AMAZON KEYWORDS ---
	 */
	$wp_customize->add_section( 'bdh_amazon_section', array(
		'title'    => __( 'Amazon Keyword Bank', 'benders-hub' ),
		'panel'    => 'bdh_panel',
		'priority' => 180,
	) );
	bdh_register_section_fields( $wp_customize, 'bdh_amazon_section', array(
		'amazon_keyword_bank' => array(
			'label'   => __( 'Custom Amazon Keywords (one per line)', 'benders-hub' ),
			'type'    => 'textarea',
			'default' => '',
		),
	) );
}
add_action( 'customize_register', 'bdh_customize_register' );

/**
 * Helper to register an array of settings+controls under one section.
 */
function bdh_register_section_fields( $wp_customize, $section_id, $fields ) {
	foreach ( $fields as $key => $field ) {
		$setting_id = 'bdh_' . $key;
		$type       = isset( $field['type'] ) ? $field['type'] : 'text';
		$default    = isset( $field['default'] ) ? $field['default'] : '';
		$sanitize   = 'sanitize_text_field';
		if ( $type === 'textarea' ) { $sanitize = 'sanitize_textarea_field'; }
		if ( $type === 'checkbox' ) { $sanitize = 'bdh_sanitize_checkbox'; }
		if ( $type === 'image' )    { $sanitize = 'esc_url_raw'; }

		$wp_customize->add_setting( $setting_id, array(
			'default'           => $default,
			'sanitize_callback' => $sanitize,
			'transport'         => 'refresh',
			'type'              => 'theme_mod',
		) );

		if ( $type === 'image' ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting_id, array(
				'label'    => $field['label'],
				'section'  => $section_id,
				'settings' => $setting_id,
			) ) );
		} elseif ( $type === 'textarea' ) {
			$wp_customize->add_control( $setting_id, array(
				'label'    => $field['label'],
				'section'  => $section_id,
				'settings' => $setting_id,
				'type'     => 'textarea',
			) );
		} elseif ( $type === 'checkbox' ) {
			$wp_customize->add_control( $setting_id, array(
				'label'    => $field['label'],
				'section'  => $section_id,
				'settings' => $setting_id,
				'type'     => 'checkbox',
			) );
		} else {
			$wp_customize->add_control( $setting_id, array(
				'label'    => $field['label'],
				'section'  => $section_id,
				'settings' => $setting_id,
				'type'     => 'text',
			) );
		}
	}
}
