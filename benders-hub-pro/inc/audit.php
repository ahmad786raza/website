<?php
/**
 * Self-Audit — Benders Hub → System Audit.
 *
 * Runs a battery of checks against the theme installation
 * and reports pass/warn/fail for every component.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_audit_menu() {
	add_submenu_page(
		'bdh-dashboard',
		__( 'System Audit', 'benders-hub' ),
		__( 'System Audit', 'benders-hub' ),
		'manage_options',
		'bdh-audit',
		'bdh_audit_render'
	);
}
add_action( 'admin_menu', 'bdh_audit_menu', 12 );

/**
 * Run all audit checks.
 * Returns an array of categories, each with rows of [name, status, detail].
 * status: pass | warn | fail
 */
function bdh_run_audit() {
	$results = array();

	/* ----- Files ----- */
	$file_checks = array(
		'style.css'                                 => 'Theme stylesheet header',
		'functions.php'                             => 'Theme bootstrap',
		'header.php'                                => 'Header template',
		'footer.php'                                => 'Footer template',
		'index.php'                                 => 'Index fallback',
		'front-page.php'                            => 'Homepage template',
		'404.php'                                   => '404 page (affiliate-first)',
		'page.php'                                  => 'Single page template',
		'single.php'                                => 'Single post template',
		'archive.php'                               => 'Archive template',
		'search.php'                                => 'Search template',
		'searchform.php'                            => 'Search form',
		'sidebar.php'                               => 'Sidebar',
		// inc
		'inc/helpers.php'                           => 'Helpers (icons, badges, options)',
		'inc/enqueue.php'                           => 'Asset enqueue',
		'inc/cpt.php'                               => 'Custom post types',
		'inc/archetypes.php'                        => '12 fabrication archetypes',
		'inc/bundles.php'                           => '8 kit bundles',
		'inc/product-database.php'                  => 'Equipment seed database',
		'inc/amazon.php'                            => 'Amazon keyword bank',
		'inc/customizer.php'                        => 'Customizer panel',
		'inc/schema.php'                            => 'Schema (JSON-LD)',
		'inc/shortcodes.php'                        => 'Shortcodes',
		'inc/ajax.php'                              => 'AJAX endpoints',
		'inc/repair-tool.php'                       => 'Repair Tool admin',
		'inc/seed-content.php'                      => 'Content Library admin',
		'inc/audit.php'                             => 'This audit module',
		// sections
		'template-parts/sections/hero.php'          => 'Hero section',
		'template-parts/sections/quick-start.php'   => 'Quick Start section',
		'template-parts/sections/archetypes.php'    => 'Archetype Explorer',
		'template-parts/sections/editors-best.php'  => 'Editor\'s Best',
		'template-parts/sections/kit-bundles.php'   => 'Kit Bundles',
		'template-parts/sections/comparison.php'    => 'Comparison Table',
		'template-parts/sections/faq.php'           => 'FAQ Accordion',
		'template-parts/sections/newsletter.php'    => 'Newsletter CTA',
		'template-parts/sections/trust.php'         => 'Trust Section',
		// tools
		'template-parts/tools/quiz.php'             => 'Tool 1: Quiz',
		'template-parts/tools/calculator.php'       => 'Tool 2: Calculator',
		'template-parts/tools/visualizer.php'       => 'Tool 3: Visualizer',
		'template-parts/tools/database.php'         => 'Tool 4: Equipment Database',
		'template-parts/tools/workflow.php'         => 'Tool 5: Workflow',
		'template-parts/tools/compatibility.php'    => 'Tool 6: Compatibility',
		'template-parts/tools/prioritizer.php'      => 'Tool 7: Prioritizer',
		'template-parts/tools/tracker.php'          => 'Tool 8: Tracker',
		// parts
		'template-parts/equipment-card.php'         => 'Equipment card',
		'template-parts/product-box.php'            => 'Product box (shortcode)',
		// CSS
		'assets/css/base.css'                       => 'CSS: base',
		'assets/css/layout.css'                     => 'CSS: layout',
		'assets/css/components.css'                 => 'CSS: components',
		'assets/css/hero.css'                       => 'CSS: hero',
		'assets/css/tools.css'                      => 'CSS: tools',
		'assets/css/product.css'                    => 'CSS: product',
		'assets/css/mobile.css'                     => 'CSS: mobile',
		'assets/css/editor.css'                     => 'CSS: editor',
		// JS
		'assets/js/main.js'                         => 'JS: main',
		'assets/js/personalization.js'              => 'JS: personalization',
		'assets/js/animations.js'                   => 'JS: animations',
		'assets/js/quiz.js'                         => 'JS: quiz',
		'assets/js/calculator.js'                   => 'JS: calculator',
		'assets/js/visualizer.js'                   => 'JS: visualizer',
		'assets/js/workflow.js'                     => 'JS: workflow',
		'assets/js/compatibility.js'                => 'JS: compatibility',
		'assets/js/prioritizer.js'                  => 'JS: prioritizer',
		'assets/js/tracker.js'                      => 'JS: tracker',
		'assets/js/database.js'                     => 'JS: database',
	);
	$rows = array();
	$missing = 0;
	foreach ( $file_checks as $path => $label ) {
		$full = BDH_THEME_DIR . '/' . $path;
		$exists = file_exists( $full );
		$size = $exists ? filesize( $full ) : 0;
		if ( ! $exists ) { $missing++; }
		$rows[] = array(
			'name'   => $label,
			'detail' => $path . ( $exists ? ' (' . size_format( $size ) . ')' : '' ),
			'status' => $exists ? ( $size > 0 ? 'pass' : 'warn' ) : 'fail',
		);
	}
	$results['Theme Files (' . ( count( $file_checks ) - $missing ) . '/' . count( $file_checks ) . ')'] = $rows;

	/* ----- Functions ----- */
	$function_checks = array(
		'bdh_setup'                       => 'Theme setup hook',
		'bdh_option'                      => 'Theme option getter',
		'bdh_icon'                        => 'Icon library',
		'bdh_trust_badge'                 => 'Trust badge renderer',
		'bdh_amazon_url'                  => 'Amazon URL builder',
		'bdh_amazon_button'               => 'Amazon CTA button',
		'bdh_quick_start_paths'           => 'Quick start path data',
		'bdh_default_faqs'                => 'Default FAQ data',
		'bdh_get_faqs'                    => 'FAQ getter',
		'bdh_js_data'                     => 'JS localization data',
		'bdh_hero_blueprint_svg'          => 'Hero blueprint SVG',
		'bdh_hero_bend_svg'               => 'Hero bend SVG',
		'bdh_primary_menu_fallback'       => 'Header menu fallback',
		'bdh_footer_menu_fallback'        => 'Footer menu fallback',
		'bdh_fallback_footer_column'      => 'Footer column fallback',
		'bdh_enqueue_assets'              => 'Asset enqueue',
		'bdh_register_cpts'               => 'CPT registration',
		'bdh_archetypes'                  => '12 archetypes data',
		'bdh_bundles'                     => '8 bundles data',
		'bdh_default_equipment'           => 'Equipment seed data',
		'bdh_equipment_categories'        => 'Equipment categories',
		'bdh_amazon_keywords'             => 'Amazon keyword bank',
		'bdh_customize_register'          => 'Customizer registration',
		'bdh_print_schema'                => 'JSON-LD output',
		'bdh_filter_amazon_links'         => 'Affiliate link filter',
		'bdh_get_homepage_sections'       => 'Homepage section getter',
		'bdh_install_seed_article'        => 'Seed article installer',
		'bdh_seed_articles'               => 'Seed article data',
		'bdh_run_audit'                   => 'Audit runner (self)',
	);
	$rows = array();
	$missing_fn = 0;
	foreach ( $function_checks as $fn => $label ) {
		$exists = function_exists( $fn );
		if ( ! $exists ) { $missing_fn++; }
		$rows[] = array(
			'name'   => $label,
			'detail' => $fn . '()',
			'status' => $exists ? 'pass' : 'fail',
		);
	}
	$results['PHP Functions (' . ( count( $function_checks ) - $missing_fn ) . '/' . count( $function_checks ) . ')'] = $rows;

	/* ----- Hooks ----- */
	global $wp_filter;
	$hook_checks = array(
		array( 'after_setup_theme', 'bdh_setup',                 'Theme setup' ),
		array( 'wp_enqueue_scripts', 'bdh_enqueue_assets',        'Asset enqueue' ),
		array( 'init',               'bdh_register_cpts',         'CPT init' ),
		array( 'widgets_init',       'bdh_register_sidebars',     'Sidebar init' ),
		array( 'customize_register', 'bdh_customize_register',    'Customizer init' ),
		array( 'wp_head',            'bdh_print_schema',          'Schema injection' ),
		array( 'wp_head',            'bdh_preload_assets',        'Asset preload' ),
		array( 'the_content',        'bdh_filter_amazon_links',   'Amazon link filter' ),
		array( 'admin_menu',         'bdh_admin_menu',            'Repair menu' ),
		array( 'admin_menu',         'bdh_content_library_menu',  'Content Library menu' ),
		array( 'admin_menu',         'bdh_audit_menu',            'Audit menu' ),
		array( 'wp_ajax_bdh_live_search', 'bdh_ajax_live_search', 'Live search AJAX' ),
		array( 'wp_ajax_bdh_newsletter',  'bdh_ajax_newsletter',  'Newsletter AJAX' ),
		array( 'wp_ajax_bdh_database',    'bdh_ajax_database',    'Database AJAX' ),
	);
	$rows = array();
	foreach ( $hook_checks as $h ) {
		$attached = has_action( $h[0], $h[1] );
		$rows[] = array(
			'name'   => $h[2],
			'detail' => $h[0] . ' → ' . $h[1] . '()' . ( $attached !== false ? ' [priority ' . $attached . ']' : '' ),
			'status' => $attached !== false ? 'pass' : 'fail',
		);
	}
	$results['Action Hooks (' . count( array_filter( $rows, function( $r ) { return $r['status'] === 'pass'; } ) ) . '/' . count( $rows ) . ')'] = $rows;

	/* ----- CPTs and Taxonomies ----- */
	$cpts = array( 'bdh_product' => 'Bending Equipment', 'bdh_guide' => 'Fabrication Guides' );
	$rows = array();
	foreach ( $cpts as $type => $label ) {
		$exists = post_type_exists( $type );
		$count  = $exists ? wp_count_posts( $type )->publish : 0;
		$rows[] = array(
			'name'   => $label . ' CPT',
			'detail' => $type . ' — ' . (int) $count . ' published',
			'status' => $exists ? 'pass' : 'fail',
		);
	}
	$taxes = array( 'bdh_equipment_type' => 'Equipment Type', 'bdh_brand' => 'Brand', 'bdh_archetype_tax' => 'Archetype' );
	foreach ( $taxes as $tax => $label ) {
		$exists = taxonomy_exists( $tax );
		$count  = $exists ? wp_count_terms( $tax ) : 0;
		if ( is_wp_error( $count ) ) { $count = 0; }
		$rows[] = array(
			'name'   => $label . ' taxonomy',
			'detail' => $tax . ' — ' . (int) $count . ' terms',
			'status' => $exists ? 'pass' : 'fail',
		);
	}
	$results['Post Types & Taxonomies'] = $rows;

	/* ----- Shortcodes ----- */
	global $shortcode_tags;
	$shortcodes = array(
		'bdh_quiz', 'bdh_calculator', 'bdh_visualizer', 'bdh_workflow',
		'bdh_compatibility', 'bdh_prioritizer', 'bdh_tracker', 'bdh_database',
		'bdh_amazon', 'bdh_product_box', 'bdh_faq',
	);
	$rows = array();
	foreach ( $shortcodes as $sc ) {
		$registered = isset( $shortcode_tags[ $sc ] );
		$rows[] = array(
			'name'   => '[' . $sc . ']',
			'detail' => $registered ? $shortcode_tags[ $sc ] : 'NOT REGISTERED',
			'status' => $registered ? 'pass' : 'fail',
		);
	}
	$results['Shortcodes (' . count( array_filter( $rows, function( $r ) { return $r['status'] === 'pass'; } ) ) . '/' . count( $rows ) . ')'] = $rows;

	/* ----- Menu locations ----- */
	$nav_locations = get_registered_nav_menus();
	$assignments   = get_theme_mod( 'nav_menu_locations', array() );
	$rows = array();
	foreach ( $nav_locations as $loc => $label ) {
		$has = ! empty( $assignments[ $loc ] );
		$rows[] = array(
			'name'   => $label,
			'detail' => 'Location: ' . $loc . ( $has ? ' → menu #' . $assignments[ $loc ] : ' (unassigned)' ),
			'status' => $has ? 'pass' : 'warn',
		);
	}
	$results['Menus'] = $rows;

	/* ----- Sidebars ----- */
	global $wp_registered_sidebars;
	$sidebars = array( 'sidebar-1', 'footer-1', 'footer-2', 'footer-3', 'footer-4' );
	$rows = array();
	foreach ( $sidebars as $sb ) {
		$registered = isset( $wp_registered_sidebars[ $sb ] );
		$active     = is_active_sidebar( $sb );
		$status     = ! $registered ? 'fail' : ( $active ? 'pass' : 'warn' );
		$rows[] = array(
			'name'   => $sb,
			'detail' => $registered ? ( $active ? 'Active (has widgets)' : 'Registered, no widgets — fallback used' ) : 'Not registered',
			'status' => $status,
		);
	}
	$results['Sidebars'] = $rows;

	/* ----- Pages ----- */
	$pages_expected = array(
		'home' => 'Homepage',
		'about' => 'About',
		'editorial-standards' => 'Editorial Standards',
		'methodology' => 'Testing Methodology',
		'affiliate-disclosure' => 'Affiliate Disclosure',
		'privacy-policy' => 'Privacy Policy',
		'terms' => 'Terms',
		'cookie-policy' => 'Cookie Policy',
		'contact' => 'Contact',
		'safety' => 'Safety',
		'glossary' => 'Glossary',
	);
	$rows = array();
	foreach ( $pages_expected as $slug => $label ) {
		$page = get_page_by_path( $slug );
		$rows[] = array(
			'name'   => $label,
			'detail' => '/' . $slug . '/ — ' . ( $page ? 'page #' . $page->ID : 'not created (run Repair → Recreate Legal Pages)' ),
			'status' => $page ? 'pass' : 'warn',
		);
	}
	$results['Pages'] = $rows;

	/* ----- Seed articles ----- */
	if ( function_exists( 'bdh_seed_articles' ) ) {
		$seed_status = bdh_seed_status();
		$installed = count( array_filter( $seed_status ) );
		$total = count( $seed_status );
		$rows = array(
			array(
				'name'   => 'Seed articles installed',
				'detail' => $installed . ' of ' . $total . ' available articles installed (Content Library)',
				'status' => $installed === $total ? 'pass' : ( $installed > 0 ? 'warn' : 'warn' ),
			),
		);
		foreach ( $seed_status as $slug => $post_id ) {
			$rows[] = array(
				'name'   => $slug,
				'detail' => $post_id ? 'Installed as post #' . $post_id : 'Available (not installed)',
				'status' => $post_id ? 'pass' : 'warn',
			);
		}
		$results['Seed Content'] = $rows;
	}

	/* ----- Customizer (registration verified via WP_Customize_Manager) ----- */
	$customizer_settings = array(
		'bdh_hero_eyebrow', 'bdh_hero_headline', 'bdh_hero_sub',
		'bdh_hero_cta_primary', 'bdh_hero_cta_primary_url',
		'bdh_hero_cta_secondary', 'bdh_hero_cta_secondary_url',
		'bdh_hero_image', 'bdh_hero_video',
		'bdh_quickstart_title', 'bdh_quickstart_sub',
		'bdh_archetypes_title', 'bdh_archetypes_sub',
		'bdh_faq_title', 'bdh_faq_sub', 'bdh_faqs_json',
		'bdh_newsletter_title', 'bdh_newsletter_sub', 'bdh_newsletter_cta',
		'bdh_trust_title', 'bdh_trust_sub',
		'bdh_footer_tagline', 'bdh_show_top_disclosure',
		'bdh_amazon_keyword_bank',
		'bdh_meta_description', 'bdh_og_default_image', 'bdh_twitter_site',
	);

	if ( ! class_exists( 'WP_Customize_Manager' ) ) {
		require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';
	}
	$tmp_customize = new WP_Customize_Manager();
	do_action( 'customize_register', $tmp_customize );

	$rows = array();
	foreach ( $customizer_settings as $key ) {
		$setting = $tmp_customize->get_setting( $key );
		if ( $setting ) {
			$rows[] = array(
				'name'   => $key,
				'detail' => 'Registered. Default: ' . ( is_scalar( $setting->default ) ? wp_trim_words( (string) $setting->default, 8 ) : '(complex)' ),
				'status' => 'pass',
			);
		} else {
			$rows[] = array(
				'name'   => $key,
				'detail' => 'NOT registered in Customizer',
				'status' => 'fail',
			);
		}
	}
	$results['Customizer Settings (' . count( array_filter( $rows, function( $r ) { return $r['status'] === 'pass'; } ) ) . '/' . count( $rows ) . ' registered)'] = $rows;

	/* ----- Schema sanity ----- */
	$rows = array();
	$rows[] = array(
		'name'   => 'Organization schema',
		'detail' => 'wp_head action attached at priority 100',
		'status' => has_action( 'wp_head', 'bdh_print_schema' ) ? 'pass' : 'fail',
	);
	$rows[] = array(
		'name'   => 'FAQ data available',
		'detail' => count( bdh_get_faqs() ) . ' Q&A pairs',
		'status' => count( bdh_get_faqs() ) > 0 ? 'pass' : 'fail',
	);
	$rows[] = array(
		'name'   => 'Equipment data available',
		'detail' => count( bdh_default_equipment() ) . ' seed entries',
		'status' => count( bdh_default_equipment() ) > 0 ? 'pass' : 'fail',
	);
	$rows[] = array(
		'name'   => 'Archetypes data available',
		'detail' => count( bdh_archetypes() ) . ' fabrication archetypes',
		'status' => count( bdh_archetypes() ) >= 12 ? 'pass' : 'fail',
	);
	$rows[] = array(
		'name'   => 'Bundles data available',
		'detail' => count( bdh_bundles() ) . ' kit bundles',
		'status' => count( bdh_bundles() ) >= 8 ? 'pass' : 'fail',
	);
	$results['Data & Schema'] = $rows;

	/* ----- Amazon tag check ----- */
	$rows = array();
	$rows[] = array(
		'name'   => 'Amazon affiliate tag',
		'detail' => 'BDH_AMAZON_TAG = ' . BDH_AMAZON_TAG,
		'status' => BDH_AMAZON_TAG === 'rcblogs-20' ? 'pass' : 'warn',
	);
	$sample_url = bdh_amazon_url( 'tube bender' );
	$rows[] = array(
		'name'   => 'Sample Amazon URL',
		'detail' => $sample_url,
		'status' => ( strpos( $sample_url, 'tag=rcblogs-20' ) !== false ) ? 'pass' : 'fail',
	);
	$rows[] = array(
		'name'   => 'Link filter active',
		'detail' => has_filter( 'the_content', 'bdh_filter_amazon_links' ) !== false ? 'Active on the_content (priority ' . has_filter( 'the_content', 'bdh_filter_amazon_links' ) . ')' : 'NOT active',
		'status' => has_filter( 'the_content', 'bdh_filter_amazon_links' ) !== false ? 'pass' : 'fail',
	);
	$results['Amazon Affiliate System'] = $rows;

	/* ----- Front page setup ----- */
	$rows = array();
	$rows[] = array(
		'name'   => 'show_on_front',
		'detail' => get_option( 'show_on_front' ),
		'status' => get_option( 'show_on_front' ) === 'page' ? 'pass' : 'warn',
	);
	$rows[] = array(
		'name'   => 'page_on_front',
		'detail' => 'Page ID: ' . get_option( 'page_on_front' ),
		'status' => get_option( 'page_on_front' ) ? 'pass' : 'warn',
	);
	$rows[] = array(
		'name'   => 'Homepage section count',
		'detail' => count( bdh_get_homepage_sections() ) . ' sections configured',
		'status' => count( bdh_get_homepage_sections() ) >= 15 ? 'pass' : 'fail',
	);
	$enabled_sections = array_filter( bdh_get_homepage_sections(), function( $s ) { return ! empty( $s['enabled'] ); } );
	$rows[] = array(
		'name'   => 'Enabled homepage sections',
		'detail' => count( $enabled_sections ) . ' of ' . count( bdh_get_homepage_sections() ) . ' sections enabled',
		'status' => count( $enabled_sections ) >= 10 ? 'pass' : 'warn',
	);
	$results['Front Page Configuration'] = $rows;

	/* ----- Tool JS controllers (size + content sanity) ----- */
	$tool_js = array(
		'quiz.js'          => 'Quiz controller',
		'calculator.js'    => 'Bend Radius Calculator',
		'visualizer.js'    => 'Tube Geometry Visualizer',
		'workflow.js'      => 'Workflow Planner',
		'compatibility.js' => 'Compatibility Generator',
		'prioritizer.js'   => 'Efficiency Prioritizer',
		'tracker.js'       => 'Project Tracker',
		'database.js'      => 'Equipment Database filter',
	);
	$rows = array();
	foreach ( $tool_js as $file => $label ) {
		$path = BDH_THEME_DIR . '/assets/js/' . $file;
		if ( ! file_exists( $path ) ) {
			$rows[] = array( 'name' => $label, 'detail' => 'assets/js/' . $file . ' — FILE MISSING', 'status' => 'fail' );
			continue;
		}
		$size = filesize( $path );
		$content = file_get_contents( $path );
		$has_listener = ( strpos( $content, 'addEventListener' ) !== false ) || ( strpos( $content, 'BDH.on' ) !== false ) || ( strpos( $content, 'BDH.delegate' ) !== false );
		$has_dom_hook = strpos( $content, 'data-bdh-' ) !== false || strpos( $content, 'querySelector' ) !== false;
		$status = ( $size > 500 && $has_listener && $has_dom_hook ) ? 'pass' : ( $size > 0 ? 'warn' : 'fail' );
		$rows[] = array(
			'name'   => $label,
			'detail' => 'assets/js/' . $file . ' — ' . size_format( $size ) . ( $has_listener ? ' · binds events' : ' · NO listeners' ) . ( $has_dom_hook ? ' · DOM hooks' : '' ),
			'status' => $status,
		);
	}
	$results['Tool JS Controllers (' . count( array_filter( $rows, function( $r ) { return $r['status'] === 'pass'; } ) ) . '/' . count( $rows ) . ' wired)'] = $rows;

	/* ----- WordPress environment ----- */
	$rows = array();
	$rows[] = array(
		'name'   => 'WordPress version',
		'detail' => get_bloginfo( 'version' ),
		'status' => version_compare( get_bloginfo( 'version' ), '6.0', '>=' ) ? 'pass' : 'warn',
	);
	$rows[] = array(
		'name'   => 'PHP version',
		'detail' => PHP_VERSION,
		'status' => version_compare( PHP_VERSION, '7.4', '>=' ) ? 'pass' : 'fail',
	);
	$rows[] = array(
		'name'   => 'Theme version',
		'detail' => BDH_VERSION,
		'status' => 'pass',
	);
	$rows[] = array(
		'name'   => 'Theme directory',
		'detail' => BDH_THEME_DIR,
		'status' => is_dir( BDH_THEME_DIR ) ? 'pass' : 'fail',
	);
	$rows[] = array(
		'name'   => 'Permalink structure',
		'detail' => get_option( 'permalink_structure' ) ?: '(plain — not recommended)',
		'status' => get_option( 'permalink_structure' ) ? 'pass' : 'warn',
	);
	$results['Environment'] = $rows;

	return $results;
}

/**
 * Render the audit page.
 */
function bdh_audit_render() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$results = bdh_run_audit();

	$totals = array( 'pass' => 0, 'warn' => 0, 'fail' => 0 );
	foreach ( $results as $rows ) {
		foreach ( $rows as $r ) {
			if ( isset( $totals[ $r['status'] ] ) ) { $totals[ $r['status'] ]++; }
		}
	}
	$total_checks = $totals['pass'] + $totals['warn'] + $totals['fail'];

	$badge = function( $status ) {
		$colors = array(
			'pass' => '#00a32a',
			'warn' => '#dba617',
			'fail' => '#d63638',
		);
		$labels = array(
			'pass' => 'PASS',
			'warn' => 'WARN',
			'fail' => 'FAIL',
		);
		$c = $colors[ $status ] ?? '#999';
		$l = $labels[ $status ] ?? '?';
		return '<span style="display:inline-block;padding:3px 10px;border-radius:4px;background:' . $c . ';color:#fff;font-family:monospace;font-size:11px;font-weight:700;letter-spacing:0.08em;">' . $l . '</span>';
	};
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Benders Hub — System Audit', 'benders-hub' ); ?></h1>
		<p><?php esc_html_e( 'Comprehensive health check of every theme component, hook, file, and data source. Run this anytime to verify the system.', 'benders-hub' ); ?></p>

		<div style="display:flex;gap:12px;flex-wrap:wrap;background:#fff;border:1px solid #c3c4c7;padding:20px;margin:16px 0;border-radius:6px;">
			<div style="flex:1;min-width:140px;text-align:center;background:#f0f9f1;border-radius:6px;padding:16px;">
				<div style="font-size:2rem;font-weight:700;color:#00a32a;"><?php echo (int) $totals['pass']; ?></div>
				<div style="color:#646970;font-size:0.85rem;letter-spacing:0.08em;text-transform:uppercase;">Passing</div>
			</div>
			<div style="flex:1;min-width:140px;text-align:center;background:#fcf9e8;border-radius:6px;padding:16px;">
				<div style="font-size:2rem;font-weight:700;color:#dba617;"><?php echo (int) $totals['warn']; ?></div>
				<div style="color:#646970;font-size:0.85rem;letter-spacing:0.08em;text-transform:uppercase;">Warnings</div>
			</div>
			<div style="flex:1;min-width:140px;text-align:center;background:#fcf0f1;border-radius:6px;padding:16px;">
				<div style="font-size:2rem;font-weight:700;color:#d63638;"><?php echo (int) $totals['fail']; ?></div>
				<div style="color:#646970;font-size:0.85rem;letter-spacing:0.08em;text-transform:uppercase;">Failing</div>
			</div>
			<div style="flex:1;min-width:140px;text-align:center;background:#f6f7f7;border-radius:6px;padding:16px;">
				<div style="font-size:2rem;font-weight:700;color:#2271b1;"><?php echo (int) $total_checks; ?></div>
				<div style="color:#646970;font-size:0.85rem;letter-spacing:0.08em;text-transform:uppercase;">Total checks</div>
			</div>
		</div>

		<?php if ( $totals['fail'] === 0 && $totals['warn'] === 0 ) : ?>
			<div class="notice notice-success" style="padding:14px;"><strong><?php esc_html_e( 'All systems green. Site is fully operational.', 'benders-hub' ); ?></strong></div>
		<?php elseif ( $totals['fail'] === 0 ) : ?>
			<div class="notice notice-warning" style="padding:14px;"><strong><?php esc_html_e( 'Operational with warnings. Most warnings are about missing pages or unassigned menus — run Repair Setup to fix.', 'benders-hub' ); ?></strong></div>
		<?php else : ?>
			<div class="notice notice-error" style="padding:14px;"><strong><?php esc_html_e( 'One or more critical checks failed. Inspect the FAIL rows below.', 'benders-hub' ); ?></strong></div>
		<?php endif; ?>

		<?php foreach ( $results as $section_title => $rows ) :
			$section_pass = count( array_filter( $rows, function( $r ) { return $r['status'] === 'pass'; } ) );
			$section_warn = count( array_filter( $rows, function( $r ) { return $r['status'] === 'warn'; } ) );
			$section_fail = count( array_filter( $rows, function( $r ) { return $r['status'] === 'fail'; } ) );
		?>
			<details<?php echo $section_fail > 0 ? ' open' : ''; ?> style="background:#fff;border:1px solid #c3c4c7;border-radius:6px;margin:12px 0;">
				<summary style="cursor:pointer;padding:14px 16px;font-weight:600;font-size:1.05rem;display:flex;justify-content:space-between;align-items:center;">
					<span><?php echo esc_html( $section_title ); ?></span>
					<span style="display:flex;gap:6px;align-items:center;">
						<?php if ( $section_pass ) : ?><span style="color:#00a32a;font-family:monospace;font-size:0.85rem;">●&nbsp;<?php echo (int) $section_pass; ?></span><?php endif; ?>
						<?php if ( $section_warn ) : ?><span style="color:#dba617;font-family:monospace;font-size:0.85rem;">●&nbsp;<?php echo (int) $section_warn; ?></span><?php endif; ?>
						<?php if ( $section_fail ) : ?><span style="color:#d63638;font-family:monospace;font-size:0.85rem;">●&nbsp;<?php echo (int) $section_fail; ?></span><?php endif; ?>
					</span>
				</summary>
				<table class="widefat striped" style="border-radius:0 0 6px 6px;overflow:hidden;">
					<thead><tr><th style="width:30%;">Check</th><th style="width:60%;">Detail</th><th style="width:10%;">Status</th></tr></thead>
					<tbody>
						<?php foreach ( $rows as $r ) : ?>
							<tr>
								<td><strong><?php echo esc_html( $r['name'] ); ?></strong></td>
								<td><code style="background:#f6f7f7;padding:2px 6px;font-size:0.85rem;"><?php echo esc_html( $r['detail'] ); ?></code></td>
								<td><?php echo $badge( $r['status'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</details>
		<?php endforeach; ?>

		<p style="margin-top:24px;color:#646970;font-size:0.9rem;">
			<?php esc_html_e( 'Tip: Many warnings (missing pages, unassigned menus) can be auto-fixed by running Benders Hub → Repair Setup → Run Full Repair.', 'benders-hub' ); ?>
		</p>
	</div>
	<?php
}
