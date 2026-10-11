<?php
/**
 * Empty state for archives and search.
 */
?>
<div class="no-results">
	<div class="no-results__icon" aria-hidden="true"><?php echo hbg_svg_icon( 'search', 28 ); // phpcs:ignore ?></div>
	<h2 class="no-results__title"><?php esc_html_e( 'Nothing found', 'hoverboards-guide' ); ?></h2>
	<p><?php esc_html_e( 'Try a different search, or jump into one of the popular topics below.', 'hoverboards-guide' ); ?></p>
	<?php get_search_form(); ?>
	<div class="no-results__chips">
		<?php foreach ( get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 6 ) ) as $c ) : ?>
			<a class="chip" href="<?php echo esc_url( get_category_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a>
		<?php endforeach; ?>
	</div>
</div>
