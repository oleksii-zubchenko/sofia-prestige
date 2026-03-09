<?php
if (!defined('ABSPATH')) exit;

// Scripts connecting
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('starter-style', get_stylesheet_uri());
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/main.css', [], null);
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.js', [], null, true);
});

// Theme support wordpress
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    register_nav_menus([
        'headerMenu_uk' => __('Header menu (Ukrainian)', 'sofia-prestige'),
        'headerMenu_en' => __('Header menu (English)', 'sofia-prestige'),
    ]);
});

// Disable comments site-wide
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

add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

add_action('admin_bar_menu', function ($wp_admin_bar) {
    $wp_admin_bar->remove_node('comments');
}, 999);

add_filter('feed_links_show_comments_feed', '__return_false');
add_action('template_redirect', function () {
    if (function_exists('is_comment_feed') && is_comment_feed()) {
        wp_die('Comments are closed.');
    }
});

// Highlight Yoast focus keyword in second-level menu items
add_filter('nav_menu_item_title', function ($title, $item, $args, $depth) {
    // Only target second level (depth 1) items that link to posts/pages
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

// Admin settings page
add_action('admin_init', function () {
    register_setting('sofia_settings_group', 'sofia_contact_telegram');
    register_setting('sofia_settings_group', 'sofia_contact_phone');
    register_setting('sofia_settings_group', 'sofia_contact_address');
    register_setting('sofia_settings_group', 'sofia_contact_address_en');
    register_setting('sofia_settings_group', 'sofia_contact_address_link');
    register_setting('sofia_settings_group', 'sofia_contact_email');
    register_setting('sofia_settings_group', 'sofia_social_facebook');
    register_setting('sofia_settings_group', 'sofia_social_instagram');
    register_setting('sofia_settings_group', 'sofia_social_youtube');
    register_setting('sofia_settings_group', 'sofia_social_tiktok');
});

add_action('admin_menu', function () {
    add_menu_page(
        'Налаштування',
        'Налаштування',
        'manage_options',
        'sofia-settings',
        'sofia_render_settings_page',
        'dashicons-admin-generic',
        2
    );
});

function sofia_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $telegram = get_option('sofia_contact_telegram', '');
    $phone = get_option('sofia_contact_phone', '');
    $address = get_option('sofia_contact_address', '');
    $address_en = get_option('sofia_contact_address_en', '');
    $address_link = get_option('sofia_contact_address_link', '');
    $email = get_option('sofia_contact_email', '');
    $facebook = get_option('sofia_social_facebook', '');
    $instagram = get_option('sofia_social_instagram', '');
    $youtube = get_option('sofia_social_youtube', '');
    $tiktok = get_option('sofia_social_tiktok', '');
    ?>
    <div class="wrap">
        <h1>Контакти</h1>
        <form method="post" action="options.php">
            <?php settings_fields('sofia_settings_group'); ?>
            <?php do_settings_sections('sofia_settings_group'); ?>

            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="sofia_contact_telegram">Telegram</label></th>
                    <td><input name="sofia_contact_telegram" type="text" id="sofia_contact_telegram" value="<?php echo esc_attr($telegram); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_address">Адреса</label></th>
                    <td><input name="sofia_contact_address" type="text" id="sofia_contact_address" value="<?php echo esc_attr($address); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_address_en">Address (EN)</label></th>
                    <td><input name="sofia_contact_address_en" type="text" id="sofia_contact_address_en" value="<?php echo esc_attr($address_en); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_address_link">Google Maps посилання</label></th>
                    <td><input name="sofia_contact_address_link" type="text" id="sofia_contact_address_link" value="<?php echo esc_attr($address_link); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_phone">Телефон</label></th>
                    <td><input name="sofia_contact_phone" type="text" id="sofia_contact_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_email">Email</label></th>
                    <td><input name="sofia_contact_email" type="text" id="sofia_contact_email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_social_facebook">Facebook</label></th>
                    <td><input name="sofia_social_facebook" type="text" id="sofia_social_facebook" value="<?php echo esc_attr($facebook); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_social_instagram">Instagram</label></th>
                    <td><input name="sofia_social_instagram" type="text" id="sofia_social_instagram" value="<?php echo esc_attr($instagram); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_social_youtube">YouTube</label></th>
                    <td><input name="sofia_social_youtube" type="text" id="sofia_social_youtube" value="<?php echo esc_attr($youtube); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_social_tiktok">TikTok</label></th>
                    <td><input name="sofia_social_tiktok" type="text" id="sofia_social_tiktok" value="<?php echo esc_attr($tiktok); ?>" class="regular-text"></td>
                </tr>
                </tbody>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Real estate map

add_action('add_meta_boxes', function() {
    add_meta_box(
        'real_estate_map',
        'Мапа',
        function() {
            echo '<div id="admin-map" style="height:400px;"></div>';
            echo '<button type="button" id="geocode-btn" class="button">Знайти за адресою</button>';
        },
        'real_estate',
        'normal',
        'high'
    );
});

add_action('admin_enqueue_scripts', function($hook) {
    global $post;

    if (!$post || $post->post_type !== 'real_estate') return;

    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet/dist/leaflet.css');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet/dist/leaflet.js', [], null, true);
    wp_enqueue_script(
        'real-estate-admin-map',
        get_template_directory_uri() . '/admin-real-estate-map.js',
        ['leaflet-js'],
        null,
        true
    );
});
