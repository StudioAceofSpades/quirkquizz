<?php
$image      = get_sub_field('image');
$content    = get_sub_field('wysiwyg');
$align      = get_sub_field('image_alignment');
?>
<div class="row">
    <div class="col-sm-6">
        <?php if($align == 'left'): ?>
        <img src="<?php echo $image['url']; ?>" alt="<? echo $image['alt']; ?>" title="<?php echo $image['title']; ?>">
        <?php else: ?>
        <div class="embedded-text-area">
            <?php echo $content; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="col-sm-6">
        <?php if($align == 'left'): ?>
        <div class="embedded-text-area">
            <?php echo $content; ?>
        </div>
        <?php else: ?>
        <img src="<?php echo $image['url']; ?>" alt="<? echo $image['alt']; ?>" title="<?php echo $image['title']; ?>">
        <?php endif; ?>
    </div>
</div>
