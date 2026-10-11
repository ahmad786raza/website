<?php
/**
 * H. Hoverboard finder: four questions → a product plus the guides to read first.
 * Without JavaScript the static decision guide below is shown instead.
 */
$embedded  = ! empty( $args['embedded'] );
$questions = hbg_finder_questions();
$fallback  = array(
	array( __( 'A child on smooth ground', 'hoverboards-guide' ), 'kids', 'gyroor-g13' ),
	array( __( 'A child who wants grass and driveways', 'hoverboards-guide' ), 'kids', 'gyroor-g13' ),
	array( __( 'A first-time rider on a tight budget', 'hoverboards-guide' ), 'budget', 'gotrax-edge' ),
	array( __( 'A teen or adult on pavement', 'hoverboards-guide' ), 'best', 'hover1-helix' ),
	array( __( 'A teen or adult on grass, gravel or dirt', 'hoverboards-guide' ), 'all_terrain', 'gyroor-warrior-g2' ),
	array( __( 'A heavier or taller adult', 'hoverboards-guide' ), 'adults', 'swagtron-t6' ),
	array( __( 'A beginner who wants the most stability', 'hoverboards-guide' ), 'how_to_ride', 'ninebot-s' ),
);
?>
<section class="section section--alt finder<?php echo $embedded ? ' finder--embedded' : ''; ?>" id="finder" aria-labelledby="finder-title">
	<div class="<?php echo $embedded ? '' : 'container'; ?>">
		<div class="finder__card">
			<div class="finder__intro">
				<div class="section-heading__kicker"><?php esc_html_e( 'Hoverboard finder', 'hoverboards-guide' ); ?></div>
				<h2 class="finder__title" id="finder-title"><?php esc_html_e( 'Not sure where to start? Answer four questions', 'hoverboards-guide' ); ?></h2>
				<p class="finder__sub"><?php esc_html_e( 'We’ll point you to the guides worth reading and the pick from our roundups that fits. It is a starting point, not a verdict: check the manufacturer’s age and weight guidance, the rider’s ability and protective gear before buying.', 'hoverboards-guide' ); ?></p>
				<div class="finder__illus" aria-hidden="true"><?php echo hbg_illustration( 'finder' ); // phpcs:ignore ?></div>
			</div>

			<div class="finder__panel js-only" data-finder>
				<ol class="finder__progress" aria-hidden="true">
					<?php foreach ( $questions as $i => $q ) : ?><li class="<?php echo 0 === $i ? 'is-active' : ''; ?>"></li><?php endforeach; ?>
				</ol>
				<?php foreach ( $questions as $i => $q ) : ?>
				<fieldset class="finder__step<?php echo 0 === $i ? ' is-active' : ''; ?>" data-step="<?php echo (int) $i; ?>" data-key="<?php echo esc_attr( $q['key'] ); ?>">
					<legend class="finder__question"><span class="finder__count"><?php printf( esc_html__( 'Question %1$d of %2$d', 'hoverboards-guide' ), (int) $i + 1, count( $questions ) ); ?></span><?php echo esc_html( $q['q'] ); ?></legend>
					<div class="finder__options">
						<?php foreach ( $q['options'] as $opt ) : ?>
							<button type="button" class="finder__option" data-value="<?php echo esc_attr( $opt['value'] ); ?>"><span><?php echo esc_html( $opt['label'] ); ?></span><?php echo hbg_svg_icon( 'arrow-right', 16 ); // phpcs:ignore ?></button>
						<?php endforeach; ?>
					</div>
					<?php if ( $i > 0 ) : ?><button type="button" class="finder__back" data-finder-back><?php echo hbg_svg_icon( 'chevron-left', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Back', 'hoverboards-guide' ); ?></button><?php endif; ?>
				</fieldset>
				<?php endforeach; ?>
				<div class="finder__result" aria-live="polite" hidden>
					<div class="finder__result-kicker"><?php esc_html_e( 'Where to start', 'hoverboards-guide' ); ?></div>
					<ul class="finder__guides" data-finder-guides></ul>
					<div class="finder__pick">
						<div class="finder__pick-media" data-finder-image></div>
						<div class="finder__pick-body">
							<span class="finder__pick-label"><?php esc_html_e( 'The matching pick from our roundups', 'hoverboards-guide' ); ?></span>
							<h3 class="finder__pick-name" data-finder-name></h3>
							<p class="finder__pick-why" data-finder-why></p>
							<dl class="specs" data-finder-specs></dl>
							<div class="finder__pick-actions">
								<a href="#" class="btn btn--ghost btn--md" data-finder-review hidden><?php esc_html_e( 'View full review', 'hoverboards-guide' ); ?></a>
								<a href="#" class="cta-amazon cta-amazon--md" target="_blank" rel="sponsored nofollow noopener" data-finder-link data-hbg-track="affiliate" data-placement="finder"><span><?php esc_html_e( 'Check price on Amazon', 'hoverboards-guide' ); ?></span><?php echo hbg_svg_icon( 'external', 14 ); // phpcs:ignore ?></a>
							</div>
							<p class="finder__pick-note" data-finder-note></p>
						</div>
					</div>
					<p class="finder__caveat"><?php echo hbg_svg_icon( 'shield', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'Age alone does not make a board safe for a particular child. Check the manufacturer’s age and weight limits, start in a self-balancing or beginner mode, supervise, and fit a helmet and pads.', 'hoverboards-guide' ); ?></p>
					<button type="button" class="btn btn--ghost btn--sm" data-finder-restart><?php esc_html_e( 'Start again', 'hoverboards-guide' ); ?></button>
				</div>
			</div>

			<div class="finder__fallback no-js-only">
				<h3 class="finder__fallback-title"><?php esc_html_e( 'Decision guide', 'hoverboards-guide' ); ?></h3>
				<table class="finder__table">
					<thead><tr><th scope="col"><?php esc_html_e( 'Rider', 'hoverboards-guide' ); ?></th><th scope="col"><?php esc_html_e( 'Read first', 'hoverboards-guide' ); ?></th><th scope="col"><?php esc_html_e( 'Pick from our roundups', 'hoverboards-guide' ); ?></th></tr></thead>
					<tbody>
					<?php foreach ( $fallback as $row ) : $url = hbg_key_url( $row[1] ); $p = hbg_get_product( $row[2] ); ?>
						<tr><td><?php echo esc_html( $row[0] ); ?></td><td><?php if ( $url ) : ?><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( hbg_post_by_slug( hbg_key_posts()[ $row[1] ] ) ) ); ?></a><?php endif; ?></td><td><?php if ( $p ) : ?><?php $r = hbg_product_review_url( $p ); echo $r ? '<a href="' . esc_url( $r ) . '">' . esc_html( $p['name'] ) . '</a>' : esc_html( $p['name'] ); ?><?php endif; ?></td></tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
