<?php
/**
 * Hoverboards Guide — functions and definitions.
 *
 * v2.1 "Convert" update: hero with quick picks, tabbed topic + shop sections, most-read strip,
 * cached popular queries, review links on product cards, TOC de-duplication.
 * v2.0 "Rich Magazine" edition: post-driven magazine homepage, category story blocks,
 * sidebar & widgets, reading progress, TOC, share, related posts, view counts,
 * newsletter capture, dark mode, real product registry and bundled royalty-free photos.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HBG_VERSION', '2.1.0' );
define( 'HBG_DIR', get_template_directory() );
define( 'HBG_URI', get_template_directory_uri() );

// -------------------------------------------------------------------------
// Includes
// -------------------------------------------------------------------------
require HBG_DIR . '/inc/image-manifest.php';
require HBG_DIR . '/inc/theme-data.php';
require HBG_DIR . '/inc/template-tags.php';
require HBG_DIR . '/inc/menus.php';
require HBG_DIR . '/inc/engagement.php';
require HBG_DIR . '/inc/shortcodes.php';
require HBG_DIR . '/inc/widgets.php';
require HBG_DIR . '/inc/schema.php';
require HBG_DIR . '/inc/customizer.php';

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
	add_theme_support( 'custom-logo', array( 'height' => 56, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );

	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu (header)', 'hoverboards-guide' ),
		'footer'  => esc_html__( 'Footer Menu', 'hoverboards-guide' ),
	) );

	// Sizes for new uploads. Existing media keeps working through WordPress' built-in sizes.
	add_image_size( 'hbg-thumb', 200, 150, true );
	add_image_size( 'hbg-card', 640, 400, true );
	add_image_size( 'hbg-wide', 1024, 576, true );
}
add_action( 'after_setup_theme', 'hbg_setup' );

function hbg_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'hbg_content_width', 760 );
}
add_action( 'after_setup_theme', 'hbg_content_width', 0 );

/**
 * Prefer the theme's card size when it exists, otherwise fall back to a
 * built-in size so older uploads (created before this theme) still render.
 */
function hbg_card_size() {
	return 'medium_large';
}

// -------------------------------------------------------------------------
// Widget areas
// -------------------------------------------------------------------------
function hbg_widgets_init() {
	$areas = array(
		'sidebar-1'       => array( __( 'Sidebar', 'hoverboards-guide' ), __( 'Shown on posts, the blog and archives. Leave empty to use the theme’s default set (search, most read, newsletter, top picks, categories).', 'hoverboards-guide' ) ),
		'home-after-featured' => array( __( 'Homepage: After Featured', 'hoverboards-guide' ), __( 'Full-width area under the featured stories — good for an ad or announcement.', 'hoverboards-guide' ) ),
		'home-before-footer'  => array( __( 'Homepage: Before Footer', 'hoverboards-guide' ), __( 'Full-width area at the bottom of the homepage.', 'hoverboards-guide' ) ),
		'single-after-content' => array( __( 'Post: After Content', 'hoverboards-guide' ), __( 'Shown after the article body, before comments.', 'hoverboards-guide' ) ),
		'footer-1'        => array( __( 'Footer Widgets', 'hoverboards-guide' ), __( 'Optional row of widgets above the footer columns.', 'hoverboards-guide' ) ),
	);
	foreach ( $areas as $id => $a ) {
		register_sidebar( array(
			'name'          => $a[0],
			'id'            => $id,
			'description'   => $a[1],
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title"><span>',
			'after_title'   => '</span></h3>',
		) );
	}
}
add_action( 'widgets_init', 'hbg_widgets_init' );

// -------------------------------------------------------------------------
// Assets
// -------------------------------------------------------------------------
function hbg_enqueue_assets() {
	wp_enqueue_style( 'hbg-fonts', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Geist:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'hbg-theme', HBG_URI . '/assets/css/theme.css', array( 'hbg-fonts' ), HBG_VERSION );
	wp_enqueue_script( 'hbg-theme', HBG_URI . '/assets/js/theme.js', array(), HBG_VERSION, true );

	$data = array(
		'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
		'nonce'       => wp_create_nonce( 'hbg_nonce' ),
		'homeUrl'     => home_url( '/' ),
		'postId'      => is_singular( 'post' ) ? get_queried_object_id() : 0,
		'accentColor' => get_theme_mod( 'hbg_accent_color', '#FF7A1A' ),
		'i18n'        => array(
			'copied'    => __( 'Link copied', 'hoverboards-guide' ),
			'loading'   => __( 'Loading…', 'hoverboards-guide' ),
			'loadMore'  => __( 'Load more articles', 'hoverboards-guide' ),
			'noMore'    => __( 'You’ve reached the end', 'hoverboards-guide' ),
			'error'     => __( 'Something went wrong. Please try again.', 'hoverboards-guide' ),
			'added'     => __( '✓ Added to compare', 'hoverboards-guide' ),
			'add'       => __( '+ Add to compare', 'hoverboards-guide' ),
			'checkPrice'=> __( 'Check price on Amazon', 'hoverboards-guide' ),
		),
	);

	if ( is_front_page() ) {
		$products = array();
		foreach ( hbg_get_products() as $id => $p ) {
			$products[ $id ] = array(
				'id'      => $id,
				'name'    => $p['name'],
				'tagline' => $p['tagline'],
				'color'   => $p['color'],
				'url'     => hbg_amazon_url( $p['search'] ),
				'image'   => hbg_image_exists( $p['image'] ) ? hbg_image_url( $p['image'], 640 ) : '',
				'note'    => ( ! empty( $p['image_note'] ) && get_theme_mod( 'hbg_show_image_notes', true ) ) ? $p['image_note'] : '',
				'score'   => $p['score'],
				'tier'    => hbg_price_tier_label( $p['tier'] ),
				'specs'   => array(
					array( 'label' => __( 'range', 'hoverboards-guide' ), 'value' => $p['range'] ),
					array( 'label' => __( 'top speed', 'hoverboards-guide' ), 'value' => $p['speed'] ),
					array( 'label' => __( 'max rider', 'hoverboards-guide' ), 'value' => $p['weight_cap'] ),
				),
			);
		}
		$data['products'] = $products;
		$data['quiz']     = array( 'keys' => wp_list_pluck( hbg_get_quiz_questions(), 'key' ), 'rules' => hbg_get_quiz_rules() );
	}
	wp_localize_script( 'hbg-theme', 'hbgData', $data );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hbg_enqueue_assets' );

// Preconnect to the font CDN for faster first paint.
function hbg_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'hbg_resource_hints', 10, 2 );

// Remove the block global-styles sheet — it injects `body { max-width }` rules that break full-width layouts.
add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'global-styles' );
	wp_deregister_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 100 );

// -------------------------------------------------------------------------
// Head: no-js swap, color scheme (before paint) and accent variable
// -------------------------------------------------------------------------
function hbg_head_inline() {
	$accent = get_theme_mod( 'hbg_accent_color', '#FF7A1A' );
	$scheme = get_theme_mod( 'hbg_default_scheme', 'auto' );
	$toggle = get_theme_mod( 'hbg_dark_mode_toggle', true ) ? 'true' : 'false';
	echo '<style id="hbg-accent">:root{--accent:' . esc_attr( $accent ) . ';}</style>' . "\n";
	// Order of precedence: ?scheme=dark|light (preview links) → visitor's saved toggle → Customizer default → OS preference.
	echo '<script>(function(h){h.className=h.className.replace("no-js","js");var s="' . esc_js( $scheme ) . '",t=null,q=/[?&]scheme=(dark|light)\b/.exec(location.search);try{t=localStorage.getItem("hbg-scheme")}catch(e){}if(!' . $toggle . '){t=null}if(q){t=q[1]}if(!t){t=s==="auto"?(window.matchMedia&&window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light"):s}h.setAttribute("data-scheme",t)})(document.documentElement)</script>' . "\n";
}
add_action( 'wp_head', 'hbg_head_inline', 1 );

// -------------------------------------------------------------------------
// Activation: record the version and refresh rewrite rules so pretty
// permalinks keep working after the switch.
// -------------------------------------------------------------------------
function hbg_theme_activation_defaults() {
	update_option( 'hbg_activated', HBG_VERSION );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'hbg_theme_activation_defaults' );
