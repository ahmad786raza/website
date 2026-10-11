<?php
/**
 * Fallback listing (blog page).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$title = ( is_home() && ! is_front_page() ) ? single_post_title( '', false ) : __( 'Latest fabrication guides', 'benders-hub' );
get_template_part( 'template-parts/archive-header', null, array( 'title' => esc_html( $title ), 'desc' => __( 'Buying guides, technical explainers and workshop how-tos.', 'benders-hub' ), 'eyebrow' => __( 'Articles', 'benders-hub' ) ) );
get_template_part( 'template-parts/post-list' );
get_footer();
