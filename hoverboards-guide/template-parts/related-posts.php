<?php
/**
 * Related articles (by tag, then category).
 */
$related = hbg_related_posts( get_the_ID(), 3 );
if ( ! $related->have_posts() ) {
	return;
}
?>
<section class="related" aria-labelledby="related-heading">
	<?php hbg_section_heading( array( 'id' => 'related-heading', 'kicker' => __( 'Keep reading', 'hoverboards-guide' ), 'title' => __( 'Related articles', 'hoverboards-guide' ), 'tag' => 'h2' ) ); ?>
	<div class="grid grid--3">
		<?php while ( $related->have_posts() ) : $related->the_post(); ?>
			<?php get_template_part( 'template-parts/content', 'card', array( 'compact' => true ) ); ?>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</section>
