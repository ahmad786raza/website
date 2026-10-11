<?php
$cta_headline = get_theme_mod( 'hbg_cta_headline', __( 'Ready to find your perfect ride?', 'hoverboards-guide' ) );
$cta_sub      = get_theme_mod( 'hbg_cta_sub', __( 'Free Prime shipping on most picks · 30-day returns · Prices re-checked weekly', 'hoverboards-guide' ) );
?>
<section class="final-cta-section section" aria-labelledby="final-cta-heading">
	<div class="container">
		<div class="final-cta-card">
			<div class="final-cta-card__pattern" aria-hidden="true"></div>
			<div class="final-cta-card__inner">
				<h2 class="final-cta-card__title" id="final-cta-heading"><?php echo esc_html( $cta_headline ); ?></h2>
				<p class="final-cta-card__sub"><?php echo esc_html( $cta_sub ); ?></p>
				<div class="final-cta-card__btns">
					<a href="#hoverboards" class="btn btn--dark btn--lg"><?php esc_html_e( 'Shop hoverboards', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 16 ); // phpcs:ignore ?></a>
					<a href="#scooters" class="btn btn--white btn--lg"><?php esc_html_e( 'Shop electric scooters', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 16 ); // phpcs:ignore ?></a>
				</div>
			</div>
		</div>
	</div>
</section>
