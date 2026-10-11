<div class="no-results">
	<h2 class="no-results__title"><?php esc_html_e( 'Nothing found', 'hoverboards-guide' ); ?></h2>
	<p><?php esc_html_e( 'Try a different search, or start from one of the topics below.', 'hoverboards-guide' ); ?></p>
	<?php get_search_form(); ?>
	<div class="no-results__chips">
		<?php foreach ( hbg_topic_hubs() as $slug => $hub ) : $l = hbg_hub_link( $slug ); if ( $l['url'] ) : ?>
			<a class="chip" href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $hub['name'] ); ?></a>
		<?php endif; endforeach; ?>
	</div>
</div>
