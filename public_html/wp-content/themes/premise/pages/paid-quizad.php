<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div id="paid-quizad">
    <div class="content reduce-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card question">
                        <h1><?php the_field('header', 'options'); ?></h1>
                        <h3><?php the_field('subheader', 'options'); ?></h3>
                        <?php the_field('text_content', 'options'); ?>
                        <div class="buttons stacked">
                            <a href="<?php echo get_field('more_button_link', 'options')['url']; ?>" class="button"><?php echo get_field('more_button_link', 'options')['title']; ?></a>
                            <a href="<?php echo add_query_arg( 'page-id', 2, $_SERVER['REQUEST_URI'] );?>" class="button purple"><?php the_field('continue_button_text', 'options'); ?></a>
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

<?php get_footer(); ?>
