<?php
/**
 * Content filters — heading anchors/TOC, responsive tables, affiliate link hygiene.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Headings collected while filtering the main post content (for the TOC).
 */
$GLOBALS['bdh_toc_items'] = array();

/**
 * Add stable id attributes to h2/h3 headings in the main content and record them for the TOC.
 * Existing ids (e.g. set in the block editor's "HTML anchor" field) are kept.
 */
function bdh_heading_anchors( $content ) {
	if ( ! is_singular() || ! in_the_loop() || ! is_main_query() ) { return $content; }
	$used  = array();
	$items = array();
	$content = preg_replace_callback(
		'#<h([23])(\s[^>]*)?>(.*?)</h\1>#is',
		function ( $m ) use ( &$used, &$items ) {
			$level = (int) $m[1];
			$attrs = isset( $m[2] ) ? $m[2] : '';
			$text  = trim( wp_strip_all_tags( $m[3] ) );
			if ( '' === $text ) { return $m[0]; }
			// Skip headings that belong to embedded tools/components.
			if ( preg_match( '/class=["\'][^"\']*\b(?:bdh-(?!prose)[a-z-]*(?:title|head|q)|bhg-faq-q)\b/i', $attrs ) ) { return $m[0]; }
			if ( preg_match( '/\sid=["\']([^"\']+)["\']/i', $attrs, $idm ) ) {
				$id = $idm[1];
			} else {
				$base = sanitize_title( $text );
				$base = $base ? $base : 'section';
				$id   = $base;
				$n    = 2;
				while ( isset( $used[ $id ] ) ) { $id = $base . '-' . $n++; }
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;
			$items[]     = array( 'level' => $level, 'id' => $id, 'text' => $text );
			return '<h' . $level . $attrs . '>' . $m[3] . '</h' . $level . '>';
		},
		$content
	);
	$GLOBALS['bdh_toc_items'] = $items;
	return $content;
}
add_filter( 'the_content', 'bdh_heading_anchors', 15 );

/**
 * Render the table of contents collected by bdh_heading_anchors().
 * Only shown for longer articles (3+ sections).
 */
function bdh_toc( $context = 'rail' ) {
	$items = isset( $GLOBALS['bdh_toc_items'] ) ? $GLOBALS['bdh_toc_items'] : array();
	if ( count( $items ) < 3 ) { return; }
	$list = '<ol class="bdh-toc-list">';
	foreach ( $items as $item ) {
		$list .= sprintf( '<li class="bdh-toc-l%d"><a href="#%s">%s</a></li>', (int) $item['level'], esc_attr( $item['id'] ), esc_html( $item['text'] ) );
	}
	$list .= '</ol>';
	if ( 'inline' === $context ) {
		echo '<details class="bdh-toc bdh-toc--inline"><summary>' . bdh_icon( 'list' ) . '<span>' . esc_html__( 'In this article', 'benders-hub' ) . '</span>' . bdh_icon( 'chevron', 'bdh-icon bdh-toc-chev' ) . '</summary>' . $list . '</details>'; // phpcs:ignore WordPress.Security.EscapeOutput
	} else {
		echo '<nav class="bdh-toc bdh-toc--rail" aria-label="' . esc_attr__( 'Table of contents', 'benders-hub' ) . '" data-bdh-toc><p class="bdh-rail-title">' . esc_html__( 'In this article', 'benders-hub' ) . '</p>' . $list . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

/**
 * Wrap tables so wide spec/comparison tables scroll inside the column on phones
 * instead of pushing the whole page sideways.
 */
function bdh_wrap_tables( $content ) {
	if ( false === stripos( $content, '<table' ) ) { return $content; }
	return preg_replace_callback(
		'#(<figure[^>]*class="[^"]*wp-block-table[^"]*"[^>]*>)?\s*(<table\b.*?</table>)#is',
		function ( $m ) {
			if ( ! empty( $m[1] ) ) { return $m[0]; } // Block tables already have a scrolling figure.
			return '<div class="bdh-table-scroll" role="region" aria-label="' . esc_attr__( 'Scrollable table', 'benders-hub' ) . '" tabindex="0">' . $m[2] . '</div>';
		},
		$content
	);
}
add_filter( 'the_content', 'bdh_wrap_tables', 20 );

/**
 * Amazon link hygiene for content links.
 *
 * - Adds our tracking tag only when a link has none (never replaces an existing tag).
 * - Merges rel="nofollow sponsored noopener" into any existing rel instead of
 *   writing a second rel attribute (v1 produced duplicate attributes).
 * - Opens in a new tab unless the author set a target.
 */
function bdh_filter_amazon_links( $content ) {
	if ( is_admin() || is_feed() || false === stripos( $content, 'amazon.' ) && false === stripos( $content, 'amzn.to' ) ) { return $content; }

	if ( class_exists( 'WP_HTML_Tag_Processor' ) ) {
		$p = new WP_HTML_Tag_Processor( $content );
		while ( $p->next_tag( 'a' ) ) {
			$href = (string) $p->get_attribute( 'href' );
			if ( ! $href || ! bdh_is_amazon_url( html_entity_decode( $href ) ) ) { continue; }
			$p->set_attribute( 'href', bdh_amazon_product_url( html_entity_decode( $href ) ) );
			$rel  = preg_split( '/\s+/', strtolower( trim( (string) $p->get_attribute( 'rel' ) ) ) );
			$rel  = array_unique( array_filter( array_merge( $rel, array( 'nofollow', 'sponsored', 'noopener' ) ) ) );
			$p->set_attribute( 'rel', implode( ' ', $rel ) );
			if ( null === $p->get_attribute( 'target' ) ) { $p->set_attribute( 'target', '_blank' ); }
			$p->add_class( 'bdh-affiliate-link' );
		}
		return $p->get_updated_html();
	}

	// Fallback for WordPress < 6.2: tag only, leave other attributes alone.
	return preg_replace_callback(
		'#href=(["\'])(https?://(?:www\.)?amazon\.[a-z.]+/[^"\']+)\1#i',
		function ( $m ) {
			return 'href=' . $m[1] . esc_url( bdh_amazon_product_url( html_entity_decode( $m[2] ) ) ) . $m[1];
		},
		$content
	);
}
add_filter( 'the_content', 'bdh_filter_amazon_links', 20 );

/**
 * "Category: Exhaust" → "Exhaust".
 */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

/**
 * Robots: illustrative (seeded, fictional) equipment profiles should not be indexed.
 * Search results are already noindexed by WordPress core.
 */
function bdh_robots( $robots ) {
	if ( is_singular( 'bdh_product' ) && bdh_is_illustrative_product( get_queried_object_id() ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'bdh_robots' );

/**
 * v1's Repair Tool created "equipment" posts for products that do not exist
 * (e.g. "Pro Workshop 20-Ton Hydraulic Bender") with invented scores.
 * They are flagged with _bdh_seed; v2 treats them as illustrative category profiles.
 */
function bdh_is_illustrative_product( $post_id ) {
	return 'bdh_product' === get_post_type( $post_id ) && (bool) get_post_meta( $post_id, '_bdh_seed', true );
}

/**
 * Strip the invented "Editor score: 9.4/10." sentence v1 wrote into the body
 * of its illustrative equipment posts.
 */
function bdh_strip_seed_scores( $content ) {
	if ( is_singular( 'bdh_product' ) && bdh_is_illustrative_product( get_the_ID() ) ) {
		$content = preg_replace( '/\s*Editor score:\s*[\d.]+\/10\.?/i', '', $content );
		$content = preg_replace( '/\s+with\s+[a-z]+-grade suitability\./i', '.', $content );
	}
	return $content;
}
add_filter( 'the_content', 'bdh_strip_seed_scores', 9 );

/**
 * Excerpt length/more for cards.
 */
add_filter( 'excerpt_more', function () { return '…'; } );

/**
 * Links between articles that were written before their targets went live.
 * Authors write <a data-bhg-link="target-slug" href="/target-slug/">text</a>.
 * Once the target post (or page) is published the anchor points to its real
 * permalink; until then only the text is shown, so visitors never hit a 404
 * whatever order the drafts are published in.
 */
function bdh_resolve_article_links( $content ) {
	if ( false === strpos( $content, 'data-bhg-link' ) ) { return $content; }
	return preg_replace_callback(
		'#<a\s[^>]*data-bhg-link=["\']([a-z0-9-]+)["\'][^>]*>(.*?)</a>#is',
		function ( $m ) {
			static $cache = array();
			$slug = $m[1];
			if ( ! array_key_exists( $slug, $cache ) ) {
				$target         = get_page_by_path( $slug, OBJECT, array( 'post', 'page' ) );
				$cache[ $slug ] = ( $target && 'publish' === $target->post_status ) ? get_permalink( $target ) : '';
			}
			return $cache[ $slug ] ? '<a href="' . esc_url( $cache[ $slug ] ) . '">' . $m[2] . '</a>' : $m[2];
		},
		$content
	);
}
add_filter( 'the_content', 'bdh_resolve_article_links', 20 );
