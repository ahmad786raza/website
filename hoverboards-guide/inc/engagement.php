<?php
/**
 * Engagement and content hygiene: view counter, AJAX load-more, content filters
 * (duplicate H1 removal, affiliate link attributes, table wrappers), body classes,
 * search scope, noindex for thin views and the legacy subscriber list.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =========================================================================
// View counter (AJAX beacon so it works behind page caches)
// =========================================================================
function hbg_ajax_track_view() {
	check_ajax_referer( 'hbg_nonce', 'nonce' );
	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	if ( ! $post_id || 'publish' !== get_post_status( $post_id ) ) {
		wp_send_json_error();
	}
	if ( current_user_can( 'edit_posts' ) ) {
		wp_send_json_success( array( 'skipped' => true ) );
	}
	$views = (int) get_post_meta( $post_id, 'hbg_views', true );
	update_post_meta( $post_id, 'hbg_views', $views + 1 );
	wp_send_json_success( array( 'views' => $views + 1 ) );
}
add_action( 'wp_ajax_hbg_track_view', 'hbg_ajax_track_view' );
add_action( 'wp_ajax_nopriv_hbg_track_view', 'hbg_ajax_track_view' );

// =========================================================================
// AJAX load more posts (public, read-only; no nonce so it keeps working behind caches)
// =========================================================================
function hbg_ajax_load_more() {
	$paged   = isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : 1;
	$per     = isset( $_POST['per_page'] ) ? min( 12, max( 1, absint( $_POST['per_page'] ) ) ) : 6;
	$cat     = isset( $_POST['cat'] ) ? absint( $_POST['cat'] ) : 0;
	$exclude = isset( $_POST['exclude'] ) ? array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['exclude'] ) ) ) ) : array();
	$args    = array( 'post_type' => 'post', 'posts_per_page' => $per, 'paged' => $paged, 'ignore_sticky_posts' => true, 'post__not_in' => array_filter( $exclude ) );
	if ( $cat ) {
		$args['cat'] = $cat;
	}
	$q = new WP_Query( $args );
	ob_start();
	while ( $q->have_posts() ) {
		$q->the_post();
		get_template_part( 'template-parts/content', 'card' );
	}
	wp_reset_postdata();
	wp_send_json_success( array( 'html' => ob_get_clean(), 'has_more' => $paged < $q->max_num_pages, 'max' => $q->max_num_pages ) );
}
add_action( 'wp_ajax_hbg_load_more', 'hbg_ajax_load_more' );
add_action( 'wp_ajax_nopriv_hbg_load_more', 'hbg_ajax_load_more' );

// =========================================================================
// Content filters
// =========================================================================

/**
 * Many imported articles repeat the post title as an <h1> inside the body, giving two H1s per page.
 * Remove an in-body H1 that matches the title; demote any other in-body H1 to an H2.
 */
function hbg_fix_duplicate_h1( $content ) {
	if ( ! is_singular() || false === stripos( $content, '<h1' ) ) {
		return $content;
	}
	$title = html_entity_decode( get_the_title(), ENT_QUOTES, 'UTF-8' );
	$norm  = function ( $s ) {
		$s = html_entity_decode( wp_strip_all_tags( $s ), ENT_QUOTES, 'UTF-8' );
		$s = preg_replace( '/[^\p{L}\p{N}]+/u', '', mb_strtolower( $s ) );
		return $s;
	};
	$t = $norm( $title );
	return preg_replace_callback( '/<h1\b([^>]*)>(.*?)<\/h1>/is', function ( $m ) use ( $norm, $t ) {
		$inner = $norm( $m[2] );
		if ( $t && ( $inner === $t || ( strlen( $inner ) > 20 && ( false !== strpos( $inner, $t ) || false !== strpos( $t, $inner ) ) ) ) ) {
			return '';
		}
		return '<h2' . $m[1] . '>' . $m[2] . '</h2>';
	}, $content );
}
add_filter( 'the_content', 'hbg_fix_duplicate_h1', 9 );

/**
 * Affiliate link hygiene: every Amazon link gets rel="sponsored nofollow noopener" and a new tab.
 * Links that have no Associates tag receive the site's existing tag; an existing tag is never changed.
 */
function hbg_affiliate_link_hygiene( $content ) {
	if ( false === stripos( $content, 'amazon.' ) && false === stripos( $content, 'amzn.to' ) ) {
		return $content;
	}
	$tag = get_theme_mod( 'hbg_affiliate_tag', 'rcblogs-20' );
	return preg_replace_callback( '/<a\s+([^>]*href=["\']([^"\']*(?:amazon\.|amzn\.to)[^"\']*)["\'][^>]*)>/i', function ( $m ) use ( $tag ) {
		$attrs = $m[1];
		$href  = $m[2];
		if ( $tag && false !== stripos( $href, 'amazon.' ) && ! preg_match( '/[?&]tag=/i', $href ) ) {
			$new   = $href . ( false === strpos( $href, '?' ) ? '?' : '&amp;' ) . 'tag=' . rawurlencode( $tag );
			$attrs = str_replace( $href, $new, $attrs );
		}
		if ( ! preg_match( '/\brel=/i', $attrs ) ) {
			$attrs .= ' rel="sponsored nofollow noopener"';
		} else {
			$attrs = preg_replace_callback( '/\brel=["\']([^"\']*)["\']/i', function ( $r ) {
				$rels = array_unique( array_merge( preg_split( '/\s+/', trim( $r[1] ) ), array( 'sponsored', 'nofollow', 'noopener' ) ) );
				return 'rel="' . esc_attr( implode( ' ', array_filter( $rels ) ) ) . '"';
			}, $attrs );
		}
		if ( ! preg_match( '/\btarget=/i', $attrs ) ) {
			$attrs .= ' target="_blank"';
		}
		if ( ! preg_match( '/data-hbg-track=/i', $attrs ) ) {
			$attrs .= ' data-hbg-track="affiliate" data-placement="content"';
		}
		return '<a ' . $attrs . '>';
	}, $content );
}
add_filter( 'the_content', 'hbg_affiliate_link_hygiene', 20 );

/** Wrap tables so wide comparison tables scroll inside the column with a visible hint. */
function hbg_wrap_tables( $content ) {
	if ( false === strpos( $content, '<table' ) ) {
		return $content;
	}
	$content = preg_replace( '/(?<!<div class="table-scroll">)<table/i', '<div class="table-scroll" tabindex="0" role="region" aria-label="' . esc_attr__( 'Scrollable table', 'hoverboards-guide' ) . '"><table', $content );
	$content = preg_replace( '/<\/table>(?!<\/div>)/i', '</table></div>', $content );
	return $content;
}
add_filter( 'the_content', 'hbg_wrap_tables', 25 );

/** Strip inline JSON-LD from the body when the theme prints the same type (avoids duplicate schema). */
function hbg_dedupe_content_schema( $content ) {
	if ( ! is_singular( 'post' ) || false === stripos( $content, 'application/ld+json' ) ) {
		return $content;
	}
	return preg_replace_callback( '/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', function ( $m ) {
		$json = json_decode( trim( $m[1] ), true );
		if ( ! is_array( $json ) ) {
			return $m[0];
		}
		$types = array();
		array_walk_recursive( $json, function ( $v, $k ) use ( &$types ) {
			if ( '@type' === $k ) {
				$types[] = $v;
			}
		} );
		if ( isset( $json['@type'] ) ) {
			$types[] = $json['@type'];
		}
		if ( ! empty( $json['@graph'] ) ) {
			foreach ( $json['@graph'] as $g ) {
				if ( isset( $g['@type'] ) ) {
					$types[] = $g['@type'];
				}
			}
		}
		// Keep FAQPage/HowTo/Product data the author added; drop duplicated Article/Breadcrumb/Organization/WebSite.
		$drop = array( 'Article', 'BlogPosting', 'NewsArticle', 'BreadcrumbList', 'Organization', 'WebSite', 'WebPage' );
		$keep = array_diff( array_unique( $types ), $drop );
		if ( $keep && array_intersect( $types, $drop ) && ! empty( $json['@graph'] ) ) {
			$json['@graph'] = array_values( array_filter( $json['@graph'], function ( $g ) use ( $drop ) { return ! isset( $g['@type'] ) || ! in_array( $g['@type'], $drop, true ); } ) );
			return '<script type="application/ld+json">' . wp_json_encode( $json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
		}
		if ( ! $keep ) {
			return '';
		}
		return $m[0];
	}, $content );
}
add_filter( 'the_content', 'hbg_dedupe_content_schema', 30 );

// =========================================================================
// Body classes, excerpts, search scope, robots
// =========================================================================
function hbg_body_classes( $classes ) {
	$classes[] = 'hbg-theme';
	$classes[] = 'hbg-v3';
	if ( is_singular( 'post' ) || ( ( is_home() || is_archive() || is_search() ) && 'none' !== get_theme_mod( 'hbg_sidebar_position', 'right' ) ) ) {
		$classes[] = 'has-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'hbg_body_classes' );

function hbg_excerpt_length( $length ) {
	return 26;
}
add_filter( 'excerpt_length', 'hbg_excerpt_length', 999 );

function hbg_excerpt_more( $more ) {
	return '…';
}
add_filter( 'excerpt_more', 'hbg_excerpt_more' );

function hbg_search_filter( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$query->set( 'post_type', array( 'post', 'page' ) );
	}
}
add_action( 'pre_get_posts', 'hbg_search_filter' );

/** Search results, author, date and attachment views are not useful in search engines. */
function hbg_robots( $robots ) {
	if ( is_search() || is_author() || is_date() || is_attachment() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['max-image-preview'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'hbg_robots' );

// =========================================================================
// Legacy subscriber list (v2 stored sign-ups in an option). Kept read-only
// so nothing is lost; the v3 form only posts to an external service.
// =========================================================================
function hbg_subscribers_menu() {
	if ( ! get_option( 'hbg_subscribers' ) ) {
		return;
	}
	add_theme_page( __( 'Newsletter Subscribers', 'hoverboards-guide' ), __( 'Subscribers (legacy)', 'hoverboards-guide' ), 'manage_options', 'hbg-subscribers', 'hbg_subscribers_page' );
}
add_action( 'admin_menu', 'hbg_subscribers_menu' );

function hbg_subscribers_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$list       = get_option( 'hbg_subscribers', array() );
	$list       = is_array( $list ) ? array_reverse( $list, true ) : array();
	$export_url = wp_nonce_url( admin_url( 'admin-post.php?action=hbg_export_subscribers' ), 'hbg_export' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Newsletter Subscribers (legacy)', 'hoverboards-guide' ); ?> <span class="count">(<?php echo esc_html( number_format_i18n( count( $list ) ) ); ?>)</span></h1>
		<p><?php esc_html_e( 'Emails captured by the v2 theme forms. v3 only shows a newsletter form when an external service URL is set under Customize → Hoverboards Guide → Newsletter, so import these into that service.', 'hoverboards-guide' ); ?></p>
		<p><a href="<?php echo esc_url( $export_url ); ?>" class="button button-primary"><?php esc_html_e( 'Export CSV', 'hoverboards-guide' ); ?></a></p>
		<table class="widefat striped" style="max-width:720px">
			<thead><tr><th><?php esc_html_e( 'Email', 'hoverboards-guide' ); ?></th><th><?php esc_html_e( 'Source', 'hoverboards-guide' ); ?></th><th><?php esc_html_e( 'Date', 'hoverboards-guide' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( array_slice( $list, 0, 500, true ) as $email => $row ) : ?>
				<tr><td><?php echo esc_html( $email ); ?></td><td><?php echo esc_html( $row['source'] ); ?></td><td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' H:i', (int) $row['time'] ) ); ?></td></tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

function hbg_export_subscribers() {
	if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '', 'hbg_export' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'hoverboards-guide' ) );
	}
	$list = get_option( 'hbg_subscribers', array() );
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=subscribers-' . gmdate( 'Y-m-d' ) . '.csv' );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array( 'email', 'source', 'date' ) );
	foreach ( (array) $list as $email => $row ) {
		fputcsv( $out, array( $email, $row['source'], gmdate( 'Y-m-d H:i', (int) $row['time'] ) ) );
	}
	fclose( $out ); // phpcs:ignore
	exit;
}
add_action( 'admin_post_hbg_export_subscribers', 'hbg_export_subscribers' );

// =========================================================================
// Imported-article normaliser. 166 articles start with an inline-styled purple
// "hero" (UPDATED badge + duplicate H1 + lede) and an "#ad" strip, and carry
// typography in inline styles that fights the theme. This filter removes the
// badge and strip, keeps the lede as a normal paragraph and strips only
// typography declarations (font, colour, line-height) from headings, paragraphs
// and lists. Layout styles on boxes are left alone. Off switch: Customizer →
// Hoverboards Guide → Articles → "Normalise imported article styling".
// =========================================================================
function hbg_normalise_imported_markup( $content ) {
	if ( ! is_singular() || ! get_theme_mod( 'hbg_clean_imported', true ) ) {
		return $content;
	}
	if ( false === strpos( $content, 'style=' ) ) {
		return $content;
	}
	// 1. Outer wrapper: keep the div, drop its font/width inline style.
	$content = preg_replace( '/^(\s*<div)\s+style="[^"]*font-family[^"]*max-width:860px[^"]*"/i', '$1 class="imported-article"', $content, 1 );
	// 2. Gradient hero: badge + H1 + lede → lede paragraph only (the header already shows title and dates).
	$content = preg_replace_callback( '/<div style="[^"]*linear-gradient[^"]*"[^>]*>\s*<div[^>]*>\s*UPDATED[^<]*<\/div>\s*(?:<h1[^>]*>.*?<\/h1>)?\s*(?:<p[^>]*>(.*?)<\/p>)?\s*<\/div>/is', function ( $m ) {
		$lede = isset( $m[1] ) ? trim( wp_strip_all_tags( $m[1] ) ) : '';
		return $lede ? '<p class="lede">' . esc_html( html_entity_decode( $lede, ENT_QUOTES, 'UTF-8' ) ) . '</p>' : '';
	}, $content, 1 );
	// 3. "#ad" strip: the theme prints its own disclosure box above the body.
	if ( get_theme_mod( 'hbg_show_disclosure', true ) ) {
		$content = preg_replace( '/<div style="[^"]*"[^>]*>\s*<strong>#ad<\/strong>[^<]*<\/div>/i', '', $content, 1 );
	}
	// 3b. In-content "Table of Contents" boxes (253 articles): keep the links, drop the purple inline styling.
	$content = preg_replace_callback( '/<div style="[^"]*"[^>]*>\s*<div[^>]*>\s*(?:[^<]*?)Table of Contents\s*<\/div>\s*(<ol.*?<\/ol>)\s*<\/div>/is', function ( $m ) {
		$list = preg_replace( '/\s+style="[^"]*"/i', '', $m[1] );
		return '<nav class="imported-toc" aria-label="' . esc_attr__( 'Table of contents', 'hoverboards-guide' ) . '"><div class="imported-toc__title">' . esc_html__( 'In this article', 'hoverboards-guide' ) . '</div>' . $list . '</nav>';
	}, $content, 1 );
	// 4. Typography-only inline styles on text elements.
	$content = preg_replace_callback( '/<(h[1-4]|p|li|ul|ol|blockquote)(\s[^>]*?)style="([^"]*)"([^>]*)>/i', function ( $m ) {
		$decls = array_filter( array_map( 'trim', explode( ';', $m[3] ) ) );
		$keep  = array();
		foreach ( $decls as $d ) {
			if ( preg_match( '/^(font|color|line-height|letter-spacing|text-transform)/i', $d ) ) {
				continue;
			}
			$keep[] = $d;
		}
		$style = $keep ? ' style="' . esc_attr( implode( '; ', $keep ) ) . '"' : '';
		return '<' . $m[1] . rtrim( $m[2] ) . $style . $m[4] . '>';
	}, $content );
	return $content;
}
add_filter( 'the_content', 'hbg_normalise_imported_markup', 8 );
