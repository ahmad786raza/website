<?php
/**
 * Fabrication Project Tracker (also serves as the Fabrication Route Planner).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="bdh-section bdh-tool bdh-tracker-section" id="bdh-tracker" data-bdh-section="route_planner">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Tool 8 of 8', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Fabrication Project Tracker', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Track active builds. Monitor material usage, save bend settings, and follow project progress — all in your browser.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-tracker-shell" data-bdh-tracker>
			<form class="bdh-tracker-add" data-bdh-tracker-form>
				<div class="bdh-tracker-grid">
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Project name', 'benders-hub' ); ?></span>
						<input type="text" name="name" required placeholder="<?php esc_attr_e( 'e.g., 240SX roll cage', 'benders-hub' ); ?>">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Material', 'benders-hub' ); ?></span>
						<input type="text" name="material" placeholder="<?php esc_attr_e( '1.75 x .095 DOM', 'benders-hub' ); ?>">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Total feet', 'benders-hub' ); ?></span>
						<input type="number" name="feet" step="0.1" value="0">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Progress (%)', 'benders-hub' ); ?></span>
						<input type="number" name="progress" min="0" max="100" value="0">
					</label>
				</div>
				<button class="bdh-btn bdh-btn-primary" type="submit"><?php esc_html_e( 'Add Project', 'benders-hub' ); ?></button>
			</form>

			<div class="bdh-tracker-board">
				<div class="bdh-tracker-list" data-bdh-tracker-list>
					<p class="bdh-tracker-empty"><?php esc_html_e( 'No active projects yet. Add your first build to start tracking.', 'benders-hub' ); ?></p>
				</div>
			</div>

			<div class="bdh-tracker-charts">
				<div class="bdh-tracker-chart-card">
					<h4><?php esc_html_e( 'Material usage', 'benders-hub' ); ?></h4>
					<div class="bdh-tracker-bars" data-bdh-tracker-bars></div>
				</div>
				<div class="bdh-tracker-chart-card">
					<h4><?php esc_html_e( 'Project completion', 'benders-hub' ); ?></h4>
					<div class="bdh-tracker-bars" data-bdh-tracker-progressbars></div>
				</div>
			</div>

			<div class="bdh-tracker-cta">
				<button class="bdh-btn bdh-btn-ghost" data-bdh-tracker-export><?php esc_html_e( 'Export Projects', 'benders-hub' ); ?></button>
				<button class="bdh-btn bdh-btn-ghost" data-bdh-tracker-clear><?php esc_html_e( 'Clear All', 'benders-hub' ); ?></button>
			</div>
		</div>
	</div>
</section>
