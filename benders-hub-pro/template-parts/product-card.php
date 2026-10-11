<?php
/**
 * Product recommendation card — one design for shortcodes and equipment pages.
 *
 * Expected $args['p'] keys (all optional except name):
 *   name, image_id, application, specs (assoc array), pros[], cons[], compat,
 *   best_for, alternative, url (Amazon product URL), keyword (Amazon search),
 *   evaluation ('research'|'hands-on'), source (spec source URL), label
 *
 * Never shows ratings, prices, stock or discounts.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$p = isset( $args['p'] ) ? $args['p'] : array();
if ( empty( $p['name'] ) ) { return; }
$hands_on = isset( $p['evaluation'] ) && 'hands-on' === $p['evaluation'];
$cta      = ! empty( $p['url'] ) ? $p['url'] : ( ! empty( $p['keyword'] ) ? $p['keyword'] : $p['name'] );
$label    = ! empty( $p['label'] ) ? $p['label'] : ( ! empty( $p['url'] ) ? __( 'Check price on Amazon', 'benders-hub' ) : __( 'See options on Amazon', 'benders-hub' ) );
?>
<article class="bdh-product">
	<div class="bdh-product-media">
		<?php if ( ! empty( $p['image_id'] ) ) : ?>
			<?php echo wp_get_attachment_image( (int) $p['image_id'], 'bdh-product', false, array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span class="bdh-card-placeholder" aria-hidden="true"><?php echo bdh_hero_bend_svg(); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		<?php endif; ?>
	</div>
	<div class="bdh-product-body">
		<p class="bdh-product-eval">
			<?php echo $hands_on ? bdh_trust_badge( 'hands-on' ) : bdh_trust_badge( 'research' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</p>
		<h3 class="bdh-product-name"><?php echo esc_html( $p['name'] ); ?></h3>
		<?php if ( ! empty( $p['application'] ) ) : ?><p class="bdh-product-app"><?php echo esc_html( $p['application'] ); ?></p><?php endif; ?>

		<?php if ( ! empty( $p['specs'] ) && is_array( $p['specs'] ) ) : ?>
			<dl class="bdh-specs">
				<?php foreach ( $p['specs'] as $k => $v ) : ?><div><dt><?php echo esc_html( $k ); ?></dt><dd><?php echo esc_html( $v ); ?></dd></div><?php endforeach; ?>
			</dl>
			<?php if ( ! empty( $p['source'] ) ) : ?>
				<p class="bdh-product-source"><?php esc_html_e( 'Specifications:', 'benders-hub' ); ?> <a href="<?php echo esc_url( $p['source'] ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'manufacturer source', 'benders-hub' ); ?></a></p>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $p['pros'] ) || ! empty( $p['cons'] ) ) : ?>
			<div class="bdh-proscons">
				<?php if ( ! empty( $p['pros'] ) ) : ?>
					<div><p class="bdh-proscons-title"><?php esc_html_e( 'Advantages', 'benders-hub' ); ?></p><ul class="bdh-pros"><?php foreach ( (array) $p['pros'] as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul></div>
				<?php endif; ?>
				<?php if ( ! empty( $p['cons'] ) ) : ?>
					<div><p class="bdh-proscons-title"><?php esc_html_e( 'Limitations', 'benders-hub' ); ?></p><ul class="bdh-cons"><?php foreach ( (array) $p['cons'] as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $p['compat'] ) ) : ?><p class="bdh-product-note"><strong><?php esc_html_e( 'Compatibility:', 'benders-hub' ); ?></strong> <?php echo esc_html( $p['compat'] ); ?></p><?php endif; ?>
		<?php if ( ! empty( $p['best_for'] ) ) : ?><p class="bdh-product-note"><strong><?php esc_html_e( 'Consider it if:', 'benders-hub' ); ?></strong> <?php echo esc_html( $p['best_for'] ); ?></p><?php endif; ?>
		<?php if ( ! empty( $p['alternative'] ) ) : ?><p class="bdh-product-note"><strong><?php esc_html_e( 'Look elsewhere if:', 'benders-hub' ); ?></strong> <?php echo esc_html( $p['alternative'] ); ?></p><?php endif; ?>

		<div class="bdh-product-cta">
			<?php bdh_amazon_button( $cta, $label, 'bdh-btn bdh-btn-primary' ); ?>
		</div>
		<?php bdh_inline_disclosure(); ?>
	</div>
</article>
