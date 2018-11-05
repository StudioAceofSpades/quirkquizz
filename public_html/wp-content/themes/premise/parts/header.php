<?php
$text   = get_sub_field('header_text');
$size   = get_sub_field('header_size');
$color  = get_sub_field('header_color');
?>

<?php if($size == 'big'): ?>
<h2 class="<?php echo $color; ?>"><?php echo $text; ?></h2>
<?php elseif($size == 'medium'): ?>
<h3 class="<?php echo $color; ?>"><?php echo $text; ?></h3>
<?php elseif($size == 'small'): ?>
<h4 class="<?php echo $color; ?>"><?php echo $text; ?></h4>
<?php endif; ?>
