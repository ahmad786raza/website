<?php
/**
 * FAQ Accordion.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = bdh_option( 'faq_title', __( 'Fabrication FAQ', 'benders-hub' ) );
$sub   = bdh_option( 'faq_sub',   __( 'Common bender, tooling, and workflow questions.', 'benders-hub' ) );
$faqs  = bdh_get_faqs();
?>
<section class="bdh-section bdh-faq" id="bdh-faq" data-bdh-section="faq">
	<div class="bdh-container bdh-faq-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Questions answered', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="bdh-section-sub"><?php echo esc_html( $sub ); ?></p>
		</header>
		<div class="bdh-faq-list" data-bdh-faq>
		<?php foreach ( $faqs as $i => $faq ) : ?>
			<details class="bdh-faq-item"<?php echo $i === 0 ? ' open' : ''; ?>>
				<summary class="bdh-faq-q">
					<span><?php echo esc_html( $faq['q'] ); ?></span>
					<span class="bdh-faq-toggle" aria-hidden="true">+</span>
				</summary>
				<div class="bdh-faq-a">
					<p><?php echo wp_kses_post( $faq['a'] ); ?></p>
				</div>
			</details>
		<?php endforeach; ?>
		</div>
	</div>
</section>
