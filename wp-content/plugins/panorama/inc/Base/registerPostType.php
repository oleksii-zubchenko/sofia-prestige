<?php
namespace BPPIV\Base;

class registerPostType{

    public function register(){
        add_action( 'init', [$this, 'init']);
        add_action( 'admin_menu', [$this, 'admin_menu']);
        add_shortcode('panorama', [$this, 'bppiv_shortcode']);
        add_shortcode('virtual-tour', [$this, 'bppiv_virtual_tour_shortcode']);
        add_filter( 'manage_bppiv-image-viewer_posts_columns', [$this, 'bppiv_columns_head_only_panorama'], 10 );
        add_action( 'manage_bppiv-image-viewer_posts_custom_column', [$this, 'bppiv_columns_content_only_panorama'], 10, 2);
        add_filter( 'manage_virtual_tour_posts_columns', [$this, 'bppiv_columns_head_only_virtual_tour'], 10 );
        add_action( 'manage_virtual_tour_posts_custom_column', [$this, 'bppiv_columns_content_only_virtual_tour'], 10, 2);
        add_action( 'edit_form_after_title', [$this, 'bppiv_shortcode_area'] );
        add_filter( 'admin_footer_text', [$this, 'bppiv_admin_footer'] );
        add_filter( 'gettext', [$this, 'bppiv_change_publish_button'], 10, 2 );
        add_filter( 'post_updated_messages', [$this, 'bppiv_updated_messages'] );
        add_action( 'admin_head-post.php', [$this, 'bppiv_hide_publishing_actions'] );
        add_action( 'admin_head-post-new.php', [$this, 'bppiv_hide_publishing_actions'] );
        if ( is_admin() ) {
            add_filter('post_row_actions', [$this, 'bppiv_remove_row_actions'],  10, 2 );
        }
       
    }

    public function init(){
        $labels = array(
            'name'           => __( 'Panorama Viewer', 'panorama-viewer' ),
            'menu_name'      => __( 'Panorama Viewer', 'panorama-viewer' ),
            'name_admin_bar' => __( 'Panorama Viewer', 'panorama-viewer' ),
            'add_new'        => __( 'Add New', 'panorama-viewer' ),
            'add_new_item'   => __( 'Add New', 'panorama-viewer' ),
            'new_item'       => __( 'New Panorama ', 'panorama-viewer' ),
            'edit_item'      => __( 'Edit Panorama ', 'panorama-viewer' ),
            'view_item'      => __( 'View Panorama ', 'panorama-viewer' ),
            'all_items'      => __( 'All Panoramas', 'panorama-viewer' ),
            'not_found'      => __( 'Sorry, we couldn\'t find the Feed you are looking for.' ),
        );
        $args = array(
            'labels'          => $labels,
            'description'     => __( 'Panorama Options.', 'panorama-viewer' ),
            'public'          => false,
            'show_ui'         => true,
            'show_in_menu'    => true,
            'menu_icon'       => 'dashicons-welcome-view-site',
            'query_var'       => true,
            'rewrite'         => array(
            'slug' => 'panorama-viewer',
        ),
        'capability_type' => 'post',
        'has_archive'     => false,
        'hierarchical'    => false,
        'menu_position'   => 20,
        'supports'        => array( 'title' ),
        );
        \register_post_type( 'bppiv-image-viewer', $args );

       
        register_post_type('virtual_tour', [
            'labels' => [
                'name' => '360° Virtual Tour',
                'singular_name' => 'Virtual Tour',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Tour',
                'edit_item' => 'Edit Tour',
                'not_found' => 'There was no tour please add one',
                'search_items' => 'Search Tour',
                'view_item' => 'View Tour',
                'not_found_in_trash' => 'No Tour found in trash',
                'item_updated' => 'Tour updated',
            ],
            'public' => true,
            'has_archive' => true,
            "show_in_rest" => true,
            "template_lock" => "all",
            "template" => [["panorama/virtual-tour"]],
            'show_in_menu' => 'edit.php?post_type=bppiv-image-viewer', 
        ]);

        register_post_type('product_spot', [
            'labels' => [
                'name' => 'Product Spot',
                'singular_name' => 'Product Spot',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Product Spot',
                'edit_item' => 'Edit Product Spot',
                'not_found' => 'There was no product spot please add one',
                'search_items' => 'Search Product Spot',
                'view_item' => 'View Product Spot',
                'not_found_in_trash' => 'No Product Spot found in trash',
                'item_updated' => 'Product Spot updated',
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-products',
            'template' => [['psb/product-spot']],
            'template_lock' => 'all',
            'show_in_menu' => 'edit.php?post_type=bppiv-image-viewer', 
        ]);
    }

    function admin_menu(){
        remove_submenu_page(
            'edit.php?post_type=bppiv-image-viewer',
            'post-new.php?post_type=bppiv-image-viewer'
        );
    }

    function bppiv_virtual_tour_shortcode($atts){
        $post_id = $atts['id'];
        $post = get_post( $post_id );

        if ( !$post ) {
            return '';
        }

        if ( post_password_required( $post ) ) {
            return get_the_password_form( $post );
        }

        switch ( $post->post_status ) {
            case 'publish':
                return $this->displayContent( $post );
                
            case 'private':
                if (current_user_can('read_private_posts')) {
                    return $this->displayContent( $post );
                }
                return '';
                
            case 'draft':
            case 'pending':
            case 'future':
                if ( current_user_can( 'edit_post', $post_id ) ) {
                    return $this->displayContent( $post );
                }
                return '';
                
            default:
                return '';
        }
    }

    function bppiv_shortcode($atts){
        $post_id = $atts['id'];
        $post = get_post( $post_id );

        if ( !$post ) {
            return '';
        }

        if ( post_password_required( $post ) ) {
            return get_the_password_form( $post );
        }

        switch ( $post->post_status ) {
            case 'publish':
                return $this->displayContent( $post );
                
            case 'private':
                if (current_user_can('read_private_posts')) {
                    return $this->displayContent( $post );
                }
                return '';
                
            case 'draft':
            case 'pending':
            case 'future':
                if ( current_user_can( 'edit_post', $post_id ) ) {
                    return $this->displayContent( $post );
                }
                return '';
                
            default:
                return '';
        }
    }
    
    function displayContent( $post ){
        $blocks = parse_blocks( $post->post_content );
        if (empty($blocks)) {
            return '';
        }
        return render_block( $blocks[0] );
    }

    function bppiv_columns_head_only_panorama( $defaults ){
        unset($defaults['date']);
        $defaults['directors_name'] = 'ShortCode';
        $defaults['date'] = 'Date';
        return $defaults;
    }

    function bppiv_columns_content_only_panorama( $column_name, $post_ID ){
        if ($column_name == 'directors_name') {
            echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr($post_ID) . '">
                    <input value="[panorama id=' . esc_attr($post_ID) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr($post_ID) . '\')" readonly>
                    <span class="tooltip">Copy To Clipboard</span>
                  </div>';
        }
    }

    function bppiv_columns_head_only_virtual_tour( $defaults ){
        unset($defaults['date']);
        $defaults['shortcode'] = 'ShortCode';
        $defaults['date'] = 'Date';
        return $defaults;
    }

    function bppiv_columns_content_only_virtual_tour( $column_name, $post_ID ){
        if ($column_name == 'shortcode') {
            echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr($post_ID) . '">
                    <input value="[virtual-tour id=' . esc_attr($post_ID) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr($post_ID) . '\')" readonly>
                    <span class="tooltip">Copy To Clipboard</span>
                  </div>';
        }
    }

    function bppiv_shortcode_area() {
        global $post;
        
        if ( $post->post_type == 'bppiv-image-viewer' ) {

            define('bpl_meta_fields', [
                'id' => '_bppivimages_',
                'title' => 'Fieds',
                'sections' => [
                    [
                        'name' => 'first',
                        'title' => 'First Section',
                        'fields' => [
                            [
                                'id' => 'bppiv_type',
                                'title' => 'Text',
                                'field' => 'text',
                                'attributes' => [
                                    'style' => ['width' => '50%']
                                ]
                            ],
                        ]
                    ]
                ]
            ]);

            wp_enqueue_style('bppiv-meta');
            wp_enqueue_script('bppiv-meta');

            ?>
            <style>
                .shortcode_gen {
                    margin-top: 20px;
                    font-family: sans-serif;
                }

                .shortcode_gen label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: 600;
                    font-size: 14px;
                }

                .shortcode_input_wrapper {
                    position: relative;
                    display: inline-block;
                }

                #bppiv_shortcode {
                    width: 300px;
                    padding: 8px 12px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    font-size: 14px;
                    cursor: pointer;
                    display: block;
                    text-align: center;
                    background: #4527a4;
                    color:#fff;
                    transition: border-color 0.3s ease;
                }

                #bppiv_shortcode:hover {
                    border-color: #007cba;
                }

                .copied-message {
                    position: absolute;
                    top: -25px;
                    left: 0;
                    background: #007cba;
                    color: white;
                    padding: 3px 8px;
                    border-radius: 4px;
                    font-size: 12px;
                    opacity: 0;
                    transition: opacity 0.3s ease, top 0.3s ease;
                    pointer-events: none;
                }

                .copied-message.show {
                    opacity: 1;
                    top: -35px;
                }
            </style>

            <div class="shortcode_gen">
                <label for="bppiv_shortcode">
                    <?php esc_html_e( 'Copy this shortcode and paste it into your post, page, or text widget content', 'panorama-viewer' ); ?>
                </label>

                <div class="shortcode_input_wrapper" onclick="copyShortcode()">
                    <div id="copiedMsg" class="copied-message">Copied!</div>
                    <input
                        type="text"
                        id="bppiv_shortcode"
                        onfocus="this.select();"
                        readonly="readonly"
                        value="[panorama id=<?php echo esc_attr( $post->ID ); ?>]"
                    />
                </div>
            </div>

            <script>
                function copyShortcode() {
                    const input = document.getElementById('bppiv_shortcode');
                    const msg = document.getElementById('copiedMsg');
                    input.select();
                    input.setSelectionRange(0, 99999);

                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(input.value).then(() => {
                            msg.classList.add("show");
                            setTimeout(() => msg.classList.remove("show"), 1500);
                        });
                    } else {
                        try {
                            const successful = document.execCommand('copy');
                            if (successful) {
                                msg.classList.add("show");
                                setTimeout(() => msg.classList.remove("show"), 1500);
                            }
                        } catch (err) {
                            console.error("Copy failed", err);
                        }
                    }
                }
            </script>
            <?php
        }
    }

    /*-------------------------------------------------------------------------------*/
    /* Footer Review Request .
    /*-------------------------------------------------------------------------------*/
    function bppiv_admin_footer( $text )       {
        
        if ( 'bppiv-image-viewer' == get_post_type() ) {
            $url = 'https://wordpress.org/support/plugin/panorama/reviews/?filter=5#new-post';
            $text = sprintf( __( 'If you like <strong> Panorama Viewer </strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'panorama-viewer' ), $url );
        }
        
        return $text;
    }

    /*-------------------------------------------------------------------------------*/
    /* Change publish button to save.
    /*-------------------------------------------------------------------------------*/
    function bppiv_change_publish_button( $translation, $text )    {
        if ( 'bppiv-image-viewer' == get_post_type() ) {
            if ( $text == 'Publish' ) {
                return 'Save';
            }
        }
        return $translation;
    }

    /*-------------------------------------------------------------------------------*/
    // Remove post update massage and link
    /*-------------------------------------------------------------------------------*/
    function bppiv_updated_messages( $messages ){
        $messages['bppiv-image-viewer'][1] = __( 'Shortcode updated ', 'panorama-viewer' );
        return $messages;
    }

    // HIDE everything in PUBLISH metabox except Move to Trash & PUBLISH button
    function bppiv_hide_publishing_actions(){
        $my_post_type = 'bppiv-image-viewer';
        global  $post ;
        if ( $post->post_type == $my_post_type ) {
            echo  '
            <style type="text/css">
                #misc-publishing-actions,
                #minor-publishing-actions{
                    display:none;
                }
            </style>
        ' ;
        }
    }

    // Hide & Disabled View, Quick Edit and Preview Button
    function bppiv_remove_row_actions( $idtions ) {
        global  $post ;
        
        if ( $post->post_type == 'bppiv-image-viewer' ) {
            unset( $idtions['view'] );
            unset( $idtions['inline hide-if-no-js'] );
        }
        
        return $idtions;
    }
    
}