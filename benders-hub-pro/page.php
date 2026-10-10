<?php
/**
 * Single Page.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<div class="bdh-container bdh-page-wrap">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'bdh-page-article' ); ?>>
			<header class="bdh-page-header">
				<h1 class="bdh-page-title"><?php the_title(); ?></h1>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="bdh-page-thumb"><?php the_post_thumbnail( 'bdh-hero', array( 'loading' => 'eager' ) ); ?></div>
			<?php endif; ?>
			<div class="bdh-page-content">
				<?php the_content(); ?>
				<?php wp_link_pages( array(
					'before' => '<nav class="bdh-page-links">' . esc_html__( 'Pages:', 'benders-hub' ),
					'after'  => '</nav>',
				) ); ?>
			</div>
		</article>
	<?php endwhile; ?>
</div>

<?php get_footer(); ?>
