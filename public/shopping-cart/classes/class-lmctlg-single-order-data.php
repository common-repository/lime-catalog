<?php

/**
 * Shopping Cart - Single Order class.
 *
 * @package     lime-catalog
 * @subpackage  public/shopping-cart/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class LMCTLG_Single_Order {

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
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Get the order data.
	 * Use this method for sending the email receipt from admin orders.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param int $order_id
	 * @return object $order_data
	 */
	public static function lmctlg_get_single_order_data_only_admin( $order_id ) 
	{
		if ( empty( $order_id ) )
			return;  
		
		// only admin allowed
		if ( ! current_user_can( 'manage_options' ) )
			return;
			
		$order_data = LMCTLG_Single_Order::lmctlg_get_single_order_data( $order_id );	
		
		return $order_data; // json object
	}
	
	/**
	 * Get single order data.
	 *
	 * @since 1.0.0
	 * @access private static - always private!!!!
	 * @param int $order_id
	 * @return object $order_data
	 */
    private static function lmctlg_get_single_order_data( $order_id ) 
	{
		if ( empty( $order_id ) )
			return;  
		
		// get postmeta order data
		$order_date            = get_post_meta( $order_id, '_order_date', true );
		$cus_user_id           = get_post_meta( $order_id, '_lmctlg_order_cus_user_id', true );
		$order_currency        = get_post_meta( $order_id, '_order_currency', true );
		$order_subtotal        = get_post_meta( $order_id, '_order_subtotal', true );
		$order_total           = get_post_meta( $order_id, '_order_total', true );
		$order_status          = get_post_meta( $order_id, '_order_status', true );
		$order_plugin_version  = get_post_meta( $order_id, '_order_plugin_version', true );
		$order_gateway         = get_post_meta( $order_id, '_order_gateway', true );
		$order_key             = get_post_meta( $order_id, '_order_key', true ); 
		$order_transaction_id  = get_post_meta( $order_id, '_order_transaction_id', true );
			
		$first_name            = get_post_meta( $order_id, '_first_name', true );
		$last_name             = get_post_meta( $order_id, '_last_name', true );
		$email                 = get_post_meta( $order_id, '_email', true );
		$phone                 = get_post_meta( $order_id, '_phone', true );
		$company               = get_post_meta( $order_id, '_company', true );
		$billing_addr_1        = get_post_meta( $order_id, '_billing_addr_1', true );
		$billing_addr_2        = get_post_meta( $order_id, '_billing_addr_2', true );
		$billing_country       = get_post_meta( $order_id, '_billing_country', true );
		$billing_state         = get_post_meta( $order_id, '_billing_state', true );
		$billing_city          = get_post_meta( $order_id, '_billing_city', true );
		$billing_zip           = get_post_meta( $order_id, '_billing_zip', true ); 
		
		// Set default values.
		if( empty( $order_date ) ) $order_date = '';
		if( empty( $cus_user_id ) ) $cus_user_id = '';
		if( empty( $order_currency ) ) $order_currency = '';
		if( empty( $order_subtotal ) ) $order_subtotal = '';
		if( empty( $order_total ) ) $order_total = '';
		if( empty( $order_status ) ) $order_status = '';
		if( empty( $order_plugin_version ) ) $order_plugin_version = '';
		if( empty( $order_gateway ) ) $order_gateway = '';
		if( empty( $order_key ) ) $order_key = '';
		if( empty( $order_transaction_id ) ) $order_transaction_id = '';
		
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
		
		$order_user_data = array(
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'email'      => $email,
			'phone'      => $phone,
			'company'    => $company
		);
		
		$order_billing_data = array(
			'billing_country'       => $billing_country,
			'billing_city'          => $billing_city,
			'billing_state'         => $billing_state,
			'billing_addr_1'        => $billing_addr_1,
			'billing_addr_2'        => $billing_addr_2,
			'billing_zip'           => $billing_zip
		);
		
		$order_total_data = array(
			'subtotal' => $order_subtotal,
			'total'  => $order_total
		);
		
		// ORDER ITEMS
		$ordered_items = LMCTLG_DB_Order_Items::lmctlg_select_order_items( $order_id );
		
		$items_data = array();
		$order_items = ''; // default
		foreach($ordered_items as $item )
		{
			$order_item_id     = $item['order_item_id'];
			$order_item_name   = $item['order_item_name'];
			$order_item_type   = $item['order_item_type'];
			$price_option_id   = $item['price_option_id'];
			$price_option_name = $item['price_option_name'];
			
			// ORDER ITEM METAS
			$item_meta = LMCTLG_DB_Order_Items::lmctlg_select_order_item_meta( $order_item_id );
			
			$order_items[$order_item_id] = array(
				'order_item_id'     => $order_item_id,
				'order_item_name'   => $order_item_name,
				'order_item_type'   => $order_item_type,
				'price_option_id'   => $price_option_id, 
				'price_option_name' => $price_option_name, 
				'order_item_meta'   => $item_meta // array
			);
	
		}	
		
		$order_data = array(
			'order_user_data'        => $order_user_data, // array
			'order_billing'          => $order_billing_data, // array
			'order_total'            => $order_total_data, // array
			'order_items'            => $order_items, // array
			'order_plugin_version'   => $order_plugin_version,
			'order_cus_user_id'      => $cus_user_id,
			'order_gateway'          => $order_gateway,
			'order_key'              => $order_key, // generated from user email and current date
			'order_currency'         => $order_currency,
			'order_date'             => $order_date,
			'order_transaction_id'   => $order_transaction_id,
			'order_status'           => $order_status
		);
		
		$order_data = json_encode($order_data);
		
		return $order_data; // json
	}
	
	/**
	 * Get single order item meta data.
	 *
	 * @to-do This method is not in use, just an example.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param object $order_data
	 * @return void
	 */
    public static function lmctlg_get_single_order_item_meta_data( $order_data ) 
	{
		if ( empty( $order_data ) )
			return;  
			
		$items = $order_data['order_items'];
		
		/*
		echo '<pre>';
		print_r($items);
		echo '</pre>';
		*/
		
		foreach($items as $item => $value )
		{
			//echo $item . ' ' . $value . '<br>';
			$item_metas = $value['order_item_meta'];
			foreach($item_metas as $item_meta )
			{
				echo $item_meta['meta_key'] . ' ' . $item_meta['meta_value'] . '<br>';
			}
		}
			
	}
	

	
}

?>