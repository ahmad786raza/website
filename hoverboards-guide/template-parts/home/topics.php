<?php
/**
 * C. Browse by topic: the eight hoverboard hubs.
 */
$hubs = hbg_topic_hubs();
?>
<section class="section section--alt topics" id="topics" aria-labelledby="topics-title">
	<div class="container">
		<?php hbg_section_heading( array( 'id' => 'topics-title', 'kicker' => __( 'Browse by topic', 'hoverboards-guide' ), 'title' => __( 'Start with the rider and the ground they’ll ride on', 'hoverboards-guide' ), 'sub' => __( 'Guides grouped the way people shop: who it is for, where it will be used, what it should cost and how to keep it safe.', 'hoverboards-guide' ) ) ); ?>
		<div class="topics__grid">
			<?php foreach ( $hubs as $slug => $hub ) { hbg_topic_tile( $slug, $hub ); } ?>
		</div>
	</div>
</section>
