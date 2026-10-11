<?php
/**
 * Find My Bender quiz.
 *
 * v1 problems fixed: any "Professional" answer returned a CNC machine, tube
 * diameter and the manual/hydraulic answer were ignored, and results quoted
 * invented figures ("under-8s cycle times", "CLR ≥ 5× OD"). v2 scores every
 * answer, explains why, and offers a runner-up.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$in_console = isset( $args['context'] ) && 'console' === $args['context'];

$questions = array(
	array( 'id' => 'project', 'q' => __( 'What will you bend most?', 'benders-hub' ), 'opts' => array(
		'rollcage'   => __( 'Roll cage or race-car safety structure', 'benders-hub' ),
		'chassis'    => __( 'Off-road armour, bumpers, frames, furniture', 'benders-hub' ),
		'exhaust'    => __( 'Exhaust, headers or intake piping', 'benders-hub' ),
		'lines'      => __( 'Brake, fuel or hydraulic lines', 'benders-hub' ),
		'pipe'       => __( 'Handrail, gates or structural pipe', 'benders-hub' ),
		'conduit'    => __( 'Electrical conduit (EMT)', 'benders-hub' ),
		'production' => __( 'Identical parts in volume', 'benders-hub' ),
		'art'        => __( 'Hoops, arches and decorative curves', 'benders-hub' ),
	) ),
	array( 'id' => 'material', 'q' => __( 'Which material?', 'benders-hub' ), 'opts' => array(
		'mild'      => __( 'Mild steel tube (ERW / DOM)', 'benders-hub' ),
		'chromoly'  => __( '4130 chromoly', 'benders-hub' ),
		'stainless' => __( 'Stainless steel', 'benders-hub' ),
		'aluminum'  => __( 'Aluminium', 'benders-hub' ),
		'soft'      => __( 'Copper or soft line', 'benders-hub' ),
		'pipe'      => __( 'Schedule pipe (sized by NPS)', 'benders-hub' ),
	) ),
	array( 'id' => 'diameter', 'q' => __( 'Largest outside diameter?', 'benders-hub' ), 'opts' => array(
		'small' => __( 'Under 1"', 'benders-hub' ),
		'mid'   => __( '1" to 2"', 'benders-hub' ),
		'large' => __( '2" to 3"', 'benders-hub' ),
		'huge'  => __( 'Over 3"', 'benders-hub' ),
	) ),
	array( 'id' => 'wall', 'q' => __( 'Typical wall thickness?', 'benders-hub' ), 'opts' => array(
		'thin'   => __( 'Thin — about .065" or less (exhaust, lines)', 'benders-hub' ),
		'medium' => __( 'Medium — about .083"–.120" (cages, frames)', 'benders-hub' ),
		'heavy'  => __( 'Heavy — .134" and up, or schedule 80', 'benders-hub' ),
		'unsure' => __( 'Not sure', 'benders-hub' ),
	) ),
	array( 'id' => 'radius', 'q' => __( 'How tight are the bends?', 'benders-hub' ), 'opts' => array(
		'tight'    => __( 'Tight — about 2× the tube diameter or less', 'benders-hub' ),
		'standard' => __( 'Standard — around 3–4× the diameter', 'benders-hub' ),
		'large'    => __( 'Large sweeps, arches or full rings', 'benders-hub' ),
	) ),
	array( 'id' => 'volume', 'q' => __( 'How often will you bend?', 'benders-hub' ), 'opts' => array(
		'occasional' => __( 'A few projects a year', 'benders-hub' ),
		'regular'    => __( 'Most weeks', 'benders-hub' ),
		'production' => __( 'Daily, or batches of identical parts', 'benders-hub' ),
	) ),
	array( 'id' => 'power', 'q' => __( 'Space and power available?', 'benders-hub' ), 'opts' => array(
		'portable' => __( 'Portable / no power needed', 'benders-hub' ),
		'garage'   => __( 'Garage with a solid floor', 'benders-hub' ),
		'shop'     => __( 'Shop with compressed air or 240 V', 'benders-hub' ),
		'industrial' => __( 'Industrial floor and 3-phase power', 'benders-hub' ),
	) ),
	array( 'id' => 'budget', 'q' => __( 'Budget for the bender and first dies?', 'benders-hub' ), 'opts' => array(
		'b1' => __( 'Under $300', 'benders-hub' ),
		'b2' => __( '$300 – $1,500', 'benders-hub' ),
		'b3' => __( '$1,500 – $5,000', 'benders-hub' ),
		'b4' => __( 'Over $5,000', 'benders-hub' ),
	) ),
	array( 'id' => 'experience', 'q' => __( 'Your bending experience?', 'benders-hub' ), 'opts' => array(
		'beginner' => __( 'New to tube bending', 'benders-hub' ),
		'inter'    => __( 'Some experience', 'benders-hub' ),
		'pro'      => __( 'Professional fabricator', 'benders-hub' ),
	) ),
);

// Guide links for each result type (only output when the post exists).
$guides = array(
	'lever'            => 'diy-garage-workshop',
	'conduit'          => 'conduit-bending-electricians',
	'rotary_manual'    => 'best-hydraulic-tube-bender',
	'rotary_hydraulic' => 'best-hydraulic-tube-bender',
	'ram'              => 'best-hydraulic-tube-bender',
	'mandrel_bends'    => 'custom-exhaust-fabrication',
	'mandrel'          => 'mandrel-vs-rotary',
	'cnc'              => 'cnc-tube-bending',
	'roller'           => 'mandrel-vs-rotary',
);
$guide_urls = array();
foreach ( $guides as $k => $slug ) {
	$p = get_page_by_path( $slug, OBJECT, 'post' );
	if ( $p && 'publish' === $p->post_status ) { $guide_urls[ $k ] = array( 'url' => get_permalink( $p ), 'title' => get_the_title( $p ) ); }
}
$rc = get_page_by_path( 'roll-cage-specifications', OBJECT, 'post' );
if ( $rc && 'publish' === $rc->post_status ) { $guide_urls['rollcage_rules'] = array( 'url' => get_permalink( $rc ), 'title' => get_the_title( $rc ) ); }
?>
<section class="bdh-section bdh-tool bdh-quiz-section" id="bdh-quiz" aria-labelledby="bdh-quiz-title">
	<div class="<?php echo $in_console ? 'bdh-tool-inner' : 'bdh-container'; ?>">
		<header class="bdh-tool-head">
			<h2 class="bdh-tool-title" id="bdh-quiz-title"><?php esc_html_e( 'Find My Bender', 'benders-hub' ); ?></h2>
			<p class="bdh-tool-sub"><?php esc_html_e( 'Nine quick questions. You get the type of bender that fits your work, the reasons, a runner-up and what to check before you buy. Answers stay in your browser.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-quiz-shell" data-bdh-quiz data-guides="<?php echo esc_attr( wp_json_encode( $guide_urls ) ); ?>" data-calc="<?php echo esc_url( bdh_tool_url( 'calculator' ) ); ?>">

			<div class="bdh-quiz-intro" data-bdh-quiz-screen="intro">
				<button type="button" class="bdh-btn bdh-btn-primary" data-bdh-quiz-start><?php esc_html_e( 'Start the quiz', 'benders-hub' ); ?><?php echo bdh_icon( 'arrow' ); ?></button>
				<span class="bdh-quiz-intro-note"><?php echo bdh_icon( 'clock' ); ?><?php esc_html_e( 'About one minute', 'benders-hub' ); ?></span>
			</div>

			<form class="bdh-quiz-runner" data-bdh-quiz-screen="runner" hidden novalidate>
				<div class="bdh-quiz-progress" aria-hidden="true"><span data-bdh-quiz-progress></span></div>
				<p class="bdh-quiz-step-label"><?php esc_html_e( 'Question', 'benders-hub' ); ?> <span data-bdh-quiz-step>1</span> <?php esc_html_e( 'of', 'benders-hub' ); ?> <?php echo (int) count( $questions ); ?></p>
				<?php foreach ( $questions as $idx => $q ) : ?>
					<fieldset class="bdh-quiz-question" data-bdh-quiz-step-card="<?php echo (int) $idx; ?>" data-bdh-quiz-id="<?php echo esc_attr( $q['id'] ); ?>" <?php echo 0 === $idx ? '' : 'hidden'; ?>>
						<legend class="bdh-quiz-q"><?php echo esc_html( $q['q'] ); ?></legend>
						<div class="bdh-quiz-opts">
						<?php foreach ( $q['opts'] as $val => $label ) : ?>
							<label class="bdh-quiz-opt">
								<input type="radio" name="<?php echo esc_attr( $q['id'] ); ?>" value="<?php echo esc_attr( $val ); ?>">
								<span><?php echo esc_html( $label ); ?></span>
							</label>
						<?php endforeach; ?>
						</div>
					</fieldset>
				<?php endforeach; ?>
				<p class="bdh-form-error" data-bdh-quiz-error role="alert" hidden></p>
				<div class="bdh-quiz-controls">
					<button type="button" class="bdh-btn bdh-btn-ghost" data-bdh-quiz-back disabled><?php esc_html_e( 'Back', 'benders-hub' ); ?></button>
					<button type="submit" class="bdh-btn bdh-btn-primary" data-bdh-quiz-next><?php esc_html_e( 'Next', 'benders-hub' ); ?></button>
				</div>
			</form>

			<div class="bdh-quiz-result" data-bdh-quiz-screen="result" hidden tabindex="-1">
				<p class="bdh-eyebrow"><?php esc_html_e( 'Your best match', 'benders-hub' ); ?></p>
				<h3 class="bdh-quiz-result-title" data-r="title"></h3>
				<p class="bdh-quiz-result-summary" data-r="summary"></p>
				<div class="bdh-quiz-result-cols">
					<div>
						<h4><?php esc_html_e( 'Why this fits', 'benders-hub' ); ?></h4>
						<ul class="bdh-checklist" data-r="reasons"></ul>
					</div>
					<div>
						<h4><?php esc_html_e( 'Check before you buy', 'benders-hub' ); ?></h4>
						<ul class="bdh-checklist" data-r="look"></ul>
					</div>
				</div>
				<p class="bdh-note bdh-note--warn" data-r="safety"></p>
				<p class="bdh-quiz-runnerup" data-r="runnerup"></p>
				<div class="bdh-quiz-result-cta">
					<a class="bdh-btn bdh-btn-primary bdh-amazon-cta" data-r="amazon" href="#" rel="nofollow sponsored noopener" target="_blank"><span data-r="amazon-label"><?php esc_html_e( 'See options on Amazon', 'benders-hub' ); ?></span><?php echo bdh_icon( 'external', 'bdh-btn-icon' ); ?></a>
					<a class="bdh-btn bdh-btn-outline" data-r="guide" href="#" hidden></a>
					<button type="button" class="bdh-btn bdh-btn-ghost" data-bdh-quiz-retake><?php esc_html_e( 'Change answers', 'benders-hub' ); ?></button>
					<button type="button" class="bdh-btn bdh-btn-ghost" data-bdh-quiz-share><?php esc_html_e( 'Share', 'benders-hub' ); ?></button>
				</div>
				<?php bdh_inline_disclosure(); ?>
			</div>
		</div>
	</div>
</section>
