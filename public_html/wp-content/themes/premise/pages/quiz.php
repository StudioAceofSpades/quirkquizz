<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); } if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $post;

// Setting up our current page and next page.
if(isset($_GET['page-id'])) {
    $current_page = $_GET['page-id'];
    if($current_page == 0){
        $current_page = 1;
    }
} else {
    $current_page = 1;
}

$next_page = $current_page+1;
// Creating an array, index is page, value is num of questions per page
if(have_rows('quiz_pages','options')){
    $page_number = 1;
    $questions_per_page = array();
    while(have_rows('quiz_pages','options')){
        the_row();
        $questions_per_page[$page_number] = get_sub_field('numbers_of_questions');
        $page_number++;
    }
}

//setting up our question offset
$question_offset = 0;
foreach($questions_per_page as $page => $num_questions){
    if($page < $current_page){
        $question_offset += $num_questions;
    }
}

//retrieves the 'max number of questions' field from cms
$max_question_count = get_field('maximum_number_of_questions', 'options');

// If we have a max number of questions on and individual quiz,
// override the global max question count
if($per_quiz_max = get_field('max_questions')) {
    $max_question_count = $per_quiz_max;
}

// Sets highest number of questions possible to an int
$questions_per_page[] = 2147483647; // INT_MAX

// adding num of questions per page and offset together; sets min amt of questions shown to max_question_count value
$question_limit = min($questions_per_page[$current_page] + $question_offset, $max_question_count);
$is_last_page   = false;

if($current_page > count(get_field('quiz_pages','options'))) {
    $is_last_page = true;
}

$paid_quizad_enabled = get_field('page_enabled', 'options');
$allanswers = get_field('funnel_result_text');
get_header(); 
?>

<script>
    <?php print("window.possible_answers = ".json_encode($allanswers)).";" ?>
</script>

<input type="hidden" id="audiolink" value="<?php bloginfo('template_directory'); ?>/audio/coin.mp3">

<div id="quiz" data-quiz-id="<?php echo $post->ID; ?>" data-curr-page="<?php echo $current_page; ?>" data-num-results="<?php echo count(get_field('results')); ?>">
    <div id="coin-counter"><img src="<?php bloginfo('template_directory'); ?>/img/coin.svg"><div id="coin-total"></div></div>
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
                            <?php if(($question_offset < $current_question) && (($current_question <= $question_limit))): ?>                                          
                                        <div class="card question" <?php if($current_question == 1) echo 'id="start-quiz"'; ?>>
                                            <h2>Question <?php echo $current_question; ?></h2>
                                            <img src="<?php echo get_sub_field('question_image')['sizes']['quiz_image']; ?>" alt="">
                                            <h3><?php the_sub_field('question') ?></h3>
                                            <?php if($question_description = get_sub_field('question_description')): ?>
                                                <?php echo $question_description; ?>
                                            <?php endif; ?>
                                            <div class="ad-slot above-answers-ad">
                                                <!-- above_answers -->
                                                <ins class="adsbygoogle"
                                                    style="display:block"
                                                    data-ad-client="ca-pub-4411421854869090"
                                                    data-ad-slot="9359346398"
                                                    data-ad-format="auto"
                                                    data-full-width-responsive="true"></ins>
                                                <script>
                                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                                </script>
                                            </div>
                                            <div class="answers">
                                                <?php if(get_sub_field('answer_type') == 'text'): ?>
                                                    <?php if(have_rows('answers')): $current_answer = 1; ?>
                                                        <?php while(have_rows('answers')): the_row(); ?>
                                                            <a data-answer-id="<?php echo get_sub_field_object('answer')['name']; ?>" href="#" class="button b offwhite"><?php the_sub_field('answer'); ?><div class="coins-get"><i class="fas fa-plus"></i><img src="<?php bloginfo('template_directory'); ?>/img/coin.svg"><img src="<?php bloginfo('template_directory'); ?>/img/coin.svg"><img src="<?php bloginfo('template_directory'); ?>/img/coin.svg"></div></a>
                                                            <?php $current_answer++; ?>
                                                        <?php endwhile; ?>
                                                    <?php endif; ?>
                                                <?php elseif(get_sub_field('answer_type') == 'image'): ?>
                                                    <?php if(have_rows('image_answers')): $current_answer = 1; ?>
                                                        <?php while(have_rows('image_answers')): the_row(); ?>
                                                            <div data-answer-id="<?php print_r(get_sub_field_object('answer')['name']); ?>" class="button ib image offwhite">
                                                                <div class="coins-get"><i class="fas fa-plus"></i><img src="<?php bloginfo('template_directory'); ?>/img/coin.svg"><img src="<?php bloginfo('template_directory'); ?>/img/coin.svg"><img src="<?php bloginfo('template_directory'); ?>/img/coin.svg"></div>
                                                                <div class="image-container" style="background-image: url(<?php echo get_sub_field('answer')['sizes']['image_answer']; ?>);">
                                                                    <?php if($title = get_sub_field('title')): ?>
                                                                    <span class="title"><?php echo $title; ?></span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <?php $current_answer++; ?>
                                                            
                                                        <?php endwhile; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="ad-slot after-questions">
                                            <!-- after_questions -->
                                            <ins class="adsbygoogle"
                                                style="display:block"
                                                data-ad-client="ca-pub-4411421854869090"
                                                data-ad-slot="9848083946"
                                                data-ad-format="auto"
                                                data-full-width-responsive="true"></ins>
                                            <script>
                                                (adsbygoogle = window.adsbygoogle || []).push({});
                                            </script>
                                        </div>
                            <?php endif; $current_question++; 
                            if ($current_question - 1 == $max_question_count) {
                            break;
                            } ?>
                        <?php endwhile; ?>
                        <?php
                            // If we ran out of questions before the end of the page we know its the last page. 
                            if($current_question - 1 <= $question_limit) {
                                $is_last_page = true;
                            }
                         ?>
                    <?php endif; ?>
                    <div class="buttons center">
                        <?php if($current_page == 1 && $paid_quizad_enabled): ?>
                            <a href="<?php echo add_query_arg( 'page-id', 'paidquizad', $_SERVER['REQUEST_URI'] );?>" id="advance-button" class="button large ib purple next-page-btn">Next Page</a>
                        <?php elseif(!$is_last_page) : ?>
                            <a href="<?php echo add_query_arg( 'page-id', $next_page, $_SERVER['REQUEST_URI'] );?>" id="advance-button" class="button large ib purple next-page-btn">Next Page</a>
                        <?php else: ?>
                            <a href="<?php echo add_query_arg(array('l' => base64_encode(get_field('funnel_final_loader_text'))), get_field('funnel_url', 'options')); ?>" id="funnel-button" class="button large ib purple get-results next-page-btn">Get Results!</a>
                        <?php endif; ?>
                    </div>
                    <div class="ad-slot after-next-button">
                        <!-- after_next_button -->
                        <ins class="adsbygoogle"
                            style="display:block"
                            data-ad-client="ca-pub-4411421854869090"
                            data-ad-slot="3282675590"
                            data-ad-format="auto"
                            data-full-width-responsive="true"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
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
