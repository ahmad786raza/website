<?php
/**
 * v2 upgrade tasks — Benders Hub → Upgrade to v2.
 *
 * One-click, reversible fixes for content that v1's Repair Tool created:
 *  - Trust pages whose body is still v1's one-line placeholder (some of which
 *    claimed workshop testing) are replaced with honest, fuller drafts. Pages
 *    you have edited are never touched; WordPress keeps a revision either way.
 *  - Fictional "equipment" entries can be moved to Draft (and restored). Their
 *    old URLs then redirect to the equipment archive.
 *  - A magazine menu built from your real categories can be assigned to the
 *    primary location, with a button to switch back to the previous menu.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The exact one-line bodies v1 created (used to detect untouched pages).
 */
function bdh_v1_placeholder_bodies() {
	return array(
		'about'                => 'Premium fabrication engineering ecosystem. About this site, our team, and how we test.',
		'editorial-standards'  => 'Our editorial standards for reviewing bending equipment and fabrication tools.',
		'methodology'          => 'How we workshop-test every tube bender, die, and accessory before publishing.',
		'affiliate-disclosure' => 'As an Amazon Associate, Benders Hub earns from qualifying purchases. We disclose every affiliate relationship.',
		'safety'               => 'Workshop safety, welding integration, and OSHA-aligned guidance for bending.',
		'glossary'             => 'Definitions of bending terminology — CLR, K-factor, springback, mandrel, and more.',
	);
}

/**
 * Honest replacement drafts (block markup). Owner notes are HTML comments, invisible to readers.
 */
function bdh_trust_page_content( $slug ) {
	$p  = function ( $t ) { return "<!-- wp:paragraph -->\n<p>" . $t . "</p>\n<!-- /wp:paragraph -->\n\n"; };
	$h  = function ( $t ) { return "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $t . "</h2>\n<!-- /wp:heading -->\n\n"; };
	$ul = function ( $items ) { return "<!-- wp:list -->\n<ul class=\"wp-block-list\">" . implode( '', array_map( function ( $i ) { return "<!-- wp:list-item -->\n<li>" . $i . "</li>\n<!-- /wp:list-item -->"; }, $items ) ) . "</ul>\n<!-- /wp:list -->\n\n"; };
	$link = function ( $s, $t ) { $u = bdh_page_url( $s ); return $u ? '<a href="' . esc_url( $u ) . '">' . $t . '</a>' : $t; };

	switch ( $slug ) {
		case 'about':
			return "<!-- Owner: add who writes Benders Hub and the fabrication experience behind it. Only include credentials you can evidence. -->\n"
				. $p( 'Benders Hub is an independent website about tube bending and metal fabrication. We publish buying guides, technical explainers and free planning tools for home builders, motorsport and off-road fabricators, and professional shops.' )
				. $h( 'What you will find here' )
				. $ul( array( 'Guides that explain how to choose a bender, dies and support tooling for a specific job.', 'Technical explainers on centerline radius, springback, wall factor and other bending fundamentals.', 'Free calculators and a bend visualizer that run entirely in your browser.', 'Equipment comparisons built from manufacturers\' published specifications, with sources linked.' ) )
				. $h( 'How we work' )
				. $p( 'Read our ' . $link( 'editorial-standards', 'editorial standards' ) . ', ' . $link( 'methodology', 'how we evaluate equipment' ) . ' and ' . $link( 'affiliate-disclosure', 'affiliate disclosure' ) . '.' );
		case 'editorial-standards':
			return $p( 'These standards apply to every article, guide and tool on Benders Hub.' )
				. $h( 'Sources' )
				. $ul( array( 'Specifications come from manufacturers\' own pages, manuals and capacity charts. We link the source where we use it.', 'Where a manufacturer\'s documents disagree, we say so rather than choosing a number.', 'Technical explanations follow manufacturer documentation and established trade references.' ) )
				. $h( 'Safety-critical topics' )
				. $p( 'Roll cages and other safety structures are governed by sanctioning-body rulebooks that change between editions. We point you to the current rulebook instead of restating its tables, and you should always confirm requirements with your sanctioning body.' )
				. $h( 'Independence' )
				. $p( 'Some links earn us a commission. Commission rates do not decide what we recommend, and advertisers cannot buy coverage or edit our content.' )
				. $h( 'Updates and corrections' )
				. $p( 'Articles show the date they were published and, when changed meaningfully, the date they were updated. If you spot an error, please ' . $link( 'contact', 'contact us' ) . ' and we will correct it.' );
		case 'methodology':
			return "<!-- Owner: if you test equipment yourself, describe the equipment, procedure and measurements here and mark those reviews as \"Hands-on tested\" in the equipment editor. -->\n"
				. $p( 'This page explains how we decide what to recommend.' )
				. $h( 'Research-based evaluation' )
				. $p( 'Unless a review says otherwise, recommendations are research-based. We compare:' )
				. $ul( array( 'Published capacity: tube OD, wall thickness and material the machine is rated for.', 'Die and tooling availability for common tube sizes and centerline radii.', 'Drive options and upgrade paths (manual, hydraulic, air or electric).', 'Documentation, safety features and manufacturer support.', 'Fit for the job: what the equipment is good at and where it falls short.' ) )
				. $h( 'Hands-on testing' )
				. $p( 'We only describe a product as tested when we have bent tube with it ourselves. Those reviews are labelled "Hands-on tested" and describe the procedure and results. We do not publish scores or ratings for products we have not tested.' )
				. $h( 'What we do not do' )
				. $ul( array( 'Invent test results, ratings, prices or availability.', 'Rank products by commission rate.', 'Accept payment for placement.' ) );
		case 'affiliate-disclosure':
			return $p( '<strong>' . esc_html( BDH_AMAZON_DISCLOSURE ) . '</strong>' )
				. $p( 'Benders Hub is a participant in the Amazon Services LLC Associates Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to Amazon.com.' )
				. $p( 'When you buy through an Amazon link on this site we may earn a commission at no extra cost to you. Affiliate links are marked, and the disclosure is shown next to product recommendations.' )
				. $p( 'Commissions never decide what we recommend. See ' . $link( 'methodology', 'how we evaluate equipment' ) . '.' )
				. $p( 'Amazon and the Amazon logo are trademarks of Amazon.com, Inc. or its affiliates. Benders Hub is not affiliated with or endorsed by Amazon.' );
		case 'safety':
			return $p( 'Tube bending stores a lot of force in the machine and the workpiece. These general points do not replace your equipment manufacturer\'s instructions.' )
				. $ul( array( 'Read the manual for your bender, dies and hydraulic components before use, and follow its setup and capacity limits.', 'Anchor floor-standing benders as the manufacturer specifies.', 'Keep hands and clothing clear of the die, clamp and pressure die while the bender is moving — these are pinch points.', 'Never exceed the rated pressure of hydraulic pumps, cylinders and hoses; inspect hoses and fittings before use.', 'Wear eye protection and gloves; add hearing protection and a respirator when cutting, grinding or welding.', 'Support long tubes so they cannot swing or drop when released.' ) )
				. $h( 'Roll cages and safety structures' )
				. $p( 'Tube size, wall thickness, material, bend radius and welding method for roll cages are set by your sanctioning body. Use the current rulebook and have your cage inspected as required.' );
		case 'glossary':
			$terms = array(
				'Centerline radius (CLR)' => 'Radius of a bend measured from the bend centre to the tube centreline. Dies are specified by tube OD and CLR.',
				'D of bend'               => 'CLR divided by tube OD. Lower values are tighter, harder bends.',
				'Wall factor'             => 'Tube OD divided by wall thickness. Higher values mean a thinner wall relative to diameter.',
				'Springback'              => 'The angle a bent tube relaxes after the bender releases it. Measured with a test bend and compensated by over-bending.',
				'Ovality'                 => 'How out-of-round the tube becomes in the bend: (maximum OD − minimum OD) ÷ nominal OD.',
				'Tangent point'           => 'Where a straight section of tube meets the start or end of the bend.',
				'Tangent length'          => 'CLR × tan(angle ÷ 2): the distance from a tangent point to where the straight centrelines would intersect.',
				'Arc length'              => 'Length of tube along the centreline inside the bend: CLR × angle in radians.',
				'Rotary-draw bending'     => 'The tube is clamped to a rotating bend die and drawn around it, giving a consistent radius.',
				'Mandrel'                 => 'A support inserted inside the tube at the bend to stop it collapsing or wrinkling.',
				'Wiper die'               => 'A tool that supports the inside of the bend just ahead of the bend die to prevent wrinkles.',
				'Pressure die'            => 'The die that holds the tube against the bend die as it is drawn around.',
				'Ram (press) bending'     => 'A ram pushes the tube between two supports. Inexpensive, but thin-wall tube tends to flatten.',
				'Roll bending'            => 'Forming large radii by passing the tube through three rolls several times.',
				'DOM'                     => 'Drawn Over Mandrel: welded steel tube that has been cold-drawn for consistent size and wall.',
				'ERW'                     => 'Electric Resistance Welded tube, as welded, without the cold-drawing step of DOM.',
				'4130 (chromoly)'         => 'A chromium-molybdenum alloy steel used for lightweight chassis and cages.',
				'NPS and schedule'        => 'Pipe sizing: nominal pipe size and wall schedule. Actual OD differs from the nominal size (1-1/2" NPS is 1.900" OD).',
				'Notching (coping)'       => 'Shaping a tube end so it fits closely against another tube before welding.',
			);
			$out = $p( 'Short definitions of the terms used across Benders Hub.' ) . "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
			foreach ( $terms as $t => $d ) { $out .= "<!-- wp:list-item -->\n<li><strong>" . esc_html( $t ) . '</strong> — ' . esc_html( $d ) . "</li>\n<!-- /wp:list-item -->"; }
			return $out . "</ul>\n<!-- /wp:list -->\n";
	}
	return '';
}

/* ------------------------------------------------------------------ */

function bdh_upgrade_menu() {
	add_submenu_page( 'bdh-dashboard', __( 'Upgrade to v2', 'benders-hub' ), __( 'Upgrade to v2', 'benders-hub' ), 'manage_options', 'bdh-upgrade', 'bdh_upgrade_render' );
}
add_action( 'admin_menu', 'bdh_upgrade_menu', 11 );

/**
 * Build/assign a menu from the categories that actually have posts.
 */
function bdh_build_magazine_menu() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	update_option( 'bdh_prev_primary_menu', isset( $locations['primary'] ) ? (int) $locations['primary'] : 0, false );
	$menu = wp_get_nav_menu_object( 'Magazine Menu' );
	$mid  = $menu ? $menu->term_id : wp_create_nav_menu( 'Magazine Menu' );
	if ( is_wp_error( $mid ) ) { return $mid; }
	if ( ! $menu ) {
		$cats = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 6, 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
		foreach ( $cats as $c ) {
			wp_update_nav_menu_item( $mid, 0, array( 'menu-item-title' => $c->name, 'menu-item-object' => 'category', 'menu-item-object-id' => $c->term_id, 'menu-item-type' => 'taxonomy', 'menu-item-status' => 'publish' ) );
		}
		$tools = wp_update_nav_menu_item( $mid, 0, array( 'menu-item-title' => __( 'Calculators & Tools', 'benders-hub' ), 'menu-item-url' => bdh_tool_url( 'calculator' ), 'menu-item-status' => 'publish' ) );
		foreach ( array( 'quiz', 'calculator', 'visualizer', 'comparison' ) as $t ) {
			$tool = bdh_tools()[ $t ];
			wp_update_nav_menu_item( $mid, 0, array( 'menu-item-title' => $tool['label'], 'menu-item-url' => bdh_tool_url( $t ), 'menu-item-parent-id' => $tools, 'menu-item-status' => 'publish' ) );
		}
		wp_update_nav_menu_item( $mid, 0, array( 'menu-item-title' => __( 'Comparisons', 'benders-hub' ), 'menu-item-url' => bdh_tool_url( 'comparison' ), 'menu-item-status' => 'publish' ) );
	}
	$locations['primary'] = $mid;
	set_theme_mod( 'nav_menu_locations', $locations );
	return $mid;
}

/**
 * Old URLs of drafted illustrative equipment → equipment archive (301).
 */
function bdh_redirect_drafted_seed_equipment() {
	if ( ! is_404() ) { return; }
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '', PHP_URL_PATH ), '/' );
	if ( preg_match( '#(^|/)equipment/(eq-[a-z0-9_-]+)$#', $path, $m ) ) {
		$p = get_page_by_path( $m[2], OBJECT, 'bdh_product' );
		if ( $p && 'draft' === $p->post_status && get_post_meta( $p->ID, '_bdh_seed', true ) ) {
			wp_safe_redirect( get_post_type_archive_link( 'bdh_product' ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'bdh_redirect_drafted_seed_equipment' );

function bdh_upgrade_render() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$notes = array();
	if ( isset( $_POST['bdh_up_action'] ) && check_admin_referer( 'bdh_up', 'bdh_up_nonce' ) ) {
		$action = sanitize_key( $_POST['bdh_up_action'] );
		if ( 'pages' === $action ) {
			foreach ( bdh_v1_placeholder_bodies() as $slug => $body ) {
				$page = get_page_by_path( $slug );
				if ( ! $page ) { continue; }
				if ( trim( wp_strip_all_tags( $page->post_content ) ) !== $body ) { $notes[] = sprintf( __( '%s: you have edited this page — left unchanged.', 'benders-hub' ), $page->post_title ); continue; }
				wp_update_post( array( 'ID' => $page->ID, 'post_content' => bdh_trust_page_content( $slug ), 'post_title' => 'methodology' === $slug ? __( 'How we evaluate equipment', 'benders-hub' ) : $page->post_title ) );
				$notes[] = sprintf( __( '%s: placeholder replaced (previous version kept in Revisions).', 'benders-hub' ), $page->post_title );
			}
		} elseif ( 'equip_draft' === $action || 'equip_restore' === $action ) {
			$ids = get_posts( array( 'post_type' => 'bdh_product', 'post_status' => array( 'publish', 'draft' ), 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_bdh_seed', 'meta_value' => 1 ) );
			foreach ( $ids as $id ) { wp_update_post( array( 'ID' => $id, 'post_status' => 'equip_draft' === $action ? 'draft' : 'publish' ) ); }
			$notes[] = sprintf( __( '%d illustrative equipment entries updated.', 'benders-hub' ), count( $ids ) );
		} elseif ( 'menu' === $action ) {
			$r = bdh_build_magazine_menu();
			$notes[] = is_wp_error( $r ) ? $r->get_error_message() : __( 'Magazine Menu assigned to the primary location. Edit it under Appearance → Menus.', 'benders-hub' );
		} elseif ( 'menu_back' === $action ) {
			$prev = (int) get_option( 'bdh_prev_primary_menu', 0 );
			$loc  = get_theme_mod( 'nav_menu_locations', array() );
			$loc['primary'] = $prev;
			set_theme_mod( 'nav_menu_locations', $loc );
			$notes[] = __( 'Previous primary menu restored.', 'benders-hub' );
		}
	}
	$seed_count = count( get_posts( array( 'post_type' => 'bdh_product', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_bdh_seed', 'meta_value' => 1 ) ) );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Benders Hub — Upgrade to v2', 'benders-hub' ); ?></h1>
		<div class="notice notice-warning inline"><p><strong><?php esc_html_e( 'Before you start:', 'benders-hub' ); ?></strong> <?php esc_html_e( 'make sure your host or backup plugin has a restorable backup from today. Each action below is reversible, but a backup is your safety net.', 'benders-hub' ); ?></p></div>
		<?php foreach ( $notes as $n ) : ?><div class="notice notice-info"><p><?php echo esc_html( $n ); ?></p></div><?php endforeach; ?>

		<form method="post" class="card" style="max-width:860px;padding:16px 20px">
			<?php wp_nonce_field( 'bdh_up', 'bdh_up_nonce' ); ?>
			<h2><?php esc_html_e( '1. Replace placeholder trust pages', 'benders-hub' ); ?></h2>
			<p><?php esc_html_e( 'v1 created About, Editorial Standards, Methodology, Affiliate Disclosure, Safety and Glossary pages containing one line of text — the Methodology page claimed every product is workshop-tested. Pages still containing that exact line get an honest, complete draft. Pages you edited are skipped.', 'benders-hub' ); ?></p>
			<p><button class="button button-primary" name="bdh_up_action" value="pages"><?php esc_html_e( 'Replace untouched placeholders', 'benders-hub' ); ?></button></p>
			<p class="description"><?php esc_html_e( 'Privacy policy, terms, cookie policy and contact pages are not generated: use Settings → Privacy and legal advice appropriate to your country, and add a real contact method.', 'benders-hub' ); ?></p>
		</form>

		<form method="post" class="card" style="max-width:860px;padding:16px 20px">
			<?php wp_nonce_field( 'bdh_up', 'bdh_up_nonce' ); ?>
			<h2><?php esc_html_e( '2. Fictional equipment entries', 'benders-hub' ); ?></h2>
			<p><?php printf( esc_html__( 'v1\'s Repair Tool published %d "equipment" posts describing products that do not exist, with invented scores. v2 already labels them as category profiles, hides the scores and asks search engines not to index them. You can also move them to Draft; their URLs will then redirect to the equipment page.', 'benders-hub' ), (int) $seed_count ); ?></p>
			<p>
				<button class="button" name="bdh_up_action" value="equip_draft"><?php esc_html_e( 'Move to Draft', 'benders-hub' ); ?></button>
				<button class="button" name="bdh_up_action" value="equip_restore"><?php esc_html_e( 'Restore (publish again)', 'benders-hub' ); ?></button>
			</p>
		</form>

		<form method="post" class="card" style="max-width:860px;padding:16px 20px">
			<?php wp_nonce_field( 'bdh_up', 'bdh_up_nonce' ); ?>
			<h2><?php esc_html_e( '3. Magazine navigation', 'benders-hub' ); ?></h2>
			<p><?php esc_html_e( 'Creates a "Magazine Menu" from your categories that contain posts (never empty ones) plus the tools, and assigns it to the primary location. Your current menu is kept and can be switched back.', 'benders-hub' ); ?></p>
			<p>
				<button class="button button-primary" name="bdh_up_action" value="menu"><?php esc_html_e( 'Create & assign Magazine Menu', 'benders-hub' ); ?></button>
				<button class="button" name="bdh_up_action" value="menu_back"><?php esc_html_e( 'Switch back to previous menu', 'benders-hub' ); ?></button>
			</p>
		</form>

		<div class="card" style="max-width:860px;padding:16px 20px">
			<h2><?php esc_html_e( '4. Photos', 'benders-hub' ); ?></h2>
			<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=bdh-image-library' ) ); ?>"><?php esc_html_e( 'Open the Image Library', 'benders-hub' ); ?></a></p>
		</div>
	</div>
	<?php
}
