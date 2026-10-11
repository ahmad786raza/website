<?php
$about     = get_theme_mod( 'hbg_footer_about', __( 'Buying guides, reviews and safety advice for hoverboards and other small electric rides. Research-based, updated as models change.', 'hoverboards-guide' ) );
$email     = get_theme_mod( 'hbg_contact_email', '' );
$hubs      = hbg_topic_hubs();
$legal     = array(
	'about'                => __( 'About', 'hoverboards-guide' ),
	'contact'              => __( 'Contact', 'hoverboards-guide' ),
	'affiliate-disclosure' => __( 'Affiliate disclosure', 'hoverboards-guide' ),
	'editorial-policy'     => __( 'Editorial policy', 'hoverboards-guide' ),
	'privacy-policy'       => __( 'Privacy policy', 'hoverboards-guide' ),
	'terms'                => __( 'Terms', 'hoverboards-guide' ),
);
?>

<footer class="site-footer">
	<div class="container">

		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<div class="footer__widgets"><?php dynamic_sidebar( 'footer-1' ); ?></div>
		<?php endif; ?>

		<div class="footer__grid">
			<div class="footer__col footer__col--brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__brand">
					<span class="brand__mark" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="16" r="3"/><circle cx="18" cy="16" r="3"/><path d="M6 13h12l-2-5H8z"/></svg></span>
					<span class="footer__brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				</a>
				<p class="footer__about"><?php echo esc_html( $about ); ?></p>
				<?php if ( $email ) : ?><p class="footer__contact"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p><?php endif; ?>
				<?php hbg_social_icons( 'social social--footer' ); ?>
			</div>

			<div class="footer__col">
				<h2 class="footer__title"><?php esc_html_e( 'Hoverboards', 'hoverboards-guide' ); ?></h2>
				<ul class="footer__links">
					<?php if ( hbg_key_url( 'best' ) ) : ?><li><a href="<?php echo esc_url( hbg_key_url( 'best' ) ); ?>"><?php esc_html_e( 'Best hoverboards', 'hoverboards-guide' ); ?></a></li><?php endif; ?>
					<?php foreach ( array( 'hoverboards-for-kids', 'hoverboards-for-adults', 'all-terrain-hoverboards', 'budget-hoverboards', 'bluetooth-hoverboards', 'hoverboard-reviews' ) as $slug ) : $l = hbg_hub_link( $slug ); if ( $l['url'] ) : ?>
						<li><a href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $hubs[ $slug ]['name'] ); ?></a></li>
					<?php endif; endforeach; ?>
				</ul>
			</div>

			<div class="footer__col">
				<h2 class="footer__title"><?php esc_html_e( 'Guides', 'hoverboards-guide' ); ?></h2>
				<ul class="footer__links">
					<?php if ( hbg_key_url( 'buying_guide' ) ) : ?><li><a href="<?php echo esc_url( hbg_key_url( 'buying_guide' ) ); ?>"><?php esc_html_e( 'Hoverboard buying guide', 'hoverboards-guide' ); ?></a></li><?php endif; ?>
					<?php foreach ( array( 'hoverboard-safety', 'hoverboard-troubleshooting' ) as $slug ) : $l = hbg_hub_link( $slug ); if ( $l['url'] ) : ?>
						<li><a href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $hubs[ $slug ]['name'] ); ?></a></li>
					<?php endif; endforeach; ?>
					<li><a href="<?php echo esc_url( home_url( '/#finder' ) ); ?>"><?php esc_html_e( 'Hoverboard finder', 'hoverboards-guide' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#compare' ) ); ?>"><?php esc_html_e( 'Compare specifications', 'hoverboards-guide' ); ?></a></li>
					<?php foreach ( hbg_more_rides() as $ride ) : $t = get_term_by( 'slug', $ride['slug'], 'category' ); if ( $t && $t->count ) : ?>
						<li><a href="<?php echo esc_url( get_category_link( $t ) ); ?>"><?php echo esc_html( $ride['label'] ); ?></a></li>
					<?php endif; endforeach; ?>
				</ul>
			</div>

			<div class="footer__col">
				<h2 class="footer__title"><?php esc_html_e( 'About this site', 'hoverboards-guide' ); ?></h2>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer__links', 'depth' => 1, 'fallback_cb' => false ) ); ?>
				<?php else : ?>
					<ul class="footer__links">
						<?php
						foreach ( $legal as $slug => $label ) {
							$url = hbg_page_url( $slug );
							if ( ! $url && 'privacy-policy' === $slug ) {
								$url = get_privacy_policy_url();
							}
							if ( $url ) {
								echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
							}
						}
						?>
					</ul>
				<?php endif; ?>
			</div>
		</div>

		<div class="footer__bottom">
			<p class="footer__disclosure"><strong><?php esc_html_e( 'Affiliate disclosure:', 'hoverboards-guide' ); ?></strong> <?php echo esc_html( hbg_disclosure_text() ); ?> <?php esc_html_e( 'Amazon and the Amazon logo are trademarks of Amazon.com, Inc. or its affiliates; this site is not endorsed by Amazon.', 'hoverboards-guide' ); ?></p>
			<p class="footer__copy">&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'hoverboards-guide' ); ?></p>
		</div>

	</div>
</footer>

</div><!-- .site -->
<button type="button" class="back-top" aria-label="<?php esc_attr_e( 'Back to top', 'hoverboards-guide' ); ?>"><?php echo hbg_svg_icon( 'arrow-up', 18 ); // phpcs:ignore ?></button>

<?php wp_footer(); ?>
</body>
</html>
