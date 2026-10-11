<?php
/**
 * Front page — v3 magazine homepage.
 *
 * A hero → B quick recommendations → C browse by topic → D featured buying guide →
 * E reviews & comparisons → comparison table → F learn before you buy → H finder →
 * FAQ → G editorial transparency → more rides → newsletter (only when configured).
 * Every section can be switched off under Customize → Hoverboards Guide → Homepage: Sections.
 */
get_header();
?>

<main id="main" class="site-main site-main--home">

	<?php get_template_part( 'template-parts/home/hero' ); ?>

	<?php if ( is_active_sidebar( 'home-after-hero' ) ) : ?>
		<div class="container widget-row"><?php dynamic_sidebar( 'home-after-hero' ); ?></div>
	<?php endif; ?>

	<?php if ( get_theme_mod( 'hbg_show_picks', true ) ) { get_template_part( 'template-parts/home/picks' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_topics', true ) ) { get_template_part( 'template-parts/home/topics' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_guide', true ) ) { get_template_part( 'template-parts/home/guide' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_latest', true ) ) { get_template_part( 'template-parts/home/latest' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_compare', true ) ) { get_template_part( 'template-parts/compare-table' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_learn', true ) ) { get_template_part( 'template-parts/home/learn' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_finder', true ) ) { get_template_part( 'template-parts/home/finder' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_faq', true ) ) { get_template_part( 'template-parts/home/faq' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_trust', true ) ) { get_template_part( 'template-parts/home/trust' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_more_rides', true ) ) { get_template_part( 'template-parts/home/more-rides' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_newsletter', true ) && hbg_newsletter_enabled() ) : ?>
		<section class="section section--sm" aria-label="<?php esc_attr_e( 'Newsletter', 'hoverboards-guide' ); ?>"><div class="container"><?php hbg_newsletter_form( array( 'variant' => 'banner' ) ); ?></div></section>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'home-before-footer' ) ) : ?>
		<div class="container widget-row"><?php dynamic_sidebar( 'home-before-footer' ); ?></div>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
