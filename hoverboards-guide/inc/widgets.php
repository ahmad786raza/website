<?php
/**
 * Theme widgets: Most read, Category posts, Recommendations, Topic hubs.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HBG_Widget_Popular extends WP_Widget {
	public function __construct() {
		parent::__construct( 'hbg_popular', __( 'HBG: Most Read', 'hoverboards-guide' ), array( 'description' => __( 'Most-read articles (by the theme’s view counter).', 'hoverboards-guide' ) ) );
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
		parent::__construct( 'hbg_cat_posts', __( 'HBG: Category Posts', 'hoverboards-guide' ), array( 'description' => __( 'Latest posts from one category.', 'hoverboards-guide' ) ) );
	}
	public function widget( $args, $instance ) {
		$cat   = ! empty( $instance['cat'] ) ? (int) $instance['cat'] : 0;
		$count = ! empty( $instance['count'] ) ? (int) $instance['count'] : 5;
		$term  = $cat ? get_term( $cat, 'category' ) : null;
		$title = ! empty( $instance['title'] ) ? $instance['title'] : ( $term && ! is_wp_error( $term ) ? $term->name : __( 'Latest', 'hoverboards-guide' ) );
		$q     = new WP_Query( array( 'posts_per_page' => $count, 'cat' => $cat, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
		if ( ! $q->have_posts() ) {
			return;
		}
		echo $args['before_widget']; // phpcs:ignore
		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title']; // phpcs:ignore
		hbg_render_post_list( $q, false );
		echo $args['after_widget']; // phpcs:ignore
	}
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$cat   = isset( $instance['cat'] ) ? (int) $instance['cat'] : 0;
		$count = isset( $instance['count'] ) ? (int) $instance['count'] : 5;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'hoverboards-guide' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php esc_html_e( 'Category:', 'hoverboards-guide' ); ?></label>
		<?php wp_dropdown_categories( array( 'id' => $this->get_field_id( 'cat' ), 'name' => $this->get_field_name( 'cat' ), 'selected' => $cat, 'hide_empty' => false, 'class' => 'widefat' ) ); ?></p>
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
		parent::__construct( 'hbg_top_picks', __( 'HBG: Recommendations', 'hoverboards-guide' ), array( 'description' => __( 'Compact product recommendations with Amazon links.', 'hoverboards-guide' ) ) );
	}
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Our picks', 'hoverboards-guide' );
		$picks = hbg_get_top_picks( 4 );
		if ( ! $picks ) {
			return;
		}
		echo $args['before_widget']; // phpcs:ignore
		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title']; // phpcs:ignore
		echo '<div class="mini-picks">';
		foreach ( $picks as $p ) {
			$review = hbg_product_review_url( $p );
			echo '<div class="mini-pick">';
			hbg_product_image( $p, array( 'class' => 'product-image mini-pick__img', 'ratio' => '1/1', 'note' => false, 'sizes' => '72px' ) );
			echo '<div class="mini-pick__body"><span class="mini-pick__badge">' . esc_html( $p['badge'] ) . '</span><strong class="mini-pick__name">' . ( $review ? '<a href="' . esc_url( $review ) . '">' . esc_html( $p['name'] ) . '</a>' : esc_html( $p['name'] ) ) . '</strong>';
			echo '<a href="' . hbg_amazon_url( $p['search'] ) . '" class="mini-pick__link" target="_blank" rel="sponsored nofollow noopener" data-hbg-track="affiliate" data-product="' . esc_attr( $p['id'] ) . '" data-placement="sidebar">' . esc_html__( 'Check price on Amazon', 'hoverboards-guide' ) . ' ' . hbg_svg_icon( 'external', 12 ) . '</a></div></div>'; // phpcs:ignore
		}
		echo '</div><p class="mini-picks__note">' . esc_html__( 'Affiliate links. Specs as advertised by manufacturers.', 'hoverboards-guide' ) . '</p>';
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

class HBG_Widget_Topics extends WP_Widget {
	public function __construct() {
		parent::__construct( 'hbg_topics', __( 'HBG: Topic Hubs', 'hoverboards-guide' ), array( 'description' => __( 'Links to the hoverboard topic hubs (kids, adults, all-terrain…).', 'hoverboards-guide' ) ) );
	}
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Browse by topic', 'hoverboards-guide' );
		echo $args['before_widget']; // phpcs:ignore
		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title']; // phpcs:ignore
		echo '<ul class="topic-links">';
		foreach ( hbg_topic_hubs() as $slug => $hub ) {
			$link = hbg_hub_link( $slug );
			if ( $link['url'] ) {
				echo '<li><a href="' . esc_url( $link['url'] ) . '">' . esc_html( $hub['name'] ) . '</a>' . ( $link['count'] ? ' <span class="count">' . esc_html( number_format_i18n( $link['count'] ) ) . '</span>' : '' ) . '</li>';
			}
		}
		echo '</ul>';
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

function hbg_register_widgets() {
	register_widget( 'HBG_Widget_Popular' );
	register_widget( 'HBG_Widget_Category_Posts' );
	register_widget( 'HBG_Widget_Top_Picks' );
	register_widget( 'HBG_Widget_Topics' );
}
add_action( 'widgets_init', 'hbg_register_widgets' );
