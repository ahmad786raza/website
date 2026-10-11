<?php
/**
 * Archives: category hubs (with intro and sub-topic chips), tags, authors, dates.
 */
get_header();
$has_sidebar = 'none' !== get_theme_mod( 'hbg_sidebar_position', 'right' );
$term        = ( is_category() || is_tag() ) ? get_queried_object() : null;
$hubs        = hbg_topic_hubs();
$desc        = $term ? wp_strip_all_tags( term_description( $term ) ) : '';
if ( ! $desc && $term && isset( $hubs[ $term->slug ] ) ) {
	$desc = $hubs[ $term->slug ]['desc'];
}
if ( ! $desc && is_category() ) {
	$desc = hbg_category_preset( $term )['blurb'];
}
$children = is_category() ? get_terms( array( 'taxonomy' => 'category', 'parent' => $term->term_id, 'hide_empty' => true ) ) : array();
$related  = array();
if ( is_category() ) {
	if ( $term->parent ) {
		$parent = get_term( $term->parent, 'category' );
		if ( $parent && ! is_wp_error( $parent ) ) {
			$related[] = $parent;
		}
		$siblings = get_terms( array( 'taxonomy' => 'category', 'parent' => $term->parent, 'hide_empty' => true, 'exclude' => array( $term->term_id ) ) );
		if ( $siblings && ! is_wp_error( $siblings ) ) {
			$related = array_merge( $related, $siblings );
		}
	} elseif ( $children && ! is_wp_error( $children ) ) {
		$related = $children;
	}
}
$total  = (int) $GLOBALS['wp_query']->found_posts;
$layout = hbg_archive_layout( $term );
?>
<main id="main" class="site-main site-main--archive">
	<div class="container">
		<?php hbg_breadcrumbs(); ?>
		<header class="archive-header"<?php if ( is_category() ) { $ap = hbg_category_preset( $term ); echo ' style="--cat:' . esc_attr( $ap['color'] ) . '"'; } ?>>
			<div class="section-heading__kicker">
				<?php
				if ( is_category() ) {
					esc_html_e( 'Topic', 'hoverboards-guide' );
				} elseif ( is_tag() ) {
					esc_html_e( 'Tag', 'hoverboards-guide' );
				} elseif ( is_author() ) {
					esc_html_e( 'Author', 'hoverboards-guide' );
				} else {
					esc_html_e( 'Archive', 'hoverboards-guide' );
				}
				?>
			</div>
			<h1 class="archive-header__title">
				<?php
				if ( is_category() ) {
					single_cat_title();
				} elseif ( is_tag() ) {
					single_tag_title();
				} elseif ( is_author() ) {
					echo esc_html( hbg_author_display_name( get_queried_object_id() ) );
				} else {
					echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
				}
				?>
			</h1>
			<?php if ( $desc ) : ?><p class="archive-header__desc"><?php echo esc_html( $desc ); ?></p><?php endif; ?>
			<p class="archive-header__count"><?php printf( esc_html( _n( '%s article', '%s articles', $total, 'hoverboards-guide' ) ), esc_html( number_format_i18n( $total ) ) ); ?></p>
			<?php if ( $related ) : ?>
				<div class="archive-header__chips">
					<span class="archive-header__chips-label"><?php echo $term->parent ? esc_html__( 'Related topics:', 'hoverboards-guide' ) : esc_html__( 'Browse by topic:', 'hoverboards-guide' ); ?></span>
					<?php foreach ( $related as $c ) : ?><a class="chip" href="<?php echo esc_url( get_category_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?> <span class="count"><?php echo esc_html( number_format_i18n( $c->count ) ); ?></span></a><?php endforeach; ?>
				</div>
			<?php endif; ?>
		</header>

		<div class="layout<?php echo $has_sidebar ? ' layout--sidebar' : ''; ?>">
			<div class="layout__main">
				<?php if ( have_posts() ) : ?>
					<?php if ( 'list' === $layout ) : ?>
						<div class="post-rows">
							<?php while ( have_posts() ) : the_post(); hbg_row( get_post(), array( 'heading' => 'h2', 'excerpt' => true, 'class' => 'row--wide' ) ); endwhile; ?>
						</div>
					<?php else : ?>
						<?php if ( 'lead-grid' === $layout && ! is_paged() ) : the_post(); ?>
							<div class="archive-lead">
								<?php get_template_part( 'template-parts/content', 'card', array( 'heading' => 'h2', 'variant' => 'feature' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="grid <?php echo $has_sidebar ? 'grid--2' : 'grid--3'; ?>">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'template-parts/content', 'card', array( 'heading' => 'h2' ) ); ?>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
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
