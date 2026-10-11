<?php
/**
 * Theme widgets: Popular posts, Category posts, Top picks, Newsletter.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared renderer for compact numbered post lists.
 */
function hbg_render_post_list( $query, $numbered = true ) {
	if ( ! $query->have_posts() ) {
		return;
	}
	echo '<ol class="post-list' . ( $numbered ? ' post-list--numbered' : '' ) . '">';
	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		<li class="post-list__item">
			<a href="<?php the_permalink(); ?>" class="post-list__thumb" tabindex="-1" aria-hidden="true">
				<?php hbg_post_thumbnail( 'hbg-thumb', array( 'sizes' => '96px' ) ); ?>
			</a>
			<div class="post-list__body">
				<?php hbg_cat_badge( null, false, 'cat-badge--sm' ); ?>
				<a href="<?php the_permalink(); ?>" class="post-list__title"><?php the_title(); ?></a>
				<?php hbg_post_meta( array( 'reading' => false, 'class' => 'post-meta--sm' ) ); ?>
			</div>
		</li>
		<?php
	}
	echo '</ol>';
	wp_reset_postdata();
}

class HBG_Widget_Popular extends WP_Widget {
	public function __construct() {
		parent::__construct( 'hbg_popular', __( 'HBG: Popular Posts', 'hoverboards-guide' ), array( 'description' => __( 'Most-read articles (by view count).', 'hoverboards-guide' ) ) );
	}
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Most read', 'hoverboards-guide' );
		$count = ! empty( $instance['count'] ) ? (int) $instance['count'] : 5;
		echo $args['before_widget']; // phpcs:ignore
		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title']; // phpcs:ignore
		hbg_render_post_list( hbg_popular_posts( $count ) );
		echo $args['after_widget']; // phpcs:ignore
	}
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$count = isset( $instance['count'] ) ? (int) $instance['count'] : 5;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'hoverboards-guide' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of posts:', 'hoverboards-guide' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $count ); ?>"></p>
		<?php
	}
	public function update( $new, $old ) {
		return array( 'title' => sanitize_text_field( $new['title'] ), 'count' => min( 10, max( 1, (int) $new['count'] ) ) );
	}
}

class HBG_Widget_Category_Posts extends WP_Widget {
	public function __construct() {
		parent::__construct( 'hbg_cat_posts', __( 'HBG: Category Posts', 'hoverboards-guide' ), array( 'description' => __( 'Latest posts from one category, with thumbnails.', 'hoverboards-guide' ) ) );
	}
	public function widget( $args, $instance ) {
		$cat   = ! empty( $instance['cat'] ) ? (int) $instance['cat'] : 0;
		$count = ! empty( $instance['count'] ) ? (int) $instance['count'] : 4;
		$term  = $cat ? get_term( $cat, 'category' ) : null;
		$title = ! empty( $instance['title'] ) ? $instance['title'] : ( $term && ! is_wp_error( $term ) ? $term->name : __( 'Latest', 'hoverboards-guide' ) );
		echo $args['before_widget']; // phpcs:ignore
		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title']; // phpcs:ignore
		$q = new WP_Query( array( 'posts_per_page' => $count, 'cat' => $cat, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
		hbg_render_post_list( $q, false );
		echo $args['after_widget']; // phpcs:ignore
	}
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$cat   = isset( $instance['cat'] ) ? (int) $instance['cat'] : 0;
		$count = isset( $instance['count'] ) ? (int) $instance['count'] : 4;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'hoverboards-guide' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php esc_html_e( 'Category:', 'hoverboards-guide' ); ?></label>
		<?php wp_dropdown_categories( array( 'id' => $this->get_field_id( 'cat' ), 'name' => $this->get_field_name( 'cat' ), 'selected' => $cat, 'show_option_all' => __( 'All', 'hoverboards-guide' ), 'class' => 'widefat', 'hide_empty' => false ) ); ?></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of posts:', 'hoverboards-guide' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $count ); ?>"></p>
		<?php
	}
	public function update( $new, $old ) {
		return array( 'title' => sanitize_text_field( $new['title'] ), 'cat' => (int) $new['cat'], 'count' => min( 10, max( 1, (int) $new['count'] ) ) );
	}
}

class HBG_Widget_Top_Picks extends WP_Widget {
	public function __construct() {
		parent::__construct( 'hbg_top_picks', __( 'HBG: Top Picks', 'hoverboards-guide' ), array( 'description' => __( 'Compact list of the editor’s top picks with Amazon buttons.', 'hoverboards-guide' ) ) );
	}
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Editor’s top picks', 'hoverboards-guide' );
		echo $args['before_widget']; // phpcs:ignore
		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title']; // phpcs:ignore
		echo '<div class="mini-picks">';
		foreach ( hbg_get_top_picks() as $p ) {
			?>
			<div class="mini-pick">
				<?php hbg_product_image( $p, array( 'class' => 'product-image mini-pick__img', 'ratio' => '1/1', 'note' => false, 'sizes' => '72px' ) ); ?>
				<div class="mini-pick__body">
					<span class="mini-pick__badge"><?php echo esc_html( $p['badge'] ); ?></span>
					<strong class="mini-pick__name"><?php echo esc_html( $p['name'] ); ?></strong>
					<a href="<?php echo esc_url( hbg_amazon_url( $p['search'] ) ); ?>" class="mini-pick__link" target="_blank" rel="sponsored nofollow noopener"><?php esc_html_e( 'Check price', 'hoverboards-guide' ); ?> <?php echo hbg_svg_icon( 'arrow-right', 12 ); // phpcs:ignore ?></a>
				</div>
			</div>
			<?php
		}
		echo '</div>';
		echo $args['after_widget']; // phpcs:ignore
	}
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'hoverboards-guide' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>
		<?php
	}
	public function update( $new, $old ) {
		return array( 'title' => sanitize_text_field( $new['title'] ) );
	}
}

class HBG_Widget_Newsletter extends WP_Widget {
	public function __construct() {
		parent::__construct( 'hbg_newsletter', __( 'HBG: Newsletter', 'hoverboards-guide' ), array( 'description' => __( 'Email signup box.', 'hoverboards-guide' ) ) );
	}
	public function widget( $args, $instance ) {
		echo $args['before_widget']; // phpcs:ignore
		hbg_newsletter_form( array( 'variant' => 'box', 'title' => isset( $instance['title'] ) ? $instance['title'] : '', 'source' => 'widget' ) );
		echo $args['after_widget']; // phpcs:ignore
	}
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title (optional):', 'hoverboards-guide' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>
		<?php
	}
	public function update( $new, $old ) {
		return array( 'title' => sanitize_text_field( $new['title'] ) );
	}
}

function hbg_register_widgets() {
	register_widget( 'HBG_Widget_Popular' );
	register_widget( 'HBG_Widget_Category_Posts' );
	register_widget( 'HBG_Widget_Top_Picks' );
	register_widget( 'HBG_Widget_Newsletter' );
}
add_action( 'widgets_init', 'hbg_register_widgets' );
