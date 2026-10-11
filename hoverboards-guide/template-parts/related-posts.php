<?php
$related = hbg_related_posts( get_the_ID(), 3 );
if ( ! $related ) {
	return;
}
?>
<section class="related" aria-labelledby="related-title">
	<?php hbg_section_heading( array( 'id' => 'related-title', 'kicker' => __( 'Keep reading', 'hoverboards-guide' ), 'title' => __( 'Related guides', 'hoverboards-guide' ), 'tag' => 'h2', 'class' => 'section-heading--sm' ) ); ?>
	<div class="grid grid--3">
		<?php foreach ( $related as $rp ) : $GLOBALS['post'] = $rp; setup_postdata( $rp ); // phpcs:ignore ?>
			<?php get_template_part( 'template-parts/content', 'card', array( 'compact' => true ) ); ?>
		<?php endforeach; wp_reset_postdata(); ?>
	</div>
</section>
