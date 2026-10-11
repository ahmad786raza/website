<?php
/**
 * Equipment archive: real equipment entries first, then the equipment-type explorer.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$is_tax  = is_tax( array( 'bdh_equipment_type', 'bdh_brand', 'bdh_archetype_tax' ) );
$queried = $is_tax ? get_queried_object() : null;
get_template_part( 'template-parts/archive-header', null, array(
	'eyebrow' => __( 'Equipment', 'benders-hub' ),
	'title'   => $queried ? esc_html( $queried->name ) : esc_html__( 'Bending equipment', 'benders-hub' ),
	'desc'    => ( $queried && $queried->description ) ? $queried->description : __( 'Equipment guides and the main classes of tube and pipe bending equipment, with typical capacities and limitations.', 'benders-hub' ),
) );

// Only list entries that are real products (not v1 illustrative seeds).
$real = array();
while ( have_posts() ) { the_post(); if ( ! bdh_is_illustrative_product( get_the_ID() ) ) { $real[] = get_the_ID(); } }
rewind_posts();
if ( $real ) : ?>
	<div class="bdh-container bdh-listing">
		<div class="bdh-card-grid">
			<?php foreach ( $real as $rid ) { bdh_post_card( $rid, 'card', 'h2' ); } ?>
		</div>
		<?php bdh_pagination(); ?>
	</div>
<?php endif;

get_template_part( 'template-parts/tools/database' );
get_template_part( 'template-parts/sections/comparison' );
get_footer();
