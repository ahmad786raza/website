<?php
/**
 * Equipment card — used in database grid + AJAX results.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$eq = get_query_var( 'bdh_eq' );
if ( ! is_array( $eq ) ) { return; }
?>
<article class="bdh-eq-card" data-eq-category="<?php echo esc_attr( $eq['category'] ); ?>" data-eq-id="<?php echo esc_attr( $eq['id'] ); ?>">
	<div class="bdh-eq-card-top">
		<span class="bdh-eq-icon"><?php echo bdh_icon( bdh_equipment_icon( $eq['category'] ) ); ?></span>
		<?php if ( ! empty( $eq['badge'] ) ) : ?>
			<span class="bdh-eq-badge bdh-badge-<?php echo esc_attr( $eq['badge'] ); ?>"><?php echo esc_html( strtoupper( $eq['badge'] ) ); ?></span>
		<?php endif; ?>
	</div>
	<h3 class="bdh-eq-name"><?php echo esc_html( $eq['name'] ); ?></h3>
	<p class="bdh-eq-type"><?php echo esc_html( $eq['type'] ); ?></p>
	<dl class="bdh-eq-specs">
		<div><dt><?php esc_html_e( 'Capacity', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['capacity'] ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Power', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['tonnage'] ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Tubing', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['tubing'] ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Precision', 'benders-hub' ); ?></dt><dd><?php echo esc_html( strtoupper( $eq['precision'] ) ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Shop', 'benders-hub' ); ?></dt><dd><?php echo esc_html( ucfirst( $eq['workshop'] ) ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Beginner', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['beginner'] === 'yes' ? __( 'Yes', 'benders-hub' ) : ( $eq['beginner'] === 'partial' ? __( 'With training', 'benders-hub' ) : __( 'No', 'benders-hub' ) ) ); ?></dd></div>
	</dl>
	<div class="bdh-eq-meta-row">
		<span class="bdh-eq-score"><?php echo bdh_icon( 'star' ); ?> <?php echo esc_html( $eq['score'] ); ?>/10</span>
		<span class="bdh-eq-price"><?php echo esc_html( $eq['price'] ); ?></span>
	</div>
	<div class="bdh-eq-prons">
		<?php if ( ! empty( $eq['pros'] ) ) : ?>
			<ul class="bdh-eq-pros">
			<?php foreach ( $eq['pros'] as $p ) : ?>
				<li><?php echo bdh_icon( 'check' ); ?> <?php echo esc_html( $p ); ?></li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<div class="bdh-eq-actions">
		<?php bdh_amazon_button( $eq['keyword'], __( 'View on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-primary bdh-btn-block' ); ?>
		<a class="bdh-btn bdh-btn-ghost bdh-btn-block bdh-btn-sm" href="#bdh-comparison"><?php esc_html_e( 'Compare', 'benders-hub' ); ?></a>
	</div>
</article>
