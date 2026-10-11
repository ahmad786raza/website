<?php
/**
 * Latest: one feature, three standard cards (plus "load more"), and two side modules —
 * more recent headlines and either genuinely most-read articles or the editor's picks.
 */
$posts = hbg_latest_posts( 10 );
if ( count( $posts ) < 4 ) {
	return;
}
$feature = array_shift( $posts );
$cards   = array_splice( $posts, 0, 3 );
$more    = array_slice( $posts, 0, 6 );
$popular = hbg_popular_module( 5 );
$all_url = hbg_all_articles_url();
?>
<section class="zone latest" id="latest" aria-labelledby="latest-title">
	<div class="container">
		<?php hbg_zone_heading( array( 'title' => __( 'Latest', 'hoverboards-guide' ), 'kicker' => __( 'New reviews, guides and fixes', 'hoverboards-guide' ), 'url' => $all_url, 'more' => __( 'All articles', 'hoverboards-guide' ), 'id' => 'latest-title' ) ); ?>
		<div class="latest__grid">
			<div class="latest__main">
				<?php $GLOBALS['post'] = $feature; setup_postdata( $feature ); // phpcs:ignore ?>
				<?php get_template_part( 'template-parts/content', 'card', array( 'heading' => 'h3', 'variant' => 'feature' ) ); ?>
				<?php wp_reset_postdata(); ?>
				<div class="grid grid--3 latest__cards" id="latest-grid" data-load-more-grid>
					<?php foreach ( $cards as $p ) : ?>
						<?php $GLOBALS['post'] = $p; setup_postdata( $p ); // phpcs:ignore ?>
						<?php get_template_part( 'template-parts/content', 'card', array( 'heading' => 'h3' ) ); ?>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
				<div class="load-more-wrap">
					<button type="button" class="btn btn--ghost load-more" data-load-more data-page="0" data-per-page="6" data-cat="0" data-exclude="<?php echo esc_attr( implode( ',', hbg_shown_ids() ) ); ?>"><?php esc_html_e( 'Load more articles', 'hoverboards-guide' ); ?></button>
				</div>
			</div>
			<aside class="latest__side">
				<?php if ( $more ) : ?>
				<div class="side-module">
					<h3 class="side-module__title"><?php esc_html_e( 'More recent', 'hoverboards-guide' ); ?></h3>
					<div class="side-module__list"><?php foreach ( $more as $p ) { hbg_row( $p, array( 'label' => false ) ); } ?></div>
				</div>
				<?php endif; ?>
				<?php if ( ! empty( $popular['posts'] ) ) : ?>
				<div class="side-module">
					<h3 class="side-module__title"><?php echo esc_html( $popular['title'] ); ?></h3>
					<div class="side-module__list"><?php foreach ( $popular['posts'] as $i => $p ) { hbg_row( $p, array( 'thumb' => false, 'number' => $i + 1, 'label' => false ) ); } ?></div>
				</div>
				<?php endif; ?>
			</aside>
		</div>
	</div>
</section>
