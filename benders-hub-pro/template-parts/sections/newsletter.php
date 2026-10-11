<?php
/**
 * Newsletter CTA. Sign-ups are stored privately in WordPress (Benders Hub →
 * Subscribers) and optionally forwarded to the endpoint set in the Customizer.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title   = bdh_option( 'newsletter_title', __( 'The workshop brief', 'benders-hub' ) );
$sub     = bdh_option( 'newsletter_sub', __( 'New guides, calculator updates and equipment explainers by email. Occasional, useful, never sold on.', 'benders-hub' ) );
$privacy = get_privacy_policy_url();
?>
<section class="bdh-section bdh-newsletter" id="bdh-newsletter" aria-labelledby="bdh-newsletter-title">
	<div class="bdh-container">
		<div class="bdh-newsletter-card">
			<div class="bdh-newsletter-copy">
				<span class="bdh-newsletter-icon"><?php echo bdh_icon( 'mail' ); ?></span>
				<h2 class="bdh-section-title" id="bdh-newsletter-title"><?php echo esc_html( $title ); ?></h2>
				<p><?php echo esc_html( $sub ); ?></p>
			</div>
			<div class="bdh-newsletter-action">
				<?php bdh_newsletter_form( 'home' ); ?>
				<p class="bdh-newsletter-fine">
					<?php esc_html_e( 'Unsubscribe any time.', 'benders-hub' ); ?>
					<?php if ( $privacy ) : ?><a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'Privacy policy', 'benders-hub' ); ?></a><?php endif; ?>
				</p>
			</div>
		</div>
	</div>
</section>
