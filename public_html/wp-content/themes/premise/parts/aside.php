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
        foreach($posts as $index=>$post) : 
            setup_postdata($post);
            cfct_excerpt();
            if($index==0){
                echo '<div class="ad-slot after-first-related-quiz">
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- after_first_related_quiz -->
                    <ins class="adsbygoogle"
                        style="display:block"
                        data-ad-client="ca-pub-4411421854869090"
                        data-ad-slot="4316555663"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    </div>';
            }
        endforeach;
    else:
        echo '<p>It appears as if we\'re having trouble getting more quizzes at the moment.</p>';
    endif;
    ?>
    <div class="ad-slot after-all-quizzes">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- after_all_related_quizzes -->
        <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-4411421854869090"
            data-ad-slot="9591280683"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
</aside>
