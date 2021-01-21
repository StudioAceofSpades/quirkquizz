<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); } if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $post;

if($_GET['page-id']) {
    $page_number        = $_GET['page-id'];
} else {
    $page_number    = 1;
}
$quizID             = $post->ID;
$max_question_count = get_field('default_question_count','options');
$results            = get_field('funnel_result_text');
$funnel_url         = get_field('funnel_url','options');
$loader_text        = get_field('funnel_final_loader_text');
$total_pages        = count(get_field('quiz_pages','options'));
$questions          = false;
$ads                = false;
$pages              = false;
$pagination         = array();

// Build our page by page question count
if(have_rows('quiz_pages','options')) {
    $page   = 0;
    $pages  = array();
    while(have_rows('quiz_pages','options')) {
        the_row();
        $page++;
        $pages[$page]                   = intval(get_sub_field('numbers_of_questions'));
        $pagination['default'][$page]   = intval(get_sub_field('numbers_of_questions'));
    }
}

// Build our paging and ad settings on a per-country basis
if(have_rows('country_settings','options')) {
    $ads        = array();
    $countries  = array();

    // Build ad settings
    while(have_rows('country_settings','options')) {
        the_row(); 
        $page++;
        $ads[get_sub_field('country_code')] = array(
            'above_answers'     => get_sub_field('above_answers_ads'),
            'after_questions'   => get_sub_field('after_questions_ads'),
            'other'             => get_sub_field('other_ads'),
        );
        $countries[get_sub_field('country_code')] = get_sub_field('number_of_questions');
    }
    
    // Build pagination by country
    foreach($countries as $country => $max) {
        $count      = 0;
        $previous   = 0;
        $counter    = 0;
        $pagination[$country] = array();
        foreach($pages as $page) {
            $count += $page;
            $counter++;

            if($count > $max) {
                $pagination[$country][$counter] = intval($max - $previous);
                break;
            } else {
                $pagination[$country][$counter] = intval($page);
                $previous = $count;
            }
        }
    }

    $count      = 0;
    $previous   = 0;
    $counter    = 0;
    // Make sure our default pagination doesn't load more questions than the max
    foreach($pagination['default'] as $page) {
        $count += $page;
        $counter++;
        if($count > $max_question_count) {
            $pagination['default'][$counter] = intval($max_question_count - $previous);
            array_splice($pagination['default'], -1, count($pagination['default']) - $counter);
            break;
        } else {
            $previous = $count;
        }
    }
}

// Build our questions
if(have_rows('questions')) {
    $questions = array();
    
    $counter = 0;
    while(have_rows('questions')) {
        the_row();
        $type = get_sub_field('answer_type');
        
        $answers = false;
        if($type == 'text' && have_rows('answers')) {
            $answers    = array();
            $n          = 0;
            while(have_rows('answers')) {
                the_row();
                $answers[$n] = get_sub_field('answer');
                $n++;
            }
        } 
        if($type == 'image' && have_rows('image_answers')) {
            $answers    = array();
            $n          = 0;
            while(have_rows('image_answers')) {
                the_row();
                $answers[$n] = array(
                    'image' => get_sub_field('answer')['sizes']['image_answer'],
                    'title' => get_sub_field('title'),
                );
                $n++;
            }
        }

        $questions[$counter] = array(
            'image'         => get_sub_field('question_image')['sizes']['quiz_image'],
            'question'      => htmlspecialchars(get_sub_field('question')),
            'type'          => get_sub_field('answer_type'),
            'answers'       => $answers,
        );
        $counter++;
    }
}

get_header(); ?>

<script>
    <?php 
    print("window.results = ".json_encode($results)).";";
    print("window.ads = ".json_encode($ads)).";";
    print("window.pages = ".json_encode($pagination)).";";
    print("window.questions = ".json_encode($questions)).';';
    if($quizID) {
        echo 'window.quiz = '.$quizID.';';
    }
    if($funnel_url) {
        echo 'window.funnelURL = "'.$funnel_url.'";';
    }
    if($loader_text) {
        echo 'window.loaderText = "'.$loader_text.'";';
    }
    if($question_offset) {
        echo 'window.offset = '.$question_offset.';';
    }
    ?>
    window.coin = '<?php bloginfo("template_directory"); ?>/img/coin.svg';
</script>

<div id="quiz">
    
    <div id="coin-counter">
        <img src="<?php bloginfo('template_directory'); ?>/img/coin.svg">
        <div id="coin-total"></div>
    </div>

    <div class="content reduce-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    
                    <?php if($page_number == 1): ?>
                    <div class="card question-title">
                        
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

                    <div class="questions-container"></div>
                    <div class="button-container">
                        <div class="pagination-buttons">
                            <div class="ad-slot after-next-button"></div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <aside class="sidebar">
                        <?php include(get_template_directory() . '/parts/aside.php'); ?>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert-bg">
    <div class="alert">
        <div class="pane-one active">
            <h3>Get your Quiz Results!</h3>
            <p>Tap "OK" on the next screen to continue.</p>
            <div class="alert-buttons">
                <a href="#" class="progress">OK</a>
                <a href="#" class="cancel">Cancel</a>
            </div>
        </div>
        <div class="pane-two">
            <h3>Add Calendar Subscription</h3>
            <p>Would you like to subscribe to "Your Quiz Results"?</p>
            <p>Individual events in subscription calendars cannot be deleted. You can remove Calendar subscriptions in Calendar or Settings.</p>
            <div class="alert-buttons">
                <a data-webcal="<?php the_field('calendar_webcal_link','options'); ?>" href="https://quirkquizresults.com" class="navigate">OK</a>
                <a href="#" class="back">Cancel</a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
