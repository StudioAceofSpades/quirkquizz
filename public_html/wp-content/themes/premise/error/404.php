<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<section id="error" class="page feed">
    <section class="hero">
        <?php include(get_stylesheet_directory() . "/parts/hero.php"); ?>
    </section>
</section>
<?php get_footer(); ?>
