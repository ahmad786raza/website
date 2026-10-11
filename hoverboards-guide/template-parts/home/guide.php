<?php
/**
 * D. Featured buying guide: the decisions it covers and a link to the full guide.
 */
$post = hbg_post_by_slug( hbg_key_posts()['buying_guide'] );
if ( ! $post ) {
	return;
}
$decisions = hbg_guide_decisions();
$GLOBALS['post'] = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
setup_postdata( $post );
?>
<section class="section section--tint-cyan guide" id="guide" aria-labelledby="guide-title">
	<div class="container">
		<?php hbg_section_heading( array( 'id' => 'guide-title', 'kicker' => __( 'Featured buying guide', 'hoverboards-guide' ), 'title' => __( 'Seven decisions, one guide', 'hoverboards-guide' ), 'sub' => __( 'The hoverboard buying guide walks through every choice that changes what you should buy, in plain English.', 'hoverboards-guide' ) ) ); ?>
		<div class="guide__grid">
			<article class="guide__card">
				<a href="<?php the_permalink(); ?>" class="guide__media" tabindex="-1" aria-hidden="true"><?php hbg_post_thumbnail( 'hbg-card', array( 'class' => 'guide__img', 'sizes' => '(max-width: 900px) 100vw, 520px' ) ); ?></a>
				<div class="guide__body">
					<?php hbg_cat_badge(); ?>
					<h3 class="guide__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p class="guide__excerpt"><?php echo esc_html( hbg_excerpt( 34 ) ); ?></p>
					<?php hbg_post_meta( array( 'updated' => true ) ); ?>
					<a href="<?php the_permalink(); ?>" class="btn btn--primary"><?php esc_html_e( 'Read the buying guide', 'hoverboards-guide' ); ?></a>
				</div>
			</article>
			<ol class="decisions">
				<?php foreach ( $decisions as $d ) : ?>
				<li class="decision">
					<span class="decision__icon" aria-hidden="true"><?php echo hbg_svg_icon( $d['icon'], 18 ); // phpcs:ignore ?></span>
					<span class="decision__text"><strong><?php echo esc_html( $d['title'] ); ?></strong> <?php echo esc_html( $d['text'] ); ?></span>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
