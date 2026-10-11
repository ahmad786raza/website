<?php
/**
 * Customizer — Appearance → Customize → Benders Hub.
 * Setting IDs from v1 are kept (hero_*, faq_*, newsletter_*, trust_*, footer_*)
 * so saved values carry over; v1 defaults that made unsupported claims are
 * filtered in bdh_option().
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_customize_register( $wp_customize ) {

	$wp_customize->add_panel( 'bdh_panel', array(
		'title'       => __( 'Benders Hub', 'benders-hub' ),
		'description' => __( 'Homepage layout, lead story, buying guides, newsletter and footer.', 'benders-hub' ),
		'priority'    => 5,
	) );

	/* --- Homepage intro --- */
	$wp_customize->add_section( 'bdh_hero', array( 'title' => __( 'Homepage intro', 'benders-hub' ), 'panel' => 'bdh_panel', 'priority' => 10 ) );
	bdh_register_section_fields( $wp_customize, 'bdh_hero', array(
		'hero_eyebrow'           => array( 'label' => __( 'Eyebrow', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'hero_headline'          => array( 'label' => __( 'Headline (homepage H1)', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
		'hero_sub'               => array( 'label' => __( 'Introduction', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
		'hero_cta_primary'       => array( 'label' => __( 'Primary button label', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'hero_cta_primary_url'   => array( 'label' => __( 'Primary button URL (blank = quiz)', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'hero_cta_secondary'     => array( 'label' => __( 'Secondary button label', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'hero_cta_secondary_url' => array( 'label' => __( 'Secondary button URL (blank = calculator)', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'hero_image'             => array( 'label' => __( 'Fallback image (shown only when there are no posts)', 'benders-hub' ), 'type' => 'image', 'default' => '' ),
	) );

	/* --- Magazine content selection --- */
	$wp_customize->add_section( 'bdh_magazine', array( 'title' => __( 'Magazine: lead story & selections', 'benders-hub' ), 'panel' => 'bdh_panel', 'priority' => 15 ) );

	$posts   = get_posts( array( 'posts_per_page' => 60, 'post_status' => 'publish', 'no_found_rows' => true ) );
	$choices = array( 0 => __( '— Newest sticky or newest post —', 'benders-hub' ) );
	foreach ( $posts as $p ) { $choices[ $p->ID ] = wp_html_excerpt( get_the_title( $p ), 70, '…' ); }
	$wp_customize->add_setting( 'bdh_featured_post', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'bdh_featured_post', array( 'label' => __( 'Lead story', 'benders-hub' ), 'section' => 'bdh_magazine', 'type' => 'select', 'choices' => $choices ) );

	$cats = array( '' => __( '— Auto-detect guides —', 'benders-hub' ) );
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $c ) { $cats[ $c->slug ] = $c->name; }
	$wp_customize->add_setting( 'bdh_guides_category', array( 'default' => 'guides', 'sanitize_callback' => 'sanitize_title' ) );
	$wp_customize->add_control( 'bdh_guides_category', array( 'label' => __( 'Buying guides category', 'benders-hub' ), 'section' => 'bdh_magazine', 'type' => 'select', 'choices' => $cats ) );

	$wp_customize->add_setting( 'bdh_curated_posts', array( 'default' => '', 'sanitize_callback' => 'bdh_sanitize_id_list' ) );
	$wp_customize->add_control( 'bdh_curated_posts', array( 'label' => __( 'Essential reads (post IDs, comma-separated, in order)', 'benders-hub' ), 'description' => __( 'An editor\'s selection — the theme does not claim these are the most popular.', 'benders-hub' ), 'section' => 'bdh_magazine', 'type' => 'text' ) );

	bdh_register_section_fields( $wp_customize, 'bdh_magazine', array(
		'archetypes_title' => array( 'label' => __( '"Learn by project" title', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'archetypes_sub'   => array( 'label' => __( '"Learn by project" subtitle', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
	) );

	/* --- FAQ --- */
	$wp_customize->add_section( 'bdh_faq_section', array( 'title' => __( 'FAQ', 'benders-hub' ), 'panel' => 'bdh_panel', 'priority' => 130 ) );
	bdh_register_section_fields( $wp_customize, 'bdh_faq_section', array(
		'faq_title' => array( 'label' => __( 'Title', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'faq_sub'   => array( 'label' => __( 'Subtitle', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'faqs_json' => array( 'label' => __( 'FAQ JSON override: [{"q":"…","a":"…"}]', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
	) );

	/* --- Newsletter --- */
	$wp_customize->add_section( 'bdh_newsletter_section', array( 'title' => __( 'Newsletter', 'benders-hub' ), 'panel' => 'bdh_panel', 'priority' => 140, 'description' => __( 'Sign-ups are stored under Benders Hub → Subscribers. Optionally forward them to your email service with a POST endpoint.', 'benders-hub' ) ) );
	bdh_register_section_fields( $wp_customize, 'bdh_newsletter_section', array(
		'newsletter_title'    => array( 'label' => __( 'Title', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'newsletter_sub'      => array( 'label' => __( 'Text', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
		'newsletter_cta'      => array( 'label' => __( 'Button label', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'newsletter_endpoint' => array( 'label' => __( 'POST endpoint (optional)', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
	) );

	/* --- Editorial policy block --- */
	$wp_customize->add_section( 'bdh_trust_section', array( 'title' => __( 'Editorial policy block', 'benders-hub' ), 'panel' => 'bdh_panel', 'priority' => 150, 'description' => __( 'Only describe testing, staff or credentials you can evidence.', 'benders-hub' ) ) );
	bdh_register_section_fields( $wp_customize, 'bdh_trust_section', array(
		'trust_title' => array( 'label' => __( 'Title', 'benders-hub' ), 'type' => 'text', 'default' => '' ),
		'trust_sub'   => array( 'label' => __( 'Subtitle', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
	) );

	/* --- Footer --- */
	$wp_customize->add_section( 'bdh_footer_section', array( 'title' => __( 'Header & footer', 'benders-hub' ), 'panel' => 'bdh_panel', 'priority' => 160 ) );
	bdh_register_section_fields( $wp_customize, 'bdh_footer_section', array(
		'footer_tagline'      => array( 'label' => __( 'Footer description', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
		'show_top_disclosure' => array( 'label' => __( 'Show the affiliate disclosure strip above the header', 'benders-hub' ), 'type' => 'checkbox', 'default' => 1 ),
	) );

	/* --- Homepage sections (order + visibility) --- */
	$wp_customize->add_section( 'bdh_homepage_layout', array( 'title' => __( 'Homepage: sections', 'benders-hub' ), 'panel' => 'bdh_panel', 'priority' => 170, 'description' => __( 'Show/hide sections and set their order (lower numbers first).', 'benders-hub' ) ) );
	foreach ( bdh_default_homepage_sections() as $key => $def ) {
		$wp_customize->add_setting( 'bdh_home_layout_v2[' . $key . '][enabled]', array( 'default' => $def['enabled'], 'sanitize_callback' => 'absint', 'type' => 'theme_mod' ) );
		$wp_customize->add_control( 'bdh_home_layout_v2_' . $key . '_enabled', array( 'label' => sprintf( __( 'Show: %s', 'benders-hub' ), $def['label'] ), 'section' => 'bdh_homepage_layout', 'settings' => 'bdh_home_layout_v2[' . $key . '][enabled]', 'type' => 'checkbox' ) );
		$wp_customize->add_setting( 'bdh_home_layout_v2[' . $key . '][order]', array( 'default' => $def['order'], 'sanitize_callback' => 'absint', 'type' => 'theme_mod' ) );
		$wp_customize->add_control( 'bdh_home_layout_v2_' . $key . '_order', array( 'label' => sprintf( __( 'Order: %s', 'benders-hub' ), $def['label'] ), 'section' => 'bdh_homepage_layout', 'settings' => 'bdh_home_layout_v2[' . $key . '][order]', 'type' => 'number' ) );
	}

	/* --- Amazon --- */
	$wp_customize->add_section( 'bdh_amazon_section', array( 'title' => __( 'Amazon keyword bank', 'benders-hub' ), 'panel' => 'bdh_panel', 'priority' => 180 ) );
	bdh_register_section_fields( $wp_customize, 'bdh_amazon_section', array(
		'amazon_keyword_bank' => array( 'label' => __( 'Custom Amazon keywords (one per line)', 'benders-hub' ), 'type' => 'textarea', 'default' => '' ),
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
		$sanitize   = 'sanitize_text_field';
		if ( 'textarea' === $type ) { $sanitize = 'sanitize_textarea_field'; }
		if ( 'checkbox' === $type ) { $sanitize = 'bdh_sanitize_checkbox'; }
		if ( 'image' === $type )    { $sanitize = 'esc_url_raw'; }
		$wp_customize->add_setting( $setting_id, array( 'default' => isset( $field['default'] ) ? $field['default'] : '', 'sanitize_callback' => $sanitize, 'type' => 'theme_mod' ) );
		if ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting_id, array( 'label' => $field['label'], 'section' => $section_id, 'settings' => $setting_id ) ) );
		} else {
			$wp_customize->add_control( $setting_id, array( 'label' => $field['label'], 'section' => $section_id, 'settings' => $setting_id, 'type' => $type ) );
		}
	}
}
