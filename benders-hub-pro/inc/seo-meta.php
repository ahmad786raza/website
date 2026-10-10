<?php
/**
 * SEO meta tags: description, Open Graph, Twitter Cards, canonical, hreflang.
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
		'type'        => 'website',
		'site_name'   => get_bloginfo( 'name' ),
		'locale'      => get_locale(),
		'twitter'     => 'summary_large_image',
		'published'   => '',
		'modified'    => '',
	);

	if ( is_front_page() ) {
		$ctx['url']         = home_url( '/' );
		$ctx['description'] = bdh_option( 'meta_description', __( 'Premium fabrication engineering hub. Reviews, calculators, and tooling guidance for tube benders, hydraulic pipe benders, exhaust mandrel benders, roll cage tubing, CNC bending, and metalworking systems.', 'benders-hub' ) );
		$ctx['image']       = bdh_option( 'hero_image' ) ?: bdh_option( 'og_default_image', '' );
	} elseif ( is_singular() ) {
		$ctx['url']         = get_permalink();
		$ctx['type']        = 'article';
		$ctx['description'] = wp_strip_all_tags( get_the_excerpt() ?: wp_trim_words( get_post_field( 'post_content', get_the_ID() ), 30 ) );
		$ctx['image']       = get_the_post_thumbnail_url( get_the_ID(), 'bdh-hero' ) ?: bdh_option( 'og_default_image', '' );
		$ctx['published']   = get_the_date( 'c' );
		$ctx['modified']    = get_the_modified_date( 'c' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		$ctx['url']         = get_term_link( $term );
		$ctx['description'] = $term && ! empty( $term->description ) ? wp_strip_all_tags( $term->description ) : sprintf( __( 'Fabrication guides, reviews, and tools tagged %s — workshop-tested by working fabricators.', 'benders-hub' ), $term->name );
		$ctx['image']       = bdh_option( 'og_default_image', '' );
	} elseif ( is_post_type_archive() ) {
		$pt = get_queried_object();
		$ctx['url']         = get_post_type_archive_link( $pt->name );
		$ctx['description'] = ! empty( $pt->description ) ? wp_strip_all_tags( $pt->description ) : __( 'Browse the Benders Hub bending equipment database — hydraulic, manual, CNC, mandrel, and specialty benders reviewed by working fabricators.', 'benders-hub' );
		$ctx['image']       = bdh_option( 'og_default_image', '' );
	} elseif ( is_search() ) {
		$ctx['url']         = home_url( '/?s=' . rawurlencode( get_search_query() ) );
		$ctx['description'] = sprintf( __( 'Search results for "%s" on Benders Hub.', 'benders-hub' ), get_search_query() );
	} elseif ( is_404() ) {
		$ctx['url']         = home_url( $_SERVER['REQUEST_URI'] ?? '/' );
		$ctx['description'] = __( 'Page not found — explore our fabrication archetypes, take the Tube Bender Match Quiz, or browse top fabrication guides.', 'benders-hub' );
	}

	// Hard cap description length to ~160 chars for SERP friendliness.
	$ctx['description'] = trim( $ctx['description'] );
	if ( mb_strlen( $ctx['description'] ) > 200 ) {
		$ctx['description'] = mb_substr( $ctx['description'], 0, 197 ) . '…';
	}

	return $ctx;
}

/**
 * Emit meta tags in <head>.
 */
function bdh_print_seo_meta() {
	$ctx = bdh_seo_context();

	if ( $ctx['description'] ) {
		echo '<meta name="description" content="' . esc_attr( $ctx['description'] ) . '">' . "\n";
	}

	if ( $ctx['url'] ) {
		echo '<link rel="canonical" href="' . esc_url( $ctx['url'] ) . '">' . "\n";
	}

	// Open Graph
	echo '<meta property="og:locale" content="' . esc_attr( str_replace( '_', '-', $ctx['locale'] ) ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $ctx['type'] ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $ctx['title'] ) . '">' . "\n";
	if ( $ctx['description'] ) {
		echo '<meta property="og:description" content="' . esc_attr( $ctx['description'] ) . '">' . "\n";
	}
	if ( $ctx['url'] ) {
		echo '<meta property="og:url" content="' . esc_url( $ctx['url'] ) . '">' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( $ctx['site_name'] ) . '">' . "\n";

	if ( $ctx['image'] ) {
		echo '<meta property="og:image" content="' . esc_url( $ctx['image'] ) . '">' . "\n";
		echo '<meta property="og:image:width" content="1200">' . "\n";
		echo '<meta property="og:image:height" content="630">' . "\n";
		echo '<meta property="og:image:alt" content="' . esc_attr( $ctx['title'] ) . '">' . "\n";
	}

	if ( $ctx['type'] === 'article' ) {
		if ( $ctx['published'] ) {
			echo '<meta property="article:published_time" content="' . esc_attr( $ctx['published'] ) . '">' . "\n";
		}
		if ( $ctx['modified'] ) {
			echo '<meta property="article:modified_time" content="' . esc_attr( $ctx['modified'] ) . '">' . "\n";
		}
		$cats = get_the_category();
		if ( $cats ) {
			echo '<meta property="article:section" content="' . esc_attr( $cats[0]->name ) . '">' . "\n";
		}
		$tags = get_the_tags();
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '">' . "\n";
			}
		}
	}

	// Twitter Cards
	echo '<meta name="twitter:card" content="' . esc_attr( $ctx['twitter'] ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $ctx['title'] ) . '">' . "\n";
	if ( $ctx['description'] ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $ctx['description'] ) . '">' . "\n";
	}
	if ( $ctx['image'] ) {
		echo '<meta name="twitter:image" content="' . esc_url( $ctx['image'] ) . '">' . "\n";
	}
	$twitter_site = bdh_option( 'twitter_site', '' );
	if ( $twitter_site ) {
		echo '<meta name="twitter:site" content="' . esc_attr( $twitter_site ) . '">' . "\n";
	}

	// Theme color for mobile UI
	echo '<meta name="theme-color" content="#111827">' . "\n";
	echo '<meta name="msapplication-TileColor" content="#111827">' . "\n";

	// Robots tag for results / search pages.
	if ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	}
}
add_action( 'wp_head', 'bdh_print_seo_meta', 2 );

/**
 * Add Customizer fields for SEO defaults.
 */
function bdh_seo_customizer( $wp_customize ) {
	$wp_customize->add_section( 'bdh_seo_section', array(
		'title'    => __( 'SEO & Social Defaults', 'benders-hub' ),
		'panel'    => 'bdh_panel',
		'priority' => 190,
	) );

	$wp_customize->add_setting( 'bdh_meta_description', array(
		'default'           => __( 'Premium fabrication engineering hub. Reviews, calculators, and tooling guidance for tube benders, hydraulic pipe benders, exhaust mandrel benders, roll cage tubing, CNC bending, and metalworking systems.', 'benders-hub' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'bdh_meta_description', array(
		'label'    => __( 'Default Meta Description (homepage)', 'benders-hub' ),
		'section'  => 'bdh_seo_section',
		'type'     => 'textarea',
	) );

	$wp_customize->add_setting( 'bdh_og_default_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bdh_og_default_image', array(
		'label'    => __( 'Default Social Share Image (1200×630)', 'benders-hub' ),
		'section'  => 'bdh_seo_section',
	) ) );

	$wp_customize->add_setting( 'bdh_twitter_site', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'bdh_twitter_site', array(
		'label'    => __( 'Twitter @handle (optional)', 'benders-hub' ),
		'section'  => 'bdh_seo_section',
		'type'     => 'text',
	) );
}
add_action( 'customize_register', 'bdh_seo_customizer', 20 );
