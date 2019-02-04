<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header(); ?>

<div id="contact">
	<div class="content">
        <?php include(get_stylesheet_directory() . '/parts/hero.php'); ?>
        <div class='container contact-info'>
            <div class='row'>
                <div class='col-sm-4'>
                    <div class='contact-information'>
                        <?php the_field('contact_information'); ?>
                    </div>
                </div>
                <div class='col-sm-8'>
                    <div class='map-embed'>
                        <h2>Find Us Here</h2>
                        <iframe src="<?php the_field('google_maps_embed_source'); ?>" width="600" height="450" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <div class='form-embed'>
                        <h2>Get In Contact</h2>
                        <?php cfct_loop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
