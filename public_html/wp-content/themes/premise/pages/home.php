<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<section id="home">
	<div class="content">
		<?php cfct_loop(); ?>
    </div>
</section>

<?php get_footer(); ?>