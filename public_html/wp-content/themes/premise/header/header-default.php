<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); } if (CFCT_DEBUG) { cfct_banner(__FILE__); } ?>
<!DOCTYPE html>

<!--

Authors: Studio Ace of Spades
Website: http://studioaceofspades.com
E-Mail: jon@studioaceofspade.com
 
-->

<head>

    <meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
    <title><?php wp_title( '-', true, 'right' ); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <?php include(get_template_directory() . '/parts/head-meta.php'); ?>

    <?php wp_head(); ?>

    <?php "go2cloud click tracking script" ?>
    <script type="text/javascript">
    var hasoffers_domain = "https://masstraffic.go2cloud.org";
    </script>
    <script type="text/javascript" src="https://media.go2app.org/assets/js/dl.js"></script>

	<!-- Snap Pixel Code -->
	<script type='text/javascript'>
	(function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
	{a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
	a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
	r.src=n;var u=t.getElementsByTagName(s)[0];
	u.parentNode.insertBefore(r,u);})(window,document,
	'https://sc-static.net/scevent.min.js');

	snaptr('init', '72f62000-d19e-462a-8f88-038b587f6cc6', {
	'user_email': '__INSERT_USER_EMAIL__'
	});

	snaptr('track', 'PAGE_VIEW');

	</script>
	<!-- End Snap Pixel Code -->
    
</head>

<body id="top-of-page">

<header class="header cf">
    <?php if(is_front_page()): ?>
        <img class="diamonds bottom-left" src="<?php echo bloginfo('template_directory'); ?>/img/diamonds/diamonds-header-top-left.png"/>
    <?php endif; ?>
    <div class="container">
        <div class="row align-items-center">

            <div class="col">
                <h1 class="brand">
                    <a href="<?php bloginfo('url'); ?>">
                        <img class="header-logo" src="<?php bloginfo('template_directory'); ?>/img/quirk-quiz-logo.png">
                    </a>
                </h1>
            </div>

            <div class="col"></div>
             
            <div class="col">
                <?php if(have_rows('navigation','options')) : ?>
                <div class="site-nav">
                    <a href="#" class="nav-trigger"><i class="fas fa-bars"></i></a>
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
                    </nav>
                </div>
                <?php endif; ?>
            </div>

        </div><!-- row -->
    </div>
</header>
