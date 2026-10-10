<?php
/**
 * 404 Page.
 *
 * Affiliate product box appears FIRST.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$archetypes = bdh_archetypes();
$equipment  = bdh_default_equipment();
$first_pick = isset( $equipment[0] ) ? $equipment[0] : null;
?>

<section class="bdh-404">
	<div class="bdh-container bdh-404-inner">

		<?php if ( $first_pick ) : ?>
		<div class="bdh-404-affiliate">
			<header class="bdh-404-affiliate-head">
				<span class="bdh-404-affiliate-tag"><?php esc_html_e( "While you're here — editor's top pick", 'benders-hub' ); ?></span>
			</header>
			<article class="bdh-product-box bdh-product-box-hero">
				<div class="bdh-product-box-media">
					<div class="bdh-product-box-graphic"><?php echo bdh_icon( 'tube', 'bdh-product-box-icon' ); ?></div>
					<span class="bdh-product-box-badge bdh-badge-pick"><?php esc_html_e( 'EDITOR PICK', 'benders-hub' ); ?></span>
				</div>
				<div class="bdh-product-box-body">
					<div class="bdh-product-box-meta">
						<span class="bdh-product-box-type"><?php echo esc_html( $first_pick['type'] ); ?></span>
						<span class="bdh-product-box-score"><?php echo bdh_icon( 'star' ); ?> <?php echo esc_html( $first_pick['score'] ); ?>/10</span>
					</div>
					<h2 class="bdh-product-box-title"><?php echo esc_html( $first_pick['name'] ); ?></h2>
					<p class="bdh-product-box-summary"><?php echo esc_html( $first_pick['use_case'] ); ?></p>
					<dl class="bdh-product-box-specs">
						<div><dt><?php esc_html_e( 'Capacity', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $first_pick['capacity'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Power', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $first_pick['tonnage'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Tubing', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $first_pick['tubing'] ); ?></dd></div>
					</dl>
					<?php bdh_amazon_button( $first_pick['keyword'], __( 'View on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-primary bdh-btn-lg' ); ?>
				</div>
			</article>
		</div>
		<?php endif; ?>

		<header class="bdh-404-header">
			<h1 class="bdh-404-title">404 — <?php esc_html_e( 'That page is bent', 'benders-hub' ); ?></h1>
			<p class="bdh-404-sub"><?php esc_html_e( 'The page you were looking for is no longer here. Let\'s get you back to fabricating.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-404-search">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo bdh_icon( 'search' ); ?>
				<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search tube benders, calculators, guides…', 'benders-hub' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit" class="bdh-btn bdh-btn-primary"><?php esc_html_e( 'Search', 'benders-hub' ); ?></button>
			</form>
		</div>

		<div class="bdh-404-archetypes">
			<h2><?php esc_html_e( 'Pick a fabrication path', 'benders-hub' ); ?></h2>
			<div class="bdh-404-archetype-grid">
			<?php foreach ( array_slice( $archetypes, 0, 6 ) as $a ) : ?>
				<a class="bdh-404-archetype-card" href="<?php echo esc_url( bdh_amazon_url( $a['keyword'] ) ); ?>" rel="nofollow sponsored noopener" target="_blank">
					<span class="bdh-404-archetype-icon"><?php echo bdh_icon( $a['icon'] ); ?></span>
					<strong><?php echo esc_html( $a['name'] ); ?></strong>
					<small><?php echo esc_html( $a['tagline'] ); ?></small>
				</a>
			<?php endforeach; ?>
			</div>
		</div>

		<div class="bdh-404-cta-row">
			<a class="bdh-btn bdh-btn-primary bdh-btn-lg" href="<?php echo esc_url( home_url( '/#bdh-quiz' ) ); ?>">
				<?php echo bdh_icon( 'tube' ); ?>
				<?php esc_html_e( 'Take the Tube Bender Quiz', 'benders-hub' ); ?>
			</a>
			<a class="bdh-btn bdh-btn-ghost bdh-btn-lg" href="<?php echo esc_url( home_url( '/#bdh-calculator' ) ); ?>">
				<?php echo bdh_icon( 'calculator' ); ?>
				<?php esc_html_e( 'Open Bend Calculator', 'benders-hub' ); ?>
			</a>
		</div>

		<div class="bdh-404-guides">
			<h2><?php esc_html_e( 'Top fabrication guides', 'benders-hub' ); ?></h2>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/category/hydraulic-benders/' ) ); ?>"><?php esc_html_e( 'Hydraulic tube bender buying guide →', 'benders-hub' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/category/exhaust-bending/' ) ); ?>"><?php esc_html_e( 'Custom exhaust fabrication walkthrough →', 'benders-hub' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/category/roll-cage/' ) ); ?>"><?php esc_html_e( 'Roll cage tubing & dies →', 'benders-hub' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/category/cnc-benders/' ) ); ?>"><?php esc_html_e( 'CNC bending production cell →', 'benders-hub' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/glossary/' ) ); ?>"><?php esc_html_e( 'Tubing & bending glossary →', 'benders-hub' ); ?></a></li>
			</ul>
		</div>

	</div>
</section>

<?php get_footer(); ?>
