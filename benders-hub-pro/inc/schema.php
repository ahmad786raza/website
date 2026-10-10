<?php
/**
 * Structured Data — JSON-LD.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_print_schema() {
	$nodes = array();

	// Organization
	$nodes[] = array(
		'@type'    => 'Organization',
		'@id'      => home_url( '/#organization' ),
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
		'logo'     => array(
			'@type' => 'ImageObject',
			'url'   => function_exists( 'wp_get_attachment_image_url' ) && get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
		),
		'description' => __( 'Premium fabrication engineering ecosystem. Reviews, calculators, and tooling guidance for tube benders, hydraulic pipe benders, CNC bending equipment, and metalworking systems.', 'benders-hub' ),
	);

	// WebSite + SearchAction
	$nodes[] = array(
		'@type'    => 'WebSite',
		'@id'      => home_url( '/#website' ),
		'url'      => home_url( '/' ),
		'name'     => get_bloginfo( 'name' ),
		'publisher' => array( '@id' => home_url( '/#organization' ) ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);

	if ( is_front_page() ) {
		// FAQ
		$faqs = bdh_get_faqs();
		if ( $faqs ) {
			$faq_items = array();
			foreach ( $faqs as $faq ) {
				$faq_items[] = array(
					'@type' => 'Question',
					'name'  => $faq['q'],
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $faq['a'],
					),
				);
			}
			$nodes[] = array(
				'@type'      => 'FAQPage',
				'mainEntity' => $faq_items,
			);
		}

		// SoftwareApplication for each interactive tool
		$tools = array(
			array( 'Tube Bender Match Quiz',       'Recommends a tube bender category from project type, material, and shop size.' ),
			array( 'Bend Radius Calculator',      'Calculates ideal bend radius, wrinkle risk, and springback for tubing.' ),
			array( 'Tube Geometry Visualizer',    'Interactive visualization of bend angle, CLR, springback, and material stress.' ),
			array( 'Fabrication Workflow Planner','Plans a sequenced fabrication workflow with safety and tooling reminders.' ),
			array( 'Tool Compatibility Generator','Generates compatible dies and tooling for a tubing/material combination.' ),
			array( 'Workshop Efficiency Prioritizer','Builds a shop profile by ranking precision, speed, portability, safety, cost.' ),
			array( 'Project Tracker',             'Locally saves fabrication projects, materials, and bend settings.' ),
		);
		foreach ( $tools as $tool ) {
			$nodes[] = array(
				'@type'              => 'SoftwareApplication',
				'name'               => $tool[0],
				'description'        => $tool[1],
				'applicationCategory'=> 'EngineeringApplication',
				'operatingSystem'    => 'Web',
				'offers'             => array( '@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD' ),
			);
		}

		// ItemList of editor picks
		$picks = bdh_default_equipment();
		$items = array();
		$pos = 1;
		foreach ( array_slice( $picks, 0, 6 ) as $p ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $p['name'],
				'url'      => home_url( '/#bdh-database' ),
			);
		}
		$nodes[] = array(
			'@type'           => 'ItemList',
			'name'            => __( "Editor's Best Tube Benders & Fabrication Equipment", 'benders-hub' ),
			'itemListElement' => $items,
		);
	}

	if ( is_singular( 'bdh_product' ) ) {
		$post_id = get_the_ID();
		$score   = get_post_meta( $post_id, '_bdh_editor_score', true );
		$nodes[] = array(
			'@type'       => 'Product',
			'name'        => get_the_title(),
			'description' => wp_strip_all_tags( get_the_excerpt() ),
			'image'       => get_the_post_thumbnail_url( $post_id, 'bdh-product' ),
			'review'      => array(
				'@type'        => 'Review',
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $score ? $score : 9,
					'bestRating'  => 10,
					'worstRating' => 1,
				),
				'author' => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ) ),
			),
		);
	}

	if ( is_singular( 'post' ) || is_singular( 'bdh_guide' ) ) {
		$nodes[] = array(
			'@type'         => 'Article',
			'headline'      => get_the_title(),
			'datePublished' => get_the_date( 'c' ),
			'dateModified'  => get_the_modified_date( 'c' ),
			'author'        => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ) ),
			'publisher'     => array( '@id'   => home_url( '/#organization' ) ),
			'image'         => get_the_post_thumbnail_url( get_the_ID(), 'bdh-hero' ),
		);
	}

	// Breadcrumbs (basic)
	if ( ! is_front_page() ) {
		$crumbs = array(
			array( 'name' => __( 'Home', 'benders-hub' ), 'url' => home_url( '/' ) ),
		);
		if ( is_singular() ) {
			$crumbs[] = array( 'name' => get_the_title(), 'url' => get_permalink() );
		} elseif ( is_archive() ) {
			$crumbs[] = array( 'name' => wp_get_document_title(), 'url' => '' );
		}
		$bc_items = array();
		$pos = 1;
		foreach ( $crumbs as $c ) {
			$bc_items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $c['name'],
				'item'     => $c['url'],
			);
		}
		$nodes[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $bc_items );
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array(
		'@context' => 'https://schema.org',
		'@graph'   => $nodes,
	), JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'bdh_print_schema', 100 );
