<?php
/**
 * Homepage Section C — Workshop tools console.
 *
 * Every tool keeps its original anchor (#bdh-quiz, #bdh-calculator, …) so
 * menus and in-article links created with v1 still land on the right tool.
 * Panels are tabs: only one tool is visible at a time, keeping the homepage
 * short while all tools stay one click (or one link) away.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$tools   = bdh_tools();
$primary = array_filter( $tools, function ( $t ) { return ! empty( $t['primary'] ); } );
$more    = array_filter( $tools, function ( $t ) { return empty( $t['primary'] ); } );
?>
<section class="bdh-section bdh-tools-band" id="bdh-tools" aria-labelledby="bdh-tools-title">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head--split">
			<div>
				<p class="bdh-eyebrow bdh-eyebrow--light"><?php esc_html_e( 'Free engineering tools', 'benders-hub' ); ?></p>
				<h2 class="bdh-section-title" id="bdh-tools-title"><?php esc_html_e( 'Plan the bend before you cut the tube', 'benders-hub' ); ?></h2>
			</div>
			<p class="bdh-section-sub"><?php esc_html_e( 'Everything runs in your browser. Nothing you enter is sent to us.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-console" data-bdh-console>
			<div class="bdh-console-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Workshop tools', 'benders-hub' ); ?>">
				<?php $first = true; foreach ( $primary as $key => $tool ) : ?>
					<button type="button" class="bdh-console-tab" role="tab" id="bdh-tab-<?php echo esc_attr( $key ); ?>" aria-controls="bdh-panel-<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo $first ? 'true' : 'false'; ?>" tabindex="<?php echo $first ? '0' : '-1'; ?>" data-bdh-anchor="<?php echo esc_attr( $tool['anchor'] ); ?>">
						<span class="bdh-console-tab-icon"><?php echo bdh_icon( $tool['icon'] ); ?></span>
						<span class="bdh-console-tab-text">
							<strong><?php echo esc_html( $tool['label'] ); ?></strong>
							<small><?php echo esc_html( $tool['desc'] ); ?></small>
						</span>
					</button>
				<?php $first = false; endforeach; ?>
			</div>

			<div class="bdh-console-more" role="tablist" aria-label="<?php esc_attr_e( 'More tools', 'benders-hub' ); ?>">
				<span aria-hidden="true"><?php esc_html_e( 'More tools:', 'benders-hub' ); ?></span>
				<?php foreach ( $more as $key => $tool ) : ?>
					<button type="button" class="bdh-chip" role="tab" id="bdh-tab-<?php echo esc_attr( $key ); ?>" aria-controls="bdh-panel-<?php echo esc_attr( $key ); ?>" aria-selected="false" tabindex="-1" data-bdh-anchor="<?php echo esc_attr( $tool['anchor'] ); ?>"><?php echo bdh_icon( $tool['icon'] ); ?><?php echo esc_html( $tool['short'] ); ?></button>
				<?php endforeach; ?>
			</div>

			<div class="bdh-console-panels">
				<?php $first = true; foreach ( $tools as $key => $tool ) : ?>
					<div class="bdh-console-panel" role="tabpanel" id="bdh-panel-<?php echo esc_attr( $key ); ?>" aria-labelledby="bdh-tab-<?php echo esc_attr( $key ); ?>" <?php echo $first ? '' : 'hidden'; ?>>
						<?php get_template_part( $tool['part'], null, array( 'context' => 'console' ) ); ?>
					</div>
				<?php $first = false; endforeach; ?>
			</div>
		</div>
	</div>
</section>
