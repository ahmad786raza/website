<?php
/**
 * Workshop Efficiency Prioritizer.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$priorities = array(
	'precision'    => __( 'Precision', 'benders-hub' ),
	'speed'        => __( 'Speed', 'benders-hub' ),
	'automation'   => __( 'Automation', 'benders-hub' ),
	'portability'  => __( 'Portability', 'benders-hub' ),
	'safety'       => __( 'Safety', 'benders-hub' ),
	'cost'         => __( 'Cost efficiency', 'benders-hub' ),
	'flexibility'  => __( 'Fabrication flexibility', 'benders-hub' ),
	'output'       => __( 'Industrial output', 'benders-hub' ),
);
?>
<section class="bdh-section bdh-tool bdh-prioritizer-section" id="bdh-prioritizer" data-bdh-section="prioritizer">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Tool 7 of 8', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Workshop Efficiency Prioritizer', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Rank what matters in your shop. We build a fabrication profile and recommend a setup tuned to your priorities.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-prio-shell" data-bdh-prio>
			<div class="bdh-prio-grid">
			<?php foreach ( $priorities as $key => $label ) : ?>
				<div class="bdh-prio-row" data-bdh-prio-key="<?php echo esc_attr( $key ); ?>">
					<div class="bdh-prio-row-head">
						<span class="bdh-prio-label"><?php echo esc_html( $label ); ?></span>
						<output class="bdh-prio-value" data-bdh-prio-value="<?php echo esc_attr( $key ); ?>">3</output>
					</div>
					<input type="range" min="1" max="5" step="1" value="3" data-bdh-prio-slider="<?php echo esc_attr( $key ); ?>">
				</div>
			<?php endforeach; ?>
			</div>
			<div class="bdh-prio-actions">
				<button class="bdh-btn bdh-btn-primary bdh-btn-lg" data-bdh-prio-build>
					<?php esc_html_e( 'Build My Workshop Profile', 'benders-hub' ); ?>
				</button>
				<button class="bdh-btn bdh-btn-ghost" data-bdh-prio-reset><?php esc_html_e( 'Reset', 'benders-hub' ); ?></button>
			</div>

			<div class="bdh-prio-result" data-bdh-prio-result hidden>
				<h3><?php esc_html_e( 'Your Fabrication Profile', 'benders-hub' ); ?></h3>
				<p data-bdh-prio-summary></p>
				<div class="bdh-prio-recommend" data-bdh-prio-recommend></div>
				<a class="bdh-btn bdh-btn-primary" data-bdh-prio-amazon href="#" rel="nofollow sponsored noopener" target="_blank">
					<?php esc_html_e( 'See Recommended Setup on Amazon', 'benders-hub' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
