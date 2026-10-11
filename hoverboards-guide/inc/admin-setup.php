<?php
/**
 * Hoverboards Guide → Setup: status checks and one-click, reversible site changes
 * (topic categories, primary menu, trust pages). Nothing runs automatically.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hbg_admin_menu() {
	add_menu_page( __( 'Hoverboards Guide', 'hoverboards-guide' ), __( 'Hoverboards Guide', 'hoverboards-guide' ), 'manage_options', 'hbg-setup', 'hbg_setup_page', 'dashicons-admin-site-alt3', 58 );
	add_submenu_page( 'hbg-setup', __( 'Setup', 'hoverboards-guide' ), __( 'Setup', 'hoverboards-guide' ), 'manage_options', 'hbg-setup', 'hbg_setup_page' );
}
add_action( 'admin_menu', 'hbg_admin_menu' );

/** Resolve the hub slug lists into post IDs (published posts only). */
function hbg_setup_hub_posts( $hub ) {
	$ids = array();
	foreach ( $hub['posts'] as $slug ) {
		$p = hbg_post_by_slug( $slug );
		if ( $p && 'post' === $p->post_type ) {
			$ids[] = $p->ID;
		}
	}
	return $ids;
}

function hbg_setup_status() {
	$status = array();
	$status['theme']     = HBG_VERSION;
	$status['seo']       = hbg_seo_plugin_active();
	$status['sitekit']   = defined( 'GOOGLESITEKIT_VERSION' );
	$status['menu']      = has_nav_menu( 'primary' );
	$status['permalink'] = get_option( 'permalink_structure' );
	$status['pages']     = array();
	foreach ( array( 'about', 'contact', 'privacy-policy', 'terms', 'affiliate-disclosure', 'editorial-policy' ) as $slug ) {
		$page = get_page_by_path( $slug );
		$status['pages'][ $slug ] = $page ? $page->post_status : '';
	}
	$status['hubs'] = array();
	foreach ( hbg_topic_hubs() as $slug => $hub ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		$status['hubs'][ $slug ] = array( 'exists' => (bool) $term, 'count' => $term ? (int) $term->count : 0, 'available' => count( hbg_setup_hub_posts( $hub ) ) );
	}
	$status['key_posts'] = array();
	foreach ( hbg_key_posts() as $key => $slug ) {
		$status['key_posts'][ $key ] = (bool) hbg_post_by_slug( $slug );
	}
	return $status;
}

function hbg_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s    = hbg_setup_status();
	$log  = get_option( 'hbg_setup_log', array() );
	$msg  = isset( $_GET['hbg_msg'] ) ? sanitize_text_field( wp_unslash( $_GET['hbg_msg'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$hubs = hbg_topic_hubs();
	?>
	<div class="wrap hbg-admin">
		<h1><?php esc_html_e( 'Hoverboards Guide — Setup', 'hoverboards-guide' ); ?></h1>
		<?php if ( $msg ) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html( $msg ); ?></p></div><?php endif; ?>
		<p><?php esc_html_e( 'Every action below is optional, runs only when you click it, and can be undone from this page. Take a backup first.', 'hoverboards-guide' ); ?></p>

		<h2><?php esc_html_e( '1. Status', 'hoverboards-guide' ); ?></h2>
		<table class="widefat striped" style="max-width:900px">
			<tbody>
				<tr><th><?php esc_html_e( 'Theme version', 'hoverboards-guide' ); ?></th><td><?php echo esc_html( $s['theme'] ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Permalinks', 'hoverboards-guide' ); ?></th><td><?php echo $s['permalink'] ? esc_html( $s['permalink'] ) : esc_html__( 'Plain (set Settings → Permalinks to “Post name”)', 'hoverboards-guide' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'SEO plugin', 'hoverboards-guide' ); ?></th><td><?php echo $s['seo'] ? esc_html__( 'Active — the theme leaves meta tags and schema to it.', 'hoverboards-guide' ) : esc_html__( 'None — the theme prints meta descriptions, Open Graph tags and JSON-LD.', 'hoverboards-guide' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Site Kit (Analytics/AdSense)', 'hoverboards-guide' ); ?></th><td><?php echo $s['sitekit'] ? esc_html__( 'Active — affiliate-click events are sent to GA4 via gtag.', 'hoverboards-guide' ) : esc_html__( 'Not detected.', 'hoverboards-guide' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Primary menu', 'hoverboards-guide' ); ?></th><td><?php echo $s['menu'] ? esc_html__( 'A WordPress menu is assigned.', 'hoverboards-guide' ) : esc_html__( 'No menu assigned — the theme shows its built-in menu (see step 3).', 'hoverboards-guide' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Key articles found', 'hoverboards-guide' ); ?></th><td><?php echo esc_html( count( array_filter( $s['key_posts'] ) ) . ' / ' . count( $s['key_posts'] ) ); ?><?php $missing = array_keys( array_filter( $s['key_posts'], function ( $v ) { return ! $v; } ) ); if ( $missing ) { echo ' — ' . esc_html__( 'missing:', 'hoverboards-guide' ) . ' ' . esc_html( implode( ', ', $missing ) ); } ?></td></tr>
				<tr><th><?php esc_html_e( 'Pages', 'hoverboards-guide' ); ?></th><td><?php foreach ( $s['pages'] as $slug => $st ) { echo '<code>' . esc_html( $slug ) . '</code>: ' . esc_html( $st ? $st : __( 'missing', 'hoverboards-guide' ) ) . '<br>'; } ?></td></tr>
			</tbody>
		</table>

		<h2><?php esc_html_e( '2. Topic categories', 'hoverboards-guide' ); ?></h2>
		<p><?php esc_html_e( 'Creates child categories of “Hoverboards” for the topic hubs (kids, adults, all-terrain, budget, Bluetooth, reviews, safety, troubleshooting) and adds the matching existing articles to them. Articles keep all their current categories, slugs and content; nothing is removed.', 'hoverboards-guide' ); ?></p>
		<table class="widefat striped" style="max-width:900px">
			<thead><tr><th><?php esc_html_e( 'Hub', 'hoverboards-guide' ); ?></th><th><?php esc_html_e( 'Category exists', 'hoverboards-guide' ); ?></th><th><?php esc_html_e( 'Articles in category', 'hoverboards-guide' ); ?></th><th><?php esc_html_e( 'Articles that would be assigned', 'hoverboards-guide' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( $hubs as $slug => $hub ) : ?>
				<tr><td><?php echo esc_html( $hub['name'] ); ?> <code><?php echo esc_html( $slug ); ?></code></td><td><?php echo $s['hubs'][ $slug ]['exists'] ? '✓' : '—'; ?></td><td><?php echo esc_html( $s['hubs'][ $slug ]['count'] ); ?></td><td><?php echo esc_html( $s['hubs'][ $slug ]['available'] ); ?></td></tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:10px">
			<?php wp_nonce_field( 'hbg_setup' ); ?>
			<input type="hidden" name="action" value="hbg_setup_action">
			<button class="button button-primary" name="do" value="hubs"><?php esc_html_e( 'Create topic categories and assign articles', 'hoverboards-guide' ); ?></button>
			<?php if ( ! empty( $log['hubs'] ) ) : ?>
				<button class="button" name="do" value="undo_hubs" onclick="return confirm('<?php echo esc_js( __( 'Remove the topic categories created by this tool from those articles?', 'hoverboards-guide' ) ); ?>')"><?php esc_html_e( 'Undo (remove the assignments made by this tool)', 'hoverboards-guide' ); ?></button>
			<?php endif; ?>
		</form>

		<h2><?php esc_html_e( '3. Primary menu', 'hoverboards-guide' ); ?></h2>
		<p><?php esc_html_e( 'Creates a WordPress menu named “Hoverboards Guide Primary” (Home, Best Hoverboards, Hoverboards for Kids, Hoverboards for Adults, All-Terrain Hoverboards, Reviews, Buying Guides, Safety & How-To, More rides) and assigns it to the header. Edit it afterwards under Appearance → Menus. Run step 2 first so items link to categories rather than single articles.', 'hoverboards-guide' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'hbg_setup' ); ?>
			<input type="hidden" name="action" value="hbg_setup_action">
			<button class="button button-primary" name="do" value="menu"><?php esc_html_e( 'Create and assign the primary menu', 'hoverboards-guide' ); ?></button>
			<?php if ( ! empty( $log['menu'] ) ) : ?>
				<button class="button" name="do" value="undo_menu"><?php esc_html_e( 'Undo (restore the previous menu assignment)', 'hoverboards-guide' ); ?></button>
			<?php endif; ?>
		</form>

		<h2><?php esc_html_e( '4. Trust pages', 'hoverboards-guide' ); ?></h2>
		<p><?php esc_html_e( 'Creates “Affiliate Disclosure” and “Editorial Policy” as drafts with plain, accurate wording. Review and publish them; the footer and article boxes link to them automatically once published.', 'hoverboards-guide' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'hbg_setup' ); ?>
			<input type="hidden" name="action" value="hbg_setup_action">
			<button class="button button-primary" name="do" value="pages"><?php esc_html_e( 'Create draft trust pages', 'hoverboards-guide' ); ?></button>
		</form>

		<h2><?php esc_html_e( '5. Finishing touches (manual)', 'hoverboards-guide' ); ?></h2>
		<ul style="list-style:disc;padding-left:20px;max-width:900px">
			<li><?php esc_html_e( 'Customize → Hoverboards Guide: hero copy, topic photos, affiliate text, newsletter service URL, editorial transparency text.', 'hoverboards-guide' ); ?></li>
			<li><?php esc_html_e( 'Hoverboards Guide → Products: check each recommended model against its current Amazon or manufacturer listing, correct the figures and tick “verified”.', 'hoverboards-guide' ); ?></li>
			<li><?php esc_html_e( 'Users → Profile: set a display name and short description so articles show a real author instead of “admin”.', 'hoverboards-guide' ); ?></li>
			<li><?php esc_html_e( 'If you use a caching plugin or Cloudflare, purge the cache after activating the theme.', 'hoverboards-guide' ); ?></li>
		</ul>
	</div>
	<?php
}

function hbg_setup_action() {
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'hbg_setup' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'hoverboards-guide' ) );
	}
	$do  = isset( $_POST['do'] ) ? sanitize_key( $_POST['do'] ) : '';
	$log = get_option( 'hbg_setup_log', array() );
	$msg = '';

	if ( 'hubs' === $do ) {
		$parent = get_term_by( 'slug', 'hoverboards', 'category' );
		$parent_id = $parent ? (int) $parent->term_id : 0;
		$created = array();
		$assigned = array();
		foreach ( hbg_topic_hubs() as $slug => $hub ) {
			$term = get_term_by( 'slug', $slug, 'category' );
			if ( ! $term ) {
				$res = wp_insert_term( $hub['name'], 'category', array( 'slug' => $slug, 'parent' => $parent_id, 'description' => $hub['desc'] ) );
				if ( is_wp_error( $res ) ) {
					continue;
				}
				$term_id   = (int) $res['term_id'];
				$created[] = $term_id;
			} else {
				$term_id = (int) $term->term_id;
				if ( ! $term->description ) {
					wp_update_term( $term_id, 'category', array( 'description' => $hub['desc'] ) );
				}
			}
			foreach ( hbg_setup_hub_posts( $hub ) as $pid ) {
				if ( ! has_category( $term_id, $pid ) ) {
					wp_set_post_categories( $pid, array( $term_id ), true );
					$assigned[] = array( $pid, $term_id );
				}
			}
		}
		// Merge with earlier runs so Undo always covers everything this tool did.
		$prev        = ! empty( $log['hubs'] ) && is_array( $log['hubs'] ) ? $log['hubs'] : array( 'created' => array(), 'assigned' => array() );
		$log['hubs'] = array( 'created' => array_values( array_unique( array_merge( (array) $prev['created'], $created ) ) ), 'assigned' => array_merge( (array) $prev['assigned'], $assigned ), 'time' => time() );
		update_option( 'hbg_setup_log', $log, false );
		$msg = sprintf( __( 'Done: %1$d categories created, %2$d article assignments added.', 'hoverboards-guide' ), count( $created ), count( $assigned ) );
	} elseif ( 'undo_hubs' === $do && ! empty( $log['hubs'] ) ) {
		foreach ( $log['hubs']['assigned'] as $pair ) {
			wp_remove_object_terms( (int) $pair[0], (int) $pair[1], 'category' );
		}
		foreach ( $log['hubs']['created'] as $tid ) {
			$t = get_term( $tid, 'category' );
			if ( $t && ! is_wp_error( $t ) && 0 === (int) $t->count ) {
				wp_delete_term( $tid, 'category' );
			}
		}
		unset( $log['hubs'] );
		update_option( 'hbg_setup_log', $log, false );
		$msg = __( 'Topic category assignments removed; empty categories deleted.', 'hoverboards-guide' );
	} elseif ( 'menu' === $do ) {
		$name    = 'Hoverboards Guide Primary';
		$menu_id = wp_create_nav_menu( $name . ' ' . gmdate( 'Y-m-d H:i' ) );
		if ( ! is_wp_error( $menu_id ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => __( 'Home', 'hoverboards-guide' ), 'menu-item-url' => home_url( '/' ), 'menu-item-status' => 'publish' ) );
			foreach ( hbg_menu_blueprint() as $item ) {
				$args = array( 'menu-item-title' => ! empty( $item['short'] ) ? $item['short'] : $item['label'], 'menu-item-url' => $item['url'], 'menu-item-status' => 'publish' );
				$pid  = wp_update_nav_menu_item( $menu_id, 0, $args );
				if ( ! empty( $item['children'] ) && ! is_wp_error( $pid ) ) {
					foreach ( $item['children'] as $c ) {
						wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $c['label'], 'menu-item-url' => $c['url'], 'menu-item-status' => 'publish', 'menu-item-parent-id' => $pid ) );
					}
				}
			}
			$locations = get_theme_mod( 'nav_menu_locations', array() );
			// Keep the original assignment from the first run so Undo restores what you had before this tool.
			$previous    = ( ! empty( $log['menu'] ) && isset( $log['menu']['previous'] ) ) ? (int) $log['menu']['previous'] : ( isset( $locations['primary'] ) ? (int) $locations['primary'] : 0 );
			$log['menu'] = array( 'menu_id' => $menu_id, 'previous' => $previous, 'time' => time() );
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
			update_option( 'hbg_setup_log', $log, false );
			$msg = __( 'Primary menu created and assigned. Edit it under Appearance → Menus.', 'hoverboards-guide' );
		}
	} elseif ( 'undo_menu' === $do && ! empty( $log['menu'] ) ) {
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = (int) $log['menu']['previous'];
		set_theme_mod( 'nav_menu_locations', $locations );
		unset( $log['menu'] );
		update_option( 'hbg_setup_log', $log, false );
		$msg = __( 'Previous menu assignment restored (the created menu still exists under Appearance → Menus).', 'hoverboards-guide' );
	} elseif ( 'pages' === $do ) {
		$pages = array(
			'affiliate-disclosure' => array( __( 'Affiliate Disclosure', 'hoverboards-guide' ), hbg_setup_disclosure_content() ),
			'editorial-policy'     => array( __( 'Editorial Policy', 'hoverboards-guide' ), hbg_setup_policy_content() ),
		);
		$n = 0;
		foreach ( $pages as $slug => $def ) {
			if ( get_page_by_path( $slug ) ) {
				continue;
			}
			wp_insert_post( array( 'post_title' => $def[0], 'post_name' => $slug, 'post_content' => $def[1], 'post_status' => 'draft', 'post_type' => 'page' ) );
			$n++;
		}
		$msg = sprintf( __( '%d draft page(s) created. Review them under Pages → Drafts, then publish.', 'hoverboards-guide' ), $n );
	}

	wp_safe_redirect( add_query_arg( array( 'page' => 'hbg-setup', 'hbg_msg' => rawurlencode( $msg ) ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_hbg_setup_action', 'hbg_setup_action' );

function hbg_setup_disclosure_content() {
	$site = get_bloginfo( 'name' );
	return "<!-- wp:paragraph --><p>{$site} is a participant in the Amazon Services LLC Associates Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to Amazon.com. As an Amazon Associate, we earn from qualifying purchases.</p><!-- /wp:paragraph -->"
		. "<!-- wp:paragraph --><p>Links labelled “Check price on Amazon” or marked as Amazon links are affiliate links. If you buy through one, Amazon pays us a small commission. The price you pay is the same.</p><!-- /wp:paragraph -->"
		. "<!-- wp:paragraph --><p>Commissions do not decide which products we recommend. We do not accept payment for placement in our guides, and we do not display prices, ratings or availability from Amazon on this site; those are shown on Amazon itself and change frequently.</p><!-- /wp:paragraph -->"
		. "<!-- wp:paragraph --><p>Amazon and the Amazon logo are trademarks of Amazon.com, Inc. or its affiliates. {$site} is not endorsed by or affiliated with Amazon beyond the Associates Program.</p><!-- /wp:paragraph -->";
}

function hbg_setup_policy_content() {
	$site = get_bloginfo( 'name' );
	return "<!-- wp:heading --><h2>What we cover</h2><!-- /wp:heading -->"
		. "<!-- wp:paragraph --><p>{$site} publishes buying guides, reviews, comparisons, safety and maintenance articles about hoverboards and other small electric rides.</p><!-- /wp:paragraph -->"
		. "<!-- wp:heading --><h2>How our recommendations are made</h2><!-- /wp:heading -->"
		. "<!-- wp:paragraph --><p>Our guides are desk research. For each product we compare the manufacturer’s published specifications (wheel size, rider weight limit, advertised speed and range, battery and charger details and safety certification), look for published recalls and safety notices, and read owner feedback across retailers. Where figures from different listings disagree, we say so. Advertised speed and range are manufacturer claims; real-world results depend on rider weight, terrain, temperature and tire pressure and are usually lower.</p><!-- /wp:paragraph -->"
		. "<!-- wp:paragraph --><p>We do not run a test laboratory. Unless an article states that we rode a specific board, our reviews are not hands-on tests.</p><!-- /wp:paragraph -->"
		. "<!-- wp:heading --><h2>Safety</h2><!-- /wp:heading -->"
		. "<!-- wp:paragraph --><p>We only recommend hoverboards sold as certified to UL 2272, which covers the electrical system of the complete board. A UL-listed battery or charger alone does not make a board compliant. Certification reduces risk but does not remove it: follow the manufacturer’s age and weight guidance, use the supplied charger, charge on a hard surface, never charge unattended overnight, and wear a helmet.</p><!-- /wp:paragraph -->"
		. "<!-- wp:heading --><h2>Updates and corrections</h2><!-- /wp:heading -->"
		. "<!-- wp:paragraph --><p>Each article shows its last-updated date. If you find an error, use the contact page and we will check the source and correct it.</p><!-- /wp:paragraph -->"
		. "<!-- wp:heading --><h2>Funding</h2><!-- /wp:heading -->"
		. "<!-- wp:paragraph --><p>The site is funded by affiliate commissions and advertising. See the affiliate disclosure for details. Commissions do not influence which products we recommend.</p><!-- /wp:paragraph -->";
}
