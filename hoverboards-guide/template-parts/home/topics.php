<?php
/**
 * Compact topic tiles: the eight hoverboard hubs in one row.
 */
$hubs = hbg_topic_hubs();
?>
<section class="topics topics--compact" id="topics" aria-labelledby="topics-title">
	<div class="container">
		<h2 class="topics__title" id="topics-title"><?php esc_html_e( 'Browse hoverboards by need', 'hoverboards-guide' ); ?></h2>
		<div class="topics__grid">
			<?php foreach ( $hubs as $slug => $hub ) { hbg_topic_tile( $slug, $hub ); } ?>
		</div>
	</div>
</section>
