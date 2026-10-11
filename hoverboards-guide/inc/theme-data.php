<?php
/**
 * Product registry & editorial content for Hoverboards Guide.
 *
 * Every product below is a real, currently-sold model. Amazon links are built as
 * tagged model-name SEARCH links (never invented ASINs) so they always resolve
 * to the live, most-reviewed listing. To force a direct listing, replace the
 * 'search' value with a verified ASIN that starts with "B0".
 *
 * Override any list with the filters: hbg_products, hbg_top_pick_ids,
 * hbg_hoverboard_ids, hbg_scooter_ids, hbg_compare_ids, hbg_faqs,
 * hbg_buying_guide, hbg_category_presets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// -------------------------------------------------------------------------
// Amazon affiliate helper
// -------------------------------------------------------------------------
function hbg_amazon_url( $asin_or_keywords, $tag = '' ) {
	if ( empty( $tag ) ) {
		$tag = get_theme_mod( 'hbg_affiliate_tag', 'rcblogs-20' );
	}
	$q = trim( (string) $asin_or_keywords );

	// Only treat the value as an ASIN when it looks like a real one (B0XXXXXXXX).
	// Plain 10-letter words such as "hoverboard" must fall through to search.
	if ( preg_match( '/^B0[A-Z0-9]{8}$/i', $q ) ) {
		$url = 'https://www.amazon.com/dp/' . strtoupper( $q ) . '/?tag=' . rawurlencode( $tag );
	} else {
		$url = 'https://www.amazon.com/s?k=' . rawurlencode( str_replace( '-', ' ', $q ) ) . '&tag=' . rawurlencode( $tag );
	}
	return esc_url( $url );
}

// -------------------------------------------------------------------------
// Price tiers (no fixed prices are shown — Amazon prices change daily)
// -------------------------------------------------------------------------
function hbg_price_tiers() {
	return array(
		1 => __( 'Under $250', 'hoverboards-guide' ),
		2 => __( '$250–$500', 'hoverboards-guide' ),
		3 => __( '$500–$800', 'hoverboards-guide' ),
		4 => __( '$800+', 'hoverboards-guide' ),
	);
}

function hbg_price_tier_label( $tier ) {
	$tiers = hbg_price_tiers();
	return isset( $tiers[ $tier ] ) ? $tiers[ $tier ] : '';
}

// -------------------------------------------------------------------------
// Product registry
// -------------------------------------------------------------------------
function hbg_get_products() {
	static $products = null;
	if ( null !== $products ) {
		return $products;
	}

	$products = array(

		'ninebot-max-g2' => array(
			'id'         => 'ninebot-max-g2',
			'name'       => 'Segway Ninebot MAX G2',
			'short'      => 'Ninebot MAX G2',
			'brand'      => 'Segway',
			'kind'       => 'scooter',
			'category'   => 'Scooter',
			'badge'      => __( 'Best Commuter', 'hoverboards-guide' ),
			'tag'        => __( 'Best commuter', 'hoverboards-guide' ),
			'tagline'    => __( 'The do-it-all commuter with suspension, turn signals and 40+ mile range', 'hoverboards-guide' ),
			'score'      => 9.2,
			'range'      => '43 mi',
			'speed'      => '22 mph',
			'weight_cap' => '265 lb',
			'wheel'      => '10" tubeless',
			'motor'      => '450W (1,000W peak)',
			'tier'       => 4,
			'price_hint' => 900,
			'search'     => 'Segway Ninebot MAX G2 electric scooter',
			'image'      => 'product-escooter-city',
			'image_note' => __( 'Representative photo', 'hoverboards-guide' ),
			'color'      => '#0EA5A4',
			'best_for'   => __( 'Daily commuting', 'hoverboards-guide' ),
			'bullets'    => array(
				__( 'Front hydraulic + rear spring suspension', 'hoverboards-guide' ),
				__( 'Self-sealing tubeless tires shrug off flats', 'hoverboards-guide' ),
				__( 'Turn signals, traction control, Apple Find My', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'Class-leading real-world range', 'hoverboards-guide' ), __( 'Very stable at speed', 'hoverboards-guide' ), __( 'Excellent app + safety features', 'hoverboards-guide' ) ),
			'cons'       => array( __( 'Heavy to carry up stairs (53 lb)', 'hoverboards-guide' ), __( 'Premium price', 'hoverboards-guide' ) ),
		),

		'hiboy-s2-pro' => array(
			'id'         => 'hiboy-s2-pro',
			'name'       => 'Hiboy S2 Pro',
			'short'      => 'Hiboy S2 Pro',
			'brand'      => 'Hiboy',
			'kind'       => 'scooter',
			'category'   => 'Scooter',
			'badge'      => __( 'Best Value', 'hoverboards-guide' ),
			'tag'        => __( 'Best value', 'hoverboards-guide' ),
			'tagline'    => __( 'The value commuter: 500W motor, rear suspension and 25-mile range', 'hoverboards-guide' ),
			'score'      => 8.7,
			'range'      => '25 mi',
			'speed'      => '19 mph',
			'weight_cap' => '220 lb',
			'wheel'      => '10" solid',
			'motor'      => '500W',
			'tier'       => 2,
			'price_hint' => 400,
			'search'     => 'Hiboy S2 Pro electric scooter',
			'image'      => 'product-hiboy-s2',
			'image_note' => __( 'Brand photo', 'hoverboards-guide' ),
			'color'      => '#3B82F6',
			'best_for'   => __( 'Budget commuting', 'hoverboards-guide' ),
			'bullets'    => array(
				__( 'Maintenance-free 10" solid tires', 'hoverboards-guide' ),
				__( 'Rear dual suspension smooths cracks', 'hoverboards-guide' ),
				__( 'Folds in seconds, app lock + cruise control', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'No flats, ever', 'hoverboards-guide' ), __( 'Strong hill climbing for the money', 'hoverboards-guide' ), __( 'Bright headlight and tail light', 'hoverboards-guide' ) ),
			'cons'       => array( __( 'Solid tires transmit more vibration', 'hoverboards-guide' ), __( '220 lb rider limit', 'hoverboards-guide' ) ),
		),

		'gotrax-gxl-v2' => array(
			'id'         => 'gotrax-gxl-v2',
			'name'       => 'Gotrax GXL V2',
			'short'      => 'Gotrax GXL V2',
			'brand'      => 'Gotrax',
			'kind'       => 'scooter',
			'category'   => 'Scooter',
			'badge'      => __( 'Best Budget', 'hoverboards-guide' ),
			'tag'        => __( 'Best budget', 'hoverboards-guide' ),
			'tagline'    => __( 'The classic starter scooter — light, simple and the most-reviewed in its class', 'hoverboards-guide' ),
			'score'      => 8.1,
			'range'      => '12 mi',
			'speed'      => '15.5 mph',
			'weight_cap' => '220 lb',
			'wheel'      => '8.5" pneumatic',
			'motor'      => '250W',
			'tier'       => 1,
			'price_hint' => 230,
			'search'     => 'Gotrax GXL V2 electric scooter',
			'image'      => 'product-escooter-road',
			'image_note' => __( 'Representative photo', 'hoverboards-guide' ),
			'color'      => '#F59E0B',
			'best_for'   => __( 'First-time riders', 'hoverboards-guide' ),
			'bullets'    => array(
				__( 'Only 26 lb — easy to carry on transit', 'hoverboards-guide' ),
				__( 'Air-filled tires ride smoother than solids', 'hoverboards-guide' ),
				__( 'Cruise control + LED display', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'Lowest price for a proven design', 'hoverboards-guide' ), __( 'Light and compact', 'hoverboards-guide' ) ),
			'cons'       => array( __( 'Struggles on steep hills', 'hoverboards-guide' ), __( 'No suspension', 'hoverboards-guide' ) ),
		),

		'varla-eagle-one' => array(
			'id'         => 'varla-eagle-one',
			'name'       => 'Varla Eagle One',
			'short'      => 'Varla Eagle One',
			'brand'      => 'Varla',
			'kind'       => 'scooter',
			'category'   => 'Scooter',
			'badge'      => __( 'Best Performance', 'hoverboards-guide' ),
			'tag'        => __( 'Best off-road', 'hoverboards-guide' ),
			'tagline'    => __( 'Dual 1,000W motors, hydraulic brakes and 40 mph for riders who want it all', 'hoverboards-guide' ),
			'score'      => 9.0,
			'range'      => '40 mi',
			'speed'      => '40 mph',
			'weight_cap' => '330 lb',
			'wheel'      => '10" pneumatic',
			'motor'      => 'Dual 1,000W',
			'tier'       => 4,
			'price_hint' => 1300,
			'search'     => 'Varla Eagle One dual motor electric scooter',
			'image'      => 'product-varla-eagle',
			'image_note' => __( 'Brand photo', 'hoverboards-guide' ),
			'color'      => '#DC2626',
			'best_for'   => __( 'Off-road & speed', 'hoverboards-guide' ),
			'bullets'    => array(
				__( 'Dual hydraulic disc brakes with ABS', 'hoverboards-guide' ),
				__( 'Front & rear spring suspension for trails', 'hoverboards-guide' ),
				__( 'Climbs 30° grades without breaking a sweat', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'Thrilling acceleration', 'hoverboards-guide' ), __( 'Built like a tank', 'hoverboards-guide' ), __( 'Huge 330 lb payload', 'hoverboards-guide' ) ),
			'cons'       => array( __( '77 lb — not for stairs', 'hoverboards-guide' ), __( 'Overkill for flat city commutes', 'hoverboards-guide' ) ),
		),

		'hover1-helios' => array(
			'id'         => 'hover1-helios',
			'name'       => 'Hover-1 Helios',
			'short'      => 'Hover-1 Helios',
			'brand'      => 'Hover-1',
			'kind'       => 'hoverboard',
			'category'   => 'Hoverboard',
			'badge'      => __( 'Best for Kids', 'hoverboards-guide' ),
			'tag'        => __( 'Best for kids', 'hoverboards-guide' ),
			'tagline'    => __( 'A UL 2272-certified starter board with LED wheels and a beginner-friendly speed cap', 'hoverboards-guide' ),
			'score'      => 8.4,
			'range'      => '7 mi',
			'speed'      => '7 mph',
			'weight_cap' => '160 lb',
			'wheel'      => '6.5"',
			'motor'      => 'Dual 200W',
			'tier'       => 1,
			'price_hint' => 150,
			'search'     => 'Hover-1 Helios hoverboard',
			'image'      => 'product-hoverboard-kids',
			'image_note' => __( 'Representative photo', 'hoverboards-guide' ),
			'color'      => '#7C3AED',
			'best_for'   => __( 'Kids 8+', 'hoverboards-guide' ),
			'bullets'    => array(
				__( 'UL 2272 certified battery & charger', 'hoverboards-guide' ),
				__( 'Self-balancing mode makes learning easy', 'hoverboards-guide' ),
				__( 'LED wheels + Bluetooth speaker', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'Very easy to learn', 'hoverboards-guide' ), __( 'Affordable and widely available', 'hoverboards-guide' ) ),
			'cons'       => array( __( 'Indoor / smooth pavement only', 'hoverboards-guide' ), __( 'Short range', 'hoverboards-guide' ) ),
		),

		'hover1-titan' => array(
			'id'         => 'hover1-titan',
			'name'       => 'Hover-1 Titan',
			'short'      => 'Hover-1 Titan',
			'brand'      => 'Hover-1',
			'kind'       => 'hoverboard',
			'category'   => 'Hoverboard',
			'badge'      => __( 'Best Off-Road Board', 'hoverboards-guide' ),
			'tag'        => __( 'Best off-road', 'hoverboards-guide' ),
			'tagline'    => __( 'Big 10" tires and a 264 lb limit make this the all-terrain board for teens and adults', 'hoverboards-guide' ),
			'score'      => 8.6,
			'range'      => '8 mi',
			'speed'      => '7.5 mph',
			'weight_cap' => '264 lb',
			'wheel'      => '10" pneumatic',
			'motor'      => 'Dual 250W',
			'tier'       => 2,
			'price_hint' => 280,
			'search'     => 'Hover-1 Titan hoverboard 10 inch',
			'image'      => 'cat-hoverboards',
			'image_note' => __( 'Representative photo', 'hoverboards-guide' ),
			'color'      => '#FF7A1A',
			'best_for'   => __( 'Grass, gravel, dirt', 'hoverboards-guide' ),
			'bullets'    => array(
				__( '10" air-filled tires absorb bumps', 'hoverboards-guide' ),
				__( 'Dual 250W motors for hills up to 10°', 'hoverboards-guide' ),
				__( 'Bluetooth speaker and LED headlights', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'Handles real off-road terrain', 'hoverboards-guide' ), __( 'Comfortable for adults', 'hoverboards-guide' ) ),
			'cons'       => array( __( 'Heavier than 6.5" boards', 'hoverboards-guide' ), __( 'Tires need occasional air', 'hoverboards-guide' ) ),
		),

		'swagtron-t6' => array(
			'id'         => 'swagtron-t6',
			'name'       => 'Swagtron T6 Outlaw',
			'short'      => 'Swagtron T6',
			'brand'      => 'Swagtron',
			'kind'       => 'hoverboard',
			'category'   => 'Hoverboard',
			'badge'      => __( 'Best for Heavy Riders', 'hoverboards-guide' ),
			'tag'        => __( 'Best for adults', 'hoverboards-guide' ),
			'tagline'    => __( 'The heavy-duty board: 420 lb capacity, 12 mph and true all-terrain tires', 'hoverboards-guide' ),
			'score'      => 8.8,
			'range'      => '12 mi',
			'speed'      => '12 mph',
			'weight_cap' => '420 lb',
			'wheel'      => '10" pneumatic',
			'motor'      => 'Dual 300W',
			'tier'       => 2,
			'price_hint' => 400,
			'search'     => 'Swagtron T6 Outlaw off road hoverboard',
			'image'      => 'hero-hoverboard-shore',
			'image_note' => __( 'Representative photo', 'hoverboards-guide' ),
			'color'      => '#16A34A',
			'best_for'   => __( 'Adults up to 420 lb', 'hoverboards-guide' ),
			'bullets'    => array(
				__( 'Highest weight limit of any board we track', 'hoverboards-guide' ),
				__( 'Climbs 30° inclines', 'hoverboards-guide' ),
				__( 'UL 2272 certified, SentryShield battery', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'Unmatched capacity and torque', 'hoverboards-guide' ), __( 'Fast for a hoverboard', 'hoverboards-guide' ) ),
			'cons'       => array( __( 'Bulky and heavy (32 lb)', 'hoverboards-guide' ), __( 'Pricier than 6.5" boards', 'hoverboards-guide' ) ),
		),

		'ninebot-s' => array(
			'id'         => 'ninebot-s',
			'name'       => 'Segway Ninebot S',
			'short'      => 'Ninebot S',
			'brand'      => 'Segway',
			'kind'       => 'hoverboard',
			'category'   => 'Hoverboard',
			'badge'      => __( 'Best Self-Balancing', 'hoverboards-guide' ),
			'tag'        => __( 'Best with knee bar', 'hoverboards-guide' ),
			'tagline'    => __( 'A knee-control bar makes this the easiest self-balancing ride to master', 'hoverboards-guide' ),
			'score'      => 8.9,
			'range'      => '13.7 mi',
			'speed'      => '10 mph',
			'weight_cap' => '220 lb',
			'wheel'      => '10.5" pneumatic',
			'motor'      => 'Dual 400W',
			'tier'       => 3,
			'price_hint' => 550,
			'search'     => 'Segway Ninebot S self balancing scooter',
			'image'      => 'product-ninebot-s',
			'image_note' => __( 'Representative photo', 'hoverboards-guide' ),
			'color'      => '#0F172A',
			'best_for'   => __( 'Beginners & campuses', 'hoverboards-guide' ),
			'bullets'    => array(
				__( 'Knee bar steering — learn in 5 minutes', 'hoverboards-guide' ),
				__( 'Big pneumatic tires ride smooth', 'hoverboards-guide' ),
				__( 'App with speed limits and anti-theft', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'Most stable board for beginners', 'hoverboards-guide' ), __( 'Premium build quality', 'hoverboards-guide' ) ),
			'cons'       => array( __( 'Costs more than classic hoverboards', 'hoverboards-guide' ), __( 'Not for riders over 220 lb', 'hoverboards-guide' ) ),
		),

		'ninebot-zing-e10' => array(
			'id'         => 'ninebot-zing-e10',
			'name'       => 'Segway Ninebot ZING E10',
			'short'      => 'Ninebot ZING E10',
			'brand'      => 'Segway',
			'kind'       => 'scooter',
			'category'   => 'Scooter',
			'badge'      => __( 'Best Kids Scooter', 'hoverboards-guide' ),
			'tag'        => __( 'Best for kids', 'hoverboards-guide' ),
			'tagline'    => __( 'A grown-up build scaled for kids 8–14, with three ride modes and a cruise setting', 'hoverboards-guide' ),
			'score'      => 8.5,
			'range'      => '6.2 mi',
			'speed'      => '10 mph',
			'weight_cap' => '132 lb',
			'wheel'      => '7" solid',
			'motor'      => '150W',
			'tier'       => 1,
			'price_hint' => 200,
			'search'     => 'Segway Ninebot ZING E10 kids electric scooter',
			'image'      => 'cat-kids',
			'image_note' => __( 'Representative photo', 'hoverboards-guide' ),
			'color'      => '#DB2777',
			'best_for'   => __( 'Kids 8–14', 'hoverboards-guide' ),
			'bullets'    => array(
				__( 'Safe, Cruise and Turbo modes grow with your kid', 'hoverboards-guide' ),
				__( 'Hand brake + rear foot brake', 'hoverboards-guide' ),
				__( 'Folds and weighs under 20 lb', 'hoverboards-guide' ),
			),
			'pros'       => array( __( 'Parent-controlled speed modes', 'hoverboards-guide' ), __( 'Well-built for the price', 'hoverboards-guide' ) ),
			'cons'       => array( __( 'Short range', 'hoverboards-guide' ), __( 'Solid tires are firm on rough paths', 'hoverboards-guide' ) ),
		),
	);

	$products = apply_filters( 'hbg_products', $products );
	return $products;
}

function hbg_get_product( $id ) {
	$all = hbg_get_products();
	return isset( $all[ $id ] ) ? $all[ $id ] : null;
}

function hbg_products_by_ids( $ids ) {
	$out = array();
	foreach ( (array) $ids as $id ) {
		$p = hbg_get_product( $id );
		if ( $p ) {
			$out[] = $p;
		}
	}
	return $out;
}

// -------------------------------------------------------------------------
// Curated lists
// -------------------------------------------------------------------------
function hbg_get_top_picks() {
	$ids   = apply_filters( 'hbg_top_pick_ids', array( 'ninebot-max-g2', 'hiboy-s2-pro', 'hover1-titan', 'varla-eagle-one' ) );
	$picks = hbg_products_by_ids( $ids );
	foreach ( $picks as $i => $p ) {
		$picks[ $i ]['rank'] = $i + 1;
	}
	return $picks;
}

function hbg_get_hoverboards() {
	return hbg_products_by_ids( apply_filters( 'hbg_hoverboard_ids', array( 'hover1-helios', 'hover1-titan', 'swagtron-t6', 'ninebot-s' ) ) );
}

function hbg_get_scooters() {
	return hbg_products_by_ids( apply_filters( 'hbg_scooter_ids', array( 'ninebot-max-g2', 'hiboy-s2-pro', 'gotrax-gxl-v2', 'varla-eagle-one' ) ) );
}

function hbg_get_compare_products() {
	return hbg_products_by_ids( apply_filters( 'hbg_compare_ids', array(
		'ninebot-max-g2', 'hiboy-s2-pro', 'gotrax-gxl-v2', 'varla-eagle-one',
		'hover1-helios', 'hover1-titan', 'swagtron-t6', 'ninebot-s', 'ninebot-zing-e10',
	) ) );
}

// -------------------------------------------------------------------------
// Quiz
// -------------------------------------------------------------------------
function hbg_get_quiz_questions() {
	return array(
		array(
			'q'       => __( 'Who’s the rider?', 'hoverboards-guide' ),
			'key'     => 'who',
			'options' => array(
				array( 'label' => __( 'A kid (8–14)', 'hoverboards-guide' ),    'value' => 'kid' ),
				array( 'label' => __( 'A teen or adult', 'hoverboards-guide' ), 'value' => 'adult' ),
			),
		),
		array(
			'q'       => __( 'Where will you ride most?', 'hoverboards-guide' ),
			'key'     => 'terrain',
			'options' => array(
				array( 'label' => __( 'Smooth sidewalks & indoors', 'hoverboards-guide' ), 'value' => 'smooth' ),
				array( 'label' => __( 'City streets & bike lanes', 'hoverboards-guide' ),  'value' => 'city' ),
				array( 'label' => __( 'Grass, gravel & trails', 'hoverboards-guide' ),     'value' => 'rough' ),
			),
		),
		array(
			'q'       => __( 'What’s your budget?', 'hoverboards-guide' ),
			'key'     => 'budget',
			'options' => array(
				array( 'label' => __( 'Under $250', 'hoverboards-guide' ), 'value' => 'low' ),
				array( 'label' => __( '$250–$800', 'hoverboards-guide' ),  'value' => 'mid' ),
				array( 'label' => __( '$800+', 'hoverboards-guide' ),      'value' => 'high' ),
			),
		),
	);
}

/**
 * Ordered rules — the first rule whose conditions all match wins.
 */
function hbg_get_quiz_rules() {
	return apply_filters( 'hbg_quiz_rules', array(
		array( 'who' => 'kid',   'terrain' => 'city',  'id' => 'ninebot-zing-e10' ),
		array( 'who' => 'kid',   'terrain' => 'rough', 'id' => 'hover1-titan' ),
		array( 'who' => 'kid',   'id' => 'hover1-helios' ),
		array( 'terrain' => 'rough', 'budget' => 'high', 'id' => 'varla-eagle-one' ),
		array( 'terrain' => 'rough', 'budget' => 'low',  'id' => 'hover1-titan' ),
		array( 'terrain' => 'rough', 'id' => 'swagtron-t6' ),
		array( 'terrain' => 'smooth', 'budget' => 'low', 'id' => 'hover1-helios' ),
		array( 'terrain' => 'smooth', 'id' => 'ninebot-s' ),
		array( 'budget' => 'low',  'id' => 'gotrax-gxl-v2' ),
		array( 'budget' => 'high', 'id' => 'ninebot-max-g2' ),
		array( 'id' => 'hiboy-s2-pro' ),
	) );
}

// -------------------------------------------------------------------------
// FAQs (also emitted as FAQPage schema on the front page)
// -------------------------------------------------------------------------
function hbg_get_faqs() {
	return apply_filters( 'hbg_faqs', array(
		array(
			'q' => __( 'Are hoverboards safe in 2026?', 'hoverboards-guide' ),
			'a' => __( 'Yes — every board we recommend is UL 2272 certified, which means the battery and electrical system passed fire-safety testing. Stick to certified boards from established brands (Hover-1, Segway, Swagtron, Gyroor), respect the weight limit, charge on a hard surface and always wear a helmet. Boards made before 2018 should be retired.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'What’s the best electric scooter for commuting?', 'hoverboards-guide' ),
			'a' => __( 'Look for at least 20 miles of real-world range, 15+ mph, two independent brakes, suspension or 10-inch tires, and a fold that fits under a desk. The Segway Ninebot MAX G2 hits every mark and is our top commuter pick; the Hiboy S2 Pro delivers most of that for less.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'How long does the battery last?', 'hoverboards-guide' ),
			'a' => __( 'Per charge, expect 6–12 miles from a hoverboard and 12–40 miles from a scooter depending on rider weight, hills and speed. Lithium-ion packs typically deliver 500–1,000 full cycles (about 2–4 years) before capacity drops noticeably. Charging to 80% and storing at room temperature extends life.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'Can adults ride hoverboards?', 'hoverboards-guide' ),
			'a' => __( 'Absolutely. Adult boards use 8.5" or 10" tires and support 220–420 lb. The Hover-1 Titan and Swagtron T6 Outlaw are built for adult riders and rough ground; the Segway Ninebot S adds a knee bar for extra control.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'Do I need a license to ride an electric scooter?', 'hoverboards-guide' ),
			'a' => __( 'In most US states, no license is required for scooters under 20 mph and 750W. Local rules vary: many cities require helmets under 18, ban sidewalk riding and set a minimum age of 16. Check your city’s micromobility ordinance before you commute.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'What’s the difference between 6.5" and 10" hoverboards?', 'hoverboards-guide' ),
			'a' => __( '6.5" solid wheels are light, cheap and ideal for kids on smooth floors. 8.5"–10" pneumatic tires handle grass, gravel and cracks, support heavier riders and reach higher speeds. Adults should almost always choose 8.5" or larger.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'Hoverboard or electric scooter — which should I buy?', 'hoverboards-guide' ),
			'a' => __( 'Buy a scooter if you need to get somewhere: it is faster, safer at speed and carries cargo. Buy a hoverboard for fun, short rides and kids. If you want both in one, a Segway Ninebot S with a knee bar is the best compromise.', 'hoverboards-guide' ),
		),
	) );
}

// -------------------------------------------------------------------------
// Buying guide items
// -------------------------------------------------------------------------
function hbg_get_buying_guide() {
	return apply_filters( 'hbg_buying_guide', array(
		array( 'icon' => 'battery',  'title' => __( 'Battery & range', 'hoverboards-guide' ),      'text' => __( 'Plan for 60–70% of the advertised range. Look for 6+ miles on kid boards and 20+ miles on commuter scooters; a bigger pack (Wh) beats a bigger motor.', 'hoverboards-guide' ) ),
		array( 'icon' => 'shield',   'title' => __( 'Safety certification', 'hoverboards-guide' ), 'text' => __( 'Only buy boards labelled UL 2272 — it tests the battery system for fire risk. Scooters should carry a UL 2272 or UL 2849 mark, a bright headlight and two brakes.', 'hoverboards-guide' ) ),
		array( 'icon' => 'speed',    'title' => __( 'Speed & motor', 'hoverboards-guide' ),        'text' => __( 'Kids do best at 6–8 mph. Commuters want 15–20 mph and 350W+. Above 25 mph you need hydraulic brakes and a helmet rated for it.', 'hoverboards-guide' ) ),
		array( 'icon' => 'weight',   'title' => __( 'Weight capacity', 'hoverboards-guide' ),      'text' => __( 'Stay 15% under the rated limit. Over it, range and torque fall fast and the warranty is void. Heavier riders should look at the Swagtron T6 or Varla Eagle One.', 'hoverboards-guide' ) ),
		array( 'icon' => 'terrain',  'title' => __( 'Tires & terrain', 'hoverboards-guide' ),      'text' => __( '6.5" solid wheels are for smooth pavement. 8.5"–10" pneumatic tires handle bumps, grass and gravel; suspension matters more than motor power on rough streets.', 'hoverboards-guide' ) ),
		array( 'icon' => 'warranty', 'title' => __( 'Warranty & support', 'hoverboards-guide' ),   'text' => __( 'A 1-year warranty is the floor. Buy from brands with US parts and a clear return window — batteries are the first thing to fail in year two.', 'hoverboards-guide' ) ),
	) );
}

// -------------------------------------------------------------------------
// Trust stats & badges
// -------------------------------------------------------------------------
function hbg_get_trust_stats() {
	$raw   = get_theme_mod( 'hbg_trust_stats', "200+|Guides & reviews published\n60+|Models researched in 2026\n0|Sponsored placements\nWeekly|Price & stock checks" );
	$lines = array_filter( array_map( 'trim', explode( "\n", $raw ) ) );
	$stats = array();
	foreach ( $lines as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( count( $parts ) === 2 ) {
			$stats[] = array( 'n' => $parts[0], 'l' => $parts[1] );
		}
	}
	return $stats;
}

function hbg_get_trust_badges() {
	return apply_filters( 'hbg_trust_badges', array(
		__( 'No sponsored picks', 'hoverboards-guide' ),
		__( 'UL 2272 certified only', 'hoverboards-guide' ),
		__( 'Re-checked weekly', 'hoverboards-guide' ),
		__( 'Independent editors', 'hoverboards-guide' ),
	) );
}

// -------------------------------------------------------------------------
// Category presets: image, colour and blurb keyed by slug
// -------------------------------------------------------------------------
function hbg_category_presets() {
	return apply_filters( 'hbg_category_presets', array(
		'hoverboards'    => array( 'image' => 'cat-hoverboards',       'color' => '#FF7A1A', 'blurb' => __( 'Reviews, safety guides and picks for every age and terrain.', 'hoverboards-guide' ) ),
		'e-scooters'     => array( 'image' => 'cat-escooters',         'color' => '#0EA5A4', 'blurb' => __( 'Commuter, budget and performance scooters, tested against real streets.', 'hoverboards-guide' ) ),
		'scooters'       => array( 'image' => 'cat-escooters-commute', 'color' => '#0EA5A4', 'blurb' => __( 'Scooters for commuting, kids and weekend fun.', 'hoverboards-guide' ) ),
		'ebikes'         => array( 'image' => 'cat-ebikes',            'color' => '#16A34A', 'blurb' => __( 'Electric bikes that replace car trips.', 'hoverboards-guide' ) ),
		'buying-guide'   => array( 'image' => 'cat-guides',            'color' => '#2563EB', 'blurb' => __( 'Plain-English buying advice before you click “buy”.', 'hoverboards-guide' ) ),
		'guide'          => array( 'image' => 'cat-guides',            'color' => '#2563EB', 'blurb' => __( 'Deep-dive guides on riding, laws and maintenance.', 'hoverboards-guide' ) ),
		'how-to'         => array( 'image' => 'cat-howto',             'color' => '#D97706', 'blurb' => __( 'Fixes, upgrades and step-by-step tutorials.', 'hoverboards-guide' ) ),
		'reviews'        => array( 'image' => 'product-escooter-city', 'color' => '#0F172A', 'blurb' => __( 'Hands-on reviews with pros, cons and verdicts.', 'hoverboards-guide' ) ),
		'top-picks'      => array( 'image' => 'hero-escooter-city',    'color' => '#FF7A1A', 'blurb' => __( 'The rides we would actually buy this year.', 'hoverboards-guide' ) ),
		'skateboards'    => array( 'image' => 'cat-skateboards',       'color' => '#DB2777', 'blurb' => __( 'Electric skateboards and longboards.', 'hoverboards-guide' ) ),
		'longboards'     => array( 'image' => 'cat-skateboards',       'color' => '#DB2777', 'blurb' => __( 'Cruisers, carvers and electric longboards.', 'hoverboards-guide' ) ),
		'unicycles'      => array( 'image' => 'cat-unicycles',         'color' => '#7C3AED', 'blurb' => __( 'Electric unicycles for the adventurous.', 'hoverboards-guide' ) ),
		'bumper-cars'    => array( 'image' => 'cat-hoverkart',         'color' => '#F59E0B', 'blurb' => __( 'Hoverboard karts and bumper cars for kids.', 'hoverboards-guide' ) ),
		'charger'        => array( 'image' => 'cat-howto',             'color' => '#D97706', 'blurb' => __( 'Chargers, batteries and charging safety.', 'hoverboards-guide' ) ),
		'chargers'       => array( 'image' => 'cat-howto',             'color' => '#D97706', 'blurb' => __( 'Chargers, batteries and charging safety.', 'hoverboards-guide' ) ),
		'electric-cars'  => array( 'image' => 'hero-escooter-city',    'color' => '#475569', 'blurb' => __( 'Electric ride-ons and cars.', 'hoverboards-guide' ) ),
		'safety'         => array( 'image' => 'cat-safety',            'color' => '#DC2626', 'blurb' => __( 'Helmets, pads and riding safely.', 'hoverboards-guide' ) ),
		'kids'           => array( 'image' => 'cat-kids',              'color' => '#DB2777', 'blurb' => __( 'Rides for kids and teens.', 'hoverboards-guide' ) ),
		'self-balancing' => array( 'image' => 'cat-self-balancing',    'color' => '#0F172A', 'blurb' => __( 'Self-balancing scooters with knee bars and handles.', 'hoverboards-guide' ) ),
	) );
}

function hbg_category_preset( $term ) {
	$default = array( 'image' => 'hero-escooter-city', 'color' => '#475569', 'blurb' => '' );
	if ( ! $term instanceof WP_Term ) {
		return $default;
	}
	$presets = hbg_category_presets();
	$slug    = $term->slug;
	if ( isset( $presets[ $slug ] ) ) {
		return wp_parse_args( $presets[ $slug ], $default );
	}
	// Fuzzy match on keywords in the slug.
	$keywords = array( 'hoverboard' => 'hoverboards', 'scooter' => 'e-scooters', 'bike' => 'ebikes', 'guide' => 'guide', 'how' => 'how-to', 'review' => 'reviews', 'skate' => 'skateboards', 'unicycle' => 'unicycles', 'kid' => 'kids', 'safety' => 'safety', 'helmet' => 'safety', 'charg' => 'charger' );
	foreach ( $keywords as $needle => $preset ) {
		if ( false !== strpos( $slug, $needle ) && isset( $presets[ $preset ] ) ) {
			return wp_parse_args( $presets[ $preset ], $default );
		}
	}
	return $default;
}
