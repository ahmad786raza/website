<?php
/**
 * Latest articles grid with AJAX "load more".
 * The sidebar is off by default on the homepage (v2.1) — enable it under
 * Customize → Hoverboards Guide → Homepage Sections if you prefer the two-column look.
 */
$per     = max( 3, (int) get_theme_mod( 'hbg_latest_count', 6 ) );
$exclude = hbg_featured_ids();
$q       = new WP_Query( array( 'posts_per_page' => $per, 'post__not_in' => $exclude, 'ignore_sticky_posts' => true ) );
if ( ! $q->have_posts() ) {
	return;
}
$with_sidebar = (bool) get_theme_mod( 'hbg_home_sidebar', false );
$all_url      = hbg_all_articles_url();
?>
<section class="latest section<?php echo $with_sidebar ? ' latest--sidebar' : ''; ?>" id="latest" aria-labelledby="latest-heading">
	<div class="container">
		<?php if ( $with_sidebar ) : ?><div class="layout layout--sidebar"><div class="layout__main"><?php endif; ?>

			<?php hbg_section_heading( array(
				'id'         => 'latest-heading',
				'kicker'     => __( 'Fresh off the road', 'hoverboards-guide' ),
				'title'      => __( 'Latest reviews & guides', 'hoverboards-guide' ),
				'link'       => $all_url,
				'link_label' => __( 'All articles', 'hoverboards-guide' ),
			) ); ?>

			<div class="grid grid--3" id="latest-grid" data-load-more-grid>
				<?php while ( $q->have_posts() ) : $q->the_post(); ?>
					<?php get_template_part( 'template-parts/content', 'card' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>

			<?php if ( $q->max_num_pages > 1 ) : ?>
			<div class="load-more-wrap">
				<button type="button" class="btn btn--outline load-more" data-load-more data-page="1" data-per-page="<?php echo esc_attr( $per ); ?>" data-exclude="<?php echo esc_attr( implode( ',', $exclude ) ); ?>" data-max="<?php echo esc_attr( $q->max_num_pages ); ?>">
					<?php esc_html_e( 'Load more articles', 'hoverboards-guide' ); ?>
				</button>
			</div>
			<?php endif; ?>

		<?php if ( $with_sidebar ) : ?></div><?php get_sidebar(); ?></div><?php endif; ?>
	</div>
</section>
