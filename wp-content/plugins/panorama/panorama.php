<?php

/**
 * Plugin Name: Panorama
 * Description: A lite Weight Plugin that helps you, Easily display panoramic 360 degree images / videos into WordPress Website in Post, Page, Widget Area using shortCode. 
 * Plugin URI:  https://wordpress.org/plugins/
 * Version:    1.4.5
 * Author: bPlugins
 * Author URI: http://abuhayatpolash.com
 * License: GPLv3
 * Text Domain: panorama-viewer
 * Domain Path:  /languages
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'panorama_fs' ) ) {
    panorama_fs()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'panorama_fs' ) ) {
        function panorama_fs() {
            global $panorama_fs;
            if ( !isset( $panorama_fs ) ) {
                if ( !defined( 'WP_FS__PRODUCT_8824_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_8824_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $panorama_fs = fs_dynamic_init( array(
                    'id'             => '8824',
                    'slug'           => 'panorama',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_a112d1d1d66d3b480dbea5690d3ff',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                        'days'               => 7,
                        'is_require_payment' => false,
                    ),
                    'menu'           => array(
                        'slug'       => 'edit.php?post_type=bppiv-image-viewer',
                        'first-path' => 'edit.php?post_type=bppiv-image-viewer&page=bppiv-support#welcome',
                    ),
                    'is_live'        => true,
                ) );
            }
            return $panorama_fs;
        }

        // Init Freemius.
        panorama_fs();
        // Signal that SDK was initiated.
        do_action( 'panorama_fs_loaded' );
    }
    // ... Your plugin's main file logic ...
    define( 'BPPIV_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
    define( 'BPPIV_VERSION', ( isset( $_SERVER['HTTP_HOST'] ) && $_SERVER['HTTP_HOST'] === 'localhost' ? time() : '1.4.5' ) );
    defined( 'BPPIV_PATH' ) or define( 'BPPIV_PATH', plugin_dir_path( __FILE__ ) );
    defined( 'BPPIV__FILE__' ) or define( 'BPPIV__FILE__', __FILE__ );
    define( 'BPPIV_HAS_PRO', file_exists( dirname( __FILE__ ) . '/freemius/start.php' ) );
    add_action( 'plugins_loaded', 'bppiv_textdomain' );
    add_action( 'init', 'onInit' );
    add_action( 'wp_ajax_panoramaPremiumChecker', 'panoramaPremiumChecker' );
    add_action( 'wp_ajax_nopriv_panoramaPremiumChecker', 'panoramaPremiumChecker' );
    add_action( 'admin_init', 'registerSettings' );
    add_action( 'rest_api_init', 'registerSettings' );
    add_filter(
        'plugin_row_meta',
        'mppiv_plugin_row_meta',
        10,
        2
    );
    add_action( 'admin_enqueue_scripts', "bppiv_popup_modal" );
    function bppiv_popup_modal() {
        wp_add_inline_script( 'jquery-core', "\r\n            jQuery(document).ready(function(\$){\r\n                \$('.pano-help-link').on('click', function(e){\r\n                    e.preventDefault();\r\n                    if (\$('#pano-help-modal').length === 0) {\r\n                        \$('body').append(`\r\n                            <div id='pano-help-modal' style='\r\n                                position:fixed; top:0; left:0; width:100%; height:100%;\r\n                                background:rgba(0,0,0,0.5); z-index:9999;\r\n                                display:flex; justify-content:center; align-items:center;'>\r\n                                <div style='\r\n                                    background:#fff; padding:20px; max-width:500px; width:90%;\r\n                                    border-radius:6px; box-shadow:0 5px 15px rgba(0,0,0,0.3);'>\r\n                                    <h3>How to Find Google Street View Panorama ID</h3>\r\n                                    <ol>\r\n                                        <li>Open <b>Google Maps</b></li>\r\n                                        <li>Search your location</li>\r\n                                        <li>Drag the yellow Street View icon onto a road</li>\r\n                                        <li>Copy the URL from your browser</li>\r\n                                        <li>Find <b>panoid=</b> in the URL</li>\r\n                                        <li>Copy the text after <b>panoid=</b></li>\r\n                                    </ol>\r\n                                    <p>Example: <code>https://www.google.com/maps/...panoid=JmSoPsBPhqWvaBmOqfFzgA</code></p>\r\n                                    <p>Panorama ID: <code>JmSoPsBPhqWvaBmOqfFzgA</code></p>\r\n                                    <button id='pano-help-close' style='margin-top:10px; cursor:pointer;'>Close</button>\r\n                                </div>\r\n                            </div>\r\n                        `);\r\n                    }\r\n                    \$('#pano-help-modal').fadeIn();\r\n                    \$('#pano-help-close').on('click', function(){ \$('#pano-help-modal').fadeOut(); });\r\n                    \$('#pano-help-modal').on('click', function(e){ if(e.target.id==='pano-help-modal'){ \$(this).fadeOut(); } });\r\n                });\r\n            });\r\n        " );
    }

    function bppiv_textdomain() {
        load_textdomain( 'panorama-viewer', BPPIV_PLUGIN_DIR . 'languages' );
    }

    function onInit() {
        register_block_type( __DIR__ . '/build/blocks/parent' );
        register_block_type( __DIR__ . '/build/blocks/image-360' );
        register_block_type( __DIR__ . '/build/blocks/image-3d' );
        register_block_type( __DIR__ . '/build/blocks/video' );
        register_block_type( __DIR__ . '/build/blocks/video-360' );
        register_block_type( __DIR__ . '/build/blocks/google-street' );
        register_block_type( __DIR__ . '/build/blocks/gallery' );
        register_block_type( __DIR__ . '/build/blocks/tour' );
        register_block_type( __DIR__ . '/build/blocks/gutenberg-block' );
        register_block_type( __DIR__ . '/build/blocks/virtual' );
        register_block_type( __DIR__ . '/build/blocks/product-spot' );
    }

    function panoramaIsPremium() {
        return panorama_fs()->can_use_premium_code();
    }

    function panoramaPremiumChecker() {
        $nonce = sanitize_text_field( $_POST['_wpnonce'] ?? null );
        if ( !wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            wp_send_json_error( 'Invalid Request' );
        }
        wp_send_json_success( [
            'isPipe' => panoramaIsPremium(),
        ] );
    }

    function registerSettings() {
        register_setting( 'panoramaUtils', 'panoramaUtils', [
            'show_in_rest'      => [
                'name'   => 'panoramaUtils',
                'schema' => [
                    'type' => 'string',
                ],
            ],
            'type'              => 'string',
            'default'           => wp_json_encode( [
                'nonce' => wp_create_nonce( 'wp_ajax' ),
            ] ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );
    }

    function mppiv_plugin_row_meta(  $plugin_meta, $plugin_file  ) {
        if ( strpos( $plugin_file, 'panorama/panorama.php' ) !== false && time() < strtotime( '2025-12-05' ) ) {
            $plugin_meta[] = "<a href='https://bplugins.com/coupons/?from=plugins.php&plugin=panorama' target='_blank' style='font-weight: 600; color: #146ef5;'>🎉 Black Friday Sale - Get up to 80% OFF Now!</a>";
        }
        return $plugin_meta;
    }

    //  FRAMEWORK + OTHER INCLUDES
    require_once 'inc/csf/codestar-framework.php';
    require_once 'admin/ads/submenu.php';
    require_once 'product-spot.php';
    $init_file = BPPIV_PATH . 'inc/Init.php';
    if ( file_exists( $init_file ) ) {
        require_once $init_file;
    }
    if ( class_exists( 'BPPIV\\Init' ) ) {
        \BPPIV\Init::instance();
    }
    function bppiv_get_woo_template(  $template  ) {
        $path = BPPIV_PATH . 'inc/Woocommerce/template/' . $template;
        if ( file_exists( $path ) ) {
            require $path;
        }
    }

    // get values from csf
    function bppiv_isset(  $array  ) {
        return function (
            $key1,
            $isBoolean = false,
            $default = false,
            $key2 = ''
        ) use($array) {
            if ( isset( $array[$key1][$key2] ) ) {
                return ( $isBoolean ? (bool) $array[$key1][$key2] : $array[$key1][$key2] );
            }
            if ( isset( $array[$key1] ) ) {
                return ( $isBoolean ? (bool) $array[$key1] : $array[$key1] );
            }
            return $default;
        };
    }

    require_once 'shortcode.php';
    add_action( 'init', function () {
        if ( panorama_fs()->is_free_plan() ) {
            require_once 'inc/metabox-options-free.php';
        }
    }, 0 );
}