<?php
// Shortcode for Panorama  Viewer
function bppiv_image_viewer( $atts ){
    extract( shortcode_atts( array(
            'id'    => null,
    ), $atts )); ob_start(); ?>

    <?php 
    // Check Post-Type
    $post_type = get_post_type($id);
    if($post_type != 'bppiv-image-viewer'){
            return false;
    }
    // Meta Data
    $bppiv_meta = get_post_meta($id, '_bppivimages_', true); 

    // // Meta Options
    $bppiv_width    = '100%';
    $bppiv_height   = '320px';

    if( isset($bppiv_meta['bppiv_image_width']['width']) ) {
        $bppiv_width = $bppiv_meta['bppiv_image_width']['width'].$bppiv_meta['bppiv_image_width']['unit'];
    }
    if( isset($bppiv_meta['bppiv_image_height']['height']) ) {
        $bppiv_height = $bppiv_meta['bppiv_image_height']['height'].$bppiv_meta['bppiv_image_height']['unit'];
    }

    $pan_type = isset($bppiv_meta['bppiv_type']) ? $bppiv_meta['bppiv_type'] : '';
      
    // Panorama Scripts and style 
    wp_enqueue_script( 'bppiv-three' );
    wp_enqueue_script( 'bppiv-panolens' );
    wp_enqueue_script( 'bppiv-pannellum-js' );
    wp_enqueue_script( 'bppiv-init' );   
    
    wp_enqueue_style( 'bppiv-font-material' );
    wp_enqueue_style( 'bppiv-pannellum-css' );
    wp_enqueue_style( 'bppiv-main-style' );
    ?>

    <?php if( $pan_type == 'image' || $pan_type ==='image360' || $pan_type ==='tour360' || $pan_type ==='video'): ?>

    <div id="bppiv_panorama<?php echo esc_attr($id); ?>" class="bppiv_panorama" data-settings='<?php echo esc_attr( wp_json_encode( $bppiv_meta)); ?>'>

    </div>

    <?php endif; ?>

    <style>
        <?php echo '#bppiv_panorama'. esc_attr($id); ?>{
            width: <?php echo esc_attr($bppiv_width); ?>;
            height: <?php echo esc_attr($bppiv_height); ?>;
        }
    </style>

    <?php   
    return ob_get_clean();

}
add_shortcode( 'panorama', 'bppiv_image_viewer' );
