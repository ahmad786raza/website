<?php
/**
 * F. Learn before you buy: six practical resources.
 */
$items = array();
foreach ( hbg_learn_items() as $it ) {
	$post = hbg_post_by_slug( $it['slug'] );
	if ( $post ) {
		$it['post'] = $post;
		$items[]    = $it;
	}
}
if ( ! $items ) {
	return;
}
$safety = hbg_hub_link( 'hoverboard-safety' );
?>
<section class="section section--tint-green learn" id="learn" aria-labelledby="learn-title">
	<div class="container">
		<?php hbg_section_heading( array( 'id' => 'learn-title', 'kicker' => __( 'Learn before you buy', 'hoverboards-guide' ), 'title' => __( 'Practical answers before you spend', 'hoverboards-guide' ), 'link' => $safety['url'], 'link_label' => __( 'All safety & how-to guides', 'hoverboards-guide' ) ) ); ?>
		<ul class="learn__grid">
			<?php foreach ( $items as $it ) : ?>
			<li class="learn__item">
				<span class="learn__icon" aria-hidden="true"><?php echo hbg_svg_icon( $it['icon'], 20 ); // phpcs:ignore ?></span>
				<span class="learn__body">
					<a href="<?php echo esc_url( get_permalink( $it['post'] ) ); ?>" class="learn__title"><?php echo esc_html( $it['label'] ); ?></a>
					<span class="learn__sub"><?php echo esc_html( hbg_excerpt( 16, $it['post'] ) ); ?></span>
				</span>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
