<?php
/**
 * Structured data (JSON-LD).
 *
 * Removed from v1 because they described things that are not on the page or
 * not true: Product + Review nodes with a default 9/10 rating for every
 * equipment post, an ItemList of fictional "Editor's Best" products, and
 * SoftwareApplication nodes for every tool on every homepage view.
 *
 * Output now: Organization, WebSite (+SearchAction), BlogPosting for articles,
 * BreadcrumbList, FAQPage only where the FAQ is visible, and Product+Review
 * only for equipment marked "hands-on tested" with an editor score.
 * Skipped entirely when an SEO plugin that writes its own schema is active.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Is a dedicated SEO plugin handling meta tags and schema?
 */
function bdh_seo_plugin_active() {
	$active = defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || class_exists( 'SQ_Classes_ObjController' );
	return (bool) apply_filters( 'bdh_seo_plugin_active', $active );
}

function bdh_print_schema() {
	if ( bdh_seo_plugin_active() ) { return; }
	$nodes = array();
	$logo  = get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '';

	$org = array(
		'@type' => 'Organization',
		'@id'   => home_url( '/#organization' ),
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);
	if ( $logo ) { $org['logo'] = array( '@type' => 'ImageObject', 'url' => $logo ); }
	$nodes[] = $org;

	$nodes[] = array(
		'@type'           => 'WebSite',
		'@id'             => home_url( '/#website' ),
		'url'             => home_url( '/' ),
		'name'            => get_bloginfo( 'name' ),
		'publisher'       => array( '@id' => home_url( '/#organization' ) ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array( '@type' => 'EntryPoint', 'urlTemplate' => home_url( '/?s={search_term_string}' ) ),
			'query-input' => 'required name=search_term_string',
		),
	);

	// FAQ — only when the FAQ section is actually displayed on the homepage.
	if ( is_front_page() ) {
		$sections = bdh_get_homepage_sections();
		if ( ! empty( $sections['faq']['enabled'] ) ) {
			$items = array();
			foreach ( bdh_get_faqs() as $faq ) {
				$items[] = array( '@type' => 'Question', 'name' => wp_strip_all_tags( $faq['q'] ), 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $faq['a'] ) ) );
			}
			if ( $items ) { $nodes[] = array( '@type' => 'FAQPage', '@id' => home_url( '/#faq' ), 'mainEntity' => $items ); }
		}
	}

	if ( is_singular( array( 'post', 'bdh_guide' ) ) ) {
		$id        = get_queried_object_id();
		$author_id = (int) get_post_field( 'post_author', $id );
		$article   = array(
			'@type'            => 'BlogPosting',
			'@id'              => get_permalink( $id ) . '#article',
			'headline'         => wp_strip_all_tags( get_the_title( $id ) ),
			'datePublished'    => get_the_date( 'c', $id ),
			'dateModified'     => get_the_modified_date( 'c', $id ),
			'mainEntityOfPage' => get_permalink( $id ),
			'author'           => array( '@type' => 'Person', 'name' => get_the_author_meta( 'display_name', $author_id ), 'url' => get_author_posts_url( $author_id ) ),
			'publisher'        => array( '@id' => home_url( '/#organization' ) ),
			'isPartOf'         => array( '@id' => home_url( '/#website' ) ),
		);
		$img = get_the_post_thumbnail_url( $id, 'bdh-lead' );
		if ( $img ) { $article['image'] = $img; }
		if ( has_excerpt( $id ) ) { $article['description'] = wp_strip_all_tags( get_the_excerpt( $id ) ); }
		$cat = bdh_primary_category( $id );
		if ( $cat ) { $article['articleSection'] = $cat->name; }
		$nodes[] = $article;
	}

	// Product + Review only for genuinely hands-on-tested equipment with a score.
	if ( is_singular( 'bdh_product' ) ) {
		$id    = get_queried_object_id();
		$score = (float) get_post_meta( $id, '_bdh_editor_score', true );
		if ( ! bdh_is_illustrative_product( $id ) && 'hands-on' === get_post_meta( $id, '_bdh_evaluation', true ) && $score > 0 ) {
			$product = array(
				'@type'  => 'Product',
				'name'   => wp_strip_all_tags( get_the_title( $id ) ),
				'review' => array(
					'@type'        => 'Review',
					'reviewRating' => array( '@type' => 'Rating', 'ratingValue' => $score, 'bestRating' => 10, 'worstRating' => 1 ),
					'author'       => array( '@type' => 'Person', 'name' => get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $id ) ) ),
				),
			);
			$img = get_the_post_thumbnail_url( $id, 'bdh-product' );
			if ( $img ) { $product['image'] = $img; }
			if ( has_excerpt( $id ) ) { $product['description'] = wp_strip_all_tags( get_the_excerpt( $id ) ); }
			$nodes[] = $product;
		}
	}

	if ( ! is_front_page() && ! is_404() ) {
		$items = bdh_breadcrumb_items();
		if ( count( $items ) > 1 ) {
			$list = array();
			foreach ( $items as $i => $item ) {
				$el = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => wp_strip_all_tags( $item[0] ) );
				if ( $item[1] ) { $el['item'] = $item[1]; }
				$list[] = $el;
			}
			$nodes[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $list );
		}
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $nodes ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'bdh_print_schema', 100 );
