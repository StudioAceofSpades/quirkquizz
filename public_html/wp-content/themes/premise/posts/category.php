<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header();

$cat_title = '<a href="'.get_category_link(intval(get_query_var('cat'))).'" title="">'.single_cat_title('', false).'</a>';

?>


<section id="category-archive">
	<section class="content">
		<h1><?php printf(__('Category Archives: %s', 'carrington-jam'), $cat_title); ?></h1>
		<?php cfct_loop(); ?>
        <?php cfct_misc('nav-posts'); ?>
    </section>
    <?php get_sidebar(); ?>
</section>

<?php get_footer(); ?>