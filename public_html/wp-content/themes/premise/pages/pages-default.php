<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div id="default-page">
	<div class="content">
		<?php cfct_loop(); ?>
    </div>
</div>
<?php get_footer(); ?>
