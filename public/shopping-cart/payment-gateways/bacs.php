<?php

/**
 * Shop Payment Gateway Bacs (Direct Bank Transfer)
 *
 * @package     lime-catalog
 * @subpackage  Public/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Gateway_Bacs {

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
	 * Get order data and save in transient.
	 * Uses: do_action lmctlg_gateway_$gateway 
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @param object $order_data
	 * @return void
	 */
    public static function lmctlg_order_data_for_gateway_bacs( $order_data ) 
	{
		$orderdata   = json_decode($order_data, true); // convert to array
		//$order_data_obj = json_decode($order_data); // convert to object
		$order_data = array(
			'order_post_data'        => $orderdata['order_post_data'], // array
			'order_user_data'        => $orderdata['order_user_data'], // array
			'order_billing'          => $orderdata['order_billing'], // array
			'order_total'            => $orderdata['order_total'], // array
			'order_items'            => $orderdata['order_items'], // array
			'order_plugin_version'   => $orderdata['order_plugin_version'],
			'order_cus_user_id'      => $orderdata['order_cus_user_id'],
			'order_gateway'          => $orderdata['order_gateway'],
			'order_key'              => $orderdata['order_key'],
			'order_currency'         => $orderdata['order_currency'],
			'order_date'             => $orderdata['order_date'],
			'order_transaction_id'   => '', // leave it empty
			'order_status'           => 'failed'
		);
		// ORDER STATUSES: 'completed', 'processing', 'pending_payment', 'failed', 'cancelled', 'refunded', 'on_hold'
		// encode to json and save in transient
		$orderdata = json_encode($order_data);
		set_transient( 'lmctlg_order_data_transient', $orderdata, 7200 ); // for ... seconds
	}

	/**
	 * Process BACS payment Ajax.
	 *
	 * @since 1.0.0
	 * @return object
	 */
    public function lmctlg_process_bacs_payment() 
	{	
		if ( get_transient( 'lmctlg_order_data_transient' ) ) {
			$orderdata = get_transient( 'lmctlg_order_data_transient' ); // json encoded
		}
		
		if ( empty( $orderdata ) )
		return;
		
		// BACS
		$formData = $_POST['formData'];
		// parse string
		parse_str($formData, $postdata);
		
		// default
		$post_id = '';

		// VALIDATE ORDER FORM
	    $validate_order_form = LMCTLG_Validate_Order_Form::lmctlg_order_processing_validate_order_form($orderdata);
		
		if ( $validate_order_form == 'ok' ) {
			
			$order_data = json_decode($orderdata, true); // convert to array
			
			// user data	
			$first_name = isset( $order_data['order_user_data']['first_name'] ) ? sanitize_text_field( $order_data['order_user_data']['first_name'] ) : '';
			$last_name  = isset( $order_data['order_user_data']['last_name'] ) ? sanitize_text_field( $order_data['order_user_data']['last_name'] ) : '';
			$email      = isset( $order_data['order_user_data']['email'] ) ? sanitize_email( $order_data['order_user_data']['email'] ) : '';
			
			$username   = isset( $order_data['order_post_data']['lmctlg_username'] ) ? sanitize_text_field( $order_data['order_post_data']['lmctlg_username'] ) : '';
			$password   = isset( $order_data['order_post_data']['lmctlg_user_pass'] ) ? sanitize_text_field( $order_data['order_post_data']['lmctlg_user_pass'] ) : '';
			
			// user data
			$userdata = array(
				'user_login'      => $username,
				'user_pass'       => $password,
				'user_email'      => $email,
				'first_name'      => $first_name,
				'last_name'       => $last_name,
				'user_registered' => date( 'Y-m-d H:i:s' ),
				'role'            => 'lime_customer' // custom role
			);
			
            // manage user (register)
			$userid = LMCTLG_Process_Order::lmctlg_order_processing_manage_user($userdata);
			
			// add user id into order data
			$orderdata = array(
				'order_post_data'        => $order_data['order_post_data'], // array
				'order_user_data'        => $order_data['order_user_data'], // array
				'order_billing'          => $order_data['order_billing'], // array
				'order_total'            => $order_data['order_total'], // array
				'order_items'            => $order_data['order_items'], // array
				'order_plugin_version'   => $order_data['order_plugin_version'],
				'order_cus_user_id'      => $userid,
				'order_gateway'          => $order_data['order_gateway'],
				'order_key'              => $order_data['order_key'],
				'order_currency'         => $order_data['order_currency'],
				'order_date'             => $order_data['order_date'],
				'order_transaction_id'   => '', // leave it empty
				'order_status'           => 'failed'
			);
			
		    $orderdata = json_encode($orderdata); // encode to json before send
			
			// insert order data
			$post_id = LMCTLG_Process_Order::lmctlg_insert_order_data( $orderdata );// returns post id
			
			if ( empty( $post_id ) ) {
				// ORDER STATUSES: 'completed', 'processing', 'pending_payment', 'failed', 'cancelled', 'refunded', 'on_hold'
				$order_status = 'failed';
				
				$error_id = 'insert_order_data_failed';
				$error_message = __('Failed to insert order data.', 'lime-catalog');
				
				// error log saved in the lime-catalog-uploads/_lmctlg-error-log.txt file.
				$print = LMCTLG_Process_Order::lmctlg_order_processing_error( $post_id, $orderdata, $order_status, $error_id, $error_message ); // output error message
				
                echo json_encode(array('checkoutsuccess'=>false, 'message'=>$print ));
				
			} else {
				// success
				// ORDER STATUSES: 'completed', 'processing', 'pending_payment', 'failed', 'cancelled', 'refunded', 'on_hold'
				
				$transaction_id_bacs = '';
				// important, if payment status = completed we will send the downloadable file(s) urls in the order receipt email
				$order_status = 'pending_payment'; // for BACS should be pending_payment
				
				// update the above fields upon successful payment
				update_post_meta( $post_id, '_order_transaction_id', $transaction_id_bacs );
				update_post_meta( $post_id, '_order_status', $order_status ); // updating order status
				
				// update database, send email, redirect to success page upon successful payment
				$print = LMCTLG_Process_Order::lmctlg_order_processing_success( $post_id, $orderdata, $order_status ); 
				
				// delete transients
				delete_transient( 'lmctlg_order_data_transient' );

				echo json_encode(array('checkoutsuccess'=>true, 'message'=>$print ));
				
			}

		} else {
			// output error message
			//echo $validate_order_form;
			$print = $validate_order_form;
			echo json_encode(array('checkoutsuccess'=>false, 'message'=>$print ));
		}

		
	    #### important! #############
	    exit; // don't forget to exit!
		
	}
	
	
}

?>