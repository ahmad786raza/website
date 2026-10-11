<?php
/**
 * A. Hero: benefit headline, what the site helps with, primary + secondary actions, one photo.
 */
$eyebrow   = get_theme_mod( 'hbg_hero_eyebrow', __( 'Hoverboard buying guides, reviews and safety', 'hoverboards-guide' ) );
$headline  = get_theme_mod( 'hbg_hero_headline', __( 'Find the Right Hoverboard for Your Ride', 'hoverboards-guide' ) );
$sub       = get_theme_mod( 'hbg_hero_subhead', __( 'Compare hoverboards for kids, teens and adults with practical buying advice on safety, range, terrain and value.', 'hoverboards-guide' ) );
$p_text    = get_theme_mod( 'hbg_hero_primary_text', __( 'Explore the best hoverboards', 'hoverboards-guide' ) );
$p_url     = get_theme_mod( 'hbg_hero_primary_url', '' );
$p_url     = $p_url ? $p_url : ( hbg_key_url( 'best' ) ? hbg_key_url( 'best' ) : hbg_key_url( 'buying_guide' ) );
$s_text    = get_theme_mod( 'hbg_hero_secondary_text', __( 'Find your ideal ride', 'hoverboards-guide' ) );
$s_url     = get_theme_mod( 'hbg_hero_secondary_url', '' );
$s_url     = $s_url ? $s_url : ( get_theme_mod( 'hbg_show_finder', true ) ? '#finder' : hbg_key_url( 'safety' ) );
$style     = get_theme_mod( 'hbg_hero_style', 'gradient' );
$points    = array_filter( array_map( 'trim', preg_split( '/\r?\n/', get_theme_mod( 'hbg_hero_points', __( "Picks by rider age, terrain and budget\nSafety: UL 2272, charging and protective gear\nTroubleshooting and maintenance fixes", 'hoverboards-guide' ) ) ) ) );
$image_id  = (int) get_theme_mod( 'hbg_hero_image', 0 );
$credit    = '';
if ( ! $image_id ) {
	$entry  = hbg_image_entry( 'cat-hoverboards' );
	$credit = $entry ? sprintf( /* translators: photographer */ __( 'Photo: %s', 'hoverboards-guide' ), $entry['by'] ) : '';
}
?>
<section class="hero<?php echo 'light' === $style ? ' hero--light' : ''; ?>" aria-labelledby="hero-title">
	<div class="container hero__inner">
		<div class="hero__text">
			<?php if ( $eyebrow ) : ?><p class="hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<h1 class="hero__title" id="hero-title"><?php echo esc_html( $headline ); ?></h1>
			<?php if ( $sub ) : ?><p class="hero__sub"><?php echo esc_html( $sub ); ?></p><?php endif; ?>
			<div class="hero__actions">
				<?php if ( $p_text && $p_url ) : ?><a href="<?php echo esc_url( $p_url ); ?>" class="btn btn--accent btn--lg"><?php echo esc_html( $p_text ); ?></a><?php endif; ?>
				<?php if ( $s_text && $s_url ) : ?><a href="<?php echo esc_url( $s_url ); ?>" class="btn btn--light btn--lg"><?php echo esc_html( $s_text ); ?> <?php echo hbg_svg_icon( 'arrow-right', 16 ); // phpcs:ignore ?></a><?php endif; ?>
			</div>
			<?php if ( $points ) : ?>
			<ul class="hero__points">
				<?php foreach ( $points as $pt ) : ?><li><?php echo hbg_svg_icon( 'check-circle', 18 ); // phpcs:ignore ?><span><?php echo esc_html( $pt ); ?></span></li><?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<figure class="hero__media">
			<?php if ( $image_id ) : ?>
				<?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'hero__img', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 900px) 100vw, 560px' ) ); ?>
				<?php $cap = wp_get_attachment_caption( $image_id ); if ( $cap ) : ?><figcaption class="hero__credit"><?php echo esc_html( $cap ); ?></figcaption><?php endif; ?>
			<?php else : ?>
				<?php hbg_image( 'cat-hoverboards', array( 'alt' => __( 'A rider’s feet on a hoverboard with lit wheels', 'hoverboards-guide' ), 'class' => 'hero__img', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 900px) 100vw, 560px' ) ); ?>
				<?php if ( $credit ) : ?><figcaption class="hero__credit"><?php echo esc_html( $credit ); ?></figcaption><?php endif; ?>
			<?php endif; ?>
		</figure>
	</div>
</section>
