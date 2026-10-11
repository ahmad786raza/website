<?php
/**
 * Full-width newsletter banner with photo.
 */
?>
<section class="nl-banner section" aria-label="<?php esc_attr_e( 'Newsletter', 'hoverboards-guide' ); ?>">
	<div class="container">
		<div class="nl-banner__card">
			<div class="nl-banner__media" aria-hidden="true">
				<?php hbg_image( 'newsletter', array( 'alt' => '', 'sizes' => '(max-width: 900px) 100vw, 520px' ) ); ?>
			</div>
			<div class="nl-banner__body">
				<?php hbg_newsletter_form( array( 'variant' => 'banner', 'source' => 'home-banner' ) ); ?>
			</div>
		</div>
	</div>
</section>
