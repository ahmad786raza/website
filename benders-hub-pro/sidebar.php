<?php
/**
 * Sidebar (used by legacy calls to get_sidebar()).
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<aside class="bdh-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'benders-hub' ); ?>">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : dynamic_sidebar( 'sidebar-1' ); else : ?>
		<div class="bdh-rail-card">
			<p class="bdh-rail-title"><?php esc_html_e( 'Engineering tools', 'benders-hub' ); ?></p>
			<ul class="bdh-rail-links">
				<?php foreach ( bdh_tools() as $key => $tool ) : if ( empty( $tool['primary'] ) ) { continue; } ?>
					<li><a href="<?php echo esc_url( bdh_tool_url( $key ) ); ?>"><?php echo bdh_icon( $tool['icon'] ); ?><?php echo esc_html( $tool['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</aside>
