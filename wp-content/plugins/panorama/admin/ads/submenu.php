<?php

add_action( 'admin_enqueue_scripts', 'custom_admin_style' );

function custom_admin_style() {
    wp_register_style( 'bppiv_admin_custom_css', plugin_dir_url(__FILE__) . 'style.css', false, BPPIV_VERSION );
    wp_enqueue_style( 'bppiv_admin_custom_css' );
}

add_action('admin_menu', 'bppiv_add_submenu_page');
add_action( 'admin_enqueue_scripts', 'adminEnqueueScripts' );

function bppiv_add_submenu_page(){
    add_submenu_page(
        'edit.php?post_type=bppiv-image-viewer',
        'Demo and Help',
        'Demo and Help', 
        'manage_options', 
        'bppiv-support',
        'renderCurrentDashboardPage'
    );
}


function renderCurrentDashboardPage(){ ?>
    <div
        id='bppivCurrentDashboard'
        data-info='<?php echo esc_attr( wp_json_encode( [
            'version' => BPPIV_VERSION,
            'isPremium' => panoramaIsPremium(),
            'hasPro' => BPPIV_HAS_PRO
        ] ) ); ?>'
    ></div>
<?php }

function adminEnqueueScripts( $screen ) {

    if( $screen == 'bppiv-image-viewer_page_bppiv-support'){
        wp_enqueue_style( 'current-admin-dashboard', BPPIV_PLUGIN_DIR . 'build/admin-dashboard.css', [], BPPIV_VERSION );
        wp_enqueue_script( 'current-admin-dashboard', BPPIV_PLUGIN_DIR . 'build/admin-dashboard.js', [ 'react', 'react-dom' ], BPPIV_VERSION, true );
    }

     wp_enqueue_script('current-admin-post', BPPIV_PLUGIN_DIR . 'build/admin-post.js', [], BPPIV_VERSION);
     wp_enqueue_style('current-admin-post', BPPIV_PLUGIN_DIR . 'build/admin-post.css', [], BPPIV_VERSION);
}
  






