<?php
/**
 * Homepage Section F — latest articles with progressive "load more".
 * Without JavaScript the button is a normal link to the article archive.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$shown = isset( $GLOBALS['bdh_shown_ids'] ) ? array_map( 'intval', $GLOBALS['bdh_shown_ids'] ) : array();
$per   = 6;
$q     = new WP_Query( array(
	'post_type'           => 'post',
	'posts_per_page'      => $per,
	'post__not_in'        => $shown,
	'ignore_sticky_posts' => true,
) );
if ( ! $q->have_posts() ) { return; }
$archive = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : '';
if ( ! $archive ) {
	$top = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 1 ) );
	$archive = $top ? get_category_link( $top[0] ) : home_url( '/' );
}
?>
<section class="bdh-section bdh-latest" id="bdh-latest" aria-labelledby="bdh-latest-title">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head--split">
			<div>
				<p class="bdh-eyebrow"><?php esc_html_e( 'From the workshop', 'benders-hub' ); ?></p>
				<h2 class="bdh-section-title" id="bdh-latest-title"><?php esc_html_e( 'Latest technical articles', 'benders-hub' ); ?></h2>
			</div>
		</header>
		<div class="bdh-card-grid" data-bdh-feed>
			<?php while ( $q->have_posts() ) : $q->the_post(); bdh_post_card( get_the_ID(), 'card', 'h3' ); endwhile; wp_reset_postdata(); ?>
		</div>
		<?php if ( $q->found_posts > $per ) : ?>
			<div class="bdh-feed-more">
				<a class="bdh-btn bdh-btn-outline" href="<?php echo esc_url( $archive ); ?>" data-bdh-load-more data-page="1" data-per="<?php echo (int) $per; ?>" data-exclude="<?php echo esc_attr( implode( ',', $shown ) ); ?>" data-total="<?php echo (int) $q->found_posts; ?>">
					<?php esc_html_e( 'Load more articles', 'benders-hub' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
