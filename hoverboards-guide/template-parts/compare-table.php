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
						<th scope="col"><?php esc_html_e( 'Wheels', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Max rider', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Top speed', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Range', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Certification', 'hoverboards-guide' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Bluetooth', 'hoverboards-guide' ); ?></th>
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
							<span class="compare-table__name"><?php if ( $review ) : ?><a href="<?php echo esc_url( $review ); ?>"><?php echo esc_html( $p['name'] ); ?></a><?php else : ?><?php echo esc_html( $p['name'] ); ?><?php endif; ?></span>
							<?php if ( ! empty( $p['badge'] ) ) : ?><span class="compare-table__badge"><?php echo esc_html( $p['badge'] ); ?></span><?php endif; ?>
							<span class="compare-table__src"><?php echo ! empty( $p['verified'] ) ? esc_html__( 'Verified specs', 'hoverboards-guide' ) : esc_html__( 'Advertised specs', 'hoverboards-guide' ); ?></span>
						</th>
						<td><?php echo esc_html( $p['best_for'] ); ?></td>
						<td><?php echo esc_html( $p['wheel'] ); ?></td>
						<td><?php echo esc_html( $p['weight_cap'] ); ?></td>
						<td><?php echo esc_html( $p['speed'] ); ?></td>
						<td><?php echo esc_html( $p['range'] ); ?></td>
						<td><?php if ( $p['certification'] ) : ?><span class="compare-table__cert"><?php echo hbg_svg_icon( 'shield', 14 ); // phpcs:ignore ?> <?php echo esc_html( $p['certification'] ); ?></span><?php else : ?><span class="compare-table__nocert"><?php esc_html_e( 'Not verified', 'hoverboards-guide' ); ?></span><?php endif; ?></td>
						<td><?php if ( ! empty( $p['bluetooth'] ) ) : ?><span class="compare-table__yes"><?php echo hbg_svg_icon( 'check', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Yes', 'hoverboards-guide' ); ?></span><?php else : ?><span class="compare-table__no"><?php esc_html_e( 'No', 'hoverboards-guide' ); ?></span><?php endif; ?></td>
						<td class="compare-table__links">
							<?php if ( $review ) : ?><a href="<?php echo esc_url( $review ); ?>" class="btn btn--ghost btn--sm"><?php esc_html_e( 'Review', 'hoverboards-guide' ); ?></a><?php endif; ?>
							<?php hbg_amazon_cta( $p, __( 'Check price', 'hoverboards-guide' ), 'sm', false, 'compare' ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p class="compare__empty" hidden><?php esc_html_e( 'No products match this filter.', 'hoverboards-guide' ); ?></p>
		<p class="compare__note"><?php echo hbg_svg_icon( 'info', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Speed and range are manufacturer figures as quoted in our reviews; expect less in real riding, especially for heavier riders, hills and cold weather. “UL 2272” means the listing states certification of the whole board; confirm the mark on the box before you buy.', 'hoverboards-guide' ); ?></p>
	</div>
</section>
