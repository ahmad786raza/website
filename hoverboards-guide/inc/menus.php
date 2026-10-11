<?php
/**
 * Navigation: fallback primary menu that follows the site's information architecture,
 * dropdown chevrons and the mobile drawer markup helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The menu the theme builds when no WordPress menu is assigned to "Primary Menu".
 * Each item points to a real destination: a topic category created by the setup tool,
 * or the lead article until then. Items without a destination are skipped.
 */
function hbg_menu_blueprint() {
	$hubs  = hbg_topic_hubs();
	$items = array();

	$items[] = array( 'label' => __( 'Best Hoverboards', 'hoverboards-guide' ), 'url' => hbg_key_url( 'best' ) );

	$short = array( 'hoverboards-for-kids' => __( 'For Kids', 'hoverboards-guide' ), 'hoverboards-for-adults' => __( 'For Adults', 'hoverboards-guide' ), 'all-terrain-hoverboards' => __( 'All-Terrain', 'hoverboards-guide' ) );
	foreach ( array( 'hoverboards-for-kids', 'hoverboards-for-adults', 'all-terrain-hoverboards' ) as $slug ) {
		$link = hbg_hub_link( $slug );
		if ( $link['url'] ) {
			$items[] = array( 'label' => $hubs[ $slug ]['name'], 'short' => $short[ $slug ], 'url' => $link['url'] );
		}
	}

	$reviews = hbg_hub_link( 'hoverboard-reviews' );
	$rev_children = array();
	foreach ( array( 'budget-hoverboards', 'bluetooth-hoverboards' ) as $slug ) {
		$l = hbg_hub_link( $slug );
		if ( $l['url'] ) {
			$rev_children[] = array( 'label' => $hubs[ $slug ]['name'], 'url' => $l['url'] );
		}
	}
	if ( $reviews['url'] ) {
		$items[] = array( 'label' => __( 'Reviews', 'hoverboards-guide' ), 'url' => $reviews['url'], 'children' => $rev_children );
	}

	$guides = get_term_by( 'slug', 'buying-guide', 'category' );
	$guide_children = array();
	if ( hbg_key_url( 'buying_guide' ) ) {
		$guide_children[] = array( 'label' => __( 'Hoverboard buying guide', 'hoverboards-guide' ), 'url' => hbg_key_url( 'buying_guide' ) );
	}
	$guide_children[] = array( 'label' => __( 'Hoverboard finder', 'hoverboards-guide' ), 'url' => home_url( '/#finder' ) );
	$guide_children[] = array( 'label' => __( 'Compare specifications', 'hoverboards-guide' ), 'url' => home_url( '/#compare' ) );
	$items[] = array( 'label' => __( 'Buying Guides', 'hoverboards-guide' ), 'url' => $guides && $guides->count ? get_category_link( $guides ) : hbg_key_url( 'buying_guide' ), 'children' => $guide_children );

	$safety = hbg_hub_link( 'hoverboard-safety' );
	$fix    = hbg_hub_link( 'hoverboard-troubleshooting' );
	$sh_children = array();
	if ( $fix['url'] ) {
		$sh_children[] = array( 'label' => $hubs['hoverboard-troubleshooting']['name'], 'url' => $fix['url'] );
	}
	if ( hbg_key_url( 'how_to_ride' ) ) {
		$sh_children[] = array( 'label' => __( 'How to ride a hoverboard', 'hoverboards-guide' ), 'url' => hbg_key_url( 'how_to_ride' ) );
	}
	if ( $safety['url'] ) {
		$items[] = array( 'label' => __( 'Safety & How-To', 'hoverboards-guide' ), 'url' => $safety['url'], 'children' => $sh_children );
	}

	// Secondary rides stay in one dropdown so hoverboard content keeps the spotlight.
	$more = array();
	foreach ( hbg_more_rides() as $ride ) {
		$t = get_term_by( 'slug', $ride['slug'], 'category' );
		if ( $t && $t->count > 0 ) {
			$more[] = array( 'label' => $ride['label'], 'url' => get_category_link( $t ) );
		}
	}
	if ( $more ) {
		$items[] = array( 'label' => __( 'More rides', 'hoverboards-guide' ), 'short' => __( 'More', 'hoverboards-guide' ), 'url' => $more[0]['url'], 'children' => $more );
	}

	return apply_filters( 'hbg_menu_blueprint', array_values( array_filter( $items, function ( $i ) { return ! empty( $i['url'] ); } ) ) );
}

function hbg_fallback_menu( $args = array() ) {
	$echo  = ! isset( $args['echo'] ) || $args['echo'];
	$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'nav-menu';
	$mobile = false !== strpos( $class, 'mobile' );
	$html  = '<ul class="' . esc_attr( $class ) . '">';
	foreach ( hbg_menu_blueprint() as $item ) {
		$has   = ! empty( $item['children'] );
		$label = ( ! $mobile && ! empty( $item['short'] ) ) ? $item['short'] : $item['label'];
		$html .= '<li class="menu-item' . ( $has ? ' menu-item-has-children' : '' ) . '">';
		$html .= '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $label ) . ( $has ? hbg_svg_icon( 'chevron-down', 14, 'menu-chevron' ) : '' ) . '</a>';
		if ( $has ) {
			$html .= '<ul class="sub-menu">';
			foreach ( $item['children'] as $c ) {
				$html .= '<li class="menu-item"><a href="' . esc_url( $c['url'] ) . '">' . esc_html( $c['label'] ) . '</a></li>';
			}
			$html .= '</ul>';
		}
		$html .= '</li>';
	}
	$html .= '</ul>';
	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	return $html;
}

function hbg_menu_item_chevron( $title, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location && 0 === $depth && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$title .= hbg_svg_icon( 'chevron-down', 14, 'menu-chevron' );
	}
	return $title;
}
add_filter( 'nav_menu_item_title', 'hbg_menu_item_chevron', 10, 4 );

function hbg_primary_menu( $location = 'primary', $class = 'nav-menu' ) {
	wp_nav_menu( array(
		'theme_location' => $location,
		'menu_class'     => $class,
		'container'      => false,
		'depth'          => 2,
		'fallback_cb'    => 'hbg_fallback_menu',
	) );
}

/**
 * WordPress marks menu items that link to a homepage anchor (…/#finder, …/#compare) as "current" on the
 * front page, which then lights up their parent (e.g. "Buying Guides") next to "Home". Only a real page
 * match should be highlighted, so anchor items and the ancestors they alone made current are reset.
 */
function hbg_fix_anchor_current_items( $items ) {
	if ( ! is_front_page() || ! is_array( $items ) ) {
		return $items;
	}
	$current_classes  = array( 'current-menu-item', 'current_page_item' );
	$ancestor_classes = array( 'current-menu-ancestor', 'current-menu-parent', 'current_page_parent', 'current_page_ancestor' );
	$real_current     = array();
	foreach ( $items as $item ) {
		$is_anchor = false !== strpos( (string) $item->url, '#' );
		$classes   = is_array( $item->classes ) ? $item->classes : array();
		if ( $is_anchor && array_intersect( $current_classes, $classes ) ) {
			$item->classes = array_values( array_diff( $classes, $current_classes ) );
			$item->current = false;
			continue;
		}
		if ( array_intersect( $current_classes, $classes ) ) {
			$real_current[] = (int) $item->menu_item_parent;
		}
	}
	foreach ( $items as $item ) {
		$classes = is_array( $item->classes ) ? $item->classes : array();
		if ( array_intersect( $ancestor_classes, $classes ) && ! in_array( (int) $item->ID, $real_current, true ) ) {
			$item->classes                = array_values( array_diff( $classes, $ancestor_classes ) );
			$item->current_item_ancestor  = false;
			$item->current_item_parent    = false;
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'hbg_fix_anchor_current_items', 20 );
