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

<?php if ( get_theme_mod( 'hbg_show_topbar', true ) ) : ?>
<div class="topbar">
	<div class="container topbar__inner">
		<div class="topbar__left">
			<span class="topbar__date"><?php echo esc_html( date_i18n( 'l, F j, Y' ) ); ?></span>
			<span class="topbar__dot" aria-hidden="true"></span>
			<span class="topbar__updated"><?php echo hbg_svg_icon( 'refresh', 12 ); // phpcs:ignore ?> <?php echo esc_html( get_theme_mod( 'hbg_hero_updated_label', __( 'Updated weekly', 'hoverboards-guide' ) ) ); ?></span>
		</div>

		<?php
		$ticker = hbg_trending_posts( 6 );
		if ( $ticker->have_posts() ) :
		?>
		<div class="topbar__ticker">
			<span class="ticker__label"><?php echo hbg_svg_icon( 'trending', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Trending', 'hoverboards-guide' ); ?></span>
			<div class="ticker" aria-label="<?php esc_attr_e( 'Trending articles', 'hoverboards-guide' ); ?>">
				<ul class="ticker__track">
					<?php for ( $pass = 0; $pass < 2; $pass++ ) : ?>
						<?php while ( $ticker->have_posts() ) : $ticker->the_post(); ?>
							<li class="ticker__item"<?php echo $pass ? ' aria-hidden="true"' : ''; ?>><a href="<?php the_permalink(); ?>"<?php echo $pass ? ' tabindex="-1"' : ''; ?>><?php the_title(); ?></a></li>
						<?php endwhile; $ticker->rewind_posts(); ?>
					<?php endfor; wp_reset_postdata(); ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>

		<div class="topbar__right">
			<?php hbg_social_icons( 'social social--topbar' ); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<header class="site-header" id="site-header" role="banner">
	<div class="container header__inner">

		<button type="button" class="icon-btn nav-toggle" aria-controls="mobile-drawer" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'hoverboards-guide' ); ?>">
			<?php echo hbg_svg_icon( 'menu', 22 ); // phpcs:ignore ?>
		</button>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="brand__icon" aria-hidden="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="M5 13l3-7h7l3 7"/></svg>
				</span>
				<span class="brand__name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				<?php $year = get_theme_mod( 'hbg_brand_year', gmdate( 'Y' ) ); if ( $year ) : ?>
					<span class="brand__year"><?php echo esc_html( $year ); ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</a>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'hoverboards-guide' ); ?>">
			<?php hbg_primary_menu( 'primary', 'nav-menu' ); ?>
		</nav>

		<div class="header__actions">
			<button type="button" class="icon-btn search-toggle" aria-controls="search-overlay" aria-expanded="false" aria-label="<?php esc_attr_e( 'Search', 'hoverboards-guide' ); ?>">
				<?php echo hbg_svg_icon( 'search', 20 ); // phpcs:ignore ?>
			</button>
			<?php if ( get_theme_mod( 'hbg_dark_mode_toggle', true ) ) : ?>
			<button type="button" class="icon-btn theme-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'hoverboards-guide' ); ?>" aria-pressed="false">
				<span class="theme-toggle__sun"><?php echo hbg_svg_icon( 'sun', 20 ); // phpcs:ignore ?></span>
				<span class="theme-toggle__moon"><?php echo hbg_svg_icon( 'moon', 20 ); // phpcs:ignore ?></span>
			</button>
			<?php endif; ?>
			<?php
			$cta_text = get_theme_mod( 'hbg_header_cta_text', __( 'Top picks', 'hoverboards-guide' ) );
			$cta_url  = get_theme_mod( 'hbg_header_cta_url', '' );
			if ( $cta_text ) :
			?>
			<a href="<?php echo esc_url( $cta_url ? $cta_url : home_url( '/#picks' ) ); ?>" class="btn btn--accent btn--sm header__cta"><?php echo hbg_svg_icon( 'bolt', 14 ); // phpcs:ignore ?> <?php echo esc_html( $cta_text ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( is_singular( 'post' ) ) : ?>
	<div class="progress" aria-hidden="true"><span class="progress__bar" id="reading-progress"></span></div>
	<?php endif; ?>
</header>

<!-- Search overlay -->
<div class="search-overlay" id="search-overlay" aria-hidden="true">
	<div class="container search-overlay__inner">
		<div class="search-overlay__head">
			<span class="search-overlay__title"><?php esc_html_e( 'Search the site', 'hoverboards-guide' ); ?></span>
			<button type="button" class="icon-btn search-close" aria-label="<?php esc_attr_e( 'Close search', 'hoverboards-guide' ); ?>"><?php echo hbg_svg_icon( 'close', 20 ); // phpcs:ignore ?></button>
		</div>
		<?php get_search_form(); ?>
		<div class="search-overlay__hint">
			<span><?php esc_html_e( 'Popular:', 'hoverboards-guide' ); ?></span>
			<?php
			$hint_cats = get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 6 ) );
			foreach ( $hint_cats as $c ) {
				echo '<a class="chip" href="' . esc_url( get_category_link( $c ) ) . '">' . esc_html( $c->name ) . '</a>';
			}
			?>
		</div>
	</div>
</div>

<!-- Mobile drawer -->
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
		<?php if ( $cta_text ) : ?>
			<a href="<?php echo esc_url( $cta_url ? $cta_url : home_url( '/#picks' ) ); ?>" class="btn btn--accent btn--full drawer__cta"><?php echo hbg_svg_icon( 'bolt', 14 ); // phpcs:ignore ?> <?php echo esc_html( $cta_text ); ?></a>
		<?php endif; ?>
		<?php hbg_social_icons( 'social social--drawer' ); ?>
	</div>
</div>
