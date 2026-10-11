<?php
/**
 * Template tag helper functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =========================================================================
// SVG icon library
// =========================================================================
function hbg_svg_icon( $name, $size = 18, $class = '' ) {
	$paths = array(
		'search'        => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'menu'          => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'         => '<path d="M6 6l12 12M18 6 6 18"/>',
		'sun'           => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
		'moon'          => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
		'arrow-right'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-up'      => '<path d="M12 19V5M5 12l7-7 7 7"/>',
		'check'         => '<path d="M5 12l5 5L20 7"/>',
		'star'          => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
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
		'warranty'      => '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>',
		'bolt'          => '<path d="M13 2 4 14h7l-1 8 9-12h-7l1-8z"/>',
		'tag'           => '<path d="M3 12V4h8l9 9-8 8-9-9z"/><circle cx="7.5" cy="8.5" r="1.5" fill="currentColor"/>',
		'fire'          => '<path d="M12 22c4 0 7-3 7-7 0-3-2-5-3-7-1 2-2 3-3 3 0-3-1-6-4-8 0 4-4 6-4 12 0 4 3 7 7 7z"/>',
		'compare'       => '<path d="M9 3v18M15 3v18M3 8h6M15 8h6M3 16h6M15 16h6"/>',
		'cart'          => '<circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/><path d="M2 3h3l2.5 11h11L21 7H6"/>',
		'external'      => '<path d="M14 4h6v6M20 4l-9 9M19 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5"/>',
		'info'          => '<circle cx="12" cy="12" r="10"/><path d="M12 8v.01M12 11v6"/>',
		'alert'         => '<path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5M12 18v.01"/>',
		'list'          => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
		'trending'      => '<path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/>',
		'quote'         => '<path d="M7 7h4v6H7a3 3 0 0 0-3 3v1M15 7h4v6h-4a3 3 0 0 0-3 3v1" />',
		'thumbs-up'     => '<path d="M7 11v9H3v-9h4zM7 11l4-8a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2.4l-1.2 6A2 2 0 0 1 16.8 20H7"/>',
		'thumbs-down'   => '<path d="M17 13V4h4v9h-4zM17 13l-4 8a2 2 0 0 1-2-2v-4H6a2 2 0 0 1-2-2.4l1.2-6A2 2 0 0 1 7.2 4H17"/>',
		'home'          => '<path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1v-9z"/>',
		'helmet'        => '<path d="M4 14a8 8 0 0 1 16 0v3H4z"/><path d="M4 17h16M9 14h11"/>',
		'ruler'         => '<path d="M3 17 17 3l4 4L7 21z"/><path d="m8 12 2 2M11 9l2 2M14 6l2 2"/>',
		'wrench'        => '<path d="M14.7 6.3a4 4 0 0 0 5 5L9.4 21.6a2 2 0 0 1-2.8-2.8L16.9 8.5"/><path d="M14.7 6.3 18 3l3 3-3.3 3.3"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon icon-' . esc_attr( $name ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

// =========================================================================
// Bundled images
// =========================================================================
function hbg_image_entry( $name ) {
	$manifest = hbg_image_manifest();
	return isset( $manifest[ $name ] ) ? $manifest[ $name ] : null;
}

function hbg_image_exists( $name ) {
	return (bool) hbg_image_entry( $name );
}

/** Largest bundled width available for an image (960 for hero/category shots, 640 for product shots). */
function hbg_image_largest_width( $name ) {
	$e = hbg_image_entry( $name );
	return $e ? max( array_keys( $e['sizes'] ) ) : 0;
}

/**
 * URL of a bundled image at a given width. Falls back to the largest available width
 * when the requested one is not bundled.
 */
function hbg_image_url( $name, $width = 0, $ext = 'webp' ) {
	$e = hbg_image_entry( $name );
	if ( $e && ( ! $width || ! isset( $e['sizes'][ (int) $width ] ) ) ) {
		$width = max( array_keys( $e['sizes'] ) );
	}
	return HBG_URI . '/assets/images/' . $name . '-' . (int) $width . '.' . $ext;
}

/**
 * Responsive <img> (or <picture> when a JPEG fallback exists) for a bundled image.
 */
function hbg_image( $name, $args = array() ) {
	$m = hbg_image_entry( $name );
	if ( ! $m ) {
		return '';
	}
	$a = wp_parse_args( $args, array(
		'alt'           => '',
		'class'         => '',
		'sizes'         => '(max-width: 640px) 100vw, 640px',
		'loading'       => 'lazy',
		'fetchpriority' => '',
		'echo'          => true,
	) );

	$widths = array_keys( $m['sizes'] );
	sort( $widths );
	$max    = end( $widths );
	$dims   = $m['sizes'][ $max ];
	$parts  = array();
	foreach ( $widths as $wd ) {
		$parts[] = hbg_image_url( $name, $wd ) . ' ' . (int) $wd . 'w';
	}
	$srcset = implode( ', ', $parts );
	$attrs  = ' width="' . (int) $dims[0] . '" height="' . (int) $dims[1] . '" alt="' . esc_attr( $a['alt'] ) . '"'
		. ( $a['class'] ? ' class="' . esc_attr( $a['class'] ) . '"' : '' )
		. ' loading="' . esc_attr( $a['loading'] ) . '" decoding="async"'
		. ( $a['fetchpriority'] ? ' fetchpriority="' . esc_attr( $a['fetchpriority'] ) . '"' : '' );

	if ( ! empty( $m['jpg'] ) ) {
		$html = '<picture>'
			. '<source type="image/webp" srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $a['sizes'] ) . '">'
			. '<img src="' . esc_url( hbg_image_url( $name, $max, 'jpg' ) ) . '"' . $attrs . '>'
			. '</picture>';
	} else {
		$html = '<img src="' . esc_url( hbg_image_url( $name, $max ) ) . '" srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $a['sizes'] ) . '"' . $attrs . '>';
	}

	if ( $a['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
	}
	return $html;
}

// -------------------------------------------------------------------------
// Product image
// -------------------------------------------------------------------------
function hbg_product_image( $product, $args = array() ) {
	$a = wp_parse_args( $args, array(
		'class'  => 'product-image',
		'ratio'  => '4/3',
		'sizes'  => '(max-width: 640px) 100vw, 400px',
		'note'   => true,
		'echo'   => true,
	) );
	$name  = isset( $product['image'] ) ? $product['image'] : '';
	$label = isset( $product['name'] ) ? $product['name'] : '';
	$note  = ( $a['note'] && ! empty( $product['image_note'] ) && get_theme_mod( 'hbg_show_image_notes', true ) ) ? $product['image_note'] : '';

	$html = '<div class="' . esc_attr( $a['class'] ) . '" style="aspect-ratio:' . esc_attr( $a['ratio'] ) . '">';
	if ( $name && hbg_image_exists( $name ) ) {
		$html .= hbg_image( $name, array( 'alt' => $label, 'sizes' => $a['sizes'], 'echo' => false ) );
	} else {
		$color = isset( $product['color'] ) ? $product['color'] : '#FF7A1A';
		$kind  = isset( $product['kind'] ) ? $product['kind'] : 'hoverboard';
		$html .= '<div class="product-image__placeholder" role="img" aria-label="' . esc_attr( $label ) . '">'
			. ( 'scooter' === $kind ? hbg_scooter_svg( $color ) : hbg_hoverboard_svg( $color ) )
			. '</div>';
	}
	if ( $note ) {
		$html .= '<span class="product-image__note">' . esc_html( $note ) . '</span>';
	}
	$html .= '</div>';

	if ( $a['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	return $html;
}

function hbg_hoverboard_svg( $color = '#FF7A1A' ) {
	$color = esc_attr( $color );
	return '<svg viewBox="0 0 200 110" width="65%" aria-hidden="true" focusable="false">'
		. '<ellipse cx="100" cy="98" rx="80" ry="6" fill="#0f172a" opacity="0.08"/>'
		. '<rect x="50" y="55" width="100" height="20" rx="6" fill="' . $color . '"/>'
		. '<rect x="92" y="58" width="16" height="14" rx="2" fill="#0f172a" opacity="0.85"/>'
		. '<circle cx="42" cy="70" r="22" fill="#1f2937"/><circle cx="42" cy="70" r="10" fill="#94a3b8"/>'
		. '<circle cx="158" cy="70" r="22" fill="#1f2937"/><circle cx="158" cy="70" r="10" fill="#94a3b8"/>'
		. '</svg>';
}

function hbg_scooter_svg( $color = '#0EA5A4' ) {
	$color = esc_attr( $color );
	return '<svg viewBox="0 0 200 130" width="70%" aria-hidden="true" focusable="false">'
		. '<ellipse cx="100" cy="118" rx="80" ry="5" fill="#0f172a" opacity="0.08"/>'
		. '<circle cx="40" cy="100" r="18" fill="#1f2937"/><circle cx="40" cy="100" r="7" fill="#94a3b8"/>'
		. '<circle cx="160" cy="100" r="18" fill="#1f2937"/><circle cx="160" cy="100" r="7" fill="#94a3b8"/>'
		. '<rect x="42" y="92" width="120" height="10" rx="3" fill="' . $color . '"/>'
		. '<rect x="148" y="20" width="6" height="80" rx="2" fill="#334155"/>'
		. '<rect x="125" y="14" width="50" height="6" rx="3" fill="#0f172a"/>'
		. '</svg>';
}

// =========================================================================
// Ratings
// =========================================================================
function hbg_stars( $value, $size = 14 ) {
	$value = (float) $value;
	$full  = (int) floor( $value );
	$half  = ( $value - $full ) >= 0.5;
	$out   = '<span class="stars" aria-label="' . esc_attr( number_format_i18n( $value, 1 ) ) . ' out of 5 stars">';
	for ( $i = 0; $i < 5; $i++ ) {
		$state = $i < $full ? 'full' : ( ( $i === $full && $half ) ? 'half' : 'empty' );
		$out  .= '<svg class="star star--' . $state . '" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 20 20" aria-hidden="true">';
		if ( 'half' === $state ) {
			$gid  = 'half-' . $i . '-' . $size . '-' . wp_rand( 100, 999 );
			$out .= '<defs><linearGradient id="' . esc_attr( $gid ) . '"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="var(--star-empty)"/></linearGradient></defs>';
			$out .= '<path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.2 1 5.9L10 15l-5.3 2.8 1-5.9L1.5 7.7l5.9-.9z" fill="url(#' . esc_attr( $gid ) . ')"/>';
		} else {
			$out .= '<path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.2 1 5.9L10 15l-5.3 2.8 1-5.9L1.5 7.7l5.9-.9z" fill="' . ( 'full' === $state ? 'currentColor' : 'var(--star-empty)' ) . '"/>';
		}
		$out .= '</svg>';
	}
	$out .= '</span>';
	echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/** Editor score out of 10 rendered as a badge. */
function hbg_score_badge( $score, $class = '' ) {
	$score = (float) $score;
	$level = $score >= 9 ? 'great' : ( $score >= 8 ? 'good' : 'ok' );
	echo '<span class="score-badge score-badge--' . esc_attr( $level ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" title="' . esc_attr__( 'Editor score', 'hoverboards-guide' ) . '"><strong>' . esc_html( number_format_i18n( $score, 1 ) ) . '</strong><span>/10</span></span>';
}

// =========================================================================
// Amazon CTA
// =========================================================================
function hbg_amazon_cta( $product_or_search, $label = '', $size = 'md', $full = false ) {
	$search = is_array( $product_or_search ) ? $product_or_search['search'] : $product_or_search;
	$label  = $label ? $label : __( 'Check price on Amazon', 'hoverboards-guide' );
	$url    = hbg_amazon_url( $search );
	$class  = 'cta-amazon cta-amazon--' . sanitize_html_class( $size ) . ( $full ? ' cta-amazon--full' : '' );
	?>
	<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $class ); ?>" target="_blank" rel="sponsored nofollow noopener">
		<span><?php echo esc_html( $label ); ?></span>
		<?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>
	<?php
}

// =========================================================================
// Section heading
// =========================================================================
function hbg_section_heading( $args ) {
	$a = wp_parse_args( $args, array( 'kicker' => '', 'title' => '', 'sub' => '', 'id' => '', 'link' => '', 'link_label' => '', 'tag' => 'h2', 'class' => '' ) );
	$tag = in_array( $a['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $a['tag'] : 'h2';
	?>
	<div class="section-heading<?php echo $a['class'] ? ' ' . esc_attr( $a['class'] ) : ''; ?>"<?php echo $a['id'] ? ' id="' . esc_attr( $a['id'] ) . '"' : ''; ?>>
		<div class="section-heading__text">
			<?php if ( $a['kicker'] ) : ?>
				<div class="section-heading__kicker"><?php echo esc_html( $a['kicker'] ); ?></div>
			<?php endif; ?>
			<<?php echo $tag; // phpcs:ignore ?> class="section-heading__title"><?php echo esc_html( $a['title'] ); ?></<?php echo $tag; // phpcs:ignore ?>>
			<?php if ( $a['sub'] ) : ?>
				<p class="section-heading__sub"><?php echo esc_html( $a['sub'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $a['link'] ) : ?>
			<a href="<?php echo esc_url( $a['link'] ); ?>" class="section-heading__link"><?php echo esc_html( $a['link_label'] ? $a['link_label'] : __( 'View all', 'hoverboards-guide' ) ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a>
		<?php endif; ?>
	</div>
	<?php
}

function hbg_guide_icon( $icon ) {
	$map = array( 'battery' => 'battery', 'shield' => 'shield', 'speed' => 'speed', 'weight' => 'weight', 'terrain' => 'terrain', 'warranty' => 'warranty' );
	return hbg_svg_icon( isset( $map[ $icon ] ) ? $map[ $icon ] : 'shield', 22 );
}

function hbg_hover_icon( $color ) {
	$color = esc_attr( $color );
	return '<svg viewBox="0 0 40 24" width="40" height="24" aria-hidden="true"><rect x="10" y="8" width="20" height="6" rx="2" fill="' . $color . '"/><circle cx="8" cy="14" r="5" fill="#1f2937"/><circle cx="32" cy="14" r="5" fill="#1f2937"/></svg>';
}

function hbg_scoot_icon( $color ) {
	$color = esc_attr( $color );
	return '<svg viewBox="0 0 40 28" width="40" height="28" aria-hidden="true"><circle cx="8" cy="22" r="4" fill="#1f2937"/><circle cx="32" cy="22" r="4" fill="#1f2937"/><rect x="9" y="19" width="24" height="3" fill="' . $color . '"/><rect x="29" y="6" width="2" height="16" fill="#334155"/><rect x="25" y="4" width="10" height="3" rx="1" fill="#0f172a"/></svg>';
}

// =========================================================================
// Post helpers
// =========================================================================
function hbg_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) );
	return max( 1, (int) ceil( $words / 210 ) );
}

function hbg_get_views( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return (int) get_post_meta( $post_id, 'hbg_views', true );
}

function hbg_format_number( $n ) {
	$n = (int) $n;
	if ( $n >= 1000000 ) {
		return number_format_i18n( $n / 1000000, 1 ) . 'M';
	}
	if ( $n >= 1000 ) {
		return number_format_i18n( $n / 1000, 1 ) . 'k';
	}
	return number_format_i18n( $n );
}

function hbg_primary_category( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$cats    = get_the_category( $post_id );
	if ( empty( $cats ) ) {
		return null;
	}
	// Prefer the category with the most posts as the "primary" one (skip Uncategorized).
	usort( $cats, function ( $a, $b ) {
		return $b->count - $a->count;
	} );
	foreach ( $cats as $c ) {
		if ( 'uncategorized' !== $c->slug ) {
			return $c;
		}
	}
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
 * Post thumbnail with a bundled category-image fallback so no card is ever empty.
 */
function hbg_post_thumbnail( $size = 'hbg-card', $args = array() ) {
	$a = wp_parse_args( $args, array( 'class' => '', 'loading' => 'lazy', 'fetchpriority' => '', 'sizes' => '(max-width: 640px) 100vw, 640px', 'echo' => true ) );
	$html = '';
	if ( has_post_thumbnail() ) {
		$attr = array( 'class' => $a['class'], 'loading' => $a['loading'], 'decoding' => 'async', 'sizes' => $a['sizes'] );
		if ( $a['fetchpriority'] ) {
			$attr['fetchpriority'] = $a['fetchpriority'];
		}
		$html = get_the_post_thumbnail( null, $size, $attr );
	} else {
		$cat    = hbg_primary_category();
		$preset = hbg_category_preset( $cat );
		$html   = hbg_image( $preset['image'], array( 'alt' => get_the_title(), 'class' => $a['class'] . ' is-fallback', 'loading' => $a['loading'], 'fetchpriority' => $a['fetchpriority'], 'sizes' => $a['sizes'], 'echo' => false ) );
	}
	if ( $a['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	return $html;
}

function hbg_post_meta( $args = array() ) {
	$a = wp_parse_args( $args, array( 'author' => false, 'date' => true, 'updated' => false, 'reading' => true, 'views' => false, 'class' => '' ) );
	?>
	<div class="post-meta<?php echo $a['class'] ? ' ' . esc_attr( $a['class'] ) : ''; ?>">
		<?php if ( $a['author'] ) : ?>
			<span class="post-meta__author">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 28, '', '', array( 'class' => 'post-meta__avatar' ) ); ?>
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( hbg_author_display_name() ); ?></a>
			</span>
		<?php endif; ?>
		<?php if ( $a['date'] ) : ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		<?php endif; ?>
		<?php if ( $a['updated'] && get_the_modified_time( 'U' ) > get_the_time( 'U' ) + DAY_IN_SECONDS ) : ?>
			<span class="post-meta__updated"><?php echo hbg_svg_icon( 'refresh', 12 ); // phpcs:ignore ?> <?php printf( esc_html__( 'Updated %s', 'hoverboards-guide' ), esc_html( get_the_modified_date() ) ); ?></span>
		<?php endif; ?>
		<?php if ( $a['reading'] ) : ?>
			<span class="post-meta__reading"><?php echo hbg_svg_icon( 'clock', 12 ); // phpcs:ignore ?> <?php printf( esc_html__( '%d min read', 'hoverboards-guide' ), (int) hbg_reading_time() ); ?></span>
		<?php endif; ?>
		<?php if ( $a['views'] && get_theme_mod( 'hbg_show_views', true ) && hbg_get_views() > 0 ) : ?>
			<span class="post-meta__views"><?php echo hbg_svg_icon( 'eye', 12 ); // phpcs:ignore ?> <?php echo esc_html( hbg_format_number( hbg_get_views() ) ); ?></span>
		<?php endif; ?>
	</div>
	<?php
}

function hbg_author_display_name( $user_id = null ) {
	$user_id = $user_id ? $user_id : get_the_author_meta( 'ID' );
	$name    = get_the_author_meta( 'display_name', $user_id );
	$fallback = get_theme_mod( 'hbg_author_name', __( 'Hoverboards Guide Editors', 'hoverboards-guide' ) );
	if ( $fallback && ( ! $name || 'admin' === strtolower( $name ) ) ) {
		return $fallback;
	}
	return $name ? $name : get_bloginfo( 'name' );
}

function hbg_excerpt( $words = 24, $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$text = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
	$text = wp_strip_all_tags( strip_shortcodes( $text ) );
	$text = preg_replace( '/\s+/', ' ', $text );
	return wp_trim_words( $text, $words, '…' );
}

// -------------------------------------------------------------------------
// Featured posts (sticky first, then latest / chosen category)
// -------------------------------------------------------------------------
function hbg_get_featured_posts( $count = 5 ) {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$source  = get_theme_mod( 'hbg_featured_source', 'sticky' );
	$posts   = array();
	$exclude = array();

	if ( 'sticky' === $source ) {
		$sticky = get_option( 'sticky_posts' );
		if ( ! empty( $sticky ) ) {
			$q = new WP_Query( array( 'post__in' => $sticky, 'posts_per_page' => $count, 'ignore_sticky_posts' => true, 'orderby' => 'date', 'no_found_rows' => true ) );
			$posts = $q->posts;
		}
	} elseif ( 'category' === $source && (int) get_theme_mod( 'hbg_featured_category', 0 ) ) {
		$q = new WP_Query( array( 'cat' => (int) get_theme_mod( 'hbg_featured_category', 0 ), 'posts_per_page' => $count, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
		$posts = $q->posts;
	}

	if ( count( $posts ) < $count ) {
		$exclude = wp_list_pluck( $posts, 'ID' );
		$q = new WP_Query( array( 'posts_per_page' => $count - count( $posts ), 'post__not_in' => $exclude, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
		$posts = array_merge( $posts, $q->posts );
	}
	$cache = $posts;
	return $cache;
}

function hbg_featured_ids() {
	return wp_list_pluck( hbg_get_featured_posts( (int) get_theme_mod( 'hbg_featured_count', 5 ) ), 'ID' );
}

/**
 * Resolve a category from a customizer setting (ID) or a list of default slugs.
 */
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

function hbg_popular_posts( $count = 5, $exclude = array() ) {
	// v2.1: the two ranking queries are cached for 15 minutes (view counts change constantly,
	// so an ordered ID list is stored and re-queried with post__in — cheap and always fresh enough).
	$exclude = array_values( array_filter( array_map( 'absint', (array) $exclude ) ) );
	$key     = 'hbg_popular_' . md5( $count . '|' . implode( ',', $exclude ) );
	$ids     = get_transient( $key );
	if ( false === $ids || ! is_array( $ids ) ) {
		$q = new WP_Query( array(
			'posts_per_page'      => $count,
			'meta_key'            => 'hbg_views',
			'orderby'             => 'meta_value_num',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'post__not_in'        => $exclude,
			'fields'              => 'ids',
		) );
		$ids = array_map( 'intval', $q->posts );
		if ( count( $ids ) < $count ) {
			// Not enough view data yet — fall back to most-commented, then latest.
			$q2  = new WP_Query( array(
				'posts_per_page'      => $count - count( $ids ),
				'orderby'             => 'comment_count date',
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'post__not_in'        => array_merge( $ids, $exclude ),
				'fields'              => 'ids',
			) );
			$ids = array_merge( $ids, array_map( 'intval', $q2->posts ) );
		}
		set_transient( $key, $ids, 15 * MINUTE_IN_SECONDS );
	}
	if ( ! $ids ) {
		return new WP_Query( array( 'post__in' => array( 0 ), 'no_found_rows' => true ) );
	}
	return new WP_Query( array(
		'post__in'            => $ids,
		'orderby'             => 'post__in',
		'posts_per_page'      => count( $ids ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	) );
}

function hbg_related_posts( $post_id = null, $count = 3 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$cats    = wp_get_post_categories( $post_id );
	$tags    = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
	$args    = array(
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'orderby'             => 'rand',
	);
	if ( $tags ) {
		$args['tag__in'] = $tags;
	} elseif ( $cats ) {
		$args['category__in'] = $cats;
	}
	$q = new WP_Query( $args );
	if ( $q->post_count < $count && $cats ) {
		$args2 = $args;
		unset( $args2['tag__in'] );
		$args2['category__in']   = $cats;
		$args2['posts_per_page'] = $count - $q->post_count;
		$args2['post__not_in']   = array_merge( array( $post_id ), wp_list_pluck( $q->posts, 'ID' ) );
		$q2 = new WP_Query( $args2 );
		$q->posts      = array_merge( $q->posts, $q2->posts );
		$q->post_count = count( $q->posts );
	}
	return $q;
}

function hbg_trending_posts( $count = 6 ) {
	if ( 'popular' === get_theme_mod( 'hbg_ticker_source', 'latest' ) ) {
		return hbg_popular_posts( $count );
	}
	return new WP_Query( array( 'posts_per_page' => $count, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
}

// -------------------------------------------------------------------------
// Breadcrumbs
// -------------------------------------------------------------------------
function hbg_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	$items   = array();
	$items[] = array( 'url' => home_url( '/' ), 'label' => __( 'Home', 'hoverboards-guide' ) );

	if ( is_singular( 'post' ) ) {
		$cat = hbg_primary_category();
		if ( $cat ) {
			$items[] = array( 'url' => get_category_link( $cat ), 'label' => $cat->name );
		}
		$items[] = array( 'url' => '', 'label' => get_the_title() );
	} elseif ( is_page() ) {
		$items[] = array( 'url' => '', 'label' => get_the_title() );
	} elseif ( is_category() ) {
		$cat = get_queried_object();
		if ( $cat && $cat->parent ) {
			$parent  = get_term( $cat->parent, 'category' );
			$items[] = array( 'url' => get_category_link( $parent ), 'label' => $parent->name );
		}
		$items[] = array( 'url' => '', 'label' => single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$items[] = array( 'url' => '', 'label' => sprintf( __( 'Tag: %s', 'hoverboards-guide' ), single_tag_title( '', false ) ) );
	} elseif ( is_author() ) {
		$items[] = array( 'url' => '', 'label' => get_the_author() );
	} elseif ( is_search() ) {
		$items[] = array( 'url' => '', 'label' => __( 'Search', 'hoverboards-guide' ) );
	} elseif ( is_home() ) {
		$items[] = array( 'url' => '', 'label' => __( 'Blog', 'hoverboards-guide' ) );
	} elseif ( is_404() ) {
		$items[] = array( 'url' => '', 'label' => __( 'Not found', 'hoverboards-guide' ) );
	} elseif ( is_archive() ) {
		$items[] = array( 'url' => '', 'label' => get_the_archive_title() );
	}

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
// Share buttons
// -------------------------------------------------------------------------
function hbg_share_buttons( $variant = 'bar' ) {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$img   = has_post_thumbnail() ? rawurlencode( get_the_post_thumbnail_url( null, 'large' ) ) : '';
	$links = array(
		'facebook'  => array( 'https://www.facebook.com/sharer/sharer.php?u=' . $url, __( 'Share on Facebook', 'hoverboards-guide' ) ),
		'x'         => array( 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title, __( 'Share on X', 'hoverboards-guide' ) ),
		'pinterest' => array( 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $img . '&description=' . $title, __( 'Pin on Pinterest', 'hoverboards-guide' ) ),
		'whatsapp'  => array( 'https://api.whatsapp.com/send?text=' . $title . '%20' . $url, __( 'Share on WhatsApp', 'hoverboards-guide' ) ),
		'mail'      => array( 'mailto:?subject=' . $title . '&body=' . $url, __( 'Share by email', 'hoverboards-guide' ) ),
	);
	?>
	<div class="share share--<?php echo esc_attr( $variant ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>">
		<?php if ( 'bar' === $variant ) : ?>
			<span class="share__label"><?php echo hbg_svg_icon( 'share', 14 ); // phpcs:ignore ?> <?php esc_html_e( 'Share', 'hoverboards-guide' ); ?></span>
		<?php endif; ?>
		<?php foreach ( $links as $key => $l ) : ?>
			<a class="share__btn share__btn--<?php echo esc_attr( $key ); ?>" href="<?php echo esc_url( $l[0] ); ?>" target="_blank" rel="noopener nofollow" aria-label="<?php echo esc_attr( $l[1] ); ?>" title="<?php echo esc_attr( $l[1] ); ?>"><?php echo hbg_svg_icon( $key, 16 ); // phpcs:ignore ?></a>
		<?php endforeach; ?>
		<button type="button" class="share__btn share__btn--copy" data-copy aria-label="<?php esc_attr_e( 'Copy link', 'hoverboards-guide' ); ?>" title="<?php esc_attr_e( 'Copy link', 'hoverboards-guide' ); ?>"><?php echo hbg_svg_icon( 'link', 16 ); // phpcs:ignore ?></button>
	</div>
	<?php
}

// -------------------------------------------------------------------------
// Table of contents: inject heading IDs and collect items.
// -------------------------------------------------------------------------
function hbg_toc_prepare( $content ) {
	$items = array();
	$used  = array();
	$content = preg_replace_callback(
		'/<h([23])([^>]*)>(.*?)<\/h\1>/is',
		function ( $m ) use ( &$items, &$used ) {
			$level = (int) $m[1];
			$attrs = $m[2];
			$text  = wp_strip_all_tags( $m[3] );
			// Skip empty headings and headings that belong to product boxes / opt-out blocks.
			if ( '' === trim( $text ) || preg_match( '/class=["\'][^"\']*(product-box__name|no-toc|newsletter__title|author-box__name)/', $attrs ) ) {
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
	if ( count( $h2s ) < 2 ) {
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
			foreach ( $items as $item ) :
				if ( 2 === $item['level'] ) {
					if ( $open_sub ) {
						echo '</ol></li>';
						$open_sub = false;
					} else if ( isset( $printed ) ) {
						echo '</li>';
					}
					echo '<li><a href="#' . esc_attr( $item['id'] ) . '">' . esc_html( $item['text'] ) . '</a>';
					$printed = true;
				} elseif ( isset( $printed ) ) {
					if ( ! $open_sub ) {
						echo '<ol class="toc__sub">';
						$open_sub = true;
					}
					echo '<li><a href="#' . esc_attr( $item['id'] ) . '">' . esc_html( $item['text'] ) . '</a></li>';
				}
			endforeach;
			if ( $open_sub ) {
				echo '</ol>';
			}
			if ( isset( $printed ) ) {
				echo '</li>';
			}
			?>
		</ol>
	</nav>
	<?php
}

// -------------------------------------------------------------------------
// Author box
// -------------------------------------------------------------------------
function hbg_author_box() {
	$id   = get_the_author_meta( 'ID' );
	$bio  = get_the_author_meta( 'description', $id );
	$bio  = $bio ? $bio : get_theme_mod( 'hbg_author_bio', __( 'Our editors research, ride and re-check every hoverboard and electric scooter we recommend — and update guides weekly as prices and models change.', 'hoverboards-guide' ) );
	?>
	<aside class="author-box" aria-label="<?php esc_attr_e( 'About the author', 'hoverboards-guide' ); ?>">
		<?php echo get_avatar( $id, 80, '', '', array( 'class' => 'author-box__avatar' ) ); ?>
		<div class="author-box__body">
			<div class="author-box__kicker"><?php esc_html_e( 'Written by', 'hoverboards-guide' ); ?></div>
			<h3 class="author-box__name"><a href="<?php echo esc_url( get_author_posts_url( $id ) ); ?>"><?php echo esc_html( hbg_author_display_name( $id ) ); ?></a></h3>
			<p class="author-box__bio"><?php echo esc_html( $bio ); ?></p>
			<a class="author-box__link" href="<?php echo esc_url( get_author_posts_url( $id ) ); ?>"><?php esc_html_e( 'More articles', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 14 ); // phpcs:ignore ?></a>
		</div>
	</aside>
	<?php
}

// -------------------------------------------------------------------------
// Social links (from Customizer)
// -------------------------------------------------------------------------
function hbg_social_links() {
	$networks = array(
		'facebook'  => 'Facebook',
		'x'         => 'X',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'pinterest' => 'Pinterest',
		'tiktok'    => 'TikTok',
	);
	$out = array();
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
// Newsletter form
// -------------------------------------------------------------------------
function hbg_newsletter_form( $args = array() ) {
	$a = wp_parse_args( $args, array( 'variant' => 'box', 'title' => '', 'sub' => '', 'source' => 'inline' ) );
	$action = get_theme_mod( 'hbg_newsletter_action', '' );
	$field  = get_theme_mod( 'hbg_newsletter_email_field', 'EMAIL' );
	$title  = $a['title'] ? $a['title'] : get_theme_mod( 'hbg_newsletter_title', __( 'Get the weekly ride report', 'hoverboards-guide' ) );
	$sub    = $a['sub'] ? $a['sub'] : get_theme_mod( 'hbg_newsletter_sub', __( 'New reviews, real deals and safety recalls — one email a week, no spam.', 'hoverboards-guide' ) );
	$uid    = 'nl-' . wp_unique_id();
	?>
	<div class="newsletter newsletter--<?php echo esc_attr( $a['variant'] ); ?>">
		<div class="newsletter__text">
			<div class="newsletter__icon" aria-hidden="true"><?php echo hbg_svg_icon( 'mail', 20 ); // phpcs:ignore ?></div>
			<h3 class="newsletter__title"><?php echo esc_html( $title ); ?></h3>
			<p class="newsletter__sub"><?php echo esc_html( $sub ); ?></p>
		</div>
		<form class="newsletter__form" method="post" action="<?php echo $action ? esc_url( $action ) : ''; ?>" <?php echo $action ? 'target="_blank"' : 'data-ajax-subscribe'; ?> novalidate>
			<label for="<?php echo esc_attr( $uid ); ?>" class="screen-reader-text"><?php esc_html_e( 'Email address', 'hoverboards-guide' ); ?></label>
			<input type="email" id="<?php echo esc_attr( $uid ); ?>" name="<?php echo esc_attr( $action ? $field : 'email' ); ?>" placeholder="<?php esc_attr_e( 'you@example.com', 'hoverboards-guide' ); ?>" required autocomplete="email">
			<input type="text" name="hbg_hp" class="hbg-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
			<input type="hidden" name="source" value="<?php echo esc_attr( $a['source'] ); ?>">
			<button type="submit" class="btn btn--accent"><?php esc_html_e( 'Subscribe', 'hoverboards-guide' ); ?></button>
			<p class="newsletter__msg" role="status" aria-live="polite"></p>
		</form>
		<p class="newsletter__fine"><?php esc_html_e( 'Unsubscribe any time. We never share your email.', 'hoverboards-guide' ); ?></p>
	</div>
	<?php
}

// -------------------------------------------------------------------------
// Affiliate disclosure box
// -------------------------------------------------------------------------
function hbg_affiliate_disclosure_box() {
	$text = get_theme_mod( 'hbg_affiliate_disclosure', __( 'As an Amazon Associate, Hoverboards Guide earns from qualifying purchases. Prices and availability are accurate as of the date/time indicated and are subject to change.', 'hoverboards-guide' ) );
	?>
	<div class="disclosure" role="note">
		<?php echo hbg_svg_icon( 'info', 16 ); // phpcs:ignore ?>
		<p><?php echo esc_html( $text ); ?></p>
	</div>
	<?php
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
// Category tile (image + name + count)
// -------------------------------------------------------------------------
function hbg_category_tile( $term ) {
	$preset = hbg_category_preset( $term );
	?>
	<a href="<?php echo esc_url( get_category_link( $term ) ); ?>" class="cat-tile" style="--cat:<?php echo esc_attr( $preset['color'] ); ?>">
		<?php hbg_image( $preset['image'], array( 'alt' => '', 'class' => 'cat-tile__img', 'sizes' => '(max-width: 640px) 50vw, 200px' ) ); ?>
		<span class="cat-tile__body">
			<span class="cat-tile__name"><?php echo esc_html( $term->name ); ?></span>
			<span class="cat-tile__count"><?php printf( esc_html( _n( '%s article', '%s articles', $term->count, 'hoverboards-guide' ) ), esc_html( number_format_i18n( $term->count ) ) ); ?></span>
		</span>
	</a>
	<?php
}

// =========================================================================
// v2.1 helpers: product review lookup, "All articles" URL, TOC detection
// =========================================================================

/**
 * URL of the site's own review of a registry product (title match, cached for a day), or ''.
 */
function hbg_product_review_url( $product ) {
	if ( empty( $product['id'] ) ) {
		return '';
	}
	$key = 'hbg_review_' . md5( $product['id'] );
	$url = get_transient( $key );
	if ( false !== $url ) {
		return $url;
	}
	$needles = array_unique( array_filter( array(
		isset( $product['short'] ) ? $product['short'] : '',
		isset( $product['name'] ) ? $product['name'] : '',
	) ) );
	$url = '';
	foreach ( $needles as $needle ) {
		$q = new WP_Query( array( 's' => $needle, 'post_type' => 'post', 'posts_per_page' => 5, 'no_found_rows' => true, 'ignore_sticky_posts' => true, 'fields' => 'ids' ) );
		foreach ( $q->posts as $pid ) {
			if ( false !== stripos( get_the_title( $pid ), $needle ) ) {
				$url = get_permalink( $pid );
				break 2;
			}
		}
	}
	set_transient( $key, $url, DAY_IN_SECONDS );
	return apply_filters( 'hbg_product_review_url', $url, $product );
}

/**
 * Where "All articles" should go: the Customizer URL, else the Posts page, else nowhere (link hidden).
 */
function hbg_all_articles_url() {
	$custom = get_theme_mod( 'hbg_all_articles_url', '' );
	if ( $custom ) {
		return $custom;
	}
	$page = (int) get_option( 'page_for_posts' );
	if ( $page && 'page' === get_option( 'show_on_front' ) ) {
		return get_permalink( $page );
	}
	return '';
}

/**
 * True when the post content already carries a table of contents (plugin markup or a
 * hand-made "Table of Contents" list of in-page anchors) — the theme then skips its own.
 */
function hbg_content_has_toc( $content ) {
	if ( preg_match( '/(ez-toc-container|lwptoc|toc_container|wp-block-rank-math-toc-block|wp-block-yoast-seo-table-of-contents|aioseo-table-of-contents|class=["\'][^"\']*\btoc\b|id=["\']toc["\'])/i', $content ) ) {
		return true;
	}
	if ( preg_match( '/(table of contents|in this (article|guide|post|review))[\s\S]{0,400}<(ul|ol)[^>]*>[\s\S]{0,300}href=["\']#/i', $content ) ) {
		return true;
	}
	return (bool) apply_filters( 'hbg_content_has_toc', false, $content );
}
