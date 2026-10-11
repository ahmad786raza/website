<?php
/**
 * Standard article card (image above, label, headline, excerpt, meta). Variants:
 *  - 'feature' : the largest card (latest section, archive lead) — wide image, bigger headline, longer excerpt
 *  - 'lead'    : a zone lead — 3:2 image, 24 px headline
 *  - compact   : four-up rows and tight grids — no excerpt
 *
 * @param array $args { 'heading' => 'h3', 'variant' => '', 'compact' => false, 'feature' => false }
 */
$args    = isset( $args ) && is_array( $args ) ? $args : array();
$heading = isset( $args['heading'] ) && in_array( $args['heading'], array( 'h2', 'h3', 'h4' ), true ) ? $args['heading'] : 'h3';
$variant = isset( $args['variant'] ) ? $args['variant'] : '';
$feature = 'feature' === $variant || ! empty( $args['feature'] );
$lead    = 'lead' === $variant;
$compact = ! empty( $args['compact'] ) && ! $feature && ! $lead;
$size    = ( $feature || $lead ) ? 'large' : 'hbg-card';
$sizes   = $feature ? '(max-width: 1024px) 100vw, 820px' : ( $lead ? '(max-width: 1024px) 100vw, 700px' : '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 400px' );
$words   = $feature ? 30 : ( $lead ? 24 : 18 );
$classes = 'card' . ( $compact ? ' card--compact' : '' ) . ( $feature ? ' card--feature' : '' ) . ( $lead ? ' card--lead' : '' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<a href="<?php the_permalink(); ?>" class="card__media" tabindex="-1" aria-hidden="true">
		<?php hbg_post_thumbnail( $size, array( 'class' => 'card__img', 'sizes' => $sizes, 'prefer_photo' => $feature || $lead ) ); ?>
	</a>
	<div class="card__body">
		<?php hbg_cat_badge( null, true, 'label' ); ?>
		<<?php echo $heading; // phpcs:ignore ?> class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $heading; // phpcs:ignore ?>>
		<?php if ( ! $compact ) : ?><p class="card__excerpt"><?php echo esc_html( hbg_excerpt( $words ) ); ?></p><?php endif; ?>
		<?php hbg_post_meta( array( 'updated' => true, 'views' => true ) ); ?>
	</div>
</article>
