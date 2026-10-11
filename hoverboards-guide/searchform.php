<?php
$hbg_search_id = 'search-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $hbg_search_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'hoverboards-guide' ); ?></label>
	<div class="search-form__field">
		<?php echo hbg_svg_icon( 'search', 18, 'search-form__icon' ); // phpcs:ignore ?>
		<input type="search" id="<?php echo esc_attr( $hbg_search_id ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'Search reviews, guides, models…', 'hoverboards-guide' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off">
	</div>
	<button type="submit" class="search-submit btn btn--dark"><?php esc_html_e( 'Search', 'hoverboards-guide' ); ?></button>
</form>
