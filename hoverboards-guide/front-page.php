<?php
/**
 * Front page — v2.1 "Convert" layout.
 *
 * Order: hero + quick picks → category tiles → editor's top picks → featured stories → quiz →
 * comparison table → latest articles → most-read strip → browse-by-topic tabs → shop-by-use-case tabs →
 * buying guide → newsletter → FAQ → trust → final CTA.
 *
 * Every block can be switched on/off under Customize → Hoverboards Guide → Homepage Sections.
 * The v2.0 story blocks, guides rows and separate product rows are still available there as "Legacy" toggles.
 */
get_header();
?>

<main id="main" class="site-main site-main--home" role="main">

	<?php if ( get_theme_mod( 'hbg_show_intro', true ) ) { get_template_part( 'template-parts/home/intro' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_cat_strip', true ) ) { get_template_part( 'template-parts/home/category-strip' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_top_picks', true ) ) { get_template_part( 'template-parts/top-picks' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_featured', true ) ) { get_template_part( 'template-parts/home/featured' ); } ?>

	<?php if ( is_active_sidebar( 'home-after-featured' ) ) : ?>
		<div class="container widget-row"><?php dynamic_sidebar( 'home-after-featured' ); ?></div>
	<?php endif; ?>

	<?php if ( get_theme_mod( 'hbg_show_quiz', true ) ) { get_template_part( 'template-parts/quiz' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_compare', true ) ) { get_template_part( 'template-parts/compare-table' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_latest', true ) ) { get_template_part( 'template-parts/home/latest' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_most_read', true ) ) { get_template_part( 'template-parts/home/most-read' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_cat_tabs', true ) ) { get_template_part( 'template-parts/home/category-tabs' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_use_case', true ) ) { get_template_part( 'template-parts/use-case' ); } ?>

	<?php /* Legacy v2.0 rows — off by default since v2.1 (the tabs above replace them). */ ?>
	<?php if ( get_theme_mod( 'hbg_show_cat_blocks', false ) ) { get_template_part( 'template-parts/home/category-blocks' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_hoverboards', false ) ) { get_template_part( 'template-parts/category-section', 'hoverboards' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_scooters', false ) ) { get_template_part( 'template-parts/category-section', 'scooters' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_guides_block', false ) ) { get_template_part( 'template-parts/home/guides-block' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_guide', true ) ) { get_template_part( 'template-parts/buying-guide' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_newsletter', true ) ) { get_template_part( 'template-parts/newsletter-banner' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_faq', true ) ) { get_template_part( 'template-parts/faq' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_trust', true ) ) { get_template_part( 'template-parts/trust' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_final_cta', true ) ) { get_template_part( 'template-parts/final-cta' ); } ?>

	<?php if ( is_active_sidebar( 'home-before-footer' ) ) : ?>
		<div class="container widget-row"><?php dynamic_sidebar( 'home-before-footer' ); ?></div>
	<?php endif; ?>

</main>

<?php if ( get_theme_mod( 'hbg_show_sticky_cta', true ) && get_theme_mod( 'hbg_show_top_picks', true ) ) : ?>
<div class="sticky-mobile-cta" role="complementary" aria-label="<?php esc_attr_e( 'Quick shop', 'hoverboards-guide' ); ?>">
	<a href="#picks" class="sticky-mobile-cta__link"><?php echo hbg_svg_icon( 'bolt', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'Shop today’s top picks', 'hoverboards-guide' ); ?></a>
</div>
<?php endif; ?>

<?php if ( get_theme_mod( 'hbg_show_top_picks', true ) && get_theme_mod( 'hbg_show_compare', true ) ) : ?>
<div class="compare-drawer" id="compare-drawer" role="complementary" aria-label="<?php esc_attr_e( 'Product comparison', 'hoverboards-guide' ); ?>">
	<span class="compare-drawer__label"><?php echo hbg_svg_icon( 'compare', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Compare', 'hoverboards-guide' ); ?></span>
	<div class="compare-drawer__items"></div>
	<a href="#compare" class="compare-drawer__view"><?php esc_html_e( 'View comparison', 'hoverboards-guide' ); ?> →</a>
	<button class="compare-drawer__clear" type="button"><?php esc_html_e( 'Clear', 'hoverboards-guide' ); ?></button>
</div>
<?php endif; ?>

<?php get_footer(); ?>
