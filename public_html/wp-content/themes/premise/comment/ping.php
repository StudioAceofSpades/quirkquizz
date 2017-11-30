<?php

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

comment_author_link();

comment_text();

comment_date();

comment_time();

edit_comment_link(__('Edit This', 'carrington-jam'), '', '');

?>