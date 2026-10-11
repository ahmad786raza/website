<?php
/**
 * Search results template.
 */
get_header();
$has_sidebar = 'none' !== get_theme_mod( 'hbg_sidebar_position', 'right' );
$total       = (int) $GLOBALS['wp_query']->found_posts;
?>

<main id="main" class="site-main site-main--search" role="main">
	<div class="container">
		<?php hbg_breadcrumbs(); ?>
		<header class="page-header page-header--archive search-header">
			<div class="section-heading__kicker"><?php esc_html_e( 'Search results', 'hoverboards-guide' ); ?></div>
			<h1 class="page-title"><?php printf( esc_html__( '“%s”', 'hoverboards-guide' ), esc_html( get_search_query() ) ); ?></h1>
			<p class="archive-desc"><?php printf( esc_html( _n( '%s result', '%s results', $total, 'hoverboards-guide' ) ), esc_html( number_format_i18n( $total ) ) ); ?></p>
			<?php get_search_form(); ?>
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
