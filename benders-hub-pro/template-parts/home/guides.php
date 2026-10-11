<?php
/**
 * Homepage Section D — featured buying guides.
 * Source: the "Buying guides" category (Customizer → Benders Hub → Magazine), falling
 * back to posts whose titles read like guides. Hidden when nothing qualifies.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$slug  = get_theme_mod( 'bdh_guides_category', 'guides' );
$term  = $slug ? get_category_by_slug( $slug ) : null;
$shown = isset( $GLOBALS['bdh_shown_ids'] ) ? $GLOBALS['bdh_shown_ids'] : array();
$args  = array( 'posts_per_page' => 4, 'post__not_in' => $shown, 'fields' => 'ids', 'no_found_rows' => true, 'ignore_sticky_posts' => true );

if ( $term ) {
	$ids = get_posts( $args + array( 'cat' => $term->term_id ) );
} else {
	$ids = array();
}
if ( count( $ids ) < 4 ) {
	// Top up with posts that are clearly selection guides.
	global $wpdb;
	$like  = array( '%guide%', '%choos%', '%best %', '% vs %' );
	$where = implode( ' OR ', array_fill( 0, count( $like ), 'post_title LIKE %s' ) );
	$extra = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' AND ( {$where} ) ORDER BY post_date DESC LIMIT 12", $like ) ); // phpcs:ignore WordPress.DB
	foreach ( $extra as $e ) {
		$e = (int) $e;
		if ( count( $ids ) >= 4 ) { break; }
		if ( ! in_array( $e, $ids, true ) && ! in_array( $e, $shown, true ) ) { $ids[] = $e; }
	}
}
if ( ! $ids ) { return; }
$GLOBALS['bdh_shown_ids'] = array_merge( $shown, $ids );
$lead = array_shift( $ids );
?>
<section class="bdh-section bdh-guides" id="bdh-guides" aria-labelledby="bdh-guides-title">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head--split">
			<div>
				<p class="bdh-eyebrow"><?php esc_html_e( 'Before you buy', 'benders-hub' ); ?></p>
				<h2 class="bdh-section-title" id="bdh-guides-title"><?php esc_html_e( 'Buying guides', 'benders-hub' ); ?></h2>
			</div>
			<?php if ( $term ) : ?>
				<a class="bdh-link-arrow" href="<?php echo esc_url( get_category_link( $term ) ); ?>"><?php esc_html_e( 'All buying guides', 'benders-hub' ); ?><?php echo bdh_icon( 'arrow' ); ?></a>
			<?php endif; ?>
		</header>
		<div class="bdh-guides-grid">
			<div class="bdh-guides-lead"><?php bdh_post_card( $lead, 'card', 'h3' ); ?></div>
			<div class="bdh-guides-list">
				<?php foreach ( $ids as $id ) { bdh_post_card( $id, 'list', 'h3' ); } ?>
			</div>
		</div>
	</div>
</section>
