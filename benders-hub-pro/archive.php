<?php
/**
 * Category, tag, author and date archives.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$eyebrow = is_category() ? __( 'Topic', 'benders-hub' ) : ( is_tag() ? __( 'Tag', 'benders-hub' ) : ( is_author() ? __( 'Author', 'benders-hub' ) : '' ) );
$desc    = get_the_archive_description();
if ( is_author() ) { $desc = get_the_author_meta( 'description', (int) get_query_var( 'author' ) ); }
get_template_part( 'template-parts/archive-header', null, array( 'eyebrow' => $eyebrow, 'desc' => $desc ) );
get_template_part( 'template-parts/post-list' );
get_footer();
