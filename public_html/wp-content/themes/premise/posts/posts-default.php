<?php

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<section id="search-results">
	<section class="content">
		<?php cfct_loop(); ?>
        <?php cfct_misc('nav-posts'); ?>
    </section>
    <?php get_sidebar(); ?>
</section>

<?php get_footer(); ?>