<?php
/**
 * Full single post.
 */
$show_toc   = get_theme_mod( 'hbg_show_toc', true );
$show_share = get_theme_mod( 'hbg_show_share', true );
$raw        = get_the_content();
$content    = apply_filters( 'the_content', $raw );
$content    = str_replace( ']]>', ']]&gt;', $content );
$toc_items  = array();
if ( $show_toc && hbg_content_has_toc( $content ) ) {
	$show_toc = false; // the post already carries its own table of contents — don't add a second one
}
if ( $show_toc ) {
	list( $content, $toc_items ) = hbg_toc_prepare( $content );
}
$standfirst = has_excerpt() ? get_the_excerpt() : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single' ); ?>>

	<header class="single__header">
		<?php hbg_cat_badge(); ?>
		<h1 class="single__title"><?php the_title(); ?></h1>
		<?php if ( $standfirst ) : ?>
			<p class="single__standfirst"><?php echo esc_html( wp_strip_all_tags( $standfirst ) ); ?></p>
		<?php endif; ?>
		<div class="single__meta">
			<?php hbg_post_meta( array( 'author' => true, 'updated' => true, 'reading' => true, 'views' => true ) ); ?>
			<?php if ( $show_share ) { hbg_share_buttons( 'bar' ); } ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="single__hero">
			<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'single__hero-img', 'sizes' => '(max-width: 900px) 100vw, 800px' ) ); ?>
			<?php $caption = get_the_post_thumbnail_caption(); if ( $caption ) : ?>
				<figcaption class="single__caption"><?php echo esc_html( $caption ); ?></figcaption>
			<?php endif; ?>
		</figure>
	<?php endif; ?>

	<?php if ( get_theme_mod( 'hbg_show_disclosure', true ) ) { hbg_affiliate_disclosure_box(); } ?>

	<div class="single__layout">
		<?php if ( $show_share ) : ?>
			<div class="single__share-sticky"><?php hbg_share_buttons( 'sticky' ); ?></div>
		<?php endif; ?>
		<div class="single__body">
			<?php if ( $show_toc ) { hbg_render_toc( $toc_items ); } ?>
			<div class="post-content entry-content">
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php
			wp_link_pages( array( 'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'hoverboards-guide' ), 'after' => '</nav>' ) );
			$tags = get_the_tags();
			if ( $tags ) :
			?>
			<div class="tags">
				<span class="tags__label"><?php echo hbg_svg_icon( 'tag', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Tagged', 'hoverboards-guide' ); ?></span>
				<?php foreach ( $tags as $t ) : ?>
					<a class="tag" href="<?php echo esc_url( get_tag_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<?php if ( $show_share ) : ?>
				<div class="single__share-bottom"><?php hbg_share_buttons( 'bar' ); ?></div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( get_theme_mod( 'hbg_show_post_newsletter', true ) ) : ?>
		<?php hbg_newsletter_form( array( 'variant' => 'inline', 'source' => 'post' ) ); ?>
	<?php endif; ?>

	<?php if ( get_theme_mod( 'hbg_show_author_box', true ) ) { hbg_author_box(); } ?>

	<?php if ( is_active_sidebar( 'single-after-content' ) ) : ?>
		<div class="widget-row"><?php dynamic_sidebar( 'single-after-content' ); ?></div>
	<?php endif; ?>

	<?php
	if ( get_theme_mod( 'hbg_show_post_nav', true ) ) :
		$prev = get_previous_post();
		$next = get_next_post();
		if ( $prev || $next ) :
	?>
	<nav class="post-nav" aria-label="<?php esc_attr_e( 'Post navigation', 'hoverboards-guide' ); ?>">
		<?php if ( $prev ) : ?>
			<a class="post-nav__item post-nav__item--prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
				<span class="post-nav__label"><?php echo hbg_svg_icon( 'chevron-left', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Previous', 'hoverboards-guide' ); ?></span>
				<span class="post-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
			</a>
		<?php endif; ?>
		<?php if ( $next ) : ?>
			<a class="post-nav__item post-nav__item--next" href="<?php echo esc_url( get_permalink( $next ) ); ?>">
				<span class="post-nav__label"><?php esc_html_e( 'Next', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'chevron-right', 14 ); // phpcs:ignore ?></span>
				<span class="post-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
			</a>
		<?php endif; ?>
	</nav>
	<?php endif; endif; ?>

	<?php if ( get_theme_mod( 'hbg_show_related', true ) ) { get_template_part( 'template-parts/related-posts' ); } ?>

</article>
