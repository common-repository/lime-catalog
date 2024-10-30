<?php

/**
 * Shopping Cart - Notification Emails class.
 *
 * @package     lime-catalog
 * @subpackage  public/shopping-cart/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Notification_Emails {

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
	 * Html email sending function, read template file.
	 *
	 * @since  1.0.0
	 * @access protected static
	 * @param  string $FileName
	 * @return void $str
	 */
	protected static function readTemplateFile($FileName) 
	{	  
		$fp = fopen($FileName,"r") or exit("Unable to open File ".$FileName);
		$str = "";
		while(!feof($fp)) {
			$str .= fread($fp,1024);
		}	
		return $str;	
	}
	
	/**
	 * Resend order receipt form admin orders view.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @param  object $orderdata
	 * @return void
	 */
	public static function lmctlg_resend_order_receipt_from_admin( $post_id, $orderdata ) 
	{
		if ( empty( $post_id ) )
		    return; 
		
		// only admin allowed
		if ( ! current_user_can( 'manage_options' ) )
			return;
			
		$orderdata   = json_decode($orderdata, true); // convert to array
		//$order_data_obj = json_decode($order_data); // convert to object
		
		$order_data = array(
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
			'order_transaction_id'   => $orderdata['order_transaction_id'],
			'order_status'           => $orderdata['order_status']
		);
		
		$order_data = json_encode($order_data); // encode to json before send
		
		// software licensing using this
		do_action( 'lmctlg_resend_order_receipt_from_admin_before', $post_id, $order_data ); // <- extensible 
		
		// send order receipt
		LMCTLG_Notification_Emails::lmctlg_send_order_receipt( $post_id, $order_data );
		
	}
	
	/**
	 * Send Notification Emails Upon any Sales. Data From : LMCTLG_Process_Order Class
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @param  object $orderdata
	 * @param  string $order_status
	 * @return void
	 */
	public static function lmctlg_order_data_for_emails( $post_id, $orderdata, $order_status ) 
	{
		if ( empty( $post_id ) )
		return;
		
		$order_transaction_id    = get_post_meta( $post_id, '_order_transaction_id', true );
		if( empty( $order_transaction_id ) ) $order_transaction_id = '';
		
		$orderdata   = json_decode($orderdata, true); // convert to array
		
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
			'order_transaction_id'   => $order_transaction_id,
			'order_status'           => $order_status
		);
		
		$order_data = json_encode($order_data); // encode to json before send
		
		// send order receipt
		LMCTLG_Notification_Emails::lmctlg_send_order_receipt( $post_id, $order_data );
		// send order sale notification
		LMCTLG_Notification_Emails::lmctlg_send_order_sale_notification( $post_id, $order_data );
		
	}

	/**
	 * Process email sending.
	 *
	 * @since  1.0.0
	 * @access private static
	 * @param  string $to
	 * @param  string $subject
	 * @param  string $emailbody
	 * @param  string $header
	 * @return bool $email_errors
	 */
	private static function lmctlg_orders_send_email( $to, $subject, $emailbody, $header ) {
		$email_errors = false;
		// send the email using wp_mail()
		if( !wp_mail($to, $subject, $emailbody, $header) ) {
			$send_email_errors = true;
		}
		return $email_errors;
	}

	/**
	 * Send Order Receipt to customer.
	 *
	 * @since  1.0.0
	 * @access private static
	 * @param  int $post_id
	 * @param  object $order_data
	 * @return void
	 */
	private static function lmctlg_send_order_receipt( $post_id, $order_data ) {
	    
	    if ( empty( $post_id ) )
	    return;
		
		$order_data  = json_decode($order_data, true); // convert to array
		
		// order data
		$user_email  = $order_data['order_user_data']['email'];
		
	    $user_email  = strtolower( $user_email );
	  
		// get options
		$order_receipts_options = get_option('lmctlg_order_receipts_options');
		
		$fromname      = $order_receipts_options['from_name'];
		$fromemail     = $order_receipts_options['from_email'];
		$subject       = $order_receipts_options['subject'];
		$email_content = $order_receipts_options['email_content'];
		
		$order_data = json_encode($order_data); // encode to json before send
		$emailbody = LMCTLG_Notification_Emails::lmctlg_orders_email_template( $post_id, $order_data, $email_content );
        
		$sender = "From: ". sanitize_text_field( $fromname ) ." <". sanitize_text_field( $fromemail ) .">" . "\r\n";
		
		// write the email content
		$header = "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/html; charset=UTF-8\r\n";
		$header .= $sender . "\r\n";
	
		$to = sanitize_email( $user_email );
		
		// send email
		LMCTLG_Notification_Emails::lmctlg_orders_send_email( $to, $subject, $emailbody, $header );
	
	}

	/**
	 * Send Order Notification email to admin.
	 *
	 * @since  1.0.0
	 * @access private static
	 * @param  int $post_id
	 * @param  object $order_data
	 * @return void
	 */
	private static function lmctlg_send_order_sale_notification( $post_id, $order_data ) {
	  
	    if ( empty( $post_id ) )
	    return;
		
		$order_data   = json_decode($order_data, true); // convert to array
	  
		// get options
		$order_notifications_options = get_option('lmctlg_order_notifications_options');
		
		$notifications_enabled   = $order_notifications_options['notifications_enabled'];
		$send_to                 = $order_notifications_options['send_to']; // array
		$subject                 = $order_notifications_options['subject'];
		$email_content           = $order_notifications_options['email_content'];
		
	    if ( $notifications_enabled !== '1' )
	    return;
		
		$order_data = json_encode($order_data); // encode to json before send
		$emailbody = LMCTLG_Notification_Emails::lmctlg_orders_email_template( $post_id, $order_data, $email_content );
        
		// get options
		$order_receipts_options = get_option('lmctlg_order_receipts_options');
		
		$fromname      = $order_receipts_options['from_name'];
		$fromemail     = $order_receipts_options['from_email'];
		
		$sender = "From: ". sanitize_text_field( $fromname ) ." <". sanitize_text_field( $fromemail ) .">" . "\r\n";
		
		// write the email content
		$header = "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/html; charset=UTF-8\r\n";
		$header .= $sender . "\r\n";
	
		$send_to = str_replace(" ","",$send_to); // replace white spaces
	    $send_to_array = explode(',', $send_to); // create array, explode by comma
		$to = $send_to_array;
		
		// send email
		LMCTLG_Notification_Emails::lmctlg_orders_send_email( $to, $subject, $emailbody, $header );
	
	}

	/**
	 * Orders email template.
	 *
	 * @since  1.0.0
	 * @access private static
	 * @param  int $post_id
	 * @param  object $order_data
	 * @param  string $email_content
	 * @return string $emailBody
	 */
	private static function lmctlg_orders_email_template( $post_id, $order_data, $email_content ) {
		
	    if ( empty( $post_id ) && empty( $order_data ) && empty( $email_content ) )
	    return;
		
		$order_data   = json_decode($order_data, true); // convert to array
		
		$order_date      = sanitize_text_field( $order_data['order_date'] );
		$order_status    = sanitize_text_field( $order_data['order_status'] );
		$transaction_id  = sanitize_text_field( $order_data['order_transaction_id'] );
		$order_gateway   = sanitize_text_field( $order_data['order_gateway'] );
		
		$order_subtotal  = sanitize_text_field( $order_data['order_total']['subtotal'] );
		$order_total     = sanitize_text_field( $order_data['order_total']['total'] );
		$order_currency  = sanitize_text_field( $order_data['order_currency'] );
	   
		$user_first_name = sanitize_text_field( $order_data['order_user_data']['first_name'] );
		$user_last_name  = sanitize_text_field( $order_data['order_user_data']['last_name'] );
		$user_email      = sanitize_email( $order_data['order_user_data']['email'] );
		$user_phone      = sanitize_text_field( $order_data['order_user_data']['phone'] );
		$user_company    = sanitize_text_field( $order_data['order_user_data']['company'] );
		
		$billing_country = sanitize_text_field( $order_data['order_billing']['billing_country'] );
		$billing_city    = sanitize_text_field( $order_data['order_billing']['billing_city'] );
		$billing_state   = sanitize_text_field( $order_data['order_billing']['billing_state'] );
		$billing_addr_1  = sanitize_text_field( $order_data['order_billing']['billing_addr_1'] );
		$billing_addr_2  = sanitize_text_field( $order_data['order_billing']['billing_addr_2'] );
		$billing_zip     = sanitize_text_field( $order_data['order_billing']['billing_zip'] );
		
		if ( ! empty($billing_country) ) {
		    // Countries
		    $countries    = lmctlg_country_list(); // function
		    $country_name = $countries[$billing_country]; // get country name
		} else {
			$country_name = '';
		}
		
		// send order key only if order status = completed
		if ( $order_status == 'completed' ) {
		   $order_key  = $order_data['order_key']; 
		} else {
		   $order_key = '';
		}
		
        // gateway options data
		$payment_gateway_data = LMCTLG_Notification_Emails::lmctlg_email_gateway_options_data($order_gateway);
		$payment_gateway_data = wp_kses_post( $payment_gateway_data );
		
		$user_email = strtolower( $user_email );
			
		// get options
		$email_settings_options = get_option('lmctlg_order_email_settings_options');
		
		$emails_logo = sanitize_text_field( $email_settings_options['emails_logo'] );
		if ( !empty($emails_logo) ) {
		   $logo_image = '<img style="margin-top:10px;" border="0" src="' . esc_url( $emails_logo ) . '" />';
		} else {
		  $logo_image = '';	
		}
		
		// get options
		$order_receipts_options = get_option('lmctlg_order_receipts_options');
		
		$fromname      = sanitize_text_field( $order_receipts_options['from_name'] );
		$fromemail     = sanitize_text_field( $order_receipts_options['from_email'] );
		
		$items = LMCTLG_Notification_Emails::lmctlg_email_ordered_items($post_id);
		
		$order_total = LMCTLG_Amount::lmctlg_format_amount($amount=$order_total);
		
		// get the currency symbol
		$order_currency_symbol = LMCTLG_Amount::lmctlg_get_currency_data_symbol( $currency=$order_currency );
		$order_total = LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$order_total, $order_currency_symbol); 
		
		$order_status_name = ''; // default
		$order_statuses    = LMCTLG_Custom_Post_Statuses::lmctlg_order_custom_post_statuses();
		$order_status_name = $order_statuses[$order_status]; // order status value
		
		// replace
		$email_content = str_replace("[user_first_name]",esc_attr( $user_first_name ),$email_content); // user first name
		$email_content = str_replace("[user_last_name]",esc_attr( $user_last_name ),$email_content); // user last name
		$email_content = str_replace("[user_email]",esc_attr( $user_email ),$email_content); // user email
		$email_content = str_replace("[user_phone]",esc_attr( $user_phone ),$email_content); // user phone
		$email_content = str_replace("[user_company]",esc_attr( $user_company ),$email_content); // user company
		
		$email_content = str_replace("[billing_country]",esc_attr( $country_name ),$email_content);
		$email_content = str_replace("[billing_city]",esc_attr( $billing_city ),$email_content);
		$email_content = str_replace("[billing_state]",esc_attr( $billing_state ),$email_content);
		$email_content = str_replace("[billing_addr_1]",esc_attr( $billing_addr_1 ),$email_content);
		$email_content = str_replace("[billing_addr_2]",esc_attr( $billing_addr_2 ),$email_content);
		$email_content = str_replace("[billing_zip]",esc_attr( $billing_zip ),$email_content);	
		
		$email_content = str_replace("[items]",$items,$email_content); // LIST, purchased items list
		$email_content = str_replace("[order_total]",$order_total,$email_content); // SPAN, ordered items total
		
		$email_content = str_replace("[order_status]",esc_attr( $order_status_name ),$email_content);
		
		$email_content = str_replace("[from_name]",esc_attr( $fromname ),$email_content); // email sent, from name
		$email_content = str_replace("[from_email]",esc_attr( $fromemail ),$email_content); // email sent, from email

		$email_content = str_replace("[order_id]",esc_attr( $post_id ),$email_content); // order id
		
		$email_content = str_replace("[transaction_id]",esc_attr( $transaction_id ),$email_content); // transaction id
		$email_content = str_replace("[order_key]",esc_attr( $order_key ),$email_content); // order key is the license key for digital goods
		
		$order_date_f = LMCTLG_Helper::formatDateTime( $date=$order_date );
		$email_content = str_replace("[order_date]",esc_attr( $order_date_f ),$email_content); // order date
		
		$email_content = str_replace("[payment_gateway]",esc_attr( $order_gateway ),$email_content);
		
		$email_content = str_replace("[payment_gateway_data]",$payment_gateway_data,$email_content); // gateway title (name), gateway notes, bank account details (only for BACS)
		
		$current_site_url = home_url();
		$email_content = str_replace("[current_site_url]",esc_attr( $current_site_url ),$email_content);
		
		// software licensing using this
		$email_content = apply_filters( 'lmctlg_orders_email_template_extend_template_tags', $email_content ); // <- extensible 
		
		// should be placed after all $email_content
		$email_content = str_replace("\n\n\n\n",'\n\n',$email_content); // fix
		$email_content = stripslashes_deep( nl2br($email_content) );

		# read themplate file
		$themeUrl = LMCTLG_PLUGIN_DIR . 'public/html-emails/default/emails-default-template.php';
		$emailBody = LMCTLG_Notification_Emails::readTemplateFile( $themeUrl );
		
		# theme replace
		$emailBody = str_replace("[logo_image]",$logo_image,$emailBody); // logo
		$emailBody = str_replace("[email_content]",$email_content,$emailBody);
		
		return $emailBody; // HTML email template
		
	}

	/**
	 * Orders payment gateway data.
	 *
	 * @since  1.0.0
	 * @access private static
	 * @param  array $order_gateway
	 * @return string $output
	 */
	private static function lmctlg_email_gateway_options_data($order_gateway) {
		
		$output = ''; // default
		
		$payment_gateways   = LMCTLG_Payment_Gateways::lmctlg_payment_gateways();// gateways
		
		if( ! empty( $order_gateway ) ) {	
			foreach($payment_gateways as $gateway => $value )
			{  
			   // get the selected gateway
			   if ( $order_gateway == $gateway ) 
			   {
					$payment_gateway_label = sanitize_text_field( $value['payment_gateway_label'] );
					$payment_gateway_name  = sanitize_text_field( $value['payment_gateway_name'] );
			   }
			}
		} else {
			// error, no gateway selected
			$payment_gateway_label = '';
			$payment_gateway_name  = '';
		}
		
		// check if option exist
		if( get_option('lmctlg_gateway_' . $order_gateway . '_options') ){
			
			$gateway_options = get_option('lmctlg_gateway_' . $order_gateway . '_options');
			
			// if gateway title exist
			if ( !empty($gateway_options['lmctlg_' . $order_gateway . '_title']) ) {
				$gateway_title = $gateway_options['lmctlg_' . $order_gateway . '_title'];
				$output .= '<strong>' . __( 'Payment Gateway: ', 'lime-catalog' ) . '</strong>' . esc_attr( $payment_gateway_label ) . " \n\n";
			}
			
			// if gateway notes exist
			if ( !empty($gateway_options['lmctlg_' . $order_gateway . '_notes']) ) {
				$gateway_notes = $gateway_options['lmctlg_' . $order_gateway . '_notes'];
				$output .= '<strong>' . __( 'Notes: ', 'lime-catalog' ) . '</strong>' . wp_strip_all_tags( $gateway_notes ) . " \n\n";
			}
			
			// if gateway bank account details exist
			if ( !empty($gateway_options['lmctlg_' . $order_gateway . '_bank_account_details']) ) {
				$gateway_bank_account_details = $gateway_options['lmctlg_' . $order_gateway . '_bank_account_details'];
				$output .= '<strong>' . __( 'Bank Account Details: ', 'lime-catalog' ) . '</strong> ' . " \n\n";
				$output .= wp_strip_all_tags( $gateway_bank_account_details ) . " \n"; // wp_strip_all_tags() removes HTML tags
			}
			
			do_action( 'lmctlg_email_gateway_options_data' ); // <- extensible
			
			return $output;
			
		} else {
			return;
		}
		
	}

	/**
	 * Ordered items.
	 *
	 * @since  1.0.0
	 * @access private static
	 * @param  int $post_id
	 * @return string $output
	 */
	private static function lmctlg_email_ordered_items($post_id) {
		
		if ( empty( $post_id ) )
		return;
		
		$output = ''; // default
		
		$order_id = $post_id;
		// echo 'Order ID: #' . $order_id . '<br><br>';
		
		// get postmeta order data
		$order_date            = get_post_meta( $order_id, '_order_date', true );
		$cus_user_id           = get_post_meta( $order_id, '_lmctlg_order_cus_user_id', true );
		$order_currency        = get_post_meta( $order_id, '_order_currency', true );
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
		
		// get the currency symbol
		$order_currency_symbol = LMCTLG_Amount::lmctlg_get_currency_data_symbol( $currency=$order_currency );
		
		$ordered_items = LMCTLG_DB_Order_Items::lmctlg_select_order_items( $order_id );
		
		$file_download_urls = array(); // create empty array 
		$site_home_url = home_url();
		
		foreach($ordered_items as $item )
		{
			$order_item_id   = $item['order_item_id'];
			$order_item_name = $item['order_item_name'];
			$order_item_type = $item['order_item_type'];
			
			$item_meta = LMCTLG_DB_Order_Items::lmctlg_select_order_item_meta( $order_item_id );
			
			foreach($item_meta as $key => $value )
			{
				// item ID 
				if ( $value['meta_key'] == '_item_id' ) {
					$item_id = $value['meta_value'];
				}
				// item price
				if ( $value['meta_key'] == '_item_price' ) {
					$item_price = $value['meta_value'];
				}
				// item quantity
				if ( $value['meta_key'] == '_item_quantity' ) {
					$item_quantity = $value['meta_value'];
				}
				// item total
				if ( $value['meta_key'] == '_item_total' ) {
					$item_total = $value['meta_value'];
				}
				
			}
			
			$item_price = LMCTLG_Amount::lmctlg_format_amount($amount=$item_price);
			$itemPrice = LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$item_price, $order_currency_symbol);
			
			$item_total = LMCTLG_Amount::lmctlg_format_amount($amount=$item_total);
			$itemTotal = LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$item_total, $order_currency_symbol);
			
			$download_data = ''; // default
			// send download file(s) data only if order status = completed
			if ( $order_status == 'completed' ) {
			
				if ( $order_item_type == 'downloadable' ) {
					// get item data, postmeta
					$item_downloadable  = get_post_meta( $item_id, '_lmctlg_item_downloadable', true ); // the checkbox data 
					// check again if file is downloadable
					if ( $item_downloadable == '1' ) {
						// get file data, downloadable items
						$item_file_name        = get_post_meta( $item_id, '_lmctlg_item_file_name', true );
						$item_file_url         = get_post_meta( $item_id, '_lmctlg_item_file_url', true );
						$item_download_limit   = get_post_meta( $item_id, '_lmctlg_item_download_limit', true );
						$item_download_expiry  = get_post_meta( $item_id, '_lmctlg_item_download_expiry', true );
						
						// download expiry date
						$current_date = date('Y-m-d H:i:s');
						// order data : order date
						$order_date = get_post_meta( $order_id, '_order_date', true );
						// order date + item download expiry 
						$add_days = strtotime(date("Y-m-d H:i:s", strtotime($order_date)) . "+" . $item_download_expiry . " days");
						$download_expiry_date = date('Y-m-d H:i:s', $add_days);
						
						
						if ( $item_download_limit == '' ) {
							// unlimited downloads
							$item_download_limit = __( 'Unlimited', 'lime-catalog' );
						}
						
						$check_expiry_date = strtotime($download_expiry_date);
						if ( $check_expiry_date == '0' ) {
							$download_expiry_date = __( 'Never Expires', 'lime-catalog' );
						} else {
							$download_expiry_date = LMCTLG_Helper::formatDate( $date=$download_expiry_date ); // format date
						}
						
						// downloadable products create download url
						$secret_data = array(
							'post_id'    => intval( $item_id ), // db posts, item id is the post id !!!
							'order_id'   => intval( $order_id ), // db posts
							'order_key'  => sanitize_text_field( $order_key )
						);
						
						// convert array to json
						$secret_data_json = json_encode( $secret_data );
						$secret_data_json_enc = LMCTLG_Helper::lmctlg_base64url_encode($data=$secret_data_json);	
						
						//$download_link = home_url() . '/lmctlg-file-dw-api/?action=download&dwfile=' . $secret_data_json_enc;
						$file_link = '<a href="' . esc_url( $site_home_url . '/lmctlg-file-dw-api/?action=download&dwfile=' . $secret_data_json_enc ) . '">' . esc_attr( $item_file_name ) . '</a>';
						
						$file_download_urls[] = $file_link; // save in array for later usage
						
						// download file data
						$download_data = ""; // " \n"
						$download_data .= __( 'Download Limit: ', 'lime-catalog' ) . esc_attr( $item_download_limit ) . " \n";
						$download_data .= __( 'Download Expiry Date: ', 'lime-catalog' ) . esc_attr( $download_expiry_date ) . " \n";	
						$download_data .= __( 'Download File: ', 'lime-catalog' ) . $file_link . " \n";
						
					}
				}
			
			}
			
			// Order Item Data
			$output .= '<strong>' . $order_item_name . '</strong>' . " \n";
			$output .=  $download_data; 
			
			// Order Item Meta Data
			$output .= __( 'Item ID: ', 'lime-catalog' ) . intval( $item_id ) . " \n";
			$output .= __( 'Item Price: ', 'lime-catalog' ) . sanitize_text_field( $itemPrice ) . " \n";
			$output .= __( 'Item Quantity: ', 'lime-catalog' ) . intval( $item_quantity ) . " \n";
			$output .= __( 'Item Total: ', 'lime-catalog' ) . sanitize_text_field( $itemTotal ) . " \n";
			$output .= " \n";
		}
		
		return $output;
	
	}

	/**
	 * Downloadable items.
	 *
	 * @to-do This Method is not in use.
	 *
	 * @since  1.0.0
	 * @access private static
	 * @param  int $post_id
	 * @return NONE
	 */
	private function lmctlg_email_downloadable_items($post_id) {
	   // downloadable products ARRAY
	   $downloadable_items = LMCTLG_Downloadable_Products::lmctlg_order_get_downloadable_items( $post_id );
	   
	   $file_download_urls = array(); // create empty array
	   $site_home_url = home_url();
	   $items_order_id = $post_id;
	   
	   $order_key = get_post_meta( $post_id, '_order_key', true ); 
	   
	    // if there are downloadable products insert download link into the email invoice
		if ( $downloadable_items ) {
			// Using foreach loop without key
			foreach($downloadable_items as $meta_key => $meta_value) {
				
				foreach($meta_value as $key => $value )
				{
				   //echo 'Post ID: ' . $value['post_id'] . ' Meta Key: ' . $value['meta_key'] . ' Meta Value: ' . $value['meta_value'] . '<br>';
				   
				   // get file data based on the file name
				   if ( $value['meta_key'] == '_lmctlg_item_file_name' ) {
					   
						$item_file_name = $value['meta_value'];
						
						$item_post_id = $value['post_id'];
						
						$secret_data = array(
							'post_id'    => intval( $item_post_id ),
							'order_id'   => intval( $items_order_id ),
							'order_key'  => sanitize_text_field( $order_key )
						);
						
						// convert array to json
						$secret_data_json = json_encode( $secret_data );
						$secret_data_json_enc = LMCTLG_Helper::lmctlg_base64url_encode($data=$secret_data_json);	
						
						//$download_link = home_url() . '/lmctlg-file-dw-api/?action=download&dwfile=' . $secret_data_json_enc;
						$file_link = '<a href="' . esc_url( $site_home_url . '/lmctlg-file-dw-api/?action=download&dwfile=' . $secret_data_json_enc ) . '">' . esc_attr(  $item_file_name ) . '</a>';
						
						$file_download_urls[] = $file_link;
				   }
				   
				   // get meta: file url
				   if ( $value['meta_key'] == '_lmctlg_item_file_url' ) {
						//echo 'File URL: ' . $item_file_url = $value['meta_value'] . '<br>';
				   }
				   
				   /*
				   // get meta: Download Limit
				   if ( $value['meta_key'] == '_lmctlg_item_download_limit' ) {
						echo 'Download Limit: ' . $item_download_limit = $value['meta_value'] . '<br>';
				   }
				   
				   // get meta: Download Expiry
				   if ( $value['meta_key'] == '_lmctlg_item_download_expiry' ) {
						echo 'Download Expiry: ' . $item_download_expiry = $value['meta_value'] . '<br>';
				   }
				   */
				   
				}

			}	
		}
		
		if ( !empty($file_download_urls) ) {
			$email_content = 'Please click on the following link(s) to download your files. <br><br>';
			$email_content .= '[xdownloadsx]';
		} else {
			// remove download lines
		}
		
	}
	
}

?>