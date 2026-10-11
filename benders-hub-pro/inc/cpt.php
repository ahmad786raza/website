<?php
/**
 * Custom Post Types & Taxonomies.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bdh_register_cpts() {

	/**
	 * Bending equipment / product reviews.
	 */
	register_post_type( 'bdh_product', array(
		'label'         => __( 'Bending Equipment', 'benders-hub' ),
		'labels'        => array(
			'name'               => __( 'Bending Equipment', 'benders-hub' ),
			'singular_name'      => __( 'Equipment', 'benders-hub' ),
			'add_new'            => __( 'Add Equipment', 'benders-hub' ),
			'add_new_item'       => __( 'Add Bending Equipment', 'benders-hub' ),
			'edit_item'          => __( 'Edit Equipment', 'benders-hub' ),
			'view_item'          => __( 'View Equipment', 'benders-hub' ),
			'all_items'          => __( 'All Equipment', 'benders-hub' ),
			'search_items'       => __( 'Search Equipment', 'benders-hub' ),
			'menu_name'          => __( 'Equipment', 'benders-hub' ),
		),
		'public'        => true,
		'has_archive'   => 'equipment',
		'menu_icon'     => 'dashicons-admin-tools',
		'rewrite'       => array( 'slug' => 'equipment', 'with_front' => false ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'  => true,
		'menu_position' => 5,
	) );

	register_taxonomy( 'bdh_equipment_type', 'bdh_product', array(
		'label'         => __( 'Equipment Type', 'benders-hub' ),
		'hierarchical'  => true,
		'public'        => true,
		'show_in_rest'  => true,
		'rewrite'       => array( 'slug' => 'equipment-type' ),
	) );
	register_taxonomy( 'bdh_brand', 'bdh_product', array(
		'label'         => __( 'Brand', 'benders-hub' ),
		'hierarchical'  => false,
		'public'        => true,
		'show_in_rest'  => true,
		'rewrite'       => array( 'slug' => 'brand' ),
	) );
	register_taxonomy( 'bdh_archetype_tax', 'bdh_product', array(
		'label'         => __( 'Fabrication Archetype', 'benders-hub' ),
		'hierarchical'  => false,
		'public'        => true,
		'show_in_rest'  => true,
		'rewrite'       => array( 'slug' => 'archetype' ),
	) );

	/**
	 * Fabrication guides — secondary post type for HowTos.
	 */
	register_post_type( 'bdh_guide', array(
		'label'        => __( 'Fabrication Guides', 'benders-hub' ),
		'labels'       => array(
			'name'          => __( 'Fabrication Guides', 'benders-hub' ),
			'singular_name' => __( 'Guide', 'benders-hub' ),
			'menu_name'     => __( 'Guides', 'benders-hub' ),
		),
		'public'       => true,
		'has_archive'  => 'guides',
		'menu_icon'    => 'dashicons-book-alt',
		'rewrite'      => array( 'slug' => 'guides', 'with_front' => false ),
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'bdh_register_cpts' );

/**
 * Equipment meta box.
 */
function bdh_product_meta_box() {
	add_meta_box(
		'bdh_product_meta',
		__( 'Equipment Specifications', 'benders-hub' ),
		'bdh_product_meta_callback',
		'bdh_product',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'bdh_product_meta_box' );

function bdh_product_meta_callback( $post ) {
	wp_nonce_field( 'bdh_product_meta_save', 'bdh_product_meta_nonce' );
	$fields = bdh_product_meta_fields();
	echo '<div class="bdh-meta-grid">';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<p class="bdh-meta-field">';
		echo '<label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br>';
		if ( $field['type'] === 'textarea' ) {
			echo '<textarea name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" rows="3" style="width:100%;">' . esc_textarea( $value ) . '</textarea>';
		} elseif ( $field['type'] === 'select' ) {
			echo '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '">';
			foreach ( $field['options'] as $opt_val => $opt_label ) {
				printf( '<option value="%s"%s>%s</option>', esc_attr( $opt_val ), selected( $value, $opt_val, false ), esc_html( $opt_label ) );
			}
			echo '</select>';
		} elseif ( $field['type'] === 'number' ) {
			echo '<input type="number" step="0.1" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%;">';
		} else {
			echo '<input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%;">';
		}
		if ( ! empty( $field['hint'] ) ) {
			echo '<small style="color:#666;">' . esc_html( $field['hint'] ) . '</small>';
		}
		echo '</p>';
	}
	echo '</div>';
}

function bdh_product_meta_fields() {
	return array(
		'_bdh_evaluation'     => array( 'label' => __( 'Evaluation type', 'benders-hub' ), 'type' => 'select', 'options' => array( 'research' => __( 'Research-based (specs, manuals, owner reports)', 'benders-hub' ), 'hands-on' => __( 'Hands-on tested by us — describe the test in the article', 'benders-hub' ) ), 'hint' => __( 'Only choose hands-on if you actually bent tube with this machine.', 'benders-hub' ) ),
		'_bdh_amazon_url'     => array( 'label' => __( 'Amazon product URL', 'benders-hub' ), 'type' => 'text', 'hint' => __( 'Paste the product page link. Your tracking tag is added automatically if missing; an existing tag is never changed.', 'benders-hub' ) ),
		'_bdh_amazon_keyword' => array( 'label' => __( 'Amazon search keyword (fallback)', 'benders-hub' ), 'type' => 'text', 'hint' => __( 'Used only when no product URL is set.', 'benders-hub' ) ),
		'_bdh_spec_source'    => array( 'label' => __( 'Specification source URL', 'benders-hub' ), 'type' => 'text', 'hint' => __( 'Manufacturer page or manual the specs came from.', 'benders-hub' ) ),
		'_bdh_capacity'       => array( 'label' => __( 'Bending capacity', 'benders-hub' ),    'type' => 'text', 'hint' => __( 'As published, e.g. 2" OD × .120" wall mild steel', 'benders-hub' ) ),
		'_bdh_tonnage'        => array( 'label' => __( 'Drive / power', 'benders-hub' ),     'type' => 'text' ),
		'_bdh_tubing_compat'  => array( 'label' => __( 'Materials', 'benders-hub' ),'type' => 'text', 'hint' => __( 'e.g. mild steel, 4130, stainless, aluminium', 'benders-hub' ) ),
		'_bdh_use_case'       => array( 'label' => __( 'Main application', 'benders-hub' ),    'type' => 'text' ),
		'_bdh_compat'         => array( 'label' => __( 'Compatibility notes', 'benders-hub' ), 'type' => 'textarea', 'hint' => __( 'Dies, mounts, hydraulic kits, power supply.', 'benders-hub' ) ),
		'_bdh_best_for'       => array( 'label' => __( 'Who should consider it', 'benders-hub' ), 'type' => 'textarea' ),
		'_bdh_alternative_if' => array( 'label' => __( 'Who should look at an alternative', 'benders-hub' ), 'type' => 'textarea' ),
		'_bdh_pros'           => array( 'label' => __( 'Advantages (one per line)', 'benders-hub' ),'type' => 'textarea' ),
		'_bdh_cons'           => array( 'label' => __( 'Limitations (one per line)', 'benders-hub' ),'type' => 'textarea' ),
		'_bdh_workshop_fit'   => array( 'label' => __( 'Workshop suitability', 'benders-hub' ),'type' => 'select', 'options' => array( '' => '—', 'garage' => __( 'Garage / home shop', 'benders-hub' ), 'small' => __( 'Small production', 'benders-hub' ), 'pro' => __( 'Professional shop', 'benders-hub' ), 'industrial' => __( 'Industrial / volume', 'benders-hub' ) ) ),
		'_bdh_beginner'       => array( 'label' => __( 'Beginner friendly?', 'benders-hub' ),  'type' => 'select', 'options' => array( '' => '—', 'yes' => __( 'Yes', 'benders-hub' ), 'partial' => __( 'With practice', 'benders-hub' ), 'no' => __( 'Experienced users', 'benders-hub' ) ) ),
		'_bdh_accessories'    => array( 'label' => __( 'Related accessories (one per line)', 'benders-hub' ), 'type' => 'textarea' ),
	);
}

function bdh_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['bdh_product_meta_nonce'] ) || ! wp_verify_nonce( $_POST['bdh_product_meta_nonce'], 'bdh_product_meta_save' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	$fields = bdh_product_meta_fields();
	foreach ( $fields as $key => $field ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = wp_unslash( $_POST[ $key ] );
			if ( $field['type'] === 'textarea' ) {
				$value = sanitize_textarea_field( $value );
			} elseif ( $field['type'] === 'number' ) {
				$value = floatval( $value );
			} elseif ( in_array( $key, array( '_bdh_amazon_url', '_bdh_spec_source' ), true ) ) {
				$value = esc_url_raw( $value );
			} else {
				$value = sanitize_text_field( $value );
			}
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_bdh_product', 'bdh_save_product_meta' );
