<?php
/**
 * Shortcodes — tools, product cards, comparison tables and editorial callouts
 * that editors can drop into any post or page.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Render a tool template part (standalone layout) and make sure its script loads.
 */
function bdh_render_tool( $part ) {
	if ( function_exists( 'bdh_mark_tool_rendered' ) ) { bdh_mark_tool_rendered(); }
	ob_start();
	get_template_part( $part, null, array( 'context' => 'shortcode' ) );
	return ob_get_clean();
}

add_shortcode( 'bdh_quiz',          function () { return bdh_render_tool( 'template-parts/tools/quiz' ); } );
add_shortcode( 'bdh_calculator',    function () { return bdh_render_tool( 'template-parts/tools/calculator' ); } );
add_shortcode( 'bdh_visualizer',    function () { return bdh_render_tool( 'template-parts/tools/visualizer' ); } );
add_shortcode( 'bdh_workflow',      function () { return bdh_render_tool( 'template-parts/tools/workflow' ); } );
add_shortcode( 'bdh_compatibility', function () { return bdh_render_tool( 'template-parts/tools/compatibility' ); } );
add_shortcode( 'bdh_prioritizer',   function () { return bdh_render_tool( 'template-parts/tools/prioritizer' ); } );
add_shortcode( 'bdh_tracker',       function () { return bdh_render_tool( 'template-parts/tools/tracker' ); } );
add_shortcode( 'bdh_database',      function () { return bdh_render_tool( 'template-parts/tools/database' ); } );
add_shortcode( 'bdh_comparison',    function () { return bdh_render_tool( 'template-parts/sections/comparison' ); } );
add_shortcode( 'bdh_faq',           function () { ob_start(); get_template_part( 'template-parts/sections/faq' ); return ob_get_clean(); } );

/** [bdh_amazon keyword="tube bender" label="See options on Amazon"] or url="https://www.amazon.com/dp/…" */
function bdh_sc_amazon( $atts ) {
	$atts = shortcode_atts( array(
		'keyword' => 'tube bender',
		'url'     => '',
		'label'   => '',
		'class'   => 'bdh-btn bdh-btn-primary',
	), $atts, 'bdh_amazon' );
	ob_start();
	bdh_amazon_button( $atts['url'] ? $atts['url'] : $atts['keyword'], $atts['label'], $atts['class'] );
	return ob_get_clean();
}
add_shortcode( 'bdh_amazon', 'bdh_sc_amazon' );

/**
 * [bdh_product name="Pro-Tools 105HD" application="Manual cage and chassis bending"
 *   specs="Round tube: 1/2–2 in OD|Max wall: .134 in|Max angle: 180°" pros="…|…" cons="…|…"
 *   compat="…" best_for="…" alternative="…" url="https://www.amazon.com/dp/…" keyword="…"
 *   evaluation="research|hands-on" source="https://manufacturer…" image="123"]
 *
 * Score/badge attributes from v1's [bdh_product_box] are accepted but ignored:
 * the site no longer prints ratings that aren't backed by testing.
 */
function bdh_sc_product( $atts ) {
	$atts = shortcode_atts( array(
		'name' => '', 'application' => '', 'summary' => '', 'specs' => '', 'pros' => '', 'cons' => '',
		'compat' => '', 'best_for' => '', 'alternative' => '', 'url' => '', 'keyword' => '',
		'evaluation' => 'research', 'source' => '', 'image' => '', 'label' => '',
		'score' => '', 'badge' => '', 'variant' => '',
	), $atts );
	$split = function ( $s ) { return array_values( array_filter( array_map( 'trim', explode( '|', (string) $s ) ) ) ); };
	$specs = array();
	foreach ( $split( $atts['specs'] ) as $pair ) {
		$kv = array_map( 'trim', explode( ':', $pair, 2 ) );
		if ( 2 === count( $kv ) ) { $specs[ $kv[0] ] = $kv[1]; }
	}
	$p = array(
		'name'        => $atts['name'],
		'application' => $atts['application'] ? $atts['application'] : $atts['summary'],
		'specs'       => $specs,
		'pros'        => $split( $atts['pros'] ),
		'cons'        => $split( $atts['cons'] ),
		'compat'      => $atts['compat'],
		'best_for'    => $atts['best_for'],
		'alternative' => $atts['alternative'],
		'url'         => $atts['url'],
		'keyword'     => $atts['keyword'],
		'evaluation'  => 'hands-on' === $atts['evaluation'] ? 'hands-on' : 'research',
		'source'      => $atts['source'],
		'image_id'    => absint( $atts['image'] ),
		'label'       => $atts['label'],
	);
	ob_start();
	get_template_part( 'template-parts/product-card', null, array( 'p' => $p ) );
	return ob_get_clean();
}
add_shortcode( 'bdh_product', 'bdh_sc_product' );
add_shortcode( 'bdh_product_box', 'bdh_sc_product' );

/** [bdh_disclosure] — the Amazon Associates statement as a visible box. */
add_shortcode( 'bdh_disclosure', function () {
	return '<aside class="bdh-disclosure-box">' . bdh_icon( 'info' ) . '<p>' . esc_html( BDH_AMAZON_DISCLOSURE ) . '</p></aside>';
} );

/** [bdh_callout type="safety|tip|note|warning" title="…"]Text[/bdh_callout] */
function bdh_sc_callout( $atts, $content = '' ) {
	$atts  = shortcode_atts( array( 'type' => 'note', 'title' => '' ), $atts );
	$type  = in_array( $atts['type'], array( 'safety', 'tip', 'note', 'warning' ), true ) ? $atts['type'] : 'note';
	$icons = array( 'safety' => 'shield', 'tip' => 'check', 'note' => 'info', 'warning' => 'alert' );
	$title = $atts['title'] ? $atts['title'] : ucfirst( $type );
	return '<aside class="bdh-callout bdh-callout--' . esc_attr( $type ) . '">' . bdh_icon( $icons[ $type ] ) . '<div><p class="bdh-callout-title">' . esc_html( $title ) . '</p>' . wpautop( do_shortcode( wp_kses_post( $content ) ) ) . '</div></aside>';
}
add_shortcode( 'bdh_callout', 'bdh_sc_callout' );

/**
 * [bdh_formula expr="L = CLR × θ" vars="L: arc length (in)|CLR: centerline radius (in)|θ: bend angle in radians"]
 * Formulas always ship with their variable definitions and units.
 */
function bdh_sc_formula( $atts ) {
	$atts = shortcode_atts( array( 'expr' => '', 'vars' => '', 'note' => '' ), $atts );
	if ( ! $atts['expr'] ) { return ''; }
	$html = '<figure class="bdh-formula-block"><p class="bdh-formula">' . esc_html( $atts['expr'] ) . '</p>';
	$vars = array_filter( array_map( 'trim', explode( '|', $atts['vars'] ) ) );
	if ( $vars ) {
		$html .= '<dl class="bdh-formula-vars">';
		foreach ( $vars as $v ) {
			$kv = array_map( 'trim', explode( ':', $v, 2 ) );
			$html .= '<div><dt>' . esc_html( $kv[0] ) . '</dt><dd>' . esc_html( isset( $kv[1] ) ? $kv[1] : '' ) . '</dd></div>';
		}
		$html .= '</dl>';
	}
	if ( $atts['note'] ) { $html .= '<figcaption>' . esc_html( $atts['note'] ) . '</figcaption>'; }
	return $html . '</figure>';
}
add_shortcode( 'bdh_formula', 'bdh_sc_formula' );

/** [bdh_image_credits] — attribution list for every image imported through the image library. */
function bdh_sc_image_credits() {
	$atts = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 200, 'meta_key' => '_bdh_license', 'orderby' => 'title', 'order' => 'ASC' ) );
	if ( ! $atts ) { return '<p>' . esc_html__( 'No library images have been imported yet.', 'benders-hub' ) . '</p>'; }
	$html = '<ul class="bdh-credits">';
	foreach ( $atts as $a ) {
		$html .= '<li><strong>' . esc_html( get_the_title( $a ) ) . '</strong> — ' . bdh_image_credit( $a->ID ) . '</li>';
	}
	return $html . '</ul>';
}
add_shortcode( 'bdh_image_credits', 'bdh_sc_image_credits' );
