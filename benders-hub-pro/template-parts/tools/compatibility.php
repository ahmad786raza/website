<?php
/**
 * Tool Compatibility Generator.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$in_console = isset( $args['context'] ) && 'console' === $args['context'];
?>
<section class="bdh-section bdh-tool bdh-compat-section" id="bdh-compatibility" data-bdh-section="compatibility">
	<div class="<?php echo $in_console ? 'bdh-tool-inner' : 'bdh-container'; ?>">
		<header class="bdh-tool-head">
			<h2 class="bdh-tool-title"><?php esc_html_e( 'Tooling checklist', 'benders-hub' ); ?></h2>
			<p class="bdh-tool-sub"><?php esc_html_e( 'Pick a tube size, material and job to list the dies and support tooling to confirm with your bender\'s manufacturer.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-compat-shell" data-bdh-compat>
			<form class="bdh-compat-form" data-bdh-compat-form>
				<div class="bdh-compat-grid">
					<label class="bdh-field">
						<span class="bdh-field-label"><?php esc_html_e( 'Tube OD', 'benders-hub' ); ?></span>
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
							<option value="aluminum">Aluminium 6061</option>
							<option value="copper">Copper / soft line</option>
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
				<button class="bdh-btn bdh-btn-primary" type="submit">
					<?php esc_html_e( 'Build my checklist', 'benders-hub' ); ?>
				</button>
			</form>

			<div class="bdh-compat-result" data-bdh-compat-result hidden>
				<h3><?php esc_html_e( 'Tooling to confirm', 'benders-hub' ); ?></h3>
				<div class="bdh-compat-list" data-bdh-compat-list></div>
				<div class="bdh-compat-warnings" data-bdh-compat-warnings></div>
				<a class="bdh-btn bdh-btn-primary" data-bdh-compat-amazon href="#" rel="nofollow sponsored noopener" target="_blank">
					<?php esc_html_e( 'See dies on Amazon', 'benders-hub' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
