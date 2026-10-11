<?php
/**
 * Asset enqueue
 *
 * v2: one stylesheet, self-hosted fonts (no Google Fonts request), and tool
 * scripts only on pages that actually contain a tool.
 *
 * @package BendersHub
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_enqueue_assets() {
	$ver = BDH_VERSION;

	wp_enqueue_style( 'bdh-magazine', BDH_THEME_URI . '/assets/css/magazine.css', array(), $ver );

	wp_enqueue_script( 'bdh-main',     BDH_THEME_URI . '/assets/js/main.js',     array(), $ver, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_enqueue_script( 'bdh-magazine', BDH_THEME_URI . '/assets/js/magazine.js', array( 'bdh-main' ), $ver, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_localize_script( 'bdh-main', 'BDH', bdh_js_data() );

	// Tool scripts: front page console, tool pages/shortcodes, and the equipment archive.
	$needs_tools = is_front_page() || is_404() || is_post_type_archive( 'bdh_product' ) || ( is_singular() && bdh_post_has_tool( get_queried_object_id() ) );
	$needs_tools = apply_filters( 'bdh_load_tool_scripts', $needs_tools );
	if ( $needs_tools ) {
		$tool_scripts = array(
			'bdh-quiz'          => 'quiz.js',
			'bdh-calculator'    => 'calculator.js',
			'bdh-visualizer'    => 'visualizer.js',
			'bdh-workflow'      => 'workflow.js',
			'bdh-compatibility' => 'compatibility.js',
			'bdh-prioritizer'   => 'prioritizer.js',
			'bdh-tracker'       => 'tracker.js',
			'bdh-database'      => 'database.js',
			'bdh-personalization' => 'personalization.js',
		);
		foreach ( $tool_scripts as $handle => $file ) {
			wp_enqueue_script( $handle, BDH_THEME_URI . '/assets/js/' . $file, array( 'bdh-main' ), $ver, array( 'strategy' => 'defer', 'in_footer' => true ) );
		}
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bdh_enqueue_assets' );

/**
 * Pre-6.3 WordPress ignores the 'strategy' arg; add defer manually there.
 */
function bdh_defer_scripts( $tag, $handle ) {
	global $wp_version;
	if ( version_compare( $wp_version, '6.3', '>=' ) ) { return $tag; }
	if ( 0 === strpos( $handle, 'bdh-' ) && false === strpos( $tag, ' defer' ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'bdh_defer_scripts', 10, 2 );

/**
 * Shortcode tools placed in widgets or page builders: load scripts when a shortcode renders.
 */
function bdh_mark_tool_rendered() {
	if ( did_action( 'wp_enqueue_scripts' ) && ! wp_script_is( 'bdh-quiz', 'enqueued' ) ) {
		foreach ( array( 'quiz', 'calculator', 'visualizer', 'workflow', 'compatibility', 'prioritizer', 'tracker', 'database' ) as $f ) {
			wp_enqueue_script( 'bdh-' . $f, BDH_THEME_URI . '/assets/js/' . $f . '.js', array( 'bdh-main' ), BDH_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
		}
	}
}

/**
 * Editor styles
 */
function bdh_editor_styles() {
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'bdh_editor_styles' );
