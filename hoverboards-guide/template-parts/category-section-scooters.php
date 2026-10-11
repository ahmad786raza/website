<?php
$items = hbg_get_scooters();
$term  = hbg_resolve_category( 'hbg_cat_block_2', array( 'e-scooters', 'scooters' ) );
if ( ! $items ) {
	return;
}
?>
<section class="category-section section section--alt" id="scooters" aria-labelledby="scooters-heading">
	<div class="container">
		<?php hbg_section_heading( array(
			'id'         => 'scooters-heading',
			'kicker'     => __( 'Electric scooters', 'hoverboards-guide' ),
			'title'      => __( 'Scooters for every commute', 'hoverboards-guide' ),
			'sub'        => __( 'Whether you fold it under a desk or chew through 40-mile rides, the right scooter saves hours a week. These four cover every budget.', 'hoverboards-guide' ),
			'link'       => $term ? get_category_link( $term ) : '',
			'link_label' => __( 'Scooter guides', 'hoverboards-guide' ),
		) ); ?>
		<?php get_template_part( 'template-parts/product-grid', null, array( 'items' => $items ) ); ?>
	</div>
</section>
