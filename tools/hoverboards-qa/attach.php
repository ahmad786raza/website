<?php
// Local test only: attach the downloaded featured images to the imported posts.
$www = $argv[1]; $dir = $argv[2];
$_SERVER['HTTP_HOST'] = '127.0.0.1:8080';
define( 'WP_USE_THEMES', false );
require $www . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
$posts = get_posts( array( 'post_type' => 'post', 'posts_per_page' => -1, 'post_status' => 'publish', 'fields' => 'ids' ) );
$done = 0; $skip = 0;
foreach ( $posts as $pid ) {
	if ( has_post_thumbnail( $pid ) ) { $skip++; continue; }
	$mid = (int) get_post_meta( $pid, '_hbg_featured_media_src', true );
	if ( ! $mid ) { continue; }
	$files = glob( "$dir/$mid.*" );
	if ( ! $files ) { continue; }
	$src = $files[0];
	if ( filesize( $src ) < 1000 ) { continue; }
	$existing = get_posts( array( 'post_type' => 'attachment', 'meta_key' => '_hbg_src_media', 'meta_value' => $mid, 'fields' => 'ids', 'posts_per_page' => 1 ) );
	if ( $existing ) { $aid = $existing[0]; } else {
		$tmp = wp_tempnam( basename( $src ) );
		copy( $src, $tmp );
		$aid = media_handle_sideload( array( 'name' => basename( $src ), 'tmp_name' => $tmp ), $pid );
		if ( is_wp_error( $aid ) ) { echo "ERR $pid: " . $aid->get_error_message() . "\n"; continue; }
		update_post_meta( $aid, '_hbg_src_media', $mid );
		update_post_meta( $aid, '_wp_attachment_image_alt', get_the_title( $pid ) );
	}
	set_post_thumbnail( $pid, $aid );
	$done++;
}
echo "attached $done, skipped $skip\n";
