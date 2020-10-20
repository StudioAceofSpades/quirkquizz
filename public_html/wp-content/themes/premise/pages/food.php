<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div class="food-page">
    <h1>My favorite food is <?php the_field("food_name"); ?></h1>
    <p><img src="<?php echo get_field('food_image')['sizes']['food_image']; ?>"></p>
    <p><h2>Why do i like it? Is because... <br> <?php the_field("reason");?></h2></p>
    <p>My other favorite foods are :</p>
    <ul>
        <?php if( have_rows('food_list') ): ?>
           <?php while( have_rows('food_list') ) : the_row(); ?>
                <li><?php the_sub_field('food')  ?></li>
            <?php endwhile; ?>
       <?php endif; ?>
    </ul>
</div>

<?php get_footer(); ?>