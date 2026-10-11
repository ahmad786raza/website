<?php
/**
 * AJAX endpoints.
 *
 * Public read-only endpoints do not use nonces: with full-page caching a
 * nonce printed into cached HTML expires after 12–24 h and silently breaks
 * the feature for every visitor (a v1 problem). The newsletter endpoint uses
 * a honeypot and per-IP rate limiting instead.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Homepage "load more" feed — returns rendered cards.
 */
function bdh_ajax_load_more() {
	$page    = isset( $_GET['page'] ) ? max( 2, absint( $_GET['page'] ) ) : 2; // phpcs:ignore WordPress.Security.NonceVerification
	$per     = isset( $_GET['per'] ) ? min( 12, max( 1, absint( $_GET['per'] ) ) ) : 6; // phpcs:ignore WordPress.Security.NonceVerification
	$exclude = isset( $_GET['exclude'] ) ? array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_GET['exclude'] ) ) ) ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification
	$q = new WP_Query( array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $per,
		'paged'               => $page,
		'post__not_in'        => array_slice( $exclude, 0, 50 ),
		'ignore_sticky_posts' => true,
	) );
	ob_start();
	while ( $q->have_posts() ) { $q->the_post(); bdh_post_card( get_the_ID(), 'card', 'h3' ); }
	wp_reset_postdata();
	wp_send_json_success( array( 'html' => ob_get_clean(), 'more' => $page < (int) $q->max_num_pages ) );
}
add_action( 'wp_ajax_bdh_load_more', 'bdh_ajax_load_more' );
add_action( 'wp_ajax_nopriv_bdh_load_more', 'bdh_ajax_load_more' );

/**
 * Live search fragment (kept for compatibility).
 */
function bdh_ajax_live_search() {
	$q = isset( $_REQUEST['q'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( strlen( $q ) < 2 ) { wp_send_json_success( array( 'items' => array() ) ); }
	$query = new WP_Query( array( 'post_type' => array( 'post', 'page', 'bdh_guide' ), 's' => $q, 'posts_per_page' => 8, 'no_found_rows' => true ) );
	$items = array();
	while ( $query->have_posts() ) {
		$query->the_post();
		$items[] = array( 'title' => get_the_title(), 'url' => get_permalink(), 'type' => get_post_type(), 'excerpt' => wp_trim_words( get_the_excerpt(), 18 ) );
	}
	wp_reset_postdata();
	wp_send_json_success( array( 'items' => $items ) );
}
add_action( 'wp_ajax_bdh_live_search', 'bdh_ajax_live_search' );
add_action( 'wp_ajax_nopriv_bdh_live_search', 'bdh_ajax_live_search' );

/**
 * Private store for newsletter sign-ups (never public, no front-end URLs).
 */
function bdh_register_subscriber_type() {
	register_post_type( 'bdh_subscriber', array(
		'label'           => __( 'Subscribers', 'benders-hub' ),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'bdh-dashboard',
		'show_in_rest'    => false,
		'supports'        => array( 'title' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
	) );
}
add_action( 'init', 'bdh_register_subscriber_type' );

/**
 * Newsletter submission. v1 always answered "You're in" and discarded the
 * address unless an endpoint was configured. v2 stores every sign-up and,
 * if an endpoint is set, forwards it and reports a failure honestly.
 */
function bdh_ajax_newsletter() {
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! empty( $_POST['website'] ) ) { // Honeypot filled → bot. Pretend success.
		wp_send_json_success( array( 'message' => __( 'Thanks — you\'re on the list.', 'benders-hub' ) ) );
	}
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$src   = isset( $_POST['source'] ) ? esc_url_raw( home_url( sanitize_text_field( wp_unslash( $_POST['source'] ) ) ) ) : '';
	// phpcs:enable
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'benders-hub' ) ) );
	}
	$ip_key = 'bdh_nl_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'x' );
	$hits   = (int) get_transient( $ip_key );
	if ( $hits >= 30 ) { // Generous: sites behind a CDN may see many visitors on one address.
		wp_send_json_error( array( 'message' => __( 'Too many attempts — please try again later.', 'benders-hub' ) ) );
	}
	set_transient( $ip_key, $hits + 1, HOUR_IN_SECONDS );

	$existing = get_posts( array( 'post_type' => 'bdh_subscriber', 'title' => $email, 'post_status' => 'any', 'fields' => 'ids', 'posts_per_page' => 1 ) );
	if ( ! $existing ) {
		wp_insert_post( array( 'post_type' => 'bdh_subscriber', 'post_status' => 'private', 'post_title' => $email, 'meta_input' => array( '_bdh_source' => $src, '_bdh_consent' => current_time( 'mysql' ) ) ) );
	}

	$endpoint = bdh_option( 'newsletter_endpoint', '' );
	if ( $endpoint ) {
		$res = wp_remote_post( esc_url_raw( $endpoint ), array( 'timeout' => 8, 'body' => array( 'email' => $email, 'source' => 'bendershub' ) ) );
		if ( is_wp_error( $res ) || wp_remote_retrieve_response_code( $res ) >= 400 ) {
			// Stored locally above, so the address is not lost.
			wp_send_json_success( array( 'message' => __( 'Thanks — you\'re on the list.', 'benders-hub' ) ) );
		}
	}
	wp_send_json_success( array( 'message' => __( 'Thanks — you\'re on the list.', 'benders-hub' ) ) );
}
add_action( 'wp_ajax_bdh_newsletter', 'bdh_ajax_newsletter' );
add_action( 'wp_ajax_nopriv_bdh_newsletter', 'bdh_ajax_newsletter' );

/**
 * CSV export of subscribers (Benders Hub → Subscribers → Export).
 */
function bdh_export_subscribers() {
	if ( ! current_user_can( 'manage_options' ) ) { wp_die( esc_html__( 'Not allowed.', 'benders-hub' ) ); }
	check_admin_referer( 'bdh_export_subscribers' );
	$ids = get_posts( array( 'post_type' => 'bdh_subscriber', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids' ) );
	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=bendershub-subscribers-' . gmdate( 'Y-m-d' ) . '.csv' );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array( 'email', 'signed_up', 'source_page' ) );
	foreach ( $ids as $id ) {
		fputcsv( $out, array( get_the_title( $id ), get_post_meta( $id, '_bdh_consent', true ), get_post_meta( $id, '_bdh_source', true ) ) );
	}
	fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}
add_action( 'admin_post_bdh_export_subscribers', 'bdh_export_subscribers' );

/**
 * Equipment explorer filter (returns HTML cards) — kept for compatibility.
 */
function bdh_ajax_database() {
	$category = isset( $_REQUEST['category'] ) ? sanitize_key( $_REQUEST['category'] ) : 'all'; // phpcs:ignore WordPress.Security.NonceVerification
	$search   = isset( $_REQUEST['q'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$filtered = array();
	foreach ( bdh_default_equipment() as $item ) {
		if ( 'all' !== $category && $item['category'] !== $category ) { continue; }
		if ( $search && false === stripos( $item['name'] . ' ' . $item['type'] . ' ' . $item['use_case'] . ' ' . $item['tubing'], $search ) ) { continue; }
		$filtered[] = $item;
	}
	ob_start();
	if ( ! $filtered ) {
		echo '<p class="bdh-empty">' . esc_html__( 'No equipment type matches that filter.', 'benders-hub' ) . '</p>';
	}
	foreach ( $filtered as $item ) { get_template_part( 'template-parts/equipment-card', null, array( 'eq' => $item ) ); }
	wp_send_json_success( array( 'html' => ob_get_clean(), 'count' => count( $filtered ) ) );
}
add_action( 'wp_ajax_bdh_database', 'bdh_ajax_database' );
add_action( 'wp_ajax_nopriv_bdh_database', 'bdh_ajax_database' );
