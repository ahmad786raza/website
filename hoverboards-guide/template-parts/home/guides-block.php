<?php
/**
 * Guides & how-to stories (full-width, four cards) plus an optional extra category row.
 */
$guides = hbg_resolve_category( 'hbg_cat_block_3', array( 'buying-guide', 'guide', 'how-to' ) );
$extra  = hbg_resolve_category( 'hbg_cat_block_4', array( 'ebikes', 'skateboards', 'unicycles' ) );
$exclude = hbg_featured_ids();

$rows = array();
if ( $guides ) {
	$cats = array( $guides->term_id );
	foreach ( array( 'guide', 'how-to', 'buying-guide' ) as $slug ) {
		$t = get_term_by( 'slug', $slug, 'category' );
		if ( $t && $t->term_id !== $guides->term_id ) {
			$cats[] = $t->term_id;
		}
	}
	$rows[] = array( 'term' => $guides, 'kicker' => __( 'Learn before you buy', 'hoverboards-guide' ), 'title' => __( 'Guides & how-tos', 'hoverboards-guide' ), 'args' => array( 'category__in' => $cats ) );
}
if ( $extra && ( ! $guides || $extra->term_id !== $guides->term_id ) ) {
	$rows[] = array( 'term' => $extra, 'kicker' => __( 'More electric rides', 'hoverboards-guide' ), 'title' => $extra->name, 'args' => array( 'cat' => $extra->term_id ) );
}
if ( ! $rows ) {
	return;
}
?>
<section class="guides-block section" aria-label="<?php esc_attr_e( 'Guides', 'hoverboards-guide' ); ?>">
	<div class="container">
		<?php foreach ( $rows as $row ) :
			$q = new WP_Query( array_merge( array( 'posts_per_page' => 4, 'post__not_in' => $exclude, 'ignore_sticky_posts' => true, 'no_found_rows' => true ), $row['args'] ) );
			if ( ! $q->have_posts() ) {
				continue;
			}
			$preset = hbg_category_preset( $row['term'] );
		?>
		<div class="guides-row" style="--cat:<?php echo esc_attr( $preset['color'] ); ?>">
			<?php hbg_section_heading( array( 'kicker' => $row['kicker'], 'title' => $row['title'], 'link' => get_category_link( $row['term'] ), 'link_label' => __( 'View all', 'hoverboards-guide' ) ) ); ?>
			<div class="grid grid--4">
				<?php while ( $q->have_posts() ) : $q->the_post(); ?>
					<?php get_template_part( 'template-parts/content', 'card', array( 'compact' => true ) ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</section>
