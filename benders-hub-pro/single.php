<?php
/**
 * Single Post / Equipment / Guide.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<div class="bdh-container bdh-single-wrap">
	<div class="bdh-single-main">
		<?php while ( have_posts() ) : the_post();
			$is_product = get_post_type() === 'bdh_product';
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'bdh-single-article' ); ?>>

				<header class="bdh-single-header">
					<?php $cats = get_the_category(); if ( $cats ) : ?>
						<div class="bdh-single-cat"><?php echo esc_html( $cats[0]->name ); ?></div>
					<?php endif; ?>
					<h1 class="bdh-single-title"><?php the_title(); ?></h1>
					<div class="bdh-single-meta">
						<span><?php echo esc_html( get_the_date() ); ?></span>
						<span>·</span>
						<span><?php echo (int) bdh_reading_time(); ?> <?php esc_html_e( 'min read', 'benders-hub' ); ?></span>
						<span>·</span>
						<span><?php echo bdh_trust_badge( 'reviewed' ); ?></span>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="bdh-single-thumb">
						<?php the_post_thumbnail( 'bdh-hero', array( 'loading' => 'eager' ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $is_product ) :
					$kw      = get_post_meta( get_the_ID(), '_bdh_amazon_keyword', true );
					$score   = get_post_meta( get_the_ID(), '_bdh_editor_score', true );
					$capacity= get_post_meta( get_the_ID(), '_bdh_capacity', true );
					$tonnage = get_post_meta( get_the_ID(), '_bdh_tonnage', true );
					$pros    = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( get_the_ID(), '_bdh_pros', true ) ) ) );
					$cons    = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( get_the_ID(), '_bdh_cons', true ) ) ) );
				?>
				<div class="bdh-single-productbox">
					<div class="bdh-single-productbox-head">
						<div>
							<span class="bdh-single-productbox-tag"><?php esc_html_e( 'Quick Verdict', 'benders-hub' ); ?></span>
							<?php if ( $score ) : ?><span class="bdh-single-productbox-score"><?php echo esc_html( $score ); ?>/10</span><?php endif; ?>
						</div>
						<?php if ( $kw ) bdh_amazon_button( $kw, __( 'View on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-primary' ); ?>
					</div>
					<dl class="bdh-single-productbox-specs">
						<?php if ( $capacity ) : ?><div><dt><?php esc_html_e( 'Capacity', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $capacity ); ?></dd></div><?php endif; ?>
						<?php if ( $tonnage )  : ?><div><dt><?php esc_html_e( 'Power', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $tonnage ); ?></dd></div><?php endif; ?>
					</dl>
					<?php if ( $pros || $cons ) : ?>
					<div class="bdh-single-productbox-pcons">
						<?php if ( $pros ) : ?>
						<div class="bdh-pcons-pros">
							<h4><?php esc_html_e( 'Pros', 'benders-hub' ); ?></h4>
							<ul><?php foreach ( $pros as $p ) echo '<li>' . bdh_icon( 'check' ) . esc_html( $p ) . '</li>'; ?></ul>
						</div>
						<?php endif; if ( $cons ) : ?>
						<div class="bdh-pcons-cons">
							<h4><?php esc_html_e( 'Cons', 'benders-hub' ); ?></h4>
							<ul><?php foreach ( $cons as $c ) echo '<li>− ' . esc_html( $c ) . '</li>'; ?></ul>
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<div class="bdh-single-content">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
				</div>

				<footer class="bdh-single-footer">
					<?php the_tags( '<div class="bdh-single-tags"><span>' . esc_html__( 'Tags:', 'benders-hub' ) . '</span>', '', '</div>' ); ?>
				</footer>

			</article>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<div class="bdh-single-comments"><?php comments_template(); ?></div>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
	<aside class="bdh-single-sidebar">
		<?php get_sidebar(); ?>
	</aside>
</div>

<?php get_footer(); ?>
