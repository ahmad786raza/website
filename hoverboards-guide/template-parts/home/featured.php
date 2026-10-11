<?php
/**
 * Featured stories: one lead story + four secondary stories.
 */
$count = max( 3, (int) get_theme_mod( 'hbg_featured_count', 5 ) );
$posts = hbg_get_featured_posts( $count );
if ( ! $posts ) {
	return;
}
$lead = array_shift( $posts );
?>
<section class="featured" aria-labelledby="featured-heading">
	<div class="container">
		<h2 class="screen-reader-text" id="featured-heading"><?php esc_html_e( 'Featured stories', 'hoverboards-guide' ); ?></h2>
		<div class="featured__grid">

			<?php
			$GLOBALS['post'] = $lead; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			setup_postdata( $lead );
			?>
			<article class="feat feat--lead" id="post-<?php the_ID(); ?>">
				<a href="<?php the_permalink(); ?>" class="feat__media" tabindex="-1" aria-hidden="true">
					<?php hbg_post_thumbnail( 'large', array( 'class' => 'feat__img', 'loading' => 'lazy', 'sizes' => '(max-width: 900px) 100vw, 800px' ) ); ?>
				</a>
				<div class="feat__body">
					<?php hbg_cat_badge(); ?>
					<h3 class="feat__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p class="feat__excerpt"><?php echo esc_html( hbg_excerpt( 28 ) ); ?></p>
					<?php hbg_post_meta( array( 'author' => true, 'reading' => true, 'class' => 'post-meta--light' ) ); ?>
				</div>
			</article>
			<?php wp_reset_postdata(); ?>

			<div class="featured__side">
				<?php foreach ( $posts as $p ) : ?>
					<?php
					$GLOBALS['post'] = $p; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					setup_postdata( $p );
					?>
					<article class="feat feat--side" id="post-<?php the_ID(); ?>">
						<a href="<?php the_permalink(); ?>" class="feat__media" tabindex="-1" aria-hidden="true">
							<?php hbg_post_thumbnail( 'medium_large', array( 'class' => 'feat__img', 'sizes' => '(max-width: 640px) 40vw, 200px' ) ); ?>
						</a>
						<div class="feat__body">
							<?php hbg_cat_badge( null, true, 'cat-badge--sm' ); ?>
							<h3 class="feat__title feat__title--sm"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php hbg_post_meta( array( 'class' => 'post-meta--sm' ) ); ?>
						</div>
					</article>
				<?php endforeach; wp_reset_postdata(); ?>
			</div>

		</div>
	</div>
</section>
