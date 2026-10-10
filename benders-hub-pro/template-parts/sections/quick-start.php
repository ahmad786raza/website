<?php
/**
 * Quick Start Paths.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = bdh_option( 'quickstart_title', __( 'Pick your fabrication path', 'benders-hub' ) );
$sub   = bdh_option( 'quickstart_sub',   __( 'Six entry points to the right tools, dies, and workflow for your shop.', 'benders-hub' ) );
?>
<section class="bdh-section bdh-quickstart" id="bdh-quickstart" data-bdh-section="quick_start">
	<div class="bdh-container">
		<header class="bdh-section-head">
			<span class="bdh-section-tag"><?php esc_html_e( 'Start here', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="bdh-section-sub"><?php echo esc_html( $sub ); ?></p>
		</header>
		<div class="bdh-quickstart-grid">
		<?php foreach ( bdh_quick_start_paths() as $path ) : ?>
			<a class="bdh-quickstart-card" href="<?php echo esc_url( bdh_amazon_url( $path['keyword'] ) ); ?>" rel="nofollow sponsored noopener" target="_blank">
				<span class="bdh-quickstart-icon"><?php echo bdh_icon( $path['icon'] ); ?></span>
				<span class="bdh-quickstart-label"><?php echo esc_html( $path['label'] ); ?></span>
				<span class="bdh-quickstart-arrow"><?php echo bdh_icon( 'arrow' ); ?></span>
			</a>
		<?php endforeach; ?>
		</div>
	</div>
</section>
