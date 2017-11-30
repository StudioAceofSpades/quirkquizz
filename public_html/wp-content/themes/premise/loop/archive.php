<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

if (have_posts()) {
	echo '<section id="archive">';
	while (have_posts()) {
		the_post(); ?>
	<section class="archived-post">
		<?php cfct_excerpt(); ?>
	</section>
<?php } echo '</section>'; } ?>