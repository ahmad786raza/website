<?php
/**
 * G. Editorial transparency: what the site covers, how advice is developed, links to policies.
 */
$headline = get_theme_mod( 'hbg_trust_headline', __( 'How this guide is put together', 'hoverboards-guide' ) );
$text     = get_theme_mod( 'hbg_trust_text', __( 'Hoverboards Guide covers hoverboards and self-balancing boards for kids, teens and adults, plus electric scooters and other small electric rides. Our recommendations are desk research: we compare manufacturer specifications, safety certification, published recalls and owner feedback, and we update guides when models change. We do not run a test lab, and we say so when we have not ridden a board. If you buy through a link marked as an Amazon link, we may earn a commission at no extra cost to you.', 'hoverboards-guide' ) );
$links    = array(
	'about'                => __( 'About us', 'hoverboards-guide' ),
	'contact'              => __( 'Contact', 'hoverboards-guide' ),
	'affiliate-disclosure' => __( 'Affiliate disclosure', 'hoverboards-guide' ),
	'editorial-policy'     => __( 'Editorial policy', 'hoverboards-guide' ),
	'privacy-policy'       => __( 'Privacy policy', 'hoverboards-guide' ),
	'terms'                => __( 'Terms', 'hoverboards-guide' ),
);
?>
<section class="section section--alt trust" id="about" aria-labelledby="trust-title">
	<div class="container trust__inner">
		<div class="trust__text">
			<div class="section-heading__kicker"><?php esc_html_e( 'Editorial transparency', 'hoverboards-guide' ); ?></div>
			<h2 class="trust__title" id="trust-title"><?php echo esc_html( $headline ); ?></h2>
			<p class="trust__p"><?php echo esc_html( $text ); ?></p>
			<ul class="trust__method">
				<li><?php echo hbg_svg_icon( 'list', 20 ); // phpcs:ignore ?><span><strong><?php esc_html_e( 'Specifications compared', 'hoverboards-guide' ); ?></strong><?php esc_html_e( 'Manufacturer figures, line by line, labelled advertised or verified.', 'hoverboards-guide' ); ?></span></li>
				<li><?php echo hbg_svg_icon( 'shield', 20 ); // phpcs:ignore ?><span><strong><?php esc_html_e( 'Safety checked', 'hoverboards-guide' ); ?></strong><?php esc_html_e( 'UL 2272 status and published recalls, before any pick.', 'hoverboards-guide' ); ?></span></li>
				<li><?php echo hbg_svg_icon( 'refresh', 20 ); // phpcs:ignore ?><span><strong><?php esc_html_e( 'Kept current', 'hoverboards-guide' ); ?></strong><?php esc_html_e( 'Guides are updated when models change.', 'hoverboards-guide' ); ?></span></li>
				<li><?php echo hbg_svg_icon( 'info', 20 ); // phpcs:ignore ?><span><strong><?php esc_html_e( 'Honest limits', 'hoverboards-guide' ); ?></strong><?php esc_html_e( 'No test lab; we say when we have not ridden a board.', 'hoverboards-guide' ); ?></span></li>
			</ul>
		</div>
		<ul class="trust__links">
			<?php
			foreach ( $links as $slug => $label ) {
				$url = hbg_page_url( $slug );
				if ( ! $url && 'privacy-policy' === $slug ) {
					$url = get_privacy_policy_url();
				}
				if ( $url ) {
					echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . ' ' . hbg_svg_icon( 'arrow-right', 14 ) . '</a></li>'; // phpcs:ignore
				}
			}
			?>
		</ul>
	</div>
</section>
