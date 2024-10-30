<?php

/**
 * Orders Metaboxes.
 *
 * @package     lime-catalog
 * @subpackage  Admin/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Orders_Metaboxes {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name    The name of the plugin.
	 * @param      string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Hide defined styles.
	 *
	 * @global $post
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_hide_publishing_actions(){
			$orders_post_type = 'lime_shop_orders';
			global $post;
			if($post->post_type == $orders_post_type){
				/*
				echo '
					<style type="text/css">
						#misc-publishing-actions ,
						#minor-publishing-actions{
							display:none;
						}
					</style>
				';
				*/
				echo '
					<style type="text/css">
						#minor-publishing-actions, .misc-pub-post-status, .misc-pub-visibility, .misc-pub-curtime{
							display:none;
						}
						#post-body-content{
							display:none;
						}
					</style>
				';
			}
	}
	
	/**
	 * Publish meta box add resend order receipt field.
	 *
	 * @global $post
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_publish_meta_data( $post_id ) {
	  global $post;
	  $post_type = 'lime_shop_orders'; // If you want a specific post type
	 
	  if($post_type==$post->post_type) {
		echo  '<div class="misc-pub-section misc-pub-section-last">'
			 .'<label><input type="checkbox" value="1" name="lmctlg_resend_order_receipt" /> <strong>' . __( 'Resend Order Receipt', 'lime-catalog' ) . '</strong></label>'
			 .'</div>';
			 /*
			//if checkbox checked
			if(isset($_POST['lmctlg_resend_order_receipt']) && $_POST['lmctlg_resend_order_receipt'] == 1) {
				echo '<p class="description"> '. __('Order receipt successfully sent!', 'lime-catalog') . ' </p>';
			}
			*/
	  }
	}

	/**
	 * Remove ccustom post type title.
	 *
	 * @uses remove_post_type_support()
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_remove_custom_post_type_support() {
		remove_post_type_support( 'lime_shop_orders', 'title' ); // remove ccustom post type 'lime_shop_orders' title
	}
	
	/**
	 * If publish button pressed save the new title.
	 *
	 * @global $wpdb
	 *
	 * @uses get_post_type()
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @return void
	 */
	public function lmctlg_order_save_new_post_title( $post_id ){
	  global $wpdb;
	  // check post type
	  if ( get_post_type( $post_id ) == 'lime_shop_orders' ) {
		  
		// If calling wp_update_post, unhook this function so it doesn't loop infinitely
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//remove_action( 'save_post', array( $this, 'lmctlg_order_save_new_post_title' ), 10, 1 );
		
	    $wpdb->update( $wpdb->posts, array( 'post_title' =>  'order-#' . $post_id ), array( 'ID' => $post_id ) ); 
		
		// re-hook this function
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//add_action( 'save_post', array( $this, 'lmctlg_order_save_new_post_title' ), 10, 1 );
		
	  }
	  
	}
	
	/**
	 * Delete order items, order itemmeta and order downloads if user empties the trash.
	 *
	 * @global $wpdb
	 *
	 * @uses wp_verify_nonce()
	 * @uses current_user_can()
	 * @uses get_post_type()
	 * @uses get_results()
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @return void
	 */
	public function lmctlg_delete_single_order_data_if_empties_the_trash( $post_id ){
	    
	  if ( empty( $post_id ) )
		return;
		
		// Check if the user has permissions to save data.
	  if ( ! current_user_can( 'edit_post', $post_id ) )
		return;
		
	  // $post_id is the order_id
	  global $wpdb;
		
	  // check post type
	  if ( get_post_type( $post_id ) == 'lime_shop_orders' ) {
		  
	    $lmctlg_order_items = $wpdb->prefix . 'lmctlg_order_items'; // table, do not forget about tables prefix
		$order_items = $wpdb->get_results( 
			"
			SELECT order_item_id, order_id 
			FROM $lmctlg_order_items
			WHERE order_id = $post_id 
			"
		);
		
		$lmctlg_order_itemmeta  = $wpdb->prefix . 'lmctlg_order_itemmeta'; // table, do not forget about tables prefix
		
		$lmctlg_order_downloads = $wpdb->prefix . 'lmctlg_order_downloads'; // table, do not forget about tables prefix
		
		foreach ( $order_items as $order_item ) 
		{
			$order_item_id = $order_item->order_item_id;
			// delete order item metas
			$wpdb->delete( $lmctlg_order_itemmeta, array( 'order_item_id' => $order_item_id ) );
			
		}
		
		// delete from lmctlg_order_downloads
		$wpdb->delete( $lmctlg_order_downloads, array( 'order_id' => $post_id ) );
		
		// delete order items
		$wpdb->delete( $lmctlg_order_items, array( 'order_id' => $post_id ) );
		
	  }
		
	}
	
	/**
	 * Add Metabox order general details.
	 * 
	 * @uses add_meta_box()
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_add_metabox_order_general_details() {
		add_meta_box(
			'lmctlg_order_general_details',
			__( 'Order General Details', 'lime-catalog' ),
			array( $this, 'lmctlg_render_metabox_general_details' ),
			'lime_shop_orders', // custom post type
			'normal',
			'high' // tells wordpress where to place the meta box in the context. 'high', 'default' or 'low' 
		);
	}
	
	/**
	 * Render Metabox for order general details.
	 *
	 * @since 1.0.0
	 * @param object $post
	 * @return void
	 */
	public function lmctlg_render_metabox_general_details( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'lmctlg_order_general_details_nonce_action', 'lmctlg_order_general_details_nonce' );
		
		// Get the meta for all keys for the current post
		$meta = get_post_meta( $post->ID );

		// Retrieve an existing value from the database.
		$order_date        = get_post_meta( $post->ID, '_order_date', true );
		$cus_user_id       = get_post_meta( $post->ID, '_lmctlg_order_cus_user_id', true );
		$order_status      = get_post_meta( $post->ID, '_order_status', true );
		$order_gateway     = get_post_meta( $post->ID, '_order_gateway', true );
		$transaction_id    = get_post_meta( $post->ID, '_order_transaction_id', true );
		
		// Set default values.
		if( empty( $order_date ) ) $order_date = '';
		if( empty( $cus_user_id ) ) $cus_user_id = '';
		if( empty( $order_status ) ) $order_status = '';
		if( empty( $order_gateway ) ) $order_gateway = '';
		if( empty( $transaction_id ) ) $transaction_id = '';
		
		// Table - Billing Details
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'pages/orders/general-details.php';
		
	}

	/**
	 * Save Metabox data order general details.
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @param object $post
	 * @return void
	 */
	public function lmctlg_save_metabox_order_general_details( $post_id ) {

		// Add nonce for security and authentication.
		$nonce_action = 'lmctlg_order_general_details_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $_POST['lmctlg_order_general_details_nonce'] ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $_POST['lmctlg_order_general_details_nonce'], $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return; 
		
		// AVOID DUPLICATES
		// get post data by post id, if post type not 'lime_shop_orders' return
		$get_post = get_post( $post_id ); 
		$get_post_type = $get_post->post_type;
		if ( $get_post_type !== 'lime_shop_orders' )
			return; 
			
		// If calling wp_update_post, unhook this function so it doesn't loop infinitely
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//remove_action( 'save_post', array( $this, 'lmctlg_save_metabox_order_general_details' ), 10, 1 );
			
		// get options
		$lmctlg_general_options = get_option('lmctlg_general_options');
		
		// get options
		$lmctlg_currency_options = get_option('lmctlg_currency_options');
		
		$default_currency_opt = $lmctlg_currency_options['catalog_currency'];
		
		update_post_meta( $post_id, '_order_currency', $default_currency_opt );

		// Sanitize user input.
		$order_date         = isset( $_POST[ '_order_date' ] ) ? sanitize_text_field( $_POST[ '_order_date' ] ) : '';
		$order_cus_user_id  = isset( $_POST[ '_lmctlg_order_cus_user_id' ] ) ? sanitize_text_field( $_POST[ '_lmctlg_order_cus_user_id' ] ) : '';
		$order_status       = isset( $_POST[ '_order_status' ] ) ? sanitize_text_field( $_POST[ '_order_status' ] ) : '';
		$order_gateway      = isset( $_POST[ '_order_gateway' ] ) ? sanitize_text_field( $_POST[ '_order_gateway' ] ) : '';
		$transaction_id     = isset( $_POST[ '_order_transaction_id' ] ) ? sanitize_text_field( $_POST[ '_order_transaction_id' ] ) : '';
		
		// update plugin version only if not set before
		$order_plugin_version = get_post_meta( $post_id, '_order_plugin_version', true ); // lime catalog version
		if( empty( $order_plugin_version ) )
		{
			$plugin_version = $this->version;
			update_post_meta( $post_id, '_order_plugin_version', $plugin_version );		  
		}

		// Update the meta field in the database.
		update_post_meta( $post_id, '_order_date', $order_date );
		update_post_meta( $post_id, '_lmctlg_order_cus_user_id', $order_cus_user_id );
		update_post_meta( $post_id, '_order_status', $order_status );
		update_post_meta( $post_id, '_order_gateway', $order_gateway );
		update_post_meta( $post_id, '_order_transaction_id', $transaction_id );

		// update : post status in posts DB
		global $wpdb;
		$posts = $wpdb->prefix . 'posts'; // table, do not forget about tables prefix 
        $wpdb->update( $posts, array( 'post_status' => $order_status ), array( 'ID' => $post_id ) );
        clean_post_cache( $post_id );
		
		// re-hook this function
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//add_action( 'save_post', array( $this, 'lmctlg_save_metabox_order_general_details' ), 10, 1 );
		
	}
	
	/**
	 * Add Metabox order billing details.
	 * 
	 * @uses add_meta_box()
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_add_metabox_order_billing_details() {
		add_meta_box(
			'lmctlg_order_billing_details',
			__( 'Order Billing Details', 'lime-catalog' ),
			array( $this, 'lmctlg_render_metabox_billing_details' ),
			'lime_shop_orders', // custom post type
			'normal',
			'high' // tells wordpress where to place the meta box in the context. 'high', 'default' or 'low' 
		);
	}
	
	/**
	 * Render Metabox for order billing details.
	 *
	 * @since 1.0.0
	 * @param object $post
	 * @return void
	 */
	public function lmctlg_render_metabox_billing_details( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'lmctlg_order_billing_details_nonce_action', 'lmctlg_order_billing_details_nonce' );
		
		// Get the meta for all keys for the current post
		$meta = get_post_meta( $post->ID );
		
		$cus_user_id       = get_post_meta( $post->ID, '_lmctlg_order_cus_user_id', true );

		// Retrieve an existing value from the database.
		$first_name         = get_post_meta( $post->ID, '_first_name', true );
		$last_name          = get_post_meta( $post->ID, '_last_name', true );
		$email              = get_post_meta( $post->ID, '_email', true );
		$phone              = get_post_meta( $post->ID, '_phone', true );
		$company            = get_post_meta( $post->ID, '_company', true );
		$billing_addr_1     = get_post_meta( $post->ID, '_billing_addr_1', true );
		$billing_addr_2     = get_post_meta( $post->ID, '_billing_addr_2', true );
		$billing_country    = get_post_meta( $post->ID, '_billing_country', true );
		$billing_state      = get_post_meta( $post->ID, '_billing_state', true );
		$billing_city       = get_post_meta( $post->ID, '_billing_city', true );
		$billing_zip        = get_post_meta( $post->ID, '_billing_zip', true );
		
		// Set default values.
		if( empty( $cus_user_id ) ) $cus_user_id = '';
		if( empty( $first_name ) ) $first_name = '';
		if( empty( $last_name ) ) $last_name = '';
		if( empty( $email ) ) $email = '';
		if( empty( $phone ) ) $phone = '';
		if( empty( $company ) ) $company = '';
		if( empty( $billing_addr_1 ) ) $billing_addr_1 = '';
		if( empty( $billing_addr_2 ) ) $billing_addr_2 = '';
		if( empty( $billing_country ) ) $billing_country = '';
		if( empty( $billing_state ) ) $billing_state = '';
		if( empty( $billing_city ) ) $billing_city = '';
		if( empty( $billing_zip ) ) $billing_zip = '';
		
		// Table - Billing Details
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'pages/orders/billing-details.php';
		
	}

	/**
	 * Save Metabox data order billing details.
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @param object $post
	 * @return void
	 */
	public function lmctlg_save_metabox_order_billing_details( $post_id ) {

		// Add nonce for security and authentication.
		$nonce_action = 'lmctlg_order_billing_details_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $_POST['lmctlg_order_billing_details_nonce'] ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $_POST['lmctlg_order_billing_details_nonce'], $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;  
			
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		  return;
		  
		// AVOID DUPLICATES
		// get post data by post id, if post type not 'lime_shop_orders' return
		$get_post = get_post( $post_id ); 
		$get_post_type = $get_post->post_type;
		if ( $get_post_type !== 'lime_shop_orders' )
			return; 
			
		// If calling wp_update_post, unhook this function so it doesn't loop infinitely
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//remove_action( 'save_post', array( $this, 'lmctlg_save_metabox_order_billing_details' ), 10, 1 );

		// Sanitize user input.
		$first_name         = isset( $_POST[ '_first_name' ] ) ? sanitize_text_field( $_POST[ '_first_name' ] ) : '';
		$last_name          = isset( $_POST[ '_last_name' ] ) ? sanitize_text_field( $_POST[ '_last_name' ] ) : '';
		$email              = isset( $_POST[ '_email' ] ) ? sanitize_text_field( $_POST[ '_email' ] ) : '';
		$phone              = isset( $_POST[ '_phone' ] ) ? sanitize_text_field( $_POST[ '_phone' ] ) : '';
		$company            = isset( $_POST[ '_company' ] ) ? sanitize_text_field( $_POST[ '_company' ] ) : '';
		$billing_addr_1     = isset( $_POST[ '_billing_addr_1' ] ) ? sanitize_text_field( $_POST[ '_billing_addr_1' ] ) : '';
		$billing_addr_2     = isset( $_POST[ '_billing_addr_2' ] ) ? sanitize_text_field( $_POST[ '_billing_addr_2' ] ) : '';
		$billing_country    = isset( $_POST[ '_billing_country' ] ) ? sanitize_text_field( $_POST[ '_billing_country' ] ) : '';
		$billing_state      = isset( $_POST[ '_billing_state' ] ) ? sanitize_text_field( $_POST[ '_billing_state' ] ) : '';
		$billing_city       = isset( $_POST[ '_billing_city' ] ) ? sanitize_text_field( $_POST[ '_billing_city' ] ) : '';
		$billing_zip        = isset( $_POST[ '_billing_zip' ] ) ? sanitize_text_field( $_POST[ '_billing_zip' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, '_first_name', $first_name );
		update_post_meta( $post_id, '_last_name', $last_name );
		update_post_meta( $post_id, '_email', $email );
		update_post_meta( $post_id, '_phone', $phone );
		update_post_meta( $post_id, '_company', $company );
		update_post_meta( $post_id, '_billing_addr_1', $billing_addr_1 );
		update_post_meta( $post_id, '_billing_addr_2', $billing_addr_2 );
		update_post_meta( $post_id, '_billing_country', $billing_country );
		update_post_meta( $post_id, '_billing_state', $billing_state );
		update_post_meta( $post_id, '_billing_city', $billing_city );
		update_post_meta( $post_id, '_billing_zip', $billing_zip );
		
		// order key, update only if not set before
		$order_key = get_post_meta( $post_id, '_order_key', true );
		if( empty( $order_key ) )
		{
			// generate order key
            $order_key = LMCTLG_Checkout::lmctlg_generate_order_key();
			update_post_meta( $post_id, '_order_key', $order_key );		  
		}
		
		// re-hook this function
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//add_action( 'save_post', array( $this, 'lmctlg_save_metabox_order_billing_details' ), 10, 1 );

	}
	
	/**
	 * Add Metabox order items.
	 * 
	 * @uses add_meta_box()
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_add_metabox_order_items() {
		add_meta_box(
			'lmctlg_order_items',
			__( 'Order Items', 'lime-catalog' ),
			array( $this, 'lmctlg_render_metabox_order_items' ),
			'lime_shop_orders', // custom post type
			'normal',
			'high' // tells wordpress where to place the meta box in the context. 'high', 'default' or 'low' 
		);
	}
	
	/**
	 * Render Metabox for order items.
	 *
	 * @since 1.0.0
	 * @param object $post
	 * @return void
	 */
	public function lmctlg_render_metabox_order_items( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'lmctlg_order_items_nonce_action', 'lmctlg_order_items_nonce' );
		
		// Get the meta for all keys for the current post
		$meta = get_post_meta( $post->ID );
		
		// Table - Items
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'pages/orders/order-items.php';
		
	}

	/**
	 * Save Metabox data order items.
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @param object $post
	 * @return void
	 */
	public function lmctlg_save_metabox_order_items( $post_id ) {

		// Add nonce for security and authentication.
		$nonce_action = 'lmctlg_order_items_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $_POST['lmctlg_order_items_nonce'] ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $_POST['lmctlg_order_items_nonce'], $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;  
			
		$order_status = get_post_meta( $post_id, '_order_status', true );
		
		// If order status is "completed" items are no longer editable.
		if ( $order_status == 'completed' )
			return; 
			
		// AVOID DUPLICATES
		// get post data by post id, if post type not 'lime_shop_orders' return
		$get_post = get_post( $post_id ); 
		$get_post_type = $get_post->post_type;
		if ( $get_post_type !== 'lime_shop_orders' )
			return; 
			
		// If calling wp_update_post, unhook this function so it doesn't loop infinitely
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//remove_action( 'save_post', array( $this, 'lmctlg_save_metabox_order_items' ), 10, 1 );

		/*
		echo '<pre>';
		print_r($_POST);
		echo '</pre>';
		*/
		
		$items_id = $_POST['lmctlg_item_id']; // array
		
		$ordered_items = LMCTLG_DB_Order_Items::lmctlg_select_order_items( $order_id=$post_id );
		
		// check if order exist, if exist delete data
		if ( !empty($ordered_items) ) {
			
			### DB ### delete order items and order itemmeta 
			$delete_order_item_data = LMCTLG_DB_Order_Items::lmctlg_delete_order_item_data( $post_id );
			
			/*
			echo '<pre>';
			print_r($ordered_items);
			echo '</pre>';
			*/
			
		} else {
			//echo 'No Order found for #' . $post_id;
		}
		
		// ###### INSERT DATA ###### 
		
		// DATA FOR CCUSTOM DATABASES : lmctlg_order_items and lmctlg_order_itemmeta
		// get the item data by item id
		$index = 0; // default
		foreach( $items_id as $item_id ) {
			
			// The intval() function casts user input as an integer, and defaults to zero if the input was a non-numeric value
			$item_id = intval( $item_id );
				   
			$order_id = $post_id;
			$order_item_name = get_the_title( $item_id ); // item title from posts database
			
			$item_downloadable  = get_post_meta( $item_id, '_lmctlg_item_downloadable', true ); // postmeta
			// if 1 convert to readable word
			if ( $item_downloadable == '1' ) {
				
			  $order_item_type = 'downloadable';
			  
			} else {
			  // not downloadable
			  $order_item_type = 'tangible'; 
			}
			
			### DB ### price_option_id
			$price_option_id = ''; // default
			if ( isset( $_POST['lmctlg_price_option_id'][$index] ) ) {
				$price_option_id =  intval( $_POST['lmctlg_price_option_id'][$index] );
			} else {
				$price_option_id = '';
			}
			
			### DB ### price_option_name
			$price_option_name = ''; // default
			if ( isset( $_POST['lmctlg_price_option_name'][$index] ) ) {
				$price_option_name =  sanitize_text_field( $_POST['lmctlg_price_option_name'][$index] ); // fix
			} else {
				$price_option_name = '';
			}
			
			### DB ### insert data, returns the order item id
			$order_item_id = LMCTLG_DB_Order_Items::lmctlg_order_items_insert_data( $order_item_name, $order_item_type, $price_option_id, $price_option_name, $order_id );
			
			### DB ### insert item_id
			$item_id_value = $item_id;
			LMCTLG_DB_Order_Items::lmctlg_order_itemmeta_insert_data( $order_item_id, $meta_key='_item_id', $meta_value=$item_id_value );
			
			### DB ### insert item_price
			if ( isset( $_POST['lmctlg_item_price'][$index] ) ) {
				$item_price =  sanitize_text_field( $_POST['lmctlg_item_price'][$index] ); // fix
				LMCTLG_DB_Order_Items::lmctlg_order_itemmeta_insert_data( $order_item_id, $meta_key='_item_price', $meta_value=$item_price );
			}
			
			### DB ### insert item_quantity
			if ( isset( $_POST['lmctlg_item_quantity'][$index] ) ) {
				$item_quantity = intval( $_POST['lmctlg_item_quantity'][$index] );
				LMCTLG_DB_Order_Items::lmctlg_order_itemmeta_insert_data( $order_item_id, $meta_key='_item_quantity', $meta_value=$item_quantity );
			}

			### DB ### insert item_quantity
			if ( isset( $_POST['lmctlg_single_item_total'][$index] ) ) {
				$single_item_total =  sanitize_text_field( $_POST['lmctlg_single_item_total'][$index] ); // fix
				LMCTLG_DB_Order_Items::lmctlg_order_itemmeta_insert_data( $order_item_id, $meta_key='_item_total', $meta_value=$single_item_total );
			}
			
			// ... manage the index this way..
			//echo "Index is $index <br>";
			$index++; // should be at the end of the loop
			
		}
		
		// manage downloads
        LMCTLG_Orders_Metaboxes::lmctlg_manage_order_downloads( $order_id=$post_id );
		
		/*
		foreach(array_combine($item_id,$item_name) as $key=>$value) {
			echo $key . ' ' . $value . '<br>';
		}
        */
		
		// ORDER SUB TOTAL RIGHT NOW NOT IN USE
		// Sanitize user input.
		//$order_subtotal = isset( $_POST[ 'lmctlg-order-subtotal' ] ) ? sanitize_text_field( $_POST[ 'lmctlg-order-subtotal' ] ) : '';
		// update order total
		//update_post_meta( $post_id, '_order_subtotal', $order_subtotal );
		
		// Sanitize user input.
		$order_total = isset( $_POST[ 'lmctlg-order-total' ] ) ? sanitize_text_field( $_POST[ 'lmctlg-order-total' ] ) : '';
		// update order total
		update_post_meta( $post_id, '_order_total', $order_total );
		
		// re-hook this function
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//add_action( 'save_post', array( $this, 'lmctlg_save_metabox_order_items' ), 10, 1 );
		
		// make it extensible (using for software licensing)
		do_action( 'lmctlg_save_metabox_order_items_after', $post_id ); // <- extensible 

	}
	
	/**
	 * If publish button pressed check actions and send order receipt email.
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @return void
	 */
	public function lmctlg_order_resend_order_receipt( $post_id ){
		
		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
		
		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return; 
			
		// AVOID DUPLICATES
		// get post data by post id, if post type not 'lime_shop_orders' return
		$get_post = get_post( $post_id ); 
		$get_post_type = $get_post->post_type;
		if ( $get_post_type !== 'lime_shop_orders' )
			return; 
			
		// If calling wp_update_post, unhook this function so it doesn't loop infinitely
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//remove_action( 'save_post', array( $this, 'lmctlg_order_resend_order_receipt' ), 10, 1 );
		
		// check post type
		if ( get_post_type( $post_id ) == 'lime_shop_orders' ) {
		 
			//if checkbox checked
			if(isset($_POST['lmctlg_resend_order_receipt']) && $_POST['lmctlg_resend_order_receipt'] == 1) {
				
				// if order receipt sent sat transient and show message, uses at : LMCTLG_Admin_Notices
				set_transient( 'lmctlg_order_receipt_sent', true, 180 ); // for 180 seconds
				
				// get single order data by order ID
				$orderdata = LMCTLG_Single_Order::lmctlg_get_single_order_data_only_admin( $order_id=$post_id );
				
				/*
				echo '<pre>';
				print_r($orderdata);
				echo '</pre>';
				exit;
				*/
				
				// re-send order receipt
				LMCTLG_Notification_Emails::lmctlg_resend_order_receipt_from_admin( $post_id, $orderdata );

			}
		 
		}
		
		// re-hook this function
		// NOTE: It is very important to use the same parameters in remove_action than in add_action. 
		// Example with priority below (extract)... if the parameters are not the same, the infinite loop occurs.
		// source: https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Avoiding_infinite_loops
		//add_action( 'save_post', array( $this, 'lmctlg_order_resend_order_receipt' ), 10, 1 );
		
	}
	
	/**
	 * Remove download if not exist.
	 *
	 * @since 1.0.0
	 * @param int $order_id
	 * @return void
	 */
	public static function lmctlg_manage_order_downloads( $order_id )
	{
		//$item_ids = array();
		$order_data = LMCTLG_Single_Order::lmctlg_get_single_order_data_only_admin( $order_id );
		$order_data   = json_decode($order_data, true); // convert to array
		// get single order item ids
		foreach($order_data['order_items'] as $item => $value )
		{
			//echo $item . ' ' . $value . '<br>';
			$item_metas = $value['order_item_meta'];
			foreach($item_metas as $item_meta )
			{
				//echo $item_meta['meta_key'] . ' ' . $item_meta['meta_value'] . '<br>';
				if ( $item_meta['meta_key'] == '_item_id' ) {
					// save in array
					$item_ids[] = $item_meta['meta_value'];
				}
			}
			
		}
		
		if ( ! empty($item_ids) ) {
		
			$item_ids_arr = array();
			$downloads = LMCTLG_DB_Order_Downloads::lmctlg_select_downloads( $order_id );
			// check if download exist, if not delete
			foreach($downloads as $download )
			{
				$item_id = $download['item_id'];
				
				if( ! in_array($item_id,$item_ids)){
					//echo $item_id.' does not exists in item_ids' . '<br>';
					// delete from downloads table
					LMCTLG_DB_Order_Downloads::lmctlg_delete_single_download( $order_id, $item_id );
				}else{
					//echo $item_id.' exists in item_ids' . '<br>';
				}
				// save in array
				$item_ids_arr[] = $download['item_id'];
				
			}
			
			// if item not exist in downloads, insert
			foreach($item_ids as $order_item_ids )
			{
				if( ! in_array($order_item_ids,$item_ids_arr) ){
					//echo $order_item_ids.' does not exists in item_ids_arr' . '<br>';
					// insert into downloads table
					LMCTLG_DB_Order_Downloads::lmctlg_insert_single_download( $order_id, $item_id=$order_item_ids );
				}else{
					//echo $order_item_ids.' exists in item_ids_arr' . '<br>';
				}
			}
		
		}
		
		
	}
	
	
	
	
}

?>