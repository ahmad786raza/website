<?php
/**
 * Post grid used by archive, index and search: first post as a lead card on page 1.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$lead_first = isset( $args['lead'] ) ? (bool) $args['lead'] : true;
?>
<div class="bdh-container bdh-listing">
	<?php if ( have_posts() ) : ?>
		<?php
		$i = 0;
		$paged = max( 1, (int) get_query_var( 'paged' ) );
		echo '<div class="bdh-card-grid">';
		while ( have_posts() ) :
			the_post();
			$variant = ( $lead_first && 0 === $i && 1 === $paged ) ? 'lead' : 'card';
			if ( 'lead' === $variant ) { echo '<div class="bdh-listing-lead">'; }
			bdh_post_card( get_the_ID(), $variant, 'h2' );
			if ( 'lead' === $variant ) { echo '</div>'; }
			$i++;
		endwhile;
		echo '</div>';
		bdh_pagination();
		?>
	<?php else : ?>
		<div class="bdh-empty">
			<p><?php esc_html_e( 'Nothing here yet.', 'benders-hub' ); ?></p>
			<?php get_search_form(); ?>
			<p><a class="bdh-btn bdh-btn-primary" href="<?php echo esc_url( bdh_tool_url( 'quiz' ) ); ?>"><?php esc_html_e( 'Find my bender', 'benders-hub' ); ?></a></p>
		</div>
	<?php endif; ?>
</div>
