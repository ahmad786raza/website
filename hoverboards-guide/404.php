<?php
get_header();
$popular = hbg_popular_posts( 6 );
?>
<main id="main" class="site-main site-main--404">
	<div class="container">
		<div class="e404">
			<div class="section-heading__kicker"><?php esc_html_e( 'Error 404', 'hoverboards-guide' ); ?></div>
			<h1 class="e404__title"><?php esc_html_e( 'That page isn’t here', 'hoverboards-guide' ); ?></h1>
			<p class="e404__sub"><?php esc_html_e( 'The link may be old or mistyped. Search the site or start from a topic.', 'hoverboards-guide' ); ?></p>
			<div class="e404__search"><?php get_search_form(); ?></div>
			<div class="e404__chips">
				<?php foreach ( hbg_topic_hubs() as $slug => $hub ) : $l = hbg_hub_link( $slug ); if ( $l['url'] ) : ?>
					<a class="chip" href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $hub['name'] ); ?></a>
				<?php endif; endforeach; ?>
				<a class="chip" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'hoverboards-guide' ); ?></a>
			</div>
		</div>
		<?php if ( $popular->have_posts() ) : ?>
		<section class="section section--sm" aria-labelledby="e404-popular">
			<?php hbg_section_heading( array( 'id' => 'e404-popular', 'kicker' => __( 'Most read', 'hoverboards-guide' ), 'title' => __( 'Guides readers go to first', 'hoverboards-guide' ) ) ); ?>
			<div class="grid grid--3">
				<?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
					<?php get_template_part( 'template-parts/content', 'card', array( 'compact' => true ) ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</section>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
