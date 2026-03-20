<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('add_meta_boxes', function () {
    add_meta_box(
        'real_estate_map',
        'Мапа',
        function () {
            echo '<div id="admin-map" style="height:400px;"></div>';
            echo '<button type="button" id="geocode-btn" class="button">Знайти за адресою</button>';
        },
        'real_estate',
        'normal',
        'high'
    );
});

add_action('admin_enqueue_scripts', function ($hook) {
    global $post;

    if (!$post || $post->post_type !== 'real_estate') {
        return;
    }

    $admin_map_path = get_template_directory() . '/admin-real-estate-map.js';

    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet/dist/leaflet.css');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet/dist/leaflet.js', [], null, true);
    wp_enqueue_script(
        'real-estate-admin-map',
        get_template_directory_uri() . '/admin-real-estate-map.js',
        ['leaflet-js'],
        file_exists($admin_map_path) ? filemtime($admin_map_path) : null,
        true
    );
});