<?php
/**
 * Asset enqueue
 *
 * @package BendersHub
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_enqueue_assets() {
	$ver = BDH_VERSION;

	// Google Fonts.
	wp_enqueue_style(
		'bdh-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Rajdhani:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	// Core CSS.
	wp_enqueue_style( 'bdh-base',       BDH_THEME_URI . '/assets/css/base.css',       array(), $ver );
	wp_enqueue_style( 'bdh-layout',     BDH_THEME_URI . '/assets/css/layout.css',     array( 'bdh-base' ), $ver );
	wp_enqueue_style( 'bdh-components', BDH_THEME_URI . '/assets/css/components.css', array( 'bdh-base' ), $ver );
	wp_enqueue_style( 'bdh-hero',       BDH_THEME_URI . '/assets/css/hero.css',       array( 'bdh-base' ), $ver );
	wp_enqueue_style( 'bdh-tools',      BDH_THEME_URI . '/assets/css/tools.css',      array( 'bdh-base' ), $ver );
	wp_enqueue_style( 'bdh-product',    BDH_THEME_URI . '/assets/css/product.css',    array( 'bdh-base' ), $ver );
	wp_enqueue_style( 'bdh-mobile',     BDH_THEME_URI . '/assets/css/mobile.css',     array( 'bdh-base' ), $ver );

	// Core JS — always loaded.
	wp_enqueue_script( 'bdh-main',           BDH_THEME_URI . '/assets/js/main.js',           array(), $ver, true );
	wp_enqueue_script( 'bdh-personalization',BDH_THEME_URI . '/assets/js/personalization.js',array( 'bdh-main' ), $ver, true );
	wp_enqueue_script( 'bdh-animations',     BDH_THEME_URI . '/assets/js/animations.js',     array( 'bdh-main' ), $ver, true );

	// Tool JS — load on every front-end page. Each script no-ops when its DOM shell isn't present.
	// Total combined size is tiny (~15KB minified) and they're deferred, so universal loading is the
	// most reliable strategy: any page that uses [bdh_quiz] / [bdh_calculator] / etc. shortcodes works.
	$tool_scripts = array(
		'bdh-quiz'          => 'quiz.js',
		'bdh-calculator'    => 'calculator.js',
		'bdh-visualizer'    => 'visualizer.js',
		'bdh-workflow'      => 'workflow.js',
		'bdh-compatibility' => 'compatibility.js',
		'bdh-prioritizer'   => 'prioritizer.js',
		'bdh-tracker'       => 'tracker.js',
		'bdh-database'      => 'database.js',
	);
	foreach ( $tool_scripts as $handle => $file ) {
		wp_enqueue_script( $handle, BDH_THEME_URI . '/assets/js/' . $file, array( 'bdh-main' ), $ver, true );
	}

	wp_localize_script( 'bdh-main', 'BDH', bdh_js_data() );

	// Defer non-critical scripts.
	add_filter( 'script_loader_tag', 'bdh_defer_scripts', 10, 2 );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bdh_enqueue_assets' );

function bdh_defer_scripts( $tag, $handle ) {
	$defer = array(
		'bdh-main', 'bdh-personalization', 'bdh-animations',
		'bdh-quiz', 'bdh-calculator', 'bdh-visualizer',
		'bdh-workflow', 'bdh-compatibility', 'bdh-prioritizer',
		'bdh-tracker', 'bdh-database',
	);
	if ( in_array( $handle, $defer, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}

/**
 * Editor styles
 */
function bdh_editor_styles() {
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'bdh_editor_styles' );
