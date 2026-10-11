<?php
/**
 * Kit Bundles section.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bundles = bdh_bundles();
?>
<section class="bdh-section bdh-bundles" id="bdh-bundles" data-bdh-section="kit_bundles">
	<div class="bdh-container">
		<header class="bdh-section-head">
			<p class="bdh-eyebrow"><?php esc_html_e( 'Shopping checklists', 'benders-hub' ); ?></p>
			<h2 class="bdh-section-title"><?php esc_html_e( 'What to buy for each kind of project', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Checklists of the equipment each kind of project usually needs. Each item opens an Amazon search so you can compare options.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-bundle-grid">
		<?php foreach ( $bundles as $bundle ) : ?>
			<article class="bdh-bundle-card" data-bundle="<?php echo esc_attr( $bundle['id'] ); ?>">
				<header class="bdh-bundle-header">
					<span class="bdh-bundle-icon"><?php echo bdh_icon( $bundle['icon'] ); ?></span>
					<div>
						<h3 class="bdh-bundle-name"><?php echo esc_html( $bundle['name'] ); ?></h3>
						<p class="bdh-bundle-summary"><?php echo esc_html( $bundle['summary'] ); ?></p>
					</div>
				</header>
				<ol class="bdh-bundle-items">
				<?php foreach ( $bundle['items'] as $item ) : ?>
					<li>
						<a href="<?php echo esc_url( bdh_amazon_url( $item['keyword'] ) ); ?>" rel="nofollow sponsored noopener" target="_blank">
							<?php echo bdh_icon( 'check' ); ?>
							<span><?php echo esc_html( $item['label'] ); ?></span>
							<?php echo bdh_icon( 'arrow', 'bdh-bundle-arrow' ); ?>
						</a>
					</li>
				<?php endforeach; ?>
				</ol>
				
			</article>
		<?php endforeach; ?>
		</div>
	</div>
	<div class="bdh-container"><?php bdh_inline_disclosure(); ?></div>
</section>
