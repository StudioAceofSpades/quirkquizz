<?php 
if(isset($counter)) {
    $counter++;
} else {
    $counter = 1;
}
$layout = get_row_layout();
?>

<div class='cms-part part-<?php echo $layout; ?>' id="section-<?php echo $counter; ?>">
    <?php include(get_stylesheet_directory() . "/parts/".$layout.".php"); ?>
</div>
