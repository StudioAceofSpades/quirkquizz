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

<section id="search-results">
	<section class="content">
		<h1><?php printf(__('Search Results for: %s', 'carrington-jam'), $search_title); ?></h1>
		<?php cfct_loop(); ?>
        <?php cfct_misc('nav-posts'); ?>
    </section>
    <?php get_sidebar(); ?>
</section>

<?php get_footer(); ?>