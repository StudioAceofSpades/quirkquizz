<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div class="food-listing">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                        $args = array(
                            'posts_per_page' => 2,
                            'post_type' => 'food',
                        );
                        $posts = get_posts($args);
                    ?>
                    <?php if(!empty($posts)): ?>
                        <?php foreach($posts as $post): setup_postdata($post);?>
                            <article class="card panel">
                                <img src="<?php echo get_field('food_image')['sizes']['food_image']; ?>">
                                <h3><?php echo get_field('food_name',$id); ?></h3>
                                <a class="button large" href="<?php echo get_page_link($id); ?>">View Food</a>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; wp_reset_postdata(); ?>

            </div>
         </div>
     </div>
</div>

<?php get_footer(); ?>