<?php
/**
 * Navigation helpers: fallback menu, dropdown chevrons and mobile drawer support.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fallback primary menu built from the site's real categories when no menu is assigned.
 */
function hbg_fallback_menu( $args = array() ) {
	$echo  = ! isset( $args['echo'] ) || $args['echo'];
	$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'nav-menu';
	$items = array();

	$groups = array(
		array( 'label' => __( 'Hoverboards', 'hoverboards-guide' ), 'slugs' => array( 'hoverboards' ), 'children' => array( 'bumper-cars', 'self-balancing' ) ),
		array( 'label' => __( 'E-Scooters', 'hoverboards-guide' ), 'slugs' => array( 'e-scooters', 'scooters' ), 'children' => array( 'scooters' ) ),
		array( 'label' => __( 'E-Bikes', 'hoverboards-guide' ), 'slugs' => array( 'ebikes' ), 'children' => array() ),
		array( 'label' => __( 'Guides', 'hoverboards-guide' ), 'slugs' => array( 'buying-guide', 'guide' ), 'children' => array( 'guide', 'how-to', 'reviews' ) ),
		array( 'label' => __( 'More rides', 'hoverboards-guide' ), 'slugs' => array( 'skateboards', 'unicycles', 'longboards' ), 'children' => array( 'skateboards', 'unicycles', 'longboards', 'electric-cars' ) ),
	);

	foreach ( $groups as $g ) {
		$term = null;
		foreach ( $g['slugs'] as $slug ) {
			$t = get_term_by( 'slug', $slug, 'category' );
			if ( $t && $t->count > 0 ) {
				$term = $t;
				break;
			}
		}
		if ( ! $term ) {
			continue;
		}
		$children = array();
		foreach ( $g['children'] as $slug ) {
			$c = get_term_by( 'slug', $slug, 'category' );
			if ( $c && $c->count > 0 && $c->term_id !== $term->term_id ) {
				$children[] = $c;
			}
		}
		$items[] = array( 'label' => $g['label'], 'url' => get_category_link( $term ), 'children' => $children );
	}

	$items[] = array( 'label' => __( 'Top Picks', 'hoverboards-guide' ), 'url' => home_url( '/#picks' ), 'children' => array() );
	$items[] = array( 'label' => __( 'Compare', 'hoverboards-guide' ), 'url' => home_url( '/#compare' ), 'children' => array() );

	$html = '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $item ) {
		$has = ! empty( $item['children'] );
		$html .= '<li class="menu-item' . ( $has ? ' menu-item-has-children' : '' ) . '">';
		$html .= '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . ( $has ? hbg_svg_icon( 'chevron-down', 14, 'menu-chevron' ) : '' ) . '</a>';
		if ( $has ) {
			$html .= '<ul class="sub-menu">';
			foreach ( $item['children'] as $c ) {
				$html .= '<li class="menu-item"><a href="' . esc_url( get_category_link( $c ) ) . '">' . esc_html( $c->name ) . '</a></li>';
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

/**
 * Append a chevron to parent items in the primary menu.
 */
function hbg_menu_item_chevron( $title, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && in_array( $args->theme_location, array( 'primary', 'mobile' ), true ) && 0 === $depth && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$title .= hbg_svg_icon( 'chevron-down', 14, 'menu-chevron' );
	}
	return $title;
}
add_filter( 'nav_menu_item_title', 'hbg_menu_item_chevron', 10, 4 );

/**
 * Output the primary menu (desktop or mobile) with fallback.
 */
function hbg_primary_menu( $location = 'primary', $class = 'nav-menu' ) {
	wp_nav_menu( array(
		'theme_location' => $location,
		'menu_class'     => $class,
		'container'      => false,
		'depth'          => 2,
		'fallback_cb'    => 'hbg_fallback_menu',
	) );
}
