<?php
/**
 * Hoverboards Guide → Products: edit the recommended products from the dashboard.
 * Saved rows are merged over the theme defaults (option `hbg_products_v3`).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hbg_products_admin_menu() {
	add_submenu_page( 'hbg-setup', __( 'Products', 'hoverboards-guide' ), __( 'Products', 'hoverboards-guide' ), 'manage_options', 'hbg-products', 'hbg_products_page' );
}
add_action( 'admin_menu', 'hbg_products_admin_menu' );

function hbg_products_admin_assets( $hook ) {
	if ( false === strpos( $hook, 'hbg-products' ) ) {
		return;
	}
	wp_enqueue_media();
	wp_add_inline_script( 'media-editor', "document.addEventListener('click',function(e){var b=e.target.closest('[data-hbg-media]');if(!b)return;e.preventDefault();var input=document.getElementById(b.getAttribute('data-hbg-media'));var f=wp.media({title:'Product image',multiple:false,library:{type:'image'}});f.on('select',function(){var a=f.state().get('selection').first().toJSON();input.value=a.id;var p=document.getElementById(input.id+'_preview');if(p){p.innerHTML='<img src=\"'+(a.sizes&&a.sizes.thumbnail?a.sizes.thumbnail.url:a.url)+'\" style=\"max-width:80px;height:auto\">';}});f.open();});" );
}
add_action( 'admin_enqueue_scripts', 'hbg_products_admin_assets' );

function hbg_products_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$products = hbg_get_products();
	$saved    = get_option( 'hbg_products_v3', array() );
	$hidden   = array();
	if ( is_array( $saved ) ) {
		foreach ( $saved as $id => $row ) {
			if ( ! empty( $row['hidden'] ) ) {
				$hidden[ $id ] = $row;
			}
		}
	}
	$roles = hbg_product_roles();
	$msg   = isset( $_GET['hbg_msg'] ) ? sanitize_text_field( wp_unslash( $_GET['hbg_msg'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<div class="wrap hbg-admin">
		<h1><?php esc_html_e( 'Recommended products', 'hoverboards-guide' ); ?></h1>
		<?php if ( $msg ) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html( $msg ); ?></p></div><?php endif; ?>
		<p><?php esc_html_e( 'These products power the homepage recommendations, the comparison table, the finder and the [hbg_product] shortcode. Figures are shown as “advertised” until you check them against the current listing and tick Verified. No prices, star ratings or review counts are ever shown.', 'hoverboards-guide' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'hbg_products' ); ?>
			<input type="hidden" name="action" value="hbg_products_save">
			<?php foreach ( $products as $id => $p ) : ?>
				<?php hbg_products_row_form( $id, $p, $roles ); ?>
			<?php endforeach; ?>
			<details class="hbg-prod" style="margin:16px 0;border:1px solid #c3c4c7;background:#fff;padding:12px 16px">
				<summary style="cursor:pointer;font-weight:600"><?php esc_html_e( 'Add a product', 'hoverboards-guide' ); ?></summary>
				<?php hbg_products_row_form( '__new__', array( 'name' => '', 'kind' => 'hoverboard', 'role' => '', 'badge' => '', 'why' => '', 'best_for' => '', 'wheel' => '', 'speed' => '', 'range' => '', 'weight_cap' => '', 'certification' => 'UL 2272', 'bluetooth' => false, 'pros' => array(), 'cons' => array(), 'search' => '', 'review_slug' => '', 'image' => 'cat-hoverboards', 'image_id' => 0, 'verified' => false, 'checked' => '' ), $roles, true ); ?>
			</details>
			<?php if ( $hidden ) : ?>
				<p><?php esc_html_e( 'Hidden products:', 'hoverboards-guide' ); ?> <?php foreach ( $hidden as $hid => $row ) : ?><label style="margin-right:12px"><input type="checkbox" name="unhide[]" value="<?php echo esc_attr( $hid ); ?>"> <?php echo esc_html( isset( $row['name'] ) && $row['name'] ? $row['name'] : $hid ); ?></label><?php endforeach; ?> <small><?php esc_html_e( '(tick to restore)', 'hoverboards-guide' ); ?></small></p>
			<?php endif; ?>
			<p>
				<button class="button button-primary"><?php esc_html_e( 'Save products', 'hoverboards-guide' ); ?></button>
				<button class="button" name="reset" value="1" onclick="return confirm('<?php echo esc_js( __( 'Discard all edits and restore the theme defaults?', 'hoverboards-guide' ) ); ?>')"><?php esc_html_e( 'Restore defaults', 'hoverboards-guide' ); ?></button>
			</p>
		</form>
	</div>
	<?php
}

function hbg_products_row_form( $id, $p, $roles, $is_new = false ) {
	$n = 'p[' . esc_attr( $id ) . ']';
	$f = function ( $key, $label, $type = 'text', $help = '' ) use ( $p, $n, $id ) {
		$val = isset( $p[ $key ] ) ? $p[ $key ] : '';
		if ( is_array( $val ) ) {
			$val = implode( "\n", $val );
		}
		echo '<p><label for="' . esc_attr( $id . '_' . $key ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
		if ( 'textarea' === $type ) {
			echo '<textarea class="large-text" rows="3" id="' . esc_attr( $id . '_' . $key ) . '" name="' . $n . '[' . esc_attr( $key ) . ']">' . esc_textarea( $val ) . '</textarea>'; // phpcs:ignore
		} else {
			echo '<input type="text" class="regular-text" id="' . esc_attr( $id . '_' . $key ) . '" name="' . $n . '[' . esc_attr( $key ) . ']" value="' . esc_attr( $val ) . '">'; // phpcs:ignore
		}
		if ( $help ) {
			echo ' <span class="description">' . esc_html( $help ) . '</span>';
		}
		echo '</p>';
	};
	?>
	<details class="hbg-prod" <?php echo $is_new ? '' : ''; ?> style="margin:12px 0;border:1px solid #c3c4c7;background:#fff;padding:12px 16px">
		<summary style="cursor:pointer;font-weight:600"><?php echo $is_new ? esc_html__( 'New product', 'hoverboards-guide' ) : esc_html( $p['name'] ) . ' <code>' . esc_html( $id ) . '</code>' . ( ! empty( $p['verified'] ) ? ' ✓ ' . esc_html__( 'verified', 'hoverboards-guide' ) : ' — ' . esc_html__( 'advertised figures', 'hoverboards-guide' ) ); ?></summary>
		<div style="display:grid;grid-template-columns:1fr 1fr;gap:0 24px;margin-top:10px">
			<div>
				<?php if ( $is_new ) : ?>
					<p><label><strong><?php esc_html_e( 'ID (letters, numbers, dashes)', 'hoverboards-guide' ); ?></strong><br><input type="text" class="regular-text" name="new_id" placeholder="e.g. gotrax-srx-pro"></p>
				<?php endif; ?>
				<?php $f( 'name', __( 'Product name', 'hoverboards-guide' ) ); ?>
				<p><label><strong><?php esc_html_e( 'Role on the homepage', 'hoverboards-guide' ); ?></strong><br>
					<select name="<?php echo $n; // phpcs:ignore ?>[role]"><option value=""><?php esc_html_e( '— none —', 'hoverboards-guide' ); ?></option><?php foreach ( $roles as $rk => $rl ) : ?><option value="<?php echo esc_attr( $rk ); ?>" <?php selected( isset( $p['role'] ) ? $p['role'] : '', $rk ); ?>><?php echo esc_html( $rl ); ?></option><?php endforeach; ?></select></label></p>
				<?php $f( 'badge', __( 'Badge', 'hoverboards-guide' ), 'text', __( 'e.g. Best for kids', 'hoverboards-guide' ) ); ?>
				<?php $f( 'why', __( 'Why consider it (one sentence, evidence-based)', 'hoverboards-guide' ), 'textarea' ); ?>
				<?php $f( 'best_for', __( 'Best for', 'hoverboards-guide' ) ); ?>
				<?php $f( 'pros', __( 'Advantages (one per line)', 'hoverboards-guide' ), 'textarea' ); ?>
				<?php $f( 'cons', __( 'Limitations (one per line)', 'hoverboards-guide' ), 'textarea' ); ?>
			</div>
			<div>
				<?php $f( 'wheel', __( 'Wheels / tires', 'hoverboards-guide' ) ); ?>
				<?php $f( 'weight_cap', __( 'Max rider weight', 'hoverboards-guide' ) ); ?>
				<?php $f( 'speed', __( 'Top speed (advertised)', 'hoverboards-guide' ) ); ?>
				<?php $f( 'range', __( 'Range (advertised)', 'hoverboards-guide' ) ); ?>
				<?php $f( 'certification', __( 'Safety certification', 'hoverboards-guide' ), 'text', __( 'Only enter UL 2272 if the listing or box shows it for the whole board.', 'hoverboards-guide' ) ); ?>
				<p><label><input type="checkbox" name="<?php echo $n; // phpcs:ignore ?>[bluetooth]" value="1" <?php checked( ! empty( $p['bluetooth'] ) ); ?>> <?php esc_html_e( 'Bluetooth speaker / app', 'hoverboards-guide' ); ?></label></p>
				<?php $f( 'search', __( 'Amazon ASIN (B0…) or search words', 'hoverboards-guide' ), 'text', __( 'The link always carries the site’s Associates tag.', 'hoverboards-guide' ) ); ?>
				<?php $f( 'review_slug', __( 'Review article slug', 'hoverboards-guide' ), 'text', __( 'e.g. hover-1-titan-hoverboard', 'hoverboards-guide' ) ); ?>
				<p><label for="<?php echo esc_attr( $id . '_image_id' ); ?>"><strong><?php esc_html_e( 'Product photo (Media Library ID)', 'hoverboards-guide' ); ?></strong></label><br>
					<input type="text" class="small-text" id="<?php echo esc_attr( $id . '_image_id' ); ?>" name="<?php echo $n; // phpcs:ignore ?>[image_id]" value="<?php echo esc_attr( ! empty( $p['image_id'] ) ? $p['image_id'] : '' ); ?>">
					<button type="button" class="button" data-hbg-media="<?php echo esc_attr( $id . '_image_id' ); ?>"><?php esc_html_e( 'Choose', 'hoverboards-guide' ); ?></button>
					<span id="<?php echo esc_attr( $id . '_image_id_preview' ); ?>"><?php if ( ! empty( $p['image_id'] ) ) { echo wp_get_attachment_image( (int) $p['image_id'], 'thumbnail', false, array( 'style' => 'max-width:80px;height:auto;vertical-align:middle' ) ); } ?></span>
					<span class="description"><?php esc_html_e( 'Only use images you are licensed to use (Amazon Associates tools, your own photos, or a library licence). Leave empty to show a labelled representative photo.', 'hoverboards-guide' ); ?></span></p>
				<p><label><input type="checkbox" name="<?php echo $n; // phpcs:ignore ?>[verified]" value="1" <?php checked( ! empty( $p['verified'] ) ); ?>> <strong><?php esc_html_e( 'Verified against the current listing', 'hoverboards-guide' ); ?></strong></label>
					<input type="text" class="small-text" name="<?php echo $n; // phpcs:ignore ?>[checked]" value="<?php echo esc_attr( ! empty( $p['checked'] ) ? $p['checked'] : '' ); ?>" placeholder="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>"> <span class="description"><?php esc_html_e( 'date checked', 'hoverboards-guide' ); ?></span></p>
				<?php if ( ! $is_new ) : ?>
					<p><label><input type="checkbox" name="<?php echo $n; // phpcs:ignore ?>[hidden]" value="1"> <?php esc_html_e( 'Hide this product', 'hoverboards-guide' ); ?></label></p>
				<?php endif; ?>
			</div>
		</div>
	</details>
	<?php
}

function hbg_products_save() {
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'hbg_products' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'hoverboards-guide' ) );
	}
	if ( ! empty( $_POST['reset'] ) ) {
		delete_option( 'hbg_products_v3' );
		wp_safe_redirect( add_query_arg( array( 'page' => 'hbg-products', 'hbg_msg' => rawurlencode( __( 'Theme defaults restored.', 'hoverboards-guide' ) ) ), admin_url( 'admin.php' ) ) );
		exit;
	}
	$in    = isset( $_POST['p'] ) && is_array( $_POST['p'] ) ? wp_unslash( $_POST['p'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$saved = get_option( 'hbg_products_v3', array() );
	$saved = is_array( $saved ) ? $saved : array();
	$text  = array( 'name', 'badge', 'best_for', 'wheel', 'speed', 'range', 'weight_cap', 'certification', 'search', 'review_slug', 'role', 'checked' );
	foreach ( $in as $id => $row ) {
		if ( '__new__' === $id ) {
			$id = isset( $_POST['new_id'] ) ? sanitize_title( wp_unslash( $_POST['new_id'] ) ) : ''; // phpcs:ignore
			if ( ! $id || empty( $row['name'] ) ) {
				continue;
			}
		}
		$id    = sanitize_title( $id );
		$clean = isset( $saved[ $id ] ) && is_array( $saved[ $id ] ) ? $saved[ $id ] : array();
		foreach ( $text as $k ) {
			$clean[ $k ] = isset( $row[ $k ] ) ? sanitize_text_field( $row[ $k ] ) : '';
		}
		$clean['kind']      = 'hoverboard';
		$clean['why']       = isset( $row['why'] ) ? sanitize_textarea_field( $row['why'] ) : '';
		$clean['pros']      = isset( $row['pros'] ) ? array_values( array_filter( array_map( 'sanitize_text_field', preg_split( '/\r?\n/', $row['pros'] ) ) ) ) : array();
		$clean['cons']      = isset( $row['cons'] ) ? array_values( array_filter( array_map( 'sanitize_text_field', preg_split( '/\r?\n/', $row['cons'] ) ) ) ) : array();
		$clean['bluetooth'] = ! empty( $row['bluetooth'] );
		$clean['verified']  = ! empty( $row['verified'] );
		$clean['hidden']    = ! empty( $row['hidden'] );
		$clean['image_id']  = isset( $row['image_id'] ) ? absint( $row['image_id'] ) : 0;
		$saved[ $id ]       = $clean;
	}
	if ( ! empty( $_POST['unhide'] ) && is_array( $_POST['unhide'] ) ) {
		foreach ( wp_unslash( $_POST['unhide'] ) as $hid ) { // phpcs:ignore
			$hid = sanitize_title( $hid );
			if ( isset( $saved[ $hid ] ) ) {
				$saved[ $hid ]['hidden'] = false;
			}
		}
	}
	update_option( 'hbg_products_v3', $saved, false );
	wp_safe_redirect( add_query_arg( array( 'page' => 'hbg-products', 'hbg_msg' => rawurlencode( __( 'Products saved.', 'hoverboards-guide' ) ) ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_hbg_products_save', 'hbg_products_save' );
