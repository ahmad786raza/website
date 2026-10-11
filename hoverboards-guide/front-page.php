<?php
/**
 * Front page — v4 editorial magazine composition.
 *
 * Intro line (the page's H1) → featured-story mosaic → top picks panel → latest + side modules →
 * Hoverboards zone → topic tiles → Product reviews zone → comparison table → finder band →
 * Buying guides → Safety & maintenance → Electric scooters → Skateboards & other rideables →
 * editorial transparency → newsletter (only when a service is configured).
 * Every block can be switched off under Customize → Hoverboards Guide → Homepage: Sections.
 */
get_header();
?>

<main id="main" class="site-main site-main--home">

	<?php get_template_part( 'template-parts/home/intro' ); ?>
	<?php if ( get_theme_mod( 'hbg_show_mosaic', true ) ) { get_template_part( 'template-parts/home/mosaic' ); } ?>

	<?php if ( is_active_sidebar( 'home-after-hero' ) ) : ?>
		<div class="container widget-row"><?php dynamic_sidebar( 'home-after-hero' ); ?></div>
	<?php endif; ?>

	<?php if ( get_theme_mod( 'hbg_show_picks', true ) ) { get_template_part( 'template-parts/home/picks' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_latest', true ) ) { get_template_part( 'template-parts/home/latest' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_zones', true ) ) { hbg_render_zone( 'hoverboards' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_topics', true ) ) { get_template_part( 'template-parts/home/topics' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_zones', true ) ) { hbg_render_zone( 'reviews' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_compare', true ) ) { get_template_part( 'template-parts/compare-table' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_finder', true ) ) { get_template_part( 'template-parts/home/finder' ); } ?>
	<?php
	if ( get_theme_mod( 'hbg_show_zones', true ) ) {
		hbg_render_zone( 'buying-guides' );
		hbg_render_zone( 'safety' );
		hbg_render_zone( 'scooters' );
		hbg_render_zone( 'rideables' );
	}
	?>
	<?php if ( get_theme_mod( 'hbg_show_guide', false ) ) { get_template_part( 'template-parts/home/guide' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_learn', false ) ) { get_template_part( 'template-parts/home/learn' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_faq', false ) ) { get_template_part( 'template-parts/home/faq' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_trust', true ) ) { get_template_part( 'template-parts/home/trust' ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_more_rides', false ) ) { get_template_part( 'template-parts/home/more-rides' ); } ?>

	<?php if ( get_theme_mod( 'hbg_show_newsletter', true ) && hbg_newsletter_enabled() ) : ?>
		<section class="section section--sm" aria-label="<?php esc_attr_e( 'Newsletter', 'hoverboards-guide' ); ?>"><div class="container"><?php hbg_newsletter_form( array( 'variant' => 'banner' ) ); ?></div></section>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'home-before-footer' ) ) : ?>
		<div class="container widget-row"><?php dynamic_sidebar( 'home-before-footer' ); ?></div>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
