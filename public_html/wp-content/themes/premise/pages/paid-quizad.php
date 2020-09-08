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
                        <div class="ad-slot">
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- intermediate-quiz-page-ad -->
                            <ins class="adsbygoogle"
                                style="display:block"
                                data-ad-client="ca-pub-4411421854869090"
                                data-ad-slot="2467571078"
                                data-ad-format="auto"
                                data-full-width-responsive="true"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                        </div>
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
