<?php
/**
 * Shortcodes — reusable section embeds for posts and pages.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** [bdh_quiz] */
function bdh_sc_quiz() {
	ob_start();
	get_template_part( 'template-parts/tools/quiz' );
	return ob_get_clean();
}
add_shortcode( 'bdh_quiz', 'bdh_sc_quiz' );

/** [bdh_calculator] */
function bdh_sc_calculator() {
	ob_start();
	get_template_part( 'template-parts/tools/calculator' );
	return ob_get_clean();
}
add_shortcode( 'bdh_calculator', 'bdh_sc_calculator' );

/** [bdh_visualizer] */
function bdh_sc_visualizer() {
	ob_start();
	get_template_part( 'template-parts/tools/visualizer' );
	return ob_get_clean();
}
add_shortcode( 'bdh_visualizer', 'bdh_sc_visualizer' );

/** [bdh_workflow] */
function bdh_sc_workflow() {
	ob_start();
	get_template_part( 'template-parts/tools/workflow' );
	return ob_get_clean();
}
add_shortcode( 'bdh_workflow', 'bdh_sc_workflow' );

/** [bdh_compatibility] */
function bdh_sc_compatibility() {
	ob_start();
	get_template_part( 'template-parts/tools/compatibility' );
	return ob_get_clean();
}
add_shortcode( 'bdh_compatibility', 'bdh_sc_compatibility' );

/** [bdh_prioritizer] */
function bdh_sc_prioritizer() {
	ob_start();
	get_template_part( 'template-parts/tools/prioritizer' );
	return ob_get_clean();
}
add_shortcode( 'bdh_prioritizer', 'bdh_sc_prioritizer' );

/** [bdh_tracker] */
function bdh_sc_tracker() {
	ob_start();
	get_template_part( 'template-parts/tools/tracker' );
	return ob_get_clean();
}
add_shortcode( 'bdh_tracker', 'bdh_sc_tracker' );

/** [bdh_database] */
function bdh_sc_database() {
	ob_start();
	get_template_part( 'template-parts/tools/database' );
	return ob_get_clean();
}
add_shortcode( 'bdh_database', 'bdh_sc_database' );

/** [bdh_amazon keyword="tube bender" label="View on Amazon"] */
function bdh_sc_amazon( $atts ) {
	$atts = shortcode_atts( array(
		'keyword' => 'tube bender',
		'label'   => __( 'View on Amazon', 'benders-hub' ),
		'class'   => 'bdh-btn bdh-btn-primary',
	), $atts, 'bdh_amazon' );
	ob_start();
	bdh_amazon_button( $atts['keyword'], $atts['label'], $atts['class'] );
	return ob_get_clean();
}
add_shortcode( 'bdh_amazon', 'bdh_sc_amazon' );

/** [bdh_product_box keyword="..." name="..." badge="pick" score="9.4"] */
function bdh_sc_product_box( $atts ) {
	$atts = shortcode_atts( array(
		'name'    => '',
		'keyword' => '',
		'badge'   => 'pick',
		'score'   => '',
		'summary' => '',
		'pros'    => '',
		'cons'    => '',
		'variant' => 'card',
	), $atts, 'bdh_product_box' );
	ob_start();
	set_query_var( 'bdh_box', $atts );
	get_template_part( 'template-parts/product-box' );
	return ob_get_clean();
}
add_shortcode( 'bdh_product_box', 'bdh_sc_product_box' );

/** [bdh_faq] */
function bdh_sc_faq() {
	ob_start();
	get_template_part( 'template-parts/sections/faq' );
	return ob_get_clean();
}
add_shortcode( 'bdh_faq', 'bdh_sc_faq' );
