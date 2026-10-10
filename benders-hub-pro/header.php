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
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="bdh-skip-link bdh-screen-reader-text" href="#bdh-main"><?php esc_html_e( 'Skip to content', 'benders-hub' ); ?></a>

<div id="bdh-page" class="bdh-site">

	<header id="bdh-masthead" class="bdh-header" role="banner">
		<div class="bdh-header-bar">
			<div class="bdh-container bdh-header-inner">

				<div class="bdh-branding">
					<?php if ( has_custom_logo() ) {
						the_custom_logo();
					} else { ?>
						<a class="bdh-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<span class="bdh-logo-mark"><?php echo bdh_icon( 'tube', 'bdh-logo-icon' ); ?></span>
							<span class="bdh-logo-text">
								<span class="bdh-logo-primary"><?php bloginfo( 'name' ); ?></span>
								<span class="bdh-logo-tag"><?php echo esc_html( get_bloginfo( 'description' ) ?: __( 'Fabrication Engineering Hub', 'benders-hub' ) ); ?></span>
							</span>
						</a>
					<?php } ?>
				</div>

				<nav id="bdh-primary-nav" class="bdh-primary-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'benders-hub' ); ?>">
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
					<button class="bdh-icon-btn bdh-search-toggle" aria-label="<?php esc_attr_e( 'Open search', 'benders-hub' ); ?>" aria-expanded="false" data-bdh-toggle="search">
						<?php echo bdh_icon( 'search' ); ?>
					</button>
					<a class="bdh-btn bdh-btn-amber bdh-cta-tool" href="#bdh-quiz">
						<?php esc_html_e( 'Find My Bender', 'benders-hub' ); ?>
					</a>
					<button class="bdh-icon-btn bdh-menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'benders-hub' ); ?>" aria-expanded="false" data-bdh-toggle="mobile-menu">
						<?php echo bdh_icon( 'menu' ); ?>
					</button>
				</div>

			</div>
		</div>

		<div class="bdh-header-search" data-bdh-panel="search" hidden>
			<div class="bdh-container">
				<form role="search" method="get" class="bdh-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="bdh-screen-reader-text" for="bdh-s"><?php esc_html_e( 'Search for:', 'benders-hub' ); ?></label>
					<input type="search" id="bdh-s" name="s" placeholder="<?php esc_attr_e( 'Search tube benders, calculators, guides…', 'benders-hub' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off">
					<button type="submit" class="bdh-btn bdh-btn-primary"><?php echo bdh_icon( 'search' ); ?> <?php esc_html_e( 'Search', 'benders-hub' ); ?></button>
				</form>
			</div>
		</div>

		<div class="bdh-mobile-menu" data-bdh-panel="mobile-menu" hidden>
			<div class="bdh-mobile-menu-inner">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'bdh-mobile-menu-list',
					'depth'          => 2,
					'fallback_cb'    => 'bdh_primary_menu_fallback',
				) );
				?>
				<div class="bdh-mobile-cta">
					<a class="bdh-btn bdh-btn-primary bdh-btn-block" href="#bdh-quiz"><?php esc_html_e( 'Take the Quiz', 'benders-hub' ); ?></a>
					<a class="bdh-btn bdh-btn-ghost bdh-btn-block" href="#bdh-calculator"><?php esc_html_e( 'Bend Calculator', 'benders-hub' ); ?></a>
				</div>
			</div>
		</div>
	</header>

	<?php
	// Affiliate disclosure (top thin bar)
	$show_disclosure = bdh_option( 'show_top_disclosure', 1 );
	if ( $show_disclosure ) : ?>
	<div class="bdh-disclosure-bar" role="note">
		<div class="bdh-container">
			<small><?php esc_html_e( 'Affiliate disclosure: As an Amazon Associate, Benders Hub earns from qualifying purchases. We only recommend fabrication tools we would use in our own workshop.', 'benders-hub' ); ?></small>
		</div>
	</div>
	<?php endif; ?>

	<main id="bdh-main" class="bdh-main" role="main">
