<?php

namespace BPPIV\Woocommerce;

class Settings{

    public function register(){
        if(!is_admin()){
          return;
        }

        $prefix = '_bppiv_woo';
        \CSF::createOptions( $prefix, array(
            'menu_title'  => __("Woocommerce Settings"),
            'menu_slug'   => '3dviewer-settdings',
            'menu_type'   => 'submenu',
            'menu_parent' => 'edit.php?post_type=bppiv-image-viewer',
            'theme'       => 'light',
            'framework_title' => __("Woocommerce Settings", 'panorama-viewer'),
            'menu_position' => 10,
            'footer'      => false,
            'footer_credit'  => 'bPlugins',
            'footer_text'    => '',
        ) );
        
           // Create a section
        \CSF::createSection( $prefix, array(
            'title'  => 'Tab Title 1',
            'fields' => array(
        
            // A text field
            array(
                'id'       => 'type',
                'type'     => 'button_set',
                'title'    => __('Panorama Type.', 'panorama-viewer'),
                'subtitle' => __('Choose Panorama Type', 'panorama-viewer'),
                'desc'     => __('Select Panorama, Default- Image.', 'panorama-viewer'),
                'multiple' => false,
                'options'  => array(
                  'image'   => 'Image',
                  'image360'=> 'Image 360°',
                  'video'   => 'Video',
                  'gallery'  => 'Gallery',
                ),
                'default'  => 'image'
              ),
          
              // Load More Button
              array(
                'id'         => 'loadMore_btn_text',
                'type'       => 'text',
                'title'      => __('LoadMore Button Text', 'bgallery'),
                'subtitle'   => __('You can use Custom Text in Button', 'bgallery'),
                'desc'       => __('Input LoadMore Button Text', 'bgallery'),
                'default'    => 'Load More',
                'dependency'   => array( 'type', '==', 'gallery' ),
              ),
              array(
                'id'         => 'loadMore_text_color',
                'type'       => 'color',
                'title'      => __('LoadMore Text Color', 'bgallery'),
                'subtitle'   => __('You can use Custom Color', 'bgallery'),
                'desc'       => __('Choose LoadMore Button Text Color', 'bgallery'),
                'default'    => '#fff',
                'dependency'   => array( 'type', '==', 'gallery' ),
              ),
              array(
                'id'         => 'loadMore_btn_bg',
                'type'       => 'color',
                'title'      => __('LoadMore Button Background', 'bgallery'),
                'desc'       => __('Choose LoadMore Button Background Color', 'bgallery'),
                'default'    => '#000',
                'dependency'   => array( 'type', '==', 'gallery' ),
              ),
              array(
                'id'         => 'loadMore_hover_bg',
                'type'       => 'color',
                'title'      => __('LoadMore Hover Background', 'bgallery'),
                'desc'       => __('Choose LoadMore Hover Background Color', 'bgallery'),
                'default'    => '#222',
                'dependency'   => array( 'type', '==', 'gallery' ),
              ),
              // Google Street View 
              array(
                'id'           => 'bppiv_pano_id',
                'type'         => 'text',
                'title'        => __('Panorama ID', 'panorama-viewer'),
                'desc'         => __('Input here Google Street View Panorama Id <a href="https://e4youth.org/blog/2019/02/05/snapping-360-images-from-google-street-view/" target="_blank">Click here for Panorama ID Details</a>', 'panorama-viewer'),
                'placeholder'  => 'Paste here panorama id',
                'default'      => 'JmSoPsBPhqWvaBmOqfFzgA',
                'dependency'   => array( 'type', '==', 'gstreet' ),
            ),
          
            array(
              'id'       => 'bppiv_auto_rotate',
              'type'     => 'switcher',
              'title'    => __('Auto Rotate ?', 'panorama-viewer'),
              'desc'     => __('Enable or Disable Auto Rotate', 'panorama-viewer'),
              'text_on'  => 'Yes',
              'text_off' => 'No',
              'default'  => false,
              'dependency'   => array( 'type', 'any', 'image,image360'  ),
            ),
          array(
            'id'       => 'bppiv_speed',
            'type'     => 'spinner',
            'title'    => __('Auto Rotate Speed', 'panorama-viewer'),
            'subtitle' => __('Choose Auto Rotate Speed', 'panorama-viewer'),
            'desc'     => __('Auto rotate speed as in degree per second. Positive is counter-clockwise and negative is clockwise.', 'panorama-viewer'), 
            'default'  => 2.0,
            'dependency' => array( 'type|bppiv_auto_rotate', 'any|==', 'image,image360|true' ),
          ),
          array(
            'id'       => 'control_show_hide',
            'type'     => 'switcher',
            'title'    => __('Show/Hide Default Control ?', 'panorama-viewer'),
            'subtitle' => __('Show / Hide Switch for Default Control.', 'panorama-viewer'),
            'desc'     => __('Show or Hide Control', 'panorama-viewer'),
            'text_on'  => 'Yes',
            'text_off' => 'No',
            'default'  => false,
            'dependency'   => array( 'type', 'any', 'image,image360' ),
          ),
         
          array(
            'id'       => 'custom_control',
            'type'     => 'switcher',
            'title'    => __('Custom Control', 'panorama-viewer'),
            'subtitle' => __('Custom Control will replace default control bar', 'panorama-viewer'),
            'desc'     => __('Show or Hide Custom Control. Default "NO"', 'panorama-viewer'),
            'text_on'  => 'Yes',
            'text_off' => 'No',
            'default'  => false,
            'dependency'   => array( 'type', '==', 'image360' ),
          ),
          array(
            'id'       => 'bppiv_auto_load',
            'type'     => 'switcher',
            'title'    => __('Auto Load', 'panorama-viewer'),
            'desc'     => __('Enable or Disable Autoload', 'panorama-viewer'),
            'subtitle'     => __('Image will be automatically load without click', 'panorama-viewer'),
            'text_on'  => 'Yes',
            'text_off' => 'No',
            'default'  => true,
            'dependency'   => array( 'type', '==', 'image360' ),
          ),
          array(
            'id'       => 'draggable_360',
            'type'     => 'switcher',
            'title'    => __('Draggable ', 'panorama-viewer'),
            'desc'     => __('Enable or Disable mouse and touch dragging', 'panorama-viewer'),
            'subtitle'     => __('Image will be Draggable with this feature', 'panorama-viewer'),
            'text_on'  => 'Yes',
            'text_off' => 'No',
            'default'  => true,
            'dependency'   => array( 'type', '==', 'image360' ),
          ),
          array(
            'id'       => 'compass_360',
            'type'     => 'switcher',
            'title'    => __('Compass ', 'panorama-viewer'),
            'desc'     => __('Show or Hide Compass.', 'panorama-viewer'),
            'subtitle' => __('Enable or Disable Compass. Default "No"', 'panorama-viewer'),
            'text_on'  => 'Yes',
            'text_off' => 'No',
            'default'  => true,
            'dependency'   => array( 'type', '==', 'image360' ),
          ),
          
          
              // Video Settings
              array(
                'id'       => 'bppiv_auto_play',
                'type'     => 'switcher',
                'title'    => __('Auto Play ?', 'panorama-viewer'),
                'desc'     => __('Enable or Disable Auto Play', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No',
                'default'  => true,
                'dependency'   => array( 'type', '==', 'video' ),
              ),
              array(
                'id'       => 'bppiv_video_mute',
                'type'     => 'switcher',
                'title'    => __('Video Mute ?', 'panorama-viewer'),
                'subtitle' => __('Enable or Disable Video Mute', 'panorama-viewer'),
                'desc'     => __('Specify if the video should auto play', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No',
                'default'  => true,
                'dependency'   => array( 'type', '==', 'video' ),
              ),
              array(
                'id'       => 'bppiv_video_loop',
                'type'     => 'switcher',
                'title'    => __('Video Loop ?', 'panorama-viewer'),
                'desc'     => __('Enable or Disable Video Loop', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No', 
                'default'  => false,
                'dependency'   => array( 'type', '==', 'video' ),
              ),
              array(
                'id'       => 'control_show_hide_video',
                'type'     => 'switcher',
                'title'    => __('Hide Control Bar ?', 'panorama-viewer'),
                'desc'     => __('Show or Hide Control', 'panorama-viewer'),
                'text_on'  => 'Yes',
                'text_off' => 'No', 
                'default'  => false,
                'dependency'   => array( 'type', '==', 'video' ),
              ),
            )
        ) );
        
    }
}
