<?php
/**
 * Footer
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
	</main><!-- #bdh-main -->

	<footer id="bdh-colophon" class="bdh-footer" role="contentinfo">
		<div class="bdh-footer-top">
			<div class="bdh-container">
				<div class="bdh-footer-grid">
					<div class="bdh-footer-brand">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bdh-footer-logo">
							<?php echo bdh_icon( 'tube', 'bdh-footer-logo-icon' ); ?>
							<span><?php bloginfo( 'name' ); ?></span>
						</a>
						<p class="bdh-footer-tagline">
							<?php
							$tag = bdh_option( 'footer_tagline', __( 'Premium fabrication engineering ecosystem. Tube benders, hydraulic systems, CNC bending, exhaust fabrication, and workshop optimization — researched, tested, and reviewed by working fabricators.', 'benders-hub' ) );
							echo esc_html( $tag );
							?>
						</p>
						<div class="bdh-footer-badges">
							<?php
							echo bdh_trust_badge( 'reviewed' );
							echo bdh_trust_badge( 'tested' );
							echo bdh_trust_badge( 'updated' );
							?>
						</div>
					</div>

					<div class="bdh-footer-cols">
						<?php for ( $i = 1; $i <= 4; $i++ ) :
							if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
								<div class="bdh-footer-col bdh-footer-col-<?php echo (int) $i; ?>">
									<?php dynamic_sidebar( 'footer-' . $i ); ?>
								</div>
							<?php else : ?>
								<div class="bdh-footer-col bdh-footer-col-<?php echo (int) $i; ?>">
									<?php bdh_fallback_footer_column( $i ); ?>
								</div>
							<?php endif;
						endfor; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="bdh-footer-bottom">
			<div class="bdh-container bdh-footer-bottom-inner">
				<div class="bdh-footer-legal">
					<small>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'benders-hub' ); ?></small>
				</div>
				<nav class="bdh-footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'benders-hub' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'bdh-footer-menu',
						'depth'          => 1,
						'fallback_cb'    => 'bdh_footer_menu_fallback',
					) );
					?>
				</nav>
			</div>
			<div class="bdh-container">
				<p class="bdh-footer-disclaimer">
					<?php esc_html_e( 'Affiliate Disclosure: As an Amazon Associate, Benders Hub earns from qualifying purchases. Product prices and availability are accurate as of date/time indicated and are subject to change. Always follow manufacturer safety guidelines and verify specifications before fabrication.', 'benders-hub' ); ?>
				</p>
			</div>
		</div>
	</footer>

	<div class="bdh-mobile-stickycta" id="bdh-mobile-sticky">
		<a href="#bdh-quiz" class="bdh-btn bdh-btn-primary bdh-btn-block">
			<?php echo bdh_icon( 'tube' ); ?>
			<?php esc_html_e( 'Find My Tube Bender', 'benders-hub' ); ?>
		</a>
	</div>

</div><!-- #bdh-page -->

<?php wp_footer(); ?>
</body>
</html>
