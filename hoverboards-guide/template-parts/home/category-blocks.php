<?php
/**
 * Two side-by-side category story blocks (lead story + list), Rich-Magazine style.
 */
$blocks = array(
	hbg_resolve_category( 'hbg_cat_block_1', array( 'hoverboards' ) ),
	hbg_resolve_category( 'hbg_cat_block_2', array( 'e-scooters', 'scooters' ) ),
);
$blocks  = array_filter( $blocks );
if ( ! $blocks ) {
	return;
}
$exclude = hbg_featured_ids();
?>
<section class="cat-blocks section section--alt" aria-label="<?php esc_attr_e( 'Stories by category', 'hoverboards-guide' ); ?>">
	<div class="container">
		<div class="cat-blocks__grid">
			<?php foreach ( $blocks as $term ) :
				$preset = hbg_category_preset( $term );
				$q = new WP_Query( array( 'cat' => $term->term_id, 'posts_per_page' => 5, 'post__not_in' => $exclude, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
				if ( ! $q->have_posts() ) {
					continue;
				}
			?>
			<div class="cat-block" style="--cat:<?php echo esc_attr( $preset['color'] ); ?>">
				<div class="cat-block__head">
					<h2 class="cat-block__title"><a href="<?php echo esc_url( get_category_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></h2>
					<a href="<?php echo esc_url( get_category_link( $term ) ); ?>" class="cat-block__link"><?php esc_html_e( 'View all', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a>
				</div>
				<?php $i = 0; while ( $q->have_posts() ) : $q->the_post(); $i++; ?>
					<?php if ( 1 === $i ) : ?>
						<article class="card card--lead">
							<a href="<?php the_permalink(); ?>" class="card__media" tabindex="-1" aria-hidden="true">
								<?php hbg_post_thumbnail( 'medium_large', array( 'class' => 'card__img', 'sizes' => '(max-width: 900px) 100vw, 560px' ) ); ?>
							</a>
							<div class="card__body">
								<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p class="card__excerpt"><?php echo esc_html( hbg_excerpt( 22 ) ); ?></p>
								<?php hbg_post_meta(); ?>
							</div>
						</article>
						<ol class="post-list">
					<?php else : ?>
						<li class="post-list__item">
							<a href="<?php the_permalink(); ?>" class="post-list__thumb" tabindex="-1" aria-hidden="true"><?php hbg_post_thumbnail( 'thumbnail', array( 'sizes' => '96px' ) ); ?></a>
							<div class="post-list__body">
								<a href="<?php the_permalink(); ?>" class="post-list__title"><?php the_title(); ?></a>
								<?php hbg_post_meta( array( 'class' => 'post-meta--sm' ) ); ?>
							</div>
						</li>
					<?php endif; ?>
				<?php endwhile; ?>
				<?php if ( $i > 1 ) : ?></ol><?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
