<?php
/**
 * JSON-LD structured data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hbg_schema_jsonld() {
	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url( '/' );
	$logo_id   = get_theme_mod( 'custom_logo' );
	$logo_url  = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : hbg_image_url( 'hero-escooter-city', 640 );
	$graphs    = array();

	$organization = array(
		'@type' => 'Organization',
		'@id'   => $site_url . '#organization',
		'name'  => $site_name,
		'url'   => $site_url,
		'logo'  => array( '@type' => 'ImageObject', 'url' => $logo_url ),
	);
	$socials = array_values( wp_list_pluck( hbg_social_links(), 'url' ) );
	if ( $socials ) {
		$organization['sameAs'] = $socials;
	}
	$graphs[] = $organization;

	$graphs[] = array(
		'@type'           => 'WebSite',
		'@id'             => $site_url . '#website',
		'name'            => $site_name,
		'url'             => $site_url,
		'publisher'       => array( '@id' => $site_url . '#organization' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array( '@type' => 'EntryPoint', 'urlTemplate' => home_url( '/?s={search_term_string}' ) ),
			'query-input' => 'required name=search_term_string',
		),
	);

	if ( is_front_page() ) {
		$faq_entries = array();
		foreach ( hbg_get_faqs() as $f ) {
			$faq_entries[] = array(
				'@type'          => 'Question',
				'name'           => $f['q'],
				'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['a'] ),
			);
		}
		if ( $faq_entries && get_theme_mod( 'hbg_show_faq', true ) ) {
			$graphs[] = array( '@type' => 'FAQPage', 'mainEntity' => $faq_entries );
		}
	}

	if ( is_singular( 'post' ) ) {
		$post    = get_queried_object();
		$article = array(
			'@type'            => 'Article',
			'headline'         => get_the_title( $post ),
			'description'      => hbg_excerpt( 30, $post ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'author'           => array( '@type' => 'Person', 'name' => hbg_author_display_name( $post->post_author ), 'url' => get_author_posts_url( $post->post_author ) ),
			'publisher'        => array( '@id' => $site_url . '#organization' ),
			'mainEntityOfPage' => array( '@type' => 'WebPage', '@id' => get_permalink( $post ) ),
			'wordCount'        => str_word_count( wp_strip_all_tags( $post->post_content ) ),
		);
		if ( has_post_thumbnail( $post ) ) {
			$article['image'] = get_the_post_thumbnail_url( $post, 'large' );
		}
		$cat = hbg_primary_category( $post->ID );
		if ( $cat ) {
			$article['articleSection'] = $cat->name;
		}
		$graphs[] = $article;

		$crumbs = array(
			array( '@type' => 'ListItem', 'position' => 1, 'name' => __( 'Home', 'hoverboards-guide' ), 'item' => $site_url ),
		);
		if ( $cat ) {
			$crumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => $cat->name, 'item' => get_category_link( $cat ) );
		}
		$crumbs[] = array( '@type' => 'ListItem', 'position' => count( $crumbs ) + 1, 'name' => get_the_title( $post ) );
		$graphs[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $crumbs );
	}

	if ( is_category() ) {
		$term = get_queried_object();
		$graphs[] = array(
			'@type'       => 'CollectionPage',
			'name'        => $term->name,
			'description' => wp_strip_all_tags( term_description( $term ) ),
			'url'         => get_category_link( $term ),
			'isPartOf'    => array( '@id' => $site_url . '#website' ),
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graphs ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'hbg_schema_jsonld', 20 );

/**
 * Open Graph / Twitter cards (skipped automatically if an SEO plugin already prints og:title).
 */
function hbg_open_graph() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'The_SEO_Framework\\Load' ) ) {
		return;
	}
	$title = wp_get_document_title();
	$desc  = get_bloginfo( 'description' );
	$url   = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	$image = hbg_image_url( 'hero-escooter-city', 0, 'jpg' );
	$type  = 'website';
	if ( is_singular() ) {
		$post  = get_queried_object();
		$desc  = hbg_excerpt( 30, $post );
		$url   = get_permalink( $post );
		$type  = is_singular( 'post' ) ? 'article' : 'website';
		if ( has_post_thumbnail( $post ) ) {
			$image = get_the_post_thumbnail_url( $post, 'large' );
		}
	} elseif ( is_category() ) {
		$term   = get_queried_object();
		$preset = hbg_category_preset( $term );
		$desc   = wp_strip_all_tags( term_description( $term ) );
		$desc   = $desc ? $desc : $preset['blurb'];
		$url    = get_category_link( $term );
		$image  = hbg_image_url( $preset['image'] );
	}
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
}
add_action( 'wp_head', 'hbg_open_graph', 6 );
