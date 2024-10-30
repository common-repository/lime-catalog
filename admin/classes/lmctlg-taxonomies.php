<?php

/**
 * Taxonomies for Lime Catalog.
 *
 * @package     lime-catalog
 * @subpackage  Admin/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Taxonomies {

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
	 * Lime category taxonomy.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_limecategory_taxonomy() {
	 
		$labels = apply_filters( 'lmctlg_limecategory_taxonomy_labels', array(
			'name'              => __( 'Lime Catalog Categories', 'lime-catalog' ),
			'singular_name'     => __( 'Category', 'lime-catalog' ),
			'search_items'      => __( 'Search Categories', 'lime-catalog' ),
			'all_items'         => __( 'All Categories', 'lime-catalog' ),
			'edit_item'         => __( 'Edit Category', 'lime-catalog' ),
			'update_item'       => __( 'Update Category', 'lime-catalog' ),
			'add_new_item'      => __( 'Add New Category', 'lime-catalog' ),
			'new_item_name'     => __( 'New Item Category', 'lime-catalog' ),
			'menu_name'         => __( 'Categories', 'lime-catalog' ),
		) );
		
		$capabilities_limecategory = array(
			'manage_terms' => 'manage_limecategory',
			'edit_terms'   => 'edit_limecategory',
			'delete_terms' => 'delete_limecategory',
			'assign_terms' => 'assign_limecategory',
		);
	 
		$args = apply_filters( 'lmctlg_limecategory_taxonomy_args', array(
			'labels'            => $labels,
			'query_var'         => true,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'capabilities'      => $capabilities_limecategory,
		) );
	 
		register_taxonomy( 'limecategory', 'limecatalog', $args );
		
	}
	

	
	
}

?>