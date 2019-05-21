<?php
// The template for displaying Comments.

$rigid_commenter = wp_get_current_commenter();
$rigid_req = get_option('require_name_email');
$rigid_aria_req = ( $rigid_req ? " aria-required='true'" : '' );
?>
<div class="clear"></div>
<?php if (post_password_required()) : ?>
	<div id="comments">
		<p class="nopassword"><?php esc_html_e('This post is password protected. Enter the password to view any comments.', 'rigid'); ?></p>
	</div><!-- #comments -->
	<?php
	/* Stop the rest of comments.php from being processed,
	 * but don't kill the script entirely -- we still have
	 * to fully load the template.
	 */
	return;
	?>
<?php endif; ?>

<?php // You can start editing here -- including this comment!  ?>

<div id="comments">
	<?php if (have_comments()) : ?>
		<h3 class="heading-title">
			<?php
			printf(_n('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'rigid'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
			?>
		</h3>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>
			<div id="comment-nav-above">
				<div class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'rigid')); ?></div>
				<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'rigid')); ?></div>
			</div>
		<?php endif; // check for comment navigation  ?>

		<ul class="commentlist">
			<?php
			/* Loop through and list the comments. Tell wp_list_comments()
			 * to use twentyeleven_comment() to format the comments.
			 * If you want to overload this in a child theme then you can
			 * define twentyeleven_comment() and that will be used instead.
			 * See twentyeleven_comment() in twentyeleven/functions.php for more.
			 */
			wp_list_comments(array('callback' => 'rigid_comment'));
			?>
		</ul>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>
			<div id="comment-nav-below">
				<div class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'rigid')); ?></div>
				<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'rigid')); ?></div>
			</div>
		<?php endif; // check for comment navigation  ?>

		<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if (!comments_open() && get_comments_number()) :
			?>
			<p class="nocomments"><?php esc_html_e('Comments are closed.', 'rigid'); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments()  ?>

	<?php
	$rigid_comments_args = array(
			'id_form' => 'commentsForm',
			'id_submit' => 'submit_btn',
			'label_submit' => esc_html__('Post Comment', 'rigid'),
			'comment_field' => '<label for="comment">* ' . _x('Comment', 'noun', 'rigid') . ':</label><textarea id="comment" name="comment" cols="60" rows="10" aria-required="true" class="textarea"></textarea>',
			'fields' => apply_filters('comment_form_default_fields', array(
					'author' => '<label for="author">' . ( $rigid_req ? '<span class="required">* </span>' : '' ) . esc_html__('Name', 'rigid') . ':</label> ' . '<input id="author" class="text-input" name="author" type="text" value="' . esc_attr($rigid_commenter['comment_author']) . '" size="30"' . $rigid_aria_req . ' />',
					'email' => '<label for="email">' . ( $rigid_req ? '<span class="required">* </span>' : '' ) . esc_html__('Email', 'rigid') . ':</label> ' . '<input id="email" class="text-input" name="email" type="text" value="' . esc_attr($rigid_commenter['comment_author_email']) . '" size="30"' . $rigid_aria_req . ' />')
			)
	);
	?>
	<?php comment_form($rigid_comments_args); ?>

</div><!-- #comments -->
