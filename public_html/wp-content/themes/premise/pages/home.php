<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

$latest = false;

get_header(); ?>

<div id="home">
    <div class="hero">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1>Our Newest Quiz</h1>
                </div>
            </div>
            <div class="row">
                
                <?php
                $args = array(
                    'posts_per_page'    => 1,
                    'orderby'           => 'post_date',
                    'order'             => 'DESC',
                    'post_status'       => 'publish',
                    'post_type'         => 'quiz'
                );
                $posts = get_posts($args);

                if(!empty($posts)):
                    foreach($posts as $post) : setup_postdata($post); $latest = $post->ID; ?>
                
                <?php if($image = get_field('quiz_image')): ?>
                <div class="col-md-5 align-self-center">
                    <img 
                        class="featured-image" 
                        src="<?php echo $image['sizes']['quiz_image']; ?>" 
                        alt="<?php echo $image['alt']; ?>">
                </div>
                <div class="col-md-7 align-self-center">
                <?php else: ?>
                <div class="col">
                <?php endif; ?>
                    <div class="featured-quiz-content">
                        <h3><?php the_title(); ?></h3>

                        <?php if($description = get_field('quiz_short_description')): ?>
                            <p><?php echo $description; ?></p>
                        <?php endif; ?>

                        <a class="button large ib" href="<?php the_permalink(); ?>">Start Quiz</a>
                    </div>
                </div>
                <?php 
                    endforeach;
                endif; 
                wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
	<div class="content">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="section-header">Trending Quizzes</h2>
                </div>
            </div>
            <div class="row">

                <?php 
                $args = array(
                    'posts_per_page'    => 6,
                    'post_type'         => 'quiz',
                    'meta_key'          => 'views',
                    'orderby'           => 'views',
                    'order'             => 'DESC',
                    'post__not_in'      => array($latest),
                );
                $posts = get_posts($args);
                if(!empty($posts)):
                    foreach($posts as $post) : setup_postdata($post);
                    ?>
                    <div class="col-lg-6 col-xl-4">
                        <?php cfct_excerpt(); ?>
                    </div>
                    <?php
                    endforeach;
                endif;
                wp_reset_postdata();
                ?>

            </div>
            <div class="row">
                <div class="col">
                    <h2 class="section-header">Latest Quizzes</h2>
                </div>
            </div>
            <div class="row arena">

                <?php 
                $args = array(
                    'posts_per_page'    => 9,
                    'post_type'         => 'quiz',
                );
                $posts = get_posts($args);
                if(!empty($posts)):
                    foreach($posts as $post) : setup_postdata($post);
                    ?>
                    <div class="col-lg-6 col-xl-4">
                        <?php cfct_excerpt(); ?>
                    </div>
                    <?php
                    endforeach;
                endif;
                wp_reset_postdata();
                ?>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="buttons center">
                        <a class="button load-trigger large ib purple" href="#">Load More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
