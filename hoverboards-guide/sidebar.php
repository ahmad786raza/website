<?php
/**
 * Sidebar: curated set (search, topics, recommendations, most read) or the Sidebar widget area.
 */
?>
<aside class="sidebar" id="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'hoverboards-guide' ); ?>">
	<div class="sidebar__sticky">
		<?php if ( 'widgets' === get_theme_mod( 'hbg_sidebar_mode', 'curated' ) && is_active_sidebar( 'sidebar-1' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		<?php else : ?>
			<?php
			$hbg_side_toc = array();
			if ( is_singular( 'post' ) && ! empty( $GLOBALS['hbg_toc_items'] ) ) {
				foreach ( $GLOBALS['hbg_toc_items'] as $hbg_it ) {
					if ( 2 === (int) $hbg_it['level'] ) {
						$hbg_side_toc[] = $hbg_it;
					}
				}
			}
			if ( count( $hbg_side_toc ) >= 3 ) :
			?>
			<section class="widget widget_hbg_sidetoc" data-side-toc>
				<h3 class="widget-title"><span><?php esc_html_e( 'On this page', 'hoverboards-guide' ); ?></span></h3>
				<ol class="side-toc">
					<?php foreach ( $hbg_side_toc as $hbg_it ) : ?><li><a href="#<?php echo esc_attr( $hbg_it['id'] ); ?>"><?php echo esc_html( $hbg_it['text'] ); ?></a></li><?php endforeach; ?>
				</ol>
			</section>
			<?php endif; ?>
			<section class="widget widget_search"><h3 class="widget-title"><span><?php esc_html_e( 'Search', 'hoverboards-guide' ); ?></span></h3><?php get_search_form(); ?></section>
			<section class="widget widget_hbg_topics">
				<h3 class="widget-title"><span><?php esc_html_e( 'Hoverboards by topic', 'hoverboards-guide' ); ?></span></h3>
				<ul class="topic-links">
					<?php foreach ( hbg_topic_hubs() as $slug => $hub ) : $l = hbg_hub_link( $slug ); if ( $l['url'] ) : ?>
						<li><a href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $hub['name'] ); ?></a><?php if ( $l['count'] ) : ?> <span class="count"><?php echo esc_html( number_format_i18n( $l['count'] ) ); ?></span><?php endif; ?></li>
					<?php endif; endforeach; ?>
				</ul>
			</section>
			<section class="widget widget_hbg_top_picks">
				<h3 class="widget-title"><span><?php esc_html_e( 'Our picks', 'hoverboards-guide' ); ?></span></h3>
				<div class="mini-picks">
					<?php foreach ( hbg_get_top_picks( 3 ) as $p ) : $review = hbg_product_review_url( $p ); ?>
						<div class="mini-pick">
							<?php hbg_product_image( $p, array( 'class' => 'product-image mini-pick__img', 'ratio' => '1/1', 'note' => false, 'sizes' => '72px' ) ); ?>
							<div class="mini-pick__body">
								<span class="mini-pick__badge"><?php echo esc_html( $p['badge'] ); ?></span>
								<strong class="mini-pick__name"><?php if ( $review ) : ?><a href="<?php echo esc_url( $review ); ?>"><?php echo esc_html( $p['name'] ); ?></a><?php else : ?><?php echo esc_html( $p['name'] ); ?><?php endif; ?></strong>
								<a href="<?php echo hbg_amazon_url( $p['search'] ); // phpcs:ignore ?>" class="mini-pick__link" target="_blank" rel="sponsored nofollow noopener" data-hbg-track="affiliate" data-product="<?php echo esc_attr( $p['id'] ); ?>" data-placement="sidebar"><?php esc_html_e( 'Check price on Amazon', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'external', 12 ); // phpcs:ignore ?></a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<p class="mini-picks__note"><?php esc_html_e( 'Affiliate links. Specs as advertised by manufacturers.', 'hoverboards-guide' ); ?></p>
			</section>
			<section class="widget widget_hbg_popular">
				<h3 class="widget-title"><span><?php esc_html_e( 'Most read', 'hoverboards-guide' ); ?></span></h3>
				<?php hbg_render_post_list( hbg_popular_posts( 5, is_singular() ? array( get_the_ID() ) : array() ) ); ?>
			</section>
		<?php endif; ?>
	</div>
</aside>
