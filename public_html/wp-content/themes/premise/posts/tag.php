<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header();

$tag_title = '<a href="'.get_tag_link(intval(get_query_var('tag_id'))).'" title="">'.single_tag_title('', false).'</a>'; ?>

<section id="posts-archive" class="feed page">


    <section class="hero">
        <?php include(get_stylesheet_directory() . "/parts/hero.php"); ?>
    </section>

    <?php include(get_stylesheet_directory() . "/parts/secondary-nav.php"); ?>

	<section class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <?php cfct_loop(); ?>
                    <?php cfct_misc('nav-posts'); ?>
                </div>
                <div class="col-sm-3">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>

    </section>
</section>

<?php get_footer(); ?>
