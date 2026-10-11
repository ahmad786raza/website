<?php
/**
 * Comparison table (homepage and [hbg_compare]). Verified specifications only where marked;
 * everything else is labelled as manufacturer-advertised.
 */
$products = isset( $args['products'] ) ? $args['products'] : hbg_get_compare_products();
$embedded = ! empty( $args['embedded'] );
$title    = ! empty( $args['title'] ) ? $args['title'] : __( 'Compare specifications', 'hoverboards-guide' );
if ( ! $products ) {
	return;
}
?>
<section class="section compare<?php echo $embedded ? ' compare--embedded' : ''; ?>" id="compare" aria-labelledby="compare-title">
	<div class="<?php echo $embedded ? '' : 'container'; ?>">
		<?php hbg_section_heading( array( 'id' => 'compare-title', 'kicker' => $embedded ? '' : __( 'Side by side', 'hoverboards-guide' ), 'title' => $title, 'sub' => $embedded ? '' : __( 'Filter by rider and sort by what matters. Speed and range are manufacturer claims unless a row is marked verified.', 'hoverboards-guide' ), 'tag' => $embedded ? 'h3' : 'h2' ) ); ?>
		<div class="compare__controls">
			<div class="seg" role="group" aria-label="<?php esc_attr_e( 'Filter by rider', 'hoverboards-guide' ); ?>">
				<button type="button" class="seg__btn is-active" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All', 'hoverboards-guide' ); ?></button>
				<button type="button" class="seg__btn" data-filter="kids" aria-pressed="false"><?php esc_html_e( 'Kids & beginners', 'hoverboards-guide' ); ?></button>
				<button type="button" class="seg__btn" data-filter="adults" aria-pressed="false"><?php esc_html_e( 'Adults', 'hoverboards-guide' ); ?></button>
				<button type="button" class="seg__btn" data-filter="terrain" aria-pressed="false"><?php esc_html_e( 'All-terrain', 'hoverboards-guide' ); ?></button>
			</div>
			<label class="compare__sort"><span><?php esc_html_e( 'Sort by', 'hoverboards-guide' ); ?></span>
				<select data-sort>
					<option value=""><?php esc_html_e( 'Our order', 'hoverboards-guide' ); ?></option>
					<option value="weight"><?php esc_html_e( 'Max rider weight', 'hoverboards-guide' ); ?></option>
					<option value="wheel"><?php esc_html_e( 'Wheel size', 'hoverboards-guide' ); ?></option>
					<option value="speed"><?php esc_html_e( 'Top speed (advertised)', 'hoverboards-guide' ); ?></option>
					<option value="range"><?php esc_html_e( 'Range (advertised)', 'hoverboards-guide' ); ?></option>
				</select>
			</label>
		</div>
		<p class="table-hint" aria-hidden="true"><?php esc_html_e( 'Scroll sideways to see every column', 'hoverboards-guide' ); ?></p>
		<div class="table-scroll table-scroll--compare" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Comparison table, scrolls sideways', 'hoverboards-guide' ); ?>">
			<table class="compare-table">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Product', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Best for', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php echo hbg_svg_icon( 'ruler', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Wheels', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php echo hbg_svg_icon( 'weight', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Max rider', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php echo hbg_svg_icon( 'speed', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Top speed', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php echo hbg_svg_icon( 'battery', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Range', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php echo hbg_svg_icon( 'shield', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Certification', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php echo hbg_svg_icon( 'bluetooth', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Bluetooth', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Links', 'hoverboards-guide' ); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $products as $i => $p ) :
					$wheel  = hbg_num( $p['wheel'] );
					$weight = hbg_num( $p['weight_cap'] );
					$tags   = array();
					if ( in_array( $p['role'], array( 'kids', 'beginners', 'budget' ), true ) || ( $weight && $weight < 200 ) ) {
						$tags[] = 'kids';
					}
					if ( $weight >= 220 ) {
						$tags[] = 'adults';
					}
					if ( $wheel >= 8.5 ) {
						$tags[] = 'terrain';
					}
					$review = hbg_product_review_url( $p );
				?>
					<tr class="<?php echo 'overall' === $p['role'] ? 'is-pick' : ''; ?>" data-role="<?php echo esc_attr( $p['role'] ); ?>" data-id="<?php echo esc_attr( $p['id'] ); ?>" data-order="<?php echo (int) $i; ?>" data-tags="<?php echo esc_attr( implode( ' ', $tags ) ); ?>" data-weight="<?php echo esc_attr( $weight ); ?>" data-wheel="<?php echo esc_attr( $wheel ); ?>" data-speed="<?php echo esc_attr( hbg_num( $p['speed'] ) ); ?>" data-range="<?php echo esc_attr( hbg_num( $p['range'] ) ); ?>">
						<th scope="row">
							<div class="compare-table__product">
								<div class="compare-table__thumb">
									<?php if ( ! empty( $p['image_id'] ) ) : ?>
										<?php echo wp_get_attachment_image( (int) $p['image_id'], 'thumbnail', false, array( 'alt' => $p['name'], 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
									<?php else : ?>
										<?php echo hbg_product_tile( $p ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php endif; ?>
								</div>
								<div class="compare-table__id">
									<span class="compare-table__name"><?php if ( $review ) : ?><a href="<?php echo esc_url( $review ); ?>"><?php echo esc_html( $p['name'] ); ?></a><?php else : ?><?php echo esc_html( $p['name'] ); ?><?php endif; ?></span>
									<?php if ( ! empty( $p['badge'] ) ) : ?><span class="compare-table__badge"><?php echo esc_html( $p['badge'] ); ?></span><?php endif; ?>
									<?php if ( ! empty( $p['verified'] ) ) : ?><span class="compare-table__verified"><?php echo hbg_svg_icon( 'check', 12 ); // phpcs:ignore ?> <?php esc_html_e( 'Verified specs', 'hoverboards-guide' ); ?></span><?php endif; ?>
									<?php if ( $review ) : ?><a href="<?php echo esc_url( $review ); ?>" class="compare-table__review"><?php esc_html_e( 'Read review', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 12 ); // phpcs:ignore ?></a><?php endif; ?>
								</div>
							</div>
						</th>
						<td class="compare-table__for" data-label="<?php esc_attr_e( 'Best for', 'hoverboards-guide' ); ?>"><?php echo esc_html( $p['best_for'] ); ?></td>
						<td data-label="<?php esc_attr_e( 'Wheels', 'hoverboards-guide' ); ?>"><?php echo esc_html( $p['wheel'] ); ?></td>
						<td data-label="<?php esc_attr_e( 'Max rider', 'hoverboards-guide' ); ?>"><?php echo esc_html( $p['weight_cap'] ); ?></td>
						<td data-label="<?php esc_attr_e( 'Top speed', 'hoverboards-guide' ); ?>"><?php echo esc_html( $p['speed'] ); ?></td>
						<td data-label="<?php esc_attr_e( 'Range', 'hoverboards-guide' ); ?>"><?php echo esc_html( $p['range'] ); ?></td>
						<td data-label="<?php esc_attr_e( 'Certification', 'hoverboards-guide' ); ?>"><?php if ( $p['certification'] ) : ?><span class="compare-table__cert"><?php echo hbg_svg_icon( 'shield', 14 ); // phpcs:ignore ?> <?php echo esc_html( $p['certification'] ); ?></span><?php else : ?><span class="compare-table__nocert"><?php esc_html_e( 'Not verified', 'hoverboards-guide' ); ?></span><?php endif; ?></td>
						<td data-label="<?php esc_attr_e( 'Bluetooth', 'hoverboards-guide' ); ?>"><?php if ( ! empty( $p['bluetooth'] ) ) : ?><span class="compare-table__yes"><?php echo hbg_svg_icon( 'check', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Yes', 'hoverboards-guide' ); ?></span><?php else : ?><span class="compare-table__no"><?php esc_html_e( 'No', 'hoverboards-guide' ); ?></span><?php endif; ?></td>
						<td class="compare-table__links">
							<?php hbg_amazon_cta( $p, __( 'Check price', 'hoverboards-guide' ), 'sm', true, 'compare' ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p class="compare__empty" hidden><?php esc_html_e( 'No products match this filter.', 'hoverboards-guide' ); ?></p>
		<p class="compare__note"><?php echo hbg_svg_icon( 'info', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Speed and range are manufacturer figures as quoted in our reviews; expect less in real riding, especially for heavier riders, hills and cold weather. “Highest” marks the top advertised figure among the boards listed here. “UL 2272” means the listing states certification of the whole board; confirm the mark on the box before you buy. Board tiles are illustrations drawn from each model’s wheel size and tyre type, not product photos.', 'hoverboards-guide' ); ?></p>
	</div>
</section>
