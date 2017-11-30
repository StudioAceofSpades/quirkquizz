<?php

// This file is part of the Carrington JAM Theme for WordPress
// http://carringtontheme.com
//
// Copyright (c) 2008-2010 Crowd Favorite, Ltd. All rights reserved.
// http://crowdfavorite.com
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }

load_theme_textdomain('carrington-jam');

define('CFCT_DEBUG', false);
define('CFCT_PATH', trailingslashit(TEMPLATEPATH));

include_once(CFCT_PATH.'carrington-core/carrington.php');
include_once(CFCT_PATH.'functions/sidebars.php');

// Load our scripts
function saos_load_scripts() {
    wp_deregister_script('jquery');
    
    wp_enqueue_script('jquery', "//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js", false, null);
    wp_enqueue_script('plugins', get_stylesheet_directory_uri().'/js/plugins.js', false, null);
    wp_enqueue_script('scripts', get_stylesheet_directory_uri().'/js/script.js', false, null);
    wp_enqueue_script('icons', '//use.fontawesome.com/a360f5a73e.js', array(), false, true);  
    
    wp_enqueue_style('grid', get_stylesheet_directory_uri().'/css/bootstrap.css', array(), false, 'all');
    wp_enqueue_style('fonts', '//fonts.googleapis.com/css?family=Roboto:300,400,700', array(), false, 'all');
    wp_enqueue_style('main', get_stylesheet_directory_uri().'/style.css', array(), false, 'all');
    
    if ( is_singular('post') ) { 
      wp_enqueue_script( 'comment-reply' ); 
    }
} add_action( 'wp_enqueue_scripts', 'saos_load_scripts' );

// Add support for featured images
add_theme_support( 'post-thumbnails' );

function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
} add_action( 'init', 'disable_emojis' );

function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

function remove_embedded_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
} add_action('wp_enqueue_scripts', 'remove_embedded_style');


// Remove the REST API endpoint.
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
 
// Turn off oEmbed auto discovery.
add_filter( 'embed_oembed_discover', '__return_false' );
 
// Don't filter oEmbed results.
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
 
// Remove oEmbed discovery links.
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
 
// Remove oEmbed-specific JavaScript from the front-end and back-end.
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
 
// Remove all embeds rewrite rules.
add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

?>