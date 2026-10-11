<?php
/**
 * Top picks: a short editorial buying panel — one recommendation per need, two or three
 * specifications each, the review link and a clearly labelled Amazon link.
 */
$embedded = ! empty( $args['embedded'] );
$picks    = hbg_get_top_picks( $embedded ? 6 : 4 );
if ( ! $picks ) {
	return;
}
$best = hbg_key_url( 'best' );
?>
<section class="picks<?php echo $embedded ? ' picks--embedded' : ''; ?>" id="picks" aria-labelledby="picks-title">
	<div class="<?php echo $embedded ? '' : 'container'; ?>">
		<div class="picks__panel">
			<?php hbg_zone_heading( array( 'title' => __( 'Top picks', 'hoverboards-guide' ), 'kicker' => __( 'Buying guide', 'hoverboards-guide' ), 'url' => $best, 'more' => __( 'Read the full roundup', 'hoverboards-guide' ), 'id' => 'picks-title', 'tag' => $embedded ? 'h3' : 'h2' ) ); ?>
			<p class="picks__intro"><?php esc_html_e( 'One recommendation per need, drawn from our roundups. Specifications are manufacturer figures unless a product is marked as checked.', 'hoverboards-guide' ); ?><?php if ( ! $embedded && get_theme_mod( 'hbg_show_compare', true ) ) : ?> <a href="#compare"><?php esc_html_e( 'Compare them side by side', 'hoverboards-guide' ); ?></a>.<?php endif; ?></p>
			<div class="picks__grid">
				<?php foreach ( $picks as $i => $p ) : $review = hbg_product_review_url( $p ); ?>
				<article class="pick" data-product="<?php echo esc_attr( $p['id'] ); ?>">
					<?php hbg_product_image( $p, array( 'class' => 'product-image pick__img', 'ratio' => '4/3', 'sizes' => '(max-width: 640px) 100vw, 280px' ) ); ?>
					<?php if ( ! empty( $p['badge'] ) ) : ?><span class="pick__label<?php echo 'overall' === $p['role'] ? ' pick__label--top' : ''; ?>"><?php echo esc_html( $p['badge'] ); ?></span><?php endif; ?>
					<h3 class="pick__name"><?php if ( $review ) : ?><a href="<?php echo esc_url( $review ); ?>"><?php echo esc_html( $p['name'] ); ?></a><?php else : ?><?php echo esc_html( $p['name'] ); ?><?php endif; ?></h3>
					<?php if ( ! empty( $p['best_for'] ) ) : ?><p class="pick__for"><?php echo esc_html( $p['best_for'] ); ?></p><?php endif; ?>
					<?php hbg_spec_list( $p, array( 'wheel', 'weight_cap', 'range' ), 'specs specs--inline' ); ?>
					<div class="pick__links">
						<?php if ( $review ) : ?><a class="pick__review" href="<?php echo esc_url( $review ); ?>"><?php esc_html_e( 'Read the review', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 13 ); // phpcs:ignore ?></a><?php endif; ?>
						<?php hbg_amazon_cta( $p, __( 'Check price', 'hoverboards-guide' ), 'sm', false, 'home-picks-' . ( $i + 1 ) ); ?>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
			<p class="picks__foot"><?php echo esc_html( hbg_disclosure_text() ); ?><?php if ( ! $embedded && get_theme_mod( 'hbg_show_compare', true ) ) : ?> <a href="#compare"><?php esc_html_e( 'See the full comparison table', 'hoverboards-guide' ); ?></a>.<?php endif; ?></p>
		</div>
	</div>
</section>
