<?php
/**
 * E. Reviews & comparisons: curated evergreen cards first, then the newest hoverboard posts via "load more".
 */
$count = max( 3, (int) get_theme_mod( 'hbg_featured_count', 6 ) );
$posts = hbg_get_featured_posts( $count );
if ( ! $posts ) {
	return;
}
$term    = get_term_by( 'slug', 'hoverboards', 'category' );
$exclude = wp_list_pluck( $posts, 'ID' );
$all_url = hbg_all_articles_url();
?>
<section class="section section--alt reviews" id="reviews" aria-labelledby="reviews-title">
	<div class="container">
		<?php hbg_section_heading( array( 'id' => 'reviews-title', 'kicker' => __( 'Reviews & comparisons', 'hoverboards-guide' ), 'title' => __( 'The hoverboard reviews and comparisons readers use most', 'hoverboards-guide' ), 'link' => $all_url, 'link_label' => __( 'All hoverboard articles', 'hoverboards-guide' ) ) ); ?>
		<div class="grid grid--3" id="latest-grid" data-load-more-grid>
			<?php foreach ( $posts as $p ) : ?>
				<?php $GLOBALS['post'] = $p; setup_postdata( $p ); // phpcs:ignore ?>
				<?php get_template_part( 'template-parts/content', 'card', array( 'heading' => 'h3' ) ); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
		<?php if ( $term && $term->count > count( $posts ) ) : ?>
		<div class="load-more-wrap">
			<button type="button" class="btn btn--ghost load-more" data-load-more data-page="0" data-per-page="6" data-cat="<?php echo esc_attr( $term->term_id ); ?>" data-exclude="<?php echo esc_attr( implode( ',', $exclude ) ); ?>"><?php esc_html_e( 'Load more hoverboard articles', 'hoverboards-guide' ); ?></button>
		</div>
		<?php endif; ?>
	</div>
</section>
