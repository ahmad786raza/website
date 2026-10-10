<?php
/**
 * Editor's Best Benders.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$picks = array_slice( bdh_default_equipment(), 0, 3 );
?>
<section class="bdh-section bdh-editors-best" id="bdh-editors-best" data-bdh-section="editors_best">
	<div class="bdh-container">
		<header class="bdh-section-head">
			<span class="bdh-section-tag"><?php esc_html_e( "Editor's choice", 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Three benders we trust this season', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( 'Workshop-tested across exhaust, chassis, and industrial work. No paid placements.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-editors-grid">
		<?php foreach ( $picks as $idx => $eq ) :
			$variant = $idx === 0 ? 'hero' : 'card';
		?>
			<article class="bdh-product-box bdh-product-box-<?php echo esc_attr( $variant ); ?>">
				<div class="bdh-product-box-media">
					<div class="bdh-product-box-graphic"><?php echo bdh_icon( 'tube', 'bdh-product-box-icon' ); ?></div>
					<?php if ( ! empty( $eq['badge'] ) ) : ?>
						<span class="bdh-product-box-badge bdh-badge-<?php echo esc_attr( $eq['badge'] ); ?>">
							<?php echo esc_html( strtoupper( $eq['badge'] ) ); ?>
						</span>
					<?php endif; ?>
				</div>
				<div class="bdh-product-box-body">
					<div class="bdh-product-box-meta">
						<span class="bdh-product-box-type"><?php echo esc_html( $eq['type'] ); ?></span>
						<?php if ( ! empty( $eq['score'] ) ) : ?>
							<span class="bdh-product-box-score"><?php echo bdh_icon( 'star' ); ?> <?php echo esc_html( $eq['score'] ); ?>/10</span>
						<?php endif; ?>
					</div>
					<h3 class="bdh-product-box-title"><?php echo esc_html( $eq['name'] ); ?></h3>
					<p class="bdh-product-box-summary"><?php echo esc_html( $eq['use_case'] ); ?></p>
					<dl class="bdh-product-box-specs">
						<div><dt><?php esc_html_e( 'Capacity', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['capacity'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Power', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['tonnage'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Tubing', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $eq['tubing'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Workshop', 'benders-hub' ); ?></dt><dd><?php echo esc_html( ucfirst( $eq['workshop'] ) ); ?></dd></div>
					</dl>
					<?php if ( ! empty( $eq['pros'] ) ) : ?>
					<ul class="bdh-product-box-pros">
						<?php foreach ( $eq['pros'] as $p ) : ?><li><?php echo bdh_icon( 'check' ); ?> <?php echo esc_html( $p ); ?></li><?php endforeach; ?>
					</ul>
					<?php endif; ?>
					<div class="bdh-product-box-cta">
						<?php bdh_amazon_button( $eq['keyword'], __( 'Check Price on Amazon', 'benders-hub' ) ); ?>
						<a class="bdh-btn bdh-btn-ghost bdh-btn-sm" href="#bdh-comparison"><?php esc_html_e( 'Compare', 'benders-hub' ); ?></a>
					</div>
				</div>
			</article>
		<?php endforeach; ?>
		</div>
	</div>
</section>
