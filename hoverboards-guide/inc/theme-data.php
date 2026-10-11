<?php
/**
 * Hoverboards Guide v3 — editorial data layer.
 *
 * Product registry, topic hubs, curated reading paths, finder rules, FAQ,
 * buying-guide summary and category presets.
 *
 * Honesty rules baked in here:
 *  - Nothing claims hands-on testing. Specs are manufacturer-advertised figures as
 *    published in this site's own reviews; each product records its `source_slug`.
 *  - Every product has a `verified` flag (false by default). The owner can check a
 *    product against the current listing and mark it verified under
 *    Hoverboards Guide → Products; cards then drop the "advertised" label.
 *  - No prices, star ratings, review counts or editor scores are shown.
 *
 * Everything is filterable: hbg_products, hbg_topic_hubs, hbg_key_posts,
 * hbg_finder_rules, hbg_faqs, hbg_guide_decisions, hbg_learn_items, hbg_category_presets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// -------------------------------------------------------------------------
// Amazon affiliate helper
// -------------------------------------------------------------------------
/**
 * Tagged Amazon URL. A value that looks like an ASIN (B0XXXXXXXX) links the listing
 * directly; anything else becomes a tagged model-name search so links never 404.
 * The tag is the site's existing Associates ID (Customizer → Affiliate), never a new one.
 */
function hbg_amazon_url( $asin_or_keywords, $tag = '' ) {
	if ( empty( $tag ) ) {
		$tag = get_theme_mod( 'hbg_affiliate_tag', 'rcblogs-20' );
	}
	$q = trim( (string) $asin_or_keywords );
	if ( preg_match( '/^B0[A-Z0-9]{8}$/i', $q ) ) {
		$url = 'https://www.amazon.com/dp/' . strtoupper( $q ) . '/?tag=' . rawurlencode( $tag );
	} else {
		$url = 'https://www.amazon.com/s?k=' . rawurlencode( str_replace( '-', ' ', $q ) ) . '&tag=' . rawurlencode( $tag );
	}
	return esc_url( $url );
}

// -------------------------------------------------------------------------
// Key posts: the destinations the homepage, menu and finder point to.
// -------------------------------------------------------------------------
function hbg_key_posts() {
	return apply_filters( 'hbg_key_posts', array(
		'best'            => 'the-best-hoverboard-reviews',
		'buying_guide'    => 'hoverboard-buying-guide',
		'safety'          => 'ultimate-guide-to-ride-a-hoverboard-safely',
		'kids'            => 'best-hoverboard-for-kids-review-complete-buying-guide',
		'adults'          => 'the-best-hoverboards-for-adults',
		'all_terrain'     => 'best-off-road-hoverboard',
		'budget'          => 'best-hoverboards-under-200',
		'bluetooth'       => 'best-bluetooth-hoverboards',
		'troubleshooting' => 'hoverboard-troubleshooting',
		'how_to_ride'     => 'how-to-use-a-hoverboard',
		'wheel_size'      => '6-5-vs-8-5-hoverboard',
		'grass_gravel'    => 'can-hoverboards-ride-on-grass-or-gravel',
		'fake_vs_real'    => 'how-to-spot-a-fake-vs-real-hoverboard',
		'charging'        => 'can-you-overcharge-a-hoverboard',
		'fire_safety'     => 'do-hoverboards-still-catch-fire',
		'weight_limit'    => 'hoverboard-weight-limit',
		'ages'            => 'hoverboard-ages',
		'helmets'         => 'best-hoverboard-helmets',
		'brands'          => 'best-hoverboard-brands',
	) );
}

/** Permalink of a key post (or any slug), or '' when the post does not exist. */
function hbg_post_url_by_slug( $slug ) {
	static $cache = array();
	if ( isset( $cache[ $slug ] ) ) {
		return $cache[ $slug ];
	}
	$post = get_page_by_path( $slug, OBJECT, array( 'post', 'page' ) );
	$cache[ $slug ] = ( $post && 'publish' === $post->post_status ) ? get_permalink( $post ) : '';
	return $cache[ $slug ];
}

function hbg_key_url( $key ) {
	$map = hbg_key_posts();
	return isset( $map[ $key ] ) ? hbg_post_url_by_slug( $map[ $key ] ) : '';
}

function hbg_post_by_slug( $slug ) {
	$post = get_page_by_path( $slug, OBJECT, array( 'post', 'page' ) );
	return ( $post && 'publish' === $post->post_status ) ? $post : null;
}

// -------------------------------------------------------------------------
// Product registry (hoverboards first). Figures come from the site's own
// reviews and roundups, which quote manufacturer listings. `verified` is false
// until the owner checks a product in Hoverboards Guide → Products.
// -------------------------------------------------------------------------
function hbg_default_products() {
	return array(

		'gyroor-warrior-g2' => array(
			'id'            => 'gyroor-warrior-g2',
			'name'          => 'Gyroor Warrior G2',
			'short'         => 'Gyroor Warrior',
			'brand'         => 'Gyroor',
			'kind'          => 'hoverboard',
			'role'          => 'overall',
			'badge'         => __( 'Best overall', 'hoverboards-guide' ),
			'why'           => __( 'The pick that comes up most often in our roundups: 8.5-inch all-terrain tires and a 265 lb rating cover most teen and adult riders on pavement, grass and packed dirt.', 'hoverboards-guide' ),
			'best_for'      => __( 'Teens and adults who ride on mixed surfaces', 'hoverboards-guide' ),
			'wheel'         => '8.5" solid all-terrain',
			'speed'         => 'Up to 10 mph',
			'range'         => '7.5–9.5 mi',
			'weight_cap'    => '265 lb',
			'certification' => 'UL 2272',
			'bluetooth'     => true,
			'pros'          => array( __( 'Bigger tires than a 6.5-inch starter board, so grass and gravel are manageable', 'hoverboards-guide' ), __( 'Higher rider limit than most boards at this size', 'hoverboards-guide' ), __( 'Bluetooth speaker and app on most listings', 'hoverboards-guide' ) ),
			'cons'          => array( __( 'Heavier to carry than a 6.5-inch board', 'hoverboards-guide' ), __( 'Our articles quote different speed and rider-limit figures for different Warrior versions — check the exact listing', 'hoverboards-guide' ) ),
			'search'        => 'B07T63VSMC',
			'review_slug'   => 'the-best-hoverboard-reviews',
			'source_slug'   => 'best-off-road-hoverboard',
			'image'         => 'cat-hoverboards',
			'image_note'    => __( 'Representative photo', 'hoverboards-guide' ),
			'verified'      => false,
			'checked'       => '',
		),

		'hover1-helix' => array(
			'id'            => 'hover1-helix',
			'name'          => 'Hover-1 Helix',
			'short'         => 'Hover-1 Helix',
			'brand'         => 'Hover-1',
			'kind'          => 'hoverboard',
			'role'          => 'budget',
			'badge'         => __( 'Best budget option', 'hoverboards-guide' ),
			'why'           => __( 'Our under-$200 roundup makes this the top pick: a UL 2272-listed 6.5-inch board with LED wheels and a speaker from a widely stocked brand.', 'hoverboards-guide' ),
			'best_for'      => __( 'First boards on smooth pavement and indoors', 'hoverboards-guide' ),
			'wheel'         => '6.5" solid',
			'speed'         => 'Up to 7 mph',
			'range'         => '7–9 mi',
			'weight_cap'    => '220 lb',
			'certification' => 'UL 2272',
			'bluetooth'     => true,
			'pros'          => array( __( 'Low price from a brand with US support', 'hoverboards-guide' ), __( 'Light enough for kids to carry', 'hoverboards-guide' ), __( 'LED wheels and built-in speaker', 'hoverboards-guide' ) ),
			'cons'          => array( __( 'Small solid wheels struggle on grass and cracks', 'hoverboards-guide' ), __( 'Short range on a single charge', 'hoverboards-guide' ) ),
			'search'        => 'B0BXCVL6KD',
			'review_slug'   => 'hover-1-helix-hoverboard-the-future-of-personal-transportation',
			'source_slug'   => 'best-hoverboards-under-200',
			'image'         => 'svg:bluetooth',
			'image_note'    => __( 'Representative photo', 'hoverboards-guide' ),
			'verified'      => false,
			'checked'       => '',
		),

		'gyroor-g13' => array(
			'id'            => 'gyroor-g13',
			'name'          => 'Gyroor G13',
			'short'         => 'Gyroor G13',
			'brand'         => 'Gyroor',
			'kind'          => 'hoverboard',
			'role'          => 'kids',
			'badge'         => __( 'Best for kids', 'hoverboards-guide' ),
			'why'           => __( 'Our kids’ guide rates it the best overall value for ages 6–12: a capped top speed, 6.5-inch off-road tires and a 176 lb limit that leaves room to grow.', 'hoverboards-guide' ),
			'best_for'      => __( 'Children roughly 6–12 riding under supervision', 'hoverboards-guide' ),
			'wheel'         => '6.5" off-road',
			'speed'         => 'Up to 7.75 mph',
			'range'         => 'Up to 8 mi',
			'weight_cap'    => '176 lb',
			'certification' => 'UL 2272',
			'bluetooth'     => true,
			'pros'          => array( __( 'Speed is capped at a level that suits beginners', 'hoverboards-guide' ), __( 'Treaded tires cope with short grass and driveways', 'hoverboards-guide' ), __( 'Self-balancing start-up mode', 'hoverboards-guide' ) ),
			'cons'          => array( __( '176 lb limit rules out most adults', 'hoverboards-guide' ), __( 'Range figures vary between listings', 'hoverboards-guide' ) ),
			'search'        => 'Gyroor G13 all terrain hoverboard kids',
			'review_slug'   => 'best-hoverboard-for-kids-review-complete-buying-guide',
			'source_slug'   => 'best-hoverboard-for-kids-review-complete-buying-guide',
			'image'         => 'cat-hoverboards-kids',
			'image_note'    => __( 'Representative photo', 'hoverboards-guide' ),
			'verified'      => false,
			'checked'       => '',
		),

		'swagtron-t6' => array(
			'id'            => 'swagtron-t6',
			'name'          => 'Swagtron T6 Outlaw',
			'short'         => 'Swagtron T6',
			'brand'         => 'Swagtron',
			'kind'          => 'hoverboard',
			'role'          => 'adults',
			'badge'         => __( 'Best for adults', 'hoverboards-guide' ),
			'why'           => __( 'The highest rider limit in our adult roundup (420 lb) with 10-inch air-filled tires, which is why we point heavier and taller riders here first.', 'hoverboards-guide' ),
			'best_for'      => __( 'Big and tall adults, rough driveways and trails', 'hoverboards-guide' ),
			'wheel'         => '10" pneumatic',
			'speed'         => 'Up to 12 mph',
			'range'         => 'Up to 12 mi',
			'weight_cap'    => '420 lb',
			'certification' => 'UL 2272',
			'bluetooth'     => true,
			'pros'          => array( __( 'Highest weight rating of any board we cover', 'hoverboards-guide' ), __( 'Air-filled tires soak up bumps', 'hoverboards-guide' ), __( 'Faster than typical 6.5-inch boards', 'hoverboards-guide' ) ),
			'cons'          => array( __( 'Heavy and bulky to carry', 'hoverboards-guide' ), __( 'Pneumatic tires need occasional air', 'hoverboards-guide' ), __( 'Costs more than entry-level boards', 'hoverboards-guide' ) ),
			'search'        => 'B01N6NQOA6',
			'review_slug'   => 'the-best-hoverboards-for-adults',
			'source_slug'   => 'the-best-hoverboards-for-adults',
			'image'         => 'hero-hoverboard-shore',
			'image_note'    => __( 'Representative photo', 'hoverboards-guide' ),
			'verified'      => false,
			'checked'       => '',
		),

		'hover1-titan' => array(
			'id'            => 'hover1-titan',
			'name'          => 'Hover-1 Titan',
			'short'         => 'Hover-1 Titan',
			'brand'         => 'Hover-1',
			'kind'          => 'hoverboard',
			'role'          => 'all-terrain',
			'badge'         => __( 'Best all-terrain value', 'hoverboards-guide' ),
			'why'           => __( 'Our review calls it the budget way into big air-filled tires: 10-inch wheels and a 265 lb rating at a price closer to 6.5-inch boards.', 'hoverboards-guide' ),
			'best_for'      => __( 'Grass, gravel and dirt paths on a budget', 'hoverboards-guide' ),
			'wheel'         => '10" pneumatic',
			'speed'         => 'Up to 7–8 mph',
			'range'         => 'Up to 8 mi',
			'weight_cap'    => '265 lb',
			'certification' => 'UL 2272',
			'bluetooth'     => true,
			'pros'          => array( __( 'Big air-filled tires for the money', 'hoverboards-guide' ), __( 'Comfortable for adults up to the rated limit', 'hoverboards-guide' ), __( 'Three ride modes for beginners to experienced riders', 'hoverboards-guide' ) ),
			'cons'          => array( __( 'Slower than the Warrior or T6', 'hoverboards-guide' ), __( 'Short 90-day warranty quoted in our review', 'hoverboards-guide' ) ),
			'search'        => 'Hover-1 Titan hoverboard 10 inch',
			'review_slug'   => 'hover-1-titan-hoverboard',
			'source_slug'   => 'hover-1-titan-hoverboard',
			'image'         => 'svg:terrain',
			'image_note'    => __( 'Representative photo', 'hoverboards-guide' ),
			'verified'      => false,
			'checked'       => '',
		),

		'gotrax-edge' => array(
			'id'            => 'gotrax-edge',
			'name'          => 'Gotrax Edge',
			'short'         => 'Gotrax Edge',
			'brand'         => 'Gotrax',
			'kind'          => 'hoverboard',
			'role'          => 'beginners',
			'badge'         => __( 'Best for beginners', 'hoverboards-guide' ),
			'why'           => __( 'Our main roundup’s “first board” pick: a slow, light 6.5-inch board that keeps the learning curve gentle without a big outlay.', 'hoverboards-guide' ),
			'best_for'      => __( 'First-time riders on smooth floors and driveways', 'hoverboards-guide' ),
			'wheel'         => '6.5" solid',
			'speed'         => 'Up to 6.2 mph',
			'range'         => 'About 2.5 mi',
			'weight_cap'    => '176 lb',
			'certification' => 'UL 2272',
			'bluetooth'     => false,
			'pros'          => array( __( 'Low top speed suits nervous beginners', 'hoverboards-guide' ), __( 'Light and easy to store', 'hoverboards-guide' ), __( 'Inexpensive entry point', 'hoverboards-guide' ) ),
			'cons'          => array( __( 'Very short range', 'hoverboards-guide' ), __( 'Pavement only; no grass or gravel', 'hoverboards-guide' ), __( '176 lb limit', 'hoverboards-guide' ) ),
			'search'        => 'Gotrax Edge hoverboard 6.5 inch',
			'review_slug'   => 'the-best-hoverboard-reviews',
			'source_slug'   => 'the-best-hoverboard-reviews',
			'image'         => 'product-hoverboard-kids',
			'image_note'    => __( 'Representative photo', 'hoverboards-guide' ),
			'verified'      => false,
			'checked'       => '',
		),

		'ninebot-s' => array(
			'id'            => 'ninebot-s',
			'name'          => 'Segway Ninebot S',
			'short'         => 'Ninebot S',
			'brand'         => 'Segway',
			'kind'          => 'hoverboard',
			'role'          => 'premium',
			'badge'         => __( 'Best with knee bar', 'hoverboards-guide' ),
			'why'           => __( 'The premium pick in our roundup: a knee-control bar and 10.5-inch air tires make it the easiest self-balancing ride to learn, with the longest advertised range here.', 'hoverboards-guide' ),
			'best_for'      => __( 'Beginners who want stability, campus and park riding', 'hoverboards-guide' ),
			'wheel'         => '10.5" pneumatic',
			'speed'         => 'Up to 10 mph',
			'range'         => 'Up to 13.7 mi',
			'weight_cap'    => '220 lb',
			'certification' => 'UL 2272',
			'bluetooth'     => true,
			'pros'          => array( __( 'Knee bar steering is quick to learn', 'hoverboards-guide' ), __( 'Longest advertised range of our picks', 'hoverboards-guide' ), __( 'App with speed limits and anti-theft', 'hoverboards-guide' ) ),
			'cons'          => array( __( 'Costs considerably more than classic hoverboards', 'hoverboards-guide' ), __( '220 lb rider limit', 'hoverboards-guide' ) ),
			'search'        => 'Segway Ninebot S self balancing scooter',
			'review_slug'   => 'the-best-hoverboard-reviews',
			'source_slug'   => 'the-best-hoverboard-reviews',
			'image'         => 'product-ninebot-s',
			'image_note'    => __( 'Representative photo', 'hoverboards-guide' ),
			'verified'      => false,
			'checked'       => '',
		),
	);
}

/** Field list used by the admin editor and the merge below. */
function hbg_product_fields() {
	return array( 'name', 'short', 'brand', 'kind', 'role', 'badge', 'why', 'best_for', 'wheel', 'speed', 'range', 'weight_cap', 'certification', 'bluetooth', 'pros', 'cons', 'search', 'review_slug', 'source_slug', 'image', 'image_id', 'image_note', 'verified', 'checked', 'hidden' );
}

/**
 * Products: defaults merged with the owner's edits (option `hbg_products_v3`).
 * Hidden products are dropped; new products added in the editor are included.
 */
function hbg_get_products() {
	static $products = null;
	if ( null !== $products ) {
		return $products;
	}
	$defaults = hbg_default_products();
	$saved    = get_option( 'hbg_products_v3', array() );
	$merged   = $defaults;
	if ( is_array( $saved ) ) {
		foreach ( $saved as $id => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$base = isset( $merged[ $id ] ) ? $merged[ $id ] : array( 'id' => $id, 'kind' => 'hoverboard', 'image' => 'cat-hoverboards', 'image_note' => __( 'Representative photo', 'hoverboards-guide' ), 'pros' => array(), 'cons' => array(), 'bluetooth' => false, 'verified' => false, 'checked' => '', 'role' => '' );
			$merged[ $id ] = array_merge( $base, $row, array( 'id' => $id ) );
		}
	}
	foreach ( $merged as $id => $p ) {
		if ( ! empty( $p['hidden'] ) || empty( $p['name'] ) ) {
			unset( $merged[ $id ] );
		}
	}
	$products = apply_filters( 'hbg_products', $merged );
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

/** Roles in display order; the quick-recommendations section shows one product per role. */
function hbg_product_roles() {
	return array(
		'overall'     => __( 'Best overall', 'hoverboards-guide' ),
		'budget'      => __( 'Best budget option', 'hoverboards-guide' ),
		'kids'        => __( 'Best for kids', 'hoverboards-guide' ),
		'adults'      => __( 'Best for adults', 'hoverboards-guide' ),
		'all-terrain' => __( 'Best all-terrain option', 'hoverboards-guide' ),
		'beginners'   => __( 'Best for beginners', 'hoverboards-guide' ),
		'premium'     => __( 'Premium pick', 'hoverboards-guide' ),
	);
}

function hbg_get_top_picks( $limit = 6 ) {
	$picks = array();
	$all   = hbg_get_products();
	foreach ( array_keys( hbg_product_roles() ) as $role ) {
		foreach ( $all as $p ) {
			if ( isset( $p['role'] ) && $role === $p['role'] && 'hoverboard' === $p['kind'] ) {
				$picks[] = $p;
				break;
			}
		}
	}
	$ids = get_theme_mod( 'hbg_pick_ids', '' );
	if ( $ids ) {
		$custom = hbg_products_by_ids( array_map( 'trim', explode( ',', $ids ) ) );
		if ( $custom ) {
			$picks = $custom;
		}
	}
	return array_slice( apply_filters( 'hbg_top_picks', $picks ), 0, max( 1, (int) $limit ) );
}

function hbg_get_compare_products() {
	$all = array_values( array_filter( hbg_get_products(), function ( $p ) { return 'hoverboard' === $p['kind']; } ) );
	return apply_filters( 'hbg_compare_products', $all );
}

/** Numeric helpers for sorting (first number in the string). */
function hbg_num( $value ) {
	return (float) preg_replace( '/^[^\d.]*([\d.]+).*$/s', '$1', (string) $value );
}

// -------------------------------------------------------------------------
// Topic hubs: curated groups of existing articles. The setup tool can turn each
// hub into a real child category of "Hoverboards"; until then the hub links to
// its lead article. Nothing is created automatically.
// -------------------------------------------------------------------------
function hbg_topic_hubs() {
	return apply_filters( 'hbg_topic_hubs', array(
		'hoverboards-for-kids' => array(
			'name'   => __( 'Hoverboards for Kids', 'hoverboards-guide' ),
			'blurb'  => __( 'Age-by-age picks, speed caps, weight limits and the protective gear that matters.', 'hoverboards-guide' ),
			'desc'   => __( 'Hoverboards for children: our picks by age, what UL 2272 means, sensible speed limits, weight ratings and the helmet and pads that prevent most injuries. Always follow the manufacturer’s age guidance and supervise young riders.', 'hoverboards-guide' ),
			'image'  => 'cat-hoverboards-kids',
			'lead'   => 'best-hoverboard-for-kids-review-complete-buying-guide',
			'posts'  => array( 'best-hoverboard-for-kids-review-complete-buying-guide', 'safe-hoverboards-for-kids', 'best-hoverboard-for-8-year-old', 'hoverboard-ages', 'top-4-hoverboards-for-girls', 'how-much-does-kids-hoverboard-costs', 'introducing-the-hover-1-the-ultimate-hoverboard-for-young-riders', 'gotrax-lil-cub-hoverboard-review', 'best-hoverboard-helmets', 'hoverboard-with-handle' ),
		),
		'hoverboards-for-adults' => array(
			'name'   => __( 'Hoverboards for Adults', 'hoverboards-guide' ),
			'blurb'  => __( 'Weight limits, bigger wheels and the boards that suit grown-up riders.', 'hoverboards-guide' ),
			'desc'   => __( 'Hoverboards for adults and heavier riders: which wheel sizes and weight ratings to look for, our adult picks, and how hoverboards compare with scooters and other rides.', 'hoverboards-guide' ),
			'image'  => 'cat-hoverboards',
			'lead'   => 'the-best-hoverboards-for-adults',
			'posts'  => array( 'the-best-hoverboards-for-adults', 'best-hoverboard-for-heavy-adults', 'self-balancing-scooter-for-adults', 'hoverboard-weight-limit', 'business-use-hoverboard-with-high-load-capacity-and-durability-top-choices-and-buying-guide', 'hoverboard-vs-electric-scooter', 'hoverboard-vs-onewheel', 'hoverboard-vs-bicycle-weighing-the-pros-and-cons-for-urban-commuting', 'hoverboard-vs-electric-unicycle' ),
		),
		'all-terrain-hoverboards' => array(
			'name'   => __( 'All-Terrain Hoverboards', 'hoverboards-guide' ),
			'blurb'  => __( 'Grass, gravel and dirt: 8.5 and 10-inch boards and what they can really handle.', 'hoverboards-guide' ),
			'desc'   => __( 'Off-road and all-terrain hoverboards: how 8.5 and 10-inch tires compare, which surfaces are realistic, and the big-wheel boards we cover.', 'hoverboards-guide' ),
			'image'  => 'hero-hoverboard-shore',
			'lead'   => 'best-off-road-hoverboard',
			'posts'  => array( 'best-off-road-hoverboard', '10-best-off-road-hoverboards-for-all-terrain-types', 'is-a-hoverboard-ride-suitable-for-grass-or-gravel', 'can-hoverboards-ride-on-grass-or-gravel', 'best-big-wheel-hoverboards', '6-5-vs-8-5-hoverboard', 'can-you-ride-a-hoverboard-in-snow', 'hover-1-titan-hoverboard', 'exploring-the-advanced-features-of-the-hover-1-titan-hoverboard', 'best-waterproof-hoverboards' ),
		),
		'budget-hoverboards' => array(
			'name'   => __( 'Budget Hoverboards', 'hoverboards-guide' ),
			'blurb'  => __( 'What you get under $200 and where cheap boards cut corners.', 'hoverboards-guide' ),
			'desc'   => __( 'Budget hoverboards: what to expect at lower prices, what to check before buying a cheap board, and the affordable models we have reviewed.', 'hoverboards-guide' ),
			'image'  => 'svg:budget',
			'lead'   => 'best-hoverboards-under-200',
			'posts'  => array( 'best-hoverboards-under-200', 'how-much-do-hoverboards-cost', 'how-much-does-kids-hoverboard-costs', 'how-to-spot-a-fake-vs-real-hoverboard', 'sisigad-hoverboard-review', 'gotrax-nova-pro-hoverboard-review', 'hover-1-h1-hoverboard-review', 'hover-1-helix-hoverboard-the-future-of-personal-transportation' ),
		),
		'bluetooth-hoverboards' => array(
			'name'   => __( 'Bluetooth Hoverboards', 'hoverboards-guide' ),
			'blurb'  => __( 'Speakers, apps and LED wheels, and whether they are worth paying for.', 'hoverboards-guide' ),
			'desc'   => __( 'Hoverboards with Bluetooth speakers, companion apps and LED lighting: which features are useful, which are gimmicks, and the models we have looked at.', 'hoverboards-guide' ),
			'image'  => 'svg:bluetooth',
			'lead'   => 'best-bluetooth-hoverboards',
			'posts'  => array( 'best-bluetooth-hoverboards', 'black-hoverboards-with-bluetooth', 'hoverboards-with-music-enabled-features-for-social-events', 'hover-1-helix-hoverboard-the-future-of-personal-transportation', 'swagtron-t580-hoverboard-review', 'is-there-any-hoverboard-app', 'hover-1-sypher-the-smart-hoverboard' ),
		),
		'hoverboard-reviews' => array(
			'name'   => __( 'Hoverboard Reviews', 'hoverboards-guide' ),
			'blurb'  => __( 'Model-by-model reviews and head-to-head brand comparisons.', 'hoverboards-guide' ),
			'desc'   => __( 'Individual hoverboard reviews and brand comparisons. Our reviews are desk research built from manufacturer specifications, owner feedback and safety notices; where we have not ridden a board, we say so.', 'hoverboards-guide' ),
			'image'  => 'cat-self-balancing',
			'lead'   => 'the-best-hoverboard-reviews',
			'posts'  => array( 'the-best-hoverboard-reviews', 'hover-1-titan-hoverboard', 'hover-1-helix-hoverboard-the-future-of-personal-transportation', 'hover-1-h1-hoverboard-review', 'hover-1-matrix-hoverboard-review', 'the-hover-1-i-200-hoverboard-review', 'hover-1-sypher-the-smart-hoverboard', 'hover-1-ranger-hoverboard-revolutionizing-personal-mobility-in-2025', 'swagtron-t1-hoverboard-review', 'swagtron-t580-hoverboard-review', 'gotrax-nova-pro-hoverboard-review', 'gotrax-lil-cub-hoverboard-review', 'sisigad-hoverboard-review', 'halo-go-2-hoverboard-review', 'megawheels-hoverboard-review', 'riorand-hoverboard-review', 'tomollo-hoverboard-review', 'fortech-hoverboard-review', 'leray-self-balancing-hoverboard', 'glyro-hoverboard-review-in-2024', 'smart-balance-wheel-hoverboard-review', 'street-saw-hoverboard-review', 'iscooter-hoverboard-review', 'astroboard-hoverboard-review', 'airwalk-self-balancing-scooter-review', 'swagtron-vs-gotrax-hoverboard', 'hover-1-vs-jetson-hoverboard-which-is-better', 'gotrax-hoverboard-vs-hover-1-in-depth-feature-comparison', 'gyrocopter-vs-gotrax-hoverboar', 'best-hoverboard-brands' ),
		),
		'hoverboard-safety' => array(
			'name'   => __( 'Hoverboard Safety', 'hoverboards-guide' ),
			'blurb'  => __( 'UL 2272, battery fires, helmets, local laws and riding safely.', 'hoverboards-guide' ),
			'desc'   => __( 'Hoverboard safety: what UL 2272 certification covers, why older boards caught fire, charging habits, protective gear and the rules on where you can ride.', 'hoverboards-guide' ),
			'image'  => 'svg:safety',
			'lead'   => 'ultimate-guide-to-ride-a-hoverboard-safely',
			'posts'  => array( 'ultimate-guide-to-ride-a-hoverboard-safely', 'do-hoverboards-still-catch-fire', 'why-hoverboards-are-so-dangerous', 'reasons-hoverboards-keep-exploding', 'how-to-spot-a-fake-vs-real-hoverboard', 'best-hoverboard-helmets', 'safe-hoverboards-for-kids', 'hoverboard-weight-limit', 'how-to-use-a-hoverboard', 'are-hoverboards-legal-in-your-state', 'new-laws-for-hoverboard-2020', 'can-you-ride-a-hoverboard-on-the-sidewalk', 'are-hoverboards-allowed-on-planes', 'can-you-overcharge-a-hoverboard' ),
		),
		'hoverboard-troubleshooting' => array(
			'name'   => __( 'Troubleshooting & Maintenance', 'hoverboards-guide' ),
			'blurb'  => __( 'Beeping, shaking, won’t charge: fixes, calibration and part replacement.', 'hoverboards-guide' ),
			'desc'   => __( 'Hoverboard troubleshooting and maintenance: calibration, beeping and shaking fixes, charging and battery care, tire, battery and sensor replacement, and when to stop and contact the manufacturer.', 'hoverboards-guide' ),
			'image'  => 'svg:maintenance',
			'lead'   => 'hoverboard-troubleshooting',
			'posts'  => array( 'hoverboard-troubleshooting', 'how-to-troubleshoot-a-hoverboard-and-fix-the-issues', 'hoverboard-problems-and-solutions', 'why-is-my-hoverboard-beeping', 'why-does-my-hoverboard-shake', 'my-hoverboard-turns-off-randomly-how-do-i-resolve-this-problem', 'hoverboard-wont-turn-off', 'how-do-you-calibrate-or-reset-your-hoverboard-there-are-some-easy-steps-to-follow', 'how-to-replace-hoverboard-tires', 'how-to-replace-the-battery-in-your-hoverboard-a-step-by-step-guide', 'how-to-replace-hoverboard-footpad-pressure-sensor', 'how-to-clean-hoverboard-wheels', 'hoverboard-loose-wheel-screws-fixing-tutorial', 'how-to-fix-a-beeping-or-faulty-jetson-hoverboard', 'can-you-overcharge-a-hoverboard', 'how-long-should-a-hoverboard-battery-last', 'best-hoverboard-chargers', 'gotrax-hoverfly-eco-charger-easy-to-operate' ),
		),
	) );
}

/** Resolve a hub to a destination: its category (if the setup tool created it) or its lead post. */
function hbg_hub_link( $slug ) {
	$hubs = hbg_topic_hubs();
	if ( ! isset( $hubs[ $slug ] ) ) {
		return array( 'url' => '', 'count' => 0, 'term' => null );
	}
	$term = get_term_by( 'slug', $slug, 'category' );
	if ( $term && ! is_wp_error( $term ) && $term->count > 0 ) {
		return array( 'url' => get_category_link( $term ), 'count' => (int) $term->count, 'term' => $term );
	}
	$count = 0;
	foreach ( $hubs[ $slug ]['posts'] as $ps ) {
		if ( hbg_post_url_by_slug( $ps ) ) {
			$count++;
		}
	}
	return array( 'url' => hbg_post_url_by_slug( $hubs[ $slug ]['lead'] ), 'count' => $count, 'term' => null );
}

/** Hubs that contain a post slug (used for curated related articles and breadcrumbs). */
function hbg_hubs_for_slug( $slug ) {
	$out = array();
	foreach ( hbg_topic_hubs() as $key => $hub ) {
		if ( in_array( $slug, $hub['posts'], true ) ) {
			$out[] = $key;
		}
	}
	return $out;
}

// -------------------------------------------------------------------------
// "Learn before you buy" (homepage section F)
// -------------------------------------------------------------------------
function hbg_learn_items() {
	return apply_filters( 'hbg_learn_items', array(
		array( 'slug' => 'how-to-use-a-hoverboard',                     'label' => __( 'How to ride a hoverboard for the first time', 'hoverboards-guide' ),      'icon' => 'user' ),
		array( 'slug' => '6-5-vs-8-5-hoverboard',                       'label' => __( 'How to choose the right wheel size', 'hoverboards-guide' ),               'icon' => 'ruler' ),
		array( 'slug' => 'can-hoverboards-ride-on-grass-or-gravel',     'label' => __( 'Can hoverboards ride on grass or gravel?', 'hoverboards-guide' ),         'icon' => 'terrain' ),
		array( 'slug' => 'how-to-spot-a-fake-vs-real-hoverboard',       'label' => __( 'How to check safety certification and spot a fake', 'hoverboards-guide' ), 'icon' => 'shield' ),
		array( 'slug' => 'can-you-overcharge-a-hoverboard',             'label' => __( 'How to charge and look after the battery', 'hoverboards-guide' ),          'icon' => 'battery' ),
		array( 'slug' => 'hoverboard-troubleshooting',                  'label' => __( 'Common hoverboard problems and fixes', 'hoverboards-guide' ),            'icon' => 'wrench' ),
	) );
}

// -------------------------------------------------------------------------
// Featured buying guide summary (homepage section D)
// -------------------------------------------------------------------------
function hbg_guide_decisions() {
	return apply_filters( 'hbg_guide_decisions', array(
		array( 'icon' => 'ruler',    'title' => __( 'Wheel size', 'hoverboards-guide' ),        'text' => __( '6.5-inch for smooth floors, 8.5-inch for mixed ground, 10-inch air tires for grass and gravel.', 'hoverboards-guide' ) ),
		array( 'icon' => 'terrain',  'title' => __( 'Terrain', 'hoverboards-guide' ),           'text' => __( 'Match tread and tire type to where the board will actually be ridden.', 'hoverboards-guide' ) ),
		array( 'icon' => 'weight',   'title' => __( 'Weight capacity', 'hoverboards-guide' ),   'text' => __( 'Pick a rating comfortably above the heaviest rider; limits run from about 80 lb to 420 lb.', 'hoverboards-guide' ) ),
		array( 'icon' => 'battery',  'title' => __( 'Battery and range', 'hoverboards-guide' ), 'text' => __( 'Advertised range assumes a light rider on flat ground; plan for less.', 'hoverboards-guide' ) ),
		array( 'icon' => 'shield',   'title' => __( 'Safety certification', 'hoverboards-guide' ), 'text' => __( 'Look for UL 2272 on the whole board, not just the battery or charger.', 'hoverboards-guide' ) ),
		array( 'icon' => 'bolt',     'title' => __( 'Charging', 'hoverboards-guide' ),          'text' => __( 'Use the supplied charger, charge on a hard surface and never leave a board charging unattended overnight.', 'hoverboards-guide' ) ),
		array( 'icon' => 'tag',      'title' => __( 'Budget', 'hoverboards-guide' ),            'text' => __( 'Decide what the price must include before comparing: tires, certification, warranty, then extras.', 'hoverboards-guide' ) ),
	) );
}

// -------------------------------------------------------------------------
// Finder: four questions → a product plus the guides to read first.
// -------------------------------------------------------------------------
function hbg_finder_questions() {
	return array(
		array(
			'key'     => 'who',
			'q'       => __( 'Who will ride it most?', 'hoverboards-guide' ),
			'options' => array(
				array( 'value' => 'child', 'label' => __( 'A child (under 12)', 'hoverboards-guide' ) ),
				array( 'value' => 'teen',  'label' => __( 'A teenager', 'hoverboards-guide' ) ),
				array( 'value' => 'adult', 'label' => __( 'An adult', 'hoverboards-guide' ) ),
			),
		),
		array(
			'key'     => 'terrain',
			'q'       => __( 'Where will it be used?', 'hoverboards-guide' ),
			'options' => array(
				array( 'value' => 'smooth', 'label' => __( 'Indoors and smooth pavement', 'hoverboards-guide' ) ),
				array( 'value' => 'mixed',  'label' => __( 'Driveways, sidewalks and parks', 'hoverboards-guide' ) ),
				array( 'value' => 'rough',  'label' => __( 'Grass, gravel and dirt paths', 'hoverboards-guide' ) ),
			),
		),
		array(
			'key'     => 'budget',
			'q'       => __( 'Roughly what budget?', 'hoverboards-guide' ),
			'options' => array(
				array( 'value' => 'low',  'label' => __( 'Keep it as low as possible', 'hoverboards-guide' ) ),
				array( 'value' => 'mid',  'label' => __( 'Mid-range', 'hoverboards-guide' ) ),
				array( 'value' => 'high', 'label' => __( 'Happy to pay for the best fit', 'hoverboards-guide' ) ),
			),
		),
		array(
			'key'     => 'priority',
			'q'       => __( 'What matters most?', 'hoverboards-guide' ),
			'options' => array(
				array( 'value' => 'easy',     'label' => __( 'Easy to learn and carry', 'hoverboards-guide' ) ),
				array( 'value' => 'wheels',   'label' => __( 'Bigger wheels and comfort', 'hoverboards-guide' ) ),
				array( 'value' => 'features', 'label' => __( 'Lights, speaker and app', 'hoverboards-guide' ) ),
				array( 'value' => 'range',    'label' => __( 'Longer range', 'hoverboards-guide' ) ),
			),
		),
	);
}

/** Ordered rules; the first rule whose conditions all match wins. */
function hbg_finder_rules() {
	return apply_filters( 'hbg_finder_rules', array(
		array( 'who' => 'child', 'budget' => 'low',                       'id' => 'gotrax-edge',       'guides' => array( 'kids', 'safety', 'ages' ) ),
		array( 'who' => 'child', 'terrain' => 'rough',                    'id' => 'gyroor-g13',        'guides' => array( 'kids', 'grass_gravel', 'safety' ) ),
		array( 'who' => 'child', 'priority' => 'features',                'id' => 'hover1-helix',      'guides' => array( 'kids', 'bluetooth', 'safety' ) ),
		array( 'who' => 'child',                                          'id' => 'gyroor-g13',        'guides' => array( 'kids', 'ages', 'safety' ) ),
		array( 'terrain' => 'rough', 'budget' => 'high',                  'id' => 'swagtron-t6',       'guides' => array( 'all_terrain', 'adults', 'weight_limit' ) ),
		array( 'terrain' => 'rough', 'budget' => 'low',                   'id' => 'hover1-titan',      'guides' => array( 'all_terrain', 'grass_gravel', 'wheel_size' ) ),
		array( 'terrain' => 'rough',                                      'id' => 'gyroor-warrior-g2', 'guides' => array( 'all_terrain', 'wheel_size', 'best' ) ),
		array( 'who' => 'adult', 'priority' => 'range',                   'id' => 'ninebot-s',         'guides' => array( 'adults', 'best', 'safety' ) ),
		array( 'who' => 'adult', 'priority' => 'easy',                    'id' => 'ninebot-s',         'guides' => array( 'adults', 'how_to_ride', 'best' ) ),
		array( 'budget' => 'low',                                         'id' => 'hover1-helix',      'guides' => array( 'budget', 'best', 'safety' ) ),
		array( 'terrain' => 'smooth', 'priority' => 'features',           'id' => 'hover1-helix',      'guides' => array( 'bluetooth', 'best', 'safety' ) ),
		array( 'terrain' => 'smooth',                                     'id' => 'hover1-helix',      'guides' => array( 'best', 'wheel_size', 'safety' ) ),
		array( 'priority' => 'wheels',                                    'id' => 'hover1-titan',      'guides' => array( 'all_terrain', 'wheel_size', 'best' ) ),
		array(                                                            'id' => 'gyroor-warrior-g2', 'guides' => array( 'best', 'buying_guide', 'safety' ) ),
	) );
}

// -------------------------------------------------------------------------
// FAQ (homepage). Answers avoid claims the site cannot back up.
// -------------------------------------------------------------------------
function hbg_get_faqs() {
	return apply_filters( 'hbg_faqs', array(
		array(
			'q' => __( 'Are hoverboards safe to buy now?', 'hoverboards-guide' ),
			'a' => __( 'Boards certified to UL 2272 have passed electrical and fire-safety testing of the complete product, which addressed the battery fires reported around 2015–2016. Buy a certified board from an established seller, respect the weight limit, charge with the supplied charger on a hard surface, and wear a helmet. Certification reduces risk; it does not remove it.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'How do I check that a hoverboard is UL 2272 certified?', 'hoverboards-guide' ),
			'a' => __( 'Look for the certification mark on the packaging and the holographic label on the underside of the board, and ask the seller for the certificate. A UL-listed battery or charger alone does not make the whole board compliant. If the price is far below similar boards, be suspicious.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'What wheel size should I choose?', 'hoverboards-guide' ),
			'a' => __( '6.5-inch solid wheels suit smooth floors and clean pavement. 8.5-inch tires add enough clearance for cracks, short grass and packed dirt. 10-inch air-filled tires are the most comfortable on rough ground but need occasional inflation and make the board heavier.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'What age is a hoverboard suitable for?', 'hoverboards-guide' ),
			'a' => __( 'Manufacturers usually state a minimum age of 8 and a minimum rider weight of around 44 lb; some kids’ models are sold for ages 5 and up with a lower speed cap. Age alone is not a safety guarantee: rider weight, balance, supervision and protective gear matter just as much.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'How far does a hoverboard go on a charge?', 'hoverboards-guide' ),
			'a' => __( 'Advertised ranges run from about 3 to 13 miles depending on the model. Expect less than the figure on the box: rider weight, hills, speed, temperature and tire pressure all reduce range.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'Can adults ride hoverboards?', 'hoverboards-guide' ),
			'a' => __( 'Yes. Look for a weight rating comfortably above your own: 8.5-inch boards are often rated around 220–265 lb and some 10-inch boards up to 420 lb. Bigger tires also make the ride steadier for taller riders.', 'hoverboards-guide' ),
		),
		array(
			'q' => __( 'Hoverboard or electric scooter?', 'hoverboards-guide' ),
			'a' => __( 'A hoverboard is for short rides and fun; an electric scooter is faster, carries bags and copes better with distance and hills. If you need to get somewhere, read our scooter guides; if it is for the driveway, the park and the kids, start with the hoverboard buying guide.', 'hoverboards-guide' ),
		),
	) );
}

// -------------------------------------------------------------------------
// Secondary rides (the site also covers these; kept secondary by design)
// -------------------------------------------------------------------------
function hbg_more_rides() {
	return apply_filters( 'hbg_more_rides', array(
		array( 'slug' => 'e-scooters',  'label' => __( 'Electric scooters', 'hoverboards-guide' ) ),
		array( 'slug' => 'ebikes',      'label' => __( 'E-bikes', 'hoverboards-guide' ) ),
		array( 'slug' => 'skateboards', 'label' => __( 'Electric skateboards', 'hoverboards-guide' ) ),
		array( 'slug' => 'unicycles',   'label' => __( 'Electric unicycles', 'hoverboards-guide' ) ),
		array( 'slug' => 'bumper-cars', 'label' => __( 'Karts and bumper cars', 'hoverboards-guide' ) ),
	) );
}

// -------------------------------------------------------------------------
// Category presets: image + colour accent + blurb keyed by slug
// -------------------------------------------------------------------------
function hbg_category_presets() {
	$hubs    = hbg_topic_hubs();
	$presets = array(
		'hoverboards'    => array( 'image' => 'cat-hoverboards',       'color' => '#1d4ed8', 'blurb' => __( 'Reviews, buying advice and safety guides for every age and terrain.', 'hoverboards-guide' ) ),
		'e-scooters'     => array( 'image' => 'cat-escooters',         'color' => '#0f766e', 'blurb' => __( 'Commuter, budget and performance electric scooters.', 'hoverboards-guide' ) ),
		'scooters'       => array( 'image' => 'cat-escooters-commute', 'color' => '#0f766e', 'blurb' => __( 'Scooters for commuting, kids and weekend rides.', 'hoverboards-guide' ) ),
		'ebikes'         => array( 'image' => 'cat-ebikes',            'color' => '#15803d', 'blurb' => __( 'Electric bikes and the questions buyers ask first.', 'hoverboards-guide' ) ),
		'buying-guide'   => array( 'image' => 'cat-guides',            'color' => '#1d4ed8', 'blurb' => __( 'Plain-English buying advice before you click “buy”.', 'hoverboards-guide' ) ),
		'guide'          => array( 'image' => 'cat-guides',            'color' => '#1d4ed8', 'blurb' => __( 'Longer guides on riding, rules and maintenance.', 'hoverboards-guide' ) ),
		'how-to'         => array( 'image' => 'svg:maintenance',       'color' => '#b45309', 'blurb' => __( 'Step-by-step fixes, upgrades and tutorials.', 'hoverboards-guide' ) ),
		'reviews'        => array( 'image' => 'cat-self-balancing',    'color' => '#0b1f3a', 'blurb' => __( 'Model reviews with specifications, pros and cons.', 'hoverboards-guide' ) ),
		'top-picks'      => array( 'image' => 'cat-hoverboards',       'color' => '#1d4ed8', 'blurb' => __( 'Our current recommendations by category.', 'hoverboards-guide' ) ),
		'skateboards'    => array( 'image' => 'cat-skateboards',       'color' => '#be185d', 'blurb' => __( 'Electric skateboards and longboards.', 'hoverboards-guide' ) ),
		'longboards'     => array( 'image' => 'cat-skateboards',       'color' => '#be185d', 'blurb' => __( 'Cruisers, carvers and electric longboards.', 'hoverboards-guide' ) ),
		'unicycles'      => array( 'image' => 'cat-unicycles',         'color' => '#6d28d9', 'blurb' => __( 'Electric unicycles for confident riders.', 'hoverboards-guide' ) ),
		'bumper-cars'    => array( 'image' => 'cat-hoverkart',         'color' => '#b45309', 'blurb' => __( 'Hoverboard karts and bumper cars for kids.', 'hoverboards-guide' ) ),
		'charger'        => array( 'image' => 'svg:charging',          'color' => '#b45309', 'blurb' => __( 'Chargers, batteries and charging safety.', 'hoverboards-guide' ) ),
		'chargers'       => array( 'image' => 'svg:charging',          'color' => '#b45309', 'blurb' => __( 'Chargers, batteries and charging safety.', 'hoverboards-guide' ) ),
		'electric-cars'  => array( 'image' => 'cat-hoverkart',         'color' => '#475569', 'blurb' => __( 'Electric ride-ons and cars for kids.', 'hoverboards-guide' ) ),
		'safety'         => array( 'image' => 'svg:safety',            'color' => '#b91c1c', 'blurb' => __( 'Helmets, pads and riding safely.', 'hoverboards-guide' ) ),
		'kids'           => array( 'image' => 'cat-hoverboards-kids',  'color' => '#be185d', 'blurb' => __( 'Rides for kids and teens.', 'hoverboards-guide' ) ),
		'self-balancing' => array( 'image' => 'cat-self-balancing',    'color' => '#0b1f3a', 'blurb' => __( 'Self-balancing scooters with knee bars and handles.', 'hoverboards-guide' ) ),
	);
	foreach ( $hubs as $slug => $hub ) {
		$presets[ $slug ] = array( 'image' => $hub['image'], 'color' => '#1d4ed8', 'blurb' => $hub['blurb'] );
	}
	return apply_filters( 'hbg_category_presets', $presets );
}

function hbg_category_preset( $term ) {
	$default = array( 'image' => 'cat-hoverboards', 'color' => '#1d4ed8', 'blurb' => '' );
	if ( ! $term instanceof WP_Term ) {
		return $default;
	}
	$presets = hbg_category_presets();
	$slug    = $term->slug;
	if ( isset( $presets[ $slug ] ) ) {
		return wp_parse_args( $presets[ $slug ], $default );
	}
	$keywords = array( 'kid' => 'kids', 'adult' => 'hoverboards-for-adults', 'terrain' => 'all-terrain-hoverboards', 'budget' => 'budget-hoverboards', 'bluetooth' => 'bluetooth-hoverboards', 'trouble' => 'hoverboard-troubleshooting', 'safety' => 'safety', 'helmet' => 'safety', 'hoverboard' => 'hoverboards', 'scooter' => 'e-scooters', 'bike' => 'ebikes', 'guide' => 'guide', 'how' => 'how-to', 'review' => 'reviews', 'skate' => 'skateboards', 'unicycle' => 'unicycles', 'charg' => 'charger' );
	foreach ( $keywords as $needle => $preset ) {
		if ( false !== strpos( $slug, $needle ) && isset( $presets[ $preset ] ) ) {
			return wp_parse_args( $presets[ $preset ], $default );
		}
	}
	return $default;
}
