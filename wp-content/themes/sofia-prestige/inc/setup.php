<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');

    add_post_type_support('post', 'excerpt');

    register_nav_menus([
        'headerMenu_uk' => __('Header menu (Ukrainian)', 'sofia-prestige'),
        'headerMenu_en' => __('Header menu (English)', 'sofia-prestige'),
        'footerMenu_uk' => __('Footer menu (Ukrainian)', 'sofia-prestige'),
        'footerMenu_en' => __('Footer menu (English)', 'sofia-prestige'),
    ]);
});