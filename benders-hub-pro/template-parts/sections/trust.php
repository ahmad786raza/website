<?php
/**
 * Editorial policy block. Describes how the site works without claiming
 * testing facilities, staff or credentials it cannot evidence.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = bdh_option( 'trust_title', __( 'How Benders Hub works', 'benders-hub' ) );
$sub   = bdh_option( 'trust_sub', __( 'What you can expect from every guide and recommendation on this site.', 'benders-hub' ) );

$pillars = array(
	array( 'icon' => 'book',    'title' => __( 'Sourced specifications', 'benders-hub' ),       'body' => __( 'Capacities and specs come from manufacturers\' own documentation, linked where we use them. When sources disagree, we say so.', 'benders-hub' ) ),
	array( 'icon' => 'check',   'title' => __( 'Research vs hands-on, labelled', 'benders-hub' ), 'body' => __( 'Most recommendations are research-based. A product is only described as tested when we have bent tube with it ourselves, and we show how.', 'benders-hub' ) ),
	array( 'icon' => 'shield',  'title' => __( 'Safety first', 'benders-hub' ),                  'body' => __( 'For roll cages and other safety structures we point you to the current rulebook rather than paraphrasing it.', 'benders-hub' ) ),
	array( 'icon' => 'info',    'title' => __( 'Clear affiliate disclosure', 'benders-hub' ),    'body' => __( 'We earn a commission from some Amazon links. Commission rates never decide what we recommend.', 'benders-hub' ) ),
);
$links = array(
	'editorial-standards'  => __( 'Editorial standards', 'benders-hub' ),
	'methodology'          => __( 'How we evaluate equipment', 'benders-hub' ),
	'affiliate-disclosure' => __( 'Affiliate disclosure', 'benders-hub' ),
);
?>
<section class="bdh-section bdh-trust" id="bdh-trust" aria-labelledby="bdh-trust-title">
	<div class="bdh-container">
		<header class="bdh-section-head">
			<p class="bdh-eyebrow"><?php esc_html_e( 'Editorial policy', 'benders-hub' ); ?></p>
			<h2 class="bdh-section-title" id="bdh-trust-title"><?php echo esc_html( $title ); ?></h2>
			<p class="bdh-section-sub"><?php echo esc_html( $sub ); ?></p>
		</header>
		<div class="bdh-trust-grid">
		<?php foreach ( $pillars as $p ) : ?>
			<div class="bdh-trust-item">
				<span class="bdh-trust-icon"><?php echo bdh_icon( $p['icon'] ); ?></span>
				<h3><?php echo esc_html( $p['title'] ); ?></h3>
				<p><?php echo esc_html( $p['body'] ); ?></p>
			</div>
		<?php endforeach; ?>
		</div>
		<p class="bdh-trust-links">
			<?php foreach ( $links as $slug => $label ) : $u = bdh_page_url( $slug ); if ( ! $u ) { continue; } ?>
				<a class="bdh-link-arrow" href="<?php echo esc_url( $u ); ?>"><?php echo esc_html( $label ); ?><?php echo bdh_icon( 'arrow' ); ?></a>
			<?php endforeach; ?>
		</p>
	</div>
</section>
