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
			<section class="widget widget_search"><h3 class="widget-title"><span><?php esc_html_e( 'Search', 'hoverboards-guide' ); ?></span></h3><?php get_search_form(); ?></section>
			<?php $hbg_guide = hbg_key_url( 'buying_guide' ); if ( $hbg_guide ) : ?>
			<section class="widget widget_hbg_start">
				<h3 class="widget-title"><span><?php esc_html_e( 'Start here', 'hoverboards-guide' ); ?></span></h3>
				<ul class="topic-links">
					<li><a href="<?php echo esc_url( $hbg_guide ); ?>"><?php esc_html_e( 'Hoverboard buying guide', 'hoverboards-guide' ); ?></a></li>
					<?php if ( hbg_key_url( 'best' ) ) : ?><li><a href="<?php echo esc_url( hbg_key_url( 'best' ) ); ?>"><?php esc_html_e( 'Best hoverboards: our roundup', 'hoverboards-guide' ); ?></a></li><?php endif; ?>
					<li><a href="<?php echo esc_url( home_url( '/#compare' ) ); ?>"><?php esc_html_e( 'Compare specifications', 'hoverboards-guide' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#finder' ) ); ?>"><?php esc_html_e( 'Hoverboard finder', 'hoverboards-guide' ); ?></a></li>
				</ul>
			</section>
			<?php endif; ?>
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
			<?php $hbg_pop = hbg_popular_module( 5 ); if ( ! empty( $hbg_pop['posts'] ) ) : ?>
			<section class="widget widget_hbg_popular">
				<h3 class="widget-title"><span><?php echo esc_html( $hbg_pop['title'] ); ?></span></h3>
				<div class="side-module__list"><?php foreach ( $hbg_pop['posts'] as $hbg_i => $hbg_p ) { hbg_row( $hbg_p, array( 'thumb' => false, 'number' => $hbg_i + 1, 'label' => false ) ); } ?></div>
			</section>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</aside>
