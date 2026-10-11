<?php
/**
 * Image Library — licence-checked photo importer.
 *
 * Benders Hub → Image Library lets an administrator:
 *  1. Re-check each candidate's licence against the live Wikimedia Commons API
 *     (only CC0, public domain, CC BY and CC BY-SA are accepted; NC/ND refused).
 *  2. Look at a preview and approve it (the importer never imports unseen images).
 *  3. Import it into the Media Library with a descriptive filename, alt text,
 *     a credit caption and source/creator/licence/date metadata.
 *  4. Use imported photos as topic images and, optionally, as featured images
 *     for posts that have none.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Candidate list (inc/data/image-candidates.php), keyed by candidate key.
 */
function bdh_image_candidates() {
	static $list = null;
	if ( null === $list ) {
		$raw  = include BDH_THEME_DIR . '/inc/data/image-candidates.php';
		$list = array();
		foreach ( (array) $raw as $c ) { $list[ $c['key'] ] = $c; }
	}
	return $list;
}

/**
 * Slots the theme uses, with the subject each one must show.
 */
function bdh_image_slots() {
	return array(
		'hero'            => __( 'Homepage fallback / social share image', 'benders-hub' ),
		'manual-bender'   => __( 'Manual / hand tube bender', 'benders-hub' ),
		'hydraulic-bender'=> __( 'Hydraulic bender', 'benders-hub' ),
		'industrial-pipe' => __( 'Industrial pipe bending & fabrication', 'benders-hub' ),
		'cnc-bender'      => __( 'CNC tube bending machine', 'benders-hub' ),
		'mandrel'         => __( 'Mandrel-bent tube / headers', 'benders-hub' ),
		'exhaust'         => __( 'Exhaust fabrication', 'benders-hub' ),
		'roll-cage'       => __( 'Roll cage', 'benders-hub' ),
		'chassis'         => __( 'Tube-frame chassis', 'benders-hub' ),
		'conduit-bender'  => __( 'Conduit bending', 'benders-hub' ),
		'welding'         => __( 'Welding tube or pipe', 'benders-hub' ),
		'workshop'        => __( 'Fabrication workshop', 'benders-hub' ),
		'safety'          => __( 'Safety equipment in use', 'benders-hub' ),
		'measuring'       => __( 'Measuring tube', 'benders-hub' ),
		'cutting'         => __( 'Cutting / notching tube', 'benders-hub' ),
		'tube-stock'      => __( 'Tube stock', 'benders-hub' ),
	);
}

/**
 * Closely related slots that may stand in for each other (never unrelated machinery).
 */
function bdh_image_slot_aliases() {
	return array(
		'bent-tube'  => array( 'mandrel', 'exhaust' ),
		'stainless'  => array( 'exhaust', 'welding' ),
		'hero'       => array( 'roll-cage', 'mandrel' ),
		'safety'     => array( 'welding' ),
		'workshop'   => array( 'welding' ),
		'chassis'    => array( 'roll-cage' ),
		'motorsport' => array( 'roll-cage' ),
	);
}

/**
 * Attachment ID for a slot. $seed (e.g. a post ID) rotates between several
 * images in the same slot so neighbouring cards don't all show one photo.
 */
function bdh_library_attachment( $slot, $seed = 0 ) {
	$override = get_option( 'bdh_img_slot_override', array() );
	if ( ! empty( $override[ $slot ] ) && wp_attachment_is_image( (int) $override[ $slot ] ) ) {
		return (int) $override[ $slot ];
	}
	$map = bdh_image_slot_map();
	if ( ! empty( $map[ $slot ] ) ) {
		$ids = array_values( $map[ $slot ] );
		return (int) $ids[ absint( $seed ) % count( $ids ) ];
	}
	$aliases = bdh_image_slot_aliases();
	if ( isset( $aliases[ $slot ] ) ) {
		foreach ( $aliases[ $slot ] as $alt ) {
			if ( ! empty( $map[ $alt ] ) || ! empty( $override[ $alt ] ) ) { return bdh_library_attachment( $alt, $seed ); }
		}
	}
	return 0;
}

/**
 * slot => [attachment IDs] from everything imported by the library.
 */
function bdh_image_slot_map( $reset = false ) {
	static $map = null;
	if ( null !== $map && ! $reset ) { return $map; }
	$map      = array();
	$imported = get_option( 'bdh_img_imported', array() );
	$cands    = bdh_image_candidates();
	foreach ( (array) $imported as $key => $att_id ) {
		if ( ! wp_attachment_is_image( (int) $att_id ) ) { continue; }
		$slots = get_post_meta( (int) $att_id, '_bdh_slots', true );
		if ( ! is_array( $slots ) ) { $slots = isset( $cands[ $key ] ) ? $cands[ $key ]['slots'] : array(); }
		foreach ( $slots as $s ) { $map[ $s ][] = (int) $att_id; }
	}
	return $map;
}

/* ------------------------------------------------------------------ */
/* Verification                                                        */
/* ------------------------------------------------------------------ */

/**
 * Is a Commons licence acceptable for commercial use with attribution?
 */
function bdh_license_allowed( $short, $code ) {
	$s = strtolower( $short . ' ' . $code );
	if ( preg_match( '/\b(nc|nd)\b|non-?commercial|no-?deriv|fair use|all rights reserved/', $s ) ) { return false; }
	return (bool) preg_match( '/cc0|public domain|\bpd\b|pd-|cc[- ]by(-sa)?[- ][0-9.]+|cc-by(-sa)?-[0-9.]+/', $s );
}

/**
 * Query the Commons API for many files in one request and store the results.
 */
function bdh_verify_commons( $keys ) {
	$cands  = bdh_image_candidates();
	$titles = array();
	foreach ( $keys as $k ) {
		if ( isset( $cands[ $k ] ) && 'commons' === $cands[ $k ]['source'] ) { $titles[ $cands[ $k ]['file'] ] = $k; }
	}
	if ( ! $titles ) { return array(); }
	$store   = get_option( 'bdh_img_verify', array() );
	$results = array();
	foreach ( array_chunk( array_keys( $titles ), 40 ) as $chunk ) {
		$url = add_query_arg( array(
			'action'      => 'query',
			'format'      => 'json',
			'formatversion' => 2,
			'prop'        => 'imageinfo',
			'iiprop'      => 'url|size|mime|extmetadata',
			'iiurlwidth'  => 1920,
			'titles'      => implode( '|', $chunk ),
		), 'https://commons.wikimedia.org/w/api.php' );
		$res = wp_remote_get( $url, array( 'timeout' => 20, 'user-agent' => bdh_image_user_agent() ) );
		if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
			foreach ( $chunk as $t ) { $results[ $titles[ $t ] ] = array( 'ok' => false, 'error' => is_wp_error( $res ) ? $res->get_error_message() : 'HTTP ' . wp_remote_retrieve_response_code( $res ) ); }
			continue;
		}
		$data = json_decode( wp_remote_retrieve_body( $res ), true );
		$norm = array();
		foreach ( (array) ( $data['query']['normalized'] ?? array() ) as $n ) { $norm[ $n['to'] ] = $n['from']; }
		foreach ( (array) ( $data['query']['pages'] ?? array() ) as $page ) {
			$orig = isset( $norm[ $page['title'] ] ) ? $norm[ $page['title'] ] : $page['title'];
			if ( ! isset( $titles[ $orig ] ) ) { continue; }
			$key = $titles[ $orig ];
			if ( ! empty( $page['missing'] ) || empty( $page['imageinfo'][0] ) ) {
				$results[ $key ] = array( 'ok' => false, 'error' => __( 'File not found on Commons', 'benders-hub' ) );
				continue;
			}
			$ii    = $page['imageinfo'][0];
			$em    = isset( $ii['extmetadata'] ) ? $ii['extmetadata'] : array();
			$get   = function ( $f ) use ( $em ) { return isset( $em[ $f ]['value'] ) ? trim( wp_strip_all_tags( (string) $em[ $f ]['value'] ) ) : ''; };
			$short = $get( 'LicenseShortName' );
			$code  = $get( 'License' );
			$ok    = bdh_license_allowed( $short, $code ) && in_array( $ii['mime'], array( 'image/jpeg', 'image/png', 'image/webp' ), true );
			$results[ $key ] = array(
				'ok'          => $ok,
				'error'       => $ok ? '' : sprintf( __( 'Licence or file type not accepted (%1$s, %2$s)', 'benders-hub' ), $short ? $short : '?', $ii['mime'] ),
				'license'     => $short,
				'license_url' => $get( 'LicenseUrl' ),
				'creator'     => $get( 'Artist' ) ? $get( 'Artist' ) : $get( 'Credit' ),
				'attribution' => 'true' === $get( 'AttributionRequired' ),
				'restrictions'=> $get( 'Restrictions' ),
				'description' => wp_html_excerpt( $get( 'ImageDescription' ), 300, '…' ),
				'download'    => ! empty( $ii['thumburl'] ) && $ii['width'] > 1920 ? $ii['thumburl'] : $ii['url'],
				'preview'     => ! empty( $ii['thumburl'] ) ? $ii['thumburl'] : $ii['url'],
				'width'       => (int) $ii['width'],
				'height'      => (int) $ii['height'],
				'page'        => isset( $ii['descriptionurl'] ) ? $ii['descriptionurl'] : '',
				'checked'     => current_time( 'mysql' ),
			);
		}
	}
	foreach ( $results as $k => $r ) { $store[ $k ] = $r; }
	update_option( 'bdh_img_verify', $store, false );
	return $results;
}

function bdh_image_user_agent() {
	return 'BendersHubImageLibrary/' . BDH_VERSION . ' (' . home_url( '/' ) . '; WordPress theme importer)';
}

/**
 * Download URL and preview for a Pexels candidate (licence is confirmed manually).
 */
function bdh_pexels_urls( $id, $w = 1920 ) {
	$id = preg_replace( '/\D/', '', (string) $id );
	return 'https://images.pexels.com/photos/' . $id . '/pexels-photo-' . $id . '.jpeg?auto=compress&cs=tinysrgb&w=' . (int) $w;
}

/* ------------------------------------------------------------------ */
/* Import                                                              */
/* ------------------------------------------------------------------ */

/**
 * Import one approved candidate into the Media Library.
 *
 * @return int|WP_Error Attachment ID.
 */
function bdh_import_candidate( $key, $alt ) {
	$cands = bdh_image_candidates();
	if ( ! isset( $cands[ $key ] ) ) { return new WP_Error( 'bdh_img', __( 'Unknown image.', 'benders-hub' ) ); }
	$c        = $cands[ $key ];
	$imported = get_option( 'bdh_img_imported', array() );
	if ( ! empty( $imported[ $key ] ) && wp_attachment_is_image( (int) $imported[ $key ] ) ) { return (int) $imported[ $key ]; }

	if ( 'commons' === $c['source'] ) {
		$v = get_option( 'bdh_img_verify', array() );
		if ( empty( $v[ $key ]['ok'] ) ) { return new WP_Error( 'bdh_img', __( 'Verify the licence first — only accepted licences can be imported.', 'benders-hub' ) ); }
		$v           = $v[ $key ];
		$download    = $v['download'];
		$creator     = $v['creator'] ? $v['creator'] : __( 'Wikimedia Commons contributor', 'benders-hub' );
		$license     = $v['license'];
		$license_url = $v['license_url'];
		$source      = $v['page'] ? $v['page'] : $c['page'];
	} else {
		$download    = bdh_pexels_urls( $c['pexels_id'] );
		$creator     = $c['creator'] . ' (Pexels)';
		$license     = 'Pexels License';
		$license_url = 'https://www.pexels.com/license/';
		$source      = $c['page'];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( $c['filename'] );
	$res = wp_safe_remote_get( $download, array( 'timeout' => 60, 'stream' => true, 'filename' => $tmp, 'user-agent' => bdh_image_user_agent() ) );
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		return new WP_Error( 'bdh_img', sprintf( __( 'Download failed: %s', 'benders-hub' ), is_wp_error( $res ) ? $res->get_error_message() : 'HTTP ' . wp_remote_retrieve_response_code( $res ) ) );
	}
	$info = @getimagesize( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	if ( ! $info || ! in_array( $info['mime'], array( 'image/jpeg', 'image/png', 'image/webp' ), true ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		return new WP_Error( 'bdh_img', __( 'The downloaded file is not a supported image.', 'benders-hub' ) );
	}

	$caption = sprintf( 'Photo: %s / %s', $creator, $license );
	$att_id  = media_handle_sideload(
		array( 'name' => sanitize_file_name( $c['filename'] ), 'tmp_name' => $tmp ),
		0,
		$c['title'],
		array(
			'post_excerpt' => $caption,
			'post_content' => sprintf( "Source: %s\nCreator: %s\nLicence: %s %s\nImported: %s", $source, $creator, $license, $license_url, current_time( 'Y-m-d' ) ),
		)
	);
	if ( is_wp_error( $att_id ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		return $att_id;
	}
	update_post_meta( $att_id, '_wp_attachment_image_alt', sanitize_text_field( $alt ? $alt : $c['alt'] ) );
	update_post_meta( $att_id, '_bdh_source_url', esc_url_raw( $source ) );
	update_post_meta( $att_id, '_bdh_creator', sanitize_text_field( $creator ) );
	update_post_meta( $att_id, '_bdh_license', sanitize_text_field( $license ) );
	update_post_meta( $att_id, '_bdh_license_url', esc_url_raw( $license_url ) );
	update_post_meta( $att_id, '_bdh_imported_on', current_time( 'mysql' ) );
	update_post_meta( $att_id, '_bdh_library_key', $key );
	update_post_meta( $att_id, '_bdh_slots', $c['slots'] );

	$imported[ $key ] = (int) $att_id;
	update_option( 'bdh_img_imported', $imported, false );
	bdh_image_slot_map( true );
	return (int) $att_id;
}

/* ------------------------------------------------------------------ */
/* Admin page                                                          */
/* ------------------------------------------------------------------ */

function bdh_image_library_menu() {
	add_submenu_page( 'bdh-dashboard', __( 'Image Library', 'benders-hub' ), __( 'Image Library', 'benders-hub' ), 'upload_files', 'bdh-image-library', 'bdh_image_library_render' );
}
add_action( 'admin_menu', 'bdh_image_library_menu', 13 );

function bdh_image_library_handle() {
	if ( ! current_user_can( 'upload_files' ) || empty( $_POST['bdh_img_action'] ) ) { return array(); }
	check_admin_referer( 'bdh_img', 'bdh_img_nonce' );
	$action = sanitize_key( $_POST['bdh_img_action'] );
	$notes  = array();

	if ( 'verify_all' === $action ) {
		$res = bdh_verify_commons( array_keys( bdh_image_candidates() ) );
		$ok  = count( array_filter( wp_list_pluck( $res, 'ok' ) ) );
		$notes[] = array( 'success', sprintf( __( 'Checked %1$d Commons files: %2$d accepted, %3$d refused or unavailable.', 'benders-hub' ), count( $res ), $ok, count( $res ) - $ok ) );
	} elseif ( 'import' === $action ) {
		$approve = isset( $_POST['approve'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['approve'] ) ) : array();
		$alts    = isset( $_POST['alt'] ) ? (array) wp_unslash( $_POST['alt'] ) : array();
		$pexels  = isset( $_POST['pexels_ok'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['pexels_ok'] ) ) : array();
		$cands   = bdh_image_candidates();
		if ( ! $approve ) { $notes[] = array( 'warning', __( 'Tick "Approve" for each photo you have looked at and want to import.', 'benders-hub' ) ); }
		foreach ( $approve as $key ) {
			if ( ! isset( $cands[ $key ] ) ) { continue; }
			if ( 'pexels' === $cands[ $key ]['source'] && ! in_array( $key, $pexels, true ) ) {
				$notes[] = array( 'warning', sprintf( __( '%s: confirm the Pexels licence on its page first.', 'benders-hub' ), $cands[ $key ]['title'] ) );
				continue;
			}
			@set_time_limit( 120 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
			$r = bdh_import_candidate( $key, isset( $alts[ $key ] ) ? sanitize_text_field( $alts[ $key ] ) : '' );
			$notes[] = is_wp_error( $r ) ? array( 'error', $cands[ $key ]['title'] . ': ' . $r->get_error_message() ) : array( 'success', sprintf( __( 'Imported %1$s (attachment #%2$d).', 'benders-hub' ), $cands[ $key ]['title'], $r ) );
		}
	} elseif ( 'slots' === $action ) {
		$in  = isset( $_POST['slot'] ) ? (array) wp_unslash( $_POST['slot'] ) : array();
		$out = array();
		foreach ( bdh_image_slots() as $slot => $label ) {
			$id = isset( $in[ $slot ] ) ? absint( $in[ $slot ] ) : 0;
			if ( $id && wp_attachment_is_image( $id ) ) { $out[ $slot ] = $id; }
		}
		update_option( 'bdh_img_slot_override', $out, false );
		$notes[] = array( 'success', __( 'Topic images saved.', 'benders-hub' ) );
	} elseif ( 'featured' === $action ) {
		$posts = isset( $_POST['feature'] ) ? array_map( 'absint', (array) wp_unslash( $_POST['feature'] ) ) : array();
		$n = 0;
		foreach ( $posts as $pid ) {
			if ( ! current_user_can( 'edit_post', $pid ) || has_post_thumbnail( $pid ) ) { continue; }
			$att = bdh_library_attachment( bdh_post_image_slot( $pid ), $pid );
			if ( $att && set_post_thumbnail( $pid, $att ) ) { $n++; }
		}
		$notes[] = array( 'success', sprintf( _n( '%d featured image set.', '%d featured images set.', $n, 'benders-hub' ), $n ) );
	} elseif ( 'credits_page' === $action ) {
		$existing = get_page_by_path( 'image-credits' );
		if ( $existing ) {
			$notes[] = array( 'info', __( 'An "image-credits" page already exists.', 'benders-hub' ) );
		} else {
			$id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => __( 'Image credits', 'benders-hub' ), 'post_name' => 'image-credits', 'post_content' => "<!-- wp:paragraph -->\n<p>" . esc_html__( 'Photographs on Benders Hub that are not our own are used under the licences listed below. Thank you to the photographers who share their work.', 'benders-hub' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->\n[bdh_image_credits]\n<!-- /wp:shortcode -->" ) );
			$notes[] = $id ? array( 'success', __( 'Image credits page created.', 'benders-hub' ) ) : array( 'error', __( 'Could not create the page.', 'benders-hub' ) );
		}
	}
	return $notes;
}

function bdh_image_library_render() {
	if ( ! current_user_can( 'upload_files' ) ) { return; }
	$notes    = bdh_image_library_handle();
	$cands    = bdh_image_candidates();
	$verify   = get_option( 'bdh_img_verify', array() );
	$imported = get_option( 'bdh_img_imported', array() );
	$slots    = bdh_image_slots();
	?>
	<div class="wrap bdh-admin">
		<h1><?php esc_html_e( 'Benders Hub — Image Library', 'benders-hub' ); ?></h1>
		<p><?php esc_html_e( 'Licence-checked photographs for topics without their own images. Work through the three steps; nothing is imported without your approval.', 'benders-hub' ); ?></p>
		<?php foreach ( $notes as $n ) : ?>
			<div class="notice notice-<?php echo esc_attr( $n[0] ); ?>"><p><?php echo esc_html( $n[1] ); ?></p></div>
		<?php endforeach; ?>

		<h2><?php esc_html_e( '1. Check licences', 'benders-hub' ); ?></h2>
		<p><?php esc_html_e( 'Asks the Wikimedia Commons API for each file\'s current licence, author and download link. Only CC0, public domain, CC BY and CC BY-SA files are accepted. Pexels photos are confirmed by you on the Pexels page.', 'benders-hub' ); ?></p>
		<form method="post"><?php wp_nonce_field( 'bdh_img', 'bdh_img_nonce' ); ?><button class="button button-primary" name="bdh_img_action" value="verify_all"><?php esc_html_e( 'Check all Commons licences', 'benders-hub' ); ?></button></form>

		<h2><?php esc_html_e( '2. Review and import', 'benders-hub' ); ?></h2>
		<p><?php esc_html_e( 'Look at each preview. Only approve photos that clearly show the subject in the "Used for" column, and check the notes for trademarks or identifiable people. Edit the alt text so it describes what is actually visible.', 'benders-hub' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'bdh_img', 'bdh_img_nonce' ); ?>
			<table class="widefat striped bdh-img-table">
				<thead><tr>
					<th style="width:230px"><?php esc_html_e( 'Preview', 'benders-hub' ); ?></th>
					<th><?php esc_html_e( 'Photo, licence & notes', 'benders-hub' ); ?></th>
					<th style="width:150px"><?php esc_html_e( 'Used for', 'benders-hub' ); ?></th>
					<th style="width:28%"><?php esc_html_e( 'Alt text', 'benders-hub' ); ?></th>
					<th style="width:110px"><?php esc_html_e( 'Approve', 'benders-hub' ); ?></th>
				</tr></thead>
				<tbody>
				<?php foreach ( $cands as $key => $c ) :
					$v      = isset( $verify[ $key ] ) ? $verify[ $key ] : null;
					$is_imp = ! empty( $imported[ $key ] ) && wp_attachment_is_image( (int) $imported[ $key ] );
					if ( 'pexels' === $c['source'] ) {
						$preview = bdh_pexels_urls( $c['pexels_id'], 350 );
					} elseif ( $v && ! empty( $v['preview'] ) ) {
						$preview = $v['preview'];
					} else {
						$preview = 'https://commons.wikimedia.org/wiki/Special:FilePath/' . rawurlencode( str_replace( 'File:', '', $c['file'] ) ) . '?width=330';
					}
				?>
					<tr>
						<td><a href="<?php echo esc_url( $c['page'] ); ?>" target="_blank" rel="noopener"><img src="<?php echo esc_url( $preview ); ?>" alt="" loading="lazy" style="width:220px;height:auto;display:block;background:#f0f0f1"></a></td>
						<td>
							<strong><?php echo esc_html( $c['title'] ); ?></strong><br>
							<a href="<?php echo esc_url( $c['page'] ); ?>" target="_blank" rel="noopener"><?php echo 'pexels' === $c['source'] ? esc_html__( 'Pexels page', 'benders-hub' ) : esc_html__( 'Commons file page', 'benders-hub' ); ?></a>
							<p>
							<?php if ( $is_imp ) : ?>
								<span style="color:#008a20;font-weight:600">● <?php printf( esc_html__( 'Imported (#%d)', 'benders-hub' ), (int) $imported[ $key ] ); ?></span>
							<?php elseif ( 'pexels' === $c['source'] ) : ?>
								<?php esc_html_e( 'Licence: Pexels License (confirm on the page).', 'benders-hub' ); ?><br>
								<label><input type="checkbox" name="pexels_ok[]" value="<?php echo esc_attr( $key ); ?>"> <?php esc_html_e( 'I checked this photo is free to use under the Pexels License', 'benders-hub' ); ?></label>
							<?php elseif ( $v && $v['ok'] ) : ?>
								<span style="color:#008a20">✔ <?php echo esc_html( $v['license'] ); ?></span> · <?php echo esc_html( $v['creator'] ); ?> · <?php echo (int) $v['width']; ?>×<?php echo (int) $v['height']; ?>
								<?php if ( $v['restrictions'] ) : ?><br><em><?php esc_html_e( 'Restrictions noted on Commons:', 'benders-hub' ); ?> <?php echo esc_html( $v['restrictions'] ); ?></em><?php endif; ?>
							<?php elseif ( $v ) : ?>
								<span style="color:#d63638">✖ <?php echo esc_html( $v['error'] ); ?></span>
							<?php else : ?>
								<span style="color:#996800"><?php esc_html_e( 'Licence not yet checked (step 1).', 'benders-hub' ); ?></span>
							<?php endif; ?>
							</p>
							<p class="description"><?php echo esc_html( $c['notes'] ); ?></p>
						</td>
						<td><?php echo esc_html( implode( ', ', array_map( function ( $s ) use ( $slots ) { return isset( $slots[ $s ] ) ? $slots[ $s ] : $s; }, $c['slots'] ) ) ); ?></td>
						<td><textarea name="alt[<?php echo esc_attr( $key ); ?>]" rows="3" style="width:100%"><?php echo esc_textarea( $c['alt'] ); ?></textarea></td>
						<td>
							<?php if ( ! $is_imp && ( 'pexels' === $c['source'] || ( $v && $v['ok'] ) ) ) : ?>
								<label><input type="checkbox" name="approve[]" value="<?php echo esc_attr( $key ); ?>"> <?php esc_html_e( 'Approve', 'benders-hub' ); ?></label>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<p><button class="button button-primary" name="bdh_img_action" value="import"><?php esc_html_e( 'Import approved photos', 'benders-hub' ); ?></button></p>
		</form>

		<h2><?php esc_html_e( '3. Topic images & featured images', 'benders-hub' ); ?></h2>
		<p><?php esc_html_e( 'Imported photos are used automatically for matching topics. To use your own photo for a topic, enter its Media Library ID.', 'benders-hub' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'bdh_img', 'bdh_img_nonce' ); ?>
			<table class="widefat striped" style="max-width:900px">
				<thead><tr><th><?php esc_html_e( 'Topic', 'benders-hub' ); ?></th><th><?php esc_html_e( 'Current image', 'benders-hub' ); ?></th><th><?php esc_html_e( 'Use attachment ID', 'benders-hub' ); ?></th></tr></thead>
				<tbody>
				<?php $override = get_option( 'bdh_img_slot_override', array() ); foreach ( $slots as $slot => $label ) : $att = bdh_library_attachment( $slot ); ?>
					<tr>
						<td><?php echo esc_html( $label ); ?></td>
						<td><?php echo $att ? wp_get_attachment_image( $att, array( 120, 80 ) ) : '<em>' . esc_html__( 'None — a drawn diagram is shown', 'benders-hub' ) . '</em>'; ?></td>
						<td><input type="number" min="0" name="slot[<?php echo esc_attr( $slot ); ?>]" value="<?php echo isset( $override[ $slot ] ) ? (int) $override[ $slot ] : ''; ?>" style="width:110px"></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<p><button class="button" name="bdh_img_action" value="slots"><?php esc_html_e( 'Save topic images', 'benders-hub' ); ?></button></p>
		</form>

		<?php
		$missing = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 100, 'fields' => 'ids', 'meta_query' => array( array( 'key' => '_thumbnail_id', 'compare' => 'NOT EXISTS' ) ) ) );
		?>
		<h3><?php esc_html_e( 'Posts without a featured image', 'benders-hub' ); ?></h3>
		<?php if ( ! $missing ) : ?>
			<p><?php esc_html_e( 'Every published post has a featured image.', 'benders-hub' ); ?></p>
		<?php else : ?>
			<form method="post">
				<?php wp_nonce_field( 'bdh_img', 'bdh_img_nonce' ); ?>
				<table class="widefat striped" style="max-width:900px">
					<thead><tr><th style="width:40px"></th><th><?php esc_html_e( 'Post', 'benders-hub' ); ?></th><th><?php esc_html_e( 'Suggested image', 'benders-hub' ); ?></th></tr></thead>
					<tbody>
					<?php foreach ( $missing as $pid ) : $att = bdh_library_attachment( bdh_post_image_slot( $pid ), $pid ); ?>
						<tr>
							<td><?php if ( $att ) : ?><input type="checkbox" name="feature[]" value="<?php echo (int) $pid; ?>"><?php endif; ?></td>
							<td><a href="<?php echo esc_url( get_edit_post_link( $pid ) ); ?>"><?php echo esc_html( get_the_title( $pid ) ); ?></a></td>
							<td><?php echo $att ? wp_get_attachment_image( $att, array( 120, 80 ) ) . ' <small>' . esc_html( get_the_title( $att ) ) . '</small>' : '<em>' . esc_html__( 'No matching photo imported yet', 'benders-hub' ) . '</em>'; ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<p><button class="button" name="bdh_img_action" value="featured"><?php esc_html_e( 'Set ticked suggestions as featured images', 'benders-hub' ); ?></button></p>
			</form>
		<?php endif; ?>

		<h3><?php esc_html_e( 'Credits', 'benders-hub' ); ?></h3>
		<p><?php esc_html_e( 'CC BY and CC BY-SA photos must be credited. Each imported photo carries its credit in the caption (shown under featured images), and the [bdh_image_credits] shortcode lists them all.', 'benders-hub' ); ?></p>
		<form method="post"><?php wp_nonce_field( 'bdh_img', 'bdh_img_nonce' ); ?><button class="button" name="bdh_img_action" value="credits_page"><?php esc_html_e( 'Create an "Image credits" page', 'benders-hub' ); ?></button></form>
	</div>
	<?php
}
