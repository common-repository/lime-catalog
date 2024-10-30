<?php

/**
 * The plugin bootstrap file
 *
 * The plugin uses the WordPress Plugin Boilerplate: 
 * A foundation for WordPress Plugin Development that aims to provide a clear and consistent guide for building your plugins.
 * Source: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           LimeCatalog
 * @author            Attila Abraham
 * @copyright         Copyright (c) Lime Catalog - Attila Abraham
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Lime Catalog
 * Plugin URI:        https://limecatalog.com
 * Description:       Responsive product catalog included with a built in e-commerce cart system to sell digital products. Works with any themes and most of the WordPress plugins.
 * Version:           1.0.3
 * Author:            Attila Abraham
 * Author URI:        https://limecatalog.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lime-catalog
 * Domain Path:       /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Plugin path
define( 'LMCTLG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
// Plugin URL
define( 'LMCTLG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
// Plugin FILE
define( 'LMCTLG_PLUGIN_FILE', __FILE__ );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lmctlg.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lmctlg() {
		
	$plugin = new LimeCatalog();
	$plugin->run();

}
run_lmctlg();

/**
 * The code that runs during plugin activation.
 *
 * @since    1.0.0
 */
function activate_lmctlg() {
	
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
	
	require_once LMCTLG_PLUGIN_DIR . 'includes/class-lmctlg-activator.php';
	
    LMCTLG_Activator::lmctlg_default_save_settings_options();
	
	LMCTLG_Activator::lmctlg_default_general_options();
	LMCTLG_Activator::lmctlg_default_currency_options();
	LMCTLG_Activator::lmctlg_default_cart_options();
	LMCTLG_Activator::lmctlg_payment_gateway_options();
	LMCTLG_Activator::lmctlg_default_template_options();
	LMCTLG_Activator::lmctlg_create_custom_upload_dir();
	LMCTLG_Activator::lmctlg_gateway_bacs_options();
	
	LMCTLG_Activator::lmctlg_order_items_table_install();
	LMCTLG_Activator::lmctlg_order_itemmeta_table_install();
	LMCTLG_Activator::lmctlg_order_downloads_table_install();
	
	LMCTLG_Activator::lmctlg_custom_roles();
	
	LMCTLG_Activator::lmctlg_email_settings_options();
	LMCTLG_Activator::lmctlg_order_receipts_options();
	LMCTLG_Activator::lmctlg_order_notifications_options();
	
	LMCTLG_Activator::lmctlg_flush_rewrite_rules();
	
}

/**
 * The code that runs during plugin deactivation.
 *
 * @since    1.0.0
 */
function deactivate_lmctlg() {
	
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
	
	require_once LMCTLG_PLUGIN_DIR . 'includes/class-lmctlg-deactivator.php';
	LMCTLG_Deactivator::lmctlg_plugin_deactivator_delete_options();
	
}

/**
 * The code that runs during plugin uninstallation.
 *
 * @since    1.0.0
 */
function uninstall_lmctlg() {
	
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
		
	//this check makes sure that this file is called manually.
	if (!defined("WP_UNINSTALL_PLUGIN"))
		return;
	
	require_once LMCTLG_PLUGIN_DIR . 'includes/class-lmctlg-uninstall.php';
	LMCTLG_Uninstall::lmctlg_plugin_uninstaller_delete_options();
	
}

register_activation_hook( __FILE__, 'activate_lmctlg' );
register_deactivation_hook( __FILE__, 'deactivate_lmctlg' ); 
register_uninstall_hook ( __FILE__, 'uninstall_lmctlg' );

?>
