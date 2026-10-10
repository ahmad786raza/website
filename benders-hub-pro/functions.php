<?php
/**
 * Benders Hub Pro - Theme Functions
 *
 * Premium fabrication engineering ecosystem for tube benders, pipe benders,
 * hydraulic benders, CNC bending equipment, and metalworking systems.
 *
 * @package BendersHub
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'BDH_VERSION', '1.0.0' );
define( 'BDH_THEME_DIR', get_template_directory() );
define( 'BDH_THEME_URI', get_template_directory_uri() );
define( 'BDH_AMAZON_TAG', 'rcblogs-20' );
define( 'BDH_PHP_PREFIX', 'bdh' );
define( 'BDH_BRAND', 'Benders Hub' );
define( 'BDH_DOMAIN', 'bendershub.com' );

/**
 * Theme Setup
 */
function bdh_setup() {
	load_theme_textdomain( 'benders-hub', BDH_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	register_nav_menus( array(
		'primary'   => __( 'Primary Menu', 'benders-hub' ),
		'footer'    => __( 'Footer Menu', 'benders-hub' ),
		'utility'   => __( 'Utility Menu', 'benders-hub' ),
		'tools'     => __( 'Fabrication Tools Menu', 'benders-hub' ),
	) );

	add_image_size( 'bdh-hero',        1920, 1080, true );
	add_image_size( 'bdh-card',         800,  600, true );
	add_image_size( 'bdh-product',      600,  600, true );
	add_image_size( 'bdh-thumb',        400,  300, true );
	add_image_size( 'bdh-blueprint',   1600,  900, true );
}
add_action( 'after_setup_theme', 'bdh_setup' );

/**
 * Content width
 */
function bdh_content_width() {
	$GLOBALS['content_width'] = 1280;
}
add_action( 'after_setup_theme', 'bdh_content_width', 0 );

/**
 * Includes
 */
$bdh_includes = array(
	'helpers',
	'amazon',
	'archetypes',
	'bundles',
	'product-database',
	'cpt',
	'enqueue',
	'customizer',
	'schema',
	'shortcodes',
	'ajax',
	'seo-meta',
	'repair-tool',
	'seed-content',
	'audit',
);
foreach ( $bdh_includes as $bdh_inc ) {
	$bdh_path = BDH_THEME_DIR . '/inc/' . $bdh_inc . '.php';
	if ( file_exists( $bdh_path ) ) {
		require_once $bdh_path;
	}
}

/**
 * Sidebars
 */
function bdh_register_sidebars() {
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'benders-hub' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar for posts and pages.', 'benders-hub' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s bdh-widget">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title bdh-widget-title">',
		'after_title'   => '</h3>',
	) );

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name'          => sprintf( __( 'Footer Column %d', 'benders-hub' ), $i ),
			'id'            => 'footer-' . $i,
			'before_widget' => '<section id="%1$s" class="widget %2$s bdh-footer-widget">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title bdh-footer-widget-title">',
			'after_title'   => '</h4>',
		) );
	}
}
add_action( 'widgets_init', 'bdh_register_sidebars' );

/**
 * Body classes
 */
function bdh_body_classes( $classes ) {
	$classes[] = 'bdh-theme';
	$classes[] = 'bdh-industrial';
	if ( is_front_page() ) { $classes[] = 'bdh-home'; }
	if ( is_singular( 'bdh_product' ) ) { $classes[] = 'bdh-product-page'; }
	return $classes;
}
add_filter( 'body_class', 'bdh_body_classes' );

/**
 * Security & cleanup
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Disable emojis for performance
 */
function bdh_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'bdh_disable_emojis' );

/**
 * Preload critical hero assets
 */
function bdh_preload_assets() {
	if ( is_front_page() ) {
		$hero_img = bdh_option( 'hero_image' );
		if ( $hero_img ) {
			echo '<link rel="preload" as="image" href="' . esc_url( $hero_img ) . '" fetchpriority="high">' . "\n";
		}
		echo '<link rel="preload" as="font" type="font/woff2" href="https://fonts.gstatic.com/s/inter/v13/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMa1ZL7.woff2" crossorigin>' . "\n";
	}
}
add_action( 'wp_head', 'bdh_preload_assets', 1 );

/**
 * Affiliate link filter to ensure tag presence
 */
function bdh_filter_amazon_links( $content ) {
	if ( is_admin() || is_feed() ) { return $content; }
	$tag = BDH_AMAZON_TAG;
	$content = preg_replace_callback(
		'#href=([\"\'])(https?://(?:www\.)?amazon\.[a-z\.]+/[^\"\']+)\1#i',
		function( $m ) use ( $tag ) {
			$url = $m[2];
			if ( strpos( $url, 'tag=' ) === false ) {
				$url .= ( strpos( $url, '?' ) === false ? '?' : '&' ) . 'tag=' . $tag;
			}
			return 'href=' . $m[1] . esc_url( $url ) . $m[1] . ' rel="nofollow sponsored noopener" target="_blank"';
		},
		$content
	);
	return $content;
}
add_filter( 'the_content', 'bdh_filter_amazon_links', 20 );

/**
 * Default homepage sections (for Customizer)
 */
function bdh_default_homepage_sections() {
	return array(
		'hero'                  => array( 'label' => 'Cinematic Hero',                'enabled' => 1, 'order' => 10 ),
		'quick_start'           => array( 'label' => 'Quick Start Paths',             'enabled' => 1, 'order' => 20 ),
		'quiz'                  => array( 'label' => 'Tube Bender Match Quiz',        'enabled' => 1, 'order' => 30 ),
		'editors_best'          => array( 'label' => 'Editor\'s Best Benders',        'enabled' => 1, 'order' => 40 ),
		'archetypes'            => array( 'label' => 'Fabrication Type Explorer',     'enabled' => 1, 'order' => 50 ),
		'bend_calculator'       => array( 'label' => 'Bend Radius Calculator',        'enabled' => 1, 'order' => 60 ),
		'geometry_visualizer'   => array( 'label' => 'Tube Geometry Visualizer',      'enabled' => 1, 'order' => 70 ),
		'workflow_planner'      => array( 'label' => 'Fabrication Workflow Planner',  'enabled' => 1, 'order' => 80 ),
		'compatibility'         => array( 'label' => 'Tool Compatibility Generator',  'enabled' => 1, 'order' => 90 ),
		'prioritizer'           => array( 'label' => 'Workshop Efficiency Prioritizer','enabled'=> 1, 'order' => 100 ),
		'route_planner'         => array( 'label' => 'Fabrication Route Planner',     'enabled' => 1, 'order' => 110 ),
		'equipment_database'    => array( 'label' => 'Bending Equipment Database',    'enabled' => 1, 'order' => 120 ),
		'kit_bundles'           => array( 'label' => 'Kit Bundles',                   'enabled' => 1, 'order' => 130 ),
		'comparison'            => array( 'label' => 'Comparison Tables',             'enabled' => 1, 'order' => 140 ),
		'faq'                   => array( 'label' => 'FAQ Accordion',                 'enabled' => 1, 'order' => 150 ),
		'newsletter'            => array( 'label' => 'Newsletter CTA',                'enabled' => 1, 'order' => 160 ),
		'trust'                 => array( 'label' => 'Editorial Trust Section',       'enabled' => 1, 'order' => 170 ),
	);
}

/**
 * Read homepage section settings (merged with defaults)
 */
function bdh_get_homepage_sections() {
	$defaults = bdh_default_homepage_sections();
	$saved    = get_theme_mod( 'bdh_homepage_sections', array() );
	if ( ! is_array( $saved ) ) { $saved = array(); }
	$merged = array();
	foreach ( $defaults as $key => $def ) {
		$merged[ $key ] = array(
			'label'   => $def['label'],
			'enabled' => isset( $saved[ $key ]['enabled'] ) ? (int) $saved[ $key ]['enabled'] : $def['enabled'],
			'order'   => isset( $saved[ $key ]['order'] )   ? (int) $saved[ $key ]['order']   : $def['order'],
		);
	}
	uasort( $merged, function( $a, $b ) { return $a['order'] - $b['order']; } );
	return $merged;
}
