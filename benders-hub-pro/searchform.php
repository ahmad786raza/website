<?php
/**
 * Search Form.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<form role="search" method="get" class="bdh-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="bdh-screen-reader-text" for="bdh-search-field"><?php esc_html_e( 'Search', 'benders-hub' ); ?></label>
	<input id="bdh-search-field" type="search" name="s" placeholder="<?php esc_attr_e( 'Search benders, calculators, guides…', 'benders-hub' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
	<button type="submit" class="bdh-btn bdh-btn-primary"><?php esc_html_e( 'Search', 'benders-hub' ); ?></button>
</form>
