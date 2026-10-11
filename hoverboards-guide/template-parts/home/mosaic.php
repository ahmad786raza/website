<?php
/**
 * Featured-story mosaic: one lead story, two supporting stories stacked beside it, then a strip of
 * four compact headlines. Stories come from the editorial featured list (Customize → Homepage).
 */
$posts = hbg_mosaic_posts( 7 );
if ( count( $posts ) < 3 ) {
	return;
}
$lead  = array_shift( $posts );
$side  = array_splice( $posts, 0, 2 );
$strip = array_slice( $posts, 0, 4 );
?>
<section class="mosaic" id="mosaic" aria-label="<?php esc_attr_e( 'Featured stories', 'hoverboards-guide' ); ?>">
	<div class="container">
		<div class="mosaic__grid">
			<?php hbg_story( $lead, array( 'variant' => 'lead', 'heading' => 'h2', 'size' => 'large', 'sizes' => '(max-width: 1024px) 100vw, 720px', 'eager' => true ) ); ?>
			<?php foreach ( $side as $p ) { hbg_story( $p, array( 'variant' => 'side', 'heading' => 'h3', 'size' => 'medium_large', 'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 480px', 'excerpt' => false ) ); } ?>
		</div>
		<?php if ( $strip ) : ?>
		<div class="mosaic__strip">
			<?php foreach ( $strip as $p ) { hbg_row( $p, array( 'class' => 'row--strip' ) ); } ?>
		</div>
		<?php endif; ?>
	</div>
</section>
