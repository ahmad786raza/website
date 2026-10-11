<?php
/**
 * Comparison tables.
 * 1) Bender types compared on what actually differs between them.
 * 2) Published specifications of popular benders, each linked to its source.
 * No scores, star ratings or invented specifications.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$in_console = isset( $args['context'] ) && 'console' === $args['context'];

$types = array(
	array( __( 'Lever hand bender', 'benders-hub' ),   __( 'Brake, fuel and instrument lines', 'benders-hub' ), __( 'Small OD, thin wall', 'benders-hub' ), __( 'Good within its rating', 'benders-hub' ), __( 'Angle marks on the form', 'benders-hub' ), __( 'None; portable', 'benders-hub' ), __( 'Lowest', 'benders-hub' ) ),
	array( __( 'Manual rotary-draw', 'benders-hub' ),  __( 'Cages, chassis, bumpers, frames', 'benders-hub' ), __( 'Up to about 2"–2.5" OD depending on wall', 'benders-hub' ), __( 'Good at about 3× OD CLR and up; thin wall at tighter radii needs a mandrel', 'benders-hub' ), __( 'Good with a degree ring or stops', 'benders-hub' ), __( 'Solid floor mount; no power', 'benders-hub' ), __( 'Moderate, plus dies', 'benders-hub' ) ),
	array( __( 'Hydraulic rotary-draw', 'benders-hub' ), __( 'Heavy wall, frequent bending', 'benders-hub' ), __( 'Similar OD range; heavier walls', 'benders-hub' ), __( 'As manual rotary-draw', 'benders-hub' ), __( 'Good to very good with stops', 'benders-hub' ), __( 'Hand, air or electric pump', 'benders-hub' ), __( 'Moderate to high', 'benders-hub' ) ),
	array( __( 'Hydraulic ram ("pipe")', 'benders-hub' ), __( 'Schedule pipe for gates and rails', 'benders-hub' ), __( 'About 1/2"–2" NPS schedule 40/80', 'benders-hub' ), __( 'Poor — tends to flatten or kink thin wall', 'benders-hub' ), __( 'Low', 'benders-hub' ), __( 'Hand pump; portable', 'benders-hub' ), __( 'Low', 'benders-hub' ) ),
	array( __( 'Mandrel bender', 'benders-hub' ),     __( 'Exhaust, intake, thin-wall tube', 'benders-hub' ), __( 'Thin wall at about 1–1.5× OD CLR', 'benders-hub' ), __( 'Best — tube stays round', 'benders-hub' ), __( 'Very good', 'benders-hub' ), __( 'Power and dedicated space', 'benders-hub' ), __( 'High', 'benders-hub' ) ),
	array( __( 'CNC / electric', 'benders-hub' ),     __( 'Batch production', 'benders-hub' ), __( 'Machine-specific', 'benders-hub' ), __( 'Depends on tooling (mandrel options)', 'benders-hub' ), __( 'Highest — programmed', 'benders-hub' ), __( 'Industrial power, floor space, training', 'benders-hub' ), __( 'Capital equipment', 'benders-hub' ) ),
);
$heads = array( __( 'Type', 'benders-hub' ), __( 'Best for', 'benders-hub' ), __( 'Typical capacity', 'benders-hub' ), __( 'Thin-wall bend quality', 'benders-hub' ), __( 'Repeatability', 'benders-hub' ), __( 'Power & space', 'benders-hub' ), __( 'Relative cost', 'benders-hub' ) );
?>
<section class="bdh-section bdh-tool bdh-comparison" id="bdh-comparison" aria-labelledby="bdh-comparison-title">
	<div class="<?php echo $in_console ? 'bdh-tool-inner' : 'bdh-container'; ?>">
		<header class="bdh-tool-head">
			<h2 class="bdh-tool-title" id="bdh-comparison-title"><?php esc_html_e( 'Tube bender types compared', 'benders-hub' ); ?></h2>
			<p class="bdh-tool-sub"><?php esc_html_e( 'What actually changes between the main kinds of bender. Capacities are typical for the class — always check the specific machine.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-table-scroll" role="region" aria-label="<?php esc_attr_e( 'Bender types comparison', 'benders-hub' ); ?>" tabindex="0">
			<table class="bdh-table bdh-table--compare">
				<thead><tr><?php foreach ( $heads as $h ) : ?><th scope="col"><?php echo esc_html( $h ); ?></th><?php endforeach; ?></tr></thead>
				<tbody>
				<?php foreach ( $types as $row ) : ?>
					<tr>
						<th scope="row"><?php echo esc_html( array_shift( $row ) ); ?></th>
						<?php foreach ( $row as $cell ) : ?><td><?php echo esc_html( $cell ); ?></td><?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<h3 class="bdh-tool-subtitle"><?php esc_html_e( 'Published specifications: popular benders', 'benders-hub' ); ?></h3>
		<p class="bdh-tool-note"><?php esc_html_e( 'Copied from each manufacturer\'s own pages (checked October 2026). Where a manufacturer\'s pages disagree we show the conflict. Confirm with the manufacturer before buying.', 'benders-hub' ); ?></p>
		<div class="bdh-table-scroll" role="region" aria-label="<?php esc_attr_e( 'Bender specifications', 'benders-hub' ); ?>" tabindex="0">
			<table class="bdh-table bdh-table--specs">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Model', 'benders-hub' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Drive', 'benders-hub' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Round tube / pipe', 'benders-hub' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Wall capacity notes', 'benders-hub' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Max bend angle', 'benders-hub' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Source', 'benders-hub' ); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( bdh_reference_benders() as $b ) : ?>
					<tr>
						<th scope="row"><?php echo esc_html( $b['model'] ); ?></th>
						<td><?php echo esc_html( $b['drive'] ); ?></td>
						<td class="bdh-mono"><?php echo esc_html( $b['od'] ); ?></td>
						<td><?php echo esc_html( $b['wall'] ); ?></td>
						<td><?php echo esc_html( $b['angle'] ); ?></td>
						<td class="bdh-table-links">
							<a href="<?php echo esc_url( $b['source'] ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Manufacturer', 'benders-hub' ); ?></a>
							<?php if ( $b['source2'] ) : ?><a href="<?php echo esc_url( $b['source2'] ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Manual / chart', 'benders-hub' ); ?></a><?php endif; ?>
							<?php if ( ! empty( $b['amazon'] ) ) : ?><a href="<?php echo esc_url( bdh_amazon_product_url( $b['amazon'] ) ); ?>" rel="nofollow sponsored noopener" target="_blank"><?php esc_html_e( 'Amazon', 'benders-hub' ); ?></a><?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p class="bdh-tool-foot">
			<a class="bdh-btn bdh-btn-primary bdh-btn-sm" href="<?php echo esc_url( bdh_tool_url( 'quiz' ) ); ?>"><?php esc_html_e( 'Not sure? Take the quiz', 'benders-hub' ); ?></a>
		</p>
	</div>
</section>
