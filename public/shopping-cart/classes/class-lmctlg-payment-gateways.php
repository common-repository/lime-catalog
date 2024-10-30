<?php

/**
 * Shopping Cart - Payment Gateways class.
 *
 * @package     lime-catalog
 * @subpackage  Public/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class LMCTLG_Payment_Gateways {

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
	 * Global. Payment gatewazs.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $gateways
	 */
    public static function lmctlg_payment_gateways() 
	{
		// get options
		$lmctlg_gateway_bacs_options = get_option('lmctlg_gateway_bacs_options');
		
		if ( $lmctlg_gateway_bacs_options['lmctlg_bacs_show_billing_details'] == '1' ) {
			$billing_details = '1';
		} else {
			$billing_details = '0';
		}
		
		$gateways = array(
			'bacs' => array(
				'payment_gateway_label' => 'Direct Bank Transfer', // payment gateway label
				'payment_gateway_name'  => 'bacs', // payment gateway name
				'create_an_account'     => '1', // checkout form show create an account fields
				'credit_card_details'   => '0', // checkout form show credit card details fields
				'billing_details'       => $billing_details // checkout form show billing details fields
			),
			/*
			'paypalstandard' => array(
				'payment_gateway_label' => __( 'PayPal Standard', 'lime-catalog' ), // payment gateway label
				'payment_gateway_name'  => 'paypalstandard', // payment gateway name
				'create_an_account'     => '1', // checkout form show create an account fields
				'credit_card_details'   => '0', // checkout form show credit card details fields
				'billing_details'       => '0' // checkout form show billing details fields
				//'file_download'         => '1', // file download upon successful payment (0 or 1)
				//'supports'              => array( 'buy_now' )
			),
			*/
		);
	
		return apply_filters( 'lmctlg_payment_gateways', $gateways ); // <- extensible

	}
	
    public static function lmctlg_selected_gateway( $selected_gateway ) 
	{
		
	}
	
}

?>