<?php
/**
 * Homepage Section I — "Essential reads". An editor's selection, not a
 * popularity ranking (the theme has no reliable analytics to rank by).
 * Customizer → Benders Hub → Magazine → Essential reads (post IDs).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$ids = array_filter( array_map( 'absint', explode( ',', (string) get_theme_mod( 'bdh_curated_posts', '' ) ) ) );
if ( ! $ids ) {
	// Default selection: the foundational explainers, when they exist.
	foreach ( array( 'bend-radius-explained', 'tube-wrinkling-prevention', 'mandrel-vs-rotary', 'best-hydraulic-tube-bender', 'tube-notching-guide', 'roll-cage-specifications' ) as $slug ) {
		$p = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $p && 'publish' === $p->post_status ) { $ids[] = (int) $p->ID; }
	}
}
$ids = array_values( array_filter( $ids, function ( $id ) { return 'publish' === get_post_status( $id ); } ) );
if ( count( $ids ) < 3 ) { return; }
$ids = array_slice( $ids, 0, 6 );
?>
<section class="bdh-section bdh-curated" id="bdh-curated" aria-labelledby="bdh-curated-title">
	<div class="bdh-container bdh-curated-inner">
		<header class="bdh-section-head">
			<p class="bdh-eyebrow"><?php esc_html_e( 'Start here', 'benders-hub' ); ?></p>
			<h2 class="bdh-section-title" id="bdh-curated-title"><?php esc_html_e( 'Essential reads', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Selected by our editors as the best foundation for new fabricators.', 'benders-hub' ); ?></p>
		</header>
		<ol class="bdh-curated-list">
			<?php foreach ( $ids as $id ) : ?>
				<li>
					<a href="<?php echo esc_url( get_permalink( $id ) ); ?>">
						<span class="bdh-curated-title"><?php echo esc_html( get_the_title( $id ) ); ?></span>
						<?php $cat = bdh_primary_category( $id ); if ( $cat ) : ?>
							<span class="bdh-curated-cat"><?php echo esc_html( $cat->name ); ?></span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
