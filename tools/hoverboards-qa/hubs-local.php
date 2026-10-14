<?php
// Local replica only: create the hoverboard hub categories and assign the curated posts (mirrors the Setup page action).
$www = isset( $argv[1] ) ? rtrim( $argv[1], '/' ) : getcwd() . '/www';
$_SERVER['HTTP_HOST'] = '127.0.0.1:8080';
require $www . '/wp-load.php';
$parent = get_term_by( 'slug', 'hoverboards', 'category' );
foreach ( hbg_topic_hubs() as $slug => $hub ) {
	$term = get_term_by( 'slug', $slug, 'category' );
	if ( ! $term ) {
		$r = wp_insert_term( $hub['name'], 'category', array( 'slug' => $slug, 'parent' => $parent ? $parent->term_id : 0, 'description' => $hub['desc'] ) );
		if ( is_wp_error( $r ) ) { echo "$slug: " . $r->get_error_message() . "\n"; continue; }
		$term = get_term( $r['term_id'], 'category' );
	}
	$n = 0;
	foreach ( $hub['posts'] as $ps ) {
		$p = hbg_post_by_slug( $ps );
		if ( ! $p ) { continue; }
		$cats = wp_get_post_categories( $p->ID );
		if ( ! in_array( $term->term_id, $cats, true ) ) { wp_set_post_categories( $p->ID, array_merge( $cats, array( $term->term_id ) ) ); $n++; }
	}
	echo "$slug: assigned $n\n";
}
