<?php
/**
 * Fabrication Workflow Planner.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$in_console = isset( $args['context'] ) && 'console' === $args['context'];
?>
<section class="bdh-section bdh-tool bdh-workflow-section" id="bdh-workflow" data-bdh-section="workflow_planner">
	<div class="<?php echo $in_console ? 'bdh-tool-inner' : 'bdh-container'; ?>">
		<header class="bdh-tool-head">
			<h2 class="bdh-tool-title"><?php esc_html_e( 'Bend sequence planner', 'benders-hub' ); ?></h2>
			<p class="bdh-tool-sub"><?php esc_html_e( 'Write down each bend in order with its angle, die and notes. Saved in your browser; export or print it for the shop floor.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-workflow-shell" data-bdh-workflow>
			<div class="bdh-workflow-grid">
				<aside class="bdh-workflow-add">
					<h3><?php esc_html_e( 'Add a bend step', 'benders-hub' ); ?></h3>
					<form data-bdh-workflow-form>
						<label class="bdh-field">
							<span class="bdh-field-label"><?php esc_html_e( 'Step label', 'benders-hub' ); ?></span>
							<input type="text" name="label" required placeholder="<?php esc_attr_e( 'e.g., Front cross-member', 'benders-hub' ); ?>">
						</label>
						<label class="bdh-field">
							<span class="bdh-field-label"><?php esc_html_e( 'Bend angle (°)', 'benders-hub' ); ?></span>
							<input type="number" name="angle" min="5" max="180" value="45">
						</label>
						<label class="bdh-field">
							<span class="bdh-field-label"><?php esc_html_e( 'CLR (in)', 'benders-hub' ); ?></span>
							<input type="number" name="clr" step="0.1" value="6.0">
						</label>
						<label class="bdh-field">
							<span class="bdh-field-label"><?php esc_html_e( 'Die / tooling', 'benders-hub' ); ?></span>
							<input type="text" name="tooling" placeholder="<?php esc_attr_e( '1.75 OD CLR6 mandrel die', 'benders-hub' ); ?>">
						</label>
						<label class="bdh-field">
							<span class="bdh-field-label"><?php esc_html_e( 'Safety note', 'benders-hub' ); ?></span>
							<input type="text" name="safety" placeholder="<?php esc_attr_e( 'Verify clamp before bend', 'benders-hub' ); ?>">
						</label>
						<button class="bdh-btn bdh-btn-primary bdh-btn-block" type="submit"><?php esc_html_e( 'Add step', 'benders-hub' ); ?></button>
					</form>
				</aside>

				<div class="bdh-workflow-board">
					<header class="bdh-workflow-board-head">
						<h3 data-bdh-workflow-title contenteditable="true" spellcheck="false"><?php esc_html_e( 'My Fabrication Workflow', 'benders-hub' ); ?></h3>
						<div class="bdh-workflow-actions">
							<button class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-bdh-workflow-save><?php esc_html_e( 'Save', 'benders-hub' ); ?></button>
							<button class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-bdh-workflow-export><?php esc_html_e( 'Export', 'benders-hub' ); ?></button>
							<button class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-bdh-workflow-print onclick="window.print()"><?php esc_html_e( 'Print', 'benders-hub' ); ?></button>
							<button class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-bdh-workflow-clear><?php esc_html_e( 'Clear', 'benders-hub' ); ?></button>
						</div>
					</header>
					<ol class="bdh-workflow-list" data-bdh-workflow-list>
						<li class="bdh-workflow-empty" data-bdh-workflow-empty><?php esc_html_e( 'No steps yet. Add your first bend step using the form on the left.', 'benders-hub' ); ?></li>
					</ol>
					<div class="bdh-workflow-status" data-bdh-workflow-status role="status" aria-live="polite"></div>
				</div>
			</div>
		</div>
	</div>
</section>
