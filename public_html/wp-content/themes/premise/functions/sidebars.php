<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }

if (function_exists('register_sidebar')) {
	register_sidebar(
		array(
			'name' => 'Primary Sidebar',
			'id' => 'primary-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '<div class="clear"></div></div>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>'
		)
	);
	register_sidebar(
		array(
			'name' => 'Secondary Sidebar',
			'id' => 'secondary-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '<div class="clear"></div></div>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>'
		)
	);
} ?>