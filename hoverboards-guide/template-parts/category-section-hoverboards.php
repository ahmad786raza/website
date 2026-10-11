<?php
$items = hbg_get_hoverboards();
$term  = hbg_resolve_category( 'hbg_cat_block_1', array( 'hoverboards' ) );
if ( ! $items ) {
	return;
}
?>
<section class="category-section section" id="hoverboards" aria-labelledby="hoverboards-heading">
	<div class="container">
		<?php hbg_section_heading( array(
			'id'         => 'hoverboards-heading',
			'kicker'     => __( 'Hoverboards', 'hoverboards-guide' ),
			'title'      => __( 'Hoverboards by use case', 'hoverboards-guide' ),
			'sub'        => __( 'From kid-safe starters to 420 lb all-terrain boards — the models we’d actually buy in each category. All UL 2272 certified, no exceptions.', 'hoverboards-guide' ),
			'link'       => $term ? get_category_link( $term ) : '',
			'link_label' => __( 'Hoverboard guides', 'hoverboards-guide' ),
		) ); ?>
		<?php get_template_part( 'template-parts/product-grid', null, array( 'items' => $items ) ); ?>
	</div>
</section>
