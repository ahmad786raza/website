<?php
/**
 * Tube Geometry Visualizer.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="bdh-section bdh-tool bdh-visualizer-section" id="bdh-visualizer" data-bdh-section="geometry_visualizer">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Tool 3 of 8', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Tube Geometry Visualizer', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Live engineering preview of your bend. Adjust OD, wall, CLR, and angle — watch stress, springback, and ID restriction update in real time.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-visualizer-shell" data-bdh-visualizer>
			<div class="bdh-visualizer-grid">
				<div class="bdh-visualizer-controls">
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'OD (in)', 'benders-hub' ); ?> <output data-bdh-vz-out="od">1.75</output></span>
						<input type="range" data-bdh-vz="od" min="0.25" max="6" step="0.05" value="1.75">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Wall (in)', 'benders-hub' ); ?> <output data-bdh-vz-out="wall">0.095</output></span>
						<input type="range" data-bdh-vz="wall" min="0.020" max="0.250" step="0.005" value="0.095">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'CLR (in)', 'benders-hub' ); ?> <output data-bdh-vz-out="clr">6.0</output></span>
						<input type="range" data-bdh-vz="clr" min="1" max="24" step="0.25" value="6">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Bend Angle', 'benders-hub' ); ?> <output data-bdh-vz-out="angle">90</output>°</span>
						<input type="range" data-bdh-vz="angle" min="5" max="180" step="1" value="90">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Material', 'benders-hub' ); ?></span>
						<select data-bdh-vz="material">
							<option value="mild">Mild Steel</option>
							<option value="chromoly">Chromoly 4130</option>
							<option value="stainless">T304 Stainless</option>
							<option value="aluminum">Aluminum 6061</option>
						</select>
					</label>
				</div>

				<div class="bdh-visualizer-stage">
					<div class="bdh-visualizer-canvas" data-bdh-vz-stage>
						<svg viewBox="0 0 600 400" data-bdh-vz-svg xmlns="http://www.w3.org/2000/svg">
							<defs>
								<linearGradient id="bdh-vz-tube" x1="0" x2="0" y1="0" y2="1">
									<stop offset="0" stop-color="#374151"/>
									<stop offset="0.5" stop-color="#9ca3af"/>
									<stop offset="1" stop-color="#1f2937"/>
								</linearGradient>
							</defs>
							<g data-bdh-vz-grid></g>
							<path data-bdh-vz-tubepath fill="none" stroke="url(#bdh-vz-tube)" stroke-width="20" stroke-linecap="round"/>
							<path data-bdh-vz-centerline fill="none" stroke="#ef4444" stroke-width="1" stroke-dasharray="4 4" opacity="0.7"/>
							<path data-bdh-vz-radiusline fill="none" stroke="#f59e0b" stroke-width="1" opacity="0.6"/>
							<circle data-bdh-vz-centerdot r="4" fill="#f59e0b"/>
							<text data-bdh-vz-anglelabel fill="#f59e0b" font-family="monospace" font-size="14"></text>
							<text data-bdh-vz-clrlabel fill="#ef4444" font-family="monospace" font-size="12"></text>
						</svg>
					</div>
					<div class="bdh-visualizer-readout">
						<div class="bdh-vz-tile">
							<small><?php esc_html_e( 'Stress', 'benders-hub' ); ?></small>
							<strong data-bdh-vz-stress>—</strong>
						</div>
						<div class="bdh-vz-tile">
							<small><?php esc_html_e( 'Springback', 'benders-hub' ); ?></small>
							<strong data-bdh-vz-sb>—</strong>
						</div>
						<div class="bdh-vz-tile">
							<small><?php esc_html_e( 'ID Restriction', 'benders-hub' ); ?></small>
							<strong data-bdh-vz-id>—</strong>
						</div>
						<div class="bdh-vz-tile">
							<small><?php esc_html_e( 'Wrinkle', 'benders-hub' ); ?></small>
							<strong data-bdh-vz-wr>—</strong>
						</div>
					</div>
					<div class="bdh-vz-warnings" data-bdh-vz-warnings></div>
				</div>
			</div>
		</div>
	</div>
</section>
