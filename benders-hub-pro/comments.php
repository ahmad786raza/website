<?php
/**
 * Comments.
 *
 * @package BendersHub
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( post_password_required() ) { return; }
?>
<section id="comments" class="bdh-comments" aria-labelledby="bdh-comments-title">
	<?php if ( have_comments() ) : ?>
		<h2 id="bdh-comments-title" class="bdh-section-title">
			<?php
			$count = get_comments_number();
			/* translators: %d: number of comments */
			printf( esc_html( _n( '%d comment', '%d comments', $count, 'benders-hub' ) ), (int) $count );
			?>
		</h2>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 40 ) ); ?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="bdh-note"><?php esc_html_e( 'Comments are closed.', 'benders-hub' ); ?></p>
	<?php endif; ?>
	<?php comment_form( array( 'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">', 'title_reply_after' => '</h2>' ) ); ?>
</section>
