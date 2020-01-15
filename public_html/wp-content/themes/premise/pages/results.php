<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div id="results">
	<div class="content reduce-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card question">

                        <h3 class="result-header">Quiz Results</h3>
                        
                        <h1>This is the result you got after taking the quiz</h1>
                        
                        <img class="featured-image" src="<?php bloginfo('template_directory'); ?>/img/demo/7.jpg" alt="">
                        
                        <div class="quiz-meta">

                            <div class="quiz-description">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
                            </div>

                        </div>

                        <div class="buttons center">
                            <a href="#start-quiz" class="button large ib purple">Share Results</a>
                            <a href="#start-quiz" class="button large ib purple">Random Quiz</a>
                        </div>
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
