<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('starter-style', get_stylesheet_uri());

    $main_css_path = get_template_directory() . '/assets/css/main.css';
    $main_js_path = get_template_directory() . '/assets/js/main.js';

    wp_enqueue_style(
        'main-style',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        file_exists($main_css_path) ? filemtime($main_css_path) : null
    );

    wp_enqueue_script(
        'sofia-hero-slider',
        get_template_directory_uri() . '/assets/js/modules/hero-slider.js',
        [],
        file_exists(get_template_directory() . '/assets/js/modules/hero-slider.js') ? filemtime(get_template_directory() . '/assets/js/modules/hero-slider.js') : null,
        true
    );

    wp_enqueue_script(
        'sofia-mobile-menu',
        get_template_directory_uri() . '/assets/js/modules/mobile-menu.js',
        [],
        file_exists(get_template_directory() . '/assets/js/modules/mobile-menu.js') ? filemtime(get_template_directory() . '/assets/js/modules/mobile-menu.js') : null,
        true
    );

    wp_enqueue_script(
        'sofia-faq',
        get_template_directory_uri() . '/assets/js/modules/faq.js',
        [],
        file_exists(get_template_directory() . '/assets/js/modules/faq.js') ? filemtime(get_template_directory() . '/assets/js/modules/faq.js') : null,
        true
    );

    wp_enqueue_script(
        'sofia-partners-slider',
        get_template_directory_uri() . '/assets/js/modules/partners-slider.js',
        [],
        file_exists(get_template_directory() . '/assets/js/modules/partners-slider.js') ? filemtime(get_template_directory() . '/assets/js/modules/partners-slider.js') : null,
        true
    );

    wp_enqueue_script(
        'sofia-seo-toggle',
        get_template_directory_uri() . '/assets/js/modules/seo-toggle.js',
        [],
        file_exists(get_template_directory() . '/assets/js/modules/seo-toggle.js') ? filemtime(get_template_directory() . '/assets/js/modules/seo-toggle.js') : null,
        true
    );

    wp_enqueue_script(
        'sofia-scroll-top',
        get_template_directory_uri() . '/assets/js/modules/scroll-top.js',
        [],
        file_exists(get_template_directory() . '/assets/js/modules/scroll-top.js') ? filemtime(get_template_directory() . '/assets/js/modules/scroll-top.js') : null,
        true
    );

    wp_enqueue_script(
        'sofia-blog-slider',
        get_template_directory_uri() . '/assets/js/modules/blog-slider.js',
        [],
        file_exists(get_template_directory() . '/assets/js/modules/blog-slider.js') ? filemtime(get_template_directory() . '/assets/js/modules/blog-slider.js') : null,
        true
    );

    wp_enqueue_script(
        'main-script',
        get_template_directory_uri() . '/assets/js/main.js',
        [
            'sofia-hero-slider',
            'sofia-mobile-menu',
            'sofia-faq',
            'sofia-blog-slider',
            'sofia-partners-slider',
            'sofia-seo-toggle',
            'sofia-scroll-top',
        ],
        file_exists($main_js_path) ? filemtime($main_js_path) : null,
        true
    );
});