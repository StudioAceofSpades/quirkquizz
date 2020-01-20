<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $post;

//Setting up our current page and next page.
if(isset($_GET['page-id'])) {
    $current_page = $_GET['page-id'];
    if($current_page == 0){
        $current_page = 1;
    }
} else {
    $current_page = 1;
}
$next_page = $current_page+1;

//creating an array, index is page, value is num of questions per page
if(have_rows('quiz_pages','options')){
    $counter = 1;
    $pagemeta = array();
    while(have_rows('quiz_pages','options')){
        the_row();
        $pagemeta[$counter] = get_sub_field('numbers_of_questions');
        $counter++;
    }
}

//setting up our question offset
$question_offset = 0;
foreach($pagemeta as $page=>$num_questions){
    if($page < $current_page){
        $question_offset += $num_questions;
    }
}

$question_limit = $pagemeta[$current_page]+$question_offset;

$is_last_page = false;
if($current_page == count(get_field('quiz_pages','options'))) {
    $is_last_page = true;
}

get_header(); ?>

<div id="quiz" data-quiz-id="<?php echo $post->ID; ?>" data-curr-page="<?php echo $current_page; ?>" data-num-results="<?php echo count(get_field('results')); ?>">
	<div class="content reduce-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php if($current_page == 1): ?>
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
                    <?php endif; ?>
                    
                    <?php if(have_rows('questions')): ?>
                        <?php $current_question = 1; ?>
                        <?php while(have_rows('questions')): the_row(); ?>
                            <?php if(($question_offset < $current_question) && (($current_question <= $question_limit) || $is_last_page)): ?>
                                <div class="card question">
                                    <h2>Question <?php echo $current_question; ?></h2>
                                    <h3><?php the_sub_field('question') ?></h3>
                                    <img src="<?php echo get_sub_field('question_image')['sizes']['quiz_image']; ?>" alt="">
                                    <?php if($question_description = get_sub_field('question_description')): ?>
                                        <?php echo $question_description; ?>
                                    <?php endif; ?>

                                    <div class="answers">
                                        <?php if(get_sub_field('answer_type') == 'text'): ?>
                                            <?php if(have_rows('answers')): $current_answer = 1; ?>
                                                <?php while(have_rows('answers')): the_row(); ?>
                                                    <a data-answer-id="<?php echo get_sub_field_object('answer')['name']; ?>" href="#" class="button b offwhite"><?php the_sub_field('answer'); ?></a>
                                                    <?php $current_answer++; ?>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        <?php elseif(get_sub_field('answer_type') == 'image'): ?>
                                            <?php if(have_rows('image_answers')): $current_answer = 1; ?>
                                                <?php while(have_rows('image_answers')): the_row(); ?>
                                                    <div data-answer-id="<?php print_r(get_sub_field_object('answer')['name']); ?>" class="button ib image offwhite">
                                                        <div class="image-container" style="background-image: url(<?php echo get_sub_field('answer')['sizes']['image_answer']; ?>);">
                                                        </div>
                                                    </div>
                                                    <?php $current_answer++; ?>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; $current_question++; ?>
                        <?php endwhile; ?>
                        <?php
                            //If we ran out of questions before the end of the page we know its the last page. 
                            if($current_question-1 <= $question_limit) $is_last_page = true;
                        ?>
                    <?php endif; ?>

                    <div class="buttons center">
                        <?php if(!$is_last_page) : ?>
                        <a href="<?php echo add_query_arg( 'page-id', $next_page, $_SERVER['REQUEST_URI'] );?>" class="button large ib purple">Next Page</a>
                        <?php else: ?>
                        <a href="<?php bloginfo('url'); ?>/your-results/?" class="button large ib purple get-results">Get Results!</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="sidebar">
                        <?php include(get_template_directory() . '/parts/aside.php'); ?>
                    </aside>
                </div>
            </div>
        </div>
		<?php cfct_loop(); ?>
    </div>
</div>



<?php get_footer(); ?>
