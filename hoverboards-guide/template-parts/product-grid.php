<?php
/**
 * Compact product cards grid.
 *
 * @param array $args { 'items' => array of products }
 */
$items = isset( $args['items'] ) ? $args['items'] : array();
if ( ! $items ) {
	return;
}
$show_tiers = get_theme_mod( 'hbg_show_price_tiers', true );
?>
<div class="cat-grid" role="list">
	<?php foreach ( $items as $item ) : ?>
	<article class="cat-card" role="listitem" style="--pcolor:<?php echo esc_attr( $item['color'] ); ?>">
		<div class="cat-card__image-wrap">
			<?php hbg_product_image( $item, array( 'class' => 'product-image', 'ratio' => '4/3', 'sizes' => '(max-width: 640px) 100vw, 300px' ) ); ?>
			<span class="cat-card__tag"><?php echo esc_html( $item['tag'] ); ?></span>
		</div>
		<div class="cat-card__body">
			<div class="cat-card__head">
				<h3 class="cat-card__name"><?php echo esc_html( $item['name'] ); ?></h3>
				<?php hbg_score_badge( $item['score'] ); ?>
			</div>
			<p class="cat-card__tagline"><?php echo esc_html( $item['tagline'] ); ?></p>
			<div class="cat-card__specs">
				<span><strong><?php echo esc_html( $item['range'] ); ?></strong> <?php esc_html_e( 'range', 'hoverboards-guide' ); ?></span>
				<span><strong><?php echo esc_html( $item['speed'] ); ?></strong> <?php esc_html_e( 'top', 'hoverboards-guide' ); ?></span>
				<span><strong><?php echo esc_html( $item['weight_cap'] ); ?></strong> <?php esc_html_e( 'max', 'hoverboards-guide' ); ?></span>
			</div>
			<div class="cat-card__footer">
				<?php if ( $show_tiers ) : ?>
					<span class="price-tier"><?php echo esc_html( hbg_price_tier_label( $item['tier'] ) ); ?></span>
				<?php endif; ?>
				<?php hbg_amazon_cta( $item, __( 'View on Amazon', 'hoverboards-guide' ), 'sm' ); ?>
			</div>
		</div>
	</article>
	<?php endforeach; ?>
</div>
