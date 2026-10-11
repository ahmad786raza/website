<?php
/**
 * Homepage Section B — editorial hero: lead story + secondary stories + masthead intro.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow  = bdh_option( 'hero_eyebrow', __( 'The tube bending & fabrication magazine', 'benders-hub' ) );
$headline = bdh_option( 'hero_headline', __( 'Plan better bends. Buy the right bender.', 'benders-hub' ) );
$sub      = bdh_option( 'hero_sub', __( 'Independent buying guides, technical explainers and free calculators for tube bending, exhaust work, roll cages and the home or professional workshop.', 'benders-hub' ) );
$cta1     = bdh_option( 'hero_cta_primary', __( 'Find my bender', 'benders-hub' ) );
$cta1u    = bdh_option( 'hero_cta_primary_url', '' );
$cta2     = bdh_option( 'hero_cta_secondary', __( 'Open bend calculator', 'benders-hub' ) );
$cta2u    = bdh_option( 'hero_cta_secondary_url', '' );
$cta1u    = ( ! $cta1u || '#bdh-quiz' === $cta1u ) ? bdh_tool_url( 'quiz' ) : $cta1u;
$cta2u    = ( ! $cta2u || '#bdh-calculator' === $cta2u ) ? bdh_tool_url( 'calculator' ) : $cta2u;

// Lead story: chosen in the Customizer, else the newest sticky post, else the newest post.
$lead_id = absint( get_theme_mod( 'bdh_featured_post', 0 ) );
if ( ! $lead_id || 'publish' !== get_post_status( $lead_id ) ) {
	$sticky  = get_option( 'sticky_posts' );
	$lead_id = 0;
	if ( $sticky ) {
		$s = get_posts( array( 'post__in' => $sticky, 'posts_per_page' => 1, 'fields' => 'ids', 'no_found_rows' => true ) );
		$lead_id = $s ? (int) $s[0] : 0;
	}
	if ( ! $lead_id ) {
		$s = get_posts( array( 'posts_per_page' => 1, 'fields' => 'ids', 'no_found_rows' => true, 'ignore_sticky_posts' => true ) );
		$lead_id = $s ? (int) $s[0] : 0;
	}
}
$secondary = array();
if ( $lead_id ) {
	$GLOBALS['bdh_shown_ids'][] = $lead_id;
	$secondary = get_posts( array( 'posts_per_page' => 3, 'post__not_in' => array( $lead_id ), 'fields' => 'ids', 'no_found_rows' => true, 'ignore_sticky_posts' => true ) );
	$GLOBALS['bdh_shown_ids'] = array_merge( $GLOBALS['bdh_shown_ids'], $secondary );
}
?>
<section class="bdh-hero" id="bdh-hero" aria-labelledby="bdh-home-title">
	<div class="bdh-container bdh-hero-grid">

		<div class="bdh-hero-intro">
			<p class="bdh-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<h1 class="bdh-hero-title" id="bdh-home-title"><?php echo wp_kses( $headline, array( 'br' => array(), 'em' => array(), 'strong' => array() ) ); ?></h1>
			<p class="bdh-hero-sub"><?php echo esc_html( $sub ); ?></p>
			<div class="bdh-hero-ctas">
				<a class="bdh-btn bdh-btn-primary" href="<?php echo esc_url( $cta1u ); ?>"><?php echo bdh_icon( 'quiz' ); ?><span><?php echo esc_html( $cta1 ); ?></span></a>
				<a class="bdh-btn bdh-btn-outline" href="<?php echo esc_url( $cta2u ); ?>"><?php echo bdh_icon( 'calculator' ); ?><span><?php echo esc_html( $cta2 ); ?></span></a>
			</div>
			<ul class="bdh-hero-paths" aria-label="<?php esc_attr_e( 'Start with your project', 'benders-hub' ); ?>">
				<?php foreach ( bdh_quick_start_paths() as $path ) : ?>
					<li><a href="<?php echo esc_url( home_url( '/#pathway-' . str_replace( '_', '-', $path['pathway'] ) ) ); ?>"><?php echo bdh_icon( $path['icon'] ); ?><span><?php echo esc_html( $path['label'] ); ?></span></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php if ( $lead_id ) : ?>
			<div class="bdh-hero-lead">
				<?php
				$thumb_id = get_post_thumbnail_id( $lead_id );
				$url      = get_permalink( $lead_id );
				?>
				<article class="bdh-lead">
					<a class="bdh-lead-media" href="<?php echo esc_url( $url ); ?>" tabindex="-1" aria-hidden="true">
						<?php echo bdh_post_image( $lead_id, 'bdh-lead', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => '', 'sizes' => '(min-width: 1100px) 760px, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
					<div class="bdh-lead-body">
						<?php echo bdh_kicker( $lead_id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<h2 class="bdh-lead-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $lead_id ) ); ?></a></h2>
						<p class="bdh-lead-excerpt"><?php echo esc_html( wp_trim_words( has_excerpt( $lead_id ) ? get_the_excerpt( $lead_id ) : wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $lead_id ) ) ), 34 ) ); ?></p>
						<p class="bdh-card-meta">
							<?php $lead_author = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $lead_id ) ); if ( $lead_author ) : ?>
								<span><?php echo esc_html( $lead_author ); ?></span>
								<span aria-hidden="true">·</span>
							<?php endif; ?>
							<time datetime="<?php echo esc_attr( get_the_date( 'c', $lead_id ) ); ?>"><?php echo esc_html( get_the_date( '', $lead_id ) ); ?></time>
							<span aria-hidden="true">·</span>
							<span><?php echo (int) bdh_reading_time( get_post_field( 'post_content', $lead_id ) ); ?> <?php esc_html_e( 'min read', 'benders-hub' ); ?></span>
						</p>
					</div>
				</article>
			</div>

			<?php if ( $secondary ) : ?>
				<div class="bdh-hero-secondary">
					<h2 class="bdh-rail-title"><?php esc_html_e( 'Also on Benders Hub', 'benders-hub' ); ?></h2>
					<?php foreach ( $secondary as $sid ) { bdh_post_card( $sid, 'list', 'h3' ); } ?>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="bdh-hero-lead bdh-hero-lead--brand">
				<?php
				$hero_att = function_exists( 'bdh_library_attachment' ) ? bdh_library_attachment( 'hero' ) : 0;
				if ( $hero_att ) {
					echo wp_get_attachment_image( $hero_att, 'bdh-lead', false, array( 'loading' => 'eager', 'fetchpriority' => 'high' ) );
				} elseif ( bdh_option( 'hero_image' ) ) {
					echo '<img src="' . esc_url( bdh_option( 'hero_image' ) ) . '" alt="" loading="eager" fetchpriority="high" width="1200" height="675">';
				} else {
					echo bdh_hero_blueprint_svg(); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				?>
			</div>
		<?php endif; ?>

	</div>
</section>
