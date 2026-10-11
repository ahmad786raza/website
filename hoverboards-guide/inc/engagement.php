<?php
/**
 * Engagement features: view counter, AJAX load-more, newsletter capture,
 * content filters (affiliate link hygiene, table wrappers) and admin subscriber list.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =========================================================================
// View counter (AJAX beacon so it works behind page caches)
// =========================================================================
function hbg_ajax_track_view() {
	check_ajax_referer( 'hbg_nonce', 'nonce' );
	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	if ( ! $post_id || 'publish' !== get_post_status( $post_id ) ) {
		wp_send_json_error();
	}
	// Ignore logged-in editors so admin previews don't inflate counts.
	if ( current_user_can( 'edit_posts' ) ) {
		wp_send_json_success( array( 'skipped' => true ) );
	}
	$views = (int) get_post_meta( $post_id, 'hbg_views', true );
	update_post_meta( $post_id, 'hbg_views', $views + 1 );
	wp_send_json_success( array( 'views' => $views + 1 ) );
}
add_action( 'wp_ajax_hbg_track_view', 'hbg_ajax_track_view' );
add_action( 'wp_ajax_nopriv_hbg_track_view', 'hbg_ajax_track_view' );

// =========================================================================
// AJAX load more posts
// =========================================================================
function hbg_ajax_load_more() {
	check_ajax_referer( 'hbg_nonce', 'nonce' );
	$paged   = isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : 1;
	$per     = isset( $_POST['per_page'] ) ? min( 12, max( 1, absint( $_POST['per_page'] ) ) ) : 6;
	$cat     = isset( $_POST['cat'] ) ? absint( $_POST['cat'] ) : 0;
	$exclude = isset( $_POST['exclude'] ) ? array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['exclude'] ) ) ) ) : array();
	$search  = isset( $_POST['s'] ) ? sanitize_text_field( wp_unslash( $_POST['s'] ) ) : '';

	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $per,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
		'post__not_in'        => array_filter( $exclude ),
	);
	if ( $cat ) {
		$args['cat'] = $cat;
	}
	if ( $search ) {
		$args['s'] = $search;
	}
	$q = new WP_Query( $args );
	ob_start();
	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();
			get_template_part( 'template-parts/content', 'card' );
		}
	}
	wp_reset_postdata();
	$html = ob_get_clean();
	wp_send_json_success( array( 'html' => $html, 'has_more' => $paged < $q->max_num_pages, 'max' => $q->max_num_pages ) );
}
add_action( 'wp_ajax_hbg_load_more', 'hbg_ajax_load_more' );
add_action( 'wp_ajax_nopriv_hbg_load_more', 'hbg_ajax_load_more' );

// =========================================================================
// Newsletter capture (stored in an option; export as CSV from Appearance menu)
// =========================================================================
function hbg_ajax_subscribe() {
	check_ajax_referer( 'hbg_nonce', 'nonce' );
	if ( ! empty( $_POST['hbg_hp'] ) ) { // honeypot
		wp_send_json_success( array( 'message' => get_theme_mod( 'hbg_newsletter_success', __( 'You’re in! Check your inbox to confirm.', 'hoverboards-guide' ) ) ) );
	}
	$email  = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$source = isset( $_POST['source'] ) ? sanitize_key( $_POST['source'] ) : 'site';
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'hoverboards-guide' ) ) );
	}
	// Simple rate limit per IP.
	$ip_key = 'hbg_sub_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' );
	if ( (int) get_transient( $ip_key ) >= 5 ) {
		wp_send_json_error( array( 'message' => __( 'Too many attempts. Please try again later.', 'hoverboards-guide' ) ) );
	}
	set_transient( $ip_key, (int) get_transient( $ip_key ) + 1, HOUR_IN_SECONDS );

	$list = get_option( 'hbg_subscribers', array() );
	if ( ! is_array( $list ) ) {
		$list = array();
	}
	if ( ! isset( $list[ $email ] ) ) {
		$list[ $email ] = array( 'time' => time(), 'source' => $source );
		if ( count( $list ) > 20000 ) {
			$list = array_slice( $list, -20000, null, true );
		}
		update_option( 'hbg_subscribers', $list, false );
		if ( get_theme_mod( 'hbg_newsletter_notify', false ) ) {
			wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New newsletter subscriber', 'hoverboards-guide' ), get_bloginfo( 'name' ) ), $email . ' (' . $source . ')' );
		}
	}
	wp_send_json_success( array( 'message' => get_theme_mod( 'hbg_newsletter_success', __( 'You’re in! Check your inbox to confirm.', 'hoverboards-guide' ) ) ) );
}
add_action( 'wp_ajax_hbg_subscribe', 'hbg_ajax_subscribe' );
add_action( 'wp_ajax_nopriv_hbg_subscribe', 'hbg_ajax_subscribe' );

// Admin page: Appearance → Subscribers
function hbg_subscribers_menu() {
	add_theme_page( __( 'Newsletter Subscribers', 'hoverboards-guide' ), __( 'Subscribers', 'hoverboards-guide' ), 'manage_options', 'hbg-subscribers', 'hbg_subscribers_page' );
}
add_action( 'admin_menu', 'hbg_subscribers_menu' );

function hbg_subscribers_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$list = get_option( 'hbg_subscribers', array() );
	$list = is_array( $list ) ? array_reverse( $list, true ) : array();
	$export_url = wp_nonce_url( admin_url( 'admin-post.php?action=hbg_export_subscribers' ), 'hbg_export' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Newsletter Subscribers', 'hoverboards-guide' ); ?> <span class="count">(<?php echo esc_html( number_format_i18n( count( $list ) ) ); ?>)</span></h1>
		<p><?php esc_html_e( 'Emails captured by the theme’s built-in newsletter forms. To send to Mailchimp/ConvertKit instead, set a form action URL under Customize → Hoverboards Guide → Newsletter.', 'hoverboards-guide' ); ?></p>
		<p><a href="<?php echo esc_url( $export_url ); ?>" class="button button-primary"><?php esc_html_e( 'Export CSV', 'hoverboards-guide' ); ?></a></p>
		<table class="widefat striped" style="max-width:720px">
			<thead><tr><th><?php esc_html_e( 'Email', 'hoverboards-guide' ); ?></th><th><?php esc_html_e( 'Source', 'hoverboards-guide' ); ?></th><th><?php esc_html_e( 'Date', 'hoverboards-guide' ); ?></th></tr></thead>
			<tbody>
			<?php if ( ! $list ) : ?>
				<tr><td colspan="3"><?php esc_html_e( 'No subscribers yet.', 'hoverboards-guide' ); ?></td></tr>
			<?php endif; ?>
			<?php foreach ( array_slice( $list, 0, 500, true ) as $email => $row ) : ?>
				<tr><td><?php echo esc_html( $email ); ?></td><td><?php echo esc_html( $row['source'] ); ?></td><td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' H:i', (int) $row['time'] ) ); ?></td></tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

function hbg_export_subscribers() {
	if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '', 'hbg_export' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'hoverboards-guide' ) );
	}
	$list = get_option( 'hbg_subscribers', array() );
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=subscribers-' . gmdate( 'Y-m-d' ) . '.csv' );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array( 'email', 'source', 'date' ) );
	foreach ( (array) $list as $email => $row ) {
		fputcsv( $out, array( $email, $row['source'], gmdate( 'Y-m-d H:i', (int) $row['time'] ) ) );
	}
	fclose( $out ); // phpcs:ignore
	exit;
}
add_action( 'admin_post_hbg_export_subscribers', 'hbg_export_subscribers' );

// =========================================================================
// Content filters
// =========================================================================

/**
 * Make sure every Amazon link carries sponsored/nofollow and opens in a new tab.
 */
function hbg_affiliate_link_hygiene( $content ) {
	if ( false === stripos( $content, 'amazon.' ) && false === stripos( $content, 'amzn.to' ) ) {
		return $content;
	}
	return preg_replace_callback( '/<a\s+([^>]*href=["\'][^"\']*(?:amazon\.|amzn\.to)[^"\']*["\'][^>]*)>/i', function ( $m ) {
		$attrs = $m[1];
		if ( ! preg_match( '/\brel=/i', $attrs ) ) {
			$attrs .= ' rel="sponsored nofollow noopener"';
		} else {
			$attrs = preg_replace_callback( '/\brel=["\']([^"\']*)["\']/i', function ( $r ) {
				$rels = array_unique( array_merge( preg_split( '/\s+/', trim( $r[1] ) ), array( 'sponsored', 'nofollow', 'noopener' ) ) );
				return 'rel="' . esc_attr( implode( ' ', array_filter( $rels ) ) ) . '"';
			}, $attrs );
		}
		if ( ! preg_match( '/\btarget=/i', $attrs ) ) {
			$attrs .= ' target="_blank"';
		}
		return '<a ' . $attrs . '>';
	}, $content );
}
add_filter( 'the_content', 'hbg_affiliate_link_hygiene', 20 );

/**
 * Wrap bare tables so wide comparison tables scroll instead of breaking the layout.
 */
function hbg_wrap_tables( $content ) {
	if ( false === strpos( $content, '<table' ) ) {
		return $content;
	}
	$content = preg_replace( '/(?<!<div class="table-scroll">)<table/i', '<div class="table-scroll"><table', $content );
	$content = preg_replace( '/<\/table>(?!<\/div>)/i', '</table></div>', $content );
	return $content;
}
add_filter( 'the_content', 'hbg_wrap_tables', 25 );

// =========================================================================
// Body classes
// =========================================================================
function hbg_body_classes( $classes ) {
	$classes[] = 'hbg-theme';
	if ( is_singular( 'post' ) ) {
		$classes[] = 'has-sidebar';
	}
	if ( ( is_home() || is_archive() || is_search() ) && 'none' !== get_theme_mod( 'hbg_sidebar_position', 'right' ) ) {
		$classes[] = 'has-sidebar';
	}
	if ( get_theme_mod( 'hbg_show_topbar', true ) ) {
		$classes[] = 'has-topbar';
	}
	return $classes;
}
add_filter( 'body_class', 'hbg_body_classes' );

// =========================================================================
// Excerpt tweaks
// =========================================================================
function hbg_excerpt_length( $length ) {
	return 26;
}
add_filter( 'excerpt_length', 'hbg_excerpt_length', 999 );

function hbg_excerpt_more( $more ) {
	return '…';
}
add_filter( 'excerpt_more', 'hbg_excerpt_more' );

// =========================================================================
// Search: posts only, so category/product pages stay clean
// =========================================================================
function hbg_search_filter( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$query->set( 'post_type', array( 'post', 'page' ) );
	}
}
add_action( 'pre_get_posts', 'hbg_search_filter' );
