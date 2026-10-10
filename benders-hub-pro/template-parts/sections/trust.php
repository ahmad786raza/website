<?php
/**
 * Editorial Trust Section.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = bdh_option( 'trust_title', __( 'How we test and review fabrication equipment', 'benders-hub' ) );
$sub   = bdh_option( 'trust_sub',   __( 'Every bender, die, and accessory is reviewed by working fabricators against documented standards. No paid placements.', 'benders-hub' ) );

$pillars = array(
	array(
		'icon'  => 'shield',
		'title' => __( 'Reviewed by Fabricators', 'benders-hub' ),
		'body'  => __( 'Every recommendation is reviewed by working fabricators — exhaust, chassis, and industrial pipe specialists.', 'benders-hub' ),
	),
	array(
		'icon'  => 'gear',
		'title' => __( 'Workshop-Tested Methodology', 'benders-hub' ),
		'body'  => __( 'We bend test coupons, measure springback, and document precision before publishing any review.', 'benders-hub' ),
	),
	array(
		'icon'  => 'flame',
		'title' => __( 'Safety-First Standards', 'benders-hub' ),
		'body'  => __( 'Our recommendations follow OSHA-aligned safety practices for fabrication and welding integration.', 'benders-hub' ),
	),
	array(
		'icon'  => 'check',
		'title' => __( 'Transparent Affiliate Policy', 'benders-hub' ),
		'body'  => __( 'We disclose every affiliate relationship. Editorial decisions are independent of commissions.', 'benders-hub' ),
	),
);
?>
<section class="bdh-section bdh-trust" id="bdh-trust" data-bdh-section="trust">
	<div class="bdh-container">
		<header class="bdh-section-head">
			<span class="bdh-section-tag"><?php esc_html_e( 'EEAT & methodology', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="bdh-section-sub"><?php echo esc_html( $sub ); ?></p>
		</header>

		<div class="bdh-trust-grid">
		<?php foreach ( $pillars as $p ) : ?>
			<article class="bdh-trust-card">
				<span class="bdh-trust-icon"><?php echo bdh_icon( $p['icon'] ); ?></span>
				<h3 class="bdh-trust-title"><?php echo esc_html( $p['title'] ); ?></h3>
				<p class="bdh-trust-body"><?php echo esc_html( $p['body'] ); ?></p>
			</article>
		<?php endforeach; ?>
		</div>

		<div class="bdh-trust-links">
			<a href="<?php echo esc_url( home_url( '/methodology/' ) ); ?>" class="bdh-btn bdh-btn-ghost"><?php esc_html_e( 'Read Our Testing Methodology', 'benders-hub' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/editorial-standards/' ) ); ?>" class="bdh-btn bdh-btn-ghost"><?php esc_html_e( 'Editorial Standards', 'benders-hub' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/affiliate-disclosure/' ) ); ?>" class="bdh-btn bdh-btn-ghost"><?php esc_html_e( 'Affiliate Disclosure', 'benders-hub' ); ?></a>
		</div>
	</div>
</section>
