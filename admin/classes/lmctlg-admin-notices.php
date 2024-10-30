<?php

/**
 * Admin Notices class.
 *
 * @package     lime-catalog
 * @subpackage  Admin/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Admin_Notices {

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
	 * @since      1.0.0
	 * @param      string    $plugin_name    The name of the plugin.
	 * @param      string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Fire when 'Resend Order Receipt' checkbox checked on the orders pages.
	 *
	 * @since      1.0.0
     * @return     void
	 */
	public function lmctlg_admin_notice_order_receipt_sent()
	{ 
	// if post type exist
	  if ( post_type_exists('lime_shop_orders') ) {
			// get transient
			$order_receipt_sent = ''; // default
			// If transient exist
			if ( get_transient( 'lmctlg_order_receipt_sent' ) ) {
			  $order_receipt_sent = '<div class="updated notice notice-success is-dismissible" id="message"><p>' 
			  . __('Order receipt successfully sent!', 'lime-catalog') . '</p></div>';
				// delete transient
				delete_transient( 'lmctlg_order_receipt_sent' );
			} 
			echo $order_receipt_sent; // use echo instead of return!!!
	  }
		
	}
	
	
}

?>