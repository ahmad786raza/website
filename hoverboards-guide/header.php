<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'hoverboards-guide' ); ?></a>

<div class="site" id="top">

<header class="site-header<?php echo get_theme_mod( 'hbg_sticky_header', true ) ? ' site-header--sticky' : ''; ?>" id="site-header">
	<div class="container header__inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="brand__mark" aria-hidden="true">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="16" r="3"/><circle cx="18" cy="16" r="3"/><path d="M6 13h12l-2-5H8z"/></svg>
				</span>
				<span class="brand__name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<?php endif; ?>
		</a>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'hoverboards-guide' ); ?>">
			<?php hbg_primary_menu( 'primary', 'nav-menu' ); ?>
		</nav>

		<div class="header__actions">
			<button type="button" class="icon-btn search-toggle" aria-controls="search-overlay" aria-expanded="false" aria-label="<?php esc_attr_e( 'Search', 'hoverboards-guide' ); ?>">
				<?php echo hbg_svg_icon( 'search', 20 ); // phpcs:ignore ?>
			</button>
			<?php
			$cta_text = get_theme_mod( 'hbg_header_cta_text', __( 'Best hoverboards', 'hoverboards-guide' ) );
			$cta_url  = get_theme_mod( 'hbg_header_cta_url', '' );
			$cta_url  = $cta_url ? $cta_url : hbg_key_url( 'best' );
			if ( $cta_text && $cta_url ) :
			?>
			<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary btn--sm header__cta"><?php echo esc_html( $cta_text ); ?></a>
			<?php endif; ?>
			<button type="button" class="icon-btn nav-toggle" aria-controls="mobile-drawer" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'hoverboards-guide' ); ?>">
				<?php echo hbg_svg_icon( 'menu', 22 ); // phpcs:ignore ?>
			</button>
		</div>
	</div>
</header>
<div class="progress" id="reading-progress" aria-hidden="true"><span></span></div>

<div class="search-overlay" id="search-overlay" aria-hidden="true">
	<div class="container search-overlay__inner" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search the site', 'hoverboards-guide' ); ?>">
		<div class="search-overlay__head">
			<span class="search-overlay__title"><?php esc_html_e( 'Search reviews, guides and fixes', 'hoverboards-guide' ); ?></span>
			<button type="button" class="icon-btn search-close" aria-label="<?php esc_attr_e( 'Close search', 'hoverboards-guide' ); ?>"><?php echo hbg_svg_icon( 'close', 20 ); // phpcs:ignore ?></button>
		</div>
		<?php get_search_form(); ?>
		<div class="search-overlay__hint">
			<span><?php esc_html_e( 'Popular:', 'hoverboards-guide' ); ?></span>
			<?php
			foreach ( array( 'hoverboards-for-kids', 'all-terrain-hoverboards', 'hoverboard-troubleshooting', 'hoverboard-safety' ) as $hub_slug ) {
				$link = hbg_hub_link( $hub_slug );
				$hubs = hbg_topic_hubs();
				if ( $link['url'] ) {
					echo '<a class="chip" href="' . esc_url( $link['url'] ) . '">' . esc_html( $hubs[ $hub_slug ]['name'] ) . '</a>';
				}
			}
			?>
		</div>
	</div>
</div>

<div class="drawer" id="mobile-drawer" aria-hidden="true">
	<div class="drawer__backdrop" data-drawer-close></div>
	<div class="drawer__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'hoverboards-guide' ); ?>">
		<div class="drawer__head">
			<span class="drawer__brand"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<button type="button" class="icon-btn drawer__close" data-drawer-close aria-label="<?php esc_attr_e( 'Close menu', 'hoverboards-guide' ); ?>"><?php echo hbg_svg_icon( 'close', 22 ); // phpcs:ignore ?></button>
		</div>
		<div class="drawer__search"><?php get_search_form(); ?></div>
		<nav class="drawer__nav" aria-label="<?php esc_attr_e( 'Mobile', 'hoverboards-guide' ); ?>">
			<?php hbg_primary_menu( 'primary', 'nav-menu nav-menu--mobile' ); ?>
		</nav>
		<?php if ( $cta_text && $cta_url ) : ?>
			<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary btn--full drawer__cta"><?php echo esc_html( $cta_text ); ?></a>
		<?php endif; ?>
		<?php hbg_social_icons( 'social social--drawer' ); ?>
	</div>
</div>
