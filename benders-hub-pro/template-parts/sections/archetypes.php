<?php
/**
 * Fabrication Archetype Explorer.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = bdh_option( 'archetypes_title', __( 'Which fabricator are you?', 'benders-hub' ) );
$sub   = bdh_option( 'archetypes_sub',   __( 'Pick an archetype — we tune the entire site to match your shop.', 'benders-hub' ) );
$archetypes = bdh_archetypes();
?>
<section class="bdh-section bdh-archetypes" id="bdh-archetypes" data-bdh-section="archetypes">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Personalize', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="bdh-section-sub"><?php echo esc_html( $sub ); ?></p>
		</header>

		<div class="bdh-archetype-grid" data-bdh-archetype-grid>
		<?php foreach ( $archetypes as $a ) : ?>
			<article class="bdh-archetype-card" data-archetype="<?php echo esc_attr( $a['id'] ); ?>" tabindex="0" role="button" aria-pressed="false">
				<div class="bdh-archetype-card-header">
					<span class="bdh-archetype-icon"><?php echo bdh_icon( $a['icon'] ); ?></span>
					<h3 class="bdh-archetype-name"><?php echo esc_html( $a['name'] ); ?></h3>
				</div>
				<p class="bdh-archetype-tagline"><?php echo esc_html( $a['tagline'] ); ?></p>
				<dl class="bdh-archetype-specs">
					<div><dt><?php esc_html_e( 'Diameter', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $a['diameter'] ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Wall', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $a['wall'] ); ?></dd></div>
					<div><dt><?php esc_html_e( 'CLR', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $a['radius'] ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Material', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $a['material'] ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Workshop', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $a['workshop'] ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Precision', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $a['precision'] ); ?></dd></div>
				</dl>
				<div class="bdh-archetype-card-actions">
					<button type="button" class="bdh-btn bdh-btn-sm bdh-btn-amber" data-bdh-archetype-pick="<?php echo esc_attr( $a['id'] ); ?>">
						<?php esc_html_e( 'Tune Site to This', 'benders-hub' ); ?>
					</button>
					<a class="bdh-btn bdh-btn-sm bdh-btn-ghost" href="<?php echo esc_url( bdh_amazon_url( $a['keyword'] ) ); ?>" rel="nofollow sponsored noopener" target="_blank">
						<?php echo esc_html( $a['cta'] ); ?>
					</a>
				</div>
			</article>
		<?php endforeach; ?>
		</div>
	</div>
</section>
