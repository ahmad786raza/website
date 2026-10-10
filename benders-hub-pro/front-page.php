<?php
/**
 * Front Page — Homepage as a premium fabrication command center.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<div class="bdh-home-wrap">

<?php
$sections = bdh_get_homepage_sections();
$section_map = array(
	'hero'                => 'template-parts/sections/hero',
	'quick_start'         => 'template-parts/sections/quick-start',
	'quiz'                => 'template-parts/tools/quiz',
	'editors_best'        => 'template-parts/sections/editors-best',
	'archetypes'          => 'template-parts/sections/archetypes',
	'bend_calculator'     => 'template-parts/tools/calculator',
	'geometry_visualizer' => 'template-parts/tools/visualizer',
	'workflow_planner'    => 'template-parts/tools/workflow',
	'compatibility'       => 'template-parts/tools/compatibility',
	'prioritizer'         => 'template-parts/tools/prioritizer',
	'route_planner'       => 'template-parts/tools/tracker',
	'equipment_database'  => 'template-parts/tools/database',
	'kit_bundles'         => 'template-parts/sections/kit-bundles',
	'comparison'          => 'template-parts/sections/comparison',
	'faq'                 => 'template-parts/sections/faq',
	'newsletter'          => 'template-parts/sections/newsletter',
	'trust'               => 'template-parts/sections/trust',
);

foreach ( $sections as $key => $cfg ) {
	if ( empty( $cfg['enabled'] ) ) { continue; }
	if ( ! isset( $section_map[ $key ] ) ) { continue; }
	get_template_part( $section_map[ $key ] );
}
?>

</div>

<?php get_footer(); ?>
