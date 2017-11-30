<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<section id="single">
	<article class="single-post">
    	
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    
        <div class="post-meta">
            <p>By <?php the_author(); ?> | Posted on <?php the_date(); ?> | <?php comments_number(); ?></p>
        </div>
        
    	<div class="post-content">
			<?php cfct_loop(); ?>
        </div>
        
        <div class="comments-template">
			<?php comments_template(); ?>
        </div>
		
        <div class="pagination-single">
			<span class="previous"><?php previous_post_link() ?></span>
			<span class="next"><?php next_post_link() ?></span>
		</div>
	
    </article>
	<?php get_sidebar(); ?>
</section>
<?php get_footer(); ?>