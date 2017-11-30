<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }
extract($data);
?>

<div id="comment-<?php comment_ID(); ?>" class="comment">

	<!-- Inform users that their comment is awaiting moderation while it is not approved yet -->
	<?php if ($comment->comment_approved == '0') {
        _e('Your comment is awaiting moderation.', 'carrington-jam');
	}?>
    
    <!-- User avatar - this can be disabled by WordPress settings -->
    <?php echo '<div class="avatar">'.get_avatar($comment, 54).'</div>'; ?>
    
    <!-- Shows who the comment is by and what the core content is -->
    <div class="comment-content">
        <div class="author-link">
            <?php comment_author_link(); ?>
        </div>
        <div class="text">
            <?php comment_text(); ?>
        </div>
    </div>
    
    <!-- Additional comment elements users may want -->
    <div class="comment-meta">
        <div class="date">
			<?php comment_date(); ?>
    	</div>
        <div class="permalink">
			<?php echo '<a href="'.htmlspecialchars(get_comment_link( $comment->comment_ID )).'">', comment_time(), '</a>'; ?>
        </div>
        <div class="reply-link">
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])), $comment, $post); ?>
        </div>
        <div class="edit-link">
			<?php edit_comment_link(__('Edit This', 'carrington-jam'), '', ''); ?>
        </div>
    </div>
    
</div>