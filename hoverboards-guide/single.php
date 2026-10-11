<?php
/**
 * Single post.
 */
get_header();
?>
<main id="main" class="site-main site-main--single">
	<div class="container">
		<?php hbg_breadcrumbs(); ?>
		<div class="layout layout--sidebar">
			<div class="layout__main">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'single' );
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
				?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
