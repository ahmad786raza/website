<?php
/**
 * Search Results.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<section class="bdh-archive-hero">
	<div class="bdh-container">
		<h1 class="bdh-page-title">
			<?php printf( esc_html__( 'Search results for: %s', 'benders-hub' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?>
		</h1>
	</div>
</section>

<div class="bdh-container bdh-blog-grid">
	<div class="bdh-blog-main">
		<?php if ( have_posts() ) : ?>
			<div class="bdh-posts-grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'bdh-card bdh-post-card' ); ?>>
					<div class="bdh-post-body">
						<h2 class="bdh-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="bdh-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
					</div>
				</article>
			<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="bdh-no-results"><?php esc_html_e( 'No results — try the fabrication archetype explorer or take the quiz.', 'benders-hub' ); ?></p>
			<a class="bdh-btn bdh-btn-primary" href="<?php echo esc_url( home_url( '/#bdh-quiz' ) ); ?>"><?php esc_html_e( 'Take the Quiz', 'benders-hub' ); ?></a>
		<?php endif; ?>
	</div>
	<aside class="bdh-blog-sidebar"><?php get_sidebar(); ?></aside>
</div>

<?php get_footer(); ?>
