<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header();

$s = get_query_var('s');

if (get_option('permalink_structure') != '') {
	$search_title = '<a href="'.trailingslashit(get_bloginfo('url')).'search/'.urlencode($s).'">'.htmlspecialchars($s).'</a>';
}
else {
	$search_title = '<a href="'.trailingslashit(get_bloginfo('url')).'?s='.urlencode($s).'">'.htmlspecialchars($s).'</a>';
} ?>

<section id="search-results" class="feed page">
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
