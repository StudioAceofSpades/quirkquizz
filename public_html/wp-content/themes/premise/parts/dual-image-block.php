<?php
$top_image      = get_sub_field('top_image');
$bottom_image   = get_sub_field('bottom_image');
$align          = get_sub_field('image_alignment');
?>
<div class="section">
    <div class="section-images <?php echo $align; ?>">
        <img 
            class="under mover"
            data-paroller-factor="0.05"
            data-paroller-type="foreground"
            data-paroller-direction="upleft"
            src="<?php echo $bottom_image['url']; ?>"
            alt="<?php echo $bottom_image['alt']; ?>"
            title="<?php echo $bottom_image['title']; ?>"
        >
        <img 
            class="over mover"
            data-paroller-factor="0.05"
            data-paroller-type="foreground"
            data-paroller-direction="downright"
            src="<?php echo $top_image['url']; ?>"
            alt="<?php echo $top_image['alt']; ?>"
            title="<?php echo $top_image['title']; ?>"
        >
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-6<?php if($align == 'left') { echo ' col-sm-offset-6'; } ?>">
                <div class="embedded-text-area">
                <?php the_sub_field('wysiwyg'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
