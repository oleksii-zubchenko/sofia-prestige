<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    $post_types = ['post', 'page', 'attachment'];

    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
        }

        if (post_type_supports($post_type, 'trackbacks')) {
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);
add_filter('feed_links_show_comments_feed', '__return_false');

add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

add_action('admin_bar_menu', function ($wp_admin_bar) {
    $wp_admin_bar->remove_node('comments');
}, 999);

add_action('template_redirect', function () {
    if (function_exists('is_comment_feed') && is_comment_feed()) {
        wp_die('Comments are closed.');
    }
});