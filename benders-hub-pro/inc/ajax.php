<?php
/**
 * AJAX endpoints.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Live search fragment (used by header search + 404).
 */
function bdh_ajax_live_search() {
	check_ajax_referer( 'bdh_ajax', 'nonce' );

	$q = isset( $_REQUEST['q'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['q'] ) ) : '';
	if ( strlen( $q ) < 2 ) { wp_send_json_success( array( 'items' => array() ) ); }

	$query = new WP_Query( array(
		'post_type'      => array( 'post', 'page', 'bdh_product', 'bdh_guide' ),
		's'              => $q,
		'posts_per_page' => 8,
		'no_found_rows'  => true,
	) );

	$items = array();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) { $query->the_post();
			$items[] = array(
				'title' => get_the_title(),
				'url'   => get_permalink(),
				'type'  => get_post_type(),
				'excerpt' => wp_trim_words( get_the_excerpt(), 18 ),
			);
		}
	}
	wp_reset_postdata();
	wp_send_json_success( array( 'items' => $items ) );
}
add_action( 'wp_ajax_bdh_live_search', 'bdh_ajax_live_search' );
add_action( 'wp_ajax_nopriv_bdh_live_search', 'bdh_ajax_live_search' );

/**
 * Newsletter submission stub (replace endpoint via Customizer).
 */
function bdh_ajax_newsletter() {
	check_ajax_referer( 'bdh_ajax', 'nonce' );
	$email = isset( $_REQUEST['email'] ) ? sanitize_email( wp_unslash( $_REQUEST['email'] ) ) : '';
	if ( ! is_email( $email ) ) { wp_send_json_error( array( 'message' => __( 'Invalid email address.', 'benders-hub' ) ) ); }

	$endpoint = bdh_option( 'newsletter_endpoint', '' );
	if ( $endpoint ) {
		wp_remote_post( esc_url_raw( $endpoint ), array(
			'timeout' => 8,
			'body'    => array( 'email' => $email, 'source' => 'bendershub' ),
		) );
	}
	wp_send_json_success( array( 'message' => __( 'You\'re in. Check your inbox.', 'benders-hub' ) ) );
}
add_action( 'wp_ajax_bdh_newsletter', 'bdh_ajax_newsletter' );
add_action( 'wp_ajax_nopriv_bdh_newsletter', 'bdh_ajax_newsletter' );

/**
 * Equipment database filter (returns HTML cards).
 */
function bdh_ajax_database() {
	check_ajax_referer( 'bdh_ajax', 'nonce' );
	$category = isset( $_REQUEST['category'] ) ? sanitize_key( $_REQUEST['category'] ) : 'all';
	$search   = isset( $_REQUEST['q'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['q'] ) ) : '';

	$items = bdh_default_equipment();
	$filtered = array();
	foreach ( $items as $item ) {
		if ( $category !== 'all' && $item['category'] !== $category ) { continue; }
		if ( $search ) {
			$haystack = strtolower( $item['name'] . ' ' . $item['type'] . ' ' . $item['use_case'] . ' ' . $item['tubing'] );
			if ( strpos( $haystack, strtolower( $search ) ) === false ) { continue; }
		}
		$filtered[] = $item;
	}

	ob_start();
	if ( empty( $filtered ) ) {
		echo '<p class="bdh-no-results">' . esc_html__( 'No equipment matches that filter — try adjusting search or category.', 'benders-hub' ) . '</p>';
	} else {
		foreach ( $filtered as $item ) {
			set_query_var( 'bdh_eq', $item );
			get_template_part( 'template-parts/equipment-card' );
		}
	}
	$html = ob_get_clean();
	wp_send_json_success( array( 'html' => $html, 'count' => count( $filtered ) ) );
}
add_action( 'wp_ajax_bdh_database', 'bdh_ajax_database' );
add_action( 'wp_ajax_nopriv_bdh_database', 'bdh_ajax_database' );
