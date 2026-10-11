<?php
/**
 * FAQ accordion (native <details>, no JS needed).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = bdh_option( 'faq_title', __( 'Tube bending questions, answered', 'benders-hub' ) );
$sub   = bdh_option( 'faq_sub', __( 'Short answers to the questions readers ask most often.', 'benders-hub' ) );
$faqs  = bdh_get_faqs();
if ( 'Fabrication FAQ' === $title ) { $title = __( 'Tube bending questions, answered', 'benders-hub' ); }
?>
<section class="bdh-section bdh-faq" id="bdh-faq" aria-labelledby="bdh-faq-title">
	<div class="bdh-container bdh-faq-inner">
		<header class="bdh-section-head">
			<p class="bdh-eyebrow"><?php esc_html_e( 'FAQ', 'benders-hub' ); ?></p>
			<h2 class="bdh-section-title" id="bdh-faq-title"><?php echo esc_html( $title ); ?></h2>
			<p class="bdh-section-sub"><?php echo esc_html( $sub ); ?></p>
		</header>
		<div class="bdh-faq-list">
		<?php foreach ( $faqs as $i => $faq ) : ?>
			<details class="bdh-faq-item"<?php echo 0 === $i ? ' open' : ''; ?>>
				<summary class="bdh-faq-q"><span><?php echo esc_html( $faq['q'] ); ?></span><?php echo bdh_icon( 'chevron', 'bdh-icon bdh-faq-chev' ); ?></summary>
				<div class="bdh-faq-a"><p><?php echo wp_kses_post( $faq['a'] ); ?></p></div>
			</details>
		<?php endforeach; ?>
		</div>
	</div>
</section>
