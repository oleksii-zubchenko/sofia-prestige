<?php
namespace BPPIV\Woocommerce;

class ProductMetaPro{

    public function register(){
        $prefix = '_bppiv_product_';
        
        \CSF::createMetabox( $prefix, array(
            'title'        => esc_html__('Panorama Settings', 'panorama-viewer'),
            'post_type'    =>  'product',
            'show_restore' => true,
        ));

        \CSF::createSection( $prefix, array(
            'fields' => array(
              array(
                'id' => 'shortcode',
                'type' => 'content',
                'title' => __("Shortcode (Pro)", "panorama-viewer"),
                'content' => '<input style="width:190px;" type="text" onclick="this.select()" readonly value="[panorama_product_viewer]" />',
                'desc' => __("Copy the shortcode and place where you want to show the model", "panorama-viewer")
              ),
              // panorama controls
              array(
                'id'       => 'type',
                'type'     => 'button_set',
                'title'    => __('Panorama Type.', 'panorama-viewer'),
                'subtitle' => __('Choose Panorama Type', 'panorama-viewer'),
                'desc'     => __('Select Panorama, Default- Image.', 'panorama-viewer'),
                'multiple' => false,
                'options'  => array(
                  'image'   => 'Image',
                  // 'image360'=> 'Image 360°',
                  'video'   => 'Video',
                  // 'gallery'  => 'Gallery',
                  // 'tour360'  => 'Tour 360°',
                  // 'gstreet'  => 'Google Street View',
                ),
                'default'  => 'image'
              ),

              array(
                'id'         => 'viewer_position',
                'type'       => 'radio',
                'title'      => esc_html__('Image/Video Position', 'panorama-viewer'),
                'options'    => array(
                  'none' => esc_html__('None', 'panorama-viewer'),
                  'top' => esc_html__('Top of the product image', 'panorama-viewer'),
                  'bottom' => esc_html__('Bottom of the product image','panorama-viewer'),
                  'replace' => esc_html__('Replace Product Image with 3D', 'panorama-viewer')
                ),
                'default'    => 'none'
              ),

              array(
                'id'           => 'image_src',
                'type'         => 'upload',
                'library'      => 'image',
                'button_title' => __('Upload Panoramic Image', 'panorama-viewer'),
                'title'        => __('Image Source.', 'panorama-viewer'),
                'desc'         => __('To create an image panorama, Panoramic image is Recommended. You can also use external Panoramic Image link here.', 'panorama-viewer'),
                'dependency'   => array( 'type', '==', 'image' ),
              ),
              // video source
              array(
                'id'           => 'video_src',
                'type'         => 'upload',
                'library'      => 'video',
                'button_title' => __('Upload Video', 'panorama-viewer'),
                'title'        => __('Video Source.', 'panorama-viewer'),
                'desc'         => __('Upload Panoramic Video', 'panorama-viewer'),
                'dependency'   => array( 'type', '==', 'video' ),
              ),

              array(
                'id'           => 'video360',
                'type'         => 'switcher',
                'title' => __('360° Video', 'panorama-viewer'),
                'desc'         => __('Enable if the video is 360°', 'panorama-viewer'),
                'dependency'   => array( 'type', '==', 'video' ),
              ),


              array(
                'id'       => 'autoRotate',
                'type'     => 'switcher',
                'title'    => __('Auto Rotate ?', 'panorama-viewer'),
                'desc'     => __('Enable or Disable Auto Rotate', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No',
                'default'  => false,
                'dependency'   => array( 'type', 'any', 'image'  ),
              ),
              array(
                'id'       => 'speed',
                'type'     => 'spinner',
                'title'    => __('Auto Rotate Speed', 'panorama-viewer'),
                'subtitle' => __('Choose Auto Rotate Speed', 'panorama-viewer'),
                'desc'     => __('Auto rotate speed as in degree per second. Positive is counter-clockwise and negative is clockwise.', 'panorama-viewer'), 
                'default'  => 2.0,
                'dependency' => array( 'type|auto_rotate', 'any|==', 'image|true' ),
              ),
              array(
                'id'       => 'showControls',
                'type'     => 'switcher',
                'title'    => __('Show Default Controls?', 'panorama-viewer'),
                'subtitle' => __('Show / Hide Switch for Default Control.', 'panorama-viewer'),
                'desc'     => __('Show or Hide Control', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No',
                'default'  => true,
                'dependency'   => array( 'type', 'any', 'image' ),
              ),
              array(
                'id'    => 'initialView',
                'type'  => 'spacing',
                'title' => 'Initial Values',
                'subtitle'=> __('Set The Custom values for Initial View. Default Initial Values are ("X=2.3 Y=-360.4 Z=120")', 'model-viewer'),
                'desc'    => __('Set Your Desire Values. (X= Horizontal Position, Y= Vertical Position, Z= Zoom Level/Position) ', 'model-viewer'),
                'default'  => array(
                  'top'    => 0,
                  'right'  => 0,
                  'bottom' => 100,
                ),
                'left'   => false,
                'show_units' => false,
                'top_icon'    => 'pitch',
                'right_icon'  => 'yaw',
                'bottom_icon' => 'hfov',
                'dependency'   => array( 'type', 'any', 'video'),
              ),
     
              // Video Settings
              array(
                'id'       => 'video_autoplay',
                'type'     => 'switcher',
                'title'    => __('Auto Play ?', 'panorama-viewer'),
                'subtitle'     => __('Enable or Disable Auto Play', 'panorama-viewer'),
                'desc'     => __('Video should be muted', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No',
                'default'  => true,
                'dependency'   => array( 'type', '==', 'video' ),
              ),
              array(
                'id'       => 'video_mute',
                'type'     => 'switcher',
                'title'    => __('Video Muted', 'panorama-viewer'),
                'subtitle' => __('Enable or Disable Video Mute', 'panorama-viewer'),
                'desc'     => __('Specify if the video should auto play', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No',
                'default'  => true,
                'dependency'   => array( 'type', '==', 'video' ),
              ),
              array(
                'id'       => 'video_loop',
                'type'     => 'switcher',
                'title'    => __('Video Loop ?', 'panorama-viewer'),
                'desc'     => __('Enable or Disable Video Loop', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No', 
                'default'  => false,
                'dependency'   => array( 'type', '==', 'video' ),
              ),
              array(
                'id'       => 'video_show_controls',
                'type'     => 'switcher',
                'title'    => __('Show video controls?', 'panorama-viewer'),
                'desc'     => __('Enable if you want to show video controls', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No', 
                'default'  => false,
                'dependency'   => array( 'type', '==', 'video' ),
              ),

              array(
                'id'       => 'title',
                'type'     => 'text',
                'title'    => __('Title', 'panorama-viewer'),
                'subtitle' => __('Display Title Text.', 'panorama-viewer'),
                'desc'     => __('Input Title Text', 'panorama-viewer'),
                'placeholder' => "360° Image",
                'default' => "",
                'dependency'   => array( 'type', '==', 'image' ),
              ),

              array(
                'id'       => 'author',
                'type'     => 'text',
                'title'    => __('Author', 'panorama-viewer'),
                'subtitle' => __('Display Author Name."', 'panorama-viewer'),
                'desc'     => __('Input Author Name', 'panorama-viewer'),
                'placeholder' => "bPlugins",
                'default' => "",
                'dependency'   => array( 'type', '==', 'image' ),
              ),
          
            ) // End fields
        ) );
    }
}