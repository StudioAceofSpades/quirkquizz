<?php
$size   = get_field('hero_size');
$bg     = get_field('default_hero_background','options');
$button = 'button main';

if(get_field('background_image')) {
    $bg = get_field('background_image');
}

if($size == 'small') {
    $bg_image   = $bg['sizes']['hero-small'];
} else {
    $bg_image = $bg['sizes']['hero-large'];
}

if(get_field('button_color') == 'light') {
    $button    .= ' yellow';
}

?>

<div 
    class="<?php echo $size; ?> subhero text-<?php the_field('text_color'); ?> button-<?php the_field('button_color'); ?>"
    style="background-image: url('<?php echo $bg_image; ?>');">
    <div class="container h100">
        <div class="row h100">
            <div class="col-sm-8 col-sm-offset-2 h100">
                <div class="vct">
                    <div class="vctr">
                        <div class="vctd">
                            
                            <?php if(get_field('page_header')) : ?>
                            <h1><?php the_field('page_header'); ?></h1>
                            <?php else: ?>
                            <h1><?php the_title(); ?></h1>
                            <?php endif; ?>

                            <?php if(get_field('page_subheader') &&  $size != 'small'): ?>
                            <h3><?php the_field('page_subheader'); ?></h3>
                            <?php endif; ?>

                            <?php if(have_rows('buttons') && $size != 'small'): ?>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                                    <div class="button-group">
                                        <?php 
                                        while(have_rows('buttons')) : 
                                            the_row(); 
                                            saos_output_link(get_sub_field('link'), $button);
                                        endwhile; 
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
