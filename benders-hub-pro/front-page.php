<?php
/**
 * Front Page — hybrid magazine: lead stories, engineering tools, guides,
 * equipment advice and a live article feed.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

// Posts already shown on this page, so sections never repeat an article.
$GLOBALS['bdh_shown_ids'] = array();

$section_map = array(
	'hero'        => 'template-parts/home/hero',
	'tools'       => 'template-parts/home/tools',
	'guides'      => 'template-parts/home/guides',
	'equipment'   => 'template-parts/home/equipment',
	'latest'      => 'template-parts/home/latest',
	'pathways'    => 'template-parts/home/pathways',
	'knowledge'   => 'template-parts/home/knowledge',
	'curated'     => 'template-parts/home/curated',
	'faq'         => 'template-parts/sections/faq',
	'newsletter'  => 'template-parts/sections/newsletter',
	'trust'       => 'template-parts/sections/trust',
	'kit_bundles' => 'template-parts/sections/kit-bundles',
);
?>
<div class="bdh-home-wrap">
<?php
foreach ( bdh_get_homepage_sections() as $key => $cfg ) {
	if ( empty( $cfg['enabled'] ) || ! isset( $section_map[ $key ] ) ) { continue; }
	get_template_part( $section_map[ $key ] );
}
?>
</div>
<?php get_footer(); ?>
