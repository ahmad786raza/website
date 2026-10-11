<?php
/**
 * Article card used in grids (home, archives, search, related, load-more).
 *
 * @param array $args { 'heading' => 'h3', 'compact' => false }
 */
$args    = isset( $args ) && is_array( $args ) ? $args : array();
$heading = isset( $args['heading'] ) && in_array( $args['heading'], array( 'h2', 'h3', 'h4' ), true ) ? $args['heading'] : 'h3';
$compact = ! empty( $args['compact'] );
$feature = ! empty( $args['feature'] );
$sizes   = $feature ? '(max-width: 1024px) 100vw, 800px' : '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 400px';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' . ( $compact ? ' card--compact' : '' ) . ( $feature ? ' card--feature' : '' ) ); ?>>
	<a href="<?php the_permalink(); ?>" class="card__media" tabindex="-1" aria-hidden="true">
		<?php hbg_post_thumbnail( 'hbg-card', array( 'class' => 'card__img', 'sizes' => $sizes ) ); ?>
	</a>
	<div class="card__body">
		<?php hbg_cat_badge( null, false, 'cat-badge--sm' ); ?>
		<<?php echo $heading; // phpcs:ignore ?> class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $heading; // phpcs:ignore ?>>
		<?php if ( ! $compact ) : ?><p class="card__excerpt"><?php echo esc_html( hbg_excerpt( 20 ) ); ?></p><?php endif; ?>
		<?php hbg_post_meta( array( 'updated' => true, 'views' => true ) ); ?>
	</div>
</article>
