<?php
/**
 * Equipment types explorer (formerly the "Bending Equipment Database" of
 * fictional products). Lists classes of equipment with honest capabilities.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$in_console = isset( $args['context'] ) && 'console' === $args['context'];
$cats  = bdh_equipment_categories();
$items = bdh_default_equipment();
?>
<section class="bdh-section bdh-tool bdh-database-section" id="bdh-database" aria-labelledby="bdh-db-title">
	<div class="<?php echo $in_console ? 'bdh-tool-inner' : 'bdh-container'; ?>">
		<header class="bdh-tool-head">
			<h2 class="bdh-tool-title" id="bdh-db-title"><?php esc_html_e( 'Equipment types explorer', 'benders-hub' ); ?></h2>
			<p class="bdh-tool-sub"><?php esc_html_e( 'What each class of bending equipment does well, its typical capacity and its limits. Capacities are typical for the class — always check a specific machine\'s chart.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-database-shell" data-bdh-database>
			<div class="bdh-database-controls">
				<label class="bdh-database-search">
					<span class="bdh-screen-reader-text"><?php esc_html_e( 'Filter equipment types', 'benders-hub' ); ?></span>
					<?php echo bdh_icon( 'search' ); ?>
					<input type="search" placeholder="<?php esc_attr_e( 'Filter: e.g. exhaust, conduit, 4130…', 'benders-hub' ); ?>" data-bdh-db-search>
				</label>
				<div class="bdh-database-cats" role="group" aria-label="<?php esc_attr_e( 'Equipment category', 'benders-hub' ); ?>">
				<?php foreach ( $cats as $key => $label ) : ?>
					<button type="button" class="bdh-chip <?php echo 'all' === $key ? 'is-active' : ''; ?>" data-bdh-db-cat="<?php echo esc_attr( $key ); ?>" aria-pressed="<?php echo 'all' === $key ? 'true' : 'false'; ?>"><?php echo esc_html( $label ); ?></button>
				<?php endforeach; ?>
				</div>
			</div>
			<div class="bdh-database-grid" data-bdh-db-grid>
			<?php foreach ( $items as $eq ) {
				get_template_part( 'template-parts/equipment-card', null, array( 'eq' => $eq ) );
			} ?>
			</div>
			<p class="bdh-database-status" data-bdh-db-status aria-live="polite"></p>
		</div>
	</div>
</section>
