<?php
/**
 * Cinematic Fabrication Hero
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = bdh_option( 'hero_eyebrow', __( 'Premium Fabrication Engineering Hub', 'benders-hub' ) );
$headline= bdh_option( 'hero_headline', __( 'Find the Best Tube Benders & Fabrication Tools for Precision Projects.', 'benders-hub' ) );
$sub     = bdh_option( 'hero_sub',      __( 'Build professional fabrication setups for exhausts, roll cages, hydraulic shops, CNC bending, and industrial workflows — researched, tested, and reviewed by working fabricators.', 'benders-hub' ) );
$cta1    = bdh_option( 'hero_cta_primary',     __( 'Find My Tube Bender', 'benders-hub' ) );
$cta1u   = bdh_option( 'hero_cta_primary_url', '#bdh-quiz' );
$cta2    = bdh_option( 'hero_cta_secondary',   __( 'Open Bend Calculator', 'benders-hub' ) );
$cta2u   = bdh_option( 'hero_cta_secondary_url','#bdh-calculator' );
$image   = bdh_option( 'hero_image' );
$video   = bdh_option( 'hero_video' );
?>
<section class="bdh-hero" id="bdh-hero" data-bdh-section="hero">
	<div class="bdh-hero-bg" aria-hidden="true">
		<?php if ( $video ) : ?>
			<video class="bdh-hero-video" autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url( $image ); ?>">
				<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
			</video>
		<?php elseif ( $image ) : ?>
			<img class="bdh-hero-img" src="<?php echo esc_url( $image ); ?>" alt="" loading="eager" fetchpriority="high">
		<?php else : ?>
			<div class="bdh-hero-svg-bg">
				<?php echo bdh_hero_blueprint_svg(); ?>
			</div>
		<?php endif; ?>
		<div class="bdh-hero-overlay"></div>
		<div class="bdh-hero-sparks">
			<span class="bdh-spark"></span><span class="bdh-spark"></span><span class="bdh-spark"></span>
			<span class="bdh-spark"></span><span class="bdh-spark"></span><span class="bdh-spark"></span>
		</div>
	</div>

	<div class="bdh-container bdh-hero-inner">
		<div class="bdh-hero-content">
			<div class="bdh-hero-eyebrow">
				<span class="bdh-hero-dot"></span>
				<span><?php echo esc_html( $eyebrow ); ?></span>
			</div>
			<h1 class="bdh-hero-headline"><?php echo wp_kses_post( $headline ); ?></h1>
			<p class="bdh-hero-sub"><?php echo esc_html( $sub ); ?></p>

			<div class="bdh-hero-cta-row">
				<a href="<?php echo esc_url( $cta1u ); ?>" class="bdh-btn bdh-btn-primary bdh-btn-lg">
					<?php echo bdh_icon( 'tube' ); ?>
					<span><?php echo esc_html( $cta1 ); ?></span>
				</a>
				<a href="<?php echo esc_url( $cta2u ); ?>" class="bdh-btn bdh-btn-ghost bdh-btn-lg">
					<?php echo bdh_icon( 'calculator' ); ?>
					<span><?php echo esc_html( $cta2 ); ?></span>
				</a>
			</div>

			<div class="bdh-hero-trust-row">
				<?php
				echo bdh_trust_badge( 'reviewed' );
				echo bdh_trust_badge( 'tested' );
				echo bdh_trust_badge( 'updated' );
				?>
				<small class="bdh-hero-disclosure">
					<?php esc_html_e( 'Affiliate disclosure: Benders Hub earns from Amazon-qualifying purchases. We only recommend fabrication tools we would use ourselves.', 'benders-hub' ); ?>
				</small>
			</div>

			<div class="bdh-hero-quickstart">
				<span class="bdh-hero-quickstart-label"><?php esc_html_e( 'Quick start:', 'benders-hub' ); ?></span>
				<div class="bdh-hero-quickstart-chips">
				<?php foreach ( bdh_quick_start_paths() as $path ) : ?>
					<a class="bdh-chip" href="<?php echo esc_url( bdh_amazon_url( $path['keyword'] ) ); ?>" rel="nofollow sponsored noopener" target="_blank">
						<?php echo bdh_icon( $path['icon'] ); ?>
						<span><?php echo esc_html( $path['label'] ); ?></span>
					</a>
				<?php endforeach; ?>
				</div>
			</div>
		</div>

		<div class="bdh-hero-side" aria-hidden="false">
			<div class="bdh-hero-spec-panel" data-bdh-panel="spec" data-bdh-hero-spec>
				<div class="bdh-hero-spec-header">
					<span class="bdh-hero-spec-icon"><?php echo bdh_icon( 'gear' ); ?></span>
					<div>
						<small><?php esc_html_e( 'Live Spec Console', 'benders-hub' ); ?></small>
						<strong data-bdh-spec-title><?php esc_html_e( 'Bend preview', 'benders-hub' ); ?></strong>
					</div>
					<span class="bdh-hero-spec-status" data-bdh-spec-status><?php esc_html_e( 'READY', 'benders-hub' ); ?></span>
				</div>
				<dl class="bdh-hero-spec-list">
					<div><dt><?php esc_html_e( 'Tubing OD', 'benders-hub' ); ?></dt><dd data-bdh-spec="od">1.750"</dd></div>
					<div><dt><?php esc_html_e( 'Wall', 'benders-hub' ); ?></dt><dd data-bdh-spec="wall">0.095"</dd></div>
					<div><dt><?php esc_html_e( 'CLR', 'benders-hub' ); ?></dt><dd data-bdh-spec="clr">6.000"</dd></div>
					<div><dt><?php esc_html_e( 'Bend Angle', 'benders-hub' ); ?></dt><dd data-bdh-spec="angle">90°</dd></div>
					<div><dt><?php esc_html_e( 'Material', 'benders-hub' ); ?></dt><dd data-bdh-spec="material">Chromoly 4130</dd></div>
					<div><dt><?php esc_html_e( 'Springback', 'benders-hub' ); ?></dt><dd data-bdh-spec="springback">+1.4°</dd></div>
				</dl>
				<div class="bdh-hero-spec-visual">
					<?php echo bdh_hero_bend_svg(); ?>
				</div>
				<a href="#bdh-visualizer" class="bdh-btn bdh-btn-sm bdh-btn-amber bdh-btn-block" data-bdh-spec-cta>
					<?php esc_html_e( 'Open Geometry Visualizer', 'benders-hub' ); ?>
				</a>
			</div>
		</div>
	</div>

	<div class="bdh-hero-blueprint-grid" aria-hidden="true"></div>
</section>
