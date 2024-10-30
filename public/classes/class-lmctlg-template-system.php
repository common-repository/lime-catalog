<?php

/**
 * Amount Format and Calculation and Currency
 *
 * @package     lime-catalog
 * @subpackage  Admin/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Template_System {

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
	 * HOME PAGE - route archive- template.
	 *
	 * @since   1.0.0
	 * @param   void $template
	 * @return  void $template
	 */
	public function lmctlg_load_archive_template($template){
	  if(is_post_type_archive('limecatalog')){
		$theme_files = array('archive-limecatalog.php'); //origin
		$exists_in_theme = locate_template($theme_files, false);
		if($exists_in_theme == ''){
		  return LMCTLG_PLUGIN_DIR . 'public/templates/default/archive-limecatalog.php'; // lime catalog home page
		}
	  }
	  return $template;
	}	
	
	/**
	 * Single product view - route single - template.
	 *
	 * @global $post
	 * @global $wp_query
	 *
	 * @since   1.0.0
	 * @param   void $single_template
	 * @return  void $single_template
	 */
	public function lmctlg_load_single_template($single_template){
	  global $post, $wp_query;

	  $found = locate_template('single-limecatalog.php', false); // Custom Template
	  //$found = locate_template('single.php');
	  if($post->post_type == 'limecatalog' && $found == ''){ 
		  
		$single_template = LMCTLG_PLUGIN_DIR . 'public/templates/default/single-limecatalog.php'; // lime catalog single product view page-lm-cart.php
	  }
	  return $single_template;
	}
	
	/**
	 * Search Results - template.
	 *
	 * @global $post
	 * @global $wp_query
	 *
	 * @since   1.0.0
	 * @param   void $template_search
	 * @return  void $template_search
	 */
	public function lmctlg_template_chooser_search($template_search)   
	{    
	  global $post, $wp_query;
      
	  $post_type = get_query_var('post_type');  

	  if( $wp_query->is_search && $post_type == 'limecatalog' )   
	  {
		  
		$found = locate_template('search-limecatalog.php', false);
		
		if ( $found !== '' ) {
			// load custom template search page
			get_template_part( 'search', 'limecatalog' ); // page : search-limecatalog.php
			exit();
		} else {
			// load default
			return LMCTLG_PLUGIN_DIR . 'public/templates/default/search-results.php';
		}
		
	  } 
	  
	  return $template_search;   
	}
	
	/**
	 * Category - taxonomy - limecategory.
	 *
	 * @since   1.0.0
	 * @param   void $template
	 * @return  void $template
	 */
	public function lmctlg_load_taxonomy_template($template) 
	{	
		if ( is_tax('limecategory') ) 
		{
			$template = 'taxonomy-limecategory.php'; // Category - Custom Template
			
			if (file_exists ( get_template_directory() . '/' . $template) ) 
			{
				return get_template_directory() . '/' . $template;
			} else {
				return LMCTLG_PLUGIN_DIR . 'public/templates/default/' . $template; // lime catalog products list 
			}
		} 
		return $template;
	}
	
	
	
}

?>