<?php
// Basic Theme Setup
function bdt_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails'); // This will be used for the book cover
}
add_action('after_setup_theme', 'bdt_theme_setup');

// Enqueue Stylesheet
function bdt_enqueue_styles() {
    wp_enqueue_style('bdt-main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'bdt_enqueue_styles');
