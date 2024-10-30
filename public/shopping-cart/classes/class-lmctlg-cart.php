<?php

/**
 * Shopping Cart - Cart
 *
 * @package     lime-catalog
 * @subpackage  public/shopping-cart/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class LMCTLG_Cart {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * When remove from cart button clicked process Ajax.
	 *
	 * @since  1.0.0
	 * @return void
	 */
    public function lmctlg_remove_from_cart_form_process() 
	{
		// default
		$item_id    = '';
		
		// get form data
		$formData = $_POST['formData'];
		// parse string
		parse_str($formData, $postdata);
		    
			/*
			echo '<pre>';
			print_r( $formData );
			echo '</pre>';
			*/
		
	    // verify nonce
	    if ( wp_verify_nonce( $postdata['lmctlg-remove-from-cart-form-nonce'], 'lmctlg_remove_from_cart_form_nonce') )
	    {	
			// get domain name
			$domain = LMCTLG_Helper::lmctlg_site_domain_name();
		    
			$item_id = sanitize_text_field( $postdata['lmctlg_item_id'] );
			
			// cookie name
			$cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();
			// check if cookie exist
			if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_items_cookie_name ) === true ) {	
				// read the cookie
				$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_items_cookie_name, $default = '');
			} else {
				$cookie = '';
			}
			
			$arr_cart_items = json_decode($cookie, true); // convert to array
			$obj_cart_items = json_decode($cookie); // convert to object
			 
			// remove the item from the array
			unset($arr_cart_items[$item_id]);
			
			/*
			echo '<pre>';
			print_r( $arr_cart_items );
			echo '</pre>';
			*/
			
			// cookie name
			$cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();
			// delete cookie
			$del_cookie = LMCTLG_Cookies::lmctlg_delete_cookie($name=$cart_items_cookie_name, $path = '/', $domain, $remove_from_global = false);
			
			$total = '0';
			// if array has contents
			if(count($arr_cart_items)>0) {
			 
				// enter new value
				$value = json_encode($arr_cart_items, true);
				// set cookie, expires in 1 day
				$set_cookie = LMCTLG_Cookies::lmctlg_set_cookie($name=$cart_items_cookie_name, $value, $expiry = 86400, $path = '/', $domain, $secure = false, $httponly = false );
					
				foreach($arr_cart_items as $key=>$value){	
					
					if ($value['item_price'] !== '0') {
					  // item total, item price x quantity
					  $itemtotal = $value['item_price'] * $value['item_quantity'];
					  // price in total
					  $total = $total + $itemtotal;
					}
					
				}
				
				// save totals in cookie
				LMCTLG_Cart::lmctlg_cart_totals($subtotal=$total, $total);
				
			
			} else {
				
				// get domain name
				$domain = LMCTLG_Helper::lmctlg_site_domain_name();
				// cookie name
				$cart_totals_cookie_name = LMCTLG_Cookies::lmctlg_cart_totals_cookie_name();
				// delete cookie
				$del_cookie = LMCTLG_Cookies::lmctlg_delete_cookie($name=$cart_totals_cookie_name, $path = '/', $domain, $remove_from_global = false);
				
				$limecataloghome = home_url() . '/limecatalog/';
				_e( 'Your cart is currently empty.', 'lime-catalog' );
				echo '<a class="btn-lime btn-lime-xs btn-lime-orange lime-float-right" href="' . esc_url( $limecataloghome ) . '"> ' . __( '< Return to Shop', 'lime-catalog' ) . ' </a>';
			}
		}
		
		
	    #### important! #############
	    exit; // don't forget to exit!
		
	}
	
	/**
	 * When update cart button clicked process Ajax.
	 *
	 * @since  1.0.0
	 * @return void
	 */
    public function lmctlg_update_cart_process() 
	{
		// get domain name
		$domain = LMCTLG_Helper::lmctlg_site_domain_name();
		
		// get form data
		$formData = $_POST['formData'];
		$formData = stripslashes($formData);
		
		$obj_cart_items = json_decode($formData);
		$arr_cart_items = json_decode($formData, true);
		
		/*
		echo '<pre>';
		print_r( $arr_cart_items );
		echo '</pre>';
		*/
		
		// convert data to array
		// initialize empty cart items array
		$cart_items=array();
		// defaults
        $total = '0';
		// if cart has contents
		if(count($obj_cart_items)>0){
			foreach($obj_cart_items as $key=>$value){
				
				if ($value->item_price !== '0') {
					
				  // item total, item price x quantity
				  $item_total = $value->item_price * intval( $value->item_quantity );
				  $item_total = LMCTLG_Amount::lmctlg_amount_hidden($amount=$item_total); // format
				  // price in total
				  $total = $total + $item_total;
				}
				
				// add new item on array
				$item = array(
					  'item_id'            => intval( $value->item_id ),
					  'item_price'         => sanitize_text_field( $value->item_price ),
					  'item_name'          => sanitize_text_field( $value->item_name ),
					  'item_quantity'      => intval( $value->item_quantity ),
					  'item_downloadable'  => intval( $value->item_downloadable ),
					  'item_total'         => sanitize_text_field( $item_total ),
					  'price_option_id'    => intval( $value->price_option_id )
				);
				
				$id = intval( $item['item_id'] );
				
				// !!!!! update array keys to item_id
				// add new item on array
				$cart_items[$id]=$item;
				
			}
		}
		
		// save totals in cookie
		$total = LMCTLG_Amount::lmctlg_amount_hidden($amount=$total);
		LMCTLG_Cart::lmctlg_cart_totals($subtotal=$total, $total);
		/*
		echo '<pre>';
		print_r( $cart_items );
		echo '</pre>';
		*/
		
		// cookie name
		$cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();
		
		// delete cookie
		$del_cookie = LMCTLG_Cookies::lmctlg_delete_cookie($name=$cart_items_cookie_name, $path = '/', $domain, $remove_from_global = false);
		
		// save items into the cookie
		$value = json_encode($cart_items, true); // convert to array
		// set cookie, expires in 1 day
        $set_cookie = LMCTLG_Cookies::lmctlg_set_cookie($name=$cart_items_cookie_name, $value, $expiry = 86400, $path = '/', $domain, $secure = false, $httponly = false );

	    #### important! #############
	    exit; // don't forget to exit!

    }
	
	/**
	 * Manage Cart Totals.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  float $subtotal
	 * @param  float $total
	 * @return void
	 */
    public static function lmctlg_cart_totals($subtotal, $total) 
	{
		if ($subtotal !== '' and $total !== '') {
			
			// get domain name
			$domain = LMCTLG_Helper::lmctlg_site_domain_name();
			
			$totals = array(
				  'subtotal'  => sanitize_text_field( $subtotal ),
				  'total'     => sanitize_text_field( $total )
			);

			// cookie name
			$cart_totals_cookie_name = LMCTLG_Cookies::lmctlg_cart_totals_cookie_name();
			// delete cookie
			$del_cookie = LMCTLG_Cookies::lmctlg_delete_cookie($name=$cart_totals_cookie_name, $path = '/', $domain, $remove_from_global = false);
			
			// put item to cookie
			$value = json_encode($totals, true); // convert to array
			// set cookie, expires in 1 day
			$set_cookie = LMCTLG_Cookies::lmctlg_set_cookie($name=$cart_totals_cookie_name, $value, $expiry = 86400, $path = '/', $domain, $secure = false, $httponly = false );
			
		} else {
			return;
		}
		
	}
	
	
}

?>