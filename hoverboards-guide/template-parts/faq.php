<?php
$faqs = hbg_get_faqs();
?>
<section class="faq-section section" id="faq" aria-labelledby="faq-heading">
	<div class="container faq-section__inner">
		<div class="faq-section__intro">
			<?php hbg_section_heading( array(
				'id'     => 'faq-heading',
				'kicker' => __( 'FAQ', 'hoverboards-guide' ),
				'title'  => __( 'Questions, answered', 'hoverboards-guide' ),
				'sub'    => __( 'The things readers ask us most often before buying.', 'hoverboards-guide' ),
			) ); ?>
			<a href="#quiz" class="btn btn--outline btn--sm"><?php esc_html_e( 'Still unsure? Take the quiz', 'hoverboards-guide' ); ?></a>
		</div>
		<div class="faq-list">
			<?php foreach ( $faqs as $i => $faq ) : ?>
			<div class="faq-item">
				<button type="button" class="faq-item__trigger" aria-expanded="false" aria-controls="faq-body-<?php echo esc_attr( $i ); ?>" id="faq-<?php echo esc_attr( $i ); ?>">
					<span><?php echo esc_html( $faq['q'] ); ?></span>
					<span class="faq-item__icon" aria-hidden="true">+</span>
				</button>
				<div class="faq-item__body" id="faq-body-<?php echo esc_attr( $i ); ?>" role="region" aria-labelledby="faq-<?php echo esc_attr( $i ); ?>">
					<div class="faq-item__answer"><?php echo esc_html( $faq['a'] ); ?></div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
