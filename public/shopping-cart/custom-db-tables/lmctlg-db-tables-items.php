<?php

/**
 * Items Custom Data Tables Items: lmctlg_order_items and lmctlg_order_itemmeta
 *
 * @package     lime-catalog
 * @subpackage  public/shopping-cart/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_DB_Order_Items {

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
	 * Insert items.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  string $order_item_name
	 * @param  string $order_item_type
	 * @param  int    $price_option_id
	 * @param  string $price_option_name
	 * @param  int    $order_id
	 * @return int    last insert ID
	 */
	public static function lmctlg_order_items_insert_data( $order_item_name, $order_item_type, $price_option_id, $price_option_name, $order_id ) 
	{
		// only admin allowed
		if ( ! current_user_can( 'manage_options' ) )
		return;
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'lmctlg_order_items';
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'order_item_name'   => sanitize_text_field( $order_item_name ), 
				'order_item_type'   => sanitize_text_field( $order_item_type ), 
				'price_option_id'   => intval( $price_option_id ), 
				'price_option_name' => sanitize_text_field( $price_option_name ), 
				'order_id'          => intval( $order_id ), 
			) 
		);
		
		return $wpdb->insert_id;
		
	}

	/**
	 * Insert itemmeta.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int    $order_item_id
	 * @param  string $meta_key
     * @param  string $meta_value
	 * @return int    last insert ID
	 */
	public static function lmctlg_order_itemmeta_insert_data( $order_item_id, $meta_key, $meta_value ) 
	{
		// only admin allowed
		if ( ! current_user_can( 'manage_options' ) )
		return;
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'lmctlg_order_itemmeta';
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'order_item_id' => intval( $order_item_id ), 
				'meta_key'      => sanitize_text_field( $meta_key ), 
				'meta_value'    => sanitize_text_field( $meta_value ), 
			) 
		);
		
		return $wpdb->insert_id;
		
	}

	/**
	 * Select all items.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $order_items
	 */
	public static function lmctlg_select_all_order_items() {
		
		// only admin allowed
		if ( ! current_user_can( 'manage_options' ) )
		return;
		
		global $wpdb;
		$lmctlg_order_items = $wpdb->prefix . 'lmctlg_order_items'; // table, do not forget about tables prefix 
			$sql  = "
					SELECT order_item_id, order_item_name, order_item_type, price_option_id, price_option_name, order_id 
					FROM $lmctlg_order_items
					";
				// save each result in array		
			    $order_items = $wpdb->get_results( $sql, ARRAY_A ); // returns array: ARRAY_A

			// return array
			return $order_items;
	}
	
	/**
	 * Select all downloadable items.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $order_items
	 */
	public static function lmctlg_select_all_order_downloadable_items() {
		
		// only admin allowed
		if ( ! current_user_can( 'manage_options' ) )
		return;
		
		global $wpdb;
		$lmctlg_order_items = $wpdb->prefix . 'lmctlg_order_items'; // table, do not forget about tables prefix 
			$sql  = "
					SELECT order_item_id, order_item_name, order_item_type, price_option_id, price_option_name, order_id 
					FROM $lmctlg_order_items
					WHERE order_item_type = 'downloadable'
					";
				// save each result in array		
			    $order_items = $wpdb->get_results( $sql, ARRAY_A ); // returns array: ARRAY_A

			// return array
			return $order_items;
	}
	
	/**
	 * Select items by order ID.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $order_id
	 * @return array $order_items
	 */
	public static function lmctlg_select_order_items( $order_id ) {
		
	    if ( empty( $order_id ) )
	    return;
		
		$order_id = intval( $order_id );
		
		global $wpdb;
		$lmctlg_order_items = $wpdb->prefix . 'lmctlg_order_items'; // table, do not forget about tables prefix 

		if ( $order_id ) {	
			$sql  = "
					SELECT order_item_id, order_item_name, order_item_type, price_option_id, price_option_name, order_id 
					FROM $lmctlg_order_items
					WHERE order_id = $order_id
					";
				// save each result in array		
			    $order_items = $wpdb->get_results( $sql, ARRAY_A ); // returns array: ARRAY_A

			// return array
			return $order_items;
		} else {
		  return false;
	    }
	}
	
	/**
	 * Select itemmeta by order item ID.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $order_item_id
	 * @return array $item_meta
	 */
	public static function lmctlg_select_order_item_meta( $order_item_id ) {
		
	    if ( empty( $order_item_id ) )
	    return;
		
		$order_item_id = intval( $order_item_id );
		
		global $wpdb;
		//$item_meta = array();
		$lmctlg_order_itemmeta = $wpdb->prefix . 'lmctlg_order_itemmeta'; // table, do not forget about tables prefix
		if ( $order_item_id ) {	
				$sql  = "
						SELECT meta_id, order_item_id, meta_key, meta_value
						FROM $lmctlg_order_itemmeta
						WHERE order_item_id = $order_item_id
						";
				// save each result in array		
			    $item_meta = $wpdb->get_results( $sql, ARRAY_A ); // returns array: ARRAY_A

			// return array
			return $item_meta; // return item meta array
		} else {
		  return false;
	    }
	}
	
	/**
	 * Delete order items and order itemmeta by post ID.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @return void
	 */
	public static function lmctlg_delete_order_item_data( $post_id ){
		
	    if ( empty( $post_id ) )
	    return;
		
		$post_id = intval( $post_id );
		
		// only admin allowed
		if ( ! current_user_can( 'manage_options' ) )
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
		
		$lmctlg_order_itemmeta = $wpdb->prefix . 'lmctlg_order_itemmeta'; // table, do not forget about tables prefix
		
		foreach ( $order_items as $order_item ) 
		{
			$order_item_id = $order_item->order_item_id;
			// delete order item metas
			$wpdb->delete( $lmctlg_order_itemmeta, array( 'order_item_id' => $order_item_id ) );
		}
		
		// delete order items
		$wpdb->delete( $lmctlg_order_items, array( 'order_id' => $post_id ) );
		
		}
		
	}
	
	
	
}

?>