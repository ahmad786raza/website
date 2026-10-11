<?php
/**
 * Original inline SVG illustrations (no external images, no licences to track).
 * Used where no suitable licensed photograph is bundled: budget, Bluetooth, safety,
 * maintenance, charging, finder, terrain and a generic hoverboard placeholder.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hbg_illustration( $name, $args = array() ) {
	$a     = wp_parse_args( $args, array( 'class' => '', 'alt' => '' ) );
	$ink   = '#0b1f3a';
	$blue  = '#1d4ed8';
	$soft  = '#dbe6ff';
	$bg    = '#eef3fb';
	$white = '#ffffff';
	$grey  = '#c7d0dd';

	$board = function ( $x, $y, $scale = 1, $color = '#1d4ed8' ) use ( $ink, $grey, $white ) {
		return '<g transform="translate(' . $x . ' ' . $y . ') scale(' . $scale . ')">'
			. '<ellipse cx="100" cy="86" rx="92" ry="7" fill="' . $ink . '" opacity="0.08"/>'
			. '<rect x="34" y="44" width="132" height="26" rx="12" fill="' . $color . '"/>'
			. '<rect x="48" y="36" width="42" height="14" rx="6" fill="' . $ink . '" opacity="0.85"/>'
			. '<rect x="110" y="36" width="42" height="14" rx="6" fill="' . $ink . '" opacity="0.85"/>'
			. '<circle cx="34" cy="62" r="24" fill="' . $ink . '"/><circle cx="34" cy="62" r="11" fill="' . $grey . '"/><circle cx="34" cy="62" r="4" fill="' . $white . '"/>'
			. '<circle cx="166" cy="62" r="24" fill="' . $ink . '"/><circle cx="166" cy="62" r="11" fill="' . $grey . '"/><circle cx="166" cy="62" r="4" fill="' . $white . '"/>'
			. '</g>';
	};

	switch ( $name ) {
		case 'budget':
			$body = '<rect width="400" height="300" fill="' . $bg . '"/><circle cx="300" cy="90" r="46" fill="' . $soft . '"/>'
				. '<path d="M274 70h40l22 22-40 40-22-22z" fill="' . $white . '" stroke="' . $ink . '" stroke-width="4" stroke-linejoin="round"/><circle cx="286" cy="82" r="5" fill="' . $ink . '"/>'
				. '<text x="300" y="118" font-family="system-ui,sans-serif" font-size="24" font-weight="700" fill="' . $blue . '" text-anchor="middle" transform="rotate(-45 300 110)">$</text>'
				. $board( 60, 130, 1.1 );
			break;
		case 'bluetooth':
			$body = '<rect width="400" height="300" fill="' . $bg . '"/><circle cx="300" cy="86" r="44" fill="' . $soft . '"/>'
				. '<path d="M288 62l24 24-24 24M300 62v48M288 86l12-12M288 86l12 12" fill="none" stroke="' . $blue . '" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>'
				. '<path d="M84 96q14-14 28 0M70 84q28-28 56 0" fill="none" stroke="' . $blue . '" stroke-width="5" stroke-linecap="round" opacity="0.7"/>'
				. $board( 60, 130, 1.1 );
			break;
		case 'safety':
			$body = '<rect width="400" height="300" fill="' . $bg . '"/>'
				. '<path d="M300 40l56 20v44c0 36-24 60-56 70-32-10-56-34-56-70V60z" fill="' . $white . '" stroke="' . $blue . '" stroke-width="6" stroke-linejoin="round"/>'
				. '<path d="M278 104l16 16 30-34" fill="none" stroke="' . $blue . '" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>'
				. '<path d="M70 110a52 52 0 0 1 104 0v16H70z" fill="' . $ink . '"/><rect x="62" y="124" width="120" height="12" rx="6" fill="' . $blue . '"/>'
				. $board( 60, 150, 1.0 );
			break;
		case 'maintenance':
			$body = '<rect width="400" height="300" fill="' . $bg . '"/><circle cx="300" cy="90" r="46" fill="' . $soft . '"/>'
				. '<path d="M318 60a18 18 0 0 0-22 22l-26 26 12 12 26-26a18 18 0 0 0 22-22l-12 12-10-2-2-10z" fill="' . $white . '" stroke="' . $ink . '" stroke-width="4" stroke-linejoin="round"/>'
				. $board( 60, 130, 1.1 )
				. '<path d="M98 232l-18 30M124 232l-4 34" fill="none" stroke="' . $blue . '" stroke-width="4" stroke-linecap="round" opacity="0.6"/>';
			break;
		case 'charging':
			$body = '<rect width="400" height="300" fill="' . $bg . '"/>'
				. '<rect x="248" y="62" width="96" height="48" rx="10" fill="' . $white . '" stroke="' . $ink . '" stroke-width="4"/><rect x="344" y="76" width="10" height="20" rx="3" fill="' . $ink . '"/><rect x="258" y="72" width="54" height="28" rx="5" fill="' . $blue . '"/>'
				. '<path d="M300 124v24h-40v18" fill="none" stroke="' . $ink . '" stroke-width="4" stroke-linecap="round"/>'
				. '<path d="M206 118l-14 24h16l-10 22 24-30h-16z" fill="' . $blue . '"/>'
				. $board( 40, 150, 1.0 );
			break;
		case 'finder':
			$body = '<rect width="400" height="300" fill="' . $bg . '"/>'
				. '<circle cx="290" cy="96" r="46" fill="' . $white . '" stroke="' . $blue . '" stroke-width="6"/><path d="M322 128l34 34" stroke="' . $blue . '" stroke-width="10" stroke-linecap="round"/>'
				. '<path d="M276 96h28M290 82v28" stroke="' . $ink . '" stroke-width="5" stroke-linecap="round"/>'
				. $board( 40, 150, 1.0 );
			break;
		case 'terrain':
			$body = '<rect width="400" height="300" fill="' . $bg . '"/>'
				. '<path d="M0 230l70-60 60 44 60-70 80 60 70-40 60 50v46H0z" fill="' . $soft . '"/>'
				. '<path d="M0 262q50-18 100 0t100 0 100 0 100 0" fill="none" stroke="' . $blue . '" stroke-width="5" stroke-linecap="round" opacity="0.6"/>'
				. $board( 100, 120, 1.0 );
			break;
		default:
			$body = '<rect width="400" height="300" fill="' . $bg . '"/>' . $board( 100, 100, 1.0 );
	}

	$attrs = $a['alt'] ? ' role="img" aria-label="' . esc_attr( $a['alt'] ) . '"' : ' aria-hidden="true"';
	return '<svg class="illus' . ( $a['class'] ? ' ' . esc_attr( $a['class'] ) : '' ) . '" viewBox="0 0 400 300" width="400" height="300" xmlns="http://www.w3.org/2000/svg"' . $attrs . ' focusable="false">' . $body . '</svg>';
}

/**
 * Product tile: a side-view board drawn from the product's own data (wheel size, tyre type, role),
 * used as the comparison-table thumbnail until the owner uploads a photo for that product.
 * It is an illustration and is labelled as such in the table note.
 */
function hbg_product_tile( $p, $args = array() ) {
	$a      = wp_parse_args( $args, array( 'class' => '', 'alt' => '' ) );
	$ink    = '#0b1f3a';
	$white  = '#ffffff';
	$colors = array(
		'overall'     => '#1d4ed8',
		'budget'      => '#0e7490',
		'kids'        => '#d97706',
		'adults'      => '#0f172a',
		'all-terrain' => '#15803d',
		'beginners'   => '#0284c7',
		'premium'     => '#6d28d9',
	);
	$role   = isset( $p['role'] ) ? $p['role'] : '';
	$deck   = isset( $colors[ $role ] ) ? $colors[ $role ] : '#1d4ed8';
	$wheel  = isset( $p['wheel'] ) ? (string) $p['wheel'] : '';
	$size   = (float) preg_replace( '/^[^\d.]*([\d.]+).*$/s', '$1', $wheel );
	$size   = $size > 0 ? min( 12, max( 6, $size ) ) : 6.5;
	$r      = round( 10 + $size * 2.5 ); // 6.5" → 26, 8.5" → 31, 10" → 35
	$knobby = (bool) preg_match( '/pneumatic|off-road|all-terrain|air/i', $wheel );
	$id     = 'tile-' . sanitize_html_class( isset( $p['id'] ) ? $p['id'] : wp_rand() );
	$y      = 118;
	$lx     = 56;
	$rx     = 144;
	$wheel_svg = function ( $cx ) use ( $y, $r, $ink, $white, $knobby ) {
		$out  = '<circle cx="' . $cx . '" cy="' . $y . '" r="' . $r . '" fill="' . $ink . '"/>';
		if ( $knobby ) {
			$out .= '<circle cx="' . $cx . '" cy="' . $y . '" r="' . ( $r - 3 ) . '" fill="none" stroke="' . $white . '" stroke-opacity="0.35" stroke-width="4" stroke-dasharray="4 4"/>';
		} else {
			$out .= '<circle cx="' . $cx . '" cy="' . $y . '" r="' . ( $r - 5 ) . '" fill="none" stroke="' . $white . '" stroke-opacity="0.18" stroke-width="2"/>';
		}
		$out .= '<circle cx="' . $cx . '" cy="' . $y . '" r="' . round( $r * 0.42 ) . '" fill="#c7d0dd"/><circle cx="' . $cx . '" cy="' . $y . '" r="' . round( $r * 0.16 ) . '" fill="' . $white . '"/>';
		return $out;
	};
	$fender = function ( $cx ) use ( $y, $r, $deck ) {
		$rr = $r + 7;
		return '<path d="M' . ( $cx - $rr ) . ' ' . $y . 'a' . $rr . ' ' . $rr . ' 0 0 1 ' . ( 2 * $rr ) . ' 0" fill="none" stroke="' . $deck . '" stroke-width="9" stroke-linecap="round"/>';
	};
	$body  = '<defs><linearGradient id="' . $id . '" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="' . $deck . '" stop-opacity="0.16"/><stop offset="1" stop-color="' . $deck . '" stop-opacity="0.04"/></linearGradient></defs>';
	$body .= '<rect width="200" height="200" rx="24" fill="#f3f6fb"/><rect width="200" height="200" rx="24" fill="url(#' . $id . ')"/>';
	$body .= '<ellipse cx="100" cy="' . ( $y + $r + 12 ) . '" rx="78" ry="7" fill="' . $ink . '" opacity="0.08"/>';
	$body .= $fender( $lx ) . $fender( $rx );
	$body .= '<rect x="' . ( $lx + 4 ) . '" y="' . ( $y - 14 ) . '" width="' . ( $rx - $lx - 8 ) . '" height="24" rx="11" fill="' . $deck . '"/>';
	$body .= '<rect x="' . ( $lx + 16 ) . '" y="' . ( $y - 22 ) . '" width="26" height="12" rx="5" fill="' . $ink . '" opacity="0.85"/><rect x="' . ( $rx - 42 ) . '" y="' . ( $y - 22 ) . '" width="26" height="12" rx="5" fill="' . $ink . '" opacity="0.85"/>';
	$body .= '<circle cx="' . ( $lx + 9 ) . '" cy="' . ( $y - 2 ) . '" r="3" fill="' . $white . '" opacity="0.9"/><circle cx="' . ( $rx - 9 ) . '" cy="' . ( $y - 2 ) . '" r="3" fill="' . $white . '" opacity="0.9"/>';
	$body .= $wheel_svg( $lx ) . $wheel_svg( $rx );
	$attrs = $a['alt'] ? ' role="img" aria-label="' . esc_attr( $a['alt'] ) . '"' : ' aria-hidden="true"';
	return '<svg class="illus product-tile' . ( $a['class'] ? ' ' . esc_attr( $a['class'] ) : '' ) . '" viewBox="0 0 200 200" width="200" height="200" xmlns="http://www.w3.org/2000/svg"' . $attrs . ' focusable="false">' . $body . '</svg>';
}
