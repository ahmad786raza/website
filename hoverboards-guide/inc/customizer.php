<?php
/**
 * Customizer: brand, header, hero, homepage sections, topic images, affiliate,
 * newsletter, articles, editorial transparency, SEO, social and footer.
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

function hbg_cz_add( $wp_customize, $id, $args ) {
	$a = wp_parse_args( $args, array( 'type' => 'text', 'default' => '', 'label' => '', 'description' => '', 'section' => '', 'choices' => array(), 'transport' => 'refresh', 'sanitize' => null ) );
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
			case 'image':
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
	if ( 'image' === $a['type'] ) {
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $id, array( 'label' => $a['label'], 'description' => $a['description'], 'section' => $a['section'], 'mime_type' => 'image' ) ) );
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
		'hbg_brand'      => __( 'Brand & Typography', 'hoverboards-guide' ),
		'hbg_header'     => __( 'Header', 'hoverboards-guide' ),
		'hbg_hero'       => __( 'Homepage: Hero', 'hoverboards-guide' ),
		'hbg_home'       => __( 'Homepage: Sections', 'hoverboards-guide' ),
		'hbg_topics'     => __( 'Topic Images', 'hoverboards-guide' ),
		'hbg_affiliate'  => __( 'Affiliate & Products', 'hoverboards-guide' ),
		'hbg_newsletter' => __( 'Newsletter', 'hoverboards-guide' ),
		'hbg_single'     => __( 'Articles', 'hoverboards-guide' ),
		'hbg_trust'      => __( 'Editorial Transparency', 'hoverboards-guide' ),
		'hbg_seo'        => __( 'SEO', 'hoverboards-guide' ),
		'hbg_social'     => __( 'Social Profiles', 'hoverboards-guide' ),
		'hbg_footer'     => __( 'Footer', 'hoverboards-guide' ),
	);
	$p = 10;
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'hbg_panel', 'priority' => $p ) );
		$p += 10;
	}

	// Brand
	$wp_customize->add_setting( 'hbg_accent_color', array( 'default' => '#1d4ed8', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'hbg_accent_color', array( 'label' => __( 'Accent colour (links and buttons)', 'hoverboards-guide' ), 'description' => __( 'Keep it dark enough to read on white; the default blue passes WCAG AA.', 'hoverboards-guide' ), 'section' => 'hbg_brand' ) ) );
	hbg_cz_add( $wp_customize, 'hbg_fonts', array( 'type' => 'select', 'default' => 'web', 'label' => __( 'Typography', 'hoverboards-guide' ), 'description' => __( '“Web fonts” loads Manrope and Inter from Google Fonts in one request. “System fonts” makes no font requests at all.', 'hoverboards-guide' ), 'section' => 'hbg_brand', 'choices' => array( 'web' => __( 'Web fonts (Manrope + Inter)', 'hoverboards-guide' ), 'system' => __( 'System fonts (fastest)', 'hoverboards-guide' ) ) ) );

	// Header
	hbg_cz_add( $wp_customize, 'hbg_header_cta_text', array( 'default' => __( 'Best hoverboards', 'hoverboards-guide' ), 'label' => __( 'Header button text (blank to hide)', 'hoverboards-guide' ), 'section' => 'hbg_header' ) );
	hbg_cz_add( $wp_customize, 'hbg_header_cta_url', array( 'type' => 'url', 'default' => '', 'label' => __( 'Header button URL', 'hoverboards-guide' ), 'description' => __( 'Leave blank to link to the main best-hoverboards roundup.', 'hoverboards-guide' ), 'section' => 'hbg_header' ) );
	hbg_cz_add( $wp_customize, 'hbg_sticky_header', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Keep the header visible while scrolling', 'hoverboards-guide' ), 'section' => 'hbg_header' ) );

	// Hero
	hbg_cz_add( $wp_customize, 'hbg_hero_eyebrow', array( 'default' => __( 'Hoverboard buying guides, reviews and safety', 'hoverboards-guide' ), 'label' => __( 'Eyebrow line', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_headline', array( 'default' => __( 'Find the Right Hoverboard for Your Ride', 'hoverboards-guide' ), 'label' => __( 'Headline (H1)', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_subhead', array( 'type' => 'textarea', 'default' => __( 'Compare hoverboards for kids, teens and adults with practical buying advice on safety, range, terrain and value.', 'hoverboards-guide' ), 'label' => __( 'Supporting text', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_primary_text', array( 'default' => __( 'Read the buying guide', 'hoverboards-guide' ), 'label' => __( 'Primary button text', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_primary_url', array( 'type' => 'url', 'default' => '', 'label' => __( 'Primary button URL (blank = hoverboard buying guide)', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_secondary_text', array( 'default' => __( 'New to hoverboards? Start with safety', 'hoverboards-guide' ), 'label' => __( 'Secondary link text', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_secondary_url', array( 'type' => 'url', 'default' => '', 'label' => __( 'Secondary link URL (blank = safety guide)', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_image', array( 'type' => 'image', 'default' => 0, 'label' => __( 'Hero photo', 'hoverboards-guide' ), 'description' => __( 'Upload a licensed photo (about 1200×900). Leave empty to use the bundled royalty-free hoverboard photo.', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );
	hbg_cz_add( $wp_customize, 'hbg_hero_points', array( 'type' => 'textarea', 'default' => __( "Picks by rider age, terrain and budget\nSafety: UL 2272, charging and protective gear\nTroubleshooting and maintenance fixes", 'hoverboards-guide' ), 'label' => __( 'Three short points (one per line)', 'hoverboards-guide' ), 'section' => 'hbg_hero' ) );

	// Homepage sections
	$toggles = array(
		'hbg_show_picks'      => array( __( 'Quick recommendations (products)', 'hoverboards-guide' ), true ),
		'hbg_show_topics'     => array( __( 'Browse by topic', 'hoverboards-guide' ), true ),
		'hbg_show_guide'      => array( __( 'Featured buying guide', 'hoverboards-guide' ), true ),
		'hbg_show_latest'     => array( __( 'Reviews & comparisons grid', 'hoverboards-guide' ), true ),
		'hbg_show_compare'    => array( __( 'Comparison table', 'hoverboards-guide' ), true ),
		'hbg_show_learn'      => array( __( 'Learn before you buy', 'hoverboards-guide' ), true ),
		'hbg_show_finder'     => array( __( 'Hoverboard finder', 'hoverboards-guide' ), true ),
		'hbg_show_faq'        => array( __( 'FAQ', 'hoverboards-guide' ), true ),
		'hbg_show_trust'      => array( __( 'Editorial transparency', 'hoverboards-guide' ), true ),
		'hbg_show_more_rides' => array( __( 'More electric rides (scooters, e-bikes…)', 'hoverboards-guide' ), true ),
		'hbg_show_newsletter' => array( __( 'Newsletter (only shows once a service URL is set)', 'hoverboards-guide' ), true ),
	);
	foreach ( $toggles as $id => $t ) {
		hbg_cz_add( $wp_customize, $id, array( 'type' => 'checkbox', 'default' => $t[1], 'label' => $t[0], 'section' => 'hbg_home' ) );
	}
	hbg_cz_add( $wp_customize, 'hbg_featured_slugs', array( 'type' => 'textarea', 'default' => '', 'label' => __( 'Reviews & comparisons: post slugs in order (one per line)', 'hoverboards-guide' ), 'description' => __( 'Leave empty for the theme’s curated hoverboard list; sticky posts and the latest hoverboard posts fill any gaps.', 'hoverboards-guide' ), 'section' => 'hbg_home' ) );
	hbg_cz_add( $wp_customize, 'hbg_featured_count', array( 'type' => 'number', 'default' => 6, 'label' => __( 'Cards in the grid', 'hoverboards-guide' ), 'section' => 'hbg_home' ) );
	hbg_cz_add( $wp_customize, 'hbg_pick_ids', array( 'default' => '', 'label' => __( 'Quick recommendations: product IDs (comma-separated)', 'hoverboards-guide' ), 'description' => __( 'Leave empty to show one product per role (best overall, budget, kids, adults, all-terrain, beginners). IDs are listed under Hoverboards Guide → Products.', 'hoverboards-guide' ), 'section' => 'hbg_home' ) );
	hbg_cz_add( $wp_customize, 'hbg_all_articles_url', array( 'type' => 'url', 'default' => '', 'label' => __( '“All articles” link', 'hoverboards-guide' ), 'description' => __( 'Defaults to the Posts page, else the Hoverboards category.', 'hoverboards-guide' ), 'section' => 'hbg_home' ) );

	// Topic images
	foreach ( hbg_topic_hubs() as $slug => $hub ) {
		hbg_cz_add( $wp_customize, 'hbg_img_' . str_replace( '-', '_', $slug ), array( 'type' => 'image', 'default' => 0, 'label' => $hub['name'], 'description' => __( 'Optional licensed photo for this topic card (about 800×600). Record the source and licence in the media item’s description.', 'hoverboards-guide' ), 'section' => 'hbg_topics' ) );
	}

	// Affiliate
	hbg_cz_add( $wp_customize, 'hbg_affiliate_tag', array( 'default' => 'hoverboardsgu-20', 'label' => __( 'Amazon Associates tag', 'hoverboards-guide' ), 'description' => __( 'Used for theme-generated links, for article links that have no tag, and in place of the retired tags listed below. Any other tag already in an article is left alone.', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );
	hbg_cz_add( $wp_customize, 'hbg_affiliate_tag_retired', array( 'default' => 'rcblogs-20', 'label' => __( 'Retired tags to replace', 'hoverboards-guide' ), 'description' => __( 'Comma-separated. Article links carrying one of these tags are shown with the tag above instead; the stored article text is not changed.', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );
	hbg_cz_add( $wp_customize, 'hbg_affiliate_disclosure', array( 'type' => 'textarea', 'default' => __( 'As an Amazon Associate, Hoverboards Guide earns from qualifying purchases. Links marked “Check price on Amazon” are affiliate links; prices and availability shown on Amazon are current there, not here.', 'hoverboards-guide' ), 'label' => __( 'Affiliate disclosure', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );
	hbg_cz_add( $wp_customize, 'hbg_show_image_notes', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Label representative (non-product-specific) photos', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );
	hbg_cz_add( $wp_customize, 'hbg_track_events', array( 'type' => 'checkbox', 'default' => true, 'label' => __( 'Send affiliate-click, finder and compare events to Google Analytics when Site Kit’s gtag is present', 'hoverboards-guide' ), 'section' => 'hbg_affiliate' ) );

	// Newsletter
	hbg_cz_add( $wp_customize, 'hbg_newsletter_action', array( 'type' => 'url', 'default' => '', 'label' => __( 'Form action URL (Mailchimp, ConvertKit, MailerLite…)', 'hoverboards-guide' ), 'description' => __( 'The newsletter box is hidden everywhere until this is set, so visitors never see a form that goes nowhere.', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );
	hbg_cz_add( $wp_customize, 'hbg_newsletter_email_field', array( 'default' => 'EMAIL', 'label' => __( 'Email field name expected by the service', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );
	hbg_cz_add( $wp_customize, 'hbg_newsletter_title', array( 'default' => __( 'New guides by email', 'hoverboards-guide' ), 'label' => __( 'Title', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );
	hbg_cz_add( $wp_customize, 'hbg_newsletter_sub', array( 'type' => 'textarea', 'default' => __( 'Occasional updates when we publish new buying guides and safety notices.', 'hoverboards-guide' ), 'label' => __( 'Text', 'hoverboards-guide' ), 'section' => 'hbg_newsletter' ) );

	// Articles
	foreach ( array(
		'hbg_show_toc'        => array( __( 'Table of contents on long articles', 'hoverboards-guide' ), true ),
		'hbg_show_share'      => array( __( 'Share buttons', 'hoverboards-guide' ), true ),
		'hbg_show_author_box' => array( __( 'Author box', 'hoverboards-guide' ), true ),
		'hbg_show_related'    => array( __( 'Related articles', 'hoverboards-guide' ), true ),
		'hbg_show_post_nav'   => array( __( 'Previous / next in the same category', 'hoverboards-guide' ), true ),
		'hbg_show_disclosure' => array( __( 'Affiliate disclosure box on articles with affiliate links', 'hoverboards-guide' ), true ),
		'hbg_show_views'      => array( __( 'Show view counts on cards', 'hoverboards-guide' ), false ),
		'hbg_hide_imported_ratings' => array( __( 'Hide star ratings, review counts and stock/shipping promises baked into imported product boxes (no source backs them)', 'hoverboards-guide' ), true ),
		'hbg_strip_heading_emoji'   => array( __( 'Remove emoji that prefix headings in imported articles', 'hoverboards-guide' ), true ),
		'hbg_clean_imported'  => array( __( 'Normalise imported article styling (purple header blocks, inline fonts, duplicate “#ad” strips)', 'hoverboards-guide' ), true ),
		'hbg_skip_title_cards' => array( __( 'Use the topic photo instead of generated “title card” featured images (files named *-featured.png)', 'hoverboards-guide' ), true ),
	) as $id => $t ) {
		hbg_cz_add( $wp_customize, $id, array( 'type' => 'checkbox', 'default' => $t[1], 'label' => $t[0], 'section' => 'hbg_single' ) );
	}
	hbg_cz_add( $wp_customize, 'hbg_sidebar_position', array( 'type' => 'select', 'default' => 'right', 'label' => __( 'Sidebar on category, search and blog pages', 'hoverboards-guide' ), 'section' => 'hbg_single', 'choices' => array( 'right' => __( 'Right sidebar', 'hoverboards-guide' ), 'none' => __( 'No sidebar', 'hoverboards-guide' ) ) ) );
	hbg_cz_add( $wp_customize, 'hbg_sidebar_mode', array( 'type' => 'select', 'default' => 'curated', 'label' => __( 'Sidebar content', 'hoverboards-guide' ), 'description' => __( 'The curated set shows search, topics, recommendations and most-read. Switch to widgets to use Appearance → Widgets → Sidebar.', 'hoverboards-guide' ), 'section' => 'hbg_single', 'choices' => array( 'curated' => __( 'Theme’s curated set', 'hoverboards-guide' ), 'widgets' => __( 'Widgets from the Sidebar area', 'hoverboards-guide' ) ) ) );
	hbg_cz_add( $wp_customize, 'hbg_author_name', array( 'default' => __( 'Hoverboards Guide Editors', 'hoverboards-guide' ), 'label' => __( 'Author name shown when the WordPress user is “admin” or an email address', 'hoverboards-guide' ), 'section' => 'hbg_single' ) );
	hbg_cz_add( $wp_customize, 'hbg_author_bio', array( 'type' => 'textarea', 'default' => __( 'Hoverboards Guide is written by a small editorial team. Our guides are built from manufacturer specifications, published safety guidance and owner feedback; we say clearly when a product has not been ridden by us.', 'hoverboards-guide' ), 'label' => __( 'Default author description', 'hoverboards-guide' ), 'section' => 'hbg_single' ) );

	// Editorial transparency
	hbg_cz_add( $wp_customize, 'hbg_trust_headline', array( 'default' => __( 'How this guide is put together', 'hoverboards-guide' ), 'label' => __( 'Headline', 'hoverboards-guide' ), 'section' => 'hbg_trust' ) );
	hbg_cz_add( $wp_customize, 'hbg_trust_text', array( 'type' => 'textarea', 'default' => __( 'Hoverboards Guide covers hoverboards and self-balancing boards for kids, teens and adults, plus electric scooters and other small electric rides. Our recommendations are desk research: we compare manufacturer specifications, safety certification, published recalls and owner feedback, and we update guides when models change. We do not run a test lab, and we say so when we have not ridden a board. If you buy through a link marked as an Amazon link, we may earn a commission at no extra cost to you.', 'hoverboards-guide' ), 'label' => __( 'Paragraph', 'hoverboards-guide' ), 'section' => 'hbg_trust' ) );

	// SEO
	hbg_cz_add( $wp_customize, 'hbg_seo_output', array( 'type' => 'select', 'default' => 'auto', 'label' => __( 'Meta description, canonical and schema output', 'hoverboards-guide' ), 'description' => __( 'Auto steps aside only when an SEO plugin is really printing its tags (a Rank Math install that prints nothing does not count). “Always” prints the theme tags regardless; “Never” leaves everything to plugins.', 'hoverboards-guide' ), 'section' => 'hbg_seo', 'choices' => array( 'auto' => __( 'Auto (recommended)', 'hoverboards-guide' ), 'always' => __( 'Always print the theme tags', 'hoverboards-guide' ), 'never' => __( 'Never (plugin only)', 'hoverboards-guide' ) ) ) );
	hbg_cz_add( $wp_customize, 'hbg_home_title', array( 'default' => '', 'label' => __( 'Homepage title tag', 'hoverboards-guide' ), 'description' => __( 'Blank uses “Site name – Tagline”. Ignored when an SEO plugin is active.', 'hoverboards-guide' ), 'section' => 'hbg_seo' ) );
	hbg_cz_add( $wp_customize, 'hbg_meta_description', array( 'type' => 'textarea', 'default' => __( 'Hoverboard buying guides, reviews and safety advice: compare boards for kids, teens and adults by wheel size, terrain, weight limit and UL 2272 certification.', 'hoverboards-guide' ), 'label' => __( 'Homepage meta description', 'hoverboards-guide' ), 'section' => 'hbg_seo' ) );

	// Social
	foreach ( array( 'facebook' => 'Facebook', 'x' => 'X (Twitter)', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'pinterest' => 'Pinterest', 'tiktok' => 'TikTok' ) as $key => $label ) {
		hbg_cz_add( $wp_customize, 'hbg_social_' . $key, array( 'type' => 'url', 'label' => $label . ' URL', 'section' => 'hbg_social' ) );
	}

	// Footer
	hbg_cz_add( $wp_customize, 'hbg_footer_about', array( 'type' => 'textarea', 'default' => __( 'Buying guides, reviews and safety advice for hoverboards and other small electric rides. Research-based, updated as models change.', 'hoverboards-guide' ), 'label' => __( 'About text', 'hoverboards-guide' ), 'section' => 'hbg_footer' ) );
	hbg_cz_add( $wp_customize, 'hbg_contact_email', array( 'default' => '', 'label' => __( 'Public contact email (optional)', 'hoverboards-guide' ), 'section' => 'hbg_footer', 'sanitize' => 'sanitize_email' ) );
}
add_action( 'customize_register', 'hbg_customizer_register' );

function hbg_customizer_preview_js() {
	wp_enqueue_script( 'hbg-customizer-preview', HBG_URI . '/assets/js/customizer-preview.js', array( 'customize-preview' ), HBG_VERSION, true );
}
add_action( 'customize_preview_init', 'hbg_customizer_preview_js' );
