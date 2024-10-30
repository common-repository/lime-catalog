<?php

/**
 * Countries class.
 *
 * @to-do Finish this class.
 *
 * @package     lime-catalog
 * @subpackage  Public/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/
 
class LMCTLG_Countries {

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
	 * Countries list.
	 * @return array
	 */
	public static function lmctlg_forms_get_countries() {
		
		return $countries;
	}
	
	/**
	 * Load the states.
	 * @return array if file exist
	 */
	public static function lmctlg_forms_get_states() {

	}
	
	
}

?>