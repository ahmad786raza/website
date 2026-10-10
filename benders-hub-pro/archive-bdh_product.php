<?php
/**
 * Equipment Archive — themed CPT archive.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$is_tax = is_tax( array( 'bdh_equipment_type', 'bdh_brand', 'bdh_archetype_tax' ) );
$queried = $is_tax ? get_queried_object() : null;

// Pull equipment from CPT if available, otherwise fall back to seed data.
$wp_query_main = $GLOBALS['wp_query'];
$has_cpt_posts = $wp_query_main->have_posts();
?>

<section class="bdh-section bdh-archive-hero" style="padding-top:var(--bdh-space-7);padding-bottom:var(--bdh-space-5);">
	<div class="bdh-container">
		<header class="bdh-section-head">
			<span class="bdh-section-tag"><?php esc_html_e( 'Equipment Database', 'benders-hub' ); ?></span>
			<h1 class="bdh-section-title">
				<?php
				if ( $is_tax && $queried ) {
					echo esc_html( $queried->name );
				} else {
					esc_html_e( 'Bending Equipment Database', 'benders-hub' );
				}
				?>
			</h1>
			<p class="bdh-section-sub">
				<?php
				if ( $is_tax && $queried && ! empty( $queried->description ) ) {
					echo esc_html( $queried->description );
				} else {
					esc_html_e( 'Hydraulic, manual, CNC, mandrel, and specialty benders — reviewed and workshop-tested. Filter by category, search by use case.', 'benders-hub' );
				}
				?>
			</p>
		</header>
	</div>
</section>

<section class="bdh-section bdh-database-section">
	<div class="bdh-container">
		<div class="bdh-tool-shell bdh-database-shell" data-bdh-database>

			<?php if ( ! $has_cpt_posts ) : ?>
				<div class="bdh-no-results" style="margin-bottom:var(--bdh-space-5);">
					<p><?php esc_html_e( 'No published equipment posts yet. Showing the curated seed database below.', 'benders-hub' ); ?></p>
					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<p>
							<a class="bdh-btn bdh-btn-amber" href="<?php echo esc_url( admin_url( 'admin.php?page=bdh-repair' ) ); ?>"><?php esc_html_e( 'Run Repair Setup', 'benders-hub' ); ?></a>
							<a class="bdh-btn bdh-btn-ghost" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=bdh_product' ) ); ?>"><?php esc_html_e( 'Add Equipment', 'benders-hub' ); ?></a>
						</p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="bdh-database-controls">
				<div class="bdh-database-search">
					<?php echo bdh_icon( 'search' ); ?>
					<input type="search" placeholder="<?php esc_attr_e( 'Search equipment, tubing, use case…', 'benders-hub' ); ?>" data-bdh-db-search>
				</div>
				<div class="bdh-database-cats" role="tablist">
				<?php foreach ( bdh_equipment_categories() as $key => $label ) : ?>
					<button class="bdh-database-cat <?php echo $key === 'all' ? 'is-active' : ''; ?>" data-bdh-db-cat="<?php echo esc_attr( $key ); ?>" role="tab" aria-selected="<?php echo $key === 'all' ? 'true' : 'false'; ?>"><?php echo esc_html( $label ); ?></button>
				<?php endforeach; ?>
				</div>
			</div>

			<div class="bdh-database-grid" data-bdh-db-grid>
				<?php if ( $has_cpt_posts ) :
					while ( $wp_query_main->have_posts() ) : $wp_query_main->the_post();
						$post_id = get_the_ID();
						$eq = array(
							'id'         => 'cpt-' . $post_id,
							'name'       => get_the_title(),
							'type'       => wp_get_object_terms( $post_id, 'bdh_equipment_type', array( 'fields' => 'names' ) )[0] ?? __( 'Bender', 'benders-hub' ),
							'category'   => sanitize_key( wp_get_object_terms( $post_id, 'bdh_equipment_type', array( 'fields' => 'slugs' ) )[0] ?? 'all' ),
							'capacity'   => get_post_meta( $post_id, '_bdh_capacity', true ) ?: '—',
							'tonnage'    => get_post_meta( $post_id, '_bdh_tonnage', true ) ?: '—',
							'precision'  => get_post_meta( $post_id, '_bdh_precision', true ) ?: 'mid',
							'durability' => (int) ( get_post_meta( $post_id, '_bdh_durability', true ) ?: 4 ),
							'beginner'   => get_post_meta( $post_id, '_bdh_beginner', true ) ?: 'partial',
							'workshop'   => get_post_meta( $post_id, '_bdh_workshop_fit', true ) ?: 'pro',
							'score'      => (float) ( get_post_meta( $post_id, '_bdh_editor_score', true ) ?: 0 ),
							'badge'      => 'pick',
							'use_case'   => get_post_meta( $post_id, '_bdh_use_case', true ) ?: wp_trim_words( get_the_excerpt(), 14 ),
							'tubing'     => get_post_meta( $post_id, '_bdh_tubing_compat', true ) ?: '—',
							'price'      => get_post_meta( $post_id, '_bdh_price_range', true ) ?: '$$',
							'keyword'    => get_post_meta( $post_id, '_bdh_amazon_keyword', true ) ?: 'tube bender',
							'pros'       => array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $post_id, '_bdh_pros', true ) ) ) ),
							'cons'       => array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $post_id, '_bdh_cons', true ) ) ) ),
							'permalink'  => get_permalink(),
						);
						set_query_var( 'bdh_eq', $eq );
						get_template_part( 'template-parts/equipment-card' );
					endwhile;
					wp_reset_postdata();
				else :
					foreach ( bdh_default_equipment() as $eq ) {
						set_query_var( 'bdh_eq', $eq );
						get_template_part( 'template-parts/equipment-card' );
					}
				endif; ?>
			</div>

			<div class="bdh-database-status" data-bdh-db-status aria-live="polite"></div>

			<?php if ( $has_cpt_posts ) : ?>
				<nav class="bdh-pagination" style="margin-top:var(--bdh-space-5);">
					<?php the_posts_pagination( array(
						'prev_text' => __( '← Previous', 'benders-hub' ),
						'next_text' => __( 'Next →', 'benders-hub' ),
					) ); ?>
				</nav>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
// Append signature site sections so equipment archive stays on-brand.
get_template_part( 'template-parts/sections/comparison' );
get_template_part( 'template-parts/sections/faq' );
get_template_part( 'template-parts/sections/newsletter' );
get_footer();
