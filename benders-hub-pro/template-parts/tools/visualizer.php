<?php
/**
 * Tube geometry visualizer — draws a single bend to scale.
 * v1 drew the tube at a fixed 28px/inch so large CLRs fell off the canvas and
 * tube width was not proportional to CLR; it also printed invented "ID restriction"
 * percentages. v2 auto-fits the drawing and only shows computed geometry.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$in_console = isset( $args['context'] ) && 'console' === $args['context'];
$controls = array(
	'od'    => array( __( 'Tube OD', 'benders-hub' ),    0.25, 6,   0.05,  1.75, 'in' ),
	'wall'  => array( __( 'Wall', 'benders-hub' ),       0.02, 0.5, 0.005, 0.12, 'in' ),
	'clr'   => array( __( 'CLR', 'benders-hub' ),        0.5,  24,  0.125, 6,    'in' ),
	'angle' => array( __( 'Bend angle', 'benders-hub' ), 1,    180, 1,     90,   '°' ),
);
?>
<section class="bdh-section bdh-tool bdh-visualizer-section" id="bdh-visualizer" aria-labelledby="bdh-viz-title">
	<div class="<?php echo $in_console ? 'bdh-tool-inner' : 'bdh-container'; ?>">
		<header class="bdh-tool-head">
			<h2 class="bdh-tool-title" id="bdh-viz-title"><?php esc_html_e( 'Tube geometry visualizer', 'benders-hub' ); ?></h2>
			<p class="bdh-tool-sub"><?php esc_html_e( 'A single bend drawn to scale. Change the diameter, wall, radius and angle to see how the geometry and outer-wall stretch change.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-visualizer-grid" data-bdh-visualizer>
			<div class="bdh-visualizer-controls">
				<?php foreach ( $controls as $key => $c ) : ?>
					<div class="bdh-field bdh-range">
						<label class="bdh-field-label" for="bdh-vz-<?php echo esc_attr( $key ); ?>-n"><?php echo esc_html( $c[0] ); ?> <span class="bdh-unit">(<?php echo esc_html( $c[5] ); ?>)</span></label>
						<div class="bdh-range-row">
							<input type="range" id="bdh-vz-<?php echo esc_attr( $key ); ?>" data-bdh-vz="<?php echo esc_attr( $key ); ?>" min="<?php echo esc_attr( $c[1] ); ?>" max="<?php echo esc_attr( $c[2] ); ?>" step="<?php echo esc_attr( $c[3] ); ?>" value="<?php echo esc_attr( $c[4] ); ?>" aria-label="<?php echo esc_attr( $c[0] ); ?>">
							<input type="number" id="bdh-vz-<?php echo esc_attr( $key ); ?>-n" data-bdh-vz-num="<?php echo esc_attr( $key ); ?>" min="<?php echo esc_attr( $c[1] ); ?>" max="<?php echo esc_attr( $c[2] ); ?>" step="any" value="<?php echo esc_attr( $c[4] ); ?>" inputmode="decimal">
						</div>
					</div>
				<?php endforeach; ?>
				<p class="bdh-field-help"><?php esc_html_e( 'Dark band = tube wall, light band = bore. Dashed line = centreline.', 'benders-hub' ); ?></p>
			</div>

			<div class="bdh-visualizer-stage">
				<figure class="bdh-visualizer-canvas">
					<svg data-bdh-vz-svg viewBox="0 0 100 100" role="img" aria-labelledby="bdh-vz-desc" xmlns="http://www.w3.org/2000/svg">
						<title id="bdh-vz-desc" data-bdh-vz-desc><?php esc_html_e( 'Bend diagram', 'benders-hub' ); ?></title>
						<g data-bdh-vz-layer></g>
					</svg>
				</figure>
				<dl class="bdh-result-grid bdh-result-grid--compact">
					<div class="bdh-result-tile" data-tile="d"><dt><?php esc_html_e( 'D of bend', 'benders-hub' ); ?></dt><dd data-vz-out="d">—</dd></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Wall factor', 'benders-hub' ); ?></dt><dd data-vz-out="wf">—</dd></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Arc length', 'benders-hub' ); ?></dt><dd data-vz-out="arc">—</dd></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Tangent length', 'benders-hub' ); ?></dt><dd data-vz-out="t">—</dd></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Inside radius', 'benders-hub' ); ?></dt><dd data-vz-out="ri">—</dd></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Outer-wall stretch', 'benders-hub' ); ?></dt><dd data-vz-out="stretch">—</dd></div>
				</dl>
				<div class="bdh-vz-warnings" data-bdh-vz-warnings aria-live="polite"></div>
			</div>
		</div>
	</div>
</section>
