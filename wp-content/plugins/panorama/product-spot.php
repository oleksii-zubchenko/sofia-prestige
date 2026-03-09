<?php

if ( !defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists( 'PSBPlugin' ) ) {
	class PSBPlugin {
		function __construct(){
			add_action('init', [ $this, 'psb_init' ] );
			add_shortcode('product_spot', [$this, 'psb_product_spotshortcode']);
			add_filter('manage_product_spot_posts_columns', [$this, 'psb_ManageColumns'], 10);
			add_action('manage_product_spot_posts_custom_column', [$this, 'psb_ManageCustomColumns'], 10, 2);
			add_action('admin_enqueue_scripts', [$this, 'psb_admin_enqueue_script']);
			add_action('add_meta_boxes', [$this, 'psb_register_meta_boxes' ]);
			add_action('save_post_product', [$this, 'save_product_spot_meta'], 10, 2);
			add_action('wp_ajax_psb_save_product_spot', [$this, 'ajax_save_product_spot']);
			add_action( 'wp_enqueue_scripts', [$this, 'psb_frontend_enqueue_styles']);
		}

		function psb_init(){
			register_post_type('product_spot', [
				'label' => 'Product Spot',
				'labels' => [
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Product Spot',
					'edit_item' => 'Edit Product Spot',
					'not_found' => 'There was no product spot please add one'
				],
				'show_in_rest' => true,
				'public' => true,
				'menu_icon' => 'dashicons-products',
				'item_published' => 'Product Spot Block Published',
				'item_updated' => 'Product Spot Block Updated',
				'template' => [['psb/product-spot']],
				'template_lock' => 'all',
			]);

			add_action('wp', [$this, 'psb_woocommerce_loaded']);
		}

		function psb_woocommerce_loaded(){
			global $post;
			if ( ! $post ) return;
			
			$position = get_post_meta($post->ID, '_product_spot_position', true);
			if ($position === 'none') return;

			add_action( 'woocommerce_before_single_product_summary','psb_render_top_or_replace', 15 );
			add_action( 'woocommerce_before_single_product_summary', 'psb_render_bottom' , 35 );

			function psb_render_top_or_replace() {
				global $post;
				if ( ! $post ) return;
			
				$position = get_post_meta($post->ID, '_product_spot_position', true);
			
				$saved_blocks = get_post_meta( $post->ID, '_product_spot_blocks', true );
				if (empty($saved_blocks)) return;
			
				$blocks = [
					[
						'blockName'  => 'psb/product-spot',
						'attrs'      => json_decode( $saved_blocks, true ),
						'innerBlocks'=> [],
						'innerHTML'  => '',
					]
				];
			
				if ( $position === 'replace' ) {
					echo '<div class="psb-product-spot-on-woo psb-replace-image">';
						echo render_block( $blocks[0] );
					echo '</div>';
				}

				if ($position === 'top') {
					echo '<div class="psb-product-spot-on-woo psb-top-image" style="margin-bottom: 20px;">';
						echo render_block( $blocks[0] );
					echo '</div>';
				}
			}
			
			function psb_render_bottom() {
				global $post;
				if ( ! $post ) return;
			
				$position = get_post_meta($post->ID, '_product_spot_position', true);
				if ($position !== 'bottom') return;
			
				$saved_blocks = get_post_meta( $post->ID, '_product_spot_blocks', true );
				if (empty($saved_blocks)) return;
			
				$blocks = [
					[
						'blockName'  => 'psb/product-spot',
						'attrs'      => json_decode( $saved_blocks, true ),
						'innerBlocks'=> [],
						'innerHTML'  => '',	
					]								
				];
			
				echo '<div class="psb-product-spot-on-woo psb-bottom-image" style="margin-top: 20px;">';
				echo render_block( $blocks[0] );
				echo '</div>';
			}
		}

		function psb_frontend_enqueue_styles() {
			if ( ! class_exists( 'WooCommerce' ) ) return;
			if ( ! is_product() ) return;

			global $post;
			if ( ! $post ) return;

			$position     = get_post_meta( $post->ID, '_product_spot_position', true );
			$saved_blocks = get_post_meta( $post->ID, '_product_spot_blocks', true );
		
			if ( empty( $position ) || empty( $saved_blocks ) ) return;

			$inline_styles = "
				.sidePanel{
					grid-template-columns: 1fr !important;
				}
				
			";
		
			wp_register_style( 'psb-inline-style', false );
			wp_enqueue_style( 'psb-inline-style' );
			wp_add_inline_style( 'psb-inline-style', $inline_styles );
		}

		function save_product_spot_meta($post_id, $post) {
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
			if (!current_user_can('edit_post', $post_id)) return;
		
			if (isset($_POST['product_spot_blocks'])) {
				update_post_meta($post_id, '_product_spot_blocks', wp_kses_post($_POST['product_spot_blocks']));
			}

			if (isset($_POST['position'])) {
				update_post_meta($post_id, '_product_spot_position', sanitize_text_field($_POST['position']));
			}
		}

		public function ajax_save_product_spot() {
			check_ajax_referer('psb_nonce', 'nonce');

			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$blocks_json = isset($_POST['blocks']) ? wp_unslash($_POST['blocks']) : ''; 
			$position = isset($_POST['position'])? sanitize_text_field( $_POST['position']) : '';

			if (!$post_id || !$blocks_json ) {
				wp_send_json_error(['message' => 'Invalid post ID or blocks data']);
			}
			$blocks = json_decode($blocks_json, true);

			if (json_last_error() !== JSON_ERROR_NONE || !is_array($blocks)) {
				wp_send_json_error(['message' => 'Blocks data is not valid JSON']);
			}

			   
			foreach ($blocks as &$block) {
				if (isset($block['attrs']) && is_array($block['attrs'])) {
					foreach ($block['attrs'] as $key => $value) {
						if (is_string($value)) {
							$block['attrs'][$key] = sanitize_text_field($value);
						}
						if (in_array($key, ['content', 'description', 'html'], true)) {
							$block['attrs'][$key] = wp_kses_post($value);
						}
					}
				}
			}

			$blocks_json = wp_json_encode($blocks);

			$saved = update_post_meta($post_id, '_product_spot_blocks', $blocks_json);
			
			$savedPos = false;
			if ($position) {
				$savedPos = update_post_meta($post_id, '_product_spot_position', $position);
			}
		
			if ($saved || $savedPos) {
				wp_send_json_success([
					'message' => 'Saved successfully!',
					'attributes' => $blocks,
					'post_id' => $post_id,
					'position' => $position,
				]);
			} else {
				wp_send_json_error(['message' => 'Failed to save']);
			}
		}
		
		function psb_register_meta_boxes() {
			add_meta_box( 
				'psb-meta-box-id',
				__( 'Product Spot', 'product-spot' ),
				[ $this, 'psb_block_editor_display'],
				'product'
			 );
		}
		
		function psb_block_editor_display($post) {
			$saved_blocks = get_post_meta($post->ID, '_product_spot_blocks', true);
			$spotPosition = get_post_meta($post->ID, '_product_spot_position', true);

			?>
			<div 
				id="psb-block-editor-root" 
				data-blocks='<?php echo esc_attr($saved_blocks); ?>'
				spot-position='<?php echo esc_attr( $spotPosition ); ?>' 
			></div>
			<?php
		}

		function psb_admin_enqueue_script(){
			global $typenow;
			
			if ('product_spot' === $typenow) {
				wp_enqueue_script( 'admin-post-js', BPPIV_PLUGIN_DIR . 'build/admin-post.js', [], BPPIV_VERSION, true );
				wp_enqueue_style( 'admin-post-css', BPPIV_PLUGIN_DIR . 'build/admin-post.css', [], BPPIV_VERSION );
			}

			else if ($typenow === "product") {
				wp_enqueue_script( 
					'product_spot-dashboard-editor-script', 
					BPPIV_PLUGIN_DIR . 'build/dashboardBlockEditor.js', 
					[ 'react', 'react-dom', 'wp-block-library', 'wp-editor','wp-components',
					  'wp-block-editor', 'wp-i18n', 'wp-api', 'wp-util', 'media-upload', 
					  'lodash', 'wp-media-utils' ,'wp-data','wp-core-data','wp-api-request', 
					  'psb-product-spot-editor-script', 'wp-tinymce' ],
					BPPIV_VERSION, 
					true 
				);

				wp_localize_script('product_spot-dashboard-editor-script', 'psb_ajax', [
					'ajaxUrl' => admin_url('admin-ajax.php'),
					'nonce'   => wp_create_nonce('psb_nonce'),
					'postId'  => get_the_ID(),
					'blocks'  => get_post_meta(get_the_ID(), '_product_spot_blocks', true),
				]);

				wp_enqueue_style( 
					'product_spot-dashboard-editor-style', 
					BPPIV_PLUGIN_DIR . 'build/dashboardBlockEditor.css', 
					['psb-product-spot-style', 'psb-product-spot-editor-style', 'wp-block-library', 'wp-components', 'wp-block-editor', 'wp-edit-blocks', 'wp-format-library'], 
					BPPIV_VERSION 
				);

			}

		}
	
		function psb_product_spotshortcode($atts){
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
			return render_block( $blocks[0] );
		}
	
		function psb_ManageColumns($defaults){
			unset($defaults['date']);
			$defaults['shortcode'] = 'ShortCode';
			$defaults['date'] = 'Date';
			return $defaults;
		}
	
		function psb_ManageCustomColumns($column_name, $post_ID){
			if ($column_name == 'shortcode') {
				echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr($post_ID) . '">
						<input value="[product_spot id=' . esc_attr($post_ID) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr($post_ID) . '\')" readonly>
						<span class="tooltip">Copy To Clipboard</span>
					  </div>';
			}
		}
	}

	new PSBPlugin();
}




