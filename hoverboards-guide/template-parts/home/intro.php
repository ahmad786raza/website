<?php
/**
 * Intro line: the homepage H1 and a one-sentence description of what the publication covers.
 */
$headline = get_theme_mod( 'hbg_hero_headline', __( 'Find the Right Hoverboard for Your Ride', 'hoverboards-guide' ) );
$sub      = get_theme_mod( 'hbg_hero_subhead', __( 'Compare hoverboards for kids, teens and adults with practical buying advice on safety, range, terrain and value.', 'hoverboards-guide' ) );
?>
<div class="home-intro">
	<div class="container home-intro__inner">
		<h1 class="home-intro__title"><?php echo esc_html( $headline ); ?></h1>
		<?php if ( $sub ) : ?><p class="home-intro__sub"><?php echo esc_html( $sub ); ?></p><?php endif; ?>
	</div>
</div>
