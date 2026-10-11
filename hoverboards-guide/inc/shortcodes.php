<?php
/**
 * Shortcodes for rich product content inside posts.
 *
 * [hbg_product id="hiboy-s2-pro"]                       — product box from the registry
 * [hbg_product name="Gotrax G4" search="Gotrax G4 electric scooter" image="https://…" score="8.4" range="25 mi" speed="20 mph" weight="220 lb" badge="Best Value"]
 * [hbg_pros_cons pros="Light|Cheap" cons="No suspension"]
 * [hbg_box type="tip|warning|note" title="Heads up"]…[/hbg_box]
 * [hbg_verdict score="8.8" title="Our verdict"]…[/hbg_verdict]
 * [hbg_button url="https://…" text="Check price on Amazon"]
 * [hbg_top_picks]                                       — the homepage top-picks grid
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hbg_sc_product( $atts ) {
	$a = shortcode_atts( array(
		'id'      => '',
		'name'    => '',
		'search'  => '',
		'asin'    => '',
		'image'   => '',
		'score'   => '',
		'badge'   => '',
		'tagline' => '',
		'range'   => '',
		'speed'   => '',
		'weight'  => '',
		'motor'   => '',
		'wheel'   => '',
		'bullets' => '',
		'label'   => '',
	), $atts, 'hbg_product' );

	$p = $a['id'] ? hbg_get_product( $a['id'] ) : null;
	if ( ! $p ) {
		if ( ! $a['name'] ) {
			return '';
		}
		$p = array(
			'name'       => $a['name'],
			'search'     => $a['asin'] ? $a['asin'] : ( $a['search'] ? $a['search'] : $a['name'] ),
			'score'      => $a['score'],
			'badge'      => $a['badge'],
			'tagline'    => $a['tagline'],
			'range'      => $a['range'],
			'speed'      => $a['speed'],
			'weight_cap' => $a['weight'],
			'motor'      => $a['motor'],
			'wheel'      => $a['wheel'],
			'bullets'    => $a['bullets'] ? array_map( 'trim', explode( '|', $a['bullets'] ) ) : array(),
			'image_url'  => $a['image'],
			'image'      => '',
			'tier'       => 0,
		);
	} else {
		foreach ( array( 'score', 'badge', 'tagline', 'range', 'speed', 'motor', 'wheel' ) as $k ) {
			if ( $a[ $k ] ) {
				$p[ $k ] = $a[ $k ];
			}
		}
		if ( $a['weight'] ) {
			$p['weight_cap'] = $a['weight'];
		}
		if ( $a['image'] ) {
			$p['image_url'] = $a['image'];
			$p['image']     = '';
		}
		if ( $a['asin'] || $a['search'] ) {
			$p['search'] = $a['asin'] ? $a['asin'] : $a['search'];
		}
	}

	ob_start();
	?>
	<div class="product-box">
		<div class="product-box__media">
			<?php if ( ! empty( $p['image_url'] ) ) : ?>
				<img src="<?php echo esc_url( $p['image_url'] ); ?>" alt="<?php echo esc_attr( $p['name'] ); ?>" loading="lazy" decoding="async">
			<?php else : ?>
				<?php hbg_product_image( $p, array( 'class' => 'product-image', 'ratio' => '4/3' ) ); ?>
			<?php endif; ?>
			<?php if ( ! empty( $p['badge'] ) ) : ?>
				<span class="product-box__badge"><?php echo esc_html( $p['badge'] ); ?></span>
			<?php endif; ?>
		</div>
		<div class="product-box__body">
			<div class="product-box__head">
				<h3 class="product-box__name"><?php echo esc_html( $p['name'] ); ?></h3>
				<?php if ( ! empty( $p['score'] ) ) { hbg_score_badge( $p['score'] ); } ?>
			</div>
			<?php if ( ! empty( $p['tagline'] ) ) : ?>
				<p class="product-box__tagline"><?php echo esc_html( $p['tagline'] ); ?></p>
			<?php endif; ?>
			<?php
			$specs = array(
				__( 'Range', 'hoverboards-guide' )      => ! empty( $p['range'] ) ? $p['range'] : '',
				__( 'Top speed', 'hoverboards-guide' )  => ! empty( $p['speed'] ) ? $p['speed'] : '',
				__( 'Max rider', 'hoverboards-guide' )  => ! empty( $p['weight_cap'] ) ? $p['weight_cap'] : '',
				__( 'Motor', 'hoverboards-guide' )      => ! empty( $p['motor'] ) ? $p['motor'] : '',
				__( 'Wheels', 'hoverboards-guide' )     => ! empty( $p['wheel'] ) ? $p['wheel'] : '',
			);
			$specs = array_filter( $specs );
			if ( $specs ) :
			?>
			<dl class="product-box__specs">
				<?php foreach ( $specs as $label => $val ) : ?>
					<div><dt><?php echo esc_html( $label ); ?></dt><dd><?php echo esc_html( $val ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
			<?php endif; ?>
			<?php if ( ! empty( $p['bullets'] ) ) : ?>
				<ul class="product-box__bullets">
					<?php foreach ( (array) $p['bullets'] as $b ) : ?>
						<li><?php echo hbg_svg_icon( 'check', 14 ); // phpcs:ignore ?> <?php echo esc_html( $b ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<div class="product-box__footer">
				<?php if ( ! empty( $p['tier'] ) && get_theme_mod( 'hbg_show_price_tiers', true ) ) : ?>
					<span class="price-tier"><?php echo esc_html( hbg_price_tier_label( $p['tier'] ) ); ?></span>
				<?php endif; ?>
				<?php hbg_amazon_cta( $p['search'], $a['label'] ? $a['label'] : '', 'md' ); ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hbg_product', 'hbg_sc_product' );

function hbg_sc_pros_cons( $atts ) {
	$a    = shortcode_atts( array( 'pros' => '', 'cons' => '', 'pros_title' => __( 'Pros', 'hoverboards-guide' ), 'cons_title' => __( 'Cons', 'hoverboards-guide' ) ), $atts, 'hbg_pros_cons' );
	$pros = array_filter( array_map( 'trim', explode( '|', $a['pros'] ) ) );
	$cons = array_filter( array_map( 'trim', explode( '|', $a['cons'] ) ) );
	ob_start();
	?>
	<div class="pros-cons">
		<div class="pros-cons__col pros-cons__col--pros">
			<h4><?php echo hbg_svg_icon( 'thumbs-up', 16 ); // phpcs:ignore ?> <?php echo esc_html( $a['pros_title'] ); ?></h4>
			<ul><?php foreach ( $pros as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul>
		</div>
		<div class="pros-cons__col pros-cons__col--cons">
			<h4><?php echo hbg_svg_icon( 'thumbs-down', 16 ); // phpcs:ignore ?> <?php echo esc_html( $a['cons_title'] ); ?></h4>
			<ul><?php foreach ( $cons as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hbg_pros_cons', 'hbg_sc_pros_cons' );

function hbg_sc_box( $atts, $content = '' ) {
	$a    = shortcode_atts( array( 'type' => 'note', 'title' => '' ), $atts, 'hbg_box' );
	$type = in_array( $a['type'], array( 'tip', 'warning', 'note', 'success' ), true ) ? $a['type'] : 'note';
	$icon = array( 'tip' => 'bolt', 'warning' => 'alert', 'note' => 'info', 'success' => 'check' );
	return '<div class="callout callout--' . esc_attr( $type ) . '">' . hbg_svg_icon( $icon[ $type ], 18 ) . '<div class="callout__body">' . ( $a['title'] ? '<strong class="callout__title">' . esc_html( $a['title'] ) . '</strong>' : '' ) . wpautop( do_shortcode( $content ) ) . '</div></div>';
}
add_shortcode( 'hbg_box', 'hbg_sc_box' );

function hbg_sc_verdict( $atts, $content = '' ) {
	$a = shortcode_atts( array( 'score' => '', 'title' => __( 'Our verdict', 'hoverboards-guide' ) ), $atts, 'hbg_verdict' );
	ob_start();
	?>
	<div class="verdict">
		<div class="verdict__head">
			<span class="verdict__kicker"><?php echo esc_html( $a['title'] ); ?></span>
			<?php if ( $a['score'] ) { hbg_score_badge( $a['score'], 'score-badge--lg' ); } ?>
		</div>
		<div class="verdict__body"><?php echo wpautop( do_shortcode( $content ) ); // phpcs:ignore ?></div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hbg_verdict', 'hbg_sc_verdict' );

function hbg_sc_button( $atts ) {
	$a = shortcode_atts( array( 'url' => '', 'text' => __( 'Check price on Amazon', 'hoverboards-guide' ), 'search' => '', 'size' => 'md', 'full' => '0' ), $atts, 'hbg_button' );
	$url = $a['url'] ? $a['url'] : hbg_amazon_url( $a['search'] );
	if ( ! $url ) {
		return '';
	}
	$is_aff = ( false !== stripos( $url, 'amazon.' ) || false !== stripos( $url, 'amzn.to' ) );
	return '<a href="' . esc_url( $url ) . '" class="cta-amazon cta-amazon--' . sanitize_html_class( $a['size'] ) . ( '1' === $a['full'] ? ' cta-amazon--full' : '' ) . '" target="_blank" rel="' . ( $is_aff ? 'sponsored nofollow noopener' : 'noopener' ) . '"><span>' . esc_html( $a['text'] ) . '</span>' . hbg_svg_icon( 'arrow-right', 14 ) . '</a>';
}
add_shortcode( 'hbg_button', 'hbg_sc_button' );

function hbg_sc_top_picks() {
	ob_start();
	get_template_part( 'template-parts/top-picks' );
	return ob_get_clean();
}
add_shortcode( 'hbg_top_picks', 'hbg_sc_top_picks' );

function hbg_sc_newsletter( $atts ) {
	$a = shortcode_atts( array( 'title' => '', 'sub' => '' ), $atts, 'hbg_newsletter' );
	ob_start();
	hbg_newsletter_form( array( 'variant' => 'inline', 'title' => $a['title'], 'sub' => $a['sub'], 'source' => 'shortcode' ) );
	return ob_get_clean();
}
add_shortcode( 'hbg_newsletter', 'hbg_sc_newsletter' );
