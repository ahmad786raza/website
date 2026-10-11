<?php
/**
 * Bend calculator.
 *
 * v1 problems fixed here:
 *  - "Bend allowance" used the sheet-metal K-factor formula; tube CLR is already
 *    measured to the centreline, so arc length is simply CLR × θ.
 *  - "Cutting length" added an arbitrary 4" ("mock") allowance.
 *  - "Wrinkle risk %" and springback degrees were invented coefficients.
 * v2 computes standard geometry exactly, classifies difficulty from D-of-bend
 * and wall factor with clearly labelled rules of thumb, and derives springback
 * from the reader's own test bend.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$in_console = isset( $args['context'] ) && 'console' === $args['context'];
?>
<section class="bdh-section bdh-tool bdh-calc-section" id="bdh-calculator" aria-labelledby="bdh-calc-title">
	<div class="<?php echo $in_console ? 'bdh-tool-inner' : 'bdh-container'; ?>">
		<header class="bdh-tool-head">
			<h2 class="bdh-tool-title" id="bdh-calc-title"><?php esc_html_e( 'Bend calculator', 'benders-hub' ); ?></h2>
			<p class="bdh-tool-sub"><?php esc_html_e( 'Arc length, tangent length, cut length, D-of-bend and wall factor for a single bend between two straight legs. Inches or millimetres.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-calc-shell" data-bdh-calc>
			<form class="bdh-calc-form" data-bdh-calc-form novalidate>
				<fieldset class="bdh-units" data-bdh-units>
					<legend class="bdh-field-label"><?php esc_html_e( 'Units', 'benders-hub' ); ?></legend>
					<label><input type="radio" name="units" value="in" checked> <?php esc_html_e( 'inches', 'benders-hub' ); ?></label>
					<label><input type="radio" name="units" value="mm"> <?php esc_html_e( 'mm', 'benders-hub' ); ?></label>
				</fieldset>
				<div class="bdh-calc-grid">
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Tube OD', 'benders-hub' ); ?> <abbr data-bdh-unit>in</abbr></span>
						<input type="number" name="od" inputmode="decimal" step="any" min="0" value="1.75" required aria-describedby="bdh-calc-od-help">
						<small id="bdh-calc-od-help" class="bdh-field-help"><?php esc_html_e( 'Actual outside diameter (tube), not nominal pipe size.', 'benders-hub' ); ?></small>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Wall thickness', 'benders-hub' ); ?> <abbr data-bdh-unit>in</abbr></span>
						<input type="number" name="wall" inputmode="decimal" step="any" min="0" value="0.120" required>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Die CLR', 'benders-hub' ); ?> <abbr data-bdh-unit>in</abbr></span>
						<input type="number" name="clr" inputmode="decimal" step="any" min="0" value="6" required aria-describedby="bdh-calc-clr-help">
						<small id="bdh-calc-clr-help" class="bdh-field-help"><?php esc_html_e( 'Centerline radius stamped on your die.', 'benders-hub' ); ?></small>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Bend angle', 'benders-hub' ); ?> <abbr title="<?php esc_attr_e( 'degrees', 'benders-hub' ); ?>">°</abbr></span>
						<input type="number" name="angle" inputmode="decimal" step="any" min="1" max="180" value="90" required>
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Leg A', 'benders-hub' ); ?> <abbr data-bdh-unit>in</abbr></span>
						<input type="number" name="legA" inputmode="decimal" step="any" min="0" value="24" aria-describedby="bdh-calc-leg-help">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Leg B', 'benders-hub' ); ?> <abbr data-bdh-unit>in</abbr></span>
						<input type="number" name="legB" inputmode="decimal" step="any" min="0" value="18">
					</label>
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Trim allowance', 'benders-hub' ); ?> <abbr data-bdh-unit>in</abbr></span>
						<input type="number" name="trim" inputmode="decimal" step="any" min="0" value="0.25">
					</label>
				</div>
				<p id="bdh-calc-leg-help" class="bdh-field-help"><?php esc_html_e( 'Legs are measured from each tube end to the point where the two straight centrelines would intersect (the "mold line" dimensions on most drawings).', 'benders-hub' ); ?></p>
				<p class="bdh-form-error" data-bdh-calc-error role="alert" hidden></p>
				<div class="bdh-calc-actions">
					<button type="submit" class="bdh-btn bdh-btn-primary"><?php echo bdh_icon( 'calculator' ); ?><span><?php esc_html_e( 'Calculate', 'benders-hub' ); ?></span></button>
					<button type="reset" class="bdh-btn bdh-btn-ghost"><?php esc_html_e( 'Reset', 'benders-hub' ); ?></button>
				</div>
			</form>

			<div class="bdh-calc-result" data-bdh-calc-result hidden aria-live="polite">
				<h3 class="bdh-result-title"><?php esc_html_e( 'Results', 'benders-hub' ); ?></h3>
				<dl class="bdh-result-grid">
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Arc length (in the bend)', 'benders-hub' ); ?></dt><dd data-out="arc"></dd><small>CLR × θ<sub>rad</sub></small></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Tangent length', 'benders-hub' ); ?></dt><dd data-out="tangent"></dd><small>CLR × tan(θ/2)</small></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Cut length', 'benders-hub' ); ?></dt><dd data-out="cut"></dd><small data-out="cut-note"></small></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Straight before bend', 'benders-hub' ); ?></dt><dd data-out="startMark"></dd><small><?php esc_html_e( 'Mark from end A to the bend start', 'benders-hub' ); ?></small></div>
					<div class="bdh-result-tile" data-tile="d"><dt><?php esc_html_e( 'D of bend', 'benders-hub' ); ?></dt><dd data-out="d"></dd><small>CLR ÷ OD</small></div>
					<div class="bdh-result-tile" data-tile="wf"><dt><?php esc_html_e( 'Wall factor', 'benders-hub' ); ?></dt><dd data-out="wf"></dd><small>OD ÷ wall</small></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Outside / inside radius', 'benders-hub' ); ?></dt><dd data-out="radii"></dd><small>CLR ± OD/2</small></div>
					<div class="bdh-result-tile"><dt><?php esc_html_e( 'Outer-wall stretch (theoretical)', 'benders-hub' ); ?></dt><dd data-out="stretch"></dd><small><?php esc_html_e( 'OD ÷ (2 × CLR); ignores neutral-axis shift', 'benders-hub' ); ?></small></div>
				</dl>
				<div class="bdh-assessment" data-bdh-calc-assessment></div>
				<p class="bdh-tool-note"><?php esc_html_e( 'Geometry is exact for the inputs. Real bends also stretch or "gain" slightly depending on machine and die, so calibrate with a test piece before cutting expensive tube.', 'benders-hub' ); ?></p>
				<div class="bdh-calc-result-cta">
					<a class="bdh-btn bdh-btn-outline bdh-btn-sm" href="<?php echo esc_url( bdh_tool_url( 'visualizer' ) ); ?>" data-bdh-calc-viz><?php esc_html_e( 'See it to scale', 'benders-hub' ); ?></a>
					<button type="button" class="bdh-btn bdh-btn-ghost bdh-btn-sm" data-bdh-print><?php esc_html_e( 'Print', 'benders-hub' ); ?></button>
				</div>
			</div>
		</div>

		<div class="bdh-tool-shell bdh-springback" data-bdh-springback>
			<h3 class="bdh-tool-subtitle"><?php esc_html_e( 'Springback from your test bend', 'benders-hub' ); ?></h3>
			<p class="bdh-tool-sub"><?php esc_html_e( 'There is no reliable universal springback table for tube — it changes with material batch, wall, radius and machine. Bend a scrap piece, measure it, and let the numbers tell you how far to over-bend.', 'benders-hub' ); ?></p>
			<form class="bdh-calc-grid bdh-calc-grid--3" data-bdh-sb-form novalidate>
				<label class="bdh-field"><span class="bdh-field-label"><?php esc_html_e( 'Bent to (machine reading) °', 'benders-hub' ); ?></span><input type="number" name="bent" inputmode="decimal" step="any" min="1" max="200" value="90"></label>
				<label class="bdh-field"><span class="bdh-field-label"><?php esc_html_e( 'Measured after release °', 'benders-hub' ); ?></span><input type="number" name="measured" inputmode="decimal" step="any" min="1" max="200" value="86.5"></label>
				<label class="bdh-field"><span class="bdh-field-label"><?php esc_html_e( 'Target angle °', 'benders-hub' ); ?></span><input type="number" name="target" inputmode="decimal" step="any" min="1" max="180" value="45"></label>
				<div class="bdh-calc-actions"><button type="submit" class="bdh-btn bdh-btn-primary bdh-btn-sm"><?php esc_html_e( 'Work out over-bend', 'benders-hub' ); ?></button></div>
			</form>
			<p class="bdh-sb-out" data-bdh-sb-out aria-live="polite"></p>
		</div>
	</div>
</section>
