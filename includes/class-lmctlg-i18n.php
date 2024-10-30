<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://limecatalog.com/
 * @since      1.0.0
 *
 * @package    Lime Catalog
 * @subpackage Lime Catalog/includes 
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Lime Catalog
 * @subpackage Lime Catalog/includes
 * @author     Attila Abraham
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'lime-catalog',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	public function load_i18n_debug() {
	    
	    $loaded=load_plugin_textdomain( 'lime-catalog', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	
		if ( ! $loaded ){
			echo "<br>";
			echo "Error: the mo file was not found! ";
			exit();
		}else{
			echo "<br><strong>Debug info</strong>:<br/>";
			echo "WPLANG: ". WPLANG;
			echo "<br/>";
			echo "translate test: ". __('Some text','lime-catalog');
			exit();
		}
	}
	
	public function debug_load_textdomain( $domain , $mofile  ){
		echo "Trying ",$domain," at ",$mofile,"<br />\n";
		exit();
	}

}

?>
