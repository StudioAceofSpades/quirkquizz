<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); } if (CFCT_DEBUG) { cfct_banner(__FILE__); }
if(!is_home()) {
	the_content(); 
} else {
	cfct_excerpt();}
?>