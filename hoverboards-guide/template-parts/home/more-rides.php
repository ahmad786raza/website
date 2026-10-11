<?php
$rides = array();
foreach ( hbg_more_rides() as $ride ) {
	$t = get_term_by( 'slug', $ride['slug'], 'category' );
	if ( $t && $t->count > 0 ) {
		$rides[] = array( 'term' => $t, 'label' => $ride['label'] );
	}
}
if ( ! $rides ) {
	return;
}
?>
<section class="section section--sm section--neutral more-rides" aria-labelledby="more-rides-title">
	<div class="container">
		<h2 class="more-rides__title" id="more-rides-title"><?php esc_html_e( 'Also on Hoverboards Guide', 'hoverboards-guide' ); ?></h2>
		<ul class="more-rides__list">
			<?php foreach ( $rides as $r ) : ?>
				<li><a href="<?php echo esc_url( get_category_link( $r['term'] ) ); ?>"><?php echo esc_html( $r['label'] ); ?> <span class="count"><?php echo esc_html( number_format_i18n( $r['term']->count ) ); ?></span></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
