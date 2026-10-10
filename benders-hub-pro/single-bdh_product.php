<?php
/**
 * Single Equipment — affiliate-first layout.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>

<?php while ( have_posts() ) : the_post();
	$post_id = get_the_ID();
	$kw       = get_post_meta( $post_id, '_bdh_amazon_keyword', true );
	$kw       = $kw ?: get_the_title();
	$amazon   = get_post_meta( $post_id, '_bdh_amazon_url', true );
	$score    = (float) get_post_meta( $post_id, '_bdh_editor_score', true );
	$capacity = get_post_meta( $post_id, '_bdh_capacity', true );
	$tonnage  = get_post_meta( $post_id, '_bdh_tonnage', true );
	$precision= get_post_meta( $post_id, '_bdh_precision', true );
	$workshop = get_post_meta( $post_id, '_bdh_workshop_fit', true );
	$tubing   = get_post_meta( $post_id, '_bdh_tubing_compat', true );
	$beginner = get_post_meta( $post_id, '_bdh_beginner', true );
	$price    = get_post_meta( $post_id, '_bdh_price_range', true );
	$use_case = get_post_meta( $post_id, '_bdh_use_case', true );
	$pros     = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $post_id, '_bdh_pros', true ) ) ) );
	$cons     = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $post_id, '_bdh_cons', true ) ) ) );
	$acc      = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $post_id, '_bdh_accessories', true ) ) ) );
?>

<section class="bdh-section" style="padding-top:var(--bdh-space-6);padding-bottom:var(--bdh-space-5);border-bottom:1px solid var(--bdh-border);">
	<div class="bdh-container">
		<nav style="font-size:0.85rem;color:var(--bdh-text-soft);margin-bottom:var(--bdh-space-4);">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:var(--bdh-text-soft);"><?php esc_html_e( 'Home', 'benders-hub' ); ?></a>
			<span style="margin:0 8px;">/</span>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'bdh_product' ) ); ?>" style="color:var(--bdh-text-soft);"><?php esc_html_e( 'Equipment', 'benders-hub' ); ?></a>
			<span style="margin:0 8px;">/</span>
			<span style="color:var(--bdh-amber);"><?php the_title(); ?></span>
		</nav>

		<div style="display:grid;grid-template-columns:1.1fr 1fr;gap:var(--bdh-space-6);align-items:start;" class="bdh-product-hero">
			<div>
				<span class="bdh-section-tag"><?php esc_html_e( 'Equipment Review', 'benders-hub' ); ?></span>
				<h1 class="bdh-section-title" style="margin-top:var(--bdh-space-3);"><?php the_title(); ?></h1>

				<div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin:var(--bdh-space-4) 0;">
					<?php if ( $score ) : ?>
						<span style="background:linear-gradient(135deg,var(--bdh-amber),var(--bdh-amber-deep));color:#111827;padding:8px 14px;border-radius:8px;font-family:var(--bdh-font-mono);font-weight:700;font-size:1.1rem;">
							<?php echo bdh_icon( 'star' ); ?> <?php echo esc_html( $score ); ?>/10
						</span>
					<?php endif; ?>
					<?php echo bdh_trust_badge( 'reviewed' ); ?>
					<?php echo bdh_trust_badge( 'tested' ); ?>
					<?php if ( $price ) : ?>
						<span class="bdh-badge"><?php echo esc_html( $price ); ?></span>
					<?php endif; ?>
				</div>

				<?php if ( $use_case || has_excerpt() ) : ?>
					<p style="font-size:1.1rem;color:var(--bdh-aluminum);"><?php echo esc_html( $use_case ?: get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:var(--bdh-space-5);">
					<?php
					if ( $amazon ) {
						printf( '<a href="%s" class="bdh-btn bdh-btn-primary bdh-btn-lg bdh-amazon-cta" rel="nofollow sponsored noopener" target="_blank">%s%s</a>',
							esc_url( $amazon ),
							'<span class="bdh-btn-label">' . esc_html__( 'Check Price on Amazon', 'benders-hub' ) . '</span>',
							'<span class="bdh-btn-icon">→</span>'
						);
					} else {
						bdh_amazon_button( $kw, __( 'Check Price on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-primary bdh-btn-lg' );
					}
					?>
					<a class="bdh-btn bdh-btn-ghost bdh-btn-lg" href="<?php echo esc_url( home_url( '/#bdh-comparison' ) ); ?>"><?php esc_html_e( 'Compare', 'benders-hub' ); ?></a>
				</div>
			</div>

			<aside class="bdh-tool-shell" style="padding:var(--bdh-space-5);">
				<?php if ( has_post_thumbnail() ) : ?>
					<div style="border-radius:var(--bdh-radius);overflow:hidden;margin-bottom:var(--bdh-space-4);">
						<?php the_post_thumbnail( 'bdh-product', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					</div>
				<?php else : ?>
					<div style="background:radial-gradient(400px 200px at 50% 50%, rgba(239,68,68,0.18), transparent),linear-gradient(135deg,#1f2937,#374151);height:300px;border-radius:var(--bdh-radius);display:grid;place-items:center;margin-bottom:var(--bdh-space-4);">
						<div style="color:var(--bdh-amber);"><?php echo bdh_icon( 'tube', 'bdh-icon-xl' ); ?></div>
					</div>
				<?php endif; ?>

				<h3 style="margin-top:0;"><?php esc_html_e( 'Quick Specs', 'benders-hub' ); ?></h3>
				<dl class="bdh-hero-spec-list">
					<?php if ( $capacity ) : ?><div><dt><?php esc_html_e( 'Capacity', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $capacity ); ?></dd></div><?php endif; ?>
					<?php if ( $tonnage )  : ?><div><dt><?php esc_html_e( 'Power', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $tonnage ); ?></dd></div><?php endif; ?>
					<?php if ( $precision ): ?><div><dt><?php esc_html_e( 'Precision', 'benders-hub' ); ?></dt><dd><?php echo esc_html( strtoupper( $precision ) ); ?></dd></div><?php endif; ?>
					<?php if ( $workshop ) : ?><div><dt><?php esc_html_e( 'Workshop', 'benders-hub' ); ?></dt><dd><?php echo esc_html( ucfirst( $workshop ) ); ?></dd></div><?php endif; ?>
					<?php if ( $tubing )   : ?><div><dt><?php esc_html_e( 'Tubing', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $tubing ); ?></dd></div><?php endif; ?>
					<?php if ( $beginner ) : ?><div><dt><?php esc_html_e( 'Beginner', 'benders-hub' ); ?></dt><dd><?php echo esc_html( $beginner === 'yes' ? __( 'Yes', 'benders-hub' ) : ( $beginner === 'partial' ? __( 'With training', 'benders-hub' ) : __( 'No', 'benders-hub' ) ) ); ?></dd></div><?php endif; ?>
				</dl>
			</aside>
		</div>
	</div>
</section>

<section class="bdh-section">
	<div class="bdh-container" style="max-width:1000px;">
		<div class="bdh-single-content">
			<?php the_content(); ?>
		</div>

		<?php if ( $pros || $cons ) : ?>
		<div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--bdh-space-4);margin-top:var(--bdh-space-6);">
			<?php if ( $pros ) : ?>
			<div class="bdh-tool-shell" style="padding:var(--bdh-space-5);">
				<h3 style="color:#6ee7b7;"><?php esc_html_e( 'Pros', 'benders-hub' ); ?></h3>
				<ul class="bdh-eq-pros" style="margin:0;">
					<?php foreach ( $pros as $p ) : ?><li><?php echo bdh_icon( 'check' ); ?> <?php echo esc_html( $p ); ?></li><?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php if ( $cons ) : ?>
			<div class="bdh-tool-shell" style="padding:var(--bdh-space-5);">
				<h3 style="color:#fca5a5;"><?php esc_html_e( 'Cons', 'benders-hub' ); ?></h3>
				<ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:6px;">
					<?php foreach ( $cons as $c ) : ?><li>− <?php echo esc_html( $c ); ?></li><?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( $acc ) : ?>
		<div class="bdh-tool-shell" style="padding:var(--bdh-space-5);margin-top:var(--bdh-space-5);">
			<h3><?php esc_html_e( 'Recommended Accessories', 'benders-hub' ); ?></h3>
			<ul style="list-style:none;padding:0;margin:0;display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:10px;">
				<?php foreach ( $acc as $a ) : ?>
					<li>
						<a href="<?php echo esc_url( bdh_amazon_url( $a ) ); ?>" rel="nofollow sponsored noopener" target="_blank" class="bdh-bundle-items" style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--bdh-bg-2);border:1px solid var(--bdh-border);border-radius:8px;color:var(--bdh-text);">
							<?php echo bdh_icon( 'check' ); ?>
							<span style="flex:1;"><?php echo esc_html( $a ); ?></span>
							<?php echo bdh_icon( 'arrow' ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>

		<div style="margin-top:var(--bdh-space-6);text-align:center;">
			<?php
			if ( $amazon ) {
				printf( '<a href="%s" class="bdh-btn bdh-btn-primary bdh-btn-lg" rel="nofollow sponsored noopener" target="_blank">%s</a>',
					esc_url( $amazon ),
					esc_html__( 'View on Amazon', 'benders-hub' )
				);
			} else {
				bdh_amazon_button( $kw, __( 'View on Amazon', 'benders-hub' ), 'bdh-btn bdh-btn-primary bdh-btn-lg' );
			}
			?>
		</div>
	</div>
</section>

<?php endwhile; ?>

<?php
get_template_part( 'template-parts/sections/comparison' );
get_template_part( 'template-parts/sections/faq' );
get_template_part( 'template-parts/sections/newsletter' );
get_footer();
