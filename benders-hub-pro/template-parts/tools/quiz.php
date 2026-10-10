<?php
/**
 * Tube Bender Match Quiz.
 *
 * Hidden results until completion. One question at a time.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$questions = array(
	array(
		'id'  => 'mode',
		'q'   => __( 'Manual or hydraulic?', 'benders-hub' ),
		'opts' => array(
			'manual'    => __( 'Manual — hand-leverage, garage scale', 'benders-hub' ),
			'hydraulic' => __( 'Hydraulic — bench or floor', 'benders-hub' ),
			'cnc'       => __( 'CNC / Servo — production grade', 'benders-hub' ),
		),
	),
	array(
		'id'  => 'material',
		'q'   => __( 'What material are you bending most?', 'benders-hub' ),
		'opts' => array(
			'mild'      => __( 'Mild steel / DOM', 'benders-hub' ),
			'stainless' => __( 'Stainless (T304/T321)', 'benders-hub' ),
			'chromoly'  => __( 'Chromoly 4130', 'benders-hub' ),
			'aluminum'  => __( 'Aluminum / thin-wall', 'benders-hub' ),
		),
	),
	array(
		'id'  => 'diameter',
		'q'   => __( 'Tubing diameter range?', 'benders-hub' ),
		'opts' => array(
			'small'  => __( '1/4" – 1"', 'benders-hub' ),
			'mid'    => __( '1" – 2"', 'benders-hub' ),
			'large'  => __( '2" – 4"', 'benders-hub' ),
			'huge'   => __( '4"+', 'benders-hub' ),
		),
	),
	array(
		'id'  => 'project',
		'q'   => __( 'Primary project type?', 'benders-hub' ),
		'opts' => array(
			'exhaust'    => __( 'Custom exhaust / headers', 'benders-hub' ),
			'rollcage'   => __( 'Roll cage / chassis', 'benders-hub' ),
			'pipe'       => __( 'Pipe / handrail / structural', 'benders-hub' ),
			'art'        => __( 'Ornamental / sculpture', 'benders-hub' ),
			'production' => __( 'OEM / production runs', 'benders-hub' ),
		),
	),
	array(
		'id'  => 'precision',
		'q'   => __( 'Precision requirement?', 'benders-hub' ),
		'opts' => array(
			'entry' => __( '±2° is fine', 'benders-hub' ),
			'mid'   => __( '±1° preferred', 'benders-hub' ),
			'pro'   => __( '±0.5° — chassis grade', 'benders-hub' ),
			'cnc'   => __( '±0.1° — production grade', 'benders-hub' ),
		),
	),
	array(
		'id'  => 'workshop',
		'q'   => __( 'Workshop size & power?', 'benders-hub' ),
		'opts' => array(
			'garage'     => __( 'Garage / single bay, 110V', 'benders-hub' ),
			'pro'        => __( 'Pro shop, 220V', 'benders-hub' ),
			'industrial' => __( 'Industrial floor, 3-phase', 'benders-hub' ),
			'mobile'     => __( 'Mobile / service truck', 'benders-hub' ),
		),
	),
	array(
		'id'  => 'experience',
		'q'   => __( 'Experience level?', 'benders-hub' ),
		'opts' => array(
			'beginner' => __( 'Beginner', 'benders-hub' ),
			'inter'    => __( 'Intermediate', 'benders-hub' ),
			'pro'      => __( 'Professional fabricator', 'benders-hub' ),
		),
	),
	array(
		'id'  => 'sector',
		'q'   => __( 'Automotive or industrial?', 'benders-hub' ),
		'opts' => array(
			'auto'       => __( 'Automotive / motorsport', 'benders-hub' ),
			'industrial' => __( 'Industrial / structural', 'benders-hub' ),
			'mixed'      => __( 'Mixed / general fab', 'benders-hub' ),
			'art'        => __( 'Art / decorative', 'benders-hub' ),
		),
	),
	array(
		'id'  => 'budget',
		'q'   => __( 'Budget range?', 'benders-hub' ),
		'opts' => array(
			'$'     => __( 'Under $200', 'benders-hub' ),
			'$$'    => __( '$200 – $500', 'benders-hub' ),
			'$$$'   => __( '$500 – $1,500', 'benders-hub' ),
			'$$$$'  => __( '$1,500 – $5,000', 'benders-hub' ),
			'$$$$$' => __( '$5,000+', 'benders-hub' ),
		),
	),
);
?>
<section class="bdh-section bdh-tool bdh-quiz-section" id="bdh-quiz" data-bdh-section="quiz">
	<div class="bdh-container">
		<header class="bdh-section-head bdh-section-head-center">
			<span class="bdh-section-tag"><?php esc_html_e( 'Tool 1 of 8', 'benders-hub' ); ?></span>
			<h2 class="bdh-section-title"><?php esc_html_e( 'Tube Bender Match Quiz', 'benders-hub' ); ?></h2>
			<p class="bdh-section-sub"><?php esc_html_e( '9 quick questions. We recommend a bender category, tooling setup, material guidance, and a starter Amazon CTA. Hidden results until completion.', 'benders-hub' ); ?></p>
		</header>

		<div class="bdh-tool-shell bdh-quiz-shell" data-bdh-quiz>

			<div class="bdh-quiz-intro" data-bdh-quiz-screen="intro">
				<div class="bdh-quiz-intro-icon"><?php echo bdh_icon( 'tube' ); ?></div>
				<h3><?php esc_html_e( 'Ready to find your bender?', 'benders-hub' ); ?></h3>
				<p><?php esc_html_e( 'Takes under 90 seconds. Your answers stay in your browser — nothing tracked.', 'benders-hub' ); ?></p>
				<button class="bdh-btn bdh-btn-primary bdh-btn-lg" data-bdh-quiz-start>
					<?php esc_html_e( 'Start Quiz', 'benders-hub' ); ?>
					<?php echo bdh_icon( 'arrow' ); ?>
				</button>
			</div>

			<div class="bdh-quiz-runner" data-bdh-quiz-screen="runner" hidden>
				<div class="bdh-quiz-progress">
					<div class="bdh-quiz-progress-bar" data-bdh-quiz-progress></div>
					<span class="bdh-quiz-progress-text">
						<span data-bdh-quiz-step>1</span>/<span data-bdh-quiz-total><?php echo (int) count( $questions ); ?></span>
					</span>
				</div>

				<div class="bdh-quiz-questions" data-bdh-quiz-questions>
				<?php foreach ( $questions as $idx => $q ) : ?>
					<fieldset class="bdh-quiz-question" data-bdh-quiz-step-card="<?php echo (int) $idx; ?>" data-bdh-quiz-id="<?php echo esc_attr( $q['id'] ); ?>" <?php echo $idx === 0 ? '' : 'hidden'; ?>>
						<legend class="bdh-quiz-q"><?php echo esc_html( $q['q'] ); ?></legend>
						<div class="bdh-quiz-opts">
						<?php foreach ( $q['opts'] as $val => $label ) : ?>
							<label class="bdh-quiz-opt">
								<input type="radio" name="<?php echo esc_attr( $q['id'] ); ?>" value="<?php echo esc_attr( $val ); ?>">
								<span class="bdh-quiz-opt-box">
									<span class="bdh-quiz-opt-radio"></span>
									<span class="bdh-quiz-opt-label"><?php echo esc_html( $label ); ?></span>
								</span>
							</label>
						<?php endforeach; ?>
						</div>
					</fieldset>
				<?php endforeach; ?>
				</div>

				<div class="bdh-quiz-controls">
					<button class="bdh-btn bdh-btn-ghost" data-bdh-quiz-back disabled>
						<?php esc_html_e( 'Back', 'benders-hub' ); ?>
					</button>
					<button class="bdh-btn bdh-btn-primary" data-bdh-quiz-next>
						<?php esc_html_e( 'Next', 'benders-hub' ); ?>
						<?php echo bdh_icon( 'arrow' ); ?>
					</button>
				</div>
			</div>

			<div class="bdh-quiz-result" data-bdh-quiz-screen="result" hidden>
				<div class="bdh-quiz-result-header">
					<span class="bdh-quiz-result-badge"><?php esc_html_e( "Your Match", 'benders-hub' ); ?></span>
					<h3 class="bdh-quiz-result-title" data-bdh-quiz-result-title></h3>
					<p class="bdh-quiz-result-summary" data-bdh-quiz-result-summary></p>
				</div>
				<div class="bdh-quiz-result-body">
					<div class="bdh-quiz-result-col">
						<h4><?php esc_html_e( 'Recommended Bender Category', 'benders-hub' ); ?></h4>
						<p data-bdh-quiz-result-category></p>
					</div>
					<div class="bdh-quiz-result-col">
						<h4><?php esc_html_e( 'Tooling & Die Setup', 'benders-hub' ); ?></h4>
						<p data-bdh-quiz-result-tooling></p>
					</div>
					<div class="bdh-quiz-result-col">
						<h4><?php esc_html_e( 'Material Compatibility', 'benders-hub' ); ?></h4>
						<p data-bdh-quiz-result-material></p>
					</div>
					<div class="bdh-quiz-result-col">
						<h4><?php esc_html_e( 'Safety Notes', 'benders-hub' ); ?></h4>
						<p data-bdh-quiz-result-safety></p>
					</div>
				</div>
				<div class="bdh-quiz-result-cta">
					<a class="bdh-btn bdh-btn-primary bdh-btn-lg" data-bdh-quiz-amazon href="#" rel="nofollow sponsored noopener" target="_blank">
						<?php esc_html_e( 'View Recommended Benders on Amazon', 'benders-hub' ); ?>
					</a>
					<button type="button" class="bdh-btn bdh-btn-ghost" data-bdh-quiz-retake>
						<?php esc_html_e( 'Retake Quiz', 'benders-hub' ); ?>
					</button>
					<button type="button" class="bdh-btn bdh-btn-ghost" data-bdh-quiz-share>
						<?php esc_html_e( 'Share Result', 'benders-hub' ); ?>
					</button>
				</div>
				<aside class="bdh-quiz-result-related">
					<h5><?php esc_html_e( 'Related fabrication guides', 'benders-hub' ); ?></h5>
					<ul data-bdh-quiz-related>
						<li><a href="#bdh-calculator"><?php esc_html_e( 'Bend Radius Calculator →', 'benders-hub' ); ?></a></li>
						<li><a href="#bdh-visualizer"><?php esc_html_e( 'Tube Geometry Visualizer →', 'benders-hub' ); ?></a></li>
						<li><a href="#bdh-database"><?php esc_html_e( 'Browse Equipment Database →', 'benders-hub' ); ?></a></li>
					</ul>
				</aside>
			</div>

		</div>
	</div>
</section>
