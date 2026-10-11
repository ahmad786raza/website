<?php
$faqs = hbg_get_faqs();
if ( ! $faqs ) {
	return;
}
?>
<section class="section faq" id="faq" aria-labelledby="faq-title">
	<div class="container faq__inner">
		<div class="faq__intro">
			<?php hbg_section_heading( array( 'id' => 'faq-title', 'kicker' => __( 'Common questions', 'hoverboards-guide' ), 'title' => __( 'Before you buy', 'hoverboards-guide' ), 'sub' => __( 'Short answers; each links to the longer guides elsewhere on the site.', 'hoverboards-guide' ) ) ); ?>
		</div>
		<div class="faq__list">
			<?php foreach ( $faqs as $i => $faq ) : ?>
			<div class="faq__item">
				<h3 class="faq__q"><button type="button" class="faq__trigger" aria-expanded="false" aria-controls="faq-body-<?php echo (int) $i; ?>" id="faq-<?php echo (int) $i; ?>"><span><?php echo esc_html( $faq['q'] ); ?></span><?php echo hbg_svg_icon( 'chevron-down', 18, 'faq__chevron' ); // phpcs:ignore ?></button></h3>
				<div class="faq__body" id="faq-body-<?php echo (int) $i; ?>" role="region" aria-labelledby="faq-<?php echo (int) $i; ?>" hidden><p><?php echo esc_html( $faq['a'] ); ?></p></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
