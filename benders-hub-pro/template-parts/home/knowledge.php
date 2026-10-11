<?php
/**
 * Homepage Section H — technical knowledge hub.
 * Definitions follow manufacturer and trade references (Pro-Tools, The
 * Fabricator, Baileigh, Rogue Fabrication); see the research notes in /docs.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$topics = array(
	array(
		'term'    => __( 'Centerline radius (CLR)', 'benders-hub' ),
		'def'     => __( 'Radius from the bend centre to the tube\'s centreline. Dies are sold by tube OD and CLR.', 'benders-hub' ),
		'formula' => 'R<sub>outside</sub> = CLR + OD/2',
		'link'    => array( 'post', 'bend-radius-explained' ),
	),
	array(
		'term'    => __( 'D of bend', 'benders-hub' ),
		'def'     => __( 'CLR divided by OD. The smaller it is, the harder the bend. Around 3D is a common practical minimum without a mandrel.', 'benders-hub' ),
		'formula' => 'D = CLR ÷ OD',
		'link'    => array( 'post', 'tube-wrinkling-prevention' ),
	),
	array(
		'term'    => __( 'Wall factor', 'benders-hub' ),
		'def'     => __( 'OD divided by wall thickness. A higher wall factor means thinner wall relative to diameter — more risk of wrinkling and flattening.', 'benders-hub' ),
		'formula' => 'WF = OD ÷ wall',
		'link'    => array( 'post', 'tube-wrinkling-prevention' ),
	),
	array(
		'term'    => __( 'Springback', 'benders-hub' ),
		'def'     => __( 'The angle a tube relaxes after the bender releases it. It varies by material, batch and radius, so measure it with a test bend and over-bend to suit.', 'benders-hub' ),
		'formula' => '',
		'link'    => array( 'tool', 'calculator' ),
	),
	array(
		'term'    => __( 'Bend arc length', 'benders-hub' ),
		'def'     => __( 'Length of tube along the centreline inside the bend. A 90° bend on a 6" CLR uses 9.42" of tube.', 'benders-hub' ),
		'formula' => 'L = CLR × θ (radians)',
		'link'    => array( 'tool', 'calculator' ),
	),
	array(
		'term'    => __( 'Tangent length & cut length', 'benders-hub' ),
		'def'     => __( 'Distance from where the bend starts to where the two straight legs would meet. Used to turn drawing dimensions into a cut length.', 'benders-hub' ),
		'formula' => 'T = CLR × tan(θ/2)',
		'link'    => array( 'tool', 'calculator' ),
	),
	array(
		'term'    => __( 'Ovality', 'benders-hub' ),
		'def'     => __( 'How out-of-round the tube is after bending. Acceptable limits depend on the application and any governing code.', 'benders-hub' ),
		'formula' => '(D<sub>max</sub> − D<sub>min</sub>) ÷ D<sub>nominal</sub> × 100%',
		'link'    => array( 'post', 'tube-wrinkling-prevention' ),
	),
	array(
		'term'    => __( 'Rotary draw, mandrel & ram', 'benders-hub' ),
		'def'     => __( 'Rotary draw pulls tube around a die for a consistent radius; a mandrel supports thin wall from inside; a ram presses tube between dies and can flatten it.', 'benders-hub' ),
		'formula' => '',
		'link'    => array( 'post', 'mandrel-vs-rotary' ),
	),
	array(
		'term'    => __( 'Tube vs pipe sizes', 'benders-hub' ),
		'def'     => __( 'Tube is sized by actual OD and wall; pipe by nominal size and schedule. 1-1/2" NPS pipe is 1.900" OD, so tube and pipe dies are not interchangeable.', 'benders-hub' ),
		'formula' => '',
		'link'    => array( 'tool', 'comparison' ),
	),
);
$glossary = bdh_page_url( 'glossary' );
?>
<section class="bdh-section bdh-knowledge" id="bdh-knowledge" aria-labelledby="bdh-knowledge-title">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head--split">
			<div>
				<p class="bdh-eyebrow"><?php esc_html_e( 'Technical knowledge hub', 'benders-hub' ); ?></p>
				<h2 class="bdh-section-title" id="bdh-knowledge-title"><?php esc_html_e( 'Bending fundamentals, defined', 'benders-hub' ); ?></h2>
			</div>
			<?php if ( $glossary ) : ?>
				<a class="bdh-link-arrow" href="<?php echo esc_url( $glossary ); ?>"><?php esc_html_e( 'Full glossary', 'benders-hub' ); ?><?php echo bdh_icon( 'arrow' ); ?></a>
			<?php endif; ?>
		</header>
		<div class="bdh-knowledge-grid">
			<?php foreach ( $topics as $t ) :
				$url = '';
				if ( 'post' === $t['link'][0] ) {
					$p = get_page_by_path( $t['link'][1], OBJECT, 'post' );
					$url = ( $p && 'publish' === $p->post_status ) ? get_permalink( $p ) : '';
				} else {
					$url = bdh_tool_url( $t['link'][1] );
				}
			?>
				<article class="bdh-term">
					<h3 class="bdh-term-name"><?php echo esc_html( $t['term'] ); ?></h3>
					<?php if ( $t['formula'] ) : ?>
						<p class="bdh-formula"><?php echo wp_kses( $t['formula'], array( 'sub' => array(), 'sup' => array() ) ); ?></p>
					<?php endif; ?>
					<p class="bdh-term-def"><?php echo esc_html( $t['def'] ); ?></p>
					<?php if ( $url ) : ?>
						<a class="bdh-term-link" href="<?php echo esc_url( $url ); ?>"><?php echo 'tool' === $t['link'][0] ? esc_html__( 'Try it in the tool', 'benders-hub' ) : esc_html__( 'Read more', 'benders-hub' ); ?><span class="bdh-screen-reader-text"> — <?php echo esc_html( $t['term'] ); ?></span><?php echo bdh_icon( 'arrow' ); ?></a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
