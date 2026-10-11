<?php
/**
 * Structured data, meta description, Open Graph and canonical tags.
 * Everything here steps aside when an SEO plugin is active (Yoast, Rank Math, AIOSEO, SEOPress, The SEO Framework).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Should the theme leave meta tags, canonical and schema to an SEO plugin?
 *
 * Customizer "SEO output" setting: auto (default), always, never.
 * In auto mode a plugin only counts when it is really working on the front end:
 * Rank Math, for example, can be "active" but print nothing (missing capabilities,
 * disabled front end), and a site with no description tags is worse than a site
 * with the theme's own. Yoast, AIOSEO, SEOPress and The SEO Framework are trusted
 * from their constants; Rank Math is checked by the hooks its front-end registers.
 */
function hbg_seo_plugin_active() {
	$mode = get_theme_mod( 'hbg_seo_output', 'auto' );
	if ( 'always' === $mode ) {
		return false;
	}
	if ( 'never' === $mode ) {
		return true;
	}
	if ( defined( 'WPSEO_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || class_exists( 'The_SEO_Framework\\Load' ) ) {
		return true;
	}
	if ( defined( 'RANK_MATH_VERSION' ) ) {
		if ( did_action( 'rank_math/head' ) ) {
			return true;
		}
		if ( apply_filters( 'rank_math/frontend/disable', false ) ) {
			return false;
		}
		return (bool) has_action( 'rank_math/head' );
	}
	return false;
}

/** True when the post body carries its own JSON-LD of the given type. */
function hbg_content_has_schema_type( $content, $types ) {
	if ( false === stripos( $content, 'application/ld+json' ) ) {
		return false;
	}
	foreach ( (array) $types as $t ) {
		if ( preg_match( '/"@type"\s*:\s*"' . preg_quote( $t, '/' ) . '"/', $content ) ) {
			return true;
		}
	}
	return false;
}

function hbg_schema_jsonld() {
	if ( hbg_seo_plugin_active() ) {
		return;
	}
	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url( '/' );
	$logo_id   = get_theme_mod( 'custom_logo' );
	$logo_url  = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : hbg_image_url( 'cat-hoverboards', 800 );
	$graphs    = array();

	$organization = array( '@type' => 'Organization', '@id' => $site_url . '#organization', 'name' => $site_name, 'url' => $site_url, 'logo' => array( '@type' => 'ImageObject', 'url' => $logo_url ) );
	$socials      = array_values( wp_list_pluck( hbg_social_links(), 'url' ) );
	if ( $socials ) {
		$organization['sameAs'] = $socials;
	}
	$graphs[] = $organization;
	$graphs[] = array( '@type' => 'WebSite', '@id' => $site_url . '#website', 'name' => $site_name, 'url' => $site_url, 'publisher' => array( '@id' => $site_url . '#organization' ), 'potentialAction' => array( '@type' => 'SearchAction', 'target' => array( '@type' => 'EntryPoint', 'urlTemplate' => home_url( '/?s={search_term_string}' ) ), 'query-input' => 'required name=search_term_string' ) );

	if ( is_front_page() && get_theme_mod( 'hbg_show_faq', true ) ) {
		$faq = array();
		foreach ( hbg_get_faqs() as $f ) {
			$faq[] = array( '@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['a'] ) );
		}
		if ( $faq ) {
			$graphs[] = array( '@type' => 'FAQPage', 'mainEntity' => $faq );
		}
	}

	if ( is_singular( 'post' ) ) {
		$post = get_queried_object();
		if ( ! hbg_content_has_schema_type( $post->post_content, array( 'Article', 'BlogPosting', 'NewsArticle' ) ) ) {
			$author_name = hbg_author_display_name( $post->post_author );
			$author      = ( $author_name === get_theme_mod( 'hbg_author_name', __( 'Hoverboards Guide Editors', 'hoverboards-guide' ) ) )
				? array( '@id' => $site_url . '#organization' )
				: array( '@type' => 'Person', 'name' => $author_name );
			$article = array(
				'@type'            => 'BlogPosting',
				'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
				'description'      => hbg_excerpt( 30, $post ),
				'datePublished'    => get_the_date( 'c', $post ),
				'dateModified'     => get_the_modified_date( 'c', $post ),
				'author'           => $author,
				'publisher'        => array( '@id' => $site_url . '#organization' ),
				'mainEntityOfPage' => array( '@type' => 'WebPage', '@id' => get_permalink( $post ) ),
			);
			if ( has_post_thumbnail( $post ) ) {
				$article['image'] = get_the_post_thumbnail_url( $post, 'large' );
			}
			$cat = hbg_primary_category( $post->ID );
			if ( $cat ) {
				$article['articleSection'] = $cat->name;
			}
			$graphs[] = $article;
		}
	}

	if ( ! is_front_page() && ! is_404() ) {
		$content_has_crumbs = is_singular( 'post' ) && hbg_content_has_schema_type( get_queried_object()->post_content, array( 'BreadcrumbList' ) );
		if ( ! $content_has_crumbs ) {
			$crumbs = array();
			foreach ( hbg_breadcrumb_items() as $i => $item ) {
				$entry = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => wp_strip_all_tags( $item['label'] ) );
				if ( $item['url'] ) {
					$entry['item'] = $item['url'];
				}
				$crumbs[] = $entry;
			}
			$graphs[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $crumbs );
		}
	}

	if ( is_category() || is_tag() ) {
		$term     = get_queried_object();
		$graphs[] = array( '@type' => 'CollectionPage', 'name' => $term->name, 'description' => hbg_meta_description(), 'url' => get_term_link( $term ), 'isPartOf' => array( '@id' => $site_url . '#website' ) );
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graphs ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'hbg_schema_jsonld', 20 );

/** Meta description, Open Graph / Twitter cards and canonical for views WordPress leaves bare. */
function hbg_head_meta() {
	if ( hbg_seo_plugin_active() ) {
		return;
	}
	$desc = hbg_meta_description();
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( wp_trim_words( $desc, 32, '' ) ) . '">' . "\n";
	}
	$title = wp_get_document_title();
	$url   = home_url( add_query_arg( array(), isset( $GLOBALS['wp']->request ) ? $GLOBALS['wp']->request : '' ) );
	$image = hbg_image_url( 'cat-hoverboards', 800 );
	$type  = 'website';
	if ( is_singular() ) {
		$post = get_queried_object();
		$url  = get_permalink( $post );
		$type = is_singular( 'post' ) ? 'article' : 'website';
		if ( has_post_thumbnail( $post ) ) {
			$image = get_the_post_thumbnail_url( $post, 'large' );
		}
	} elseif ( is_category() || is_tag() ) {
		$url = get_term_link( get_queried_object() );
	} elseif ( is_front_page() ) {
		$url   = home_url( '/' );
		$hero  = get_theme_mod( 'hbg_hero_image', '' );
		$image = $hero ? wp_get_attachment_image_url( (int) $hero, 'large' ) : $image;
	}
	if ( ( is_front_page() || is_category() || is_tag() ) && ! is_paged() ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action( 'wp_head', 'hbg_head_meta', 6 );

/** Homepage title tag (Customizer override), otherwise WordPress' default "Site – Tagline". */
function hbg_document_title( $title ) {
	if ( is_front_page() ) {
		$custom = get_theme_mod( 'hbg_home_title', '' );
		if ( $custom ) {
			return $custom;
		}
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'hbg_document_title' );
