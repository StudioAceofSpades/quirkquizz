<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

if (get_option('permalink_structure') != '') {
	$onsubmit = "location.href=this.action+'search/'+encodeURIComponent(this.s.value).replace(/%20/g, '+'); return false;";
} else {
	$onsubmit = ''; } ?>

<form class="search-form" method="get" action="<?php echo trailingslashit(get_bloginfo('url')); ?>" onsubmit="<?php echo $onsubmit; ?>">
	<input type="text" id="s" name="s" value="<?php if(isset($s)) echo esc_html($s, 1); ?>" size="15" placeholder="Search..." />
	<input class="submit-search-button" type="submit" name="submit_button" value="<?php _e('Search', 'carrington-jam'); ?>" />
</form>