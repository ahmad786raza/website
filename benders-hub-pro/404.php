<?php
/**
 * 404 — help the reader find what they wanted (search, topics, tools).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<section class="bdh-404">
	<div class="bdh-container">
		<header class="bdh-archive-header bdh-404-head">
			<p class="bdh-eyebrow">404</p>
			<h1 class="bdh-archive-title"><?php esc_html_e( 'This page has been cut from the drawing', 'benders-hub' ); ?></h1>
			<p class="bdh-archive-desc"><?php esc_html_e( 'The address may be mistyped or the page may have moved. Search, or start from a topic or tool below.', 'benders-hub' ); ?></p>
			<?php get_search_form(); ?>
		</header>

		<div class="bdh-404-grid">
			<div>
				<h2 class="bdh-rail-title"><?php esc_html_e( 'Topics', 'benders-hub' ); ?></h2>
				<ul class="bdh-rail-links">
					<?php foreach ( get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 8 ) ) as $cat ) : ?>
						<li><a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div>
				<h2 class="bdh-rail-title"><?php esc_html_e( 'Engineering tools', 'benders-hub' ); ?></h2>
				<ul class="bdh-rail-links">
					<?php foreach ( bdh_tools() as $key => $tool ) : if ( empty( $tool['primary'] ) ) { continue; } ?>
						<li><a href="<?php echo esc_url( bdh_tool_url( $key ) ); ?>"><?php echo bdh_icon( $tool['icon'] ); ?><?php echo esc_html( $tool['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div>
				<h2 class="bdh-rail-title"><?php esc_html_e( 'Latest articles', 'benders-hub' ); ?></h2>
				<?php foreach ( get_posts( array( 'posts_per_page' => 3, 'fields' => 'ids', 'no_found_rows' => true ) ) as $pid ) { bdh_post_card( $pid, 'list', 'h3' ); } ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
