<?php
/**
 * Helper functions
 *
 * @package BendersHub
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Get a theme mod with default fallback.
 */
function bdh_option( $key, $default = '' ) {
	return get_theme_mod( 'bdh_' . $key, $default );
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
 * Build an Amazon affiliate URL from a keyword.
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
 * Output an Amazon affiliate CTA button.
 */
function bdh_amazon_button( $keyword, $label = '', $class = 'bdh-btn bdh-btn-primary', $aria = '' ) {
	$label = $label ? $label : __( 'View on Amazon', 'benders-hub' );
	$aria  = $aria ? $aria : sprintf( __( 'Search %s on Amazon', 'benders-hub' ), $keyword );
	printf(
		'<a href="%1$s" class="%2$s bdh-amazon-cta" rel="nofollow sponsored noopener" target="_blank" aria-label="%3$s" data-bdh-affiliate="%4$s"><span class="bdh-btn-label">%5$s</span><span class="bdh-btn-icon" aria-hidden="true">→</span></a>',
		esc_url( bdh_amazon_url( $keyword ) ),
		esc_attr( $class ),
		esc_attr( $aria ),
		esc_attr( $keyword ),
		esc_html( $label )
	);
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
		'tube'       => '<path d="M3 7c0-2 9-4 9-4s9 2 9 4v10c0 2-9 4-9 4s-9-2-9-4V7z"/><path d="M3 7c0 2 9 4 9 4s9-2 9-4"/>',
		'check'      => '<polyline points="20 6 9 17 4 12"/>',
		'arrow'      => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
		'flame'      => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
		'shield'     => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
		'calculator' => '<rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="14" x2="8" y2="14"/><line x1="12" y1="14" x2="12" y2="14"/><line x1="16" y1="14" x2="16" y2="14"/><line x1="8" y1="18" x2="8" y2="18"/><line x1="12" y1="18" x2="12" y2="18"/><line x1="16" y1="18" x2="16" y2="18"/>',
		'compass'    => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
		'workshop'   => '<path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14h2v3H8zM14 14h2v3h-2z"/>',
		'star'       => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
		'menu'       => '<line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>',
		'close'      => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
		'search'     => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
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
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 220 ) );
	return $minutes;
}

/**
 * Trust/credibility badge.
 */
function bdh_trust_badge( $type = 'tested', $text = '' ) {
	$badges = array(
		'tested'    => __( 'Workshop Tested', 'benders-hub' ),
		'reviewed'  => __( 'Reviewed by Fabricators', 'benders-hub' ),
		'updated'   => __( 'Updated 2026', 'benders-hub' ),
		'pick'      => __( 'Editor\'s Pick', 'benders-hub' ),
		'precision' => __( 'Precision Verified', 'benders-hub' ),
		'safety'    => __( 'Safety Approved', 'benders-hub' ),
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
 * Quick start path defaults.
 */
function bdh_quick_start_paths() {
	return array(
		array( 'icon' => 'tube',       'label' => __( 'Beginner Tube Bending', 'benders-hub' ),  'keyword' => 'beginner tube bender' ),
		array( 'icon' => 'flame',      'label' => __( 'Exhaust Fabrication', 'benders-hub' ),    'keyword' => 'exhaust pipe bender' ),
		array( 'icon' => 'shield',     'label' => __( 'Roll Cage Setup', 'benders-hub' ),        'keyword' => 'roll cage tube bender' ),
		array( 'icon' => 'wrench',     'label' => __( 'Hydraulic Bending', 'benders-hub' ),      'keyword' => 'hydraulic pipe bender' ),
		array( 'icon' => 'gear',       'label' => __( 'CNC Fabrication', 'benders-hub' ),        'keyword' => 'CNC tube bender' ),
		array( 'icon' => 'workshop',   'label' => __( 'Workshop Optimization', 'benders-hub' ),  'keyword' => 'fabrication workshop tools' ),
	);
}

/**
 * FAQ defaults.
 */
function bdh_default_faqs() {
	return array(
		array(
			'q' => __( 'What is the best tube bender for beginners?', 'benders-hub' ),
			'a' => __( 'For beginners, a manual hydraulic bender with interchangeable dies and a clear angle scale gives the best balance of precision, ease of use, and price. Look for models supporting 1/2" to 2" tubing with mild steel compatibility.', 'benders-hub' ),
		),
		array(
			'q' => __( 'How do I prevent wrinkling and tube collapse?', 'benders-hub' ),
			'a' => __( 'Wrinkling is controlled by matching wall thickness to bend radius (CLR ≥ 2× outside diameter), using a properly sized mandrel for thin-walled tube, and lubricating the die. Our bend radius calculator predicts the wrinkle risk for your exact setup.', 'benders-hub' ),
		),
		array(
			'q' => __( 'What is the difference between CLR and bend radius?', 'benders-hub' ),
			'a' => __( 'Centerline Radius (CLR) is measured from the bend center to the tube\'s centerline. Outside Radius is measured to the tube\'s outer surface. Most professional bending dies are spec\'d by CLR; this is the value you input into our tube geometry visualizer.', 'benders-hub' ),
		),
		array(
			'q' => __( 'Can I bend stainless steel with a hydraulic bender?', 'benders-hub' ),
			'a' => __( 'Yes, but you need higher tonnage capacity (often 12+ tons for 1.5" 304 stainless) and dies rated for stainless. Springback is typically 10-25% higher than mild steel — our calculator accounts for that.', 'benders-hub' ),
		),
		array(
			'q' => __( 'Do I need a mandrel bender for exhaust work?', 'benders-hub' ),
			'a' => __( 'For maximum flow and a smooth ID through the bend, yes. Crush benders create wrinkles and flow restriction. For show cars or performance builds, mandrel benders preserve the ID. For utility work, a quality rotary draw bender often suffices.', 'benders-hub' ),
		),
		array(
			'q' => __( 'How accurate are these calculators and tools?', 'benders-hub' ),
			'a' => __( 'Our calculators use accepted fabrication formulas (Bend Allowance, K-factor, springback compensation) and are reviewed by working fabricators. They are excellent for planning, but always verify on scrap material before production runs.', 'benders-hub' ),
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
 * Map equipment category to representative icon (used in card placeholder).
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
 * Hero blueprint background SVG (inline, lightweight).
 */
function bdh_hero_blueprint_svg() {
	return '<svg viewBox="0 0 1600 900" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice"><defs><pattern id="bp" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M40 0 L0 0 0 40" fill="none" stroke="#374151" stroke-width="0.5" opacity="0.4"/></pattern><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#111827"/><stop offset="1" stop-color="#1f2937"/></linearGradient></defs><rect width="1600" height="900" fill="url(#g)"/><rect width="1600" height="900" fill="url(#bp)"/><circle cx="1200" cy="300" r="180" fill="none" stroke="#ef4444" stroke-width="1.5" opacity="0.35"/><circle cx="1200" cy="300" r="280" fill="none" stroke="#f59e0b" stroke-width="1" opacity="0.25"/><path d="M200 700 Q 600 500 1000 700" fill="none" stroke="#f59e0b" stroke-width="2" opacity="0.5" stroke-dasharray="8 6"/><path d="M150 200 L 350 200 L 350 400" fill="none" stroke="#ef4444" stroke-width="1.5" opacity="0.4"/></svg>';
}

/**
 * Hero spec panel preview SVG.
 */
function bdh_hero_bend_svg() {
	return '<svg viewBox="0 0 320 160" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="tubeg" x1="0" x2="1"><stop offset="0" stop-color="#374151"/><stop offset="1" stop-color="#e5e7eb"/></linearGradient></defs><path d="M20 130 L 140 130 A 80 80 0 0 1 220 50 L 220 20" fill="none" stroke="url(#tubeg)" stroke-width="14" stroke-linecap="round"/><path d="M20 130 L 140 130 A 80 80 0 0 1 220 50 L 220 20" fill="none" stroke="#ef4444" stroke-width="1" stroke-dasharray="3 3" opacity="0.6"/><circle cx="140" cy="50" r="3" fill="#f59e0b"/><text x="148" y="55" fill="#f59e0b" font-family="monospace" font-size="10">CLR</text><text x="40" y="120" fill="#9ca3af" font-family="monospace" font-size="9">90°</text></svg>';
}

/**
 * Primary navigation fallback (no menu assigned).
 */
function bdh_primary_menu_fallback() {
	echo '<ul class="bdh-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/#bdh-quiz' ) ) . '">' . esc_html__( 'Quiz', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/#bdh-calculator' ) ) . '">' . esc_html__( 'Calculator', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/#bdh-database' ) ) . '">' . esc_html__( 'Equipment', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/#bdh-comparison' ) ) . '">' . esc_html__( 'Compare', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '">' . esc_html__( 'About', 'benders-hub' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Footer menu fallback.
 */
function bdh_footer_menu_fallback() {
	echo '<ul class="bdh-footer-menu">';
	echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '">' . esc_html__( 'About', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/editorial-standards/' ) ) . '">' . esc_html__( 'Editorial Standards', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/methodology/' ) ) . '">' . esc_html__( 'Methodology', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/affiliate-disclosure/' ) ) . '">' . esc_html__( 'Affiliate Disclosure', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . esc_html__( 'Privacy Policy', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/terms/' ) ) . '">' . esc_html__( 'Terms', 'benders-hub' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( 'Contact', 'benders-hub' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Footer column fallback (when no widget configured).
 */
function bdh_fallback_footer_column( $i ) {
	$cols = array(
		1 => array(
			'title' => __( 'Fabrication Tools', 'benders-hub' ),
			'links' => array(
				array( __( 'Tube Bender Match Quiz', 'benders-hub' ),       home_url( '/#bdh-quiz' ) ),
				array( __( 'Bend Radius Calculator', 'benders-hub' ),       home_url( '/#bdh-calculator' ) ),
				array( __( 'Tube Geometry Visualizer', 'benders-hub' ),     home_url( '/#bdh-visualizer' ) ),
				array( __( 'Workflow Planner', 'benders-hub' ),             home_url( '/#bdh-workflow' ) ),
				array( __( 'Compatibility Generator', 'benders-hub' ),      home_url( '/#bdh-compatibility' ) ),
			),
		),
		2 => array(
			'title' => __( 'Equipment Categories', 'benders-hub' ),
			'links' => array(
				array( __( 'Hydraulic Tube Benders', 'benders-hub' ),       home_url( '/category/hydraulic-benders/' ) ),
				array( __( 'Manual Tube Benders', 'benders-hub' ),          home_url( '/category/manual-benders/' ) ),
				array( __( 'CNC Bending Systems', 'benders-hub' ),          home_url( '/category/cnc-benders/' ) ),
				array( __( 'Exhaust Fabrication', 'benders-hub' ),          home_url( '/category/exhaust-bending/' ) ),
				array( __( 'Roll Cage Tooling', 'benders-hub' ),            home_url( '/category/roll-cage/' ) ),
			),
		),
		3 => array(
			'title' => __( 'Workshop Resources', 'benders-hub' ),
			'links' => array(
				array( __( 'Editorial Standards', 'benders-hub' ),          home_url( '/editorial-standards/' ) ),
				array( __( 'Testing Methodology', 'benders-hub' ),          home_url( '/methodology/' ) ),
				array( __( 'Fabrication Safety', 'benders-hub' ),           home_url( '/safety/' ) ),
				array( __( 'Tubing Glossary', 'benders-hub' ),              home_url( '/glossary/' ) ),
				array( __( 'Affiliate Disclosure', 'benders-hub' ),         home_url( '/affiliate-disclosure/' ) ),
			),
		),
		4 => array(
			'title'      => __( 'Stay In The Loop', 'benders-hub' ),
			'links'      => array(),
			'newsletter' => true,
		),
	);
	if ( ! isset( $cols[ $i ] ) ) { return; }
	$col = $cols[ $i ];
	echo '<h4 class="bdh-footer-widget-title">' . esc_html( $col['title'] ) . '</h4>';
	if ( ! empty( $col['links'] ) ) {
		echo '<ul class="bdh-footer-links">';
		foreach ( $col['links'] as $link ) {
			printf( '<li><a href="%s">%s</a></li>', esc_url( $link[1] ), esc_html( $link[0] ) );
		}
		echo '</ul>';
	}
	if ( ! empty( $col['newsletter'] ) ) {
		?>
		<p class="bdh-footer-newsletter-text"><?php esc_html_e( 'Get fabrication guides, bender reviews, and workshop tips every Friday.', 'benders-hub' ); ?></p>
		<form class="bdh-footer-newsletter" data-bdh-newsletter>
			<label class="bdh-screen-reader-text" for="bdh-footer-email"><?php esc_html_e( 'Email address', 'benders-hub' ); ?></label>
			<input type="email" id="bdh-footer-email" name="email" required placeholder="<?php esc_attr_e( 'you@workshop.com', 'benders-hub' ); ?>">
			<button type="submit" class="bdh-btn bdh-btn-primary"><?php esc_html_e( 'Subscribe', 'benders-hub' ); ?></button>
		</form>
		<?php
	}
}

/**
 * Localized JS data.
 */
function bdh_js_data() {
	return array(
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
		'nonce'        => wp_create_nonce( 'bdh_ajax' ),
		'home_url'     => home_url( '/' ),
		'theme_uri'    => BDH_THEME_URI,
		'amazon_tag'   => BDH_AMAZON_TAG,
		'brand'        => BDH_BRAND,
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
			'see_results'    => __( 'See My Results', 'benders-hub' ),
			'retake'         => __( 'Retake', 'benders-hub' ),
			'continue'       => __( 'Continue', 'benders-hub' ),
			'save'           => __( 'Save', 'benders-hub' ),
			'export'         => __( 'Export', 'benders-hub' ),
			'print'          => __( 'Print', 'benders-hub' ),
			'calculating'    => __( 'Calculating…', 'benders-hub' ),
			'loading'        => __( 'Loading…', 'benders-hub' ),
			'view_amazon'    => __( 'View on Amazon', 'benders-hub' ),
		),
	);
}
