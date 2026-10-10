<?php
/**
 * Newsletter CTA.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = bdh_option( 'newsletter_title', __( 'Get the Workshop Brief', 'benders-hub' ) );
$sub   = bdh_option( 'newsletter_sub',   __( 'Bender reviews, fabrication guides, and shop tips every Friday — no spam.', 'benders-hub' ) );
$cta   = bdh_option( 'newsletter_cta',   __( 'Join 12,000+ Fabricators', 'benders-hub' ) );
?>
<section class="bdh-section bdh-newsletter" id="bdh-newsletter" data-bdh-section="newsletter">
	<div class="bdh-container">
		<div class="bdh-newsletter-card">
			<div class="bdh-newsletter-graphic" aria-hidden="true">
				<?php echo bdh_icon( 'flame', 'bdh-newsletter-icon' ); ?>
			</div>
			<div class="bdh-newsletter-body">
				<h2 class="bdh-newsletter-title"><?php echo esc_html( $title ); ?></h2>
				<p class="bdh-newsletter-sub"><?php echo esc_html( $sub ); ?></p>
				<form class="bdh-newsletter-form" data-bdh-newsletter novalidate>
					<label class="bdh-screen-reader-text" for="bdh-newsletter-email"><?php esc_html_e( 'Email address', 'benders-hub' ); ?></label>
					<input type="email" id="bdh-newsletter-email" name="email" placeholder="<?php esc_attr_e( 'you@workshop.com', 'benders-hub' ); ?>" required autocomplete="email">
					<button type="submit" class="bdh-btn bdh-btn-amber bdh-btn-lg">
						<?php echo esc_html( $cta ); ?>
					</button>
					<div class="bdh-newsletter-message" data-bdh-newsletter-msg role="status" aria-live="polite"></div>
				</form>
				<small class="bdh-newsletter-disclaimer"><?php esc_html_e( 'Unsubscribe anytime. We never share your email.', 'benders-hub' ); ?></small>
			</div>
		</div>
	</div>
</section>
