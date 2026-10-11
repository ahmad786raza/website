<?php
$items = hbg_get_buying_guide();
$term  = hbg_resolve_category( 'hbg_cat_block_3', array( 'buying-guide', 'guide' ) );
?>
<section class="buying-guide section section--alt" id="guide" aria-labelledby="guide-heading">
	<div class="container">
		<?php hbg_section_heading( array(
			'id'         => 'guide-heading',
			'kicker'     => __( 'Buying guide', 'hoverboards-guide' ),
			'title'      => __( 'How to choose the right ride', 'hoverboards-guide' ),
			'sub'        => __( 'Six things to check before you click buy. Plain English — no jargon, no fluff.', 'hoverboards-guide' ),
			'link'       => $term ? get_category_link( $term ) : '',
			'link_label' => __( 'All buying guides', 'hoverboards-guide' ),
		) ); ?>
		<div class="guide-grid">
			<?php foreach ( $items as $i => $item ) : ?>
			<div class="guide-card">
				<div class="guide-card__num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
				<div class="guide-card__icon-wrap" aria-hidden="true"><?php echo hbg_guide_icon( $item['icon'] ); // phpcs:ignore ?></div>
				<h3 class="guide-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
				<p class="guide-card__text"><?php echo esc_html( $item['text'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
