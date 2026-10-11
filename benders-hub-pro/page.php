<?php
/**
 * Pages. Pages that embed a tool shortcode get a wide layout.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) :
	the_post();
	$wide = bdh_post_has_tool( get_the_ID() );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'bdh-page' . ( $wide ? ' bdh-page--wide' : '' ) ); ?>>
		<header class="bdh-archive-header">
			<div class="bdh-container">
				<?php bdh_breadcrumbs(); ?>
				<h1 class="bdh-archive-title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><div class="bdh-archive-desc"><p><?php echo esc_html( get_the_excerpt() ); ?></p></div><?php endif; ?>
			</div>
		</header>
		<?php if ( has_post_thumbnail() && ! $wide ) : ?>
			<figure class="bdh-article-figure bdh-container"><?php the_post_thumbnail( 'bdh-lead', array( 'loading' => 'eager' ) ); ?></figure>
		<?php endif; ?>
		<div class="bdh-container">
			<div class="<?php echo $wide ? 'bdh-page-tool' : 'bdh-prose bdh-page-prose'; ?>">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<nav class="bdh-page-links">' . esc_html__( 'Pages:', 'benders-hub' ), 'after' => '</nav>' ) ); ?>
			</div>
		</div>
	</article>
	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="bdh-container bdh-comments-wrap">';
		comments_template();
		echo '</div>';
	}
endwhile;
get_footer();
