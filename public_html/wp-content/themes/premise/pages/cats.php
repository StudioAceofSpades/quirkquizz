<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div class="cat-page">
    <h1>My cats name is: <?php the_field('cat_name'); ?> </h1>
    <?php var_dump(get_field('cat_picture')['sizes']['cat_image']); ?>

</div>

<?php get_footer(); ?>
