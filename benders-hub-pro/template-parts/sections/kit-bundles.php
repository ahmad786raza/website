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
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Build a workflow', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Pre-built fabrication kit bundles', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Curated bender + die + accessory bundles for every fabrication archetype. Click any item to view on Amazon.', 'benders-hub' ); ?></p>
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
					<span class="bdh-badge bdh-badge-<?php echo esc_attr( $bundle['badge'] ); ?>"><?php echo esc_html( strtoupper( $bundle['badge'] ) ); ?></span>
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
				<div class="bdh-bundle-footer">
					<a href="<?php echo esc_url( bdh_amazon_url( $bundle['items'][0]['keyword'] ) ); ?>" rel="nofollow sponsored noopener" target="_blank" class="bdh-btn bdh-btn-primary bdh-btn-block">
						<?php esc_html_e( 'Open Full Bundle on Amazon', 'benders-hub' ); ?>
					</a>
				</div>
			</article>
		<?php endforeach; ?>
		</div>
	</div>
</section>
