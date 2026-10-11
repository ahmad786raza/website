<?php
/**
 * Helper functions
 *
 * @package BendersHub
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Default strings shipped by v1 that made claims the site cannot support
 * ("workshop-tested", "reviewed by working fabricators", "12,000+").
 * If a saved Customizer value still equals one of these, the v2 default is
 * used instead. Anything the owner has written themselves is left alone.
 */
function bdh_legacy_option_values() {
	return array(
		'hero_sub'       => array(
			'Build professional fabrication setups for exhausts, roll cages, hydraulic shops, CNC bending, and industrial workflows — researched, tested, and reviewed by working fabricators.',
		),
		'hero_headline'  => array( 'Find the Best Tube Benders & Fabrication Tools for Precision Projects.' ),
		'hero_eyebrow'   => array( 'Premium Fabrication Engineering Hub' ),
		'trust_title'    => array( 'How we test and review fabrication equipment' ),
		'trust_sub'      => array( 'Every bender, die, and accessory is reviewed by working fabricators against documented standards. No paid placements.' ),
		'newsletter_cta' => array( 'Join 12,000+ Fabricators' ),
		'newsletter_sub' => array( 'Bender reviews, fabrication guides, and shop tips every Friday — no spam.' ),
		'footer_tagline' => array(
			'Premium fabrication engineering ecosystem. Researched, tested, and reviewed by working fabricators.',
			'Premium fabrication engineering ecosystem. Tube benders, hydraulic systems, CNC bending, exhaust fabrication, and workshop optimization — researched, tested, and reviewed by working fabricators.',
		),
		'archetypes_sub' => array( 'Pick an archetype — we tune the entire site to match your shop.' ),
	);
}

/**
 * Get a theme mod with default fallback.
 */
function bdh_option( $key, $default = '' ) {
	$value  = get_theme_mod( 'bdh_' . $key, $default );
	if ( '' === $value ) { return $default; } // An emptied text field falls back to the theme default.
	$legacy = bdh_legacy_option_values();
	if ( isset( $legacy[ $key ] ) && is_string( $value ) && in_array( trim( $value ), $legacy[ $key ], true ) ) {
		return $default;
	}
	return $value;
}

/**
 * Sanitize a checkbox value.
 */
function bdh_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === (bool) $checked ) ? true : false );
}

/**
 * Sanitize a select value against allowed options.
 */
function bdh_sanitize_select( $input, $setting ) {
	$control = $setting->manager->get_control( $setting->id );
	if ( ! $control ) { return $setting->default; }
	return array_key_exists( $input, $control->choices ) ? $input : $setting->default;
}

/**
 * Sanitize a comma-separated list of post IDs.
 */
function bdh_sanitize_id_list( $input ) {
	$ids = array_filter( array_map( 'absint', explode( ',', (string) $input ) ) );
	return implode( ',', array_unique( $ids ) );
}

/**
 * Build an Amazon affiliate search URL from a keyword.
 * Search-result links are a supported Associates link type; they always carry our tag.
 */
function bdh_amazon_url( $keyword, $params = array() ) {
	$keyword = trim( $keyword );
	$base    = 'https://www.amazon.com/s';
	$args    = wp_parse_args( $params, array(
		'k'   => $keyword,
		'tag' => BDH_AMAZON_TAG,
	) );
	return add_query_arg( array_map( 'rawurlencode', $args ), $base );
}

/**
 * Normalise a direct Amazon product URL: keep any tag already present
 * (never overwrite an existing tracking ID), otherwise add ours.
 */
function bdh_amazon_product_url( $url ) {
	$url = trim( (string) $url );
	if ( ! $url || ! bdh_is_amazon_url( $url ) ) { return $url; }
	if ( preg_match( '/[?&]tag=/', $url ) ) { return $url; }
	return add_query_arg( 'tag', BDH_AMAZON_TAG, $url );
}

/**
 * Is this an Amazon (or amzn.to short) URL?
 */
function bdh_is_amazon_url( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! $host ) { return false; }
	$host = strtolower( $host );
	return (bool) preg_match( '/(^|\.)amazon\.[a-z.]+$/', $host ) || in_array( $host, array( 'amzn.to', 'www.amzn.to' ), true );
}

/**
 * Output an Amazon affiliate CTA button.
 *
 * @param string $keyword Search keyword, or a full Amazon URL.
 */
function bdh_amazon_button( $keyword, $label = '', $class = 'bdh-btn bdh-btn-primary', $aria = '' ) {
	$is_url = 0 === strpos( $keyword, 'http' );
	$url    = $is_url ? bdh_amazon_product_url( $keyword ) : bdh_amazon_url( $keyword );
	$label  = $label ? $label : ( $is_url ? __( 'Check price on Amazon', 'benders-hub' ) : __( 'See options on Amazon', 'benders-hub' ) );
	$aria   = $aria ? $aria : ( $is_url ? $label . ' ' . __( '(opens Amazon in a new tab)', 'benders-hub' ) : sprintf( __( 'Search Amazon for %s (opens in a new tab)', 'benders-hub' ), $keyword ) );
	printf(
		'<a href="%1$s" class="%2$s bdh-amazon-cta" rel="nofollow sponsored noopener" target="_blank" aria-label="%3$s" data-bdh-affiliate="%4$s"><span class="bdh-btn-label">%5$s</span>%6$s</a>',
		esc_url( $url ),
		esc_attr( $class ),
		esc_attr( $aria ),
		esc_attr( $is_url ? 'product' : $keyword ),
		esc_html( $label ),
		bdh_icon( 'external', 'bdh-btn-icon' )
	);
}

/**
 * Short inline Amazon disclosure placed next to affiliate CTAs.
 */
function bdh_inline_disclosure( $echo = true ) {
	$page = get_page_by_path( 'affiliate-disclosure' );
	$html = '<p class="bdh-inline-disclosure">' . bdh_icon( 'info' ) . '<span>' . esc_html( BDH_AMAZON_DISCLOSURE ) . '</span>';
	if ( $page && 'publish' === $page->post_status ) {
		$html .= ' <a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html__( 'Disclosure', 'benders-hub' ) . '</a>';
	}
	$html .= '</p>';
	if ( $echo ) { echo $html; } // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts.
	return $html;
}

/**
 * Escape & echo helper.
 */
function bdh_e( $value, $context = 'html' ) {
	switch ( $context ) {
		case 'attr': echo esc_attr( $value ); break;
		case 'url':  echo esc_url( $value );  break;
		case 'js':   echo esc_js( $value );   break;
		default:     echo esc_html( $value );
	}
}

/**
 * SVG icon library (inline, no external deps).
 */
function bdh_icon( $name, $class = 'bdh-icon' ) {
	$icons = array(
		'spark'      => '<path d="M12 2v8M12 14v8M2 12h8M14 12h8M5 5l4 4M15 15l4 4M19 5l-4 4M9 15l-4 4"/>',
		'wrench'     => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
		'gear'       => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
		'tube'       => '<path d="M4 20V11a7 7 0 0 1 7-7h9"/><path d="M8 20v-9a3 3 0 0 1 3-3h9"/>',
		'check'      => '<polyline points="20 6 9 17 4 12"/>',
		'arrow'      => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
		'flame'      => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
		'shield'     => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
		'calculator' => '<rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="11" x2="8" y2="11"/><line x1="12" y1="11" x2="12" y2="11"/><line x1="16" y1="11" x2="16" y2="11"/><line x1="8" y1="15" x2="8" y2="15"/><line x1="12" y1="15" x2="12" y2="15"/><line x1="16" y1="15" x2="16" y2="18"/><line x1="8" y1="18" x2="12" y2="18"/>',
		'compass'    => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
		'workshop'   => '<path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14h2v3H8zM14 14h2v3h-2z"/>',
		'star'       => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
		'menu'       => '<line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>',
		'close'      => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
		'search'     => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
		'external'   => '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>',
		'info'       => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
		'alert'      => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
		'book'       => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
		'ruler'      => '<path d="M21.3 8.7l-6-6a1 1 0 0 0-1.4 0L2.7 13.9a1 1 0 0 0 0 1.4l6 6a1 1 0 0 0 1.4 0L21.3 10.1a1 1 0 0 0 0-1.4z"/><path d="M7.5 10.5l2 2M10.5 7.5l2 2M13.5 4.5l2 2M4.5 13.5l2 2"/>',
		'layers'     => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
		'factory'    => '<path d="M2 20V9l6 4V9l6 4V4h8v16H2z"/><path d="M6 16h2M11 16h2M16 16h2"/>',
		'car'        => '<path d="M5 17h14M3 13l2-6h14l2 6v4h-2M5 17H3v-4"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/>',
		'user'       => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
		'clock'      => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'chevron'    => '<polyline points="6 9 12 15 18 9"/>',
		'list'       => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
		'quiz'       => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
		'compare'    => '<rect x="3" y="4" width="7" height="16" rx="1"/><rect x="14" y="4" width="7" height="16" rx="1"/><line x1="5.5" y1="9" x2="7.5" y2="9"/><line x1="16.5" y1="9" x2="18.5" y2="9"/><line x1="5.5" y1="13" x2="7.5" y2="13"/><line x1="16.5" y1="13" x2="18.5" y2="13"/>',
		'angle'      => '<path d="M4 20h16"/><path d="M4 20L16 6"/><path d="M10 20a6 6 0 0 0-1.6-4.1"/>',
		'mail'       => '<rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22 6 12 13 2 6"/>',
	);
	$body = isset( $icons[ $name ] ) ? $icons[ $name ] : '';
	return sprintf(
		'<svg class="%s bdh-icon-%s" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
		esc_attr( $class ),
		esc_attr( $name ),
		$body
	);
}

/**
 * Reading time estimate.
 */
function bdh_reading_time( $content = '' ) {
	if ( ! $content ) { $content = get_post_field( 'post_content', get_the_ID() ); }
	$words   = str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) );
	$minutes = max( 1, (int) ceil( $words / 230 ) );
	return $minutes;
}

/**
 * Editorial label.
 *
 * v1 printed "Workshop Tested", "Reviewed by Fabricators" and "Updated 2026"
 * on every page regardless of what had actually happened. The badge
 * function is kept for backward compatibility with saved content, but the
 * unverifiable labels now return nothing.
 */
function bdh_trust_badge( $type = 'tested', $text = '' ) {
	$badges = array(
		'pick'      => __( 'Editor\'s pick', 'benders-hub' ),
		'research'  => __( 'Research-based', 'benders-hub' ),
		'hands-on'  => __( 'Hands-on tested', 'benders-hub' ),
		'safety'    => __( 'Safety note', 'benders-hub' ),
	);
	$label = $text ? $text : ( isset( $badges[ $type ] ) ? $badges[ $type ] : '' );
	if ( ! $label ) { return ''; }
	return sprintf(
		'<span class="bdh-badge bdh-badge-%1$s">%2$s<span>%3$s</span></span>',
		esc_attr( $type ),
		bdh_icon( 'check', 'bdh-badge-icon' ),
		esc_html( $label )
	);
}

/**
 * Quick start paths (now point to on-site pathways instead of straight to Amazon).
 */
function bdh_quick_start_paths() {
	return array(
		array( 'icon' => 'tube',     'label' => __( 'Beginner tube bending', 'benders-hub' ), 'pathway' => 'diy_garage',    'keyword' => 'beginner tube bender' ),
		array( 'icon' => 'flame',    'label' => __( 'Exhaust fabrication', 'benders-hub' ),   'pathway' => 'exhaust',       'keyword' => 'exhaust pipe bender' ),
		array( 'icon' => 'shield',   'label' => __( 'Roll cage & chassis', 'benders-hub' ),   'pathway' => 'rollcage',      'keyword' => 'roll cage tube bender' ),
		array( 'icon' => 'wrench',   'label' => __( 'Hydraulic & pipe', 'benders-hub' ),      'pathway' => 'hydraulic_pro', 'keyword' => 'hydraulic pipe bender' ),
		array( 'icon' => 'gear',     'label' => __( 'CNC production', 'benders-hub' ),        'pathway' => 'cnc_operator',  'keyword' => 'CNC tube bender' ),
		array( 'icon' => 'workshop', 'label' => __( 'Workshop setup', 'benders-hub' ),        'pathway' => 'industrial',    'keyword' => 'fabrication workshop tools' ),
	);
}

/**
 * FAQ defaults — rewritten for accuracy. Every answer either states standard
 * geometry or tells the reader how to verify on their own equipment.
 */
function bdh_default_faqs() {
	return array(
		array(
			'q' => __( 'Which type of tube bender should a beginner start with?', 'benders-hub' ),
			'a' => __( 'Start from the tube you need to bend, not the machine. For occasional bends in small tube (roughly 1" OD and under) a lever-type hand bender is usually enough. For structural tube such as 1.5"–1.75" DOM used in cages and chassis, you need a rotary-draw bender with a die made for that exact outside diameter and centerline radius — available as manual or with hydraulic assist. Ram-style "pipe benders" sold for plumbing pipe tend to flatten or kink thin-wall tube, so they are a poor fit for cages or exhausts. The Find My Bender quiz walks through these trade-offs.', 'benders-hub' ),
		),
		array(
			'q' => __( 'What is the difference between tube and pipe?', 'benders-hub' ),
			'a' => __( 'Tube is specified by its actual outside diameter and wall thickness (for example 1.750" × 0.120"). Pipe is specified by a nominal pipe size and schedule, and its real outside diameter differs from the nominal size — 1-1/2" NPS pipe measures 1.900" OD. Bender dies are made for one or the other, so a "1-1/2 inch" tube die will not fit 1-1/2" pipe.', 'benders-hub' ),
		),
		array(
			'q' => __( 'What is centerline radius (CLR)?', 'benders-hub' ),
			'a' => __( 'CLR is the radius of the bend measured from the bend center to the centerline of the tube. Dies are specified by tube OD and CLR. The outside of the bend sits at CLR + OD/2 and the inside at CLR − OD/2, which is why a tighter CLR stretches the outer wall more.', 'benders-hub' ),
		),
		array(
			'q' => __( 'How do I prevent wrinkles and flattening?', 'benders-hub' ),
			'a' => __( 'Risk rises as the bend gets tighter relative to the tube (a lower CLR ÷ OD, often called the "D of bend") and as the wall gets thinner relative to the diameter (a higher OD ÷ wall, the "wall factor"). Use a die set made for your tube, set the pressure die as the manufacturer describes, and for thin-wall tube at tight radii use mandrel and wiper-die support. Always test on a scrap piece first.', 'benders-hub' ),
		),
		array(
			'q' => __( 'Do I need a mandrel bender for exhaust work?', 'benders-hub' ),
			'a' => __( 'Thin-wall exhaust tubing bent to a tight radius generally needs internal mandrel support to stay round. Mandrel benders are a significant investment, so many home builders buy pre-made mandrel-bent U-bends and J-bends and cut and weld them to fit instead. Press ("crush") bending reduces the cross-section through the bend.', 'benders-hub' ),
		),
		array(
			'q' => __( 'How accurate are the calculators on this site?', 'benders-hub' ),
			'a' => __( 'The geometry is standard: bend arc length = CLR × angle (in radians) and tangent/setback = CLR × tan(angle ÷ 2). Springback, wall thinning and ovality depend on the material batch, die and machine, so those figures are planning ranges only. Verify with a test bend on scrap before cutting production material.', 'benders-hub' ),
		),
	);
}

/**
 * Get filtered FAQ list (Customizer override possible).
 */
function bdh_get_faqs() {
	$custom = bdh_option( 'faqs_json', '' );
	if ( $custom ) {
		$decoded = json_decode( $custom, true );
		if ( is_array( $decoded ) && ! empty( $decoded ) ) { return $decoded; }
	}
	return bdh_default_faqs();
}

/**
 * Map equipment category to representative icon.
 */
function bdh_equipment_icon( $category ) {
	$map = array(
		'hydraulic' => 'gear',
		'manual'    => 'wrench',
		'cnc'       => 'gear',
		'mandrel'   => 'flame',
		'notcher'   => 'wrench',
		'roller'    => 'compass',
		'specialty' => 'compass',
		'all'       => 'tube',
	);
	return isset( $map[ $category ] ) ? $map[ $category ] : 'tube';
}

/**
 * Hero blueprint background SVG (used only when no photograph is available).
 */
function bdh_hero_blueprint_svg() {
	return '<svg viewBox="0 0 1600 900" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><defs><pattern id="bdh-bp" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M40 0 L0 0 0 40" fill="none" stroke="#3b4148" stroke-width="0.6" opacity="0.5"/></pattern></defs><rect width="1600" height="900" fill="#16191d"/><rect width="1600" height="900" fill="url(#bdh-bp)"/><path d="M220 760 L620 760 A260 260 0 0 0 880 500 L880 140" fill="none" stroke="#5c636b" stroke-width="64" stroke-linecap="round" opacity="0.55"/><path d="M220 760 L620 760 A260 260 0 0 0 880 500 L880 140" fill="none" stroke="#c2410c" stroke-width="2" stroke-dasharray="10 8"/><line x1="620" y1="500" x2="880" y2="500" stroke="#c2410c" stroke-width="1.5"/><circle cx="620" cy="500" r="5" fill="#c2410c"/></svg>';
}

/**
 * Simple bend diagram (used in knowledge hub cards and empty states).
 */
function bdh_hero_bend_svg() {
	return '<svg viewBox="0 0 320 160" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M20 130 L 140 130 A 80 80 0 0 0 220 50 L 220 20" fill="none" stroke="#9aa1a9" stroke-width="16" stroke-linecap="butt"/><path d="M20 130 L 140 130 A 80 80 0 0 0 220 50 L 220 20" fill="none" stroke="#c2410c" stroke-width="1.5" stroke-dasharray="4 3"/><line x1="140" y1="50" x2="220" y2="50" stroke="#2f5d8a" stroke-width="1.5"/><circle cx="140" cy="50" r="3" fill="#2f5d8a"/><text x="160" y="44" fill="#2f5d8a" font-family="monospace" font-size="11">CLR</text></svg>';
}

/**
 * URL for a tool: its dedicated page when one exists, else the homepage console anchor.
 */
function bdh_tool_url( $tool ) {
	$pages = array(
		'quiz'          => array( 'quiz', 'bdh-quiz' ),
		'calculator'    => array( 'calculator', 'bdh-calculator' ),
		'visualizer'    => array( 'visualizer', 'bdh-visualizer' ),
		'comparison'    => array( 'compare', 'bdh-comparison' ),
		'workflow'      => array( 'workflow', 'bdh-workflow' ),
		'compatibility' => array( 'compatibility', 'bdh-compatibility' ),
		'prioritizer'   => array( 'prioritizer', 'bdh-prioritizer' ),
		'tracker'       => array( 'tracker', 'bdh-tracker' ),
		'database'      => array( 'database', 'bdh-database' ),
	);
	if ( ! isset( $pages[ $tool ] ) ) { return home_url( '/' ); }
	static $cache = array();
	if ( isset( $cache[ $tool ] ) ) { return $cache[ $tool ]; }
	$page = get_page_by_path( $pages[ $tool ][0] );
	if ( $page && 'publish' === $page->post_status ) {
		$cache[ $tool ] = get_permalink( $page );
	} else {
		$cache[ $tool ] = home_url( '/#' . $pages[ $tool ][1] );
	}
	return $cache[ $tool ];
}

/**
 * Tool registry used by the homepage console, sidebar and next-step boxes.
 */
function bdh_tools() {
	return array(
		'quiz'          => array( 'label' => __( 'Find My Bender quiz', 'benders-hub' ),       'short' => __( 'Quiz', 'benders-hub' ),        'icon' => 'quiz',       'anchor' => 'bdh-quiz',          'part' => 'template-parts/tools/quiz',          'desc' => __( 'Nine questions about your tube, projects and shop. Get the bender type that fits — and why.', 'benders-hub' ), 'primary' => true ),
		'calculator'    => array( 'label' => __( 'Bend calculator', 'benders-hub' ),           'short' => __( 'Calculator', 'benders-hub' ),  'icon' => 'calculator', 'anchor' => 'bdh-calculator',    'part' => 'template-parts/tools/calculator',    'desc' => __( 'Arc length, tangent setback, cut length, D-of-bend and wall factor for any OD, wall and CLR.', 'benders-hub' ), 'primary' => true ),
		'visualizer'    => array( 'label' => __( 'Geometry visualizer', 'benders-hub' ),       'short' => __( 'Visualizer', 'benders-hub' ),  'icon' => 'angle',      'anchor' => 'bdh-visualizer',    'part' => 'template-parts/tools/visualizer',    'desc' => __( 'See a bend drawn to scale and how CLR and diameter change outer-wall stretch.', 'benders-hub' ), 'primary' => true ),
		'comparison'    => array( 'label' => __( 'Bender type comparison', 'benders-hub' ),    'short' => __( 'Compare', 'benders-hub' ),     'icon' => 'compare',    'anchor' => 'bdh-comparison',    'part' => 'template-parts/sections/comparison', 'desc' => __( 'Manual, hydraulic, ram, mandrel and CNC benders side by side — what each is good and bad at.', 'benders-hub' ), 'primary' => true ),
		'compatibility' => array( 'label' => __( 'Tooling checklist', 'benders-hub' ),         'short' => __( 'Tooling', 'benders-hub' ),     'icon' => 'layers',     'anchor' => 'bdh-compatibility', 'part' => 'template-parts/tools/compatibility', 'desc' => __( 'List the dies and support tooling a tube size and material call for.', 'benders-hub' ), 'primary' => false ),
		'workflow'      => array( 'label' => __( 'Bend sequence planner', 'benders-hub' ),     'short' => __( 'Planner', 'benders-hub' ),     'icon' => 'list',       'anchor' => 'bdh-workflow',      'part' => 'template-parts/tools/workflow',      'desc' => __( 'Write down a part\'s bend sequence and save it in your browser.', 'benders-hub' ), 'primary' => false ),
		'prioritizer'   => array( 'label' => __( 'Workshop prioritizer', 'benders-hub' ),      'short' => __( 'Prioritizer', 'benders-hub' ), 'icon' => 'workshop',   'anchor' => 'bdh-prioritizer',   'part' => 'template-parts/tools/prioritizer',   'desc' => __( 'Rank what matters in your shop to see which class of equipment to research first.', 'benders-hub' ), 'primary' => false ),
		'tracker'       => array( 'label' => __( 'Project tracker', 'benders-hub' ),           'short' => __( 'Tracker', 'benders-hub' ),     'icon' => 'clock',      'anchor' => 'bdh-tracker',       'part' => 'template-parts/tools/tracker',       'desc' => __( 'Keep track of builds and tube usage, stored only in your browser.', 'benders-hub' ), 'primary' => false ),
		'database'      => array( 'label' => __( 'Equipment types explorer', 'benders-hub' ), 'short' => __( 'Equipment', 'benders-hub' ),   'icon' => 'factory',    'anchor' => 'bdh-database',      'part' => 'template-parts/tools/database',      'desc' => __( 'Browse each class of bending equipment: what it does, typical capacity and limits.', 'benders-hub' ), 'primary' => false ),
	);
}

/**
 * Does the given post contain a theme tool shortcode? Used for conditional script loading.
 */
function bdh_post_has_tool( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post ) { return false; }
	foreach ( array( 'bdh_quiz', 'bdh_calculator', 'bdh_visualizer', 'bdh_workflow', 'bdh_compatibility', 'bdh_prioritizer', 'bdh_tracker', 'bdh_database', 'bdh_comparison' ) as $sc ) {
		if ( has_shortcode( $post->post_content, $sc ) ) { return true; }
	}
	return false;
}

/**
 * Helper: a published page's permalink by slug, or '' when it does not exist.
 */
function bdh_page_url( $slug ) {
	static $cache = array();
	if ( isset( $cache[ $slug ] ) ) { return $cache[ $slug ]; }
	$page = get_page_by_path( $slug );
	$cache[ $slug ] = ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
	return $cache[ $slug ];
}

/**
 * Primary navigation fallback (no menu assigned): topic categories that
 * actually contain posts, then the tools page — never empty categories.
 */
function bdh_primary_menu_fallback( $args = array() ) {
	$class = ! empty( $args['menu_class'] ) ? $args['menu_class'] : 'bdh-menu';
	echo '<ul class="' . esc_attr( $class ) . '">';
	$cats = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 6, 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
	foreach ( $cats as $cat ) {
		printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( get_category_link( $cat ) ), esc_html( $cat->name ) );
	}
	printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( bdh_tool_url( 'calculator' ) ), esc_html__( 'Calculators & Tools', 'benders-hub' ) );
	printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( bdh_tool_url( 'comparison' ) ), esc_html__( 'Comparisons', 'benders-hub' ) );
	echo '</ul>';
}

/**
 * Footer menu fallback — only links to pages that exist.
 */
function bdh_footer_menu_fallback() {
	$links = array(
		'about'                => __( 'About', 'benders-hub' ),
		'editorial-standards'  => __( 'Editorial standards', 'benders-hub' ),
		'methodology'          => __( 'How we evaluate equipment', 'benders-hub' ),
		'affiliate-disclosure' => __( 'Affiliate disclosure', 'benders-hub' ),
		'privacy-policy'       => __( 'Privacy policy', 'benders-hub' ),
		'terms'                => __( 'Terms', 'benders-hub' ),
		'contact'              => __( 'Contact', 'benders-hub' ),
		'image-credits'        => __( 'Image credits', 'benders-hub' ),
	);
	echo '<ul class="bdh-footer-menu">';
	foreach ( $links as $slug => $label ) {
		$url = 'privacy-policy' === $slug ? get_privacy_policy_url() : bdh_page_url( $slug );
		if ( ! $url ) { continue; }
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Footer column fallback (when no widget configured).
 */
function bdh_fallback_footer_column( $i ) {
	if ( 1 === $i ) {
		echo '<h2 class="bdh-footer-title">' . esc_html__( 'Engineering tools', 'benders-hub' ) . '</h2><ul class="bdh-footer-links">';
		foreach ( bdh_tools() as $key => $tool ) {
			if ( in_array( $key, array( 'tracker', 'prioritizer' ), true ) ) { continue; }
			printf( '<li><a href="%s">%s</a></li>', esc_url( bdh_tool_url( $key ) ), esc_html( $tool['label'] ) );
		}
		echo '</ul>';
	} elseif ( 2 === $i ) {
		echo '<h2 class="bdh-footer-title">' . esc_html__( 'Topics', 'benders-hub' ) . '</h2><ul class="bdh-footer-links">';
		$cats = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 7, 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
		foreach ( $cats as $cat ) {
			printf( '<li><a href="%s">%s</a></li>', esc_url( get_category_link( $cat ) ), esc_html( $cat->name ) );
		}
		echo '</ul>';
	} elseif ( 3 === $i ) {
		echo '<h2 class="bdh-footer-title">' . esc_html__( 'About Benders Hub', 'benders-hub' ) . '</h2>';
		bdh_footer_menu_fallback();
	} elseif ( 4 === $i ) {
		echo '<h2 class="bdh-footer-title">' . esc_html__( 'The workshop brief', 'benders-hub' ) . '</h2>';
		echo '<p class="bdh-footer-text">' . esc_html__( 'New guides and tool updates by email. No spam; unsubscribe any time.', 'benders-hub' ) . '</p>';
		bdh_newsletter_form( 'footer' );
	}
}

/**
 * Newsletter form markup (one implementation used everywhere).
 */
function bdh_newsletter_form( $context = 'inline' ) {
	$id = 'bdh-nl-' . sanitize_key( $context );
	?>
	<form class="bdh-newsletter-form bdh-newsletter-<?php echo esc_attr( $context ); ?>" data-bdh-newsletter novalidate>
		<label class="bdh-screen-reader-text" for="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Email address', 'benders-hub' ); ?></label>
		<input type="email" id="<?php echo esc_attr( $id ); ?>" name="email" autocomplete="email" required placeholder="<?php esc_attr_e( 'you@workshop.com', 'benders-hub' ); ?>">
		<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="bdh-hp" aria-hidden="true">
		<button type="submit" class="bdh-btn bdh-btn-primary"><?php echo esc_html( bdh_option( 'newsletter_cta', __( 'Subscribe', 'benders-hub' ) ) ); ?></button>
		<p class="bdh-newsletter-msg" data-bdh-newsletter-msg role="status" aria-live="polite"></p>
	</form>
	<?php
}

/**
 * Localized JS data.
 */
function bdh_js_data() {
	return array(
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
		'home_url'     => home_url( '/' ),
		'theme_uri'    => BDH_THEME_URI,
		'amazon_tag'   => BDH_AMAZON_TAG,
		'brand'        => BDH_BRAND,
		'tool_urls'    => array(
			'quiz'       => bdh_tool_url( 'quiz' ),
			'calculator' => bdh_tool_url( 'calculator' ),
			'visualizer' => bdh_tool_url( 'visualizer' ),
			'comparison' => bdh_tool_url( 'comparison' ),
		),
		'storage_keys' => array(
			'archetype'    => 'bdh_archetype',
			'quiz_result'  => 'bdh_quiz_result',
			'workflow'     => 'bdh_workflow',
			'project'      => 'bdh_project',
			'compatibility'=> 'bdh_compatibility',
			'priorities'   => 'bdh_priorities',
		),
		'i18n' => array(
			'next'           => __( 'Next', 'benders-hub' ),
			'back'           => __( 'Back', 'benders-hub' ),
			'see_results'    => __( 'See my match', 'benders-hub' ),
			'retake'         => __( 'Retake', 'benders-hub' ),
			'loading'        => __( 'Loading…', 'benders-hub' ),
			'load_more'      => __( 'Load more articles', 'benders-hub' ),
			'no_more'        => __( 'That\'s everything for now.', 'benders-hub' ),
			'view_amazon'    => __( 'See options on Amazon', 'benders-hub' ),
			'invalid_email'  => __( 'Please enter a valid email address.', 'benders-hub' ),
			'network_error'  => __( 'Network error — please try again.', 'benders-hub' ),
			'pick_answer'    => __( 'Choose an answer to continue.', 'benders-hub' ),
			'copied'         => __( 'Link copied.', 'benders-hub' ),
		),
	);
}
