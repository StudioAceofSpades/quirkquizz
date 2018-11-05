<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<?php include(locate_template('parts/hero.php')); ?>

<div class="subpage">
    <div class="container">
        <?php if(have_rows('content_content')):
            while(have_rows('content_content')):the_row();
                include(get_stylesheet_directory() . "/parts/cms.php");
            endwhile;
        endif ?>
    </div>
</div>

<?php get_footer(); ?>
