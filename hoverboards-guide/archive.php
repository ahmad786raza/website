<?php
/**
 * Archive template (categories, tags, author, date).
 */
get_header();
$has_sidebar = 'none' !== get_theme_mod( 'hbg_sidebar_position', 'right' );
$term        = is_category() ? get_queried_object() : null;
$preset      = hbg_category_preset( $term );
$desc        = get_the_archive_description();
$desc        = $desc ? wp_strip_all_tags( $desc ) : ( $term ? $preset['blurb'] : '' );
$total       = (int) $GLOBALS['wp_query']->found_posts;
?>

<main id="main" class="site-main site-main--archive" role="main">

	<section class="archive-hero" style="--cat:<?php echo esc_attr( $preset['color'] ); ?>">
		<div class="archive-hero__bg" aria-hidden="true">
			<?php hbg_image( $preset['image'], array( 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw' ) ); ?>
		</div>
		<div class="container archive-hero__inner">
			<?php hbg_breadcrumbs(); ?>
			<div class="archive-hero__kicker">
				<?php
				if ( is_category() ) {
					esc_html_e( 'Category', 'hoverboards-guide' );
				} elseif ( is_tag() ) {
					esc_html_e( 'Tag', 'hoverboards-guide' );
				} elseif ( is_author() ) {
					esc_html_e( 'Author', 'hoverboards-guide' );
				} else {
					esc_html_e( 'Archive', 'hoverboards-guide' );
				}
				?>
			</div>
			<h1 class="archive-hero__title">
				<?php
				if ( is_category() ) {
					single_cat_title();
				} elseif ( is_tag() ) {
					single_tag_title();
				} elseif ( is_author() ) {
					echo esc_html( hbg_author_display_name( get_queried_object_id() ) );
				} elseif ( is_year() ) {
					echo esc_html( get_the_date( 'Y' ) );
				} elseif ( is_month() ) {
					echo esc_html( get_the_date( 'F Y' ) );
				} else {
					echo esc_html( get_the_archive_title() );
				}
				?>
			</h1>
			<?php if ( $desc ) : ?>
				<p class="archive-hero__desc"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>
			<div class="archive-hero__count"><?php printf( esc_html( _n( '%s article', '%s articles', $total, 'hoverboards-guide' ) ), esc_html( number_format_i18n( $total ) ) ); ?></div>
		</div>
	</section>

	<div class="container section--sm">
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
