<?php
/**
 * Homepage Section G — learn by project or application.
 * Each pathway links to existing guides (only those that are published), the
 * quiz pre-set for that profile, and the matching equipment advice.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$title = bdh_option( 'archetypes_title', __( 'Learn by project', 'benders-hub' ) );
$sub   = bdh_option( 'archetypes_sub', __( 'Pick the work you do. Each path collects the guides and tools that matter for it.', 'benders-hub' ) );
if ( 'Which fabricator are you?' === $title ) { $title = __( 'Learn by project', 'benders-hub' ); }
$quiz  = bdh_tool_url( 'quiz' );
?>
<section class="bdh-section bdh-pathways" id="bdh-archetypes" aria-labelledby="bdh-pathways-title">
	<span id="bdh-quickstart" class="bdh-anchor"></span>
	<div class="bdh-container">
		<header class="bdh-section-head">
			<p class="bdh-eyebrow"><?php esc_html_e( 'Fabrication pathways', 'benders-hub' ); ?></p>
			<h2 class="bdh-section-title" id="bdh-pathways-title"><?php echo esc_html( $title ); ?></h2>
			<p class="bdh-section-sub"><?php echo esc_html( $sub ); ?></p>
		</header>
		<div class="bdh-pathway-grid">
			<?php foreach ( bdh_archetypes() as $a ) :
				$links = array();
				foreach ( $a['posts'] as $slug ) {
					$p = get_page_by_path( $slug, OBJECT, 'post' );
					if ( $p && 'publish' === $p->post_status ) { $links[] = $p; }
					if ( count( $links ) >= 3 ) { break; }
				}
			?>
				<article class="bdh-pathway" id="pathway-<?php echo esc_attr( str_replace( '_', '-', $a['id'] ) ); ?>" data-archetype="<?php echo esc_attr( $a['id'] ); ?>">
					<header class="bdh-pathway-head">
						<span class="bdh-pathway-icon"><?php echo bdh_icon( $a['icon'] ); ?></span>
						<h3 class="bdh-pathway-name"><?php echo esc_html( $a['name'] ); ?></h3>
					</header>
					<p class="bdh-pathway-tagline"><?php echo esc_html( $a['tagline'] ); ?></p>
					<dl class="bdh-pathway-specs">
						<div><dt><?php esc_html_e( 'Typical tube', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $a['typical'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Core equipment', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $a['equipment'] ); ?></dd></div>
					</dl>
					<?php if ( $links ) : ?>
						<ul class="bdh-pathway-links">
							<?php foreach ( $links as $p ) : ?>
								<li><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<a class="bdh-link-arrow" href="<?php echo esc_url( add_query_arg( 'profile', $a['profile'], $quiz ) ); ?>" data-bdh-archetype-pick="<?php echo esc_attr( $a['id'] ); ?>" data-bdh-no-prevent><?php echo esc_html( $a['cta'] ); ?><?php echo bdh_icon( 'arrow' ); ?></a>
				</article>
			<?php endforeach; ?>
			<article class="bdh-pathway bdh-pathway--cta">
				<h3 class="bdh-pathway-name"><?php esc_html_e( 'Not sure where you fit?', 'benders-hub' ); ?></h3>
				<p><?php esc_html_e( 'Answer nine questions about your tube, projects and shop. The quiz explains which type of bender fits and why.', 'benders-hub' ); ?></p>
				<a class="bdh-btn bdh-btn-primary" href="<?php echo esc_url( $quiz ); ?>"><?php esc_html_e( 'Take the quiz', 'benders-hub' ); ?></a>
			</article>
		</div>
	</div>
</section>
