<?php
/**
 * Search results.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
global $wp_query;
$count = (int) $wp_query->found_posts;
get_template_part( 'template-parts/archive-header', null, array(
	'eyebrow' => __( 'Search', 'benders-hub' ),
	/* translators: %s: search query */
	'title'   => sprintf( esc_html__( 'Results for “%s”', 'benders-hub' ), esc_html( get_search_query() ) ),
	/* translators: %d: number of results */
	'desc'    => sprintf( _n( '%d article found.', '%d articles found.', $count, 'benders-hub' ), $count ),
) );
echo '<div class="bdh-container bdh-search-again">';
get_search_form();
echo '</div>';
get_template_part( 'template-parts/post-list', null, array( 'lead' => false ) );
get_footer();
