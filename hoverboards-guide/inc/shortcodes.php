<?php
/**
 * Shortcodes for review, comparison and guide layouts inside posts.
 *
 * [hbg_product id="hover1-titan"]                               registry product box
 * [hbg_product name="…" search="…|ASIN" wheel="…" speed="…" range="…" weight="…" cert="UL 2272" pros="a|b" cons="c" best_for="…" review="slug"]
 * [hbg_compare ids="gyroor-warrior-g2,hover1-titan"]            comparison table (all hoverboards when ids is empty)
 * [hbg_specs name="…" wheel="…" speed="…" range="…" weight="…" cert="…" motor="…" battery="…" charge="…" board_weight="…" source="…"]
 * [hbg_verdict title="Our verdict" best_for="…"]…[/hbg_verdict]
 * [hbg_pros_cons pros="a|b" cons="c"]
 * [hbg_box type="tip|warning|note|safety" title="…"]…[/hbg_box]
 * [hbg_steps]<ol>…</ol>[/hbg_steps]
 * [hbg_button url="…" text="…"]  or [hbg_button search="…"]
 * [hbg_picks]  [hbg_finder]  [hbg_newsletter]  [hbg_disclosure]
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hbg_sc_list( $value ) {
	return array_values( array_filter( array_map( 'trim', explode( '|', (string) $value ) ) ) );
}

function hbg_sc_product( $atts ) {
	$a = shortcode_atts( array( 'id' => '', 'name' => '', 'search' => '', 'asin' => '', 'image' => '', 'badge' => '', 'why' => '', 'best_for' => '', 'wheel' => '', 'speed' => '', 'range' => '', 'weight' => '', 'cert' => '', 'pros' => '', 'cons' => '', 'review' => '', 'label' => '', 'verified' => '' ), $atts, 'hbg_product' );
	$p = $a['id'] ? hbg_get_product( $a['id'] ) : null;
	if ( ! $p ) {
		if ( ! $a['name'] ) {
			return '';
		}
		$p = array( 'id' => sanitize_title( $a['name'] ), 'name' => $a['name'], 'kind' => 'hoverboard', 'search' => $a['name'], 'image' => '', 'image_note' => '', 'pros' => array(), 'cons' => array(), 'verified' => false, 'checked' => '', 'badge' => '', 'why' => '', 'best_for' => '', 'wheel' => '', 'speed' => '', 'range' => '', 'weight_cap' => '', 'certification' => '', 'review_slug' => '' );
	}
	$over = array( 'badge' => 'badge', 'why' => 'why', 'best_for' => 'best_for', 'wheel' => 'wheel', 'speed' => 'speed', 'range' => 'range', 'weight' => 'weight_cap', 'cert' => 'certification', 'review' => 'review_slug' );
	foreach ( $over as $att => $key ) {
		if ( '' !== $a[ $att ] ) {
			$p[ $key ] = $a[ $att ];
		}
	}
	if ( $a['pros'] ) {
		$p['pros'] = hbg_sc_list( $a['pros'] );
	}
	if ( $a['cons'] ) {
		$p['cons'] = hbg_sc_list( $a['cons'] );
	}
	if ( $a['asin'] || $a['search'] ) {
		$p['search'] = $a['asin'] ? $a['asin'] : $a['search'];
	}
	if ( $a['image'] ) {
		$p['image_id'] = is_numeric( $a['image'] ) ? (int) $a['image'] : attachment_url_to_postid( $a['image'] );
	}
	if ( '' !== $a['verified'] ) {
		$p['verified'] = in_array( strtolower( $a['verified'] ), array( '1', 'true', 'yes' ), true );
	}
	$review = hbg_product_review_url( $p );
	ob_start();
	?>
	<div class="product-box" data-product="<?php echo esc_attr( $p['id'] ); ?>">
		<div class="product-box__media">
			<?php hbg_product_image( $p, array( 'class' => 'product-image', 'ratio' => '4/3', 'sizes' => '(max-width: 640px) 100vw, 260px' ) ); ?>
			<?php if ( ! empty( $p['badge'] ) ) : ?><span class="product-box__badge"><?php echo esc_html( $p['badge'] ); ?></span><?php endif; ?>
		</div>
		<div class="product-box__body">
			<h3 class="product-box__name no-toc"><?php echo esc_html( $p['name'] ); ?></h3>
			<?php if ( ! empty( $p['why'] ) ) : ?><p class="product-box__why"><?php echo esc_html( $p['why'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $p['best_for'] ) ) : ?><p class="product-box__for"><strong><?php esc_html_e( 'Best for:', 'hoverboards-guide' ); ?></strong> <?php echo esc_html( $p['best_for'] ); ?></p><?php endif; ?>
			<?php hbg_spec_list( $p, array( 'wheel', 'weight_cap', 'speed', 'range', 'certification' ), 'specs product-box__specs' ); ?>
			<?php if ( ! empty( $p['pros'] ) || ! empty( $p['cons'] ) ) : ?>
				<div class="product-box__pc">
					<?php if ( ! empty( $p['pros'] ) ) : ?><div><strong class="pc-title pc-title--pros"><?php esc_html_e( 'Advantages', 'hoverboards-guide' ); ?></strong><ul><?php foreach ( (array) $p['pros'] as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
					<?php if ( ! empty( $p['cons'] ) ) : ?><div><strong class="pc-title pc-title--cons"><?php esc_html_e( 'Limitations', 'hoverboards-guide' ); ?></strong><ul><?php foreach ( (array) $p['cons'] as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="product-box__footer">
				<?php hbg_amazon_cta( $p, $a['label'] ? $a['label'] : '', 'md', false, 'product-box' ); ?>
				<?php if ( $review && $review !== get_permalink() ) : ?><a href="<?php echo esc_url( $review ); ?>" class="btn btn--ghost btn--md"><?php esc_html_e( 'View full review', 'hoverboards-guide' ); ?></a><?php endif; ?>
			</div>
			<p class="product-box__note"><?php echo esc_html( hbg_spec_source_note( $p ) ); ?></p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hbg_product', 'hbg_sc_product' );

function hbg_sc_compare( $atts ) {
	$a        = shortcode_atts( array( 'ids' => '', 'title' => '' ), $atts, 'hbg_compare' );
	$ids      = array_filter( array_map( 'trim', explode( ',', $a['ids'] ) ) );
	$products = $ids ? hbg_products_by_ids( $ids ) : hbg_get_compare_products();
	if ( ! $products ) {
		return '';
	}
	ob_start();
	get_template_part( 'template-parts/compare-table', null, array( 'products' => $products, 'title' => $a['title'], 'embedded' => true ) );
	return ob_get_clean();
}
add_shortcode( 'hbg_compare', 'hbg_sc_compare' );

function hbg_sc_specs( $atts ) {
	$a    = shortcode_atts( array( 'name' => '', 'wheel' => '', 'speed' => '', 'range' => '', 'weight' => '', 'cert' => '', 'motor' => '', 'battery' => '', 'charge' => '', 'board_weight' => '', 'age' => '', 'warranty' => '', 'source' => '', 'checked' => '' ), $atts, 'hbg_specs' );
	$rows = array_filter( array(
		__( 'Wheels / tires', 'hoverboards-guide' )       => $a['wheel'],
		__( 'Top speed', 'hoverboards-guide' )            => $a['speed'],
		__( 'Range', 'hoverboards-guide' )                => $a['range'],
		__( 'Max rider weight', 'hoverboards-guide' )     => $a['weight'],
		__( 'Safety certification', 'hoverboards-guide' ) => $a['cert'],
		__( 'Motor', 'hoverboards-guide' )                => $a['motor'],
		__( 'Battery', 'hoverboards-guide' )              => $a['battery'],
		__( 'Charge time', 'hoverboards-guide' )          => $a['charge'],
		__( 'Board weight', 'hoverboards-guide' )         => $a['board_weight'],
		__( 'Recommended age', 'hoverboards-guide' )      => $a['age'],
		__( 'Warranty', 'hoverboards-guide' )             => $a['warranty'],
	) );
	if ( ! $rows ) {
		return '';
	}
	$out = '<div class="spec-table"><div class="spec-table__head"><strong>' . esc_html( $a['name'] ? sprintf( /* translators: %s product */ __( '%s key specifications', 'hoverboards-guide' ), $a['name'] ) : __( 'Key specifications', 'hoverboards-guide' ) ) . '</strong></div><table><tbody>';
	foreach ( $rows as $label => $val ) {
		$out .= '<tr><th scope="row">' . esc_html( $label ) . '</th><td>' . esc_html( $val ) . '</td></tr>';
	}
	$out .= '</tbody></table><p class="spec-table__note">' . esc_html( $a['source'] ? $a['source'] : __( 'Manufacturer-advertised figures; real-world results vary with rider weight, terrain and temperature.', 'hoverboards-guide' ) ) . ( $a['checked'] ? ' ' . esc_html( sprintf( /* translators: %s date */ __( 'Checked %s.', 'hoverboards-guide' ), $a['checked'] ) ) : '' ) . '</p></div>';
	return $out;
}
add_shortcode( 'hbg_specs', 'hbg_sc_specs' );

function hbg_sc_pros_cons( $atts ) {
	$a    = shortcode_atts( array( 'pros' => '', 'cons' => '', 'pros_title' => __( 'Advantages', 'hoverboards-guide' ), 'cons_title' => __( 'Limitations', 'hoverboards-guide' ) ), $atts, 'hbg_pros_cons' );
	$pros = hbg_sc_list( $a['pros'] );
	$cons = hbg_sc_list( $a['cons'] );
	ob_start();
	?>
	<div class="pros-cons">
		<div class="pros-cons__col pros-cons__col--pros"><h4 class="no-toc"><?php echo hbg_svg_icon( 'thumbs-up', 16 ); // phpcs:ignore ?> <?php echo esc_html( $a['pros_title'] ); ?></h4><ul><?php foreach ( $pros as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul></div>
		<div class="pros-cons__col pros-cons__col--cons"><h4 class="no-toc"><?php echo hbg_svg_icon( 'thumbs-down', 16 ); // phpcs:ignore ?> <?php echo esc_html( $a['cons_title'] ); ?></h4><ul><?php foreach ( $cons as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul></div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hbg_pros_cons', 'hbg_sc_pros_cons' );

function hbg_sc_box( $atts, $content = '' ) {
	$a    = shortcode_atts( array( 'type' => 'note', 'title' => '' ), $atts, 'hbg_box' );
	$type = in_array( $a['type'], array( 'tip', 'warning', 'note', 'success', 'safety' ), true ) ? $a['type'] : 'note';
	$icon = array( 'tip' => 'bolt', 'warning' => 'alert', 'note' => 'info', 'success' => 'check', 'safety' => 'shield' );
	return '<div class="callout callout--' . esc_attr( $type ) . '">' . hbg_svg_icon( $icon[ $type ], 18 ) . '<div class="callout__body">' . ( $a['title'] ? '<strong class="callout__title">' . esc_html( $a['title'] ) . '</strong>' : '' ) . wpautop( do_shortcode( $content ) ) . '</div></div>';
}
add_shortcode( 'hbg_box', 'hbg_sc_box' );

function hbg_sc_verdict( $atts, $content = '' ) {
	$a = shortcode_atts( array( 'title' => __( 'Our verdict', 'hoverboards-guide' ), 'best_for' => '', 'search' => '', 'label' => '' ), $atts, 'hbg_verdict' );
	ob_start();
	?>
	<div class="verdict">
		<div class="verdict__head"><span class="verdict__kicker"><?php echo esc_html( $a['title'] ); ?></span></div>
		<div class="verdict__body"><?php echo wpautop( do_shortcode( $content ) ); // phpcs:ignore ?></div>
		<?php if ( $a['best_for'] ) : ?><p class="verdict__for"><strong><?php esc_html_e( 'Best for:', 'hoverboards-guide' ); ?></strong> <?php echo esc_html( $a['best_for'] ); ?></p><?php endif; ?>
		<?php if ( $a['search'] ) : ?><div class="verdict__cta"><?php hbg_amazon_cta( $a['search'], $a['label'], 'md', false, 'verdict' ); ?></div><?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hbg_verdict', 'hbg_sc_verdict' );

function hbg_sc_steps( $atts, $content = '' ) {
	return '<div class="steps">' . do_shortcode( $content ) . '</div>';
}
add_shortcode( 'hbg_steps', 'hbg_sc_steps' );

function hbg_sc_button( $atts ) {
	$a   = shortcode_atts( array( 'url' => '', 'text' => __( 'Check price on Amazon', 'hoverboards-guide' ), 'search' => '', 'size' => 'md', 'full' => '0' ), $atts, 'hbg_button' );
	$url = $a['url'] ? $a['url'] : ( $a['search'] ? hbg_amazon_url( $a['search'] ) : '' );
	if ( ! $url ) {
		return '';
	}
	$is_aff = ( false !== stripos( $url, 'amazon.' ) || false !== stripos( $url, 'amzn.to' ) );
	return '<a href="' . esc_url( $url ) . '" class="cta-amazon cta-amazon--' . sanitize_html_class( $a['size'] ) . ( '1' === $a['full'] ? ' cta-amazon--full' : '' ) . '" target="_blank" rel="' . ( $is_aff ? 'sponsored nofollow noopener' : 'noopener' ) . '"' . ( $is_aff ? ' data-hbg-track="affiliate" data-placement="button"' : '' ) . '><span>' . esc_html( $a['text'] ) . '</span>' . hbg_svg_icon( 'external', 14 ) . '</a>';
}
add_shortcode( 'hbg_button', 'hbg_sc_button' );

function hbg_sc_picks() {
	ob_start();
	get_template_part( 'template-parts/home/picks', null, array( 'embedded' => true ) );
	return ob_get_clean();
}
add_shortcode( 'hbg_picks', 'hbg_sc_picks' );
add_shortcode( 'hbg_top_picks', 'hbg_sc_picks' );

function hbg_sc_finder() {
	ob_start();
	get_template_part( 'template-parts/home/finder', null, array( 'embedded' => true ) );
	return ob_get_clean();
}
add_shortcode( 'hbg_finder', 'hbg_sc_finder' );

function hbg_sc_newsletter( $atts ) {
	$a = shortcode_atts( array( 'title' => '', 'sub' => '' ), $atts, 'hbg_newsletter' );
	ob_start();
	hbg_newsletter_form( array( 'variant' => 'inline', 'title' => $a['title'], 'sub' => $a['sub'] ) );
	return ob_get_clean();
}
add_shortcode( 'hbg_newsletter', 'hbg_sc_newsletter' );

function hbg_sc_disclosure() {
	ob_start();
	hbg_affiliate_disclosure_box();
	return ob_get_clean();
}
add_shortcode( 'hbg_disclosure', 'hbg_sc_disclosure' );
