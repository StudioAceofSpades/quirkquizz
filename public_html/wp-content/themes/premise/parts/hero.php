<?php
$hero_image     = get_field('hero_background_image');
$override_title = get_field('hero_override_title');

if($override_title) {
    $title      = get_field('hero_title');
    $subtitle   = get_field('hero_subtitle');
} else {
    $title = get_the_title();
}
?>
<div 
    <?php if($hero_image): ?>
    style="background-image: url('<?php echo $hero_image['url']; ?>');"
    <?php endif; ?>
    class="hero small">
    <div class="vct">
        <div class="vctr">
            <div class="vctd">
                <div class="hero-content">
                    <h1><?php echo $title; ?></h1>

                    <?php if($override_title): ?>
                        <?php if($subtitle): ?>
                        <h2><?php echo $subtitle; ?></h2>
                        <?php endif; ?>

                        <?php if(have_rows('hero_buttons_buttons')): ?>
                        <div class="buttons">
                            <?php 
                            while(have_rows('hero_buttons_buttons')): 
                                the_row();
                                saos_output_link(get_sub_field('link'), 'arrow', 'button hollow '.get_sub_field('color'));
                            endwhile; 
                            ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
