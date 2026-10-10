<?php
/**
 * Amazon affiliate helpers.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Curated keyword bank for Amazon searches.
 */
function bdh_amazon_keywords() {
	$default = array(
		'tube bender',
		'hydraulic pipe bender',
		'mandrel tube bender',
		'CNC tube bender',
		'exhaust pipe bender',
		'roll cage tubing bender',
		'tubing die set',
		'tube notcher',
		'tubing deburring tool',
		'tubing cutter',
		'fabrication clamps',
		'welding gloves',
		'welding helmet auto darkening',
		'TIG welder 200 amp',
		'MIG welder 140 amp',
		'shop bench vise',
		'angle finder digital',
		'tubing storage rack',
		'chassis fabrication table',
		'metalworking layout tools',
	);
	$custom = bdh_option( 'amazon_keyword_bank', '' );
	if ( $custom ) {
		$lines = array_filter( array_map( 'trim', explode( "\n", $custom ) ) );
		if ( ! empty( $lines ) ) { return $lines; }
	}
	return $default;
}
