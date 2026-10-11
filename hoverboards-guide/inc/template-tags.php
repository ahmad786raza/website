<?php
/**
 * Template tags: icons, images, product cards, post helpers, breadcrumbs, TOC,
 * related content, author box, disclosure, pagination, topic tiles.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =========================================================================
// SVG icon library (inline, currentColor)
// =========================================================================
function hbg_svg_icon( $name, $size = 18, $class = '' ) {
	$paths = array(
		'search'        => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'menu'          => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'         => '<path d="M6 6l12 12M18 6 6 18"/>',
		'arrow-right'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-up'      => '<path d="M12 19V5M5 12l7-7 7 7"/>',
		'check'         => '<path d="M5 12l5 5L20 7"/>',
		'check-circle'  => '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>',
		'clock'         => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
		'eye'           => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
		'calendar'      => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/>',
		'refresh'       => '<path d="M3 12a9 9 0 0 1 15-6.7L21 8M21 3v5h-5M21 12a9 9 0 0 1-15 6.7L3 16M3 21v-5h5"/>',
		'user'          => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/>',
		'share'         => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/>',
		'facebook'      => '<path d="M14 8h3V4h-3c-2.8 0-4 1.8-4 4v2H7v4h3v6h4v-6h3l1-4h-4V8.5c0-.3.2-.5.5-.5z" fill="currentColor" stroke="none"/>',
		'x'             => '<path d="M4 4l16 16M20 4 4 20"/>',
		'instagram'     => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>',
		'youtube'       => '<path d="M22 8.5c-.2-1.4-.9-2.3-2.3-2.5C17.6 5.7 12 5.7 12 5.7s-5.6 0-7.7.3C2.9 6.2 2.2 7.1 2 8.5 1.7 10.6 1.7 12 1.7 12s0 1.4.3 3.5c.2 1.4.9 2.3 2.3 2.5 2.1.3 7.7.3 7.7.3s5.6 0 7.7-.3c1.4-.2 2.1-1.1 2.3-2.5.3-2.1.3-3.5.3-3.5s0-1.4-.3-3.5z"/><path d="M10 9.5v5l4.5-2.5z" fill="currentColor" stroke="none"/>',
		'pinterest'     => '<circle cx="12" cy="12" r="9"/><path d="M9 20l2-8M10.5 12c-1.2-.3-2-1.3-2-2.7 0-2 1.6-3.8 4.2-3.8 2.2 0 3.8 1.4 3.8 3.3 0 2.5-1.3 4.6-3.3 4.6-.9 0-1.6-.5-1.6-1.2"/>',
		'tiktok'        => '<path d="M14 4v10.5a3.5 3.5 0 1 1-3.5-3.5M14 4c0 2.5 2 4.5 4.5 4.5"/>',
		'whatsapp'      => '<path d="M4 20l1.3-3.8A8 8 0 1 1 8 18.7L4 20z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 1a4 4 0 0 1-2-2l1-1-1-2-1.5 1z" fill="currentColor" stroke="none"/>',
		'mail'          => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'link'          => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
		'chevron-down'  => '<path d="m6 9 6 6 6-6"/>',
		'chevron-right' => '<path d="m9 6 6 6-6 6"/>',
		'chevron-left'  => '<path d="m15 6-6 6 6 6"/>',
		'battery'       => '<rect x="3" y="7" width="16" height="10" rx="2"/><path d="M21 11v2M7 12h6"/>',
		'shield'        => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/>',
		'speed'         => '<path d="M4 15a8 8 0 1 1 16 0"/><path d="M12 15l4-5"/><circle cx="12" cy="15" r="1.5" fill="currentColor"/>',
		'weight'        => '<rect x="6" y="9" width="12" height="11" rx="2"/><circle cx="12" cy="6" r="2"/>',
		'terrain'       => '<path d="M3 18l5-7 4 5 3-4 6 6"/>',
		'bolt'          => '<path d="M13 2 4 14h7l-1 8 9-12h-7l1-8z"/>',
		'tag'           => '<path d="M3 12V4h8l9 9-8 8-9-9z"/><circle cx="7.5" cy="8.5" r="1.5" fill="currentColor"/>',
		'compare'       => '<path d="M9 3v18M15 3v18M3 8h6M15 8h6M3 16h6M15 16h6"/>',
		'external'      => '<path d="M14 4h6v6M20 4l-9 9M19 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5"/>',
		'info'          => '<circle cx="12" cy="12" r="10"/><path d="M12 8v.01M12 11v6"/>',
		'alert'         => '<path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5M12 18v.01"/>',
		'list'          => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
		'thumbs-up'     => '<path d="M7 11v9H3v-9h4zM7 11l4-8a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2.4l-1.2 6A2 2 0 0 1 16.8 20H7"/>',
		'thumbs-down'   => '<path d="M17 13V4h4v9h-4zM17 13l-4 8a2 2 0 0 1-2-2v-4H6a2 2 0 0 1-2-2.4l1.2-6A2 2 0 0 1 7.2 4H17"/>',
		'home'          => '<path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1v-9z"/>',
		'helmet'        => '<path d="M4 14a8 8 0 0 1 16 0v3H4z"/><path d="M4 17h16M9 14h11"/>',
		'ruler'         => '<path d="M3 17 17 3l4 4L7 21z"/><path d="m8 12 2 2M11 9l2 2M14 6l2 2"/>',
		'wrench'        => '<path d="M14.7 6.3a4 4 0 0 0 5 5L9.4 21.6a2 2 0 0 1-2.8-2.8L16.9 8.5"/><path d="M14.7 6.3 18 3l3 3-3.3 3.3"/>',
		'book'          => '<path d="M4 4h7a2 2 0 0 1 2 2v14a2 2 0 0 0-2-2H4zM20 4h-7a2 2 0 0 0-2 2v14a2 2 0 0 1 2-2h7z"/>',
		'compass'       => '<circle cx="12" cy="12" r="9"/><path d="m15 9-2 6-4 0 2-6z" fill="currentColor" stroke="none"/>',
		'bluetooth'     => '<path d="M7 7l10 10-5 5V2l5 5L7 17"/>',
		'kid'           => '<circle cx="12" cy="7" r="3.5"/><path d="M6 21c0-3.5 2.7-6 6-6s6 2.5 6 6"/>',
		'star'          => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
		'cart'          => '<circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/><path d="M2 3h3l2.5 11h11L21 7H6"/>',
		'filter'        => '<path d="M3 5h18l-7 8v6l-4 2v-8z"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon icon-' . esc_attr( $name ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

// =========================================================================
// Bundled images (see inc/image-manifest.php) and original illustrations
// =========================================================================
function hbg_image_entry( $name ) {
	$manifest = hbg_image_manifest();
	return isset( $manifest[ $name ] ) ? $manifest[ $name ] : null;
}

function hbg_image_exists( $name ) {
	return (bool) hbg_image_entry( $name );
}

function hbg_image_url( $name, $width = 0, $ext = 'webp' ) {
	$e = hbg_image_entry( $name );
	if ( ! $e ) {
		return '';
	}
	if ( ! $width || ! isset( $e['sizes'][ (int) $width ] ) ) {
		$width = max( array_keys( $e['sizes'] ) );
	}
	return HBG_URI . '/assets/images/' . $name . '-' . (int) $width . '.' . $ext;
}

/** Responsive <img> (or <picture> when a JPEG fallback exists) for a bundled image. */
function hbg_image( $name, $args = array() ) {
	$m = hbg_image_entry( $name );
	if ( ! $m ) {
		return '';
	}
	$a = wp_parse_args( $args, array( 'alt' => '', 'class' => '', 'sizes' => '(max-width: 640px) 100vw, 640px', 'loading' => 'lazy', 'fetchpriority' => '', 'echo' => true ) );
	$widths = array_keys( $m['sizes'] );
	sort( $widths );
	$max   = end( $widths );
	$dims  = $m['sizes'][ $max ];
	$parts = array();
	foreach ( $widths as $wd ) {
		$parts[] = hbg_image_url( $name, $wd ) . ' ' . (int) $wd . 'w';
	}
	$srcset = implode( ', ', $parts );
	$attrs  = ' width="' . (int) $dims[0] . '" height="' . (int) $dims[1] . '" alt="' . esc_attr( $a['alt'] ) . '"'
		. ( $a['class'] ? ' class="' . esc_attr( $a['class'] ) . '"' : '' )
		. ' loading="' . esc_attr( $a['loading'] ) . '" decoding="async"'
		. ( $a['fetchpriority'] ? ' fetchpriority="' . esc_attr( $a['fetchpriority'] ) . '"' : '' );
	if ( ! empty( $m['jpg'] ) ) {
		$html = '<picture><source type="image/webp" srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $a['sizes'] ) . '"><img src="' . esc_url( hbg_image_url( $name, $max, 'jpg' ) ) . '"' . $attrs . '></picture>';
	} else {
		$html = '<img src="' . esc_url( hbg_image_url( $name, $max ) ) . '" srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $a['sizes'] ) . '"' . $attrs . '>';
	}
	if ( $a['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
	}
	return $html;
}

/**
 * Topic / section image. Accepts an attachment ID, a bundled image name or "svg:<illustration>".
 * The Customizer can override any topic image with an upload (hbg_img_<key>).
 */
function hbg_topic_image( $ref, $args = array() ) {
	$a = wp_parse_args( $args, array( 'alt' => '', 'class' => '', 'sizes' => '(max-width: 640px) 100vw, 400px', 'loading' => 'lazy', 'fetchpriority' => '', 'echo' => true, 'size' => 'medium_large' ) );
	$html = '';
	if ( is_numeric( $ref ) && (int) $ref > 0 ) {
		$attr = array( 'class' => $a['class'], 'loading' => $a['loading'], 'decoding' => 'async', 'sizes' => $a['sizes'], 'alt' => $a['alt'] );
		if ( $a['fetchpriority'] ) {
			$attr['fetchpriority'] = $a['fetchpriority'];
		}
		$html = wp_get_attachment_image( (int) $ref, $a['size'], false, $attr );
	} elseif ( is_string( $ref ) && 0 === strpos( $ref, 'svg:' ) ) {
		$html = hbg_illustration( substr( $ref, 4 ), array( 'class' => $a['class'], 'alt' => $a['alt'] ) );
	} elseif ( is_string( $ref ) && hbg_image_exists( $ref ) ) {
		$html = hbg_image( $ref, array( 'alt' => $a['alt'], 'class' => $a['class'], 'sizes' => $a['sizes'], 'loading' => $a['loading'], 'fetchpriority' => $a['fetchpriority'], 'echo' => false ) );
	}
	if ( ! $html ) {
		$html = hbg_illustration( 'hoverboard', array( 'class' => $a['class'], 'alt' => $a['alt'] ) );
	}
	if ( $a['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	return $html;
}

// -------------------------------------------------------------------------
// Product image: owner-uploaded attachment, else bundled representative photo,
// else an original illustration. Non-product-specific images are labelled.
// -------------------------------------------------------------------------
function hbg_product_image( $product, $args = array() ) {
	$a = wp_parse_args( $args, array( 'class' => 'product-image', 'ratio' => '4/3', 'sizes' => '(max-width: 640px) 100vw, 400px', 'note' => true, 'echo' => true, 'loading' => 'lazy' ) );
	$label = isset( $product['name'] ) ? $product['name'] : '';
	$html  = '<div class="' . esc_attr( $a['class'] ) . '" style="aspect-ratio:' . esc_attr( $a['ratio'] ) . '">';
	$note  = '';
	if ( ! empty( $product['image_id'] ) ) {
		$html .= wp_get_attachment_image( (int) $product['image_id'], 'medium_large', false, array( 'alt' => $label, 'loading' => $a['loading'], 'decoding' => 'async', 'sizes' => $a['sizes'] ) );
	} elseif ( ! empty( $product['image'] ) && 0 === strpos( $product['image'], 'svg:' ) ) {
		$html .= hbg_illustration( substr( $product['image'], 4 ), array( 'alt' => '' ) );
		$note  = __( 'Illustration', 'hoverboards-guide' );
	} elseif ( ! empty( $product['image'] ) && hbg_image_exists( $product['image'] ) ) {
		$html .= hbg_image( $product['image'], array( 'alt' => '', 'sizes' => $a['sizes'], 'loading' => $a['loading'], 'echo' => false ) );
		$note  = ! empty( $product['image_note'] ) ? $product['image_note'] : __( 'Representative photo', 'hoverboards-guide' );
	} else {
		$html .= hbg_illustration( 'hoverboard', array( 'alt' => '' ) );
		$note  = __( 'Illustration', 'hoverboards-guide' );
	}
	if ( $note && $a['note'] && get_theme_mod( 'hbg_show_image_notes', true ) ) {
		$html .= '<span class="product-image__note">' . esc_html( $note ) . '</span>';
	}
	$html .= '</div>';
	if ( $a['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	return $html;
}

// =========================================================================
// Affiliate CTA and product card
// =========================================================================
function hbg_amazon_cta( $product_or_search, $label = '', $size = 'md', $full = false, $placement = '' ) {
	$search  = is_array( $product_or_search ) ? $product_or_search['search'] : $product_or_search;
	$pid     = is_array( $product_or_search ) && isset( $product_or_search['id'] ) ? $product_or_search['id'] : '';
	$label   = $label ? $label : __( 'Check price on Amazon', 'hoverboards-guide' );
	$url     = hbg_amazon_url( $search );
	$class   = 'cta-amazon cta-amazon--' . sanitize_html_class( $size ) . ( $full ? ' cta-amazon--full' : '' );
	?>
	<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $class ); ?>" target="_blank" rel="sponsored nofollow noopener" data-hbg-track="affiliate" data-product="<?php echo esc_attr( $pid ); ?>" data-placement="<?php echo esc_attr( $placement ); ?>">
		<span><?php echo esc_html( $label ); ?></span>
		<?php echo hbg_svg_icon( 'external', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>
	<?php
}

/** Review URL for a product (its review_slug), or ''. */
function hbg_product_review_url( $product ) {
	if ( empty( $product['review_slug'] ) ) {
		return '';
	}
	return apply_filters( 'hbg_product_review_url', hbg_post_url_by_slug( $product['review_slug'] ), $product );
}

/** Key specifications list. Marks figures as advertised unless the owner verified them. */
function hbg_spec_list( $p, $keys = array( 'wheel', 'weight_cap', 'speed', 'range' ), $class = 'specs' ) {
	$labels = array(
		'wheel'         => __( 'Wheels', 'hoverboards-guide' ),
		'weight_cap'    => __( 'Max rider', 'hoverboards-guide' ),
		'speed'         => __( 'Top speed', 'hoverboards-guide' ),
		'range'         => __( 'Range', 'hoverboards-guide' ),
		'certification' => __( 'Certification', 'hoverboards-guide' ),
	);
	$icons = array( 'wheel' => 'ruler', 'weight_cap' => 'weight', 'speed' => 'speed', 'range' => 'battery', 'certification' => 'shield' );
	$items = array();
	foreach ( $keys as $k ) {
		if ( ! empty( $p[ $k ] ) && isset( $labels[ $k ] ) ) {
			$items[] = '<div><dt>' . hbg_svg_icon( $icons[ $k ], 13 ) . esc_html( $labels[ $k ] ) . '</dt><dd>' . esc_html( $p[ $k ] ) . '</dd></div>';
		}
	}
	if ( ! $items ) {
		return;
	}
	echo '<dl class="' . esc_attr( $class ) . '">' . implode( '', $items ) . '</dl>'; // phpcs:ignore
}

function hbg_spec_source_note( $p ) {
	if ( ! empty( $p['verified'] ) ) {
		$when = ! empty( $p['checked'] ) ? sprintf( /* translators: %s date */ __( 'Specifications checked against the manufacturer listing on %s.', 'hoverboards-guide' ), esc_html( $p['checked'] ) ) : __( 'Specifications checked against the manufacturer listing.', 'hoverboards-guide' );
		return $when;
	}
	return __( 'Manufacturer-advertised figures as quoted in our review; real-world range and speed are usually lower.', 'hoverboards-guide' );
}

/**
 * Product recommendation card (homepage section B, 404, shortcode).
 *
 * @param array $p    Product.
 * @param array $args { 'placement' => string, 'heading' => 'h3', 'compact' => bool }
 */
function hbg_product_card( $p, $args = array() ) {
	$a      = wp_parse_args( $args, array( 'placement' => 'card', 'heading' => 'h3', 'compact' => false ) );
	$h      = in_array( $a['heading'], array( 'h2', 'h3', 'h4' ), true ) ? $a['heading'] : 'h3';
	$review = hbg_product_review_url( $p );
	?>
	<article class="pcard<?php echo $a['compact'] ? ' pcard--compact' : ''; ?>" data-product="<?php echo esc_attr( $p['id'] ); ?>">
		<div class="pcard__media">
			<?php hbg_product_image( $p, array( 'class' => 'product-image pcard__img', 'ratio' => '4/3', 'sizes' => '(max-width: 640px) 100vw, 360px' ) ); ?>
			<?php if ( ! empty( $p['badge'] ) ) : ?><span class="pcard__badge"><?php echo esc_html( $p['badge'] ); ?></span><?php endif; ?>
		</div>
		<div class="pcard__body">
			<<?php echo $h; // phpcs:ignore ?> class="pcard__name"><?php if ( $review ) : ?><a href="<?php echo esc_url( $review ); ?>"><?php echo esc_html( $p['name'] ); ?></a><?php else : ?><?php echo esc_html( $p['name'] ); ?><?php endif; ?></<?php echo $h; // phpcs:ignore ?>>
			<?php if ( ! empty( $p['why'] ) ) : ?><p class="pcard__why"><?php echo esc_html( $p['why'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $p['best_for'] ) ) : ?><p class="pcard__for"><strong><?php esc_html_e( 'Best for:', 'hoverboards-guide' ); ?></strong> <?php echo esc_html( $p['best_for'] ); ?></p><?php endif; ?>
			<?php hbg_spec_list( $p, array( 'wheel', 'weight_cap', 'range' ), 'specs pcard__specs' ); ?>
			<div class="pcard__actions">
				<?php hbg_amazon_cta( $p, __( 'Check price on Amazon', 'hoverboards-guide' ), 'md', false, $a['placement'] ); ?>
				<?php if ( $review ) : ?>
					<a href="<?php echo esc_url( $review ); ?>" class="btn btn--ghost btn--md"><?php esc_html_e( 'View full review', 'hoverboards-guide' ); ?></a>
				<?php endif; ?>
			</div>
			<p class="pcard__note"><?php echo esc_html( empty( $p['verified'] ) ? __( 'Specs as advertised by the manufacturer.', 'hoverboards-guide' ) : __( 'Specs checked against the manufacturer listing.', 'hoverboards-guide' ) ); ?></p>
		</div>
	</article>
	<?php
}

// =========================================================================
// Section heading
// =========================================================================
function hbg_section_heading( $args ) {
	$a   = wp_parse_args( $args, array( 'kicker' => '', 'title' => '', 'sub' => '', 'id' => '', 'link' => '', 'link_label' => '', 'tag' => 'h2', 'class' => '' ) );
	$tag = in_array( $a['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $a['tag'] : 'h2';
	?>
	<div class="section-heading<?php echo $a['class'] ? ' ' . esc_attr( $a['class'] ) : ''; ?>">
		<div class="section-heading__text">
			<?php if ( $a['kicker'] ) : ?><div class="section-heading__kicker"><?php echo esc_html( $a['kicker'] ); ?></div><?php endif; ?>
			<<?php echo $tag; // phpcs:ignore ?> class="section-heading__title"<?php echo $a['id'] ? ' id="' . esc_attr( $a['id'] ) . '"' : ''; ?>><?php echo esc_html( $a['title'] ); ?></<?php echo $tag; // phpcs:ignore ?>>
			<?php if ( $a['sub'] ) : ?><p class="section-heading__sub"><?php echo esc_html( $a['sub'] ); ?></p><?php endif; ?>
		</div>
		<?php if ( $a['link'] ) : ?>
			<a href="<?php echo esc_url( $a['link'] ); ?>" class="section-heading__link"><?php echo esc_html( $a['link_label'] ? $a['link_label'] : __( 'View all', 'hoverboards-guide' ) ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a>
		<?php endif; ?>
	</div>
	<?php
}

// =========================================================================
// Post helpers
// =========================================================================
function hbg_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) );
	return max( 1, (int) ceil( $words / 220 ) );
}

function hbg_get_views( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return (int) get_post_meta( $post_id, 'hbg_views', true );
}

/**
 * Primary category: the most specific one (a child category wins over its parent),
 * then the largest; Uncategorized is skipped.
 */
function hbg_primary_category( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$cats    = get_the_category( $post_id );
	if ( empty( $cats ) ) {
		return null;
	}
	$cats = array_values( array_filter( $cats, function ( $c ) { return 'uncategorized' !== $c->slug; } ) );
	if ( ! $cats ) {
		return null;
	}
	usort( $cats, function ( $a, $b ) {
		if ( (bool) $a->parent !== (bool) $b->parent ) {
			return $a->parent ? -1 : 1;
		}
		return $b->count - $a->count;
	} );
	return $cats[0];
}

function hbg_cat_badge( $post_id = null, $link = true, $class = '' ) {
	$cat = hbg_primary_category( $post_id );
	if ( ! $cat ) {
		return;
	}
	$preset = hbg_category_preset( $cat );
	$style  = ' style="--cat:' . esc_attr( $preset['color'] ) . '"';
	if ( $link ) {
		echo '<a href="' . esc_url( get_category_link( $cat ) ) . '" class="cat-badge' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '"' . $style . '>' . esc_html( $cat->name ) . '</a>'; // phpcs:ignore
	} else {
		echo '<span class="cat-badge' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '"' . $style . '>' . esc_html( $cat->name ) . '</span>'; // phpcs:ignore
	}
}

/**
 * Is the post's featured image a generated "title card" (a graphic with the headline baked in)?
 * Many imported posts carry one, named *-featured.png. With the Customizer option on, cards and
 * article heroes use the topic photo instead so headlines are not shown twice.
 */
function hbg_is_title_card( $post_id = null ) {
	if ( ! get_theme_mod( 'hbg_skip_title_cards', true ) ) {
		return false;
	}
	$post_id = $post_id ? $post_id : get_the_ID();
	$thumb   = get_post_thumbnail_id( $post_id );
	if ( ! $thumb ) {
		return false;
	}
	$file = get_post_meta( $thumb, '_wp_attached_file', true );
	return (bool) preg_match( '/-featured\.(png|jpe?g|webp)$/i', (string) $file );
}

/** Post thumbnail with a topic-image fallback so cards are never empty. */
function hbg_post_thumbnail( $size = 'hbg-card', $args = array() ) {
	$a    = wp_parse_args( $args, array( 'class' => '', 'loading' => 'lazy', 'fetchpriority' => '', 'sizes' => '(max-width: 640px) 100vw, 640px', 'echo' => true ) );
	$html = '';
	if ( has_post_thumbnail() && ! hbg_is_title_card() ) {
		$attr = array( 'class' => $a['class'], 'loading' => $a['loading'], 'decoding' => 'async', 'sizes' => $a['sizes'] );
		if ( $a['fetchpriority'] ) {
			$attr['fetchpriority'] = $a['fetchpriority'];
		}
		$html = get_the_post_thumbnail( null, $size, $attr );
	} else {
		$html = hbg_topic_image( hbg_fallback_image_for_post( get_the_ID() ), array( 'alt' => '', 'class' => trim( $a['class'] . ' is-fallback' ), 'loading' => $a['loading'], 'sizes' => $a['sizes'], 'echo' => false ) );
	}
	if ( $a['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	return $html;
}

function hbg_post_meta( $args = array() ) {
	$a = wp_parse_args( $args, array( 'author' => false, 'date' => true, 'updated' => false, 'reading' => true, 'views' => false, 'class' => '' ) );
	$updated = $a['updated'] && get_the_modified_time( 'U' ) > get_the_time( 'U' ) + DAY_IN_SECONDS;
	?>
	<div class="post-meta<?php echo $a['class'] ? ' ' . esc_attr( $a['class'] ) : ''; ?>">
		<?php if ( $a['author'] ) : ?>
			<span class="post-meta__author"><?php echo esc_html( hbg_author_display_name() ); ?></span>
		<?php endif; ?>
		<?php if ( $a['date'] && ! $updated ) : ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		<?php endif; ?>
		<?php if ( $updated ) : ?>
			<span class="post-meta__updated"><?php printf( esc_html__( 'Updated %s', 'hoverboards-guide' ), '<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date() ) . '</time>' ); ?></span>
		<?php endif; ?>
		<?php if ( $a['reading'] ) : ?>
			<span class="post-meta__reading"><?php printf( esc_html__( '%d min read', 'hoverboards-guide' ), (int) hbg_reading_time() ); ?></span>
		<?php endif; ?>
		<?php if ( $a['views'] && get_theme_mod( 'hbg_show_views', false ) && hbg_get_views() > 0 ) : ?>
			<span class="post-meta__views"><?php echo hbg_svg_icon( 'eye', 12 ); // phpcs:ignore ?> <?php echo esc_html( number_format_i18n( hbg_get_views() ) ); ?></span>
		<?php endif; ?>
	</div>
	<?php
}

function hbg_author_display_name( $user_id = null ) {
	$user_id  = $user_id ? $user_id : get_the_author_meta( 'ID' );
	$name     = get_the_author_meta( 'display_name', $user_id );
	$fallback = get_theme_mod( 'hbg_author_name', __( 'Hoverboards Guide Editors', 'hoverboards-guide' ) );
	if ( $fallback && ( ! $name || 'admin' === strtolower( $name ) || false !== strpos( $name, '@' ) ) ) {
		return $fallback;
	}
	return $name ? $name : get_bloginfo( 'name' );
}

function hbg_excerpt( $words = 24, $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$title = get_the_title( $post );
	$text  = '';
	$junk = '/^.{1,40}[·‧•]/u'; // "Label‧Category · Label" chains copied from product cards
	if ( $post->post_excerpt && ! preg_match( $junk, trim( wp_strip_all_tags( $post->post_excerpt ) ) ) ) {
		$text = $post->post_excerpt;
	} else {
		// No hand-written excerpt: prefer the article's own lede (the sentence under the title in an
		// imported hero block), then the first real paragraph of the body, skipping imported header
		// blocks (date stamps, repeated titles, product-card rating snippets).
		$content = strip_shortcodes( $post->post_content );
		if ( preg_match( '/<div class="hb-hero">(.*?)<\/div>/is', $content, $hero ) || preg_match( '/<div style="[^"]*linear-gradient[^"]*"[^>]*>\s*<div[^>]*>\s*UPDATED[^<]*<\/div>\s*<h1[^>]*>.*?<\/h1>(.*?)<\/div>/is', $content, $hero ) ) {
			$lede = preg_replace( '/<h1\b[^>]*>.*?<\/h1>/is', '', $hero[1] );
			$lede = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $lede ) ) );
			if ( mb_strlen( $lede ) >= 40 && false === strpos( $lede, '★' ) ) {
				$text = $lede;
			}
		}
		if ( ! $text && preg_match_all( '/<p\b[^>]*>(.*?)<\/p>/is', $content, $m ) ) {
			foreach ( $m[1] as $para ) {
				$t = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $para ) ) );
				if ( mb_strlen( $t ) < 80 || false !== strpos( $t, '★' ) || preg_match( '/^(UPDATED|Updated)\b/', $t ) || ( $title && 0 === stripos( $t, $title ) ) || preg_match( '/\(\s*[\d,.]+\+?\s*reviews?\s*\)/i', $t ) || preg_match( $junk, $t ) || preg_match( '/^[.#@a-z][^{}]{0,200}\{/i', $t ) ) {
					continue;
				}
				$text = $t;
				break;
			}
		}
		if ( ! $text ) {
			$text = $post->post_content;
		}
	}
	$text = wp_strip_all_tags( strip_shortcodes( $text ) );
	$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text = preg_replace( '/\s+/u', ' ', $text );
	// Imported previews often start with "UPDATED MONTH YEAR" or "Category · Updated Month Year", then the title.
	$text = preg_replace( '/^\s*(?:[A-Za-z][A-Za-z0-9 &\-]{1,40}\s*[·‧•|]\s*)?UPDATED\s+[A-Za-z]+\s+\d{4}\s*/iu', '', $text );
	$text = preg_replace( '/[★☆]{2,}\s*[\d.]*\s*(?:\([\d,.]+\s*reviews?\))?\s*/u', '', $text );
	if ( $title && 0 === stripos( $text, $title ) ) {
		$text = trim( substr( $text, strlen( $title ) ) );
	}
	return wp_trim_words( $text, $words, '…' );
}

/**
 * Standfirst for the article header: the hand-written excerpt, or nothing. Imported posts often carry a
 * scraped product-card string as their excerpt ("Best Overall‧Hoverboards · ★★★★☆ 4.4 (5,120 reviews) …");
 * that is never shown — a missing standfirst reads better than a wrong one.
 */
function hbg_standfirst( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || ! $post->post_excerpt ) {
		return '';
	}
	$raw = trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( $post->post_excerpt ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
	if ( preg_match( '/^.{1,40}[·‧•]/u', $raw ) || false !== strpos( $raw, '★' ) || preg_match( '/\(\s*[\d,.]+\+?\s*reviews?\s*\)/i', $raw ) ) {
		return '';
	}
	return $raw;
}

// -------------------------------------------------------------------------
// Featured / curated post lists
// -------------------------------------------------------------------------
/** Posts from a comma- or newline-separated list of slugs (published only, in order). */
function hbg_posts_from_slugs( $slugs, $limit = 0 ) {
	$out = array();
	foreach ( (array) $slugs as $slug ) {
		$slug = trim( $slug );
		if ( ! $slug ) {
			continue;
		}
		$p = hbg_post_by_slug( $slug );
		if ( $p ) {
			$out[] = $p;
		}
		if ( $limit && count( $out ) >= $limit ) {
			break;
		}
	}
	return $out;
}

/** Default curated "reviews & comparisons" list for the homepage. */
function hbg_default_featured_slugs() {
	return array( 'the-best-hoverboard-reviews', 'the-best-hoverboards-for-adults', 'best-hoverboard-for-kids-review-complete-buying-guide', 'best-off-road-hoverboard', 'best-hoverboards-under-200', 'hover-1-titan-hoverboard', 'swagtron-vs-gotrax-hoverboard', 'best-bluetooth-hoverboards', 'best-hoverboard-brands' );
}

function hbg_get_featured_posts( $count = 6 ) {
	static $cache = array();
	if ( isset( $cache[ $count ] ) ) {
		return $cache[ $count ];
	}
	$raw   = get_theme_mod( 'hbg_featured_slugs', '' );
	$slugs = $raw ? preg_split( '/[\s,]+/', $raw ) : hbg_default_featured_slugs();
	$posts = hbg_posts_from_slugs( $slugs, $count );
	if ( count( $posts ) < $count ) {
		$exclude = wp_list_pluck( $posts, 'ID' );
		$sticky  = get_option( 'sticky_posts' );
		if ( ! empty( $sticky ) ) {
			$q = new WP_Query( array( 'post__in' => $sticky, 'post__not_in' => $exclude, 'posts_per_page' => $count - count( $posts ), 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
			$posts = array_merge( $posts, $q->posts );
			$exclude = wp_list_pluck( $posts, 'ID' );
		}
	}
	if ( count( $posts ) < $count ) {
		$term = get_term_by( 'slug', 'hoverboards', 'category' );
		$args = array( 'posts_per_page' => $count - count( $posts ), 'post__not_in' => wp_list_pluck( $posts, 'ID' ), 'ignore_sticky_posts' => true, 'no_found_rows' => true );
		if ( $term ) {
			$args['cat'] = $term->term_id;
		}
		$q     = new WP_Query( $args );
		$posts = array_merge( $posts, $q->posts );
	}
	$cache[ $count ] = $posts;
	return $posts;
}

function hbg_featured_ids() {
	return wp_list_pluck( hbg_get_featured_posts( (int) get_theme_mod( 'hbg_featured_count', 6 ) ), 'ID' );
}

function hbg_resolve_category( $mod, $default_slugs ) {
	$id = (int) get_theme_mod( $mod, 0 );
	if ( $id ) {
		$term = get_term( $id, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			return $term;
		}
	}
	foreach ( (array) $default_slugs as $slug ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && $term->count > 0 ) {
			return $term;
		}
	}
	return null;
}

/** Most-read posts by the theme's view counter (15-minute cache); falls back to latest. */
function hbg_popular_posts( $count = 5, $exclude = array() ) {
	$exclude = array_values( array_filter( array_map( 'absint', (array) $exclude ) ) );
	$key     = 'hbg_popular_' . md5( $count . '|' . implode( ',', $exclude ) );
	$ids     = get_transient( $key );
	if ( false === $ids || ! is_array( $ids ) ) {
		$q = new WP_Query( array( 'posts_per_page' => $count, 'meta_key' => 'hbg_views', 'orderby' => 'meta_value_num', 'order' => 'DESC', 'ignore_sticky_posts' => true, 'no_found_rows' => true, 'post__not_in' => $exclude, 'fields' => 'ids' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		$ids = array_map( 'intval', $q->posts );
		if ( count( $ids ) < $count ) {
			$q2  = new WP_Query( array( 'posts_per_page' => $count - count( $ids ), 'ignore_sticky_posts' => true, 'no_found_rows' => true, 'post__not_in' => array_merge( $ids, $exclude ), 'fields' => 'ids' ) );
			$ids = array_merge( $ids, array_map( 'intval', $q2->posts ) );
		}
		set_transient( $key, $ids, 15 * MINUTE_IN_SECONDS );
	}
	if ( ! $ids ) {
		return new WP_Query( array( 'post__in' => array( 0 ), 'no_found_rows' => true ) );
	}
	return new WP_Query( array( 'post__in' => $ids, 'orderby' => 'post__in', 'posts_per_page' => count( $ids ), 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
}

/**
 * Related posts: curated hub neighbours first, then the same (most specific) category,
 * then the parent category. Never random, never another ride type.
 */
function hbg_related_posts( $post_id = null, $count = 3 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$post    = get_post( $post_id );
	$found   = array();
	if ( $post ) {
		foreach ( hbg_hubs_for_slug( $post->post_name ) as $hub ) {
			$hubs = hbg_topic_hubs();
			foreach ( $hubs[ $hub ]['posts'] as $slug ) {
				if ( $slug === $post->post_name ) {
					continue;
				}
				$p = hbg_post_by_slug( $slug );
				if ( $p && ! isset( $found[ $p->ID ] ) ) {
					$found[ $p->ID ] = $p;
				}
				if ( count( $found ) >= $count ) {
					break 2;
				}
			}
		}
	}
	if ( count( $found ) < $count ) {
		$cat  = hbg_primary_category( $post_id );
		$cats = array();
		if ( $cat ) {
			$cats[] = $cat->term_id;
			if ( $cat->parent ) {
				$cats[] = $cat->parent;
			}
		}
		foreach ( $cats as $cid ) {
			if ( count( $found ) >= $count ) {
				break;
			}
			$q = new WP_Query( array( 'cat' => $cid, 'posts_per_page' => $count - count( $found ), 'post__not_in' => array_merge( array( $post_id ), array_keys( $found ) ), 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
			foreach ( $q->posts as $p ) {
				$found[ $p->ID ] = $p;
			}
		}
	}
	return array_values( $found );
}

/** "Read next" for the current post: curated by hub, labelled by hub name. */
function hbg_read_next( $post_id = null, $count = 3 ) {
	return hbg_related_posts( $post_id, $count );
}

// -------------------------------------------------------------------------
// Breadcrumbs (Home › Category › Child › Title)
// -------------------------------------------------------------------------
function hbg_breadcrumb_items() {
	$items   = array();
	$items[] = array( 'url' => home_url( '/' ), 'label' => __( 'Home', 'hoverboards-guide' ) );
	if ( is_singular( 'post' ) ) {
		$cat = hbg_primary_category();
		if ( $cat ) {
			if ( $cat->parent ) {
				$parent = get_term( $cat->parent, 'category' );
				if ( $parent && ! is_wp_error( $parent ) ) {
					$items[] = array( 'url' => get_category_link( $parent ), 'label' => $parent->name );
				}
			}
			$items[] = array( 'url' => get_category_link( $cat ), 'label' => $cat->name );
		}
		$items[] = array( 'url' => get_permalink(), 'label' => get_the_title() );
	} elseif ( is_page() ) {
		$items[] = array( 'url' => get_permalink(), 'label' => get_the_title() );
	} elseif ( is_category() ) {
		$cat = get_queried_object();
		if ( $cat && $cat->parent ) {
			$parent = get_term( $cat->parent, 'category' );
			if ( $parent && ! is_wp_error( $parent ) ) {
				$items[] = array( 'url' => get_category_link( $parent ), 'label' => $parent->name );
			}
		}
		$items[] = array( 'url' => get_category_link( $cat ), 'label' => single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$items[] = array( 'url' => get_tag_link( get_queried_object_id() ), 'label' => single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$items[] = array( 'url' => '', 'label' => hbg_author_display_name( get_queried_object_id() ) );
	} elseif ( is_search() ) {
		$items[] = array( 'url' => '', 'label' => __( 'Search', 'hoverboards-guide' ) );
	} elseif ( is_home() ) {
		$items[] = array( 'url' => '', 'label' => __( 'All articles', 'hoverboards-guide' ) );
	} elseif ( is_404() ) {
		$items[] = array( 'url' => '', 'label' => __( 'Not found', 'hoverboards-guide' ) );
	} elseif ( is_archive() ) {
		$items[] = array( 'url' => '', 'label' => wp_strip_all_tags( get_the_archive_title() ) );
	}
	return $items;
}

function hbg_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	$items = hbg_breadcrumb_items();
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'hoverboards-guide' ) . '"><ol>';
	foreach ( $items as $i => $item ) {
		echo '<li>';
		if ( $item['url'] && $i < count( $items ) - 1 ) {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $item['label'] ) . '</span>';
		}
		echo '</li>';
	}
	echo '</ol></nav>';
}

// -------------------------------------------------------------------------
// Share buttons (no third-party scripts)
// -------------------------------------------------------------------------
function hbg_share_buttons() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$links = array(
		'facebook'  => array( 'https://www.facebook.com/sharer/sharer.php?u=' . $url, __( 'Share on Facebook', 'hoverboards-guide' ) ),
		'x'         => array( 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title, __( 'Share on X', 'hoverboards-guide' ) ),
		'pinterest' => array( 'https://pinterest.com/pin/create/button/?url=' . $url . '&description=' . $title, __( 'Pin on Pinterest', 'hoverboards-guide' ) ),
		'mail'      => array( 'mailto:?subject=' . $title . '&body=' . $url, __( 'Share by email', 'hoverboards-guide' ) ),
	);
	?>
	<div class="share" data-url="<?php echo esc_url( get_permalink() ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>">
		<span class="share__label"><?php esc_html_e( 'Share', 'hoverboards-guide' ); ?></span>
		<?php foreach ( $links as $key => $l ) : ?>
			<a class="share__btn share__btn--<?php echo esc_attr( $key ); ?>" href="<?php echo esc_url( $l[0] ); ?>" target="_blank" rel="noopener nofollow" aria-label="<?php echo esc_attr( $l[1] ); ?>" title="<?php echo esc_attr( $l[1] ); ?>"><?php echo hbg_svg_icon( $key, 16 ); // phpcs:ignore ?></a>
		<?php endforeach; ?>
		<button type="button" class="share__btn share__btn--copy" data-copy aria-label="<?php esc_attr_e( 'Copy link', 'hoverboards-guide' ); ?>" title="<?php esc_attr_e( 'Copy link', 'hoverboards-guide' ); ?>"><?php echo hbg_svg_icon( 'link', 16 ); // phpcs:ignore ?></button>
	</div>
	<?php
}

// -------------------------------------------------------------------------
// Table of contents
// -------------------------------------------------------------------------
function hbg_toc_prepare( $content ) {
	$items   = array();
	$used    = array();
	$content = preg_replace_callback(
		'/<h([23])([^>]*)>(.*?)<\/h\1>/is',
		function ( $m ) use ( &$items, &$used ) {
			$level = (int) $m[1];
			$attrs = $m[2];
			$text  = trim( wp_strip_all_tags( $m[3] ) );
			if ( '' === $text || preg_match( '/class=["\'][^"\']*(product-box__name|no-toc|newsletter__title|author-box__name|hbg-faq-q)/', $attrs ) ) {
				return $m[0];
			}
			if ( preg_match( '/\sid=["\']([^"\']+)["\']/', $attrs, $idm ) ) {
				$id = $idm[1];
			} else {
				$base = sanitize_title( $text );
				$base = $base ? $base : 'section';
				$id   = $base;
				$n    = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $n++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;
			$items[]     = array( 'level' => $level, 'id' => $id, 'text' => $text );
			return '<h' . $level . $attrs . '>' . $m[3] . '</h' . $level . '>';
		},
		$content
	);
	return array( $content, $items );
}

function hbg_render_toc( $items ) {
	$h2s = array_filter( $items, function ( $i ) { return 2 === $i['level']; } );
	if ( count( $h2s ) < 3 ) {
		return;
	}
	?>
	<nav class="toc" id="toc" aria-label="<?php esc_attr_e( 'Table of contents', 'hoverboards-guide' ); ?>" data-toc>
		<button type="button" class="toc__toggle" aria-expanded="true" aria-controls="toc-list">
			<span><?php echo hbg_svg_icon( 'list', 16 ); // phpcs:ignore ?> <?php esc_html_e( 'In this article', 'hoverboards-guide' ); ?></span>
			<?php echo hbg_svg_icon( 'chevron-down', 16, 'toc__chevron' ); // phpcs:ignore ?>
		</button>
		<ol class="toc__list" id="toc-list">
			<?php
			$open_sub = false;
			$printed  = false;
			foreach ( $items as $item ) {
				if ( 2 === $item['level'] ) {
					if ( $open_sub ) {
						echo '</ol></li>';
						$open_sub = false;
					} elseif ( $printed ) {
						echo '</li>';
					}
					echo '<li><a href="#' . esc_attr( $item['id'] ) . '">' . esc_html( $item['text'] ) . '</a>';
					$printed = true;
				} elseif ( $printed ) {
					if ( ! $open_sub ) {
						echo '<ol class="toc__sub">';
						$open_sub = true;
					}
					echo '<li><a href="#' . esc_attr( $item['id'] ) . '">' . esc_html( $item['text'] ) . '</a></li>';
				}
			}
			if ( $open_sub ) {
				echo '</ol>';
			}
			if ( $printed ) {
				echo '</li>';
			}
			?>
		</ol>
	</nav>
	<?php
}

/** True when the post already carries its own table of contents. */
function hbg_content_has_toc( $content ) {
	if ( preg_match( '/(ez-toc-container|lwptoc|toc_container|wp-block-rank-math-toc-block|wp-block-yoast-seo-table-of-contents|aioseo-table-of-contents|hb-toc|class=["\'][^"\']*\btoc\b|id=["\']toc["\'])/i', $content ) ) {
		return true;
	}
	if ( preg_match( '/(table of contents|in this (article|guide|post|review))[\s\S]{0,400}<(ul|ol)[^>]*>[\s\S]{0,300}href=["\']#/i', $content ) ) {
		return true;
	}
	return (bool) apply_filters( 'hbg_content_has_toc', false, $content );
}

// -------------------------------------------------------------------------
// Author box: shown with an honest default description
// -------------------------------------------------------------------------
function hbg_author_box() {
	$id  = get_the_author_meta( 'ID' );
	$bio = get_the_author_meta( 'description', $id );
	$bio = $bio ? $bio : get_theme_mod( 'hbg_author_bio', __( 'Hoverboards Guide is written by a small editorial team. Our guides are built from manufacturer specifications, published safety guidance and owner feedback; we say clearly when a product has not been ridden by us.', 'hoverboards-guide' ) );
	$policy = hbg_page_url( 'editorial-policy' );
	?>
	<aside class="author-box" aria-label="<?php esc_attr_e( 'About the author', 'hoverboards-guide' ); ?>">
		<div class="author-box__avatar" aria-hidden="true"><?php echo hbg_svg_icon( 'user', 26 ); // phpcs:ignore ?></div>
		<div class="author-box__body">
			<div class="author-box__kicker"><?php esc_html_e( 'Written by', 'hoverboards-guide' ); ?></div>
			<h2 class="author-box__name no-toc"><?php echo esc_html( hbg_author_display_name( $id ) ); ?></h2>
			<p class="author-box__bio"><?php echo esc_html( $bio ); ?></p>
			<?php if ( $policy ) : ?>
				<a class="author-box__link" href="<?php echo esc_url( $policy ); ?>"><?php esc_html_e( 'How we research and review', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}

/** URL of a published page by slug, or ''. */
function hbg_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}

// -------------------------------------------------------------------------
// Social links (Customizer)
// -------------------------------------------------------------------------
function hbg_social_links() {
	$networks = array( 'facebook' => 'Facebook', 'x' => 'X', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'pinterest' => 'Pinterest', 'tiktok' => 'TikTok' );
	$out      = array();
	foreach ( $networks as $key => $label ) {
		$url = get_theme_mod( 'hbg_social_' . $key, '' );
		if ( $url ) {
			$out[ $key ] = array( 'url' => $url, 'label' => $label );
		}
	}
	return $out;
}

function hbg_social_icons( $class = 'social' ) {
	$links = hbg_social_links();
	if ( ! $links ) {
		return;
	}
	echo '<div class="' . esc_attr( $class ) . '">';
	foreach ( $links as $key => $l ) {
		echo '<a href="' . esc_url( $l['url'] ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $l['label'] ) . '" class="' . esc_attr( $class ) . '__link">' . hbg_svg_icon( $key, 16 ) . '</a>'; // phpcs:ignore
	}
	echo '</div>';
}

// -------------------------------------------------------------------------
// Newsletter: only rendered when a real external form action is configured.
// -------------------------------------------------------------------------
function hbg_newsletter_enabled() {
	return (bool) get_theme_mod( 'hbg_newsletter_action', '' );
}

function hbg_newsletter_form( $args = array() ) {
	if ( ! hbg_newsletter_enabled() ) {
		return;
	}
	$a      = wp_parse_args( $args, array( 'variant' => 'box', 'title' => '', 'sub' => '' ) );
	$action = get_theme_mod( 'hbg_newsletter_action', '' );
	$field  = get_theme_mod( 'hbg_newsletter_email_field', 'EMAIL' );
	$title  = $a['title'] ? $a['title'] : get_theme_mod( 'hbg_newsletter_title', __( 'New guides by email', 'hoverboards-guide' ) );
	$sub    = $a['sub'] ? $a['sub'] : get_theme_mod( 'hbg_newsletter_sub', __( 'Occasional updates when we publish new buying guides and safety notices.', 'hoverboards-guide' ) );
	$uid    = 'nl-' . wp_unique_id();
	?>
	<div class="newsletter newsletter--<?php echo esc_attr( $a['variant'] ); ?>">
		<h3 class="newsletter__title no-toc"><?php echo esc_html( $title ); ?></h3>
		<p class="newsletter__sub"><?php echo esc_html( $sub ); ?></p>
		<form class="newsletter__form" method="post" action="<?php echo esc_url( $action ); ?>" target="_blank">
			<label for="<?php echo esc_attr( $uid ); ?>" class="screen-reader-text"><?php esc_html_e( 'Email address', 'hoverboards-guide' ); ?></label>
			<input type="email" id="<?php echo esc_attr( $uid ); ?>" name="<?php echo esc_attr( $field ); ?>" placeholder="<?php esc_attr_e( 'you@example.com', 'hoverboards-guide' ); ?>" required autocomplete="email">
			<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Subscribe', 'hoverboards-guide' ); ?></button>
		</form>
		<p class="newsletter__fine"><?php esc_html_e( 'Unsubscribe at any time.', 'hoverboards-guide' ); ?></p>
	</div>
	<?php
}

// -------------------------------------------------------------------------
// Affiliate disclosure
// -------------------------------------------------------------------------
function hbg_disclosure_text() {
	return get_theme_mod( 'hbg_affiliate_disclosure', __( 'As an Amazon Associate, Hoverboards Guide earns from qualifying purchases. Links marked “Check price on Amazon” are affiliate links; prices and availability shown on Amazon are current there, not here.', 'hoverboards-guide' ) );
}

function hbg_affiliate_disclosure_box( $compact = false ) {
	$page = hbg_page_url( 'affiliate-disclosure' );
	?>
	<div class="disclosure<?php echo $compact ? ' disclosure--compact' : ''; ?>" role="note">
		<?php echo hbg_svg_icon( 'info', 16 ); // phpcs:ignore ?>
		<p><?php echo esc_html( hbg_disclosure_text() ); ?><?php if ( $page ) : ?> <a href="<?php echo esc_url( $page ); ?>"><?php esc_html_e( 'Read our affiliate disclosure', 'hoverboards-guide' ); ?></a><?php endif; ?></p>
	</div>
	<?php
}

/** Does the post contain affiliate links? Used to show the disclosure only where it applies. */
function hbg_content_has_affiliate_links( $content ) {
	return (bool) preg_match( '/(amazon\.|amzn\.to|rel=["\'][^"\']*sponsored)/i', $content );
}

// -------------------------------------------------------------------------
// Pagination
// -------------------------------------------------------------------------
function hbg_posts_pagination() {
	the_posts_pagination( array(
		'mid_size'  => 1,
		'prev_text' => hbg_svg_icon( 'chevron-left', 16 ) . '<span class="screen-reader-text">' . esc_html__( 'Previous', 'hoverboards-guide' ) . '</span>',
		'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'hoverboards-guide' ) . '</span>' . hbg_svg_icon( 'chevron-right', 16 ),
		'class'     => 'hbg-pagination',
	) );
}

// -------------------------------------------------------------------------
// Topic hub tile (homepage section C) and category tile
// -------------------------------------------------------------------------
function hbg_topic_tile( $slug, $hub ) {
	$link  = hbg_hub_link( $slug );
	if ( ! $link['url'] ) {
		return;
	}
	$image = get_theme_mod( 'hbg_img_' . str_replace( '-', '_', $slug ), '' );
	$image = $image ? $image : $hub['image'];
	?>
	<a href="<?php echo esc_url( $link['url'] ); ?>" class="topic-tile">
		<span class="topic-tile__media"><?php hbg_topic_image( $image, array( 'alt' => '', 'class' => 'topic-tile__img', 'sizes' => '(max-width: 640px) 50vw, 300px' ) ); ?></span>
		<span class="topic-tile__body">
			<span class="topic-tile__name"><?php echo esc_html( $hub['name'] ); ?></span>
			<span class="topic-tile__blurb"><?php echo esc_html( $hub['blurb'] ); ?></span>
			<?php if ( $link['count'] ) : ?>
				<span class="topic-tile__count"><?php printf( esc_html( _n( '%s article', '%s articles', $link['count'], 'hoverboards-guide' ) ), esc_html( number_format_i18n( $link['count'] ) ) ); ?></span>
			<?php endif; ?>
		</span>
	</a>
	<?php
}

function hbg_category_tile( $term ) {
	$preset = hbg_category_preset( $term );
	?>
	<a href="<?php echo esc_url( get_category_link( $term ) ); ?>" class="topic-tile topic-tile--cat" style="--cat:<?php echo esc_attr( $preset['color'] ); ?>">
		<span class="topic-tile__media"><?php hbg_topic_image( $preset['image'], array( 'alt' => '', 'class' => 'topic-tile__img', 'sizes' => '(max-width: 640px) 50vw, 300px' ) ); ?></span>
		<span class="topic-tile__body">
			<span class="topic-tile__name"><?php echo esc_html( $term->name ); ?></span>
			<?php if ( $preset['blurb'] ) : ?><span class="topic-tile__blurb"><?php echo esc_html( $preset['blurb'] ); ?></span><?php endif; ?>
			<span class="topic-tile__count"><?php printf( esc_html( _n( '%s article', '%s articles', $term->count, 'hoverboards-guide' ) ), esc_html( number_format_i18n( $term->count ) ) ); ?></span>
		</span>
	</a>
	<?php
}

/** Compact numbered post list used in sidebars and widgets. */
function hbg_render_post_list( $query, $numbered = true ) {
	if ( ! $query->have_posts() ) {
		return;
	}
	echo '<ol class="post-list' . ( $numbered ? ' post-list--numbered' : '' ) . '">';
	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		<li class="post-list__item">
			<a href="<?php the_permalink(); ?>" class="post-list__thumb" tabindex="-1" aria-hidden="true"><?php hbg_post_thumbnail( 'hbg-thumb', array( 'sizes' => '96px' ) ); ?></a>
			<div class="post-list__body">
				<?php hbg_cat_badge( null, false, 'cat-badge--sm' ); ?>
				<a href="<?php the_permalink(); ?>" class="post-list__title"><?php the_title(); ?></a>
			</div>
		</li>
		<?php
	}
	echo '</ol>';
	wp_reset_postdata();
}

/** "All articles" URL: Customizer value, else the Posts page, else the main Hoverboards category. */
function hbg_all_articles_url() {
	$custom = get_theme_mod( 'hbg_all_articles_url', '' );
	if ( $custom ) {
		return $custom;
	}
	$page = (int) get_option( 'page_for_posts' );
	if ( $page && 'page' === get_option( 'show_on_front' ) ) {
		return get_permalink( $page );
	}
	$term = get_term_by( 'slug', 'hoverboards', 'category' );
	return $term ? get_category_link( $term ) : '';
}

/** Meta description for the current view (used when no SEO plugin is active). */
function hbg_meta_description() {
	if ( is_front_page() ) {
		return get_theme_mod( 'hbg_meta_description', __( 'Hoverboard buying guides, reviews and safety advice: compare boards for kids, teens and adults by wheel size, terrain, weight limit and UL 2272 certification.', 'hoverboards-guide' ) );
	}
	if ( is_singular() ) {
		return hbg_excerpt( 28, get_queried_object() );
	}
	if ( is_category() || is_tag() ) {
		$term = get_queried_object();
		$desc = wp_strip_all_tags( term_description( $term ) );
		if ( ! $desc && is_category() ) {
			$hubs = hbg_topic_hubs();
			$desc = isset( $hubs[ $term->slug ] ) ? $hubs[ $term->slug ]['desc'] : hbg_category_preset( $term )['blurb'];
		}
		return $desc;
	}
	return '';
}

/**
 * Photo for a post that has no usable featured image (none, or a generated title card).
 * Picks from the bundled photos that match the post's subject (slug keywords, then category) and
 * cycles through that pool so neighbouring cards do not all show the same picture.
 */
function hbg_fallback_image_for_post( $post_id ) {
	$post  = get_post( $post_id );
	$slug  = $post ? $post->post_name : '';
	$cat   = hbg_primary_category( $post_id );
	$pools = array(
		'kids'           => array( 'cat-hoverboards-kids', 'product-hoverboard-kids', 'cat-kids' ),
		'adult'          => array( 'cat-hoverboards', 'hero-hoverboard-shore', 'cat-self-balancing' ),
		'off-road'       => array( 'hero-hoverboard-shore', 'cat-hoverboards' ),
		'terrain'        => array( 'hero-hoverboard-shore', 'cat-hoverboards' ),
		'safety'         => array( 'cat-safety', 'svg:safety' ),
		'helmet'         => array( 'cat-safety' ),
		'charg'          => array( 'svg:charging', 'cat-howto' ),
		'batter'         => array( 'svg:charging', 'cat-howto' ),
		'trouble'        => array( 'cat-howto', 'svg:maintenance' ),
		'fix'            => array( 'cat-howto', 'svg:maintenance' ),
		'calibrat'       => array( 'cat-howto', 'svg:maintenance' ),
		'beep'           => array( 'cat-howto', 'svg:maintenance' ),
		'bluetooth'      => array( 'svg:bluetooth', 'cat-hoverboards' ),
		'cheap'          => array( 'svg:budget', 'cat-hoverboards' ),
		'under-'         => array( 'svg:budget', 'cat-hoverboards' ),
		'budget'         => array( 'svg:budget', 'cat-hoverboards' ),
		'kart'           => array( 'cat-hoverkart' ),
		'seat'           => array( 'cat-hoverkart' ),
		'scooter'        => array( 'cat-escooters', 'cat-escooters-commute', 'hero-escooter-city', 'product-escooter-city', 'product-escooter-road', 'product-hiboy-s2', 'product-varla-eagle' ),
		'skateboard'     => array( 'cat-skateboards' ),
		'longboard'      => array( 'cat-skateboards' ),
		'unicycle'       => array( 'cat-unicycles' ),
		'bike'           => array( 'cat-ebikes' ),
		'ninebot'        => array( 'product-ninebot-s', 'cat-self-balancing' ),
		'segway'         => array( 'product-ninebot-s', 'cat-self-balancing' ),
		'self-balancing' => array( 'cat-self-balancing', 'product-ninebot-s' ),
		'buying'         => array( 'cat-guides', 'cat-hoverboards', 'hero-hoverboard-shore' ),
		'guide'          => array( 'cat-guides', 'cat-hoverboards' ),
		'hoverboard'     => array( 'cat-hoverboards', 'hero-hoverboard-shore', 'cat-self-balancing', 'cat-hoverboards-kids' ),
	);
	$pool = array();
	foreach ( $pools as $needle => $names ) {
		if ( $slug && false !== strpos( $slug, $needle ) ) {
			$pool = $names;
			break;
		}
	}
	if ( ! $pool && $cat ) {
		$preset = hbg_category_preset( $cat );
		$pool   = array( $preset['image'] );
		foreach ( $pools as $needle => $names ) {
			if ( false !== strpos( $cat->slug, $needle ) ) {
				$pool = array_values( array_unique( array_merge( $pool, $names ) ) );
				break;
			}
		}
	}
	if ( ! $pool ) {
		$pool = $pools['hoverboard'];
	}
	$pool = apply_filters( 'hbg_fallback_image_pool', $pool, $post_id );
	static $cursor = array();
	$key = md5( implode( '|', $pool ) );
	$cursor[ $key ] = isset( $cursor[ $key ] ) ? ( $cursor[ $key ] + 1 ) % count( $pool ) : (int) $post_id % count( $pool );
	return $pool[ $cursor[ $key ] ];
}
