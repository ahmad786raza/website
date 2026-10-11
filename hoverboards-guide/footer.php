<?php
$disclosure = get_theme_mod( 'hbg_affiliate_disclosure', __( 'As an Amazon Associate, Hoverboards Guide earns from qualifying purchases. Prices and availability are accurate as of the date/time indicated and are subject to change.', 'hoverboards-guide' ) );
$about      = get_theme_mod( 'hbg_footer_about', __( 'Independent reviews of hoverboards, electric scooters and e-bikes. Written by riders, updated weekly, never sponsored.', 'hoverboards-guide' ) );
$foot_cats  = get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 7 ) );
$popular    = hbg_popular_posts( 5 );
?>

<footer class="site-footer" role="contentinfo">
	<div class="container">

		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<div class="footer__widgets"><?php dynamic_sidebar( 'footer-1' ); ?></div>
		<?php endif; ?>

		<div class="footer__grid">

			<div class="footer__col footer__col--brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__brand">
					<span class="brand__icon" aria-hidden="true">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="M5 13l3-7h7l3 7"/></svg>
					</span>
					<span class="footer__brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				</a>
				<p class="footer__about"><?php echo esc_html( $about ); ?></p>
				<?php hbg_social_icons( 'social social--footer' ); ?>
			</div>

			<div class="footer__col">
				<h3 class="footer__title"><?php esc_html_e( 'Explore', 'hoverboards-guide' ); ?></h3>
				<ul class="footer__links">
					<?php foreach ( $foot_cats as $c ) : ?>
						<li><a href="<?php echo esc_url( get_category_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?> <span class="footer__count"><?php echo esc_html( number_format_i18n( $c->count ) ); ?></span></a></li>
					<?php endforeach; ?>
					<li><a href="<?php echo esc_url( home_url( '/#picks' ) ); ?>"><?php esc_html_e( 'Editor’s top picks', 'hoverboards-guide' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#compare' ) ); ?>"><?php esc_html_e( 'Compare rides', 'hoverboards-guide' ); ?></a></li>
				</ul>
			</div>

			<div class="footer__col">
				<h3 class="footer__title"><?php esc_html_e( 'Most read', 'hoverboards-guide' ); ?></h3>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer__links', 'depth' => 1, 'fallback_cb' => false ) ); ?>
				<?php elseif ( $popular->have_posts() ) : ?>
					<ul class="footer__links footer__links--posts">
						<?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
							<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php endwhile; wp_reset_postdata(); ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="footer__col footer__col--newsletter">
				<?php hbg_newsletter_form( array( 'variant' => 'footer', 'source' => 'footer' ) ); ?>
			</div>

		</div><!-- .footer__grid -->

		<div class="footer__bottom">
			<div class="footer__disclosure">
				<strong><?php esc_html_e( 'Affiliate disclosure:', 'hoverboards-guide' ); ?></strong>
				<?php echo esc_html( $disclosure ); ?>
			</div>
			<ul class="footer__legal">
				<?php
				$legal = array(
					'about'          => __( 'About', 'hoverboards-guide' ),
					'contact'        => __( 'Contact', 'hoverboards-guide' ),
					'privacy-policy' => __( 'Privacy', 'hoverboards-guide' ),
					'terms'          => __( 'Terms', 'hoverboards-guide' ),
				);
				foreach ( $legal as $slug => $label ) {
					$page = get_page_by_path( $slug );
					if ( $page ) {
						echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $label ) . '</a></li>';
					} elseif ( 'privacy-policy' === $slug && get_privacy_policy_url() ) {
						echo '<li><a href="' . esc_url( get_privacy_policy_url() ) . '">' . esc_html( $label ) . '</a></li>';
					}
				}
				?>
			</ul>
		</div>

		<p class="footer__copy">
			<?php
			printf(
				/* translators: %1$s = year, %2$s = site name, %3$s = tagline */
				esc_html__( '© %1$s %2$s · %3$s', 'hoverboards-guide' ),
				esc_html( date_i18n( 'Y' ) ),
				esc_html( get_bloginfo( 'name' ) ),
				esc_html( get_theme_mod( 'hbg_footer_tagline', __( 'Made for riders, not advertisers', 'hoverboards-guide' ) ) )
			);
			?>
		</p>

	</div>
</footer>

</div><!-- .site -->

<button type="button" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'hoverboards-guide' ); ?>"><?php echo hbg_svg_icon( 'arrow-up', 18 ); // phpcs:ignore ?></button>

<?php wp_footer(); ?>
</body>
</html>
