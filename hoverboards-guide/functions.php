<?php
/**
 * Hoverboards Guide — functions and definitions.
 *
 * v3.0 "Magazine": light editorial design system, hoverboard-first information
 * architecture, honest product cards, comparison table, finder, topic hubs,
 * setup tools, product editor, structured data and performance work.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HBG_VERSION', '3.5.0' );
define( 'HBG_DIR', get_template_directory() );
define( 'HBG_URI', get_template_directory_uri() );

require HBG_DIR . '/inc/image-manifest.php';
require HBG_DIR . '/inc/illustrations.php';
require HBG_DIR . '/inc/theme-data.php';
require HBG_DIR . '/inc/template-tags.php';
require HBG_DIR . '/inc/menus.php';
require HBG_DIR . '/inc/engagement.php';
require HBG_DIR . '/inc/shortcodes.php';
require HBG_DIR . '/inc/widgets.php';
require HBG_DIR . '/inc/schema.php';
require HBG_DIR . '/inc/customizer.php';
if ( is_admin() ) {
	require HBG_DIR . '/inc/admin-setup.php';
	require HBG_DIR . '/inc/admin-products.php';
}

// -------------------------------------------------------------------------
// Theme setup
// -------------------------------------------------------------------------
function hbg_setup() {
	load_theme_textdomain( 'hoverboards-guide', HBG_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array( 'height' => 48, 'width' => 220, 'flex-height' => true, 'flex-width' => true ) );
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu (header)', 'hoverboards-guide' ),
		'footer'  => esc_html__( 'Footer Menu', 'hoverboards-guide' ),
	) );
	add_image_size( 'hbg-thumb', 200, 150, true );
	add_image_size( 'hbg-card', 640, 400, true );
	add_image_size( 'hbg-wide', 1024, 576, true );
}
add_action( 'after_setup_theme', 'hbg_setup' );

function hbg_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'hbg_content_width', 760 );
}
add_action( 'after_setup_theme', 'hbg_content_width', 0 );

// -------------------------------------------------------------------------
// Widget areas
// -------------------------------------------------------------------------
function hbg_widgets_init() {
	$areas = array(
		'sidebar-1'            => array( __( 'Sidebar', 'hoverboards-guide' ), __( 'Shown on articles, the blog and archives when “Sidebar content” is set to widgets.', 'hoverboards-guide' ) ),
		'home-after-hero'      => array( __( 'Homepage: After Hero', 'hoverboards-guide' ), __( 'Full-width area under the hero.', 'hoverboards-guide' ) ),
		'home-before-footer'   => array( __( 'Homepage: Before Footer', 'hoverboards-guide' ), __( 'Full-width area at the bottom of the homepage.', 'hoverboards-guide' ) ),
		'single-after-content' => array( __( 'Article: After Content', 'hoverboards-guide' ), __( 'Shown after the article body, before related articles.', 'hoverboards-guide' ) ),
		'footer-1'             => array( __( 'Footer Widgets', 'hoverboards-guide' ), __( 'Optional row of widgets above the footer columns.', 'hoverboards-guide' ) ),
	);
	foreach ( $areas as $id => $a ) {
		register_sidebar( array( 'name' => $a[0], 'id' => $id, 'description' => $a[1], 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h3 class="widget-title"><span>', 'after_title' => '</span></h3>' ) );
	}
}
add_action( 'widgets_init', 'hbg_widgets_init' );

// -------------------------------------------------------------------------
// Assets
// -------------------------------------------------------------------------
function hbg_enqueue_assets() {
	$deps = array();
	if ( 'system' !== get_theme_mod( 'hbg_fonts', 'web' ) ) {
		wp_enqueue_style( 'hbg-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap', array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		$deps[] = 'hbg-fonts';
	}
	wp_enqueue_style( 'hbg-theme', HBG_URI . '/assets/css/theme.css', $deps, HBG_VERSION );
	wp_enqueue_script( 'hbg-theme', HBG_URI . '/assets/js/theme.js', array(), HBG_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

	$data = array(
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'hbg_nonce' ),
		'homeUrl'  => home_url( '/' ),
		'postId'   => is_singular( 'post' ) ? get_queried_object_id() : 0,
		'track'    => (bool) get_theme_mod( 'hbg_track_events', true ),
		'i18n'     => array(
			'copied'   => __( 'Link copied', 'hoverboards-guide' ),
			'loading'  => __( 'Loading…', 'hoverboards-guide' ),
			'loadMore' => __( 'Load more articles', 'hoverboards-guide' ),
			'noMore'   => __( 'That’s everything for now', 'hoverboards-guide' ),
			'error'    => __( 'Something went wrong. Please try again.', 'hoverboards-guide' ),
			'checkPrice' => __( 'Check price on Amazon', 'hoverboards-guide' ),
			'review'   => __( 'View full review', 'hoverboards-guide' ),
			'advertised' => __( 'Specs as advertised by the manufacturer.', 'hoverboards-guide' ),
			'verified' => __( 'Specs checked against the manufacturer listing.', 'hoverboards-guide' ),
		),
	);
	if ( is_front_page() || is_page() || is_singular( 'post' ) ) {
		$products = array();
		foreach ( hbg_get_products() as $id => $p ) {
			$products[ $id ] = array(
				'id'       => $id,
				'name'     => $p['name'],
				'badge'    => isset( $p['badge'] ) ? $p['badge'] : '',
				'why'      => isset( $p['why'] ) ? $p['why'] : '',
				'best_for' => isset( $p['best_for'] ) ? $p['best_for'] : '',
				'url'      => hbg_amazon_url( $p['search'] ),
				'review'   => hbg_product_review_url( $p ),
				'image'    => hbg_product_image( $p, array( 'echo' => false, 'class' => 'product-image', 'ratio' => '4/3', 'sizes' => '(max-width: 640px) 100vw, 320px' ) ),
				'verified' => ! empty( $p['verified'] ),
				'specs'    => array(
					array( 'label' => __( 'Wheels', 'hoverboards-guide' ), 'value' => $p['wheel'] ),
					array( 'label' => __( 'Max rider', 'hoverboards-guide' ), 'value' => $p['weight_cap'] ),
					array( 'label' => __( 'Top speed', 'hoverboards-guide' ), 'value' => $p['speed'] ),
					array( 'label' => __( 'Range', 'hoverboards-guide' ), 'value' => $p['range'] ),
				),
			);
		}
		$guides = array();
		foreach ( hbg_key_posts() as $key => $slug ) {
			$post = hbg_post_by_slug( $slug );
			if ( $post ) {
				$guides[ $key ] = array( 'title' => get_the_title( $post ), 'url' => get_permalink( $post ) );
			}
		}
		$data['products'] = $products;
		$data['guides']   = $guides;
		$data['finder']   = array( 'keys' => wp_list_pluck( hbg_finder_questions(), 'key' ), 'rules' => hbg_finder_rules() );
	}
	wp_localize_script( 'hbg-theme', 'hbgData', $data );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hbg_enqueue_assets' );

function hbg_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && 'system' !== get_theme_mod( 'hbg_fonts', 'web' ) ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'hbg_resource_hints', 10, 2 );

// Remove block global styles (they inject body max-width rules) and emoji scripts.
add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'global-styles' );
	wp_deregister_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/** Accent colour variable and a preload for the homepage hero image. */
function hbg_head_inline() {
	$accent = get_theme_mod( 'hbg_accent_color', '#1d4ed8' );
	echo '<style id="hbg-accent">:root{--accent:' . esc_attr( $accent ) . ';}</style>' . "\n";
	echo '<script>document.documentElement.className=document.documentElement.className.replace("no-js","js")</script>' . "\n";
	if ( is_front_page() ) {
		$hero = (int) get_theme_mod( 'hbg_hero_image', 0 );
		$url  = $hero ? wp_get_attachment_image_url( $hero, 'large' ) : hbg_image_url( 'cat-hoverboards', 800 );
		if ( $url ) {
			echo '<link rel="preload" as="image" href="' . esc_url( $url ) . '" fetchpriority="high">' . "\n";
		}
	}
}
add_action( 'wp_head', 'hbg_head_inline', 1 );

// -------------------------------------------------------------------------
// Activation
// -------------------------------------------------------------------------
function hbg_theme_activation_defaults() {
	update_option( 'hbg_activated', HBG_VERSION );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'hbg_theme_activation_defaults' );

/** Image sizes for responsive cards. */
function hbg_card_size() {
	return 'medium_large';
}
