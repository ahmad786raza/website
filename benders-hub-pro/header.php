<?php
/**
 * Header
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="bdh-skip-link" href="#bdh-main"><?php esc_html_e( 'Skip to content', 'benders-hub' ); ?></a>

<div id="bdh-page" class="bdh-site">

	<header id="bdh-masthead" class="bdh-header">
		<?php if ( bdh_option( 'show_top_disclosure', 1 ) ) : ?>
		<div class="bdh-topbar">
			<div class="bdh-container bdh-topbar-inner">
				<p class="bdh-topbar-note">
					<?php echo esc_html__( 'Reader-supported.', 'benders-hub' ) . ' ' . esc_html( BDH_AMAZON_DISCLOSURE ); ?>
					<?php $disc = bdh_page_url( 'affiliate-disclosure' ); if ( $disc ) : ?>
						<a href="<?php echo esc_url( $disc ); ?>"><?php esc_html_e( 'Learn more', 'benders-hub' ); ?></a>
					<?php endif; ?>
				</p>
				<?php if ( has_nav_menu( 'utility' ) ) : ?>
					<nav class="bdh-utility-nav" aria-label="<?php esc_attr_e( 'Utility', 'benders-hub' ); ?>">
						<?php wp_nav_menu( array( 'theme_location' => 'utility', 'container' => false, 'menu_class' => 'bdh-utility-menu', 'depth' => 1 ) ); ?>
					</nav>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

		<div class="bdh-header-bar">
			<div class="bdh-container bdh-header-inner">

				<div class="bdh-branding">
					<?php if ( has_custom_logo() ) {
						the_custom_logo();
					} else { ?>
						<a class="bdh-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — <?php esc_attr_e( 'home', 'benders-hub' ); ?>">
							<?php get_template_part( 'template-parts/logo-mark' ); ?>
							<span class="bdh-logo-text">
								<span class="bdh-logo-word"><?php bloginfo( 'name' ); ?></span>
								<span class="bdh-logo-tag"><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : __( 'Tube bending & fabrication', 'benders-hub' ) ); ?></span>
							</span>
						</a>
					<?php } ?>
				</div>

				<nav id="bdh-primary-nav" class="bdh-primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'benders-hub' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'bdh-menu',
						'depth'          => 2,
						'fallback_cb'    => 'bdh_primary_menu_fallback',
					) );
					?>
				</nav>

				<div class="bdh-header-actions">
					<button type="button" class="bdh-icon-btn bdh-search-toggle" aria-label="<?php esc_attr_e( 'Search', 'benders-hub' ); ?>" aria-expanded="false" aria-controls="bdh-search-panel" data-bdh-toggle="search">
						<?php echo bdh_icon( 'search' ); ?>
					</button>
					<a class="bdh-btn bdh-btn-primary bdh-btn-sm bdh-header-cta" href="<?php echo esc_url( bdh_tool_url( 'quiz' ) ); ?>">
						<?php echo bdh_icon( 'quiz' ); ?><span><?php esc_html_e( 'Find my bender', 'benders-hub' ); ?></span>
					</a>
					<button type="button" class="bdh-icon-btn bdh-menu-toggle" aria-label="<?php esc_attr_e( 'Menu', 'benders-hub' ); ?>" aria-expanded="false" aria-controls="bdh-mobile-menu" data-bdh-toggle="mobile-menu">
						<span class="bdh-menu-icon-open"><?php echo bdh_icon( 'menu' ); ?></span>
						<span class="bdh-menu-icon-close"><?php echo bdh_icon( 'close' ); ?></span>
					</button>
				</div>

			</div>
		</div>

		<div class="bdh-search-panel" id="bdh-search-panel" data-bdh-panel="search" hidden>
			<div class="bdh-container">
				<?php get_search_form(); ?>
			</div>
		</div>

		<div class="bdh-mobile-menu" id="bdh-mobile-menu" data-bdh-panel="mobile-menu" hidden>
			<div class="bdh-container">
				<nav aria-label="<?php esc_attr_e( 'Mobile', 'benders-hub' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'bdh-mobile-menu-list',
						'depth'          => 2,
						'fallback_cb'    => 'bdh_primary_menu_fallback',
					) );
					?>
				</nav>
				<div class="bdh-mobile-tools">
					<p class="bdh-rail-title"><?php esc_html_e( 'Engineering tools', 'benders-hub' ); ?></p>
					<ul>
						<?php foreach ( bdh_tools() as $key => $tool ) : if ( empty( $tool['primary'] ) ) { continue; } ?>
							<li><a href="<?php echo esc_url( bdh_tool_url( $key ) ); ?>"><?php echo bdh_icon( $tool['icon'] ); ?><?php echo esc_html( $tool['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</header>

	<main id="bdh-main" class="bdh-main">
