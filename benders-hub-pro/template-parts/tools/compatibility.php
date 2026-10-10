<?php
/**
 * Tool Compatibility Generator.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="bdh-section bdh-tool bdh-compat-section" id="bdh-compatibility" data-bdh-section="compatibility">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Tool 6 of 8', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Tool Compatibility Generator', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Tell us what you\'re bending — we generate the die set, mandrel ball, lubricant, and tooling list.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-compat-shell" data-bdh-compat>
			<form class="bdh-compat-form" data-bdh-compat-form>
				<div class="bdh-compat-grid">
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Tubing size', 'benders-hub' ); ?></span>
						<select name="size">
							<option value="0.5">1/2"</option>
							<option value="0.75">3/4"</option>
							<option value="1">1"</option>
							<option value="1.25">1-1/4"</option>
							<option value="1.5">1-1/2"</option>
							<option value="1.75" selected>1-3/4"</option>
							<option value="2">2"</option>
							<option value="2.5">2-1/2"</option>
							<option value="3">3"</option>
							<option value="4">4"</option>
						</select>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Material', 'benders-hub' ); ?></span>
						<select name="material">
							<option value="mild">Mild Steel / DOM</option>
							<option value="chromoly">Chromoly 4130</option>
							<option value="stainless">T304 Stainless</option>
							<option value="aluminum">Aluminum 6061</option>
							<option value="copper">Copper</option>
						</select>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Fabrication type', 'benders-hub' ); ?></span>
						<select name="type">
							<option value="exhaust">Exhaust / Headers</option>
							<option value="rollcage">Roll Cage / Chassis</option>
							<option value="pipe">Pipe / Structural</option>
							<option value="art">Ornamental / Art</option>
							<option value="prod">Production / OEM</option>
						</select>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Goal', 'benders-hub' ); ?></span>
						<select name="goal">
							<option value="precision">Maximum precision</option>
							<option value="speed">Speed / throughput</option>
							<option value="cost">Lowest cost</option>
							<option value="finish">Best finish / no wrinkle</option>
						</select>
					</label>
				</div>
				<button class="bdh-btn bdh-btn-primary bdh-btn-lg" type="submit">
					<?php esc_html_e( 'Generate Compatible Tooling', 'benders-hub' ); ?>
				</button>
			</form>

			<div class="bdh-compat-result" data-bdh-compat-result hidden>
				<h3><?php esc_html_e( 'Compatible Tooling List', 'benders-hub' ); ?></h3>
				<div class="bdh-compat-list" data-bdh-compat-list></div>
				<div class="bdh-compat-warnings" data-bdh-compat-warnings></div>
				<a class="bdh-btn bdh-btn-primary" data-bdh-compat-amazon href="#" rel="nofollow sponsored noopener" target="_blank">
					<?php esc_html_e( 'Find Tooling on Amazon', 'benders-hub' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
