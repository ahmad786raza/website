<?php
$products   = hbg_get_compare_products();
$show_tiers = get_theme_mod( 'hbg_show_price_tiers', true );
if ( ! $products ) {
	return;
}
?>
<section class="compare-section section" id="compare" aria-labelledby="compare-heading">
	<div class="container">
		<?php hbg_section_heading( array(
			'id'     => 'compare-heading',
			'kicker' => __( 'Side-by-side', 'hoverboards-guide' ),
			'title'  => __( 'Compare every pick at a glance', 'hoverboards-guide' ),
			'sub'    => __( 'Filter by category, sort by what matters to you, and click straight through to Amazon.', 'hoverboards-guide' ),
		) ); ?>

		<div class="compare-controls">
			<div class="filter-tabs" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'hoverboards-guide' ); ?>">
				<button type="button" class="filter-tab active" data-filter="All"><?php esc_html_e( 'All', 'hoverboards-guide' ); ?></button>
				<button type="button" class="filter-tab" data-filter="Hoverboard"><?php esc_html_e( 'Hoverboards', 'hoverboards-guide' ); ?></button>
				<button type="button" class="filter-tab" data-filter="Scooter"><?php esc_html_e( 'Scooters', 'hoverboards-guide' ); ?></button>
			</div>
			<div class="sort-controls" role="group" aria-label="<?php esc_attr_e( 'Sort by', 'hoverboards-guide' ); ?>">
				<span><?php esc_html_e( 'Sort by', 'hoverboards-guide' ); ?></span>
				<div class="sort-tabs">
					<button type="button" class="sort-tab active" data-sort="score" data-default-dir="desc"><?php esc_html_e( 'Score', 'hoverboards-guide' ); ?><span class="sort-arrow"> ↓</span></button>
					<button type="button" class="sort-tab" data-sort="price" data-default-dir="asc"><?php esc_html_e( 'Price', 'hoverboards-guide' ); ?><span class="sort-arrow"></span></button>
					<button type="button" class="sort-tab" data-sort="range" data-default-dir="desc"><?php esc_html_e( 'Range', 'hoverboards-guide' ); ?><span class="sort-arrow"></span></button>
					<button type="button" class="sort-tab" data-sort="speed" data-default-dir="desc"><?php esc_html_e( 'Speed', 'hoverboards-guide' ); ?><span class="sort-arrow"></span></button>
				</div>
			</div>
		</div>

		<div class="compare-table-wrap">
			<div class="compare-table-scroll">
				<table class="compare-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Product', 'hoverboards-guide' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Best for', 'hoverboards-guide' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Score', 'hoverboards-guide' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Range', 'hoverboards-guide' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Speed', 'hoverboards-guide' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Max rider', 'hoverboards-guide' ); ?></th>
							<?php if ( $show_tiers ) : ?><th scope="col"><?php esc_html_e( 'Price', 'hoverboards-guide' ); ?></th><?php endif; ?>
							<th scope="col"><span class="screen-reader-text"><?php esc_html_e( 'Buy', 'hoverboards-guide' ); ?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $products as $p ) :
							$range_num = (float) preg_replace( '/[^\d.]/', '', $p['range'] );
							$speed_num = (float) preg_replace( '/[^\d.]/', '', $p['speed'] );
						?>
						<tr data-id="<?php echo esc_attr( $p['id'] ); ?>" data-category="<?php echo esc_attr( $p['category'] ); ?>" data-price="<?php echo esc_attr( $p['price_hint'] ); ?>" data-range="<?php echo esc_attr( $range_num ); ?>" data-speed="<?php echo esc_attr( $speed_num ); ?>" data-score="<?php echo esc_attr( $p['score'] ); ?>">
							<td>
								<div class="compare-table__product">
									<div class="compare-table__icon" style="background:<?php echo esc_attr( $p['color'] ); ?>18">
										<?php echo 'Hoverboard' === $p['category'] ? hbg_hover_icon( $p['color'] ) : hbg_scoot_icon( $p['color'] ); // phpcs:ignore ?>
									</div>
									<div>
										<div class="compare-table__product-name"><?php echo esc_html( $p['name'] ); ?></div>
										<div class="compare-table__product-cat"><?php echo esc_html( $p['category'] ); ?> · <?php echo esc_html( $p['badge'] ); ?></div>
									</div>
								</div>
							</td>
							<td><?php echo esc_html( $p['best_for'] ); ?></td>
							<td><?php hbg_score_badge( $p['score'] ); ?></td>
							<td><strong><?php echo esc_html( $p['range'] ); ?></strong></td>
							<td><strong><?php echo esc_html( $p['speed'] ); ?></strong></td>
							<td><?php echo esc_html( $p['weight_cap'] ); ?></td>
							<?php if ( $show_tiers ) : ?><td class="compare-table__price"><?php echo esc_html( hbg_price_tier_label( $p['tier'] ) ); ?></td><?php endif; ?>
							<td><?php hbg_amazon_cta( $p, __( 'Check price', 'hoverboards-guide' ), 'sm' ); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<p class="compare-note"><?php echo hbg_svg_icon( 'info', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Range and speed are manufacturer ratings; expect 60–70% of rated range in real riding. Price tiers reflect typical Amazon pricing and change often.', 'hoverboards-guide' ); ?></p>
	</div>
</section>
