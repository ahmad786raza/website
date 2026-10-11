<?php
/**
 * Sidebar: the theme's curated set by default; switch to the "Sidebar" widget area
 * under Customize → Hoverboards Guide → Single Post → Sidebar content.
 */
?>
<aside class="sidebar" id="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'hoverboards-guide' ); ?>">
	<div class="sidebar__sticky">
		<?php if ( 'widgets' === get_theme_mod( 'hbg_sidebar_mode', 'curated' ) && is_active_sidebar( 'sidebar-1' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		<?php else : ?>

			<section class="widget widget_search">
				<h3 class="widget-title"><span><?php esc_html_e( 'Search', 'hoverboards-guide' ); ?></span></h3>
				<?php get_search_form(); ?>
			</section>

			<section class="widget widget_hbg_popular">
				<h3 class="widget-title"><span><?php esc_html_e( 'Most read', 'hoverboards-guide' ); ?></span></h3>
				<?php hbg_render_post_list( hbg_popular_posts( 5, is_singular() ? array( get_the_ID() ) : array() ) ); ?>
			</section>

			<section class="widget widget_hbg_newsletter">
				<?php hbg_newsletter_form( array( 'variant' => 'box', 'source' => 'sidebar' ) ); ?>
			</section>

			<section class="widget widget_hbg_top_picks">
				<h3 class="widget-title"><span><?php esc_html_e( 'Editor’s top picks', 'hoverboards-guide' ); ?></span></h3>
				<div class="mini-picks">
					<?php foreach ( hbg_get_top_picks() as $p ) : ?>
						<div class="mini-pick">
							<?php hbg_product_image( $p, array( 'class' => 'product-image mini-pick__img', 'ratio' => '1/1', 'note' => false, 'sizes' => '72px' ) ); ?>
							<div class="mini-pick__body">
								<span class="mini-pick__badge"><?php echo esc_html( $p['badge'] ); ?></span>
								<strong class="mini-pick__name"><?php echo esc_html( $p['name'] ); ?></strong>
								<a href="<?php echo esc_url( hbg_amazon_url( $p['search'] ) ); ?>" class="mini-pick__link" target="_blank" rel="sponsored nofollow noopener"><?php esc_html_e( 'Check price', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 12 ); // phpcs:ignore ?></a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>

			<section class="widget widget_categories">
				<h3 class="widget-title"><span><?php esc_html_e( 'Browse by topic', 'hoverboards-guide' ); ?></span></h3>
				<ul>
					<?php
					foreach ( get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 10 ) ) as $c ) {
						$preset = hbg_category_preset( $c );
						echo '<li style="--cat:' . esc_attr( $preset['color'] ) . '"><a href="' . esc_url( get_category_link( $c ) ) . '">' . esc_html( $c->name ) . '</a> <span class="count">' . esc_html( number_format_i18n( $c->count ) ) . '</span></li>';
					}
					?>
				</ul>
			</section>

		<?php endif; ?>
	</div>
</aside>
