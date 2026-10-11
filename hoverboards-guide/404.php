<?php
/**
 * 404 template — keeps visitors moving with search, popular posts and top picks.
 */
get_header();
$popular = hbg_popular_posts( 6 );
?>

<main id="main" class="site-main site-main--404" role="main">

	<section class="e404">
		<div class="e404__bg" aria-hidden="true"><?php hbg_image( 'notfound', array( 'alt' => '', 'sizes' => '100vw', 'loading' => 'eager' ) ); ?></div>
		<div class="container e404__inner">
			<div class="e404__badge"><?php esc_html_e( 'Error 404', 'hoverboards-guide' ); ?></div>
			<h1 class="e404__title"><?php esc_html_e( 'Lost on the road?', 'hoverboards-guide' ); ?></h1>
			<p class="e404__sub"><?php esc_html_e( 'That page has rolled away. Search the site or jump straight to the guides other riders are reading right now.', 'hoverboards-guide' ); ?></p>
			<div class="e404__search"><?php get_search_form(); ?></div>
			<div class="e404__chips">
				<?php foreach ( get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 6 ) ) as $c ) : ?>
					<a class="chip chip--light" href="<?php echo esc_url( get_category_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a>
				<?php endforeach; ?>
				<a class="chip chip--light" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo hbg_svg_icon( 'home', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Home', 'hoverboards-guide' ); ?></a>
			</div>
		</div>
	</section>

	<?php if ( $popular->have_posts() ) : ?>
	<section class="section">
		<div class="container">
			<?php hbg_section_heading( array( 'kicker' => __( 'Popular right now', 'hoverboards-guide' ), 'title' => __( 'Most-read guides', 'hoverboards-guide' ) ) ); ?>
			<div class="grid grid--3">
				<?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
					<?php get_template_part( 'template-parts/content', 'card', array( 'compact' => true ) ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/top-picks' ); ?>

</main>

<?php get_footer(); ?>
