<?php
/**
 * Single equipment entry.
 * Seeded v1 entries (fictional products) render as clearly-labelled
 * category profiles and are noindexed (see content-filters.php).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

while ( have_posts() ) :
	the_post();
	$id     = get_the_ID();
	$illus  = bdh_is_illustrative_product( $id );
	$lines  = function ( $key ) use ( $id ) { return array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $id, $key, true ) ) ) ); };
	$specs  = array();
	foreach ( array( '_bdh_capacity' => __( 'Capacity', 'benders-hub' ), '_bdh_tonnage' => __( 'Drive / power', 'benders-hub' ), '_bdh_tubing_compat' => __( 'Materials', 'benders-hub' ) ) as $k => $label ) {
		$v = get_post_meta( $id, $k, true );
		if ( $v ) { $specs[ $label ] = $v; }
	}
	$card = array(
		'name'        => get_the_title(),
		'image_id'    => get_post_thumbnail_id(),
		'application' => get_post_meta( $id, '_bdh_use_case', true ),
		'specs'       => $specs,
		'pros'        => $lines( '_bdh_pros' ),
		'cons'        => $lines( '_bdh_cons' ),
		'compat'      => get_post_meta( $id, '_bdh_compat', true ),
		'best_for'    => get_post_meta( $id, '_bdh_best_for', true ),
		'alternative' => get_post_meta( $id, '_bdh_alternative_if', true ),
		'url'         => get_post_meta( $id, '_bdh_amazon_url', true ),
		'keyword'     => get_post_meta( $id, '_bdh_amazon_keyword', true ),
		'evaluation'  => get_post_meta( $id, '_bdh_evaluation', true ) === 'hands-on' ? 'hands-on' : 'research',
		'source'      => get_post_meta( $id, '_bdh_spec_source', true ),
	);
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'bdh-article bdh-equipment' ); ?>>
		<header class="bdh-article-header">
			<div class="bdh-container bdh-article-header-inner">
				<?php bdh_breadcrumbs(); ?>
				<p class="bdh-kicker"><?php echo $illus ? esc_html__( 'Equipment category profile', 'benders-hub' ) : esc_html__( 'Equipment', 'benders-hub' ); ?></p>
				<h1 class="bdh-article-title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p class="bdh-article-dek"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
		</header>
		<div class="bdh-container bdh-article-layout bdh-article-layout--single">
			<div class="bdh-article-main">
				<?php if ( $illus ) : ?>
					<p class="bdh-note bdh-note--info"><?php esc_html_e( 'This page describes a general class of equipment. It is not a review of a specific product, and the capacities shown are typical examples — check the manufacturer\'s specifications for any machine you are considering.', 'benders-hub' ); ?></p>
				<?php else : ?>
					<?php get_template_part( 'template-parts/product-card', null, array( 'p' => $card ) ); ?>
				<?php endif; ?>
				<div class="bdh-prose"><?php the_content(); ?></div>
				<?php if ( $illus && $card['keyword'] ) : ?>
					<p><?php bdh_amazon_button( $card['keyword'], __( 'See options on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-outline' ); ?></p>
					<?php bdh_inline_disclosure(); ?>
				<?php endif; ?>
			</div>
		</div>
	</article>
	<?php
endwhile;
get_template_part( 'template-parts/sections/comparison' );
get_footer();
