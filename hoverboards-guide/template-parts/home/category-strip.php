<?php
/**
 * Category tiles: quick visual navigation into the main topics.
 */
$slugs = array_filter( array_map( 'trim', explode( ',', get_theme_mod( 'hbg_cat_strip_slugs', 'hoverboards,e-scooters,ebikes,skateboards,buying-guide,how-to' ) ) ) );
$terms = array();
foreach ( $slugs as $slug ) {
	$t = get_term_by( 'slug', sanitize_title( $slug ), 'category' );
	if ( $t && $t->count > 0 ) {
		$terms[] = $t;
	}
}
if ( count( $terms ) < 3 ) {
	$terms = get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 6 ) );
}
if ( ! $terms ) {
	return;
}
?>
<section class="cat-strip" aria-label="<?php esc_attr_e( 'Browse by category', 'hoverboards-guide' ); ?>">
	<div class="container">
		<div class="cat-strip__grid">
			<?php foreach ( $terms as $t ) { hbg_category_tile( $t ); } ?>
		</div>
	</div>
</section>
