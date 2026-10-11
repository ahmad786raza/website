<?php
/**
 * Single article (posts and fabrication guides).
 * Equipment entries use single-bdh_product.php.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();

	// Render content first so the TOC can be built from its headings.
	ob_start();
	the_content();
	wp_link_pages( array( 'before' => '<nav class="bdh-page-links">' . esc_html__( 'Pages:', 'benders-hub' ), 'after' => '</nav>' ) );
	$content = ob_get_clean();

	$has_aff  = bdh_post_has_affiliate( $post_id );
	$thumb_id = get_post_thumbnail_id( $post_id );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'bdh-article' ); ?>>
		<header class="bdh-article-header">
			<div class="bdh-container bdh-article-header-inner">
				<?php bdh_breadcrumbs(); ?>
				<?php echo bdh_kicker( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<h1 class="bdh-article-title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="bdh-article-dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<?php bdh_byline( $post_id ); ?>
			</div>
		</header>

		<?php if ( $thumb_id ) : ?>
			<figure class="bdh-article-figure bdh-container">
				<?php the_post_thumbnail( 'bdh-lead', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1240px) 1160px, 100vw' ) ); ?>
				<?php
				$caption = wp_get_attachment_caption( $thumb_id );
				$credit  = bdh_image_credit( $thumb_id );
				if ( $caption || $credit ) :
				?>
					<figcaption><?php echo esc_html( $caption ); ?> <?php echo $credit; // phpcs:ignore WordPress.Security.EscapeOutput ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<div class="bdh-container bdh-article-layout">
			<div class="bdh-article-main">
				<?php if ( $has_aff ) : ?>
					<aside class="bdh-disclosure-box" aria-label="<?php esc_attr_e( 'Affiliate disclosure', 'benders-hub' ); ?>">
						<?php echo bdh_icon( 'info' ); ?>
						<p>
							<strong><?php esc_html_e( 'Reader-supported.', 'benders-hub' ); ?></strong>
							<?php echo esc_html( BDH_AMAZON_DISCLOSURE ); ?>
							<?php esc_html_e( 'Recommendations are based on our research, not commission.', 'benders-hub' ); ?>
							<?php $m = bdh_page_url( 'methodology' ); if ( $m ) : ?><a href="<?php echo esc_url( $m ); ?>"><?php esc_html_e( 'How we evaluate equipment', 'benders-hub' ); ?></a><?php endif; ?>
						</p>
					</aside>
				<?php endif; ?>

				<?php
				// Safety-critical topics get a standing notice pointing to the governing rulebook.
				$hay = strtolower( get_the_title() . ' ' . implode( ' ', wp_list_pluck( get_the_category() ? get_the_category() : array(), 'slug' ) ) );
				if ( preg_match( '/roll[ -]?cage|rollbar|roll bar|chassis cert|sfi|nhra|scca|fia/', $hay ) ) :
				?>
					<aside class="bdh-callout bdh-callout--safety">
						<?php echo bdh_icon( 'shield' ); ?>
						<div>
							<p class="bdh-callout-title"><?php esc_html_e( 'Safety-critical: check the current rulebook', 'benders-hub' ); ?></p>
							<p><?php esc_html_e( 'Tube size, wall thickness, material, bend radius and welding requirements for roll cages are set by each sanctioning body and change between editions. Treat this article as background and confirm every requirement in the current rulebook for your class before you buy tube or start building.', 'benders-hub' ); ?></p>
						</div>
					</aside>
				<?php endif; ?>

				<?php bdh_toc( 'inline' ); ?>

				<div class="bdh-prose">
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content. ?>
				</div>

				<?php $steps = bdh_next_steps( $post_id ); $tools = bdh_tools(); ?>
				<section class="bdh-next-steps" aria-labelledby="bdh-next-title">
					<h2 id="bdh-next-title" class="bdh-next-title"><?php esc_html_e( 'Put it to work', 'benders-hub' ); ?></h2>
					<div class="bdh-next-grid">
						<?php foreach ( $steps as $key ) : if ( ! isset( $tools[ $key ] ) ) { continue; } $t = $tools[ $key ]; ?>
							<a class="bdh-next-card" href="<?php echo esc_url( bdh_tool_url( $key ) ); ?>">
								<span class="bdh-next-icon"><?php echo bdh_icon( $t['icon'] ); ?></span>
								<span><strong><?php echo esc_html( $t['label'] ); ?></strong><small><?php echo esc_html( $t['desc'] ); ?></small></span>
							</a>
						<?php endforeach; ?>
					</div>
				</section>

				<footer class="bdh-article-footer">
					<?php the_tags( '<div class="bdh-tags"><span class="bdh-tags-label">' . esc_html__( 'Tagged', 'benders-hub' ) . '</span>', '', '</div>' ); ?>

					<?php
					$author_id = (int) get_the_author_meta( 'ID' );
					$bio       = get_the_author_meta( 'description', $author_id );
					if ( $bio ) :
					?>
						<div class="bdh-author-box">
							<?php echo get_avatar( $author_id, 72, '', '', array( 'class' => 'bdh-avatar' ) ); ?>
							<div>
								<p class="bdh-author-name"><a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a></p>
								<p class="bdh-author-bio"><?php echo esc_html( $bio ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</footer>
			</div>

			<aside class="bdh-article-rail" aria-label="<?php esc_attr_e( 'Article tools', 'benders-hub' ); ?>">
				<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
					<div class="bdh-rail-widgets"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
				<?php endif; ?>
				<div class="bdh-rail-sticky">
					<?php bdh_toc( 'rail' ); ?>
					<div class="bdh-rail-card">
						<p class="bdh-rail-title"><?php esc_html_e( 'Choosing a bender?', 'benders-hub' ); ?></p>
						<p><?php esc_html_e( 'Answer nine questions about your tube and projects to see which type fits.', 'benders-hub' ); ?></p>
						<a class="bdh-btn bdh-btn-primary bdh-btn-sm bdh-btn-block" href="<?php echo esc_url( bdh_tool_url( 'quiz' ) ); ?>"><?php esc_html_e( 'Take the quiz', 'benders-hub' ); ?></a>
					</div>
				</div>
			</aside>
		</div>

		<?php
		$related = bdh_related_posts( $post_id, 3 );
		if ( $related ) :
		?>
			<section class="bdh-section bdh-related" aria-labelledby="bdh-related-title">
				<div class="bdh-container">
					<h2 class="bdh-section-title" id="bdh-related-title"><?php esc_html_e( 'Keep reading', 'benders-hub' ); ?></h2>
					<div class="bdh-card-grid">
						<?php foreach ( $related as $rid ) { bdh_post_card( $rid, 'card', 'h3' ); } ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<div class="bdh-container bdh-comments-wrap"><?php comments_template(); ?></div>
		<?php endif; ?>
	</article>
<?php endwhile; ?>

<?php get_footer(); ?>
