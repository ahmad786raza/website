<?php
/**
 * Search form (unique field id per instance).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bdh_sid = wp_unique_id( 'bdh-search-' );
?>
<form role="search" method="get" class="bdh-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="bdh-screen-reader-text" for="<?php echo esc_attr( $bdh_sid ); ?>"><?php esc_html_e( 'Search Benders Hub', 'benders-hub' ); ?></label>
	<?php echo bdh_icon( 'search', 'bdh-icon bdh-search-icon' ); ?>
	<input id="<?php echo esc_attr( $bdh_sid ); ?>" type="search" name="s" placeholder="<?php esc_attr_e( 'Search guides, benders, calculators…', 'benders-hub' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
	<button type="submit" class="bdh-btn bdh-btn-primary"><?php esc_html_e( 'Search', 'benders-hub' ); ?></button>
</form>
