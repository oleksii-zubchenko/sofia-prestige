<?php
if (!defined('ABSPATH')) {
    exit;
}

add_filter('nav_menu_item_title', function ($title, $item, $args, $depth) {
    if ($depth !== 1 || $item->type !== 'post_type' || empty($item->object_id)) {
        return $title;
    }

    $focus_keyword = '';

    if (class_exists('WPSEO_Meta')) {
        $focus_keyword = WPSEO_Meta::get_value('focuskw', $item->object_id);
    }

    if (!$focus_keyword) {
        $focus_keyword = get_post_meta($item->object_id, '_yoast_wpseo_focuskw', true);
    }

    if (!$focus_keyword) {
        return $title;
    }

    $contains_kw = function_exists('mb_stripos')
        ? mb_stripos($title, $focus_keyword) !== false
        : stripos($title, $focus_keyword) !== false;

    if (!$contains_kw) {
        return $title;
    }

    $pattern = '/' . preg_quote($focus_keyword, '/') . '/iu';

    return preg_replace($pattern, '<b>$0</b>', $title, 1);
}, 10, 4);