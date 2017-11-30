<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header();

$tag_title = '<a href="'.get_tag_link(intval(get_query_var('tag_id'))).'" title="">'.single_tag_title('', false).'</a>'; ?>

<section id="tag-archive">
	<section class="content">
		<h1 class="tag-title"><?php printf(__('Tag Archives: %s', 'carrington-jam'), $tag_title); ?></h1>
		<?php cfct_loop(); ?>
        <?php cfct_misc('nav-posts'); ?>
    </section>
    <?php get_sidebar(); ?>
</section>

<?php get_footer(); ?>