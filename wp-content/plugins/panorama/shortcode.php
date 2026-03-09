<?php
// Shortcode for Panorama  Pro
function bppiv_image_viewer( $atts ){
    extract( shortcode_atts( array(
            'id'    => null,
            
    ), $atts )); ob_start(); ?>

    <?php 
    $block = null;
    // Check Post-Type
    $post_type = get_post_type($id);
    if($post_type != 'bppiv-image-viewer'){
        return false;
    }
    // Meta Data
    $bppiv_meta = get_post_meta($id, '_bppivimages_', true); 

    $bppiv_width    = '100%';
    $bppiv_height   = '320px';

    if( isset($bppiv_meta['bppiv_image_width']['width']) ) {
        $bppiv_width = $bppiv_meta['bppiv_image_width']['width'].$bppiv_meta['bppiv_image_width']['unit'];
    }
    if( isset($bppiv_meta['bppiv_image_height']['height']) ) {
        $bppiv_height = $bppiv_meta['bppiv_image_height']['height'].$bppiv_meta['bppiv_image_height']['unit'];
    }

    $pan_type = isset($bppiv_meta['bppiv_type']) ? $bppiv_meta['bppiv_type'] : '';
    
    // Load More Meta Data
    $btn_text       = isset($bppiv_meta['loadMore_btn_text']) ? $bppiv_meta['loadMore_btn_text'] : '';
    $btn_color      = isset($bppiv_meta['loadMore_text_color']) ? $bppiv_meta['loadMore_text_color'] : '';
    $btn_bg         = isset($bppiv_meta['loadMore_btn_bg']) ? $bppiv_meta['loadMore_btn_bg'] : '';
    $btn_hover_bg   = isset($bppiv_meta['loadMore_hover_bg']) ? $bppiv_meta['loadMore_hover_bg'] : '';

    $get_value = bppiv_isset($bppiv_meta);

    if(file_exists(BPPIV_PATH."blocks/$pan_type.php")){
        include(BPPIV_PATH."blocks/$pan_type.php");
    }

    // echo "<pre>";
    // print_r($block);
    // echo "</pre>";

    return render_block($block);
   
    if( $pan_type !== 'video2'){
        wp_enqueue_script( 'bppiv-pro' );   
    
        wp_enqueue_style( 'bppiv-font-material' );
        wp_enqueue_style( 'bppiv-pannellum-css' );
        wp_enqueue_style( 'bppiv-main-style' );
    }

    ?>


    <?php if( $pan_type === 'gallery'): ?>

        <div id="gallery-container" ></div>
            <!-- Load MOre Button -->
        <?php if( is_array($bppiv_meta['bppiv_pan_gallery']) && count( $bppiv_meta['bppiv_pan_gallery']) > 0 ): ?>
        <div class="pan_loadMore"><?php echo esc_html($btn_text ); ?></div>
        <?php endif; ?>
    
        <div id="panorama-container" class="bppiv_panorama" data-settings='<?php echo esc_attr( wp_json_encode( $bppiv_meta)); ?>'>
            <!-- Progress bar -->
            <div id="bppiv-progress-bar"></div>
            <!-- Close button -->
            <div class="close"> <i class="material-icons">close</i> </div>
            <!-- Main container -->
            <div id="bppiv-main-container"></div>

        </div>



    <?php elseif( $pan_type === 'gstreet'): ?>
        <div id="bppiv_panorama<?php echo esc_attr($id); ?>" class="bppiv_panorama pan_gstreet" data-settings='<?php echo esc_attr( wp_json_encode( $bppiv_meta)); ?>'>
        </div>
    <!-- Image 360 Degree -->
    <?php elseif( $pan_type === 'image360'): ?>
        
        <div id="bppiv_panorama<?php echo esc_attr($id); ?>" class="bppiv_panorama" data-settings='<?php echo esc_attr( wp_json_encode( $bppiv_meta)); ?>'>

        <!-- Custom Control -->
        <?php if($pan_type ==='image360' && isset($bppiv_meta['custom_control']) && $bppiv_meta['custom_control'] === "1"): ?>
            <div id="controls">
                <div class="ctrl" id="pan-up">&#9650;</div>
                <div class="ctrl" id="pan-down">&#9660;</div>
                <div class="ctrl" id="pan-left"><svg width="20px" height="15px" xmlns="http://www.w3.org/2000/svg"><path d="M14 5v10l-9-5 9-5z"/></svg></div>
                <div class="ctrl" id="pan-right"><svg width="20px" height="15px" xmlns="http://www.w3.org/2000/svg"><path d="M15 10l-9 5V5l9 5z"/></svg></div>
                <div class="ctrl" id="zoom-in">&plus;</div>
                <div class="ctrl" id="zoom-out">&minus;</div>
                <div class="ctrl" id="fullscreen">&#x2922;</div>
            </div>
        <?php endif; ?>
    </div>
    <?php elseif( $pan_type === 'video2'): 
        wp_enqueue_script('videojs');
        wp_enqueue_style('videojs');
        wp_enqueue_script('videojs-vr');
        wp_enqueue_style('videojs-vr');
        wp_enqueue_script('videojs-init');

        $attributes = [
            'enabledInitialView' => $bppiv_meta['initial_view_video'] ?? null,
            'initialView' => $bppiv_meta['initial_view_video_property'] ?? null,
        ]
        ?>
        <div>
            <div class="bppiv_panorama_video2" style="width: <?php echo esc_attr($bppiv_width) ?>" data-attributes="<?php echo esc_attr(wp_json_encode($attributes)) ?>">
                <video class="video-js"  playsinline webkit-playsinline
                    preload="auto" id="<?php echo esc_attr('bppiv_panorama_video2_'.$id) ?>" controls>
                    <source src="<?php echo esc_url($bppiv_meta['bppiv_video_src']['url'] ?? '') ?>" />
                    <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a
                        web browser that supports HTML5 video
                    </p>
                </video>
            </div>
        </div>
     
    <?php else: ?>
        <div id="bppiv_panorama<?php echo esc_attr($id); ?>" class="bppiv_panorama" data-settings='<?php echo esc_attr( wp_json_encode( $bppiv_meta)); ?>'></div>
    <?php endif; ?>

    <style>
        <?php echo '#bppiv_panorama'. esc_attr($id); ?> {
            width: <?php echo esc_attr($bppiv_width); ?>;
            height: <?php echo esc_attr($bppiv_height); ?>;
        }
        .pan_loadMore {
            background: <?php echo esc_attr($btn_bg); ?>;
            color: <?php echo esc_attr($btn_color); ?>;
        }
        .photo:hover {
            border-color: <?php echo esc_attr($btn_bg); ?>;
        }
        .pan_loadMore:hover,
        .item-badge:hover {
            background: <?php echo esc_attr($btn_hover_bg); ?>;
            color: <?php echo esc_attr($btn_color); ?>;
        }
    </style>

    <?php  
    if(in_array($pan_type, ['video', 'video2'])){
        ?>
     <style>
        .camera_position_wrapper {
            position: absolute;
            top: 0px;
            left: 0px;
            display: flex;
            width: 100%;
        }
        .camera_position_wrapper .camera_close {
            position: absolute;
            top: 0;
            right: 0;
            background: #333;
            color: #fff;
            padding: 10px;
            font-size: 24px;
            line-height: 0;
            height: 100%;
        }
        .camera_position_wrapper p {
            margin: 0;
            flex: 1;
            padding: 0 10px;
            background: #fff;
        }
        .bppiv_panorama_video2 {
            max-width: 100%;
            position: relative;
        }
        .bppiv_panorama_video2 video {
            max-width: 100%;
            width: 100%;
        }
    </style>

    <?php
    }
    return ob_get_clean();

}
add_shortcode( 'panorama', 'bppiv_image_viewer' );

// Shortcode for Panorama  Pro
function panorama_product_viewer_callback( $attrs ){

    ob_start(); 

    $meta = get_post_meta(get_the_ID(), '_bppiv_product_', true);
    if($meta['video360'] === '1' && $meta['type'] === 'video'){
        wp_enqueue_script('bppiv-panolens');
    }else if($meta['type'] === 'image'){
        wp_enqueue_script('bppiv-pannellum-js');
        wp_enqueue_style('bppiv-pannellum-css');
    }

    wp_enqueue_script('bppiv-product');
    wp_enqueue_style('bppiv-product');

    $attributes = '';
    if($meta['video_autoplay']){
        $attributes .= ' autoplay';
    }

    if($meta['video_mute']){
        $attributes .= ' muted';
    }

    if($meta['video_loop']){
        $attributes .= ' loop';
    }

    if($meta['video_show_controls']){
        $attributes .= ' controls';
    }

   ?>
   <div id="bppiv_product_panorama" data-settings="<?php echo esc_attr(wp_json_encode($meta)) ?>">
        <?php if($meta['type'] === 'video' && $meta['video360'] === '0') { ?>
            <video style="max-width: 100%;" <?php echo esc_attr($attributes);  ?> src="<?php echo esc_url($meta['video_src']) ?>"></video>
        <?php } ?>
        
    </div>
   <?php
   return ob_get_clean();
}
add_shortcode( 'panorama_product_viewer', 'panorama_product_viewer_callback' );
