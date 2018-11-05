<?php $image = get_sub_field('image'); ?>
<div class="row">
    <div class="col-sm-3">
        <div class="image-caption">
            
            <?php if($header = get_sub_field("caption_header")): ?>
            <h3><?php echo $header; ?></h3>
            <?php endif; ?>

            <?php if($content = get_sub_field('caption')): ?>
            <p><?php echo $content; ?></p>
            <?php endif; ?>

        </div>
    </div>
    <div class="col-sm-9">
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>">
    </div>
</div>
