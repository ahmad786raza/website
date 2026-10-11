<?php
/**
 * Footer
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
	</main><!-- #bdh-main -->

	<footer id="bdh-colophon" class="bdh-footer">
		<div class="bdh-container">
			<div class="bdh-footer-grid">
				<div class="bdh-footer-brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bdh-logo bdh-logo--footer">
						<?php get_template_part( 'template-parts/logo-mark' ); ?>
						<span class="bdh-logo-text"><span class="bdh-logo-word"><?php bloginfo( 'name' ); ?></span></span>
					</a>
					<p class="bdh-footer-tagline">
						<?php echo esc_html( bdh_option( 'footer_tagline', __( 'An independent publication about tube bending and metal fabrication: buying guides, technical explainers and free planning tools for home builders and professional shops.', 'benders-hub' ) ) ); ?>
					</p>
				</div>

				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<div class="bdh-footer-col bdh-footer-col-<?php echo (int) $i; ?>">
						<?php
						if ( is_active_sidebar( 'footer-' . $i ) ) {
							dynamic_sidebar( 'footer-' . $i );
						} else {
							bdh_fallback_footer_column( $i );
						}
						?>
					</div>
				<?php endfor; ?>
			</div>

			<div class="bdh-footer-disclaimer">
				<p><strong><?php esc_html_e( 'Affiliate disclosure:', 'benders-hub' ); ?></strong> <?php echo esc_html( BDH_AMAZON_DISCLOSURE ); ?> <?php esc_html_e( 'Amazon and the Amazon logo are trademarks of Amazon.com, Inc. or its affiliates; Benders Hub is not affiliated with or endorsed by Amazon.', 'benders-hub' ); ?></p>
				<p><strong><?php esc_html_e( 'Safety:', 'benders-hub' ); ?></strong> <?php esc_html_e( 'Calculator results are planning estimates. Follow your equipment manufacturer\'s instructions and, for roll cages and other safety structures, the current rulebook of your sanctioning body.', 'benders-hub' ); ?></p>
			</div>

			<div class="bdh-footer-bottom">
				<small>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></small>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<nav class="bdh-footer-nav" aria-label="<?php esc_attr_e( 'Footer', 'benders-hub' ); ?>">
						<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'bdh-footer-menu', 'depth' => 1 ) ); ?>
					</nav>
				<?php endif; ?>
			</div>
		</div>
	</footer>

</div><!-- #bdh-page -->

<?php wp_footer(); ?>
</body>
</html>
