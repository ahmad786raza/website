<?php
/**
 * WordPress Customizer settings for Hoverboards Guide.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hbg_sanitize_checkbox( $checked ) {
	return (bool) $checked;
}

function hbg_sanitize_select( $value, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return array_key_exists( $value, $choices ) ? $value : $setting->default;
}

function hbg_sanitize_int( $value ) {
	return absint( $value );
}

/**
 * Compact helper to register a setting + control.
 */
function hbg_cz_add( $wp_customize, $id, $args ) {
	$a = wp_parse_args( $args, array(
		'type'        => 'text',
		'default'     => '',
		'label'       => '',
		'description' => '',
		'section'     => '',
		'choices'     => array(),
		'transport'   => 'refresh',
		'sanitize'    => null,
	) );
	$sanitize = $a['sanitize'];
	if ( ! $sanitize ) {
		switch ( $a['type'] ) {
			case 'checkbox':
				$sanitize = 'hbg_sanitize_checkbox';
				break;
			case 'textarea':
				$sanitize = 'sanitize_textarea_field';
				break;
			case 'select':
				$sanitize = 'hbg_sanitize_select';
				break;
			case 'number':
			case 'dropdown-pages':
			case 'category':
				$sanitize = 'hbg_sanitize_int';
				break;
			case 'url':
				$sanitize = 'esc_url_raw';
				break;
			default:
				$sanitize = 'sanitize_text_field';
		}
	}
	$wp_customize->add_setting( $id, array( 'default' => $a['default'], 'sanitize_callback' => $sanitize, 'transport' => $a['transport'] ) );

	if ( 'category' === $a['type'] ) {
		$cats    = get_categories( array( 'hide_empty' => false ) );
		$choices = array( 0 => __( '— Auto (by slug) —', 'hoverboards-guide' ) );
		foreach ( $cats as $c ) {
			$choices[ $c->term_id ] = $c->name . ' (' . $c->count . ')';
		}
		$wp_customize->add_control( $id, array( 'type' => 'select', 'label' => $a['label'], 'description' => $a['description'], 'section' => $a['section'], 'choices' => $choices ) );
		return;
	}
	$control = array( 'type' => $a['type'], 'label' => $a['label'], 'description' => $a['description'], 'section' => $a['section'] );
	if ( $a['choices'] ) {
		$control['choices'] = $a['choices'];
	}
	if ( 'number' === $a['type'] ) {
		$control['input_attrs'] = array( 'min' => 1, 'max' => 12, 'step' => 1 );
	}
	$wp_customize->add_control( $id, $control );
}

function hbg_customizer_register( $wp_customize ) {

	$wp_customize->add_panel( 'hbg_panel', array( 'title' => __( 'Hoverboards Guide', 'hoverboards-guide' ), 'priority' => 130 ) );

	$sections = array(
		'hbg_brand'      => __( 'Brand, Colors & Dark Mode', 'hoverboards-guide' ),
		'hbg_header'     => __( 'Header & Top Bar', 'hoverboards-guide' ),
		'hbg_social'     => __( 'Social Profiles', 'hoverboards-guide' ),
		'hbg_home'       => __( 'Homepage Sections', 'hoverboards-guide' ),
		'hbg_home_cats'  => __( 'Homepage Categories', 'hoverboards-guide' ),
		'hbg_hero'       => __( 'Homepage Hero', 'hoverboards-guide' ),
		'hbg_affiliate'  => __( 'Affiliate & Products', 'hoverboards-guide' ),
		'hbg_newsletter' => __( 'Newsletter', 'hoverboards-guide' ),
		'hbg_single'     => __( 'Single Post', 'hoverboards-guide' ),
		'hbg_trust'      => __( 'Trust Section', 'hoverboards-guide' ),
		'hbg_cta'        => __( 'Final CTA', 'hoverboards-guide' ),
		'hbg_footer'     => __( 'Footer', 'hoverboards-guide' ),
	);
	$p = 10;
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'hbg_panel', 'priority' => $p ) );
		$p += 10;
	}

	// Brand
	$wp_customize->add_setting( 'hbg_accent_color', array( 'default' => '#FF7A1A', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'hbg_accent_color', array( 'label' => __( 'Accent color', 'hoverboards-guide' ), 'section' => 'hbg_brand' ) ) );
	hbg_cz_add( $wp_customize, 'hbg_dark_mode_toggle', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Show dark-mode toggle in header', 'hoverboards-guide' ), 'section' => 'hbg_brand' ) );
	hbg_cz_add( $wp_customize, 'hbg_default_scheme', array( 'type' => 'select', 'default' => 'auto', 'label' => __( 'Default color scheme', 'hoverboards-guide' ), 'section' => 'hbg_brand', 'choices' => array( 'auto' => __( 'Follow visitor’s system', 'hoverboards-guide' ), 'light' => __( 'Light', 'hoverboards-guide' ), 'dark' => __( 'Dark', 'hoverboards-guide' ) ) ) );
	hbg_cz_add( $wp_customize, 'hbg_brand_year', array( 'default' => gmdate( 'Y' ), 'label' => __( 'Year badge next to logo (blank to hide)', 'hoverboards-guide' ), 'section' => 'hbg_brand' ) );

	// Header
	hbg_cz_add( $wp_customize, 'hbg_show_topbar', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Show top bar (date, trending ticker, social)', 'hoverboards-guide' ), 'section' => 'hbg_header' ) );
	hbg_cz_add( $wp_customize, 'hbg_ticker_source', array( 'type' => 'select', 'default' => 'latest', 'label' => __( 'Trending ticker shows', 'hoverboards-guide' ), 'section' => 'hbg_header', 'choices' => array( 'latest' => __( 'Latest posts', 'hoverboards-guide' ), 'popular' => __( 'Most-viewed posts', 'hoverboards-guide' ) ) ) );
	hbg_cz_add( $wp_customize, 'hbg_header_cta_text', array( 'default' => __( 'Top picks', 'hoverboards-guide' ), 'label' => __( 'Header button text (blank to hide)', 'hoverboards-guide' ), 'section' => 'hbg_header' ) );
	hbg_cz_add( $wp_customize, 'hbg_header_cta_url', array( 'type' => 'url', 'default' => '', 'label' => __( 'Header button URL', 'hoverboards-guide' ), 'description' => __( 'Leave blank to link to the homepage Top Picks section.', 'hoverboards-guide' ), 'section' => 'hbg_header' ) );
	hbg_cz_add( $wp_customize, 'hbg_show_sticky_cta', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Show sticky mobile "Shop top picks" bar on the homepage', 'hoverboards-guide' ), 'section' => 'hbg_header' ) );

	// Social
	foreach ( array( 'facebook' => 'Facebook', 'x' => 'X (Twitter)', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'pinterest' => 'Pinterest', 'tiktok' => 'TikTok' ) as $key => $label ) {
		hbg_cz_add( $wp_customize, 'hbg_social_' . $key, array( 'type' => 'url', 'label' => $label . ' URL', 'section' => 'hbg_social' ) );
	}

	// Homepage sections
	hbg_cz_add( $wp_customize, 'hbg_featured_source', array( 'type' => 'select', 'default' => 'sticky', 'label' => __( 'Featured stories come from', 'hoverboards-guide' ), 'description' => __( 'Sticky posts are always shown first; the rest is filled with the latest posts.', 'hoverboards-guide' ), 'section' => 'hbg_home', 'choices' => array( 'sticky' => __( 'Sticky posts, then latest', 'hoverboards-guide' ), 'latest' => __( 'Latest posts', 'hoverboards-guide' ), 'category' => __( 'A category (choose below)', 'hoverboards-guide' ) ) ) );
	hbg_cz_add( $wp_customize, 'hbg_featured_category', array( 'type' => 'category', 'default' => 0, 'label' => __( 'Featured category', 'hoverboards-guide' ), 'section' => 'hbg_home' ) );
	hbg_cz_add( $wp_customize, 'hbg_latest_count', array( 'type' => 'number', 'default' => 6, 'label' => __( 'Posts in "Latest" grid', 'hoverboards-guide' ), 'section' => 'hbg_home' ) );
	$toggles = array(
		'hbg_show_intro'        => array( __( 'Hero with quick picks', 'hoverboards-guide' ), true ),
		'hbg_show_cat_strip'    => array( __( 'Category tiles', 'hoverboards-guide' ), true ),
		'hbg_show_top_picks'    => array( __( 'Editor’s top picks (products)', 'hoverboards-guide' ), true ),
		'hbg_show_featured'     => array( __( 'Featured stories', 'hoverboards-guide' ), true ),
		'hbg_show_quiz'         => array( __( '60-second quiz', 'hoverboards-guide' ), true ),
		'hbg_show_compare'      => array( __( 'Comparison table', 'hoverboards-guide' ), true ),
		'hbg_show_latest'       => array( __( 'Latest articles', 'hoverboards-guide' ), true ),
		'hbg_show_most_read'    => array( __( 'Most-read strip', 'hoverboards-guide' ), true ),
		'hbg_show_cat_tabs'     => array( __( 'Browse-by-topic tabs', 'hoverboards-guide' ), true ),
		'hbg_show_use_case'     => array( __( 'Shop by use case (tabbed product grids)', 'hoverboards-guide' ), true ),
		'hbg_show_guide'        => array( __( 'Buying guide cards', 'hoverboards-guide' ), true ),
		'hbg_show_newsletter'   => array( __( 'Newsletter banner', 'hoverboards-guide' ), true ),
		'hbg_show_faq'          => array( __( 'FAQ', 'hoverboards-guide' ), true ),
		'hbg_show_trust'        => array( __( 'Why trust us', 'hoverboards-guide' ), true ),
		'hbg_show_final_cta'    => array( __( 'Final CTA', 'hoverboards-guide' ), true ),
		'hbg_show_cat_blocks'   => array( __( 'Legacy (v2.0): category story blocks', 'hoverboards-guide' ), false ),
		'hbg_show_guides_block' => array( __( 'Legacy (v2.0): guides & how-to rows', 'hoverboards-guide' ), false ),
		'hbg_show_hoverboards'  => array( __( 'Legacy (v2.0): hoverboards product row', 'hoverboards-guide' ), false ),
		'hbg_show_scooters'     => array( __( 'Legacy (v2.0): scooters product row', 'hoverboards-guide' ), false ),
	);
	foreach ( $toggles as $id => $t ) {
		hbg_cz_add( $wp_customize, $id, array( 'type' => 'checkbox', 'default' => $t[1], 'label' => $t[0], 'section' => 'hbg_home' ) );
	}
	hbg_cz_add( $wp_customize, 'hbg_home_sidebar', array( 'type' => 'checkbox', 'default' => false, 'label' => __( 'Show the sidebar next to "Latest articles" on the homepage', 'hoverboards-guide' ), 'section' => 'hbg_home' ) );
	hbg_cz_add( $wp_customize, 'hbg_all_articles_url', array( 'type' => 'url', 'default' => '', 'label' => __( '"All articles" button URL', 'hoverboards-guide' ), 'description' => __( 'Defaults to your Posts page (Settings → Reading). The button is hidden when neither is set.', 'hoverboards-guide' ), 'section' => 'hbg_home' ) );

	// Homepage categories
	hbg_cz_add( $wp_customize, 'hbg_cat_strip_slugs', array( 'default' => 'hoverboards,e-scooters,ebikes,skateboards,buying-guide,how-to', 'label' => __( 'Category tiles (comma-separated slugs)', 'hoverboards-guide' ), 'section' => 'hbg_home_cats' ) );
	hbg_cz_add( $wp_customize, 'hbg_cat_block_1', array( 'type' => 'category', 'default' => 0, 'label' => __( 'Story block 1 (default: Hoverboards)', 'hoverboards-guide' ), 'section' => 'hbg_home_cats' ) );
	hbg_cz_add( $wp_customize, 'hbg_cat_block_2', array( 'type' => 'category', 'default' => 0, 'label' => __( 'Story block 2 (default: E Scooters)', 'hoverboards-guide' ), 'section' => 'hbg_home_cats' ) );
	hbg_cz_add( $wp_customize, 'hbg_cat_block_3', array( 'type' => 'category', 'default' => 0, 'label' => __( 'Guides block (default: Buying Guide)', 'hoverboards-guide' ), 'section' => 'hbg_home_cats' ) );
	hbg_cz_add( $wp_customize, 'hbg_cat_block_4', array( 'type' => 'category', 'default' => 0, 'label' => __( 'Extra block (default: eBikes)', 'hoverboards-guide' ), 'section' => 'hbg_home_cats' ) );

	// Hero
	hbg_cz_add( $wp_customize, 'hbg_show_quick_picks', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Show the "quick picks" product panel in the hero', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_quick_picks_count', array( 'type' => 'number', 'default' => 3, 'label' => __( 'Products in the quick-picks panel (2–4)', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_cta_primary', array( 'default' => __( 'See our top picks', 'hoverboards-guide' ), 'label' => __( 'Primary button text (blank to hide)', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_cta_secondary', array( 'default' => __( 'Find my ride in 60 seconds', 'hoverboards-guide' ), 'label' => __( 'Secondary button text (blank to hide)', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_headline', array( 'default' => __( 'Best Hoverboards & Electric Scooters in 2026', 'hoverboards-guide' ), 'label' => __( 'Homepage H1', 'hoverboards-guide' ), 'section' => 'hbg_hero', 'sanitize' => 'wp_kses_post' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_subhead', array( 'type' => 'textarea', 'default' => __( 'Independent reviews, buying guides and how-tos for hoverboards, e-scooters and every electric ride in between — prices re-checked weekly.', 'hoverboards-guide' ), 'label' => __( 'Sub-headline', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_updated_label', array( 'default' => __( 'Updated weekly', 'hoverboards-guide' ), 'label' => __( 'Updated badge text', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_chips', array( 'default' => __( 'UL 2272-certified picks only|200+ guides & reviews|No sponsored placements', 'hoverboards-guide' ), 'label' => __( 'Trust chips (separate with |)', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );

	// Affiliate
	hbg_cz_add( $wp_customize, 'hbg_affiliate_tag', array( 'default' => 'rcblogs-20', 'label' => __( 'Amazon Associates tag', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );
	hbg_cz_add( $wp_customize, 'hbg_affiliate_disclosure', array( 'type' => 'textarea', 'default' => __( 'As an Amazon Associate, Hoverboards Guide earns from qualifying purchases. Prices and availability are accurate as of the date/time indicated and are subject to change.', 'hoverboards-guide' ), 'label' => __( 'Affiliate disclosure', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );
	hbg_cz_add( $wp_customize, 'hbg_show_price_tiers', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Show price tiers (e.g. "$250–$500") on product cards', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );
	hbg_cz_add( $wp_customize, 'hbg_show_image_notes', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Show "Representative photo" note on stock product images', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );

	// Newsletter
	hbg_cz_add( $wp_customize, 'hbg_newsletter_title', array( 'default' => __( 'Get the weekly ride report', 'hoverboards-guide' ), 'label' => __( 'Title', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );
	hbg_cz_add( $wp_customize, 'hbg_newsletter_sub', array( 'type' => 'textarea', 'default' => __( 'New reviews, real deals and safety recalls — one email a week, no spam.', 'hoverboards-guide' ), 'label' => __( 'Sub-text', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );
	hbg_cz_add( $wp_customize, 'hbg_newsletter_action', array( 'type' => 'url', 'default' => '', 'label' => __( 'External form action URL (Mailchimp, ConvertKit…)', 'hoverboards-guide' ), 'description' => __( 'Leave blank to store subscribers in WordPress (Appearance → Subscribers).', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );
	hbg_cz_add( $wp_customize, 'hbg_newsletter_email_field', array( 'default' => 'EMAIL', 'label' => __( 'Email field name for the external form', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );
	hbg_cz_add( $wp_customize, 'hbg_newsletter_success', array( 'default' => __( 'You’re in! Check your inbox to confirm.', 'hoverboards-guide' ), 'label' => __( 'Success message', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );
	hbg_cz_add( $wp_customize, 'hbg_newsletter_notify', array( 'type' => 'checkbox', 'default' => false, 'label' => __( 'Email the admin on every new subscriber', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );

	// Single post
	foreach ( array(
		'hbg_show_toc'        => __( 'Table of contents', 'hoverboards-guide' ),
		'hbg_show_share'      => __( 'Share buttons', 'hoverboards-guide' ),
		'hbg_show_author_box' => __( 'Author box', 'hoverboards-guide' ),
		'hbg_show_related'    => __( 'Related articles', 'hoverboards-guide' ),
		'hbg_show_post_nav'   => __( 'Previous / next navigation', 'hoverboards-guide' ),
		'hbg_show_post_newsletter' => __( 'Newsletter box after content', 'hoverboards-guide' ),
		'hbg_show_views'      => __( 'View counts', 'hoverboards-guide' ),
		'hbg_show_disclosure' => __( 'Affiliate disclosure box', 'hoverboards-guide' ),
	) as $id => $label ) {
		hbg_cz_add( $wp_customize, $id, array( 'type' => 'checkbox', 'default' => true, 'label' => $label, 'section' => 'hbg_single' ) );
	}
	hbg_cz_add( $wp_customize, 'hbg_sidebar_position', array( 'type' => 'select', 'default' => 'right', 'label' => __( 'Sidebar on blog & archive pages', 'hoverboards-guide' ), 'section' => 'hbg_single', 'choices' => array( 'right' => __( 'Right sidebar', 'hoverboards-guide' ), 'none' => __( 'No sidebar', 'hoverboards-guide' ) ) ) );
	hbg_cz_add( $wp_customize, 'hbg_sidebar_mode', array( 'type' => 'select', 'default' => 'curated', 'label' => __( 'Sidebar content', 'hoverboards-guide' ), 'description' => __( 'The curated set shows search, most-read, newsletter, top picks and categories. Switch to widgets to use Appearance → Widgets → Sidebar.', 'hoverboards-guide' ), 'section' => 'hbg_single', 'choices' => array( 'curated' => __( 'Theme’s curated set (recommended)', 'hoverboards-guide' ), 'widgets' => __( 'Widgets from the Sidebar area', 'hoverboards-guide' ) ) ) );
	hbg_cz_add( $wp_customize, 'hbg_author_name', array( 'default' => __( 'Hoverboards Guide Editors', 'hoverboards-guide' ), 'label' => __( 'Author name shown when the WordPress user is "admin"', 'hoverboards-guide' ), 'section' => 'hbg_single' ) );
	hbg_cz_add( $wp_customize, 'hbg_author_bio', array( 'type' => 'textarea', 'default' => __( 'Our editors research, ride and re-check every hoverboard and electric scooter we recommend — and update guides weekly as prices and models change.', 'hoverboards-guide' ), 'label' => __( 'Default author bio', 'hoverboards-guide' ), 'section' => 'hbg_single' ) );

	// Trust
	hbg_cz_add( $wp_customize, 'hbg_trust_headline', array( 'default' => __( 'Independent reviews. No paid placements. Ever.', 'hoverboards-guide' ), 'label' => __( 'Headline', 'hoverboards-guide' ), 'section' => 'hbg_trust' ) );
	hbg_cz_add( $wp_customize, 'hbg_trust_text', array( 'type' => 'textarea', 'default' => __( 'We research every product against owner reviews, spec sheets and safety recalls, ride what we can, and re-check prices every week. We earn a small commission on Amazon clicks — that funds the work, never the recommendation.', 'hoverboards-guide' ), 'label' => __( 'Paragraph', 'hoverboards-guide' ), 'section' => 'hbg_trust' ) );
	hbg_cz_add( $wp_customize, 'hbg_trust_stats', array( 'type' => 'textarea', 'default' => "200+|Guides & reviews published\n60+|Models researched in 2026\n0|Sponsored placements\nWeekly|Price & stock checks", 'label' => __( 'Stats (one per line: Number|Label)', 'hoverboards-guide' ), 'section' => 'hbg_trust' ) );

	// Final CTA
	hbg_cz_add( $wp_customize, 'hbg_cta_headline', array( 'default' => __( 'Ready to find your perfect ride?', 'hoverboards-guide' ), 'label' => __( 'Headline', 'hoverboards-guide' ), 'section' => 'hbg_cta' ) );
	hbg_cz_add( $wp_customize, 'hbg_cta_sub', array( 'default' => __( 'Free Prime shipping on most picks · 30-day returns · Prices re-checked weekly', 'hoverboards-guide' ), 'label' => __( 'Sub-text', 'hoverboards-guide' ), 'section' => 'hbg_cta' ) );

	// Footer
	hbg_cz_add( $wp_customize, 'hbg_footer_about', array( 'type' => 'textarea', 'default' => __( 'Independent reviews of hoverboards, electric scooters and e-bikes. Written by riders, updated weekly, never sponsored.', 'hoverboards-guide' ), 'label' => __( 'About text', 'hoverboards-guide' ), 'section' => 'hbg_footer' ) );
	hbg_cz_add( $wp_customize, 'hbg_footer_tagline', array( 'default' => __( 'Made for riders, not advertisers', 'hoverboards-guide' ), 'label' => __( 'Copyright tagline', 'hoverboards-guide' ), 'section' => 'hbg_footer' ) );
}
add_action( 'customize_register', 'hbg_customizer_register' );

function hbg_customizer_preview_js() {
	wp_enqueue_script( 'hbg-customizer-preview', HBG_URI . '/assets/js/customizer-preview.js', array( 'customize-preview' ), HBG_VERSION, true );
}
add_action( 'customize_preview_init', 'hbg_customizer_preview_js' );
