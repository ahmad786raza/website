<?php
/**
 * Template tags — cards, bylines, breadcrumbs, related content.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Primary category of a post (Yoast/Rank Math primary term respected when present).
 */
function bdh_primary_category( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$primary = 0;
	foreach ( array( '_yoast_wpseo_primary_category', 'rank_math_primary_category' ) as $meta ) {
		$val = (int) get_post_meta( $post_id, $meta, true );
		if ( $val ) { $primary = $val; break; }
	}
	if ( $primary ) {
		$term = get_term( $primary, 'category' );
		if ( $term && ! is_wp_error( $term ) ) { return $term; }
	}
	$cats = get_the_category( $post_id );
	if ( ! $cats ) { return null; }
	$default = (int) get_option( 'default_category' );
	foreach ( $cats as $cat ) {
		if ( (int) $cat->term_id !== $default ) { return $cat; }
	}
	return $cats[0];
}

/**
 * Category kicker label.
 */
function bdh_kicker( $post_id = 0, $link = true ) {
	$cat = bdh_primary_category( $post_id );
	if ( ! $cat ) { return ''; }
	if ( $link ) {
		return sprintf( '<a class="bdh-kicker" href="%s">%s</a>', esc_url( get_category_link( $cat ) ), esc_html( $cat->name ) );
	}
	return '<span class="bdh-kicker">' . esc_html( $cat->name ) . '</span>';
}

/**
 * Image slot that best represents a post when it has no featured image.
 * Matches on category/tag/title words; returns a slot key from the image library.
 */
function bdh_post_image_slot( $post_id ) {
	$hay = strtolower( get_the_title( $post_id ) . ' ' . implode( ' ', wp_list_pluck( get_the_category( $post_id ) ? get_the_category( $post_id ) : array(), 'slug' ) ) . ' ' . implode( ' ', wp_list_pluck( get_the_tags( $post_id ) ? get_the_tags( $post_id ) : array(), 'name' ) ) );
	$map = array(
		'roll-cage'          => array( 'roll cage', 'rollcage', 'cage', 'nhra', 'scca', 'fia' ),
		'chassis'            => array( 'chassis', 'notch', 'fishmouth', 'chromoly', '4130' ),
		'exhaust'            => array( 'exhaust', 'header', 'muffler', 'downpipe' ),
		'mandrel'            => array( 'mandrel', 'wrinkl', 'collapse', 'ovality' ),
		'cnc-bender'         => array( 'cnc', 'production', 'automation' ),
		'hydraulic-bender'   => array( 'hydraulic', 'tonnage', 'pipe bender' ),
		'conduit-bender'     => array( 'conduit', 'emt', 'electrician' ),
		'stainless'          => array( 'stainless', 't304', 'aluminum', 'aluminium' ),
		'workshop'           => array( 'workshop', 'garage', 'shop', 'tools every' ),
		'welding'            => array( 'weld', 'tig', 'mig' ),
		'measuring'          => array( 'radius', 'clr', 'springback', 'k-factor', 'calculat', 'theory', 'geometry' ),
		'safety'             => array( 'safety', 'osha', 'ppe' ),
		'manual-bender'      => array( 'manual', 'beginner', 'tube bender' ),
	);
	foreach ( $map as $slot => $needles ) {
		foreach ( $needles as $needle ) {
			if ( false !== strpos( $hay, $needle ) ) { return $slot; }
		}
	}
	return 'bent-tube';
}

/**
 * Card / lead image for a post: featured image, else a licensed library
 * image matched to the topic, else a neutral drawn placeholder.
 */
function bdh_post_image( $post_id, $size = 'bdh-card', $attr = array() ) {
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail( $post_id, $size, $attr );
	}
	if ( function_exists( 'bdh_library_attachment' ) ) {
		$att = bdh_library_attachment( bdh_post_image_slot( $post_id ), $post_id );
		if ( $att ) {
			$attr['alt'] = isset( $attr['alt'] ) ? $attr['alt'] : get_post_meta( $att, '_wp_attachment_image_alt', true );
			return wp_get_attachment_image( $att, $size, false, $attr );
		}
	}
	return '<span class="bdh-card-placeholder" aria-hidden="true">' . bdh_hero_bend_svg() . '</span>';
}

/**
 * Standard article card.
 *
 * @param string $variant card|lead|compact|list
 */
function bdh_post_card( $post_id = 0, $variant = 'card', $heading = 'h3' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$heading = in_array( $heading, array( 'h2', 'h3', 'h4' ), true ) ? $heading : 'h3';
	$size    = 'lead' === $variant ? 'bdh-lead' : ( 'compact' === $variant || 'list' === $variant ? 'bdh-thumb' : 'bdh-card' );
	$url     = get_permalink( $post_id );
	$excerpt = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post_id ) ) ), 28 );
	$words   = 'lead' === $variant ? 40 : 22;
	?>
	<article class="bdh-card bdh-card--<?php echo esc_attr( $variant ); ?>">
		<a class="bdh-card-media" href="<?php echo esc_url( $url ); ?>" tabindex="-1" aria-hidden="true">
			<?php echo bdh_post_image( $post_id, $size, array( 'loading' => 'lead' === $variant ? 'eager' : 'lazy', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</a>
		<div class="bdh-card-body">
			<?php echo bdh_kicker( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<<?php echo $heading; ?> class="bdh-card-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></<?php echo $heading; ?>>
			<?php if ( 'compact' !== $variant ) : ?>
				<p class="bdh-card-excerpt"><?php echo esc_html( wp_trim_words( $excerpt, $words ) ); ?></p>
			<?php endif; ?>
			<p class="bdh-card-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( get_the_date( '', $post_id ) ); ?></time>
				<span aria-hidden="true">·</span>
				<span><?php echo (int) bdh_reading_time( get_post_field( 'post_content', $post_id ) ); ?> <?php esc_html_e( 'min read', 'benders-hub' ); ?></span>
			</p>
		</div>
	</article>
	<?php
}

/**
 * Byline: author, published and (meaningfully) updated dates, reading time.
 */
function bdh_byline( $post_id = 0 ) {
	$post_id   = $post_id ? $post_id : get_the_ID();
	$author_id = (int) get_post_field( 'post_author', $post_id );
	$published = get_post_time( 'U', true, $post_id );
	$modified  = get_post_modified_time( 'U', true, $post_id );
	?>
	<div class="bdh-byline">
		<?php if ( $author_id ) : ?>
			<span class="bdh-byline-author">
				<?php echo get_avatar( $author_id, 40, '', '', array( 'class' => 'bdh-avatar' ) ); ?>
				<span><?php esc_html_e( 'By', 'benders-hub' ); ?> <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" rel="author"><?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?></a></span>
			</span>
		<?php endif; ?>
		<span class="bdh-byline-dates">
			<span><?php esc_html_e( 'Published', 'benders-hub' ); ?> <time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( get_the_date( '', $post_id ) ); ?></time></span>
			<?php if ( $modified - $published > DAY_IN_SECONDS ) : ?>
				<span><?php esc_html_e( 'Updated', 'benders-hub' ); ?> <time datetime="<?php echo esc_attr( get_the_modified_date( 'c', $post_id ) ); ?>"><?php echo esc_html( get_the_modified_date( '', $post_id ) ); ?></time></span>
			<?php endif; ?>
			<span><?php echo (int) bdh_reading_time( get_post_field( 'post_content', $post_id ) ); ?> <?php esc_html_e( 'min read', 'benders-hub' ); ?></span>
		</span>
	</div>
	<?php
}

/**
 * Breadcrumb trail as an array of [label, url]. Shared by the visible trail and the JSON-LD.
 */
function bdh_breadcrumb_items() {
	$items = array( array( __( 'Home', 'benders-hub' ), home_url( '/' ) ) );
	if ( is_singular( 'post' ) ) {
		$cat = bdh_primary_category( get_queried_object_id() );
		if ( $cat ) {
			$ancestors = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
			foreach ( $ancestors as $anc ) {
				$t = get_term( $anc, 'category' );
				if ( $t && ! is_wp_error( $t ) ) { $items[] = array( $t->name, get_category_link( $t ) ); }
			}
			$items[] = array( $cat->name, get_category_link( $cat ) );
		}
		$items[] = array( get_the_title( get_queried_object_id() ), '' );
	} elseif ( is_singular( array( 'bdh_product', 'bdh_guide' ) ) ) {
		$pt = get_post_type_object( get_post_type( get_queried_object_id() ) );
		if ( $pt && $pt->has_archive ) { $items[] = array( $pt->labels->name, get_post_type_archive_link( $pt->name ) ); }
		$items[] = array( get_the_title( get_queried_object_id() ), '' );
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $anc ) {
			$items[] = array( get_the_title( $anc ), get_permalink( $anc ) );
		}
		$items[] = array( get_the_title( get_queried_object_id() ), '' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! empty( $term->parent ) ) {
			foreach ( array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) ) as $anc ) {
				$t = get_term( $anc, $term->taxonomy );
				if ( $t && ! is_wp_error( $t ) ) { $items[] = array( $t->name, get_term_link( $t ) ); }
			}
		}
		$items[] = array( $term ? $term->name : '', '' );
	} elseif ( is_post_type_archive() ) {
		$items[] = array( post_type_archive_title( '', false ), '' );
	} elseif ( is_author() ) {
		$items[] = array( get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) ), '' );
	} elseif ( is_search() ) {
		$items[] = array( sprintf( __( 'Search: %s', 'benders-hub' ), get_search_query() ), '' );
	} elseif ( is_home() ) {
		$items[] = array( __( 'Articles', 'benders-hub' ), '' );
	}
	return $items;
}

/**
 * Visible breadcrumbs.
 */
function bdh_breadcrumbs() {
	if ( is_front_page() ) { return; }
	if ( function_exists( 'yoast_breadcrumb' ) && get_option( 'wpseo_titles' ) && ! empty( get_option( 'wpseo_titles' )['breadcrumbs-enable'] ) ) {
		yoast_breadcrumb( '<nav class="bdh-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'benders-hub' ) . '">', '</nav>' );
		return;
	}
	$items = bdh_breadcrumb_items();
	echo '<nav class="bdh-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'benders-hub' ) . '"><ol>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		if ( $i === $last || ! $item[1] ) {
			echo '<li><span aria-current="page">' . esc_html( $item[0] ) . '</span></li>';
		} else {
			echo '<li><a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a></li>';
		}
	}
	echo '</ol></nav>';
}

/**
 * Related posts: same primary category first, then shared tags, excluding the current post.
 */
function bdh_related_posts( $post_id, $count = 3 ) {
	$cat     = bdh_primary_category( $post_id );
	$exclude = array( $post_id );
	$ids     = array();
	if ( $cat ) {
		$ids = get_posts( array( 'post_type' => 'post', 'posts_per_page' => $count, 'cat' => $cat->term_id, 'post__not_in' => $exclude, 'fields' => 'ids', 'no_found_rows' => true, 'ignore_sticky_posts' => true ) );
	}
	if ( count( $ids ) < $count ) {
		$tags = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
		if ( $tags ) {
			$more = get_posts( array( 'post_type' => 'post', 'posts_per_page' => $count - count( $ids ), 'tag__in' => $tags, 'post__not_in' => array_merge( $exclude, $ids ), 'fields' => 'ids', 'no_found_rows' => true, 'ignore_sticky_posts' => true ) );
			$ids  = array_merge( $ids, $more );
		}
	}
	if ( count( $ids ) < $count ) {
		$more = get_posts( array( 'post_type' => 'post', 'posts_per_page' => $count - count( $ids ), 'post__not_in' => array_merge( $exclude, $ids ), 'fields' => 'ids', 'no_found_rows' => true, 'ignore_sticky_posts' => true ) );
		$ids  = array_merge( $ids, $more );
	}
	return $ids;
}

/**
 * Next-step tool suggestions for an article, chosen from its topic.
 */
function bdh_next_steps( $post_id ) {
	$hay   = strtolower( get_the_title( $post_id ) . ' ' . implode( ' ', wp_list_pluck( get_the_category( $post_id ) ? get_the_category( $post_id ) : array(), 'slug' ) ) . ' ' . implode( ' ', wp_list_pluck( get_the_tags( $post_id ) ? get_the_tags( $post_id ) : array(), 'name' ) ) );
	$steps = array();
	if ( preg_match( '/radius|clr|springback|k-factor|theory|wrinkl|geometry|notch|roll cage|chassis|exhaust/', $hay ) ) {
		$steps[] = 'calculator';
	}
	if ( preg_match( '/buy|guide|best|choos|beginner|hydraulic|manual|workshop|garage|cnc|bender/', $hay ) ) {
		$steps[] = 'quiz';
	}
	if ( preg_match( '/mandrel|rotary|press|process|vs|compar|cnc|hydraulic/', $hay ) ) {
		$steps[] = 'comparison';
	}
	if ( preg_match( '/radius|clr|springback|geometry|wrinkl|stainless/', $hay ) ) {
		$steps[] = 'visualizer';
	}
	$steps = array_values( array_unique( $steps ) );
	if ( count( $steps ) < 2 ) { $steps = array_values( array_unique( array_merge( $steps, array( 'quiz', 'calculator' ) ) ) ); }
	return array_slice( $steps, 0, 3 );
}

/**
 * Does post content contain affiliate links or product boxes?
 */
function bdh_post_has_affiliate( $post_id ) {
	$content = (string) get_post_field( 'post_content', $post_id );
	if ( preg_match( '#https?://(www\.)?(amazon\.[a-z.]+|amzn\.to)/#i', $content ) ) { return true; }
	foreach ( array( 'bdh_amazon', 'bdh_product_box', 'bdh_product' ) as $sc ) {
		if ( has_shortcode( $content, $sc ) ) { return true; }
	}
	return (bool) get_post_meta( $post_id, '_bdh_amazon_keyword', true ) && 'bdh_product' === get_post_type( $post_id );
}

/**
 * Credit line for an attachment imported through the image library
 * (required for CC BY / CC BY-SA images, shown for all imported images).
 */
function bdh_image_credit( $attachment_id ) {
	$creator = get_post_meta( $attachment_id, '_bdh_creator', true );
	$license = get_post_meta( $attachment_id, '_bdh_license', true );
	if ( ! $creator && ! $license ) { return ''; }
	$source  = get_post_meta( $attachment_id, '_bdh_source_url', true );
	$lic_url = get_post_meta( $attachment_id, '_bdh_license_url', true );
	$parts   = array();
	$parts[] = $source ? '<a href="' . esc_url( $source ) . '" rel="noopener" target="_blank">' . esc_html( $creator ? $creator : __( 'Source', 'benders-hub' ) ) . '</a>' : esc_html( $creator );
	if ( $license ) {
		$parts[] = $lic_url ? '<a href="' . esc_url( $lic_url ) . '" rel="license noopener" target="_blank">' . esc_html( $license ) . '</a>' : esc_html( $license );
	}
	return '<span class="bdh-credit">' . esc_html__( 'Photo:', 'benders-hub' ) . ' ' . implode( ' / ', $parts ) . '</span>';
}

/**
 * Archive/feed pagination with accessible labels.
 */
function bdh_pagination() {
	the_posts_pagination( array(
		'mid_size'           => 1,
		'prev_text'          => '&larr; ' . __( 'Newer', 'benders-hub' ),
		'next_text'          => __( 'Older', 'benders-hub' ) . ' &rarr;',
		'screen_reader_text' => __( 'More articles', 'benders-hub' ),
		'class'              => 'bdh-pagination',
	) );
}
