<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); } ?>

<li class="comment-list-wrapper" id="li-comment-<?php comment_ID() ?>">
	<section class="comment-wrapper" id="div-comment-<?php comment_ID(); ?>">
<?php cfct_comment($data); ?>
	</section>
    
<?php // Dropped </li> is intentional: WordPress figures out where to place the </li> so it can nest comment lists. ?>