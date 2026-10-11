<?php
/**
 * Editor's top picks (products).
 * v2.1: each card also links to the site's own review of the model when one exists
 * (matched by title, cached for a day) — a second path to the purchase for cautious shoppers.
 */
$picks = hbg_get_top_picks();
if ( ! $picks ) {
	return;
}
$show_tiers = get_theme_mod( 'hbg_show_price_tiers', true );
?>
<section class="top-picks section" id="picks" aria-labelledby="picks-heading">
	<div class="container">
		<?php hbg_section_heading( array(
			'id'         => 'picks-heading',
			'kicker'     => __( 'Editor’s top picks', 'hoverboards-guide' ),
			'title'      => __( 'The rides we’d buy in 2026', 'hoverboards-guide' ),
			'sub'        => __( 'Four real, currently-sold models that nail the balance of safety, range and value. Every one is UL 2272 certified and in stock on Amazon.', 'hoverboards-guide' ),
			'link'       => get_theme_mod( 'hbg_show_compare', true ) ? '#compare' : '',
			'link_label' => __( 'Compare all', 'hoverboards-guide' ),
		) ); ?>

		<div class="picks-grid" role="list">
			<?php foreach ( $picks as $pick ) : $review = hbg_product_review_url( $pick ); ?>
			<article class="pick-card" role="listitem" style="--pcolor:<?php echo esc_attr( $pick['color'] ); ?>">
				<div class="pick-card__top">
					<span class="pick-card__rank">#<?php echo esc_html( $pick['rank'] ); ?></span>
					<span class="pick-card__badge"><?php echo esc_html( $pick['badge'] ); ?></span>
				</div>
				<?php hbg_product_image( $pick, array( 'class' => 'product-image pick-card__img', 'ratio' => '4/3', 'sizes' => '(max-width: 640px) 100vw, 300px' ) ); ?>
				<div class="pick-card__body">
					<h3 class="pick-card__name"><?php echo esc_html( $pick['name'] ); ?></h3>
					<p class="pick-card__tagline"><?php echo esc_html( $pick['tagline'] ); ?></p>
					<div class="pick-card__rating">
						<?php hbg_score_badge( $pick['score'] ); ?>
						<?php hbg_stars( $pick['score'] / 2, 13 ); ?>
					</div>
					<div class="pick-card__features">
						<div><span class="pick-card__feature-label"><?php esc_html_e( 'Range', 'hoverboards-guide' ); ?></span><span class="pick-card__feature-value"><?php echo esc_html( $pick['range'] ); ?></span></div>
						<div><span class="pick-card__feature-label"><?php esc_html_e( 'Top speed', 'hoverboards-guide' ); ?></span><span class="pick-card__feature-value"><?php echo esc_html( $pick['speed'] ); ?></span></div>
						<div><span class="pick-card__feature-label"><?php esc_html_e( 'Max rider', 'hoverboards-guide' ); ?></span><span class="pick-card__feature-value"><?php echo esc_html( $pick['weight_cap'] ); ?></span></div>
					</div>
					<ul class="pick-card__bullets">
						<?php foreach ( $pick['bullets'] as $bullet ) : ?>
							<li><?php echo hbg_svg_icon( 'check', 14, 'pick-card__check' ); // phpcs:ignore ?><span><?php echo esc_html( $bullet ); ?></span></li>
						<?php endforeach; ?>
					</ul>
					<div class="pick-card__footer">
						<?php if ( $show_tiers ) : ?>
							<div>
								<span class="pick-card__price-label"><?php esc_html_e( 'Typical price', 'hoverboards-guide' ); ?></span>
								<span class="price-tier"><?php echo esc_html( hbg_price_tier_label( $pick['tier'] ) ); ?></span>
							</div>
						<?php endif; ?>
						<?php hbg_amazon_cta( $pick, '', 'md', true ); ?>
						<div class="pick-card__secondary">
							<?php if ( $review ) : ?>
								<a href="<?php echo esc_url( $review ); ?>" class="pick-card__review"><?php esc_html_e( 'Read our review', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 12 ); // phpcs:ignore ?></a>
							<?php endif; ?>
							<button class="pick-card__compare-btn" type="button" data-product-id="<?php echo esc_attr( $pick['id'] ); ?>" aria-pressed="false">+ <?php esc_html_e( 'Add to compare', 'hoverboards-guide' ); ?></button>
						</div>
					</div>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
