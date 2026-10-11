<?php
/**
 * Browse by topic: one tabbed section for up to four categories.
 * Replaces the v2.0 story blocks + guides rows (four stacked post lists) with a single, shorter block.
 * Without JavaScript every panel is shown stacked.
 */
$candidates = array(
	hbg_resolve_category( 'hbg_cat_block_1', array( 'hoverboards' ) ),
	hbg_resolve_category( 'hbg_cat_block_2', array( 'e-scooters', 'scooters' ) ),
	hbg_resolve_category( 'hbg_cat_block_3', array( 'buying-guide', 'guide', 'how-to' ) ),
	hbg_resolve_category( 'hbg_cat_block_4', array( 'ebikes', 'skateboards', 'unicycles' ) ),
);
$exclude = hbg_featured_ids();
$tabs    = array();
$seen    = array();
foreach ( $candidates as $term ) {
	if ( ! $term || isset( $seen[ $term->term_id ] ) ) {
		continue;
	}
	$q = new WP_Query( array( 'cat' => $term->term_id, 'posts_per_page' => 4, 'post__not_in' => $exclude, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
	if ( ! $q->have_posts() ) {
		continue;
	}
	$seen[ $term->term_id ] = true;
	$preset = hbg_category_preset( $term );
	$blurb  = ! empty( $preset['blurb'] ) ? $preset['blurb'] : wp_strip_all_tags( category_description( $term ) );
	$tabs[] = array( 'term' => $term, 'query' => $q, 'color' => $preset['color'], 'blurb' => $blurb );
}
if ( count( $tabs ) < 2 ) {
	return;
}
?>
<section class="cat-tabs section section--alt" id="topics" aria-labelledby="topics-heading">
	<div class="container">
		<?php hbg_section_heading( array(
			'id'     => 'topics-heading',
			'kicker' => __( 'Browse by topic', 'hoverboards-guide' ),
			'title'  => __( 'Dig into what you ride', 'hoverboards-guide' ),
			'sub'    => __( 'Reviews, comparisons and how-tos, sorted by the ride you’re shopping for.', 'hoverboards-guide' ),
		) ); ?>

		<div class="tabs" data-tabs>
			<div class="tabs__list" role="tablist" aria-label="<?php esc_attr_e( 'Categories', 'hoverboards-guide' ); ?>">
				<?php foreach ( $tabs as $i => $tab ) : $slug = sanitize_html_class( $tab['term']->slug ); ?>
					<button type="button" role="tab" class="tab<?php echo 0 === $i ? ' is-active' : ''; ?>" id="topic-tab-<?php echo esc_attr( $slug ); ?>" aria-controls="topic-panel-<?php echo esc_attr( $slug ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" style="--cat:<?php echo esc_attr( $tab['color'] ); ?>">
						<?php echo esc_html( $tab['term']->name ); ?>
						<span class="tab__count"><?php echo esc_html( number_format_i18n( $tab['term']->count ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<?php foreach ( $tabs as $i => $tab ) : $slug = sanitize_html_class( $tab['term']->slug ); $q = $tab['query']; ?>
			<div class="tabs__panel<?php echo 0 === $i ? ' is-active' : ''; ?>" role="tabpanel" id="topic-panel-<?php echo esc_attr( $slug ); ?>" aria-labelledby="topic-tab-<?php echo esc_attr( $slug ); ?>" style="--cat:<?php echo esc_attr( $tab['color'] ); ?>">
				<div class="grid grid--4">
					<?php while ( $q->have_posts() ) : $q->the_post(); ?>
						<?php get_template_part( 'template-parts/content', 'card', array( 'compact' => true ) ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
				<div class="tabs__panel-foot">
					<?php if ( $tab['blurb'] ) : ?><p class="tabs__panel-sub"><?php echo esc_html( $tab['blurb'] ); ?></p><?php endif; ?>
					<a href="<?php echo esc_url( get_category_link( $tab['term'] ) ); ?>" class="section-heading__link">
						<?php
						/* translators: %s: category name */
						printf( esc_html__( 'All %s articles', 'hoverboards-guide' ), esc_html( $tab['term']->name ) );
						?>
						<?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?>
					</a>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
