<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $post, $wp_query, $comments, $comment;

if (have_comments() || comments_open()) { ?>

<h2 class="comments-header"><?php comments_number('', __('One Response', 'carrington-jam'), __('% Responses', 'carrington-jam')); ?></h2>

<p class="stay-in-touch-with-comments">
	<?php printf(__('Stay in touch with the conversation, subscribe to the <a class="feed" rel="alternate" href="%s"><acronym title="Really Simple Syndication">RSS</acronym> feed for comments on this post</a>.', 'carrington-jam'), get_post_comments_feed_link($post->ID, '')); ?>
</p>

<?php 

	if (!post_password_required()) {
		$comments = $wp_query->comments;
		$comment_count = 0;
		$ping_count = 0;
		foreach ($comments as $comment) {
			if (get_comment_type() == 'comment') {
				$comment_count++;
			}
			else {
				$ping_count++;
			}
		}
		if ($comment_count) {
			echo '<ol class="comments">', wp_list_comments('type=comment&callback=cfct_threaded_comment'), '</ol>';
			
			previous_comments_link();
			next_comments_link();
		}
	}
	cfct_form('comment');
}

?>