<?php
/**
 * Most-read strip: five numbered reader favourites in one row.
 * Replaces the homepage sidebar list from v2.0 (which left an empty column next to the grid).
 */
$q = hbg_popular_posts( 5 );
if ( ! $q->have_posts() ) {
	return;
}
?>
<section class="most-read section section--sm" id="most-read" aria-labelledby="most-read-heading">
	<div class="container">
		<?php hbg_section_heading( array(
			'id'     => 'most-read-heading',
			'kicker' => __( 'Reader favourites', 'hoverboards-guide' ),
			'title'  => __( 'Most read right now', 'hoverboards-guide' ),
		) ); ?>
		<ol class="most-read__list">
			<?php $i = 0; while ( $q->have_posts() ) : $q->the_post(); $i++; ?>
			<li class="mr">
				<div class="mr__top">
					<span class="mr__num" aria-hidden="true"><?php echo esc_html( str_pad( (string) $i, 2, '0', STR_PAD_LEFT ) ); ?></span>
					<?php hbg_cat_badge( null, true, 'cat-badge--sm' ); ?>
				</div>
				<a href="<?php the_permalink(); ?>" class="mr__thumb" tabindex="-1" aria-hidden="true">
					<?php hbg_post_thumbnail( 'medium_large', array( 'sizes' => '(max-width: 640px) 70vw, 220px' ) ); ?>
				</a>
				<a href="<?php the_permalink(); ?>" class="mr__title"><?php the_title(); ?></a>
				<?php hbg_post_meta( array( 'class' => 'post-meta--sm', 'reading' => true ) ); ?>
			</li>
			<?php endwhile; wp_reset_postdata(); ?>
		</ol>
	</div>
</section>
