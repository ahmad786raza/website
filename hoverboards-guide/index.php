<?php
/**
 * Blog index (posts page) and generic fallback.
 */
get_header();
$has_sidebar = 'none' !== get_theme_mod( 'hbg_sidebar_position', 'right' );
?>

<main id="main" class="site-main site-main--blog" role="main">
	<div class="container">
		<?php hbg_breadcrumbs(); ?>
		<header class="page-header page-header--archive">
			<div class="section-heading__kicker"><?php esc_html_e( 'The magazine', 'hoverboards-guide' ); ?></div>
			<h1 class="page-title"><?php echo is_home() && ! is_front_page() && get_option( 'page_for_posts' ) ? esc_html( get_the_title( get_option( 'page_for_posts' ) ) ) : esc_html__( 'All articles', 'hoverboards-guide' ); ?></h1>
			<p class="archive-desc"><?php esc_html_e( 'Every review, guide and how-to, newest first.', 'hoverboards-guide' ); ?></p>
		</header>

		<div class="layout<?php echo $has_sidebar ? ' layout--sidebar' : ''; ?>">
			<div class="layout__main">
				<?php if ( have_posts() ) : ?>
					<div class="grid <?php echo $has_sidebar ? 'grid--2' : 'grid--3'; ?>">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'template-parts/content', 'card', array( 'heading' => 'h2' ) ); ?>
						<?php endwhile; ?>
					</div>
					<?php hbg_posts_pagination(); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</div>
			<?php if ( $has_sidebar ) { get_sidebar(); } ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
