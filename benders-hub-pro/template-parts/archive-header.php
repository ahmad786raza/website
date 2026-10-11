<?php
/**
 * Archive/listing page header.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = isset( $args['title'] ) ? $args['title'] : get_the_archive_title();
$desc  = isset( $args['desc'] ) ? $args['desc'] : get_the_archive_description();
$eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
?>
<header class="bdh-archive-header">
	<div class="bdh-container">
		<?php bdh_breadcrumbs(); ?>
		<?php if ( $eyebrow ) : ?><p class="bdh-eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
		<h1 class="bdh-archive-title"><?php echo wp_kses_post( $title ); ?></h1>
		<?php if ( $desc ) : ?><div class="bdh-archive-desc"><?php echo wp_kses_post( wpautop( $desc ) ); ?></div><?php endif; ?>
	</div>
</header>
