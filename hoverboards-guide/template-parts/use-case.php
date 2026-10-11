<?php
/**
 * Shop by use case: hoverboard and scooter product grids in one tabbed section.
 * Replaces the two stacked product rows from v2.0. Panel ids stay "hoverboards" / "scooters"
 * so existing anchors (final CTA, menus) keep working; theme.js opens the right tab on arrival.
 */
$panels = array();
$boards = hbg_get_hoverboards();
if ( $boards ) {
	$term     = hbg_resolve_category( 'hbg_cat_block_1', array( 'hoverboards' ) );
	$panels[] = array(
		'id'         => 'hoverboards',
		'label'      => __( 'Hoverboards', 'hoverboards-guide' ),
		'items'      => $boards,
		'sub'        => __( 'From kid-safe starters to 420 lb all-terrain boards — the models we’d actually buy in each category. All UL 2272 certified, no exceptions.', 'hoverboards-guide' ),
		'link'       => $term ? get_category_link( $term ) : '',
		'link_label' => __( 'Hoverboard guides & reviews', 'hoverboards-guide' ),
		'color'      => '#FF7A1A',
	);
}
$scooters = hbg_get_scooters();
if ( $scooters ) {
	$term     = hbg_resolve_category( 'hbg_cat_block_2', array( 'e-scooters', 'scooters' ) );
	$panels[] = array(
		'id'         => 'scooters',
		'label'      => __( 'Electric scooters', 'hoverboards-guide' ),
		'items'      => $scooters,
		'sub'        => __( 'Commuters, budget picks and performance machines — the scooters that earn their price in real riding, with the range and brakes to match.', 'hoverboards-guide' ),
		'link'       => $term ? get_category_link( $term ) : '',
		'link_label' => __( 'Scooter guides & reviews', 'hoverboards-guide' ),
		'color'      => '#0EA5A4',
	);
}
if ( ! $panels ) {
	return;
}
?>
<section class="use-case section" id="shop" aria-labelledby="shop-heading">
	<div class="container">
		<?php hbg_section_heading( array(
			'id'         => 'shop-heading',
			'kicker'     => __( 'Shop by use case', 'hoverboards-guide' ),
			'title'      => __( 'The right ride for every rider', 'hoverboards-guide' ),
			'sub'        => __( 'Pick your category, then the use case — each card links straight to the live Amazon listing.', 'hoverboards-guide' ),
			'link'       => get_theme_mod( 'hbg_show_compare', true ) ? '#compare' : '',
			'link_label' => __( 'Compare all', 'hoverboards-guide' ),
		) ); ?>

		<div class="tabs" data-tabs>
			<div class="tabs__list" role="tablist" aria-label="<?php esc_attr_e( 'Product category', 'hoverboards-guide' ); ?>">
				<?php foreach ( $panels as $i => $panel ) : ?>
					<button type="button" role="tab" class="tab<?php echo 0 === $i ? ' is-active' : ''; ?>" id="shop-tab-<?php echo esc_attr( $panel['id'] ); ?>" aria-controls="<?php echo esc_attr( $panel['id'] ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" style="--cat:<?php echo esc_attr( $panel['color'] ); ?>">
						<?php echo esc_html( $panel['label'] ); ?>
						<span class="tab__count"><?php echo esc_html( number_format_i18n( count( $panel['items'] ) ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<?php foreach ( $panels as $i => $panel ) : ?>
			<div class="tabs__panel<?php echo 0 === $i ? ' is-active' : ''; ?>" role="tabpanel" id="<?php echo esc_attr( $panel['id'] ); ?>" aria-labelledby="shop-tab-<?php echo esc_attr( $panel['id'] ); ?>">
				<p class="tabs__panel-intro"><?php echo esc_html( $panel['sub'] ); ?></p>
				<?php get_template_part( 'template-parts/product-grid', null, array( 'items' => $panel['items'] ) ); ?>
				<?php if ( $panel['link'] ) : ?>
				<div class="tabs__panel-foot tabs__panel-foot--end">
					<a href="<?php echo esc_url( $panel['link'] ); ?>" class="section-heading__link"><?php echo esc_html( $panel['link_label'] ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a>
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
