<?php
/**
 * Main index template — blog/archive fallback.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<section class="bdh-archive-hero">
	<div class="bdh-container">
		<h1 class="bdh-page-title">
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} else {
				esc_html_e( 'Latest Fabrication Guides & Tool Reviews', 'benders-hub' );
			}
			?>
		</h1>
		<p class="bdh-page-sub"><?php esc_html_e( 'Workshop-tested reviews, bending tutorials, and fabrication insights from working metalworkers.', 'benders-hub' ); ?></p>
	</div>
</section>

<div class="bdh-container bdh-blog-grid">
	<div class="bdh-blog-main">
		<?php if ( have_posts() ) : ?>
			<div class="bdh-posts-grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'bdh-card bdh-post-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="bdh-post-thumb" href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'bdh-card', array( 'loading' => 'lazy' ) ); ?>
							</a>
						<?php endif; ?>
						<div class="bdh-post-body">
							<div class="bdh-post-meta">
								<?php
								$cats = get_the_category();
								if ( $cats ) {
									echo '<span class="bdh-post-cat">' . esc_html( $cats[0]->name ) . '</span>';
								}
								?>
								<span class="bdh-post-readtime"><?php echo (int) bdh_reading_time(); ?> <?php esc_html_e( 'min read', 'benders-hub' ); ?></span>
							</div>
							<h2 class="bdh-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="bdh-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<a class="bdh-post-readmore" href="<?php the_permalink(); ?>">
								<?php esc_html_e( 'Read guide', 'benders-hub' ); ?> <?php echo bdh_icon( 'arrow' ); ?>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<nav class="bdh-pagination">
				<?php the_posts_pagination( array(
					'prev_text' => __( '&larr; Previous', 'benders-hub' ),
					'next_text' => __( 'Next &rarr;', 'benders-hub' ),
				) ); ?>
			</nav>
		<?php else : ?>
			<p class="bdh-no-results"><?php esc_html_e( 'No fabrication guides found.', 'benders-hub' ); ?></p>
		<?php endif; ?>
	</div>

	<aside class="bdh-blog-sidebar">
		<?php get_sidebar(); ?>
	</aside>
</div>

<?php get_footer(); ?>
