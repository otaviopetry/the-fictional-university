<?php 

function university_files () {
    wp_enqueue_script('main-javascript', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
    wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('main-stylesheet', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features () {
    add_theme_support('title-tag');
    register_nav_menu('footer-location-1', 'Footer location 1');
    register_nav_menu('footer-location-2', 'Footer location 2');
}

add_action('after_setup_theme', 'university_features');