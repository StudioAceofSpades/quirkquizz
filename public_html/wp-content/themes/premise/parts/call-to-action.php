<?php
$bg         = get_sub_field('background_image')['url'];
$title      = get_sub_field('title');
$subtitle   = get_sub_field('subtitle');
$content    = get_sub_field('content');
?>

    </div><!-- part wrapper -->
</div><!-- container -->

<?php if($bg): ?>
<style>
.section.background-image.cta-<?php echo $counter; ?>::after {
    background-image: url('<?php echo $bg; ?>');
}
</style>
<?php endif; ?>

<div class="section dark background-image cta-<?php echo $counter; ?>">
    <div class="container">
        <?php if($title): ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="section-header">
                    <h3><?php echo $title; ?></h3>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($subtitle || $content) : ?>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 text-center">
                <div class="section-content">

                    <?php if($subtitle): ?>
                    <h4 class="content-header"><?php echo $subtitle; ?></h4>
                    <?php endif; ?>

                    <?php if($content): ?>
                    <p><?php echo $content; ?></p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(have_rows('buttons_buttons')): ?>
        <div class="buttons text-center">
            <?php 
            while(have_rows('buttons_buttons')):
                the_row();
                saos_output_link(get_sub_field('link'), 'arrow', 'button hollow '.get_sub_field('color'));
            endwhile; 
            ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<div class="container">
