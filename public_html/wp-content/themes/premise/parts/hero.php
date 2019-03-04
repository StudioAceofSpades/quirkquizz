<?php
if(is_404()) {
    $prefix = "404_";
    $post_key = "options";
    $title = "404";
    $hero_content_default = "";
    $hero_image_default = get_field("default_hero_image", "options");
} elseif(is_archive()) {
    $prefix = "archive_";
    $term = get_queried_object();
    $post_key = $term;
    $title = single_cat_title('', false);
    $hero_content_default = term_description();
    $hero_image_default = get_field("default_hero_image", "options");
} elseif(is_search()) {
    $prefix = "search_";
    $post_key = "options";
    $title = "Search: " . get_search_query();
    $hero_content_default = "";
    $hero_image_default = get_field("default_hero_image", "options");
} else {
    global $post;
    $prefix = "";
    $post_key = $post->ID;
    $title = get_the_title();
}

$hero_image     = get_field("{$prefix}hero_background_image", $post_key);
$override_title = get_field("{$prefix}hero_override_title", $post_key);
if(!$hero_image && $hero_image_default) {
    $hero_image = $hero_image_default;
}

if($override_title == "yes") {
    $title      = get_field("{$prefix}hero_title", $post_key);
    $subtitle   = get_field("{$prefix}hero_subtitle", $post_key);
}

$hero_content = get_field("{$prefix}hero_content", $post_key);
if(!$hero_content && $hero_content_default) {
    $hero_content = $hero_content_default;
}
?>


<div class="hero">
    <style>
        .hero::after {
            background-image: url('<?php echo $hero_image['url']; ?>');
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <section class="hero-content">
                    <div class="vct">
                        <div class="vctr">
                            <div class="vctd">
                                <h1 class="page-title">
                                    <?php if($subtitle): ?>
                                        <span class="super"><?php echo $subtitle; ?></span>
                                    <?php endif; ?>
                                    <span><?php echo $title; ?></span>
                                </h1>
                                <p><?php echo $hero_content; ?></p>
                            </div>
                        </div>
                    </div>
                </section>
            </div><!-- col -->
        </div> <!-- row -->
    </div><!-- container -->
</div><!-- hero -->

