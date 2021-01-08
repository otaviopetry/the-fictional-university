<?php

require get_theme_file_path('/includes/search-route.php');
require get_theme_file_path('/includes/like-route.php');

function university_custom_rest () {
	// (post type, desired field name, manage field)
	register_rest_field('post', 'authorName', array(
		'get_callback' => function () {
			return get_the_author(); 
		}
	));

	register_rest_field('note', 'userNoteCount', array(
		'get_callback' => function () {
			return count_user_posts(get_current_user_id(), 'note');
		}
	));
}

add_action('rest_api_init', 'university_custom_rest');

function page_banner ($args = NULL) {
    if (!$args['title']) {
        $args['title'] = get_the_title();
    }
    if (!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!$args['photo']) {
        if (get_field('page_banner_background_image') AND !is_archive() AND !is_home()) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['page-banner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    ?>
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
                <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
                </div>
            </div>  
        </div>
    <?php
}

function university_files () {
    wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('leaflet-css', get_theme_file_uri('/css/leaflet.css'), NULL, NULL);
    wp_enqueue_script('leaflet-js', get_theme_file_uri('/js/leaflet.js'), NULL, NULL, false);
    
    if (strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')) {
        wp_enqueue_script('main-javascript', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
    } else {
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.a6d527facd974cdcaf68.js'), NULL, '1.0', true);
        wp_enqueue_script('main-javascript', get_theme_file_uri('/bundled-assets/scripts.178f02c28a612e5604bc.js'), NULL, '1.0', true);
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.178f02c28a612e5604bc.css'));
	}
	
	wp_localize_script('main-javascript', 'universityData', array(
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce('wp_rest')
	));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professor-landscape', 400, 260, true);
    add_image_size('professor-portrait', 480, 650, true);
    add_image_size('page-banner', 1500, 350, true);
    register_nav_menu('footer-location-1', 'Footer location 1');
    register_nav_menu('footer-location-2', 'Footer location 2');
}

add_action('after_setup_theme', 'university_features');

// CUSTOM POST TYPES
// The best approach to deal with custom post types is to put the code inside the mu-plugins folder
// Im keeping it here to keep it in git repo
function university_post_types () {

    // EVENT POST TYPE
    register_post_type('event', array(
		'capability_type' => 'event',
		'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar'
    ));

    // PROGRAMS POST TYPE
    register_post_type('program', array(
        'show_in_rest' => true,
        'supports' => array('title'),
        'rewrite' => array('slug' => 'programs'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards'
    ));

    // PROFESSOR POST TYPE
    register_post_type('professor', array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'public' => true,
        'labels' => array(
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more'
    ));

    // CAMPUS POST TYPE
    register_post_type('campus', array(
		'capability_type' => 'campus',
		'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'campuses'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        ),
        'menu_icon' => 'dashicons-location-alt'
	));
	
	// NOTE POST TYPE
    register_post_type('note', array(
		'capability_type' => 'note',
		'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor'),
		'public' => false,
		'show_ui' => true,
        'labels' => array(
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note'
        ),
        'menu_icon' => 'dashicons-welcome-write-blog'
	));
	
	// LIKE POST TYPE
	register_post_type('like', array(
        'supports' => array('title'),
		'public' => false,
		'show_ui' => true,
        'labels' => array(
            'name' => 'Likes',
            'add_new_item' => 'Add New Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like'
        ),
        'menu_icon' => 'dashicons-heart'		
	));
}

add_action('init', 'university_post_types');

function university_adjust_queries ($query) {
    $today = date('Ymd');
    
    // EVENTS ARCHIVE QUERY
    if ( !is_admin() AND is_post_type_archive('event') AND $query->is_main_query() ) {
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }

    // CAMPUSES ARCHIVE QUERY
    if ( !is_admin() AND is_post_type_archive('campus') AND $query->is_main_query() ) {
        $query->set('posts_per_page', -1);
    }

    // PROGRAMS ARCHIVE QUERY
    if ( !is_admin() AND is_post_type_archive('program') AND $query->is_main_query() ) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
}

add_action('pre_get_posts', 'university_adjust_queries');


// Redirect subscriber logins to front-end home
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend () {
	$currentUser = wp_get_current_user();
	
	if (count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber') {
		wp_redirect(site_url('/'));
		exit;
	}
}

add_action('wp_loaded', 'noAdminBarForSubs');

function noAdminBarForSubs () {
	$currentUser = wp_get_current_user();
	
	if (count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber') {
		show_admin_bar(false);
	}
}

// Customize Login Screen

add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl () {
	return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS () {
	wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.178f02c28a612e5604bc.css'));
	wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');	
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle () {
	return get_bloginfo('name');
}

// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate ($data, $postarr) {
	if ($data['post_type'] == 'note') {
		// check if user still havent reached post limit and it is a new post (not a update/delete request)
		if (count_user_posts(get_current_user_id(), 'note') >= 5 AND !$postarr['ID']) {
			die('You have reached your note limit');	
		}
		// if not, sanitize it
		$data['post_title'] = sanitize_text_field($data['post_title']);
		$data['post_content'] = sanitize_textarea_field($data['post_content']);
	}
 
	if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
		$data['post_status'] = "private";
	}

	return $data;
}