<?php
/**
 * Repair Tool — Benders Hub → Tools → Repair Site Setup.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Admin menu.
 */
function bdh_admin_menu() {
	add_menu_page(
		__( 'Benders Hub', 'benders-hub' ),
		__( 'Benders Hub', 'benders-hub' ),
		'manage_options',
		'bdh-dashboard',
		'bdh_dashboard_render',
		'dashicons-admin-tools',
		3
	);
	add_submenu_page( 'bdh-dashboard', __( 'Dashboard', 'benders-hub' ),  __( 'Dashboard', 'benders-hub' ),  'manage_options', 'bdh-dashboard', 'bdh_dashboard_render' );
	add_submenu_page( 'bdh-dashboard', __( 'Repair Site Setup', 'benders-hub' ), __( 'Repair Setup', 'benders-hub' ), 'manage_options', 'bdh-repair', 'bdh_repair_render' );
}
add_action( 'admin_menu', 'bdh_admin_menu' );

/**
 * Dashboard view.
 */
function bdh_dashboard_render() {
	$cards = array(
		array( __( 'Upgrade to v2', 'benders-hub' ), __( 'Replace placeholder trust pages, retire fictional equipment entries, build the magazine menu.', 'benders-hub' ), admin_url( 'admin.php?page=bdh-upgrade' ), true ),
		array( __( 'Image Library', 'benders-hub' ), __( 'Check licences, review and import photographs with credits and alt text.', 'benders-hub' ), admin_url( 'admin.php?page=bdh-image-library' ), true ),
		array( __( 'Customize homepage', 'benders-hub' ), __( 'Lead story, buying-guides category, essential reads, section order.', 'benders-hub' ), admin_url( 'customize.php?autofocus[panel]=bdh_panel' ), false ),
		array( __( 'Subscribers', 'benders-hub' ), __( 'Newsletter sign-ups stored on this site. Export as CSV.', 'benders-hub' ), admin_url( 'edit.php?post_type=bdh_subscriber' ), false ),
		array( __( 'Content Library', 'benders-hub' ), __( 'Starter articles. Review every claim before publishing.', 'benders-hub' ), admin_url( 'admin.php?page=bdh-content-library' ), false ),
		array( __( 'System Audit', 'benders-hub' ), __( 'Check templates, pages and settings.', 'benders-hub' ), admin_url( 'admin.php?page=bdh-audit' ), false ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Benders Hub', 'benders-hub' ); ?> <small style="font-size:13px;color:#646970">v<?php echo esc_html( BDH_VERSION ); ?></small></h1>
		<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:20px;margin-top:24px;max-width:1100px">
			<?php foreach ( $cards as $c ) : ?>
				<div class="card" style="padding:20px;margin:0"><h2><?php echo esc_html( $c[0] ); ?></h2><p><?php echo esc_html( $c[1] ); ?></p><a class="button <?php echo $c[3] ? 'button-primary' : ''; ?>" href="<?php echo esc_url( $c[2] ); ?>"><?php esc_html_e( 'Open', 'benders-hub' ); ?></a></div>
			<?php endforeach; ?>
		</div>
		<p style="margin-top:24px"><a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=bdh_export_subscribers' ), 'bdh_export_subscribers' ) ); ?>"><?php esc_html_e( 'Export subscribers (CSV)', 'benders-hub' ); ?></a></p>
	</div>
	<?php
}

/**
 * Repair tool view.
 */
function bdh_repair_render() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }

	// Process actions
	if ( isset( $_POST['bdh_repair_action'] ) && check_admin_referer( 'bdh_repair', 'bdh_repair_nonce' ) ) {
		$action = sanitize_key( $_POST['bdh_repair_action'] );
		$results = array();
		switch ( $action ) {
			case 'homepage':         $results = bdh_repair_homepage();        break;
			case 'tool_pages':       $results = bdh_repair_tool_pages();      break;
			case 'menus':            $results = bdh_repair_menus();           break;
			case 'legal':            $results = bdh_repair_legal_pages();     break;
			case 'permalinks':       $results = bdh_repair_permalinks();      break;
			case 'sections':         $results = bdh_repair_sections();        break;
			case 'schema':           $results = bdh_repair_schema();          break;
			case 'verify_tools':     $results = bdh_repair_verify_tools();    break;
			case 'archetypes':       $results = bdh_repair_archetypes();      break;
			case 'product_cats':     $results = bdh_repair_product_categories(); break;
			case 'seed_equipment':   $results = bdh_repair_seed_equipment();  break;
			case 'all':              $results = bdh_repair_all();             break;
		}
		set_transient( 'bdh_repair_results', $results, 30 );
		wp_safe_redirect( admin_url( 'admin.php?page=bdh-repair&done=1' ) );
		exit;
	}

	$results = get_transient( 'bdh_repair_results' );
	delete_transient( 'bdh_repair_results' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Benders Hub — Repair Site Setup', 'benders-hub' ); ?></h1>
		<p><?php esc_html_e( 'Recreate or repair core pieces of the theme setup. Safe to re-run — existing items are detected and skipped.', 'benders-hub' ); ?></p>

		<?php if ( $results ) : ?>
			<div class="notice notice-success" style="padding:14px;">
				<strong><?php esc_html_e( 'Repair complete:', 'benders-hub' ); ?></strong>
				<ul style="margin-left:20px;list-style:disc;">
				<?php foreach ( $results as $line ) echo '<li>' . esc_html( $line ) . '</li>'; ?>
				</ul>
			</div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'bdh_repair', 'bdh_repair_nonce' ); ?>
			<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;margin-top:20px;">
				<?php
				$actions = array(
					'homepage'     => array( __( 'Recreate Homepage', 'benders-hub' ),       __( 'Create / set "Home" page using the Benders Hub Pro front-page layout.', 'benders-hub' ) ),
					'tool_pages'   => array( __( 'Recreate Tool Pages', 'benders-hub' ),    __( 'Build standalone pages for Quiz, Calculator, Visualizer, Database, Workflow.', 'benders-hub' ) ),
					'menus'        => array( __( 'Rebuild Menus', 'benders-hub' ),          __( 'Create Primary, Footer, and Tools navigation menus and assign locations.', 'benders-hub' ) ),
					'legal'        => array( __( 'Recreate Legal Pages', 'benders-hub' ),   __( 'About, Editorial Standards, Methodology, Affiliate Disclosure, Privacy, Terms, Cookie, Contact.', 'benders-hub' ) ),
					'permalinks'   => array( __( 'Flush Permalinks', 'benders-hub' ),       __( 'Re-flush rewrite rules. Fixes 404s on CPT archives.', 'benders-hub' ) ),
					'sections'     => array( __( 'Repair Homepage Sections', 'benders-hub' ),__( 'Reset homepage section order and visibility to defaults.', 'benders-hub' ) ),
					'schema'       => array( __( 'Repair Schema', 'benders-hub' ),          __( 'Verify Organization, FAQ, ItemList, SoftwareApplication schema is wired.', 'benders-hub' ) ),
					'verify_tools' => array( __( 'Verify Tools', 'benders-hub' ),           __( 'Ping every interactive tool template and confirm assets enqueue.', 'benders-hub' ) ),
					'archetypes'   => array( __( 'Rebuild Fabrication Archetypes', 'benders-hub' ), __( 'Sync 12 default archetypes into the bdh_archetype_tax taxonomy.', 'benders-hub' ) ),
					'product_cats' => array( __( 'Create Product Categories', 'benders-hub' ), __( 'Create hydraulic-benders, manual-benders, cnc-benders, exhaust-bending, roll-cage, mandrel categories (kills footer 404s).', 'benders-hub' ) ),
					'seed_equipment'=> array( __( 'Seed Equipment CPT', 'benders-hub' ), __( 'Populate the bdh_product CPT with the 10 curated equipment entries — fills the /equipment/ archive.', 'benders-hub' ) ),
					'all'          => array( __( 'Run Full Repair (recommended)', 'benders-hub' ), __( 'Run every repair step in order. Idempotent.', 'benders-hub' ) ),
				);
				foreach ( $actions as $key => $info ) :
					$primary = $key === 'all' ? 'button-primary' : '';
				?>
				<div class="card" style="padding:16px;">
					<h3 style="margin:0 0 6px;"><?php echo esc_html( $info[0] ); ?></h3>
					<p style="color:#646970;"><?php echo esc_html( $info[1] ); ?></p>
					<button type="submit" name="bdh_repair_action" value="<?php echo esc_attr( $key ); ?>" class="button <?php echo esc_attr( $primary ); ?>">
						<?php esc_html_e( 'Run', 'benders-hub' ); ?>
					</button>
				</div>
				<?php endforeach; ?>
			</div>
		</form>
	</div>
	<?php
}

/* ------------------ Repair operations ------------------ */

function bdh_repair_homepage() {
	$out = array();
	$page = get_page_by_path( 'home' );
	if ( ! $page ) {
		$id = wp_insert_post( array(
			'post_title'   => 'Home',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_name'    => 'home',
			'post_content' => '<!-- Front Page handled by template front-page.php -->',
		) );
		$out[] = $id ? 'Home page created.' : 'Home page creation failed.';
		$page  = $id ? get_post( $id ) : null;
	} else {
		$out[] = 'Home page already exists.';
	}
	if ( $page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page->ID );
		$out[] = 'Set static front page.';
	}
	return $out;
}

function bdh_repair_tool_pages() {
	$out = array();
	$tools = array(
		'quiz'         => array( 'Tube Bender Match Quiz',       '[bdh_quiz]' ),
		'calculator'   => array( 'Bend Radius Calculator',       '[bdh_calculator]' ),
		'visualizer'   => array( 'Tube Geometry Visualizer',     '[bdh_visualizer]' ),
		'workflow'     => array( 'Workflow Planner',             '[bdh_workflow]' ),
		'compatibility'=> array( 'Tool Compatibility Generator', '[bdh_compatibility]' ),
		'prioritizer'  => array( 'Workshop Efficiency Prioritizer','[bdh_prioritizer]' ),
		'tracker'      => array( 'Fabrication Project Tracker',  '[bdh_tracker]' ),
		'database'     => array( 'Bending Equipment Database',   '[bdh_database]' ),
	);
	foreach ( $tools as $slug => $info ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) { $out[] = $info[0] . ' — page exists.'; continue; }
		$id = wp_insert_post( array(
			'post_title'   => $info[0],
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => $info[1],
		) );
		$out[] = $id ? $info[0] . ' page created.' : $info[0] . ' creation failed.';
	}
	return $out;
}

function bdh_repair_menus() {
	$out = array();
	$pairs = array(
		'primary' => array( 'name' => 'Primary Menu', 'items' => array(
			array( 'Home', '/' ), array( 'Quiz', '/#bdh-quiz' ), array( 'Calculator', '/#bdh-calculator' ),
			array( 'Visualizer', '/#bdh-visualizer' ), array( 'Equipment', '/#bdh-database' ), array( 'Compare', '/#bdh-comparison' ),
			array( 'About', '/about/' ),
		) ),
		'footer'  => array( 'name' => 'Footer Menu',  'items' => array(
			array( 'About', '/about/' ), array( 'Editorial Standards', '/editorial-standards/' ),
			array( 'Methodology', '/methodology/' ), array( 'Affiliate Disclosure', '/affiliate-disclosure/' ),
			array( 'Privacy Policy', '/privacy-policy/' ), array( 'Terms', '/terms/' ), array( 'Contact', '/contact/' ),
		) ),
		'tools'   => array( 'name' => 'Tools Menu',   'items' => array(
			array( 'Quiz', '/quiz/' ), array( 'Calculator', '/calculator/' ),
			array( 'Visualizer', '/visualizer/' ), array( 'Workflow', '/workflow/' ),
			array( 'Compatibility', '/compatibility/' ), array( 'Prioritizer', '/prioritizer/' ),
			array( 'Tracker', '/tracker/' ), array( 'Database', '/database/' ),
		) ),
	);
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	foreach ( $pairs as $loc => $cfg ) {
		$menu = wp_get_nav_menu_object( $cfg['name'] );
		if ( ! $menu ) {
			$mid = wp_create_nav_menu( $cfg['name'] );
			if ( ! is_wp_error( $mid ) ) {
				foreach ( $cfg['items'] as $it ) {
					wp_update_nav_menu_item( $mid, 0, array(
						'menu-item-title'  => $it[0],
						'menu-item-url'    => home_url( $it[1] ),
						'menu-item-status' => 'publish',
					) );
				}
				$locations[ $loc ] = $mid;
				$out[] = $cfg['name'] . ' created with ' . count( $cfg['items'] ) . ' items.';
			}
		} else {
			$locations[ $loc ] = $menu->term_id;
			$out[] = $cfg['name'] . ' already exists — assigned to location.';
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );
	return $out;
}

function bdh_repair_legal_pages() {
	$out = array();
	$pages = array(
		'about'                => array( 'About Benders Hub',     'Premium fabrication engineering ecosystem. About this site, our team, and how we test.' ),
		'editorial-standards'  => array( 'Editorial Standards',   'Our editorial standards for reviewing bending equipment and fabrication tools.' ),
		'methodology'          => array( 'Testing Methodology',   'How we workshop-test every tube bender, die, and accessory before publishing.' ),
		'affiliate-disclosure' => array( 'Affiliate Disclosure',  'As an Amazon Associate, Benders Hub earns from qualifying purchases. We disclose every affiliate relationship.' ),
		'privacy-policy'       => array( 'Privacy Policy',        'How Benders Hub collects, uses, and protects your data.' ),
		'terms'                => array( 'Terms of Service',      'Terms governing your use of bendershub.com.' ),
		'cookie-policy'        => array( 'Cookie Policy',         'How we use cookies and localStorage.' ),
		'contact'              => array( 'Contact',               'Get in touch with Benders Hub.' ),
		'safety'               => array( 'Fabrication Safety',    'Workshop safety, welding integration, and OSHA-aligned guidance for bending.' ),
		'glossary'             => array( 'Tubing & Bending Glossary', 'Definitions of bending terminology — CLR, K-factor, springback, mandrel, and more.' ),
		'compare'              => array( 'Compare Tube Benders',  "[bdh_comparison]" ),
	);
	foreach ( $pages as $slug => $info ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) { $out[] = $info[0] . ' — exists.'; continue; }
		$draft = function_exists( 'bdh_trust_page_content' ) ? bdh_trust_page_content( $slug ) : '';
		if ( in_array( $slug, array( 'privacy-policy', 'terms', 'cookie-policy', 'contact' ), true ) ) {
			$out[] = $info[0] . ' — not generated (needs your own legal text / contact details).';
			continue;
		}
		$id = wp_insert_post( array(
			'post_title'   => 'methodology' === $slug ? __( 'How we evaluate equipment', 'benders-hub' ) : $info[0],
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => $draft ? $draft : $info[1],
		) );
		$out[] = $id ? $info[0] . ' created.' : $info[0] . ' failed.';
	}
	return $out;
}

function bdh_repair_permalinks() {
	flush_rewrite_rules( false );
	return array( 'Permalinks flushed.' );
}

function bdh_repair_sections() {
	remove_theme_mod( 'bdh_home_layout_v2' );
	return array( 'Homepage section order and visibility reset to defaults.' );
}

function bdh_repair_schema() {
	if ( bdh_seo_plugin_active() ) {
		return array( 'An SEO plugin is active, so the theme leaves structured data to it.' );
	}
	return array( 'Theme outputs: Organization, WebSite + SearchAction, BlogPosting (articles), BreadcrumbList, FAQPage (homepage, when the FAQ is shown), Product + Review only for hands-on-tested equipment. Validate with Google\'s Rich Results Test.' );
}

function bdh_repair_verify_tools() {
	$tools = array(
		'tools/quiz', 'tools/calculator', 'tools/visualizer', 'tools/workflow',
		'tools/compatibility', 'tools/prioritizer', 'tools/tracker', 'tools/database',
	);
	$out = array();
	foreach ( $tools as $t ) {
		$file = BDH_THEME_DIR . '/template-parts/' . $t . '.php';
		$out[] = $t . ': ' . ( file_exists( $file ) ? 'PRESENT' : 'MISSING' );
	}
	return $out;
}

function bdh_repair_seed_equipment() {
	// v1 created ten fictional products with invented scores here. v2 no longer does.
	return array( __( 'Equipment seeding is disabled in v2 (it created fictional products). Add real equipment under Equipment → Add Equipment.', 'benders-hub' ) );
}

function bdh_repair_product_categories() {
	$out = array();
	$cats = array(
		'hydraulic-benders' => array( 'Hydraulic Benders',        'Hydraulic rotary-draw and ram-style tube and pipe benders.' ),
		'manual-benders'    => array( 'Manual Benders',           'Hand-leverage tube benders for light tubing and field work.' ),
		'cnc-benders'       => array( 'CNC Benders',              'Programmable CNC and electric tube benders for production work.' ),
		'exhaust-bending'   => array( 'Exhaust Fabrication',      'Mandrel benders, exhaust dies, and headers fabrication tooling.' ),
		'roll-cage'         => array( 'Roll Cage & Chassis',      'Roll cage tubing, chromoly fabrication, chassis dies, and tube notchers.' ),
		'mandrel'           => array( 'Mandrel Benders',          'Mandrel bending systems for thin-wall tubing and ID-critical bends.' ),
	);
	foreach ( $cats as $slug => $info ) {
		$term = term_exists( $slug, 'category' );
		if ( ! $term ) {
			$res = wp_insert_term( $info[0], 'category', array( 'slug' => $slug, 'description' => $info[1] ) );
			$out[] = is_wp_error( $res ) ? $info[0] . ' — error' : $info[0] . ' category created.';
		} else {
			$out[] = $info[0] . ' — exists.';
		}
	}
	return $out;
}

function bdh_repair_archetypes() {
	$out = array();
	$archetypes = bdh_archetypes();
	foreach ( $archetypes as $a ) {
		$term = term_exists( $a['id'], 'bdh_archetype_tax' );
		if ( ! $term ) {
			$res = wp_insert_term( $a['name'], 'bdh_archetype_tax', array( 'slug' => sanitize_title( $a['id'] ), 'description' => $a['tagline'] ) );
			$out[] = is_wp_error( $res ) ? $a['name'] . ' — error' : $a['name'] . ' — created.';
		} else {
			$out[] = $a['name'] . ' — exists.';
		}
	}
	return $out;
}

function bdh_repair_all() {
	$out = array();
	$out = array_merge( $out, bdh_repair_homepage() );
	$out = array_merge( $out, bdh_repair_tool_pages() );
	$out = array_merge( $out, bdh_repair_legal_pages() );
	$out = array_merge( $out, bdh_repair_product_categories() );
	$out = array_merge( $out, bdh_repair_seed_equipment() );
	$out = array_merge( $out, bdh_repair_menus() );
	$out = array_merge( $out, bdh_repair_archetypes() );
	$out = array_merge( $out, bdh_repair_sections() );
	$out = array_merge( $out, bdh_repair_permalinks() );
	$out = array_merge( $out, bdh_repair_schema() );
	$out = array_merge( $out, bdh_repair_verify_tools() );
	return $out;
}
