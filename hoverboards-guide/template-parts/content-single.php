<?php
/**
 * Full article: header, hero image, disclosure (only when affiliate links exist), table of
 * contents, body, read-next, author box, previous/next in the same category, related articles.
 */
$show_toc   = get_theme_mod( 'hbg_show_toc', true );
$show_share = get_theme_mod( 'hbg_show_share', true );
$content    = apply_filters( 'the_content', get_the_content() );
$content    = str_replace( ']]>', ']]&gt;', $content );
$toc_items  = array();
if ( $show_toc && hbg_content_has_toc( $content ) ) {
	$show_toc = false;
}
if ( $show_toc ) {
	list( $content, $toc_items ) = hbg_toc_prepare( $content );
}
$standfirst = has_excerpt() ? get_the_excerpt() : '';
$affiliate  = hbg_content_has_affiliate_links( $content );
$cat        = hbg_primary_category();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single' ); ?>>

	<header class="single__header">
		<?php hbg_cat_badge(); ?>
		<h1 class="single__title"><?php the_title(); ?></h1>
		<?php if ( $standfirst ) : ?><p class="single__standfirst"><?php echo esc_html( wp_strip_all_tags( $standfirst ) ); ?></p><?php endif; ?>
		<div class="single__meta">
			<?php hbg_post_meta( array( 'author' => true, 'date' => true, 'updated' => true, 'reading' => true ) ); ?>
			<?php if ( $show_share ) { hbg_share_buttons(); } ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() && ! hbg_is_title_card() ) : ?>
		<figure class="single__hero">
			<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'single__hero-img', 'sizes' => '(max-width: 900px) 100vw, 800px' ) ); ?>
			<?php $caption = get_the_post_thumbnail_caption(); if ( $caption ) : ?><figcaption class="single__caption"><?php echo esc_html( $caption ); ?></figcaption><?php endif; ?>
		</figure>
	<?php endif; ?>

	<?php if ( $affiliate && get_theme_mod( 'hbg_show_disclosure', true ) ) { hbg_affiliate_disclosure_box(); } ?>

	<div class="single__body">
		<?php if ( $show_toc ) { hbg_render_toc( $toc_items ); } ?>
		<div class="post-content entry-content">
			<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php wp_link_pages( array( 'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'hoverboards-guide' ), 'after' => '</nav>' ) ); ?>
		<?php $tags = get_the_tags(); if ( $tags ) : ?>
			<div class="tags"><span class="tags__label"><?php esc_html_e( 'Topics', 'hoverboards-guide' ); ?></span><?php foreach ( $tags as $t ) : ?><a class="tag" href="<?php echo esc_url( get_tag_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a><?php endforeach; ?></div>
		<?php endif; ?>
	</div>

	<?php
	$next_reads = get_theme_mod( 'hbg_show_related', true ) ? hbg_read_next( get_the_ID(), 3 ) : array();
	if ( $next_reads ) :
	?>
	<aside class="read-next" aria-labelledby="read-next-title">
		<h2 class="read-next__title no-toc" id="read-next-title"><?php esc_html_e( 'Read next', 'hoverboards-guide' ); ?></h2>
		<ul class="read-next__list">
			<?php foreach ( $next_reads as $rp ) : ?>
				<li><a href="<?php echo esc_url( get_permalink( $rp ) ); ?>"><?php echo esc_html( get_the_title( $rp ) ); ?></a><span class="read-next__meta"><?php $c = hbg_primary_category( $rp->ID ); echo $c ? esc_html( $c->name ) : ''; ?></span></li>
			<?php endforeach; ?>
		</ul>
	</aside>
	<?php endif; ?>

	<?php if ( hbg_newsletter_enabled() ) { hbg_newsletter_form( array( 'variant' => 'inline' ) ); } ?>
	<?php if ( get_theme_mod( 'hbg_show_author_box', true ) ) { hbg_author_box(); } ?>

	<?php if ( is_active_sidebar( 'single-after-content' ) ) : ?>
		<div class="widget-row"><?php dynamic_sidebar( 'single-after-content' ); ?></div>
	<?php endif; ?>

	<?php
	if ( get_theme_mod( 'hbg_show_post_nav', true ) ) :
		$prev = get_previous_post( true, '', 'category' );
		$next = get_next_post( true, '', 'category' );
		if ( $prev || $next ) :
	?>
	<nav class="post-nav" aria-label="<?php esc_attr_e( 'More in this category', 'hoverboards-guide' ); ?>">
		<?php if ( $prev ) : ?>
			<a class="post-nav__item post-nav__item--prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>"><span class="post-nav__label"><?php echo hbg_svg_icon( 'chevron-left', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Previous', 'hoverboards-guide' ); ?></span><span class="post-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span></a>
		<?php endif; ?>
		<?php if ( $next ) : ?>
			<a class="post-nav__item post-nav__item--next" href="<?php echo esc_url( get_permalink( $next ) ); ?>"><span class="post-nav__label"><?php esc_html_e( 'Next', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'chevron-right', 14 ); // phpcs:ignore ?></span><span class="post-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span></a>
		<?php endif; ?>
	</nav>
	<?php endif; endif; ?>

	<?php if ( get_theme_mod( 'hbg_show_related', true ) ) { get_template_part( 'template-parts/related-posts' ); } ?>
</article>
