<?php
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
    wp_enqueue_script('util', get_stylesheet_directory_uri().'/js/util.js', false, null);
    wp_enqueue_script('plugins', get_stylesheet_directory_uri().'/js/plugins.js', false, null);
    wp_enqueue_script('scripts', get_stylesheet_directory_uri().'/js/script.js', false, null);
    wp_enqueue_script('quiz', get_stylesheet_directory_uri().'/js/quiz.js', false, null);
    wp_enqueue_script('icons', 'https://kit.fontawesome.com/b0b03c6933.js', false, null);
    
    wp_enqueue_style('fonts', '//fonts.googleapis.com/css?family=Quicksand:400,700|Roboto:400,700', array(), false, 'all');

    if(ENVIRONMENT == "development") {
        wp_enqueue_style('main', get_stylesheet_directory_uri().'/devcss/style.css', array(), false, 'all');
    } else {
        wp_enqueue_style('main', get_stylesheet_directory_uri().'/style.css', array(), false, 'all');
    }
    
    if ( is_singular('post') ) { 
      wp_enqueue_script( 'comment-reply' ); 
    }
} add_action( 'wp_enqueue_scripts', 'saos_load_scripts' );

// Add support for featured images
add_theme_support( 'post-thumbnails' );
add_image_size('quiz_image', 670, 450, true);
add_image_size('quiz_question', 660, 300, true);
add_image_size('quiz_thumb', 510, 333, true);
add_image_size('image_answer', 510, 333, true);

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

// Prevent BWP from minifying for admin users
add_filter("bwp_minify_is_loadable", "no_logged_in_minify");

function no_logged_in_minify($loadable) {
    if(is_user_logged_in() && !is_admin()) {
        $loadable = false;
    }   
    return $loadable;
}

function clever_debug($object = null) {
	if($object === null) {
		$object = debug_backtrace();
	}
    echo "<script>";
    echo "window.debug_message = window.debug_message || [];";
    echo "window.debug_message.push(" . json_encode($object) . ")";
    echo "</script>";
}

if(function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'    => 'Global Theme Settings',
        'menu_title'    => 'Site Settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Header Settings',
        'menu_title'    => 'Header & Navigation',
        'parent_slug'   => 'acf-options-site-settings'
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'acf-options-site-settings'
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Global Site Options',
        'menu_title'    => 'Global Options',
        'parent_slug'   => 'acf-options-site-settings'
    ));
}

function saos_configure_link($link) {
	 
	if(!isset($link)) {
		return false;
	}
 
	if(!isset($link['url']) || $link['url'] == '') {
		return false;
	}
 
	if($link['url'] == '#' && $link['title'] == '') {
		return false;
	}
	if($link['title'] == '' && $link['url'] == '') {
		return false;
	}

	if($link['title'] == '') {
		$post_id = url_to_postid($link['url']);
		$link['title'] = get_the_title($post_id);
	}

    return $link;
}

function saos_output_link($link, $classes) {
	$link = saos_configure_link($link);
	if($link) {
		echo '<a class="'.$classes.'" href="'.$link['url'].'" target="'.$link['target'].'">'.$link['title'].'</a>';
	}
}

function create_quiz_cpt() {

	$labels = array(
		'name' => _x( 'Quizzes', 'Post Type General Name', 'textdomain' ),
		'singular_name' => _x( 'Quiz', 'Post Type Singular Name', 'textdomain' ),
		'menu_name' => _x( 'Quizzes', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar' => _x( 'Quiz', 'Add New on Toolbar', 'textdomain' ),
		'archives' => __( 'Quiz Archives', 'textdomain' ),
		'attributes' => __( 'Quiz Attributes', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Quiz:', 'textdomain' ),
		'all_items' => __( 'All Quizzes', 'textdomain' ),
		'add_new_item' => __( 'Add New Quiz', 'textdomain' ),
		'add_new' => __( 'Add New', 'textdomain' ),
		'new_item' => __( 'New Quiz', 'textdomain' ),
		'edit_item' => __( 'Edit Quiz', 'textdomain' ),
		'update_item' => __( 'Update Quiz', 'textdomain' ),
		'view_item' => __( 'View Quiz', 'textdomain' ),
		'view_items' => __( 'View Quizzes', 'textdomain' ),
		'search_items' => __( 'Search Quiz', 'textdomain' ),
		'not_found' => __( 'Not found', 'textdomain' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
		'featured_image' => __( 'Featured Image', 'textdomain' ),
		'set_featured_image' => __( 'Set featured image', 'textdomain' ),
		'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
		'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
		'insert_into_item' => __( 'Insert into Quiz', 'textdomain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Quiz', 'textdomain' ),
		'items_list' => __( 'Quizzes list', 'textdomain' ),
		'items_list_navigation' => __( 'Quizzes list navigation', 'textdomain' ),
		'filter_items_list' => __( 'Filter Quizzes list', 'textdomain' ),
	);
	$args = array(
		'label' => __( 'Quiz', 'textdomain' ),
		'description' => __( '', 'textdomain' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-welcome-learn-more',
		'supports' => array('title', 'excerpt', 'revisions', 'author', 'page-attributes', 'post-formats', 'custom-fields'),
		'taxonomies' => array(),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'quiz', $args );

}
add_action( 'init', 'create_quiz_cpt', 0 );

function track_views() {
	wp_enqueue_script(
		'track-views',
		get_template_directory_uri() . '/js/track-views.js',
		array('jquery')
	);
	wp_localize_script(
		'track-views',
		'track_views_obj',
		array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
	);
} 
add_action('wp_enqueue_scripts','track_views');

function track_views_ajax() {
	if ( isset($_REQUEST) ) {
        $id = $_REQUEST['quiz'];
        $views = get_post_meta($id, 'views', true);

        if($views) {
            $views++;
        } else {
            $views = 1;
        }
        update_post_meta($_REQUEST['quiz'], 'views', $views);
    }
    die();
}
add_action( 'wp_ajax_track_views_ajax', 'track_views_ajax' );
add_action( 'wp_ajax_nopriv_track_views_ajax', 'track_views_ajax' );

function load_more() {
	wp_enqueue_script(
		'load-more',
		get_template_directory_uri() . '/js/load-more.js',
		array('jquery')
	);
	wp_localize_script(
		'load-more',
		'load_more_obj',
		array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
	);
} 
add_action('wp_enqueue_scripts','load_more');

function load_more_ajax() {
    global $post;

	if ( isset($_REQUEST) ) {
        $args = array(
            'posts_per_page'    => 9,
            'post_type'         => 'quiz',
            'offset'            => $_REQUEST['cards'],
        );
        $posts = get_posts($args);
        if(!empty($posts)):
            foreach($posts as $post) : setup_postdata($post);
            ?>
            <div class="col-lg-6 col-xl-4">
                <?php cfct_excerpt(); ?>
            </div>
            <?php
            endforeach;

        else : 
            echo 'false';
        endif;
        wp_reset_postdata();
    }
    die();
}
add_action( 'wp_ajax_load_more_ajax', 'load_more_ajax' );
add_action( 'wp_ajax_nopriv_load_more_ajax', 'load_more_ajax' );

function random_quiz_url() {
	wp_enqueue_script(
		'random-quiz-url',
		get_template_directory_uri() . '/js/random-quiz-url.js',
		array('jquery')
	);
	wp_localize_script(
		'random-quiz-url',
		'random_quiz_url_obj',
		array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
	);
} 
add_action('wp_enqueue_scripts','random_quiz_url');

function random_quiz_url_ajax() {
    global $post;

	if ( isset($_REQUEST) ) {
        $args = array(
            'posts_per_page'    => 1,
            'orderby'           => 'rand',
            'post_status'       => 'publish',
            'post_type'         => 'quiz'
        );
        $posts = get_posts($args);

        if(!empty($posts)):
            foreach($posts as $post) : setup_postdata($post);
                echo get_permalink();
            endforeach;
        else: 
            echo 'Uhoh! Something went wrong fetching a random quiz!';
        endif;
    }
    die();
}
add_action( 'wp_ajax_random_quiz_url_ajax', 'random_quiz_url_ajax' );
add_action( 'wp_ajax_nopriv_random_quiz_url_ajax', 'random_quiz_url_ajax' );


?>
