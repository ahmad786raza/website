<?php
/**
 * Bend Radius Calculator.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="bdh-section bdh-tool bdh-calc-section" id="bdh-calculator" data-bdh-section="bend_calculator">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Tool 2 of 8', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Bend Radius Calculator', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Inputs: tubing OD, wall, material, bend angle, CLR. Outputs: ideal radius, wrinkle risk, springback, tooling. Results hidden until submit.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-calc-shell" data-bdh-calc>
			<form class="bdh-calc-form" data-bdh-calc-form>
				<div class="bdh-calc-grid">
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Tubing OD (inches)', 'benders-hub' ); ?></span>
						<input type="number" name="od" step="0.001" min="0.125" max="12" value="1.75" required>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Wall thickness (inches)', 'benders-hub' ); ?></span>
						<input type="number" name="wall" step="0.001" min="0.020" max="0.500" value="0.095" required>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Material', 'benders-hub' ); ?></span>
						<select name="material" required>
							<option value="mild">Mild Steel / DOM</option>
							<option value="chromoly">Chromoly 4130</option>
							<option value="stainless">T304 Stainless</option>
							<option value="stainless_t321">T321 Stainless</option>
							<option value="aluminum">Aluminum 6061</option>
							<option value="copper">Copper</option>
						</select>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Bend angle (°)', 'benders-hub' ); ?></span>
						<input type="number" name="angle" step="1" min="5" max="180" value="90" required>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'CLR target (inches)', 'benders-hub' ); ?></span>
						<input type="number" name="clr" step="0.01" min="0.5" max="48" value="6.0" required>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Process', 'benders-hub' ); ?></span>
						<select name="process">
							<option value="rotary">Rotary Draw</option>
							<option value="mandrel">Mandrel</option>
							<option value="press">Press / Ram</option>
							<option value="roll">Roll Bending</option>
						</select>
					</label>
				</div>
				<div class="bdh-calc-actions">
					<button type="submit" class="bdh-btn bdh-btn-primary bdh-btn-lg">
						<?php echo bdh_icon( 'calculator' ); ?>
						<?php esc_html_e( 'Calculate Bend', 'benders-hub' ); ?>
					</button>
					<button type="reset" class="bdh-btn bdh-btn-ghost"><?php esc_html_e( 'Reset', 'benders-hub' ); ?></button>
				</div>
			</form>

			<div class="bdh-calc-result" data-bdh-calc-result hidden aria-live="polite">
				<div class="bdh-calc-result-header">
					<h3><?php esc_html_e( 'Bend Analysis', 'benders-hub' ); ?></h3>
					<span class="bdh-calc-result-stamp" data-bdh-calc-time></span>
				</div>
				<div class="bdh-calc-result-grid">
					<div class="bdh-calc-result-tile">
						<small><?php esc_html_e( 'Ideal CLR', 'benders-hub' ); ?></small>
						<strong data-bdh-calc-clr></strong>
						<em data-bdh-calc-clr-note></em>
					</div>
					<div class="bdh-calc-result-tile">
						<small><?php esc_html_e( 'Wrinkle Risk', 'benders-hub' ); ?></small>
						<strong data-bdh-calc-wrinkle></strong>
						<em data-bdh-calc-wrinkle-note></em>
					</div>
					<div class="bdh-calc-result-tile">
						<small><?php esc_html_e( 'Springback', 'benders-hub' ); ?></small>
						<strong data-bdh-calc-springback></strong>
						<em data-bdh-calc-springback-note></em>
					</div>
					<div class="bdh-calc-result-tile">
						<small><?php esc_html_e( 'Bend Allowance', 'benders-hub' ); ?></small>
						<strong data-bdh-calc-allowance></strong>
						<em data-bdh-calc-allowance-note></em>
					</div>
					<div class="bdh-calc-result-tile">
						<small><?php esc_html_e( 'D/T Ratio', 'benders-hub' ); ?></small>
						<strong data-bdh-calc-dt></strong>
						<em data-bdh-calc-dt-note></em>
					</div>
					<div class="bdh-calc-result-tile">
						<small><?php esc_html_e( 'Cutting Length', 'benders-hub' ); ?></small>
						<strong data-bdh-calc-cut></strong>
						<em data-bdh-calc-cut-note></em>
					</div>
				</div>

				<div class="bdh-calc-result-summary">
					<h4><?php esc_html_e( 'Tooling Recommendation', 'benders-hub' ); ?></h4>
					<p data-bdh-calc-tooling></p>
				</div>

				<div class="bdh-calc-result-cta">
					<a class="bdh-btn bdh-btn-primary" data-bdh-calc-amazon href="#" rel="nofollow sponsored noopener" target="_blank">
						<?php esc_html_e( 'Find Matching Tooling on Amazon', 'benders-hub' ); ?>
					</a>
					<button class="bdh-btn bdh-btn-ghost" data-bdh-calc-print onclick="window.print()">
						<?php esc_html_e( 'Print / Export', 'benders-hub' ); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</section>
