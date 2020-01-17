<aside class="sidebar">
    <h2 class="section-header">More Quizzes</h2>
    <?php
    $args = array(
        'posts_per_page'    => 3,
        'orderby'           => 'rand',
        'post_status'       => 'publish',
        'post_type'         => 'quiz'
    );
    $posts = get_posts($args);

    if(!empty($posts)):
        foreach($posts as $post) : 
            setup_postdata($post);
            cfct_excerpt();
        endforeach;
    else:
        echo '<p>It appears as if we\'re having trouble getting more quizzes at the moment.</p>';
    endif;
    ?>
</aside>
