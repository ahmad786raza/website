<?php
$questions   = hbg_get_quiz_questions();
$total_steps = count( $questions );
$default     = hbg_get_product( 'hiboy-s2-pro' );
if ( ! $default ) {
	$all     = hbg_get_products();
	$default = reset( $all );
}
?>
<section class="quiz-section section" id="quiz" aria-labelledby="quiz-heading">
	<div class="container">
		<div class="quiz-card">
			<div class="quiz-card__orb" aria-hidden="true"></div>

			<div class="quiz-card__intro">
				<div class="quiz-card__kicker"><?php esc_html_e( '60-second quiz', 'hoverboards-guide' ); ?></div>
				<h2 class="quiz-card__title" id="quiz-heading"><?php esc_html_e( 'Find your perfect ride in 3 questions', 'hoverboards-guide' ); ?></h2>
				<p class="quiz-card__sub"><?php esc_html_e( 'Tell us who’s riding, where and on what budget — we’ll match you with the best pick from our tested shortlist.', 'hoverboards-guide' ); ?></p>
				<div class="quiz-progress" aria-hidden="true">
					<?php for ( $i = 0; $i < $total_steps; $i++ ) : ?>
						<div class="quiz-progress__bar<?php echo 0 === $i ? ' active' : ''; ?>"></div>
					<?php endfor; ?>
				</div>
			</div>

			<div class="quiz-panel">
				<?php foreach ( $questions as $i => $question ) : ?>
				<div class="quiz-step<?php echo 0 === $i ? ' active' : ''; ?>" data-step="<?php echo esc_attr( $i ); ?>" data-key="<?php echo esc_attr( $question['key'] ); ?>">
					<div class="quiz-step-label"><?php printf( esc_html__( 'Step %1$d of %2$d', 'hoverboards-guide' ), (int) $i + 1, (int) $total_steps ); ?></div>
					<h3 class="quiz-question"><?php echo esc_html( $question['q'] ); ?></h3>
					<div class="quiz-options" role="group" aria-label="<?php echo esc_attr( $question['q'] ); ?>">
						<?php foreach ( $question['options'] as $opt ) : ?>
							<button type="button" class="quiz-option-btn" data-value="<?php echo esc_attr( $opt['value'] ); ?>">
								<span><?php echo esc_html( $opt['label'] ); ?></span>
								<?php echo hbg_svg_icon( 'arrow-right', 16 ); // phpcs:ignore ?>
							</button>
						<?php endforeach; ?>
					</div>
					<?php if ( $i > 0 ) : ?>
						<button type="button" class="quiz-back-btn" data-quiz-back>← <?php esc_html_e( 'Back', 'hoverboards-guide' ); ?></button>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>

				<div class="quiz-result" aria-live="polite">
					<div class="quiz-result__kicker"><?php esc_html_e( 'Your match', 'hoverboards-guide' ); ?></div>
					<div class="quiz-result__image" data-quiz-result-image>
						<?php hbg_product_image( $default, array( 'class' => 'product-image', 'ratio' => '16/10', 'sizes' => '(max-width: 640px) 100vw, 420px' ) ); ?>
					</div>
					<h3 class="quiz-result__name" data-quiz-result-name><?php echo esc_html( $default['name'] ); ?></h3>
					<p class="quiz-result__tagline" data-quiz-result-tagline><?php echo esc_html( $default['tagline'] ); ?></p>
					<div class="quiz-result__specs" data-quiz-result-specs>
						<span><strong><?php echo esc_html( $default['range'] ); ?></strong> <?php esc_html_e( 'range', 'hoverboards-guide' ); ?></span>
						<span><strong><?php echo esc_html( $default['speed'] ); ?></strong> <?php esc_html_e( 'top speed', 'hoverboards-guide' ); ?></span>
						<span><strong><?php echo esc_html( $default['weight_cap'] ); ?></strong> <?php esc_html_e( 'max rider', 'hoverboards-guide' ); ?></span>
					</div>
					<a href="<?php echo esc_url( hbg_amazon_url( $default['search'] ) ); ?>" class="cta-amazon cta-amazon--md cta-amazon--full" target="_blank" rel="sponsored nofollow noopener" data-quiz-result-link>
						<span><?php esc_html_e( 'Check price on Amazon', 'hoverboards-guide' ); ?></span>
						<?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?>
					</a>
					<button type="button" class="quiz-retake-btn"><?php echo hbg_svg_icon( 'refresh', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Retake quiz', 'hoverboards-guide' ); ?></button>
				</div>
			</div>
		</div>
	</div>
</section>
