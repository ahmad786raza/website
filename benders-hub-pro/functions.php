<?php
/**
 * Benders Hub Pro - Theme Functions
 *
 * Editorial magazine + engineering tools theme for tube bending, metal
 * fabrication, exhaust and roll cage work, and workshop equipment guidance.
 *
 * @package BendersHub
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'BDH_VERSION', '2.0.0' );
define( 'BDH_THEME_DIR', get_template_directory() );
define( 'BDH_THEME_URI', get_template_directory_uri() );
define( 'BDH_AMAZON_TAG', 'rcblogs-20' );
define( 'BDH_PHP_PREFIX', 'bdh' );
define( 'BDH_BRAND', 'Benders Hub' );
define( 'BDH_DOMAIN', 'bendershub.com' );

/**
 * The exact disclosure wording required by the Amazon Associates Program
 * Operating Agreement. Kept in one place so every placement stays identical.
 */
define( 'BDH_AMAZON_DISCLOSURE', 'As an Amazon Associate I earn from qualifying purchases.' );

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
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Editor palette mirrors the front-end design tokens.
	add_theme_support( 'editor-color-palette', array(
		array( 'name' => __( 'Graphite', 'benders-hub' ),      'slug' => 'graphite', 'color' => '#16191d' ),
		array( 'name' => __( 'Safety orange', 'benders-hub' ), 'slug' => 'orange',   'color' => '#c2410c' ),
		array( 'name' => __( 'Steel blue', 'benders-hub' ),    'slug' => 'steel',    'color' => '#2f5d8a' ),
		array( 'name' => __( 'Shop floor', 'benders-hub' ),    'slug' => 'floor',    'color' => '#f6f5f2' ),
		array( 'name' => __( 'White', 'benders-hub' ),         'slug' => 'white',    'color' => '#ffffff' ),
	) );

	register_nav_menus( array(
		'primary'   => __( 'Primary Menu', 'benders-hub' ),
		'footer'    => __( 'Footer Menu', 'benders-hub' ),
		'utility'   => __( 'Utility Menu', 'benders-hub' ),
		'tools'     => __( 'Fabrication Tools Menu', 'benders-hub' ),
	) );

	// Image sizes. Card + hero crops share a 3:2 / 16:9 grid so every list looks consistent.
	add_image_size( 'bdh-hero',        1920, 1080, true );
	add_image_size( 'bdh-lead',        1200,  675, true );
	add_image_size( 'bdh-card',         800,  533, true );
	add_image_size( 'bdh-product',      600,  600, true );
	add_image_size( 'bdh-thumb',        400,  267, true );
	add_image_size( 'bdh-blueprint',   1600,  900, true );
}
add_action( 'after_setup_theme', 'bdh_setup' );

/**
 * Content width (article measure).
 */
function bdh_content_width() {
	$GLOBALS['content_width'] = 760;
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
	'template-tags',
	'content-filters',
	'enqueue',
	'customizer',
	'schema',
	'shortcodes',
	'ajax',
	'seo-meta',
	'image-library',
	'trust-pages',
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
		'name'          => __( 'Article Sidebar', 'benders-hub' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Shown beside articles under the table of contents.', 'benders-hub' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s bdh-widget">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title bdh-widget-title">',
		'after_title'   => '</h2>',
	) );

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name'          => sprintf( __( 'Footer Column %d', 'benders-hub' ), $i ),
			'id'            => 'footer-' . $i,
			'before_widget' => '<section id="%1$s" class="widget %2$s bdh-footer-widget">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title bdh-footer-title">',
			'after_title'   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'bdh_register_sidebars' );

/**
 * Body classes
 */
function bdh_body_classes( $classes ) {
	$classes[] = 'bdh-theme';
	$classes[] = 'bdh-magazine';
	if ( is_front_page() ) { $classes[] = 'bdh-home'; }
	if ( is_singular( 'bdh_product' ) ) { $classes[] = 'bdh-product-page'; }
	if ( is_singular() && bdh_post_has_tool( get_queried_object_id() ) ) { $classes[] = 'bdh-has-tool'; }
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
 * Optional font preloads. Off by default: in throttled mobile tests, preloading
 * competed with the stylesheet and delayed first paint (fonts use font-display: swap).
 * v1 preloaded a hard-coded Google Fonts URL that returned 404.
 * Example: add_filter( 'bdh_preload_fonts', fn() => array( 'archivo-latin.woff2' ) );
 */
function bdh_preload_assets() {
	foreach ( (array) apply_filters( 'bdh_preload_fonts', array() ) as $font ) {
		printf(
			'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>' . "\n",
			esc_url( BDH_THEME_URI . '/assets/fonts/' . basename( $font ) )
		);
	}
}
add_action( 'wp_head', 'bdh_preload_assets', 1 );

/**
 * Default homepage sections (for Customizer).
 *
 * v2 replaces the 17-section tool stack with a magazine layout. All tools
 * still render (inside the Workshop Tools console) so every existing
 * #bdh-quiz / #bdh-calculator / ... link keeps working.
 */
function bdh_default_homepage_sections() {
	return array(
		'hero'       => array( 'label' => 'Lead story',                      'enabled' => 1, 'order' => 10 ),
		'tools'      => array( 'label' => 'Workshop tools console',          'enabled' => 1, 'order' => 20 ),
		'guides'     => array( 'label' => 'Featured buying guides',          'enabled' => 1, 'order' => 30 ),
		'equipment'  => array( 'label' => 'Equipment by need',               'enabled' => 1, 'order' => 40 ),
		'latest'     => array( 'label' => 'Latest articles',                 'enabled' => 1, 'order' => 50 ),
		'pathways'   => array( 'label' => 'Learn by project',                'enabled' => 1, 'order' => 60 ),
		'knowledge'  => array( 'label' => 'Technical knowledge hub',         'enabled' => 1, 'order' => 70 ),
		'curated'    => array( 'label' => 'Essential reads',                 'enabled' => 1, 'order' => 80 ),
		'faq'        => array( 'label' => 'FAQ',                             'enabled' => 1, 'order' => 90 ),
		'newsletter' => array( 'label' => 'Newsletter',                      'enabled' => 1, 'order' => 100 ),
		'trust'      => array( 'label' => 'How we work (editorial policy)',  'enabled' => 1, 'order' => 110 ),
		'kit_bundles'=> array( 'label' => 'Kit bundle checklists',           'enabled' => 0, 'order' => 120 ),
	);
}

/**
 * Read homepage section settings (merged with defaults).
 *
 * v2 stores its layout under a new key so a v1 layout saved in the
 * Customizer cannot hide the new sections; the v1 value is left untouched
 * for rollback.
 */
function bdh_get_homepage_sections() {
	$defaults = bdh_default_homepage_sections();
	$saved    = get_theme_mod( 'bdh_home_layout_v2', array() );
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
