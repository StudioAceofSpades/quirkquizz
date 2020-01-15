<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); } if (CFCT_DEBUG) { cfct_banner(__FILE__); } ?>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="footer-about">
                        <div class="footer-logo">
                            <img src="<?php bloginfo('template_url'); ?>/img/quirk-quiz-small-light-logo.png" alt="Quirk Quiz">
                        </div>
                        <?php if($about = get_field('about_quirkquiz','options')): ?>
                            <?php echo $about; ?>
                        <?php endif; ?>


                        <?php if(have_rows('social_media_accounts','options')) : ?>
                        <nav class="social">
                            <ul>
                            <?php 
                            while(have_rows('social_media_accounts','options')) : the_row();
                                if(($icon = get_sub_field('icon')) && ($link = get_sub_field('link'))):
                            ?>
                                <li>
                                    <a href="<?php echo $link; ?>" target="_blank">
                                        <i class="fab fa-<?php echo $icon; ?>"></i>
                                    </a>
                                </li>
                            <?php 
                                endif;
                            endwhile; 
                            ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-7 align-self-center">
                    <div class="footer-newsletter">
                        <?php if($header = get_field('newsletter_header','options')): ?>
                        <h3><?php echo $header; ?></h3>
                        <?php endif; ?>
                        <?php if($subheader = get_field('newsletter_subheader','options')): ?>
                        <h4><?php echo $subheader; ?></h4>
                        <?php endif; ?>
                        <div class="newsletter form">
                            <input type="email" placeholder="you@youremail.com">
                            <input class="button" type="submit" value="Sign Up">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="subfooter">
            <div class="container cf">
                <?php if(have_rows('navigation','options')) : ?>
                <div class="site-nav">
                    <nav class="site">
                        <ul>
                        <?php 
                        while(have_rows('navigation','options')) : the_row(); 
                            if($link = get_sub_field('link')): 
                        ?>
                            <li>
                                <?php saos_output_link($link, ''); ?>
                            </li>
                        <?php
                            endif;
                        endwhile;
                        ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>

                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> QuirkQuiz</p>
                </div>
            </div>
        </div>
    </footer>
	
	</body>
	<?php wp_footer(); ?>
</html>
