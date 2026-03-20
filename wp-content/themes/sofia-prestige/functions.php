<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/disable-comments.php';
require_once get_template_directory() . '/inc/yoast-menu-highlight.php';
require_once get_template_directory() . '/inc/helpers.php';

require_once get_template_directory() . '/inc/admin/media-fields.php';
require_once get_template_directory() . '/inc/admin/settings-page.php';
require_once get_template_directory() . '/inc/admin/real-estate-map.php';