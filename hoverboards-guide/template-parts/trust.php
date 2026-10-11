<?php
$trust_headline = get_theme_mod( 'hbg_trust_headline', __( 'Independent reviews. No paid placements. Ever.', 'hoverboards-guide' ) );
$trust_text     = get_theme_mod( 'hbg_trust_text', __( 'We research every product against owner reviews, spec sheets and safety recalls, ride what we can, and re-check prices every week. We earn a small commission on Amazon clicks — that funds the work, never the recommendation.', 'hoverboards-guide' ) );
$stats  = hbg_get_trust_stats();
$badges = hbg_get_trust_badges();
$about  = get_page_by_path( 'about' );
?>
<section class="trust-section section" id="trust" aria-labelledby="trust-heading">
	<div class="container">
		<div class="trust-card">
			<div class="trust-card__media" aria-hidden="true">
				<?php hbg_image( 'trust-testing', array( 'alt' => '', 'sizes' => '(max-width: 900px) 100vw, 480px' ) ); ?>
			</div>
			<div class="trust-card__body">
				<div class="trust-card__kicker"><?php esc_html_e( 'Why trust us', 'hoverboards-guide' ); ?></div>
				<h2 class="trust-card__title" id="trust-heading"><?php echo esc_html( $trust_headline ); ?></h2>
				<p class="trust-card__sub"><?php echo esc_html( $trust_text ); ?></p>
				<div class="trust-badges">
					<?php foreach ( $badges as $badge ) : ?>
						<span class="trust-badge"><?php echo hbg_svg_icon( 'check', 12 ); // phpcs:ignore ?> <?php echo esc_html( $badge ); ?></span>
					<?php endforeach; ?>
				</div>
				<?php if ( $stats ) : ?>
				<div class="trust-stats">
					<?php foreach ( $stats as $stat ) : ?>
						<div class="trust-stat">
							<div class="trust-stat__n"><?php echo esc_html( $stat['n'] ); ?></div>
							<div class="trust-stat__label"><?php echo esc_html( $stat['l'] ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if ( $about ) : ?>
					<a href="<?php echo esc_url( get_permalink( $about ) ); ?>" class="link-arrow"><?php esc_html_e( 'How we test & rate', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
