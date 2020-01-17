<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $post;

$quiz_id    = $_GET['quiz_id'];
$result_id  = $_GET['result'];
$error      = false;

if(!$quiz_id || !$result_id) {
    $error = true;
}

get_header(); ?>

<div id="results">
	<div class="content reduce-padding">
        <div class="container">
            <div class="row">
            <?php if($error): ?>
                <div class="col-lg-8">
                    <div class="card question">
                        <h3 class="result-header">Uhoh. Looks like something went wrong.</h3>
                        <h1>You've somehow reached this page by accident</h1>
                        <div class="quiz-description">
                            <p><br></p>
                        </div>
                        <div class="buttons center">
                            <a href="<?php bloginfo('url'); ?>" class="button large ib purple">Head back to the homepage</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-lg-8">
                    <div class="card question">

                        <h3 class="result-header">Quiz Results</h3>
                        
                        <?php
                        if(have_rows('results', $quiz_id)):
                            $counter = 0;
                            while(have_rows('results', $quiz_id)) : 
                                the_row();
                                $counter++;
                                if($counter == $result_id) :
                                ?>
                                
                                <?php if($title = get_sub_field('result_title')): ?>
                                <h1><?php echo $title; ?></h1>
                                <?php endif; ?>
                                
                                <?php if($image = get_sub_field('result_image')): ?>
                                <img 
                                    class="featured-image" 
                                    src="<?php echo $image['sizes']['quiz_image']; ?>" 
                                    alt="<?php echo $image['alt']; ?>">
                                <?php endif; ?>
                                
                                <?php if($description = get_sub_field('result_description')): ?>
                                <div class="quiz-meta">
                                    <div class="quiz-description">
                                        <?php echo $description; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="buttons center">
                                    <a href="#start-quiz" class="button large ib purple">Share Results</a>
                                    <a href="#start-quiz" class="button large ib purple">Random Quiz</a>
                                </div>

                                <?php
                                endif;
                            endwhile;
                        endif;
                        ?>

                    </div>
                </div>
            <?php endif; ?>
                <div class="col-lg-4">
                    <?php include(get_template_directory() . '/parts/aside.php'); ?>
                </div>
            </div>
        </div>
		<?php cfct_loop(); ?>
    </div>
</div>

<?php get_footer(); ?>
