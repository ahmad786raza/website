<?php
/**
 * Bending Equipment Database.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$cats = bdh_equipment_categories();
$items = bdh_default_equipment();
?>
<section class="bdh-section bdh-tool bdh-database-section" id="bdh-database" data-bdh-section="equipment_database">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Tool 4 of 8', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Bending Equipment Database', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Search hydraulic, manual, CNC, mandrel, notchers, and rollers. Filter by capacity, precision, and shop type.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-database-shell" data-bdh-database>
			<div class="bdh-database-controls">
				<div class="bdh-database-search">
					<?php echo bdh_icon( 'search' ); ?>
					<input type="search" placeholder="<?php esc_attr_e( 'Search equipment, tubing, use case…', 'benders-hub' ); ?>" data-bdh-db-search>
				</div>
				<div class="bdh-database-cats" role="tablist">
				<?php foreach ( $cats as $key => $label ) : ?>
					<button class="bdh-database-cat <?php echo $key === 'all' ? 'is-active' : ''; ?>" data-bdh-db-cat="<?php echo esc_attr( $key ); ?>" role="tab" aria-selected="<?php echo $key === 'all' ? 'true' : 'false'; ?>">
						<?php echo esc_html( $label ); ?>
					</button>
				<?php endforeach; ?>
				</div>
			</div>

			<div class="bdh-database-grid" data-bdh-db-grid>
			<?php foreach ( $items as $eq ) :
				set_query_var( 'bdh_eq', $eq );
				get_template_part( 'template-parts/equipment-card' );
			endforeach; ?>
			</div>

			<div class="bdh-database-status" data-bdh-db-status aria-live="polite"></div>
		</div>
	</div>
</section>
