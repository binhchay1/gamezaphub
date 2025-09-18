<?php
/**
 * The template for displaying the comments.
 *
 * This contains both the comments and the comment form.
 *
 * @package Schema
 */

// Do not delete these lines.
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) ) { // PHPCS:ignore
	die( esc_html__( 'Please do not load this page directly. Thanks!', 'schema' ) );
}

if ( post_password_required() ) {
	?>
	<p class="nocomments"><?php esc_html_e( 'This post is password protected. Enter the password to view comments.', 'schema' ); ?></p>
	<?php
	return;
}

// You can start editing here.
if ( have_comments() ) :
	?>
	<div id="comments">
		<h4 class="total-comments"><?php comments_number( __( 'No Responses', 'schema' ), __( 'One Response', 'schema' ), __( '% Comments', 'schema' ) ); ?></h4>
		<ol class="commentlist">
			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through.
				?>
				<div class="navigation">
					<div class="alignleft"><?php previous_comments_link(); ?></div>
					<div class="alignright"><?php next_comments_link(); ?></div>
				</div>
				<?php
			}

			wp_list_comments( 'callback=mts_comments' );

			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through.
				?>
				<div class="navigation">
					<div class="alignleft"><?php previous_comments_link(); ?></div>
					<div class="alignright"><?php next_comments_link(); ?></div>
				</div>
				<?php
			}
			?>
		</ol>
	</div>
<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<div id="commentsAdd">
		<div id="respond" class="box m-t-6">
			<?php
			// Declare Vars.
			$comment_send     = esc_html__( 'Post Comment', 'schema' );
			$comment_reply    = esc_html__( 'Leave a Reply', 'schema' );
			$comment_reply_to = esc_html__( 'Reply', 'schema' );
			$comment_author   = esc_html__( 'Name*', 'schema' );
			$comment_email    = esc_html__( 'Email*', 'schema' );
			$comment_body     = esc_html__( 'Comment Text*', 'schema' );
			$comment_url      = esc_html__( 'Website', 'schema' );
			$comment_cookies  = esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'schema' );
			$comment_cancel   = esc_html__( 'Cancel Reply', 'schema' );
			$comments_args    = [
				// Define Fields.
				'fields'               => [
					// Author field.
					'author'  => '<p class="comment-form-author"><input id="author" name="author" aria-required="true" placeholder="' . $comment_author . '" size="35"></input></p>',
					// Email Field.
					'email'   => '<p class="comment-form-email"><input id="email" name="email" aria-required="true" placeholder="' . $comment_email . '" size="35"></input></p>',
					// URL Field.
					'url'     => '<p class="comment-form-url"><input id="url" name="url" placeholder="' . $comment_url . '" size="35"></input></p>',
					// Cookies.
					//'cookies' => '<p class="comment-form-cookies-consent"><input type="checkbox" required><label for="wp-comment-cookies-consent">' . $comment_cookies . '</label></p>',
				],
				// Change the title of send button.
				'label_submit'         => $comment_send,
				// Change the title of the reply section.
				'title_reply'          => $comment_reply,
				// Change the title of the reply section.
				'title_reply_to'       => $comment_reply_to,
				// Cancel Reply Text.
				'cancel_reply_link'    => $comment_cancel,
				// Redefine your own textarea (the comment body).
				'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="6" aria-required="true" placeholder="' . $comment_body . '"></textarea></p>',
				// Message Before Comment.
				'comment_notes_before' => '',
				// Remove "Text or HTML to be displayed after the set of comment fields".
				'comment_notes_after'  => '',
				// Submit Button ID.
				'id_submit'            => 'submit',
			];
			comment_form( $comments_args );
			?>
		</div>

	</div>
<?php
endif;
