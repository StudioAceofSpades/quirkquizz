<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div id="quiz">
	<div class="content close">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="card start">
                        
                        <?php if($title = get_field('quiz_title')): ?>
                        <h1><?php echo $title; ?></h1>
                        <?php else: ?>
                        <h1><?php the_title(); ?></h1>
                        <?php endif; ?>
                        
                        <?php if($image = get_field('quiz_image')): ?>
                        <img class="featured-image" src="<?php echo $image['sizes']['quiz_image']; ?>" alt="">
                        <?php endif; ?>

                        <div class="quiz-meta">
                            <h3>Quiz Creator: <?php the_author(); ?></h3>

                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <aside class="sidebar">
                    </aside>
                </div>
            </div>
        </div>
		<?php cfct_loop(); ?>
    </div>
</div>

<?php get_footer(); ?>
