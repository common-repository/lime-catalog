<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://limecatalog.com
 * @since      1.0.0
 *
 * @package    Lime Catalog
 * @subpackage Lime Catalog/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Lime Catalog
 * @subpackage Lime Catalog/includes
 * @author     Attila Abraham
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Deactivator {

	/**
	 * Delete options on plugin deactivation if save settings not == 1
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_plugin_deactivator_delete_options() 
	{
		// check if we need to save the data on plugin deactivation
		if ( get_option('lmctlg_save_settings_options') ) {
			// get options
			$lmctlg_save_settings_options = get_option('lmctlg_save_settings_options');
			$plugin_deactivation_save_settings = $lmctlg_save_settings_options['lmctlg_plugin_deactivation_save_settings'];
			// delete options
			if ( $plugin_deactivation_save_settings !== '1' ) { 
			   delete_option('lmctlg_general_options');
			   delete_option('lmctlg_currency_options');
			   delete_option('lmctlg_cart_options');
			   delete_option('lmctlg_save_settings_options');
			   delete_option('lmctlg_payment_gateway_options');
			   delete_option('lmctlg_template_options');
			   delete_option('lmctlg_gateway_bacs_options'); 
			   delete_option('lmctlg_order_email_settings_options');  
			   delete_option('lmctlg_order_receipts_options'); 
			   delete_option('lmctlg_order_notifications_options'); 
			}
		} else {
		   return;
		}
	}

}

?>
