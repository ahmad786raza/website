<?php
/**
 * Equipment type card — used by the explorer grid and the AJAX filter.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$eq = isset( $args['eq'] ) ? $args['eq'] : get_query_var( 'bdh_eq' );
if ( ! is_array( $eq ) ) { return; }
$beginner = array( 'yes' => __( 'Yes', 'benders-hub' ), 'partial' => __( 'With practice', 'benders-hub' ), 'no' => __( 'Experienced users', 'benders-hub' ) );
?>
<article class="bdh-eq-card" data-eq-category="<?php echo esc_attr( $eq['category'] ); ?>" data-eq-id="<?php echo esc_attr( $eq['id'] ); ?>">
	<header class="bdh-eq-head">
		<span class="bdh-eq-icon"><?php echo bdh_icon( bdh_equipment_icon( $eq['category'] ) ); ?></span>
		<div>
			<h3 class="bdh-eq-name"><?php echo esc_html( $eq['name'] ); ?></h3>
			<p class="bdh-eq-type"><?php echo esc_html( $eq['type'] ); ?></p>
		</div>
	</header>
	<p class="bdh-eq-use"><?php echo esc_html( $eq['use_case'] ); ?></p>
	<dl class="bdh-specs">
		<div><dt><?php esc_html_e( 'Typical capacity', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['capacity'] ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Drive', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['tonnage'] ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Materials', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['tubing'] ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Where it fits', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['workshop'] ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Beginner-friendly', 'benders-hub' ); ?></dt><dd><?php echo esc_html( isset( $beginner[ $eq['beginner'] ] ) ? $beginner[ $eq['beginner'] ] : '' ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Relative cost', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['price'] ); ?></dd></div>
	</dl>
	<div class="bdh-proscons">
		<?php if ( ! empty( $eq['pros'] ) ) : ?>
			<ul class="bdh-pros" aria-label="<?php esc_attr_e( 'Strengths', 'benders-hub' ); ?>"><?php foreach ( $eq['pros'] as $p ) : ?><li><?php echo esc_html( $p ); ?></li><?php endforeach; ?></ul>
		<?php endif; ?>
		<?php if ( ! empty( $eq['cons'] ) ) : ?>
			<ul class="bdh-cons" aria-label="<?php esc_attr_e( 'Limitations', 'benders-hub' ); ?>"><?php foreach ( $eq['cons'] as $c ) : ?><li><?php echo esc_html( $c ); ?></li><?php endforeach; ?></ul>
		<?php endif; ?>
	</div>
	<div class="bdh-eq-actions">
		<?php bdh_amazon_button( $eq['keyword'], __( 'See options on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-outline bdh-btn-sm' ); ?>
	</div>
</article>
