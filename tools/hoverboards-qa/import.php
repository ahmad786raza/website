<?php
// Local test only: replicate the live site's categories, posts and pages from the public REST data.
$www  = $argv[1];
$base = $argv[2];
$_SERVER['HTTP_HOST'] = '127.0.0.1:8080';
define( 'WP_USE_THEMES', false );
require $www . '/wp-load.php';
update_option( 'blogname', 'Hoverboards Guide' );
update_option( 'blogdescription', 'Best Hoverboards & Electric Scooters' );
update_option( 'permalink_structure', '/%postname%/' );
update_option( 'timezone_string', 'UTC' );
$cats = json_decode( file_get_contents( "$base/categories.json" ), true );
$map  = array();
foreach ( $cats as $c ) {
	$t = term_exists( $c['slug'], 'category' );
	if ( ! $t ) {
		$t = wp_insert_term( $c['name'], 'category', array( 'slug' => $c['slug'], 'description' => $c['description'] ) );
	}
	$map[ $c['id'] ] = (int) ( is_array( $t ) ? $t['term_id'] : $t );
}
echo "categories: " . count( $map ) . "\n";
$n = 0;
foreach ( array( 1, 2, 3 ) as $pg ) {
	$posts = json_decode( file_get_contents( "$base/posts-full-$pg.json" ), true );
	foreach ( $posts as $p ) {
		if ( get_post( $p['id'] ) ) { continue; }
		$id = wp_insert_post( array(
			'import_id'     => $p['id'],
			'post_title'    => html_entity_decode( $p['title']['rendered'], ENT_QUOTES, 'UTF-8' ),
			'post_name'     => $p['slug'],
			'post_content'  => $p['content']['rendered'],
			'post_excerpt'  => trim( wp_strip_all_tags( $p['excerpt']['rendered'] ) ),
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_date'     => $p['date'],
			'post_date_gmt' => $p['date'],
			'post_modified' => $p['modified'],
			'post_modified_gmt' => $p['modified'],
			'post_author'   => 1,
			'post_category' => array_values( array_filter( array_map( function ( $cid ) use ( $map ) { return isset( $map[ $cid ] ) ? $map[ $cid ] : 0; }, $p['categories'] ) ) ),
			'meta_input'    => array( 'hbg_views' => wp_rand( 0, 400 ), '_hbg_featured_media_src' => $p['featured_media'] ),
		), true );
		if ( is_wp_error( $id ) ) { echo "ERR {$p['slug']}: " . $id->get_error_message() . "\n"; continue; }
		$n++;
	}
}
echo "posts imported: $n\n";
$pages = json_decode( file_get_contents( "$base/pages-full.json" ), true );
foreach ( $pages as $p ) {
	if ( get_post( $p['id'] ) ) { continue; }
	wp_insert_post( array( 'import_id' => $p['id'], 'post_title' => html_entity_decode( $p['title']['rendered'], ENT_QUOTES, 'UTF-8' ), 'post_name' => $p['slug'], 'post_content' => $p['content']['rendered'], 'post_status' => 'publish', 'post_type' => 'page', 'post_author' => 1 ) );
}
echo "pages done\n";
switch_theme( 'hoverboards-guide' );
flush_rewrite_rules();
echo "theme: " . get_option( 'stylesheet' ) . " | posts: " . wp_count_posts()->publish . "\n";
