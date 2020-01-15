<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div id="quiz">
	<div class="content reduce-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card question">
                        
                        <?php if($title = get_field('quiz_title')): ?>
                        <h1><?php echo $title; ?></h1>
                        <?php else: ?>
                        <h1><?php the_title(); ?></h1>
                        <?php endif; ?>
                        
                        <?php if($image = get_field('quiz_image')): ?>
                        <img class="featured-image" src="<?php echo $image['sizes']['quiz_image']; ?>" alt="">
                        <?php endif; ?>
                        
                        <div class="quiz-meta">

                            <?php if($author = get_field('quiz_author')): ?>
                            <h3 class="quiz-author">Quiz Creator: <span><?php echo $author['display_name']; ?></span></h3>
                            <?php endif; ?>
                            
                            <?php if($description = get_field('quiz_description')): ?>
                            <div class="quiz-description">
                                <?php echo $description; ?>
                            </div>
                            <?php endif; ?>

                        </div>

                        <div class="buttons center">
                            <a href="#start-quiz" class="button large ib purple">Start Quiz</a>
                        </div>
                    </div>
                    
                    <?php if(have_rows('questions')): ?>
                        <?php while(have_rows('questions')): the_row(); ?>
                        <div class="card question">
                            <h2>Question 2</h2>
                            <h3>What is the first question of the quiz?</h3>
                            <img src="<?php bloginfo('template_directory'); ?>/img/demo/9.jpg" alt="">
                            <?php echo $description; ?>

                            <div class="answers">
                                <a href="#" class="button b offwhite selected">First option for question one</a>
                                <a href="#" class="button b offwhite">First option for question one</a>
                                <a href="#" class="button b offwhite">First option for question one</a>
                                <a href="#" class="button b offwhite">First option for question one</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php endif; ?>

                    <div class="buttons center">
                        <a href="#" class="button large ib purple">Next Page</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="sidebar">
                        <h2 class="section-header">More Quizzes</h2>
                        <div class="card panel">
                            <img 
                                src="<?php bloginfo('template_directory'); ?>/img/demo/2.jpg"
                                alt="">
                            <h3>This is a really long quiz that we're going to have to figure out quiz</h3>
                            <a class="button large" href="#">Start Quiz</a>
                        </div>
                        <div class="card panel">
                            <img 
                                src="<?php bloginfo('template_directory'); ?>/img/demo/2.jpg"
                                alt="">
                            <h3>This is a really long quiz that we're going to have to figure out quiz</h3>
                            <a class="button large" href="#">Start Quiz</a>
                        </div>
                        <div class="card panel">
                            <img 
                                src="<?php bloginfo('template_directory'); ?>/img/demo/2.jpg"
                                alt="">
                            <h3>This is a really long quiz that we're going to have to figure out quiz</h3>
                            <a class="button large" href="#">Start Quiz</a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
		<?php cfct_loop(); ?>
    </div>
</div>

<?php get_footer(); ?>
