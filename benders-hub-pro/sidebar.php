<?php
/**
 * Sidebar
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<aside id="secondary" class="bdh-sidebar" role="complementary">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else : ?>
		<section class="bdh-widget bdh-widget-tool">
			<h3 class="bdh-widget-title"><?php esc_html_e( 'Quick Tools', 'benders-hub' ); ?></h3>
			<ul class="bdh-widget-tool-list">
				<li><a href="<?php echo esc_url( home_url( '/#bdh-quiz' ) ); ?>"><?php echo bdh_icon( 'tube' ); ?> <?php esc_html_e( 'Tube Bender Match Quiz', 'benders-hub' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#bdh-calculator' ) ); ?>"><?php echo bdh_icon( 'calculator' ); ?> <?php esc_html_e( 'Bend Radius Calculator', 'benders-hub' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#bdh-visualizer' ) ); ?>"><?php echo bdh_icon( 'compass' ); ?> <?php esc_html_e( 'Geometry Visualizer', 'benders-hub' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#bdh-workflow' ) ); ?>"><?php echo bdh_icon( 'gear' ); ?> <?php esc_html_e( 'Workflow Planner', 'benders-hub' ); ?></a></li>
			</ul>
		</section>
		<section class="bdh-widget bdh-widget-newsletter">
			<h3 class="bdh-widget-title"><?php esc_html_e( 'Workshop Newsletter', 'benders-hub' ); ?></h3>
			<p><?php esc_html_e( 'Weekly fabrication guides and bender reviews.', 'benders-hub' ); ?></p>
			<form data-bdh-newsletter>
				<input type="email" name="email" placeholder="<?php esc_attr_e( 'Email address', 'benders-hub' ); ?>" required>
				<button type="submit" class="bdh-btn bdh-btn-primary bdh-btn-block"><?php esc_html_e( 'Join', 'benders-hub' ); ?></button>
			</form>
		</section>
	<?php endif; ?>
</aside>
