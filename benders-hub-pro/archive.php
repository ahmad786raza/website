<?php
/**
 * Archive Template.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<section class="bdh-archive-hero">
	<div class="bdh-container">
		<h1 class="bdh-page-title"><?php the_archive_title(); ?></h1>
		<div class="bdh-page-sub"><?php the_archive_description(); ?></div>
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
							<h2 class="bdh-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="bdh-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
							<a class="bdh-post-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read', 'benders-hub' ); ?> <?php echo bdh_icon( 'arrow' ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="bdh-no-results"><?php esc_html_e( 'No fabrication content matched.', 'benders-hub' ); ?></p>
		<?php endif; ?>
	</div>
	<aside class="bdh-blog-sidebar"><?php get_sidebar(); ?></aside>
</div>

<?php get_footer(); ?>
