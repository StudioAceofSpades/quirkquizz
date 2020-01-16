<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); } if (CFCT_DEBUG) { cfct_banner(__FILE__); } ?>

<article class="card panel">
    <?php if($image = get_field('quiz_image')): ?>
    <img 
        src="<?php echo $image['sizes']['quiz_image']; ?>"
        alt="<?php echo $image['alt']; ?>">
    <?php endif; ?>
    <h3><?php the_title(); ?></h3>
    <a class="button large" href="<?php the_permalink(); ?>">Start Quiz</a>
</article>
