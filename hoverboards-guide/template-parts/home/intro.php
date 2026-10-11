<?php
/**
 * Homepage hero (v2.1 "Convert"): headline, two calls to action, trust chips and a
 * "quick picks" panel so shoppers can act above the fold without scrolling.
 */
$headline   = get_theme_mod( 'hbg_hero_headline', __( 'Best Hoverboards & Electric Scooters in 2026', 'hoverboards-guide' ) );
$subhead    = get_theme_mod( 'hbg_hero_subhead', __( 'Independent reviews, buying guides and how-tos for hoverboards, e-scooters and every electric ride in between — prices re-checked weekly.', 'hoverboards-guide' ) );
$chips      = array_filter( array_map( 'trim', explode( '|', get_theme_mod( 'hbg_hero_chips', __( 'UL 2272-certified picks only|200+ guides & reviews|No sponsored placements', 'hoverboards-guide' ) ) ) ) );
$parts      = array_map( 'trim', explode( '&', html_entity_decode( $headline, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
$primary    = get_theme_mod( 'hbg_hero_cta_primary', __( 'See our top picks', 'hoverboards-guide' ) );
$secondary  = get_theme_mod( 'hbg_hero_cta_secondary', __( 'Find my ride in 60 seconds', 'hoverboards-guide' ) );
$has_picks  = get_theme_mod( 'hbg_show_top_picks', true );
$has_quiz   = get_theme_mod( 'hbg_show_quiz', true );
$has_cmp    = get_theme_mod( 'hbg_show_compare', true );
$show_qp    = get_theme_mod( 'hbg_show_quick_picks', true ) && $has_picks;
$qp_count   = max( 2, min( 4, (int) get_theme_mod( 'hbg_quick_picks_count', 3 ) ) );
$picks      = $show_qp ? array_slice( hbg_get_top_picks(), 0, $qp_count ) : array();
$show_tiers = get_theme_mod( 'hbg_show_price_tiers', true );
$counts     = wp_count_posts();
$published  = $counts ? (int) $counts->publish : 0;
$modified   = get_lastpostmodified( 'blog' );
$updated    = $modified ? date_i18n( 'F Y', strtotime( $modified ) ) : date_i18n( 'F Y' );
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Introduction', 'hoverboards-guide' ); ?>">
	<div class="hero__bg" aria-hidden="true"></div>
	<div class="container hero__inner<?php echo $picks ? '' : ' hero__inner--single'; ?>">

		<div class="hero__text">
			<p class="hero__eyebrow">
				<span class="hero__pulse" aria-hidden="true"></span>
				<?php
				/* translators: 1: month and year, 2: number of published posts */
				printf( esc_html__( 'Updated %1$s · %2$s guides & reviews', 'hoverboards-guide' ), esc_html( $updated ), esc_html( number_format_i18n( $published ) ) );
				?>
			</p>
			<h1 class="hero__title">
				<?php
				foreach ( $parts as $i => $part ) {
					if ( $i > 0 ) {
						echo '<span class="hero__amp">&amp;</span> ';
					}
					echo esc_html( $part ) . ' ';
				}
				?>
			</h1>
			<p class="hero__sub"><?php echo esc_html( $subhead ); ?></p>

			<?php if ( ( $primary && $has_picks ) || ( $secondary && $has_quiz ) ) : ?>
			<div class="hero__actions">
				<?php if ( $primary && $has_picks ) : ?>
					<a href="#picks" class="btn btn--accent btn--lg"><?php echo esc_html( $primary ); ?> <?php echo hbg_svg_icon( 'arrow-right', 16 ); // phpcs:ignore ?></a>
				<?php endif; ?>
				<?php if ( $secondary && $has_quiz ) : ?>
					<a href="#quiz" class="btn btn--outline btn--lg"><?php echo hbg_svg_icon( 'bolt', 16 ); // phpcs:ignore ?> <?php echo esc_html( $secondary ); ?></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $chips ) : ?>
			<ul class="hero__chips" aria-label="<?php esc_attr_e( 'Why trust us', 'hoverboards-guide' ); ?>">
				<?php foreach ( $chips as $chip ) : ?>
					<li class="trust-chip"><?php echo hbg_svg_icon( 'check', 14 ); // phpcs:ignore ?> <?php echo esc_html( $chip ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>

		<?php if ( $picks ) : ?>
		<aside class="quick-picks" aria-labelledby="quick-picks-heading">
			<div class="quick-picks__head">
				<h2 class="quick-picks__title" id="quick-picks-heading"><?php esc_html_e( 'Our picks at a glance', 'hoverboards-guide' ); ?></h2>
				<span class="quick-picks__meta"><?php esc_html_e( 'Prices re-checked weekly', 'hoverboards-guide' ); ?></span>
			</div>
			<ol class="quick-picks__list">
				<?php foreach ( $picks as $p ) : $review = hbg_product_review_url( $p ); ?>
				<li class="qp">
					<?php hbg_product_image( $p, array( 'class' => 'product-image qp__thumb', 'ratio' => '1/1', 'note' => false, 'sizes' => '64px' ) ); ?>
					<div class="qp__body">
						<span class="qp__badge"><?php echo esc_html( $p['badge'] ); ?></span>
						<strong class="qp__name">
							<?php if ( $review ) : ?>
								<a href="<?php echo esc_url( $review ); ?>"><?php echo esc_html( $p['name'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $p['name'] ); ?>
							<?php endif; ?>
						</strong>
						<span class="qp__row">
							<?php hbg_score_badge( $p['score'] ); ?>
							<?php if ( $show_tiers ) : ?><span class="qp__tier"><?php echo esc_html( hbg_price_tier_label( $p['tier'] ) ); ?></span><?php endif; ?>
						</span>
					</div>
					<?php hbg_amazon_cta( $p, __( 'Check price', 'hoverboards-guide' ), 'sm' ); ?>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php if ( $has_cmp ) : ?>
				<a href="#compare" class="quick-picks__more"><?php esc_html_e( 'Compare all our picks side by side', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a>
			<?php endif; ?>
		</aside>
		<?php endif; ?>

	</div>
</section>
