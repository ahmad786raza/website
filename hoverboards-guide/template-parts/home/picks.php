<?php
/**
 * B. Quick recommendations: one product per need, each linking to the full review and to Amazon.
 */
$embedded = ! empty( $args['embedded'] );
$picks    = hbg_get_top_picks( 6 );
if ( ! $picks ) {
	return;
}
$best = hbg_key_url( 'best' );
?>
<section class="section<?php echo $embedded ? ' picks--embedded' : ' section--tint-blue'; ?> picks" id="picks" aria-labelledby="picks-title">
	<div class="<?php echo $embedded ? '' : 'container'; ?>">
		<?php if ( ! $embedded ) : ?>
			<?php hbg_section_heading( array( 'id' => 'picks-title', 'kicker' => __( 'Quick recommendations', 'hoverboards-guide' ), 'title' => __( 'The hoverboards we point readers to first', 'hoverboards-guide' ), 'sub' => __( 'One pick per need, drawn from our roundups. Each card links to the full review and to the Amazon listing.', 'hoverboards-guide' ), 'link' => $best, 'link_label' => __( 'Read the full roundup', 'hoverboards-guide' ) ) ); ?>
			<?php hbg_affiliate_disclosure_box( true ); ?>
		<?php endif; ?>
		<div class="picks__grid">
			<?php foreach ( $picks as $i => $p ) { hbg_product_card( $p, array( 'placement' => 'home-picks-' . ( $i + 1 ) ) ); } ?>
		</div>
		<?php if ( ! $embedded && get_theme_mod( 'hbg_show_compare', true ) ) : ?>
			<p class="picks__foot"><a href="#compare" class="link-arrow"><?php esc_html_e( 'Compare specifications side by side', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a></p>
		<?php endif; ?>
	</div>
</section>
