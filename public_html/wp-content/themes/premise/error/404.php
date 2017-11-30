<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<section id="error">
	<div class="content">
		<?php cfct_form('search'); ?>
	</section>
</div>
<?php get_footer(); ?>