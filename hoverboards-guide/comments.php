<?php
/**
 * Comments template.
 */
if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$count = get_comments_number();
			printf( esc_html( _n( '%s comment', '%s comments', $count, 'hoverboards-guide' ) ), esc_html( number_format_i18n( $count ) ) );
			?>
		</h2>

		<ol class="comment-list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 44 ) ); ?>
		</ol>

		<?php the_comments_navigation( array( 'prev_text' => '← ' . esc_html__( 'Older comments', 'hoverboards-guide' ), 'next_text' => esc_html__( 'Newer comments', 'hoverboards-guide' ) . ' →' ) ); ?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'hoverboards-guide' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form( array(
		'title_reply'          => __( 'Join the conversation', 'hoverboards-guide' ),
		'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h2>',
		'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Ridden one of these? Share your real-world range, issues or tips — it helps other riders.', 'hoverboards-guide' ) . '</p>',
		'class_submit'         => 'btn btn--accent',
		'label_submit'         => __( 'Post comment', 'hoverboards-guide' ),
	) );
	?>
</section>
