<?php
/**
 * Comparison Tables
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$rows = array_slice( bdh_default_equipment(), 0, 5 );
?>
<section class="bdh-section bdh-comparison" id="bdh-comparison" data-bdh-section="comparison">
	<div class="bdh-container">
		<header class="bdh-section-head">
			<span class="bdh-section-tag"><?php esc_html_e( 'Side by side', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Tube bender comparison table', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Capacity, precision, durability, automation, and editor score. Swipe horizontally on mobile.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-table-wrap" data-bdh-comparison>
			<table class="bdh-compare-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Bender', 'benders-hub' ); ?></th>
						<th><?php esc_html_e( 'Capacity', 'benders-hub' ); ?></th>
						<th><?php esc_html_e( 'Power', 'benders-hub' ); ?></th>
						<th><?php esc_html_e( 'Precision', 'benders-hub' ); ?></th>
						<th><?php esc_html_e( 'Durability', 'benders-hub' ); ?></th>
						<th><?php esc_html_e( 'Workshop', 'benders-hub' ); ?></th>
						<th><?php esc_html_e( 'Score', 'benders-hub' ); ?></th>
						<th><?php esc_html_e( 'CTA', 'benders-hub' ); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $rows as $i => $r ) : ?>
					<tr class="<?php echo $i === 0 ? 'bdh-best-pick' : ''; ?>">
						<td data-label="<?php esc_attr_e( 'Bender', 'benders-hub' ); ?>">
							<div class="bdh-compare-name">
								<?php echo bdh_icon( 'tube' ); ?>
								<span>
									<strong><?php echo esc_html( $r['name'] ); ?></strong>
									<small><?php echo esc_html( $r['type'] ); ?></small>
								</span>
							</div>
						</td>
						<td data-label="<?php esc_attr_e( 'Capacity', 'benders-hub' ); ?>"><?php echo esc_html( $r['capacity'] ); ?></td>
						<td data-label="<?php esc_attr_e( 'Power', 'benders-hub' ); ?>"><?php echo esc_html( $r['tonnage'] ); ?></td>
						<td data-label="<?php esc_attr_e( 'Precision', 'benders-hub' ); ?>"><span class="bdh-pill bdh-pill-<?php echo esc_attr( $r['precision'] ); ?>"><?php echo esc_html( strtoupper( $r['precision'] ) ); ?></span></td>
						<td data-label="<?php esc_attr_e( 'Durability', 'benders-hub' ); ?>">
							<span class="bdh-rating">
								<?php for ( $s = 1; $s <= 5; $s++ ) {
									echo $s <= (int) $r['durability'] ? bdh_icon( 'star', 'bdh-star bdh-star-on' ) : bdh_icon( 'star', 'bdh-star bdh-star-off' );
								} ?>
							</span>
						</td>
						<td data-label="<?php esc_attr_e( 'Workshop', 'benders-hub' ); ?>"><?php echo esc_html( ucfirst( $r['workshop'] ) ); ?></td>
						<td data-label="<?php esc_attr_e( 'Score', 'benders-hub' ); ?>"><strong class="bdh-score"><?php echo esc_html( $r['score'] ); ?></strong>/10</td>
						<td data-label="<?php esc_attr_e( 'CTA', 'benders-hub' ); ?>">
							<?php bdh_amazon_button( $r['keyword'], __( 'View on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-primary bdh-btn-sm' ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
