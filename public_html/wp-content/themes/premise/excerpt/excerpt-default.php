<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); } ?>

<article class="excerpt">

	<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

    <div class="post-meta">
    	<p>By <?php the_author(); ?> | Posted on <?php the_date(); ?> | <?php comments_number(); ?></p>
    </div>

    <div class="post-content">
		<?php the_excerpt(); ?>
    </div>
    
</article>