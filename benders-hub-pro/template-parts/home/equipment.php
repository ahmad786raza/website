<?php
/**
 * Homepage Section E — recommended equipment by need.
 * Editorial categories of equipment, not a ranking of products.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$needs = bdh_equipment_needs();
$method = bdh_page_url( 'methodology' );
?>
<section class="bdh-section bdh-needs" id="bdh-equipment" aria-labelledby="bdh-needs-title">
	<?php // Anchors used by v1 menus and article links (#bdh-editors-best, #bdh-bundles) land here. ?>
	<span id="bdh-editors-best" class="bdh-anchor"></span>
	<?php $bdh_sections = bdh_get_homepage_sections(); if ( empty( $bdh_sections['kit_bundles']['enabled'] ) ) : ?><span id="bdh-bundles" class="bdh-anchor"></span><?php endif; ?>
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head--split">
			<div>
				<p class="bdh-eyebrow"><?php esc_html_e( 'Recommended equipment', 'benders-hub' ); ?></p>
				<h2 class="bdh-section-title" id="bdh-needs-title"><?php esc_html_e( 'The right kind of bender for the job', 'benders-hub' ); ?></h2>
			</div>
			<p class="bdh-section-sub">
				<?php esc_html_e( 'Equipment categories chosen from manufacturers\' published capacities and common shop practice — not hands-on product tests.', 'benders-hub' ); ?>
				<?php if ( $method ) : ?><a href="<?php echo esc_url( $method ); ?>"><?php esc_html_e( 'How we evaluate equipment', 'benders-hub' ); ?></a><?php endif; ?>
			</p>
		</header>

		<div class="bdh-needs-grid">
			<?php foreach ( $needs as $need ) :
				$att = function_exists( 'bdh_library_attachment' ) ? bdh_library_attachment( $need['image'] ) : 0;
				$guide = 0;
				foreach ( $need['posts'] as $slug ) {
					$p = get_page_by_path( $slug, OBJECT, 'post' );
					if ( $p && 'publish' === $p->post_status ) { $guide = (int) $p->ID; break; }
				}
			?>
				<article class="bdh-need-card" id="need-<?php echo esc_attr( $need['id'] ); ?>">
					<?php if ( $att ) : ?>
						<div class="bdh-need-media"><?php echo wp_get_attachment_image( $att, 'bdh-card', false, array( 'loading' => 'lazy', 'sizes' => '(min-width: 1100px) 380px, (min-width: 700px) 50vw, 100vw' ) ); ?></div>
					<?php else : ?>
						<div class="bdh-need-media bdh-need-media--icon" aria-hidden="true"><?php echo bdh_icon( $need['icon'] ); ?></div>
					<?php endif; ?>
					<div class="bdh-need-body">
						<p class="bdh-need-label"><?php echo esc_html( $need['label'] ); ?></p>
						<h3 class="bdh-need-pick"><?php echo esc_html( $need['pick'] ); ?></h3>
						<p class="bdh-need-why"><?php echo esc_html( $need['why'] ); ?></p>
						<p class="bdh-need-sub"><?php esc_html_e( 'Look for', 'benders-hub' ); ?></p>
						<ul class="bdh-checklist">
							<?php foreach ( $need['look'] as $item ) : ?><li><?php echo bdh_icon( 'check' ); ?><span><?php echo esc_html( $item ); ?></span></li><?php endforeach; ?>
						</ul>
						<p class="bdh-need-caution"><?php echo bdh_icon( 'alert' ); ?><span><?php echo esc_html( $need['caution'] ); ?></span></p>
						<div class="bdh-need-actions">
							<?php if ( $guide ) : ?>
								<a class="bdh-btn bdh-btn-outline bdh-btn-sm" href="<?php echo esc_url( get_permalink( $guide ) ); ?>"><?php esc_html_e( 'Read the guide', 'benders-hub' ); ?></a>
							<?php endif; ?>
							<?php bdh_amazon_button( $need['keyword'], __( 'See options on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-primary bdh-btn-sm' ); ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php bdh_inline_disclosure(); ?>
	</div>
</section>
