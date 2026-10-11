<?php
get_header();
?>
<main id="main" class="site-main site-main--page">
	<div class="container">
		<?php hbg_breadcrumbs(); ?>
		<div class="page-narrow">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article' ); ?>>
					<header class="single__header"><h1 class="single__title"><?php the_title(); ?></h1></header>
					<?php if ( has_post_thumbnail() ) : ?><figure class="single__hero"><?php the_post_thumbnail( 'large', array( 'class' => 'single__hero-img' ) ); ?></figure><?php endif; ?>
					<div class="post-content entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'hoverboards-guide' ), 'after' => '</nav>' ) ); ?>
					</div>
				</article>
				<?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
			<?php endwhile; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
