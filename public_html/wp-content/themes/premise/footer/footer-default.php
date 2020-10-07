<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); } if (CFCT_DEBUG) { cfct_banner(__FILE__); } ?>

    <footer class="footer">
        <img class="diamonds desktop top-left" src="<?php bloginfo('template_directory'); ?>/img/diamonds/diamonds-footer-top-left.png" />
        <img class="diamonds mobile top-left" src="<?php bloginfo('template_directory'); ?>/img/diamonds/diamonds-mobile-footer-top-left.png" />
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12">
                    <div class="footer-about">
                        <div class="footer-logo">
                            <img src="<?php bloginfo('template_url'); ?>/img/quirk-quiz-small-light-logo.png" alt="Quirk Quiz">
                        </div>
                        <?php if($about = get_field('about_quirkquiz','options')): ?>
                            <?php echo $about; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 align-self-center">
                    <?php include(get_stylesheet_directory() . '/parts/hardcoded-form.php'); ?>
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

                <div class="copyright desktop">
                    <p>&copy; <?php echo date('Y'); ?> QUIRKQUIZ</p>
                </div>
            </div>
        </div>
        <div class="copyright mobile">
            <p>&copy; <?php echo date('Y'); ?> QUIRKQUIZ</p>
        </div>
        <div id="capture">
            <input type="text" name="fname" autocomplete="given-name" />
            <input type="text" name="lname" autocomplete="family-name" />
            <input type="text" name="email" autocomplete="email" />
            <input type="text" name="zip" autocomplete="postal-code" />
            <input type="tel" name="phone" autocomplete="tel" />
        </div>
    </footer>
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-157717335-1', 'auto');
	ga('send', 'pageview');
	</script>
	</body>
	<?php wp_footer(); ?>
</html>
