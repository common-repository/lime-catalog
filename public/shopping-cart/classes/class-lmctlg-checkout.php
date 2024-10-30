<?php

/**
 * Shopping Cart - Checkout class.
 *
 * @package     lime-catalog
 * @subpackage  public/shopping-cart/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class LMCTLG_Checkout {

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
	 * Generate order key.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return string $order_key
	 */
    public static function lmctlg_generate_order_key() 
	{
		$rand_num = LMCTLG_Helper::lmctlg_generate_random_string($length='12');
		$order_key = 'order_' . $rand_num;
		//$order_key_generate = strtolower( $email . ' ' . date( 'Y-m-d H:i:s' ) . ' lmctlg_order_key' );  // Order key
		//$order_key = LMCTLG_Helper::lmctlg_base64url_encode($order_key_generate);
		return $order_key;
	}
	
	/**
	 * Return checkout cart totals.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $arr_cart_totals
	 */
    public static function lmctlg_checkout_cart_totals() 
	{
		// cookie name
		$cart_totals_cookie_name = LMCTLG_Cookies::lmctlg_cart_totals_cookie_name();
		
		// check if cookie exist
		if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_totals_cookie_name ) === true ) 
		{	
			// read the cookie
			$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_totals_cookie_name, $default = '');
			$cart_totals = $cookie;
			
			$arr_cart_totals = json_decode($cart_totals, true); // convert to array
			$obj_cart_totals = json_decode($cart_totals); // convert to object
		
			// if has contents
			if(count($obj_cart_totals)>0)
			{
				return $arr_cart_totals;
			}
		} else {
		  return;	
		}
	}
	
	/**
	 * Return checkout cart items.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $arr_cart_items
	 */
    public static function lmctlg_checkout_cart_items() 
	{
		// cookie name
		$cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();
		
		// check if cookie exist
		if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_items_cookie_name ) === true ) 
		{
			// read the cookie
			$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_items_cookie_name, $default = '');
			$cart_items = $cookie;

			$arr_cart_items = json_decode($cart_items, true); // convert to array
			$obj_cart_items = json_decode($cart_items); // convert to object
		    
			// if cart has contents
			if(count($obj_cart_items)>0)
			{	
			  return $arr_cart_items;
			}
			
		} else {
		  return;	
		}
	}
	
	/**
	 * Replace roles.
	 *
	 * @to-do This method is not in use for many reasons.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return void
	 */
    public static function lmctlg_checkout_form_process_manage_user_role() 
	{
		// check if user logged in
		if ( is_user_logged_in() ) {
			
			// if logged in get current user data
			$current_user = wp_get_current_user();
			
			$user_id     = $current_user->ID;
			$username    = $current_user->user_login;
			$email       = $current_user->user_email;
			$first_name  = $current_user->user_firstname;
			$last_name   = $current_user->user_lastname;
			$displayname = $current_user->display_name;
			$user_roles  = $current_user->roles; // get roles
			
			$first_user_role = $user_roles['0']; // first role
			
			### IMPORTANT !!! ###
			// Multiple roles are NOT working on WP so use only ONE role
			
			// if role is subscriber or lime_subscriber, replace role  with (lime_customer)
			if ( $first_user_role == 'subscriber' || $first_user_role == 'lime_subscriber' ) {
				// remove role
				//$user->remove_role($first_user_role);
				// add role
				//$user->add_role('lime_customer');
			}
			
			// if user role is NOT administrator, editor, author, contributor
			if ( $first_user_role !== 'administrator' && $first_user_role !== 'editor' && $first_user_role !== 'author' && $first_user_role !== 'contributor' ) {
				// remove role
				//$user->remove_role($first_user_role);
				// add role
				//$user->add_role('lime_customer');
			}
			
			if ( ! empty($user_roles) ) {
				foreach( $user_roles as $user_role )
				{
					$db_user_roles[] = $user_role;
				}
			}
			
			/*
			$lime_customer = 'lime_customer'; // role
			// if role 'lime_customer' not in roles, add
			if( ! in_array($lime_customer,$db_user_roles)) {
				
				$user = new WP_User( $user_id );
				
				// Remove role
				//$user->remove_role( 'lime_subscriber' );
		
				// Add role
				//$user->add_role( $lime_customer );
			}
			*/
			
		} else {
			return;
		}

	}
	
	/**
	 * Add "lmctlg_lime_customer" for user meta so we can determine if a member ever bought something.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $userid
	 * @return void
	 */
    public static function lmctlg_checkout_form_process_add_extra_user_meta($userid) 
	{
		if ( empty( $userid ) )
		return;
		// if user purchased any product add lmctlg_lime_customer = 1 so user become into lime customer
		// update user meta
		update_user_meta( $userid, 'lmctlg_lime_customer', '1' );
	}

	/**
	 * Checkout form Ajax process.
	 *
	 * @since  1.0.0
	 * @return void
	 */
    public function lmctlg_checkout_form_process() 
	{
		// get form data
		$formData = $_POST['formData'];
		
		if ( empty( $formData ) )
		return;
		
		// parse string, convert formdata into array
		parse_str($formData, $postdata);
		
	    // verify nonce
	    if ( wp_verify_nonce( $postdata['lmctlg-checkout-form-nonce'], 'lmctlg_checkout_form_nonce') )
	    {
			// get options
		    $lmctlg_general_options = get_option('lmctlg_general_options');
			
			// get options
			$lmctlg_currency_options = get_option('lmctlg_currency_options');
			$default_currency_opt = $lmctlg_currency_options['catalog_currency'];
			
			$payment_gateways    = LMCTLG_Payment_Gateways::lmctlg_payment_gateways();// gateways
			$gateway             = $postdata['lmctlg_default_gateway'] ? sanitize_text_field( $postdata['lmctlg_default_gateway'] ) : '';

            

			// personal details
			$first_name          = isset( $postdata['lmctlg_first_name'] ) ? sanitize_text_field( $postdata['lmctlg_first_name'] ) : '';
			$last_name           = isset( $postdata['lmctlg_last_name'] ) ? sanitize_text_field( $postdata['lmctlg_last_name'] ) : '';
			$email               = isset( $postdata['lmctlg_user_email'] ) ? sanitize_text_field( $postdata['lmctlg_user_email'] ) : '';
			$phone               = isset( $postdata['lmctlg_phone'] ) ? sanitize_text_field( $postdata['lmctlg_phone'] ) : '';
			$company             = isset( $postdata['lmctlg_company'] ) ? sanitize_text_field( $postdata['lmctlg_company'] ) : '';

			// billing details
			$billing_country     = isset( $postdata['lmctlg_billing_country'] ) ? sanitize_text_field( $postdata['lmctlg_billing_country'] ) : '';
			$billing_city        = isset( $postdata['lmctlg_billing_city'] ) ? sanitize_text_field( $postdata['lmctlg_billing_city'] ) : '';
			$billing_state       = isset( $postdata['lmctlg_billing_state'] ) ? sanitize_text_field( $postdata['lmctlg_billing_state'] ) : '';
			$billing_addr_1      = isset( $postdata['lmctlg_billing_addr_1'] ) ? sanitize_text_field( $postdata['lmctlg_billing_addr_1'] ) : '';
			$billing_addr_2      = isset( $postdata['lmctlg_billing_addr_2'] ) ? sanitize_text_field( $postdata['lmctlg_billing_addr_2'] ) : '';
			$billing_zip         = isset( $postdata['lmctlg_billing_zip'] ) ? sanitize_text_field( $postdata['lmctlg_billing_zip'] ) : '';

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
				
			$order_total = LMCTLG_Checkout::lmctlg_checkout_cart_totals();
			$order_items = LMCTLG_Checkout::lmctlg_checkout_cart_items();
			
			$order_plugin_version = sanitize_text_field( $this->version );
			
			// generate order key
			$order_key = LMCTLG_Checkout::lmctlg_generate_order_key();
			
			$order_date = date( 'Y-m-d H:i:s' );
				
			$order_data = array(
				'order_post_data'        => $postdata, // array
				'order_user_data'        => $order_user_data, // array
				'order_billing'          => $order_billing_data, // array
				'order_total'            => $order_total, // array
				'order_items'            => $order_items, // array
				'order_plugin_version'   => $order_plugin_version,
				'order_cus_user_id'      => '',
				'order_gateway'          => $gateway,
				'order_key'              => $order_key, // generated from user email and current date
				'order_currency'         => $default_currency_opt, // option
				'order_date'             => $order_date
			);
			
			$order_data = json_encode($order_data); // encode to json before send
			
			// Allow order details to be modified before send to gateway
			$order_data = apply_filters('lmctlg_order_data_before_gateway',$order_data); // <- extensible
				
			/*
			echo '<pre>';
			print_r( $order_data );
			echo '</pre>';
			exit;
			*/
			
			do_action( 'lmctlg_checkout_before_gateway', $order_data ); // <- extensible 
			
			// ### send data to gateway ###
			LMCTLG_Checkout::lmctlg_send_order_data_to_gateway( $gateway, $order_data );
				
				
				// ONLY FOR TESTING
				
				//$success  = LMCTLG_Checkout::lmctlg_checkout_form_success();
				//$message  = $success['checkout_success']; // array
				//$print    = LMCTLG_Login_Register::lmctlg_print_success_message( $message ) ;
				
				//  success 
				//echo json_encode(array('checkoutformvalid'=>true, 'message'=>$print ));
				
				//  success 
				//echo json_encode(array('checkoutformvalid'=>true ));
			
		}

	    #### important! #############
	    exit; // don't forget to exit!

	}
	
	/**
	 * Order Data for Gateway.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  string $gateway
	 * @param  object $order_data
	 * @return void
	 */
	public static function lmctlg_send_order_data_to_gateway( $gateway, $order_data ) {
		// $gateway must match the registered gateway ID
		do_action( 'lmctlg_gateway_' . $gateway, $order_data );
	}

	/**
	 * Checkout form on success message.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $message
	 */
	public static function lmctlg_checkout_form_success() {
		
		$message = array(
			'checkout_success' => array(
				'success_id' => 'checkout_success',
				'success_message' => __( 'Thank you for your order!', 'lime-catalog' )
			)
		);
		
        return apply_filters( 'lmctlg_checkout_form_success', $message );

	}

	/**
	 * Checkout form billing details required fields.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $required_fields
	 */
	public static function lmctlg_billing_details_required_fields() {
		
		$required_fields = array(
			'lmctlg_billing_country' => array(
				'error_id' => 'billing_country_required',
				'error_message' => __( 'Please select Country.', 'lime-catalog' )
			),
			'lmctlg_billing_city' => array(
				'error_id' => 'billing_city_required',
				'error_message' => __( 'City is required.', 'lime-catalog' )
			),
			'lmctlg_billing_addr_1' => array(
				'error_id' => 'billing_addr_1_required',
				'error_message' => __( 'Street Addr. 1 is required.', 'lime-catalog' )
			),
			'lmctlg_billing_zip' => array(
				'error_id' => 'billing_zip_required',
				'error_message' => __( 'Postcode/Zip is required.', 'lime-catalog' )
			)
		);
		
        return apply_filters( 'lmctlg_billing_details_required_fields', $required_fields );

	}

	/**
	 * Checkout form validate billing details fields.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $billing
	 * @return void $print
	 */
	public static function lmctlg_validate_billing_details( $billing ) 
	{ 
		// defaults
		$print = '';
		
		if( ! empty( $billing ) ) {
			
			if ( empty( $billing['lmctlg_billing_country'] ) ) { 
				
				// username - validate
				$required = LMCTLG_Checkout::lmctlg_billing_details_required_fields();
				$errors   = $required['lmctlg_billing_country']; // array
				return $print    = LMCTLG_Login_Register::lmctlg_print_error_message( $errors );
				
			} elseif ( empty( $billing['lmctlg_billing_city'] ) ) { 
				
				// username - validate
				$required = LMCTLG_Checkout::lmctlg_billing_details_required_fields();
				$errors   = $required['lmctlg_billing_city']; // array
				return $print    = LMCTLG_Login_Register::lmctlg_print_error_message( $errors );
				
			} elseif ( empty( $billing['lmctlg_billing_addr_1'] ) ) { 
				
				// username - validate
				$required = LMCTLG_Checkout::lmctlg_billing_details_required_fields();
				$errors   = $required['lmctlg_billing_addr_1']; // array
				return $print    = LMCTLG_Login_Register::lmctlg_print_error_message( $errors );
				
			} elseif ( empty( $billing['lmctlg_billing_zip'] ) ) { 
				
				// username - validate
				$required = LMCTLG_Checkout::lmctlg_billing_details_required_fields();
				$errors   = $required['lmctlg_billing_zip']; // array
				return $print    = LMCTLG_Login_Register::lmctlg_print_error_message( $errors );
				
			}
			
			do_action( 'lmctlg_validate_billing_details' ); // <- extensible	
			
		}
		
		// validation
		if( empty( $print ) ) {
           return; // valid
		}
		
	}
	
	
}

?>