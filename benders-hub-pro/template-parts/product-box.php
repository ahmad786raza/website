<?php
/**
 * Product Box (shortcode variant).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$box = get_query_var( 'bdh_box' );
if ( ! is_array( $box ) ) { return; }
$variant = ! empty( $box['variant'] ) ? sanitize_key( $box['variant'] ) : 'card';
?>
<article class="bdh-product-box bdh-product-box-<?php echo esc_attr( $variant ); ?>">
	<div class="bdh-product-box-media">
		<div class="bdh-product-box-graphic"><?php echo bdh_icon( 'tube', 'bdh-product-box-icon' ); ?></div>
		<?php if ( ! empty( $box['badge'] ) ) : ?>
			<span class="bdh-product-box-badge bdh-badge-<?php echo esc_attr( $box['badge'] ); ?>"><?php echo esc_html( strtoupper( $box['badge'] ) ); ?></span>
		<?php endif; ?>
	</div>
	<div class="bdh-product-box-body">
		<?php if ( ! empty( $box['score'] ) ) : ?>
			<div class="bdh-product-box-meta">
				<span class="bdh-product-box-score"><?php echo bdh_icon( 'star' ); ?> <?php echo esc_html( $box['score'] ); ?>/10</span>
			</div>
		<?php endif; ?>
		<h3 class="bdh-product-box-title"><?php echo esc_html( $box['name'] ?: __( 'Bender pick', 'benders-hub' ) ); ?></h3>
		<?php if ( ! empty( $box['summary'] ) ) : ?>
			<p class="bdh-product-box-summary"><?php echo esc_html( $box['summary'] ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $box['pros'] ) ) : ?>
			<ul class="bdh-product-box-pros">
			<?php foreach ( array_filter( array_map( 'trim', explode( '|', $box['pros'] ) ) ) as $p ) : ?>
				<li><?php echo bdh_icon( 'check' ); ?> <?php echo esc_html( $p ); ?></li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( ! empty( $box['cons'] ) ) : ?>
			<ul class="bdh-product-box-cons">
			<?php foreach ( array_filter( array_map( 'trim', explode( '|', $box['cons'] ) ) ) as $c ) : ?>
				<li>− <?php echo esc_html( $c ); ?></li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<div class="bdh-product-box-cta">
			<?php bdh_amazon_button( $box['keyword'] ?: 'tube bender', __( 'Check Price on Amazon', 'benders-hub' ) ); ?>
		</div>
	</div>
</article>
