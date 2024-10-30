<?php

/**
 * Public Main class.
 *
 * @package     lime-catalog
 * @subpackage  Public
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Allow redirection, even if the theme starts to send output to the browser.
	 * Usees:  page=cart and page=checkout
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function lmctlg_do_output_buffer() {
	  ob_start();
	}
	
	/**
	 * Check if multisite is enabled then get network or home url
	 *
	 * @since 1.0.0
	 * @return void url
	 */
	public static function lmctlg_get_site_url() {
		if ( is_multisite() ) {
			return network_home_url();
		} else {
			return home_url();
		}
	}

	/**
	 * Check if page exist by post name (slug).
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param  string $post_name
	 * @return bool
	 */
	public static function lmctlg_check_if_page_slug_exists($post_name) {
		
	    if ( empty( $post_name ) )
	    return;
		
		global $wpdb;
		$posts = $wpdb->prefix . 'posts'; // table, do not forget about tables prefix 
		
		if($wpdb->get_row("SELECT post_name FROM $posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Ajax Front end items view selector. Save the selected view in cookie.
	 * Items View: Normal, Large or List View
	 *
	 * @since 1.0.0
	 * @return void
	 */
    public function lmctlg_items_view_process() 
	{
		if ( isset ($_POST['itemsview']) ) {

			// get domain name
			$domain = LMCTLG_Helper::lmctlg_site_domain_name();

			// get form data
			$limecatalogitemsview = sanitize_text_field( $_POST['itemsview'] );

			// cookie name
			$items_view_cookie_name  = LMCTLG_Cookies::lmctlg_items_view_cookie_name();
		
			// delete cookie
			$del_cookie = LMCTLG_Cookies::lmctlg_delete_cookie($name=$items_view_cookie_name, $path = '/', $domain, $remove_from_global = false);
			// set cookie, expires in 1 year
			$set_cookie = LMCTLG_Cookies::lmctlg_set_cookie($name=$items_view_cookie_name, $value=$limecatalogitemsview, $expiry = 31536000, $path = '/', $domain, $secure = false, $httponly = false );
			
		} else {
			return;
		}
		
	    exit; // don't forget to exit!
		
	}

	/**
	 * Exclude children in taxonomy query.
	 *
	 * @since 1.0.0
	 * @param  object $query
	 * @return void
	 */
	public function lmctlg_limecategory_exclude_children($query) {
	
		if ($query->is_main_query() && $query->is_tax('limecategory')):
	
			$tax_obj = $query->get_queried_object();
	
			$tax_query = array(
	
				'taxonomy' => $tax_obj->taxonomy,
	
				'field' => 'slug',
	
				'terms' => $tax_obj->slug,
	
				'include_children' => FALSE
	
			);
	
			$query->tax_query->queries[] = $tax_query;
	
			$query->query_vars['tax_query'] = $query->tax_query->queries;
	
		endif;
	
	}

	/**
	 * Set Taxonomy query.
	 *
	 * @since 1.0.0
	 * @param  object $query
	 * @return void
	 */
	public function lmctlg_limecategory_tax_query($query) {
	
		if (is_admin() || !$query->is_main_query())
	
			return;
	     
		 // taxonomy=limecategory&post_type=limecatalog
		if ( is_tax('limecategory') ) {
			
            $lmctlg_general_options = get_option('lmctlg_general_options'); 
			
			//$query->set('posts_per_page', '6'); // or use variable key: posts_per_page or posts_per_archive_page
	
			$query->set('posts_per_page', $lmctlg_general_options['number_of_items_per_page']); // items per page
	
			$query->set('orderby', $lmctlg_general_options['items_order_by']); // ID, date, title...
	
			$query->set('order', $lmctlg_general_options['items_order']);// ASC, DESC
	
			return;
	
		}
	
	}

	/**
	 * Set Taxonomy query post per page.
	 *
	 * @since 1.0.0
	 * @param  object $query
	 * @return void
	 */
	public function lmctlg_limit_archive_posts_per_page( $query ) {
	  if ( !is_admin() && $query->is_main_query() && is_post_type_archive( 'limecatalog' ) ) {
		$query->set( 'posts_per_page', '5' );
		//$query->set( 'posts_per_archive_page', 5 );
	  }
	}

	/**
	 * Archive page limit post per page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_limit_posts_per_archive_page() {
		if ( is_category() )
			set_query_var('posts_per_page', '5'); // or use variable key: posts_per_page or posts_per_archive_page
	}
	
	/**
	 * Remove hardcoded width and height from thumbnail images.
	 *
	 * @since 1.0.0
	 * @param  string $html
	 * @param  int    $post_id
	 * @param  int    $post_image_id
	 * @return void   $html
	 */
	public function lmctlg_remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
		return $html;
	}

	/**
	 * Shorten titles for lime categories.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param  string $after
	 * @param  int    $length
	 * @return string $s_title
	 */
	public static function lmctlg_shorten_title($after = '', $length) {
		$s_title = explode(' ', get_the_title(), $length);
		if (count($s_title)>=$length) {
			array_pop($s_title);
			$s_title = implode(" ",$s_title). $after;
		} else {
			$s_title = implode(" ",$s_title);
		}
		return $s_title;
	}

	/**
	 * Shorten text.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param  string $text
	 * @param  int    $limit
	 * @return string $text
	 */
	public static function lmctlg_shorten_text($text, $limit) {
		  if (str_word_count($text, 0) > $limit) {
			  $words = str_word_count($text, 2);
			  $pos = array_keys($words);
			  $text = substr($text, 0, $pos[$limit]) . '...';
		  }
		  return $text;
	}
	
	/**
	 * Find out if there is a shortcode on the page.
	 *
	 * @global $post
	 *
	 * @since 1.0.0
	 * @access public static
	 * @return bool
	 */
	public static function lmctlg_check_if_has_shortcode() {
		global $post;

		// Currently ( 5/8/2014 ) the has_shortcode() function will not find a 
		// nested shortcode. This seems to do the trick currently, will switch if 
		// has_shortcode() gets updated. -NY
		// check if post exist
		if ($post) { 
		// check if has shortcode
			if ( strpos($post->post_content, '[lime_catalog') !== false  ) {							   							   
				return true;
			}
		}

		return false;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since      1.0.0
     * @return     void
	 */
	public function enqueue_styles() {

	  wp_enqueue_style( 'lmctlg-front-end', plugin_dir_url( __FILE__ ) . 'assets/css/cwctlg-front-end.css', array(), $this->version, 'all' );
	  
	  wp_enqueue_style( 'lmctlg-widgets-style', plugin_dir_url( __FILE__ ) . 'assets/css/widgets-style.css', array(), $this->version, 'all' );
	  
	  wp_enqueue_style( 'lmctlg-lime-catalog-grid', plugin_dir_url( __FILE__ ) . 'assets/css/lime-catalog-grid.css', array(), $this->version, 'all' );
	  
	  wp_enqueue_style( 'lmctlg-table-responsive', plugin_dir_url( __FILE__ ) . 'assets/css/table-responsive.css', array(), $this->version, 'all' );
	  
	  wp_enqueue_style( 'lmctlg-glyphicon', plugins_url() . '/' . $this->plugin_name . '/assets/css/glyphicon.css', array(), $this->version, 'all' );
	  wp_enqueue_style( 'lmctlg-cw-form', plugins_url() . '/' . $this->plugin_name . '/assets/css/cw-form.css', array(), $this->version, 'all' );
      //wp_enqueue_style( 'lmctlg-cw-orange', plugins_url() . '/' . $this->plugin_name . '/assets/css/cw-orange.css', array(), $this->version, 'all' ); // form orange
	  //wp_enqueue_style( 'lmctlg-cw-blue', plugins_url() . '/' . $this->plugin_name . '/assets/css/cw-blue.css', array(), $this->version, 'all' ); // form blue
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since      1.0.0
     * @return     void
	 */
	public function enqueue_scripts() {
			
		$formloaderimg = LMCTLG_Process_Order::lmctlg_order_processing_form_loader_image(); // form loader image
		$lmctlg_success_redirect_url = LMCTLG_Process_Order::lmctlg_order_processing_success_redirect_url(); // redirect url (option)
		
		$login_success_redirect_url = LMCTLG_Login_Register::lmctlg_login_success_redirect_url(); // redirect url (option)
			
		// Add to cart button
		wp_enqueue_script( 'lmctlg-shopping-cart', plugin_dir_url( __FILE__ ) . 'shopping-cart/assets/js/lmctlg-shopping-cart.js', array( 'jquery' ), $this->version, true );
		
		wp_localize_script( 'lmctlg-shopping-cart', 'lmctlg_ajax_shopping_cart', array( 
			'lmctlg_wp_ajax_url'           => admin_url( 'admin-ajax.php' ),
			'lmctlg_login_redirect_url'    => $login_success_redirect_url,
			'lmctlg_register_redirect_url' => $_SERVER['REQUEST_URI'],
			'lmctlg_form_loader_img'       => $formloaderimg,
			'lmctlg_refress_page'          => $_SERVER['REQUEST_URI'], // for payment gateways
			'lmctlg_success_redirect_url'  => $lmctlg_success_redirect_url, // for payment gateways
			'lmctlg_loading_message'       => __('Signing in, please wait...', 'lime-catalog')
		));

	}

}

?>
