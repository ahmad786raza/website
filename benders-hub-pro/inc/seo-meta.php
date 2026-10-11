<?php
/**
 * SEO meta: description, Open Graph, Twitter cards.
 *
 * v1 printed a second rel=canonical on every post (WordPress core already
 * prints one), duplicated the core search-page robots tag, and ran alongside
 * SEO plugins, doubling every meta tag. v2 defers to Yoast, Rank Math,
 * AIOSEO, SEOPress or The SEO Framework when present, and otherwise leaves
 * canonical/robots to core.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Build the meta context for the current page.
 */
function bdh_seo_context() {
	$ctx = array(
		'title'       => wp_get_document_title(),
		'description' => '',
		'url'         => '',
		'image'       => '',
		'image_alt'   => '',
		'type'        => 'website',
		'site_name'   => get_bloginfo( 'name' ),
		'locale'      => get_locale(),
		'published'   => '',
		'modified'    => '',
	);
	$default_img = bdh_option( 'og_default_image', '' );
	if ( ! $default_img && function_exists( 'bdh_library_attachment' ) ) {
		$att = bdh_library_attachment( 'hero', 0, false );
		if ( $att ) { $default_img = wp_get_attachment_image_url( $att, 'bdh-lead' ); }
	}

	if ( is_front_page() ) {
		$ctx['url']         = home_url( '/' );
		$ctx['description'] = bdh_option( 'meta_description', __( 'Independent tube bending and fabrication guides: how to choose a tube bender, plan accurate bends with free calculators, and set up for exhaust, roll cage and workshop work.', 'benders-hub' ) );
		$ctx['image']       = bdh_option( 'hero_image' ) ? bdh_option( 'hero_image' ) : $default_img;
	} elseif ( is_singular() ) {
		$id                 = get_queried_object_id();
		$ctx['url']         = get_permalink( $id );
		$ctx['type']        = 'article';
		$ctx['description'] = has_excerpt( $id ) ? get_the_excerpt( $id ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $id ) ) ), 28, '…' );
		$ctx['image']       = get_the_post_thumbnail_url( $id, 'bdh-lead' );
		if ( $ctx['image'] ) {
			$ctx['image_alt'] = get_post_meta( get_post_thumbnail_id( $id ), '_wp_attachment_image_alt', true );
		} else {
			$ctx['image'] = $default_img;
		}
		$ctx['published']   = get_the_date( 'c', $id );
		$ctx['modified']    = get_the_modified_date( 'c', $id );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		$ctx['url']         = get_term_link( $term );
		$ctx['description'] = ( $term && ! empty( $term->description ) ) ? $term->description : sprintf( __( '%s: guides, explainers and equipment advice from Benders Hub.', 'benders-hub' ), $term ? $term->name : '' );
		$ctx['image']       = $default_img;
	} elseif ( is_post_type_archive() ) {
		$pt = get_queried_object();
		$ctx['url']         = get_post_type_archive_link( $pt->name );
		$ctx['description'] = ! empty( $pt->description ) ? $pt->description : __( 'The main classes of tube and pipe bending equipment: what each does, typical capacities and limitations.', 'benders-hub' );
		$ctx['image']       = $default_img;
	}

	$ctx['description'] = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $ctx['description'] ) ) );
	if ( mb_strlen( $ctx['description'] ) > 160 ) {
		$cut = mb_substr( $ctx['description'], 0, 157 );
		$ctx['description'] = mb_substr( $cut, 0, (int) mb_strrpos( $cut, ' ' ) ) . '…';
	}
	return $ctx;
}

/**
 * Emit meta tags in <head>.
 */
function bdh_print_seo_meta() {
	if ( bdh_seo_plugin_active() || is_search() || is_404() ) { return; }
	$ctx = bdh_seo_context();

	if ( $ctx['description'] ) {
		echo '<meta name="description" content="' . esc_attr( $ctx['description'] ) . '">' . "\n";
	}
	// Canonical: core prints it for singular views; add it only for archives/home.
	if ( $ctx['url'] && ! is_singular() && ! is_paged() ) {
		echo '<link rel="canonical" href="' . esc_url( $ctx['url'] ) . '">' . "\n";
	}

	echo '<meta property="og:locale" content="' . esc_attr( $ctx['locale'] ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $ctx['type'] ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $ctx['title'] ) . '">' . "\n";
	if ( $ctx['description'] ) { echo '<meta property="og:description" content="' . esc_attr( $ctx['description'] ) . '">' . "\n"; }
	if ( $ctx['url'] ) { echo '<meta property="og:url" content="' . esc_url( $ctx['url'] ) . '">' . "\n"; }
	echo '<meta property="og:site_name" content="' . esc_attr( $ctx['site_name'] ) . '">' . "\n";
	if ( $ctx['image'] ) {
		echo '<meta property="og:image" content="' . esc_url( $ctx['image'] ) . '">' . "\n";
		if ( $ctx['image_alt'] ) { echo '<meta property="og:image:alt" content="' . esc_attr( $ctx['image_alt'] ) . '">' . "\n"; }
	}
	if ( 'article' === $ctx['type'] ) {
		echo '<meta property="article:published_time" content="' . esc_attr( $ctx['published'] ) . '">' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( $ctx['modified'] ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="' . ( $ctx['image'] ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	$twitter_site = bdh_option( 'twitter_site', '' );
	if ( $twitter_site ) { echo '<meta name="twitter:site" content="' . esc_attr( $twitter_site ) . '">' . "\n"; }
}
add_action( 'wp_head', 'bdh_print_seo_meta', 2 );

/**
 * Theme colour for mobile browser UI (harmless alongside SEO plugins).
 */
add_action( 'wp_head', function () { echo '<meta name="theme-color" content="#ffffff">' . "\n"; }, 3 );

/**
 * Customizer fields for SEO defaults.
 */
function bdh_seo_customizer( $wp_customize ) {
	$wp_customize->add_section( 'bdh_seo_section', array(
		'title'       => __( 'SEO & social defaults', 'benders-hub' ),
		'panel'       => 'bdh_panel',
		'priority'    => 190,
		'description' => __( 'Ignored automatically when Yoast SEO, Rank Math, AIOSEO, SEOPress or The SEO Framework is active.', 'benders-hub' ),
	) );
	$wp_customize->add_setting( 'bdh_meta_description', array( 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'bdh_meta_description', array( 'label' => __( 'Homepage meta description (≤160 characters)', 'benders-hub' ), 'section' => 'bdh_seo_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'bdh_og_default_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bdh_og_default_image', array( 'label' => __( 'Default social share image (1200×630)', 'benders-hub' ), 'section' => 'bdh_seo_section' ) ) );
	$wp_customize->add_setting( 'bdh_twitter_site', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'bdh_twitter_site', array( 'label' => __( 'X/Twitter @handle (optional)', 'benders-hub' ), 'section' => 'bdh_seo_section', 'type' => 'text' ) );
}
add_action( 'customize_register', 'bdh_seo_customizer', 20 );
