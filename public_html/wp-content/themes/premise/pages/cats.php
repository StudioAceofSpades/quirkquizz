<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div class="cat-page">
   <h1>My cat's name is: <?php echo get_field('cat_name'); ?></h1>
   <img src="<?php echo get_field('cat_picture')['sizes']['cat_image']; ?>" alt="">
   <ul>
      <?php if(have_rows('favorite_foods')): ?>
         <?php while(have_rows('favorite_foods')): the_row(); ?>
            <li><?php the_sub_field('food'); ?></li>
         <?php endwhile; ?>   
      <?php endif; ?>   
   </ul>

</div>

<?php get_footer(); ?>
