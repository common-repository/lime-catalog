<?php

	/**
	 * Public Shortcodes.
	 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_shortcodes {
	
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
	 * Lime Catalog catalog home page.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_home_page()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/pages/lime-catalog-home.php'; // home and shopping cart pages template-archive-pages.php
	}
	
	/**
	 * Lime Catalog catalog products list page.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_products_list()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/pages/lime-catalog-products-list.php';
	}
	
	/**
	 * Lime Catalog catalog product view page.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_single_product_view()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/pages/lime-catalog-single-product-view.php';
	}
	
	/**
	 * Lime Catalog catalog search results page.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_search_results_page()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/pages/lime-catalog-search-results.php'; // home and shopping cart pages
	}
	
	/**
	 * Lime Catalog template files inner template header.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_inner_template_header() 
	{		
		// get options
		$lmctlg_template_options = get_option('lmctlg_template_options');
		$html = stripslashes($lmctlg_template_options['inner_template_header']);
		//$html = '<div class="container" style="margin-left: auto; margin-right: auto;">';
		return apply_filters( 'lmctlg_inner_template_header', $html ); // <- extensible
	}

	/**
	 * Lime Catalog template files inner template footer.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_inner_template_footer() 
	{
		// get options
		$lmctlg_template_options = get_option('lmctlg_template_options');
		$html = stripslashes($lmctlg_template_options['inner_template_footer']);
		//$html = '</div>';
		return apply_filters( 'lmctlg_inner_template_footer', $html ); // <- extensible
	}
	
	/**
	 * Lime Catalog catalog products.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_products()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/products.php';
	}
	
	/**
	 * Lime Catalog catalog home page products.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_home_products()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/home-products.php';
	}
	
	/**
	 * Lime Catalog catalog home page category boxes.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_category_boxes()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/home-category-boxes.php';
	}
	
	/**
	 * Lime Catalog catalog sidebar basket.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_sidebar_basket()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/pages/includes/sidebar-basket.php';
	}
	
	/**
	 * Lime Catalog catalog sidebar search.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_sidebar_search()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/pages/includes/sidebar-search.php';
	}
	
	/**
	 * Lime Catalog catalog sidebar categories.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_sidebar_nav()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/pages/includes/categories-nav.php';
	}
	
	/**
	 * Lime Catalog catalog grid or list view selector.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_grid_or_list_view()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/grid-or-list-view.php';
	}
	
	/**
	 * Lime Catalog catalog product list sub category boxes.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_sub_category_boxes()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/sub-category-boxes.php';
	}

	/**
	 * Lime Catalog catalog breadcrumbs.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_breadcrumbs()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/breadcrumbs.php';
	}
	
	/**
	 * Lime Catalog catalog search results.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_search_results()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/search-results.php';
	}
	
	/**
	 * Lime Catalog catalog single product view.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_product_view()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/product-view.php';
	}
	
	/**
	 * Lime Catalog shopping cart cart page.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_cart()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/cart.php';
	}
	
	/**
	 * Lime Catalog shopping cart cart totals.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_cart_totals()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/cart-totals.php';
	}
	
	/**
	 * Lime Catalog shopping cart checkout page.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_checkout()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/checkout.php';
	}
	
	/**
	 * Lime Catalog shopping cart checkout totals.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_checkout_totals()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/checkout-totals.php';
	}
	
	/**
	 * Lime Catalog shopping cart basket.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_basket()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/basket.php';
	}
	
	/**
	 * Lime Catalog catalog search.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_search()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/search.php';
	}
	
	/**
	 * Lime Catalog catalog categories nav.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_categories_nav()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/categories-nav.php';
	}
	
	
	##### ->
	
	
	/**
	 * Lime Catalog login form.
	 *
	 * @since   1.0.0
	 * @param   array $atts
	 * @return  void
	 */
	public function lmctlg_shortcode_login_form( $atts )
	{ 
	  // defaults
      $atts = shortcode_atts( array(
        'title' => __( 'Login to your Account', 'lime-catalog' ),
      ), $atts );
	
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/login-form.php';
	}
	
	/**
	 * Lime Catalog register form.
	 *
	 * @since   1.0.0
	 * @param   array $atts
	 * @return  void
	 */
	public function lmctlg_shortcode_register_form( $atts )
	{ 
	  // defaults
      $atts = shortcode_atts( array(
        'title' => __( 'Create an Account', 'lime-catalog' ),
        'role'  => 'lime_subscriber',
      ), $atts );
	
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/register-form.php';
	}
	
	/**
	 * Lime Catalog forgot password form.
	 *
	 * @since   1.0.0
	 * @param   array $atts
	 * @return  void
	 */
	public function lmctlg_shortcode_forgot_pw_form( $atts )
	{ 
	  // defaults
      $atts = shortcode_atts( array(
        'title' => __( 'Forgot Password', 'lime-catalog' ),
      ), $atts );
	
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/forgot-pw-form.php';
	}
	
	/**
	 * Lime Catalog account.
	 *
	 * @since   1.0.0
	 * @param   array $atts
	 * @return  void
	 */
	public function lmctlg_shortcode_account( $atts )
	{ 
	  // defaults
      $atts = shortcode_atts( array(
        'role'  => 'lime_subscriber',
      ), $atts );
	
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/account.php';
	}
	
	/**
	 * Lime Catalog login manager.
	 * Include login, register and forgot password forms.
	 *
	 * @since   1.0.0
	 * @param   array $atts
	 * @return  void
	 */
	public function lmctlg_shortcode_login_manager( $atts )
	{ 
	  // defaults
      $atts = shortcode_atts( array(
        'role'  => 'lime_subscriber',
      ), $atts );
	
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/login-manager.php';
	}
	
	/**
	 * Lime Catalog contact form.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_contact_form( $atts )
	{ 
	  // defaults
      $atts = shortcode_atts( array(
        'title' => __( 'Please enter your contact details and a short message below, we will try to answer your query as soon as possible.', 'lime-catalog' ), //e.g. Please enter your contact details and a short message below, we will try to answer your query as soon as possible.
      ), $atts );
	  
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/contact-form.php';
	}
	
	/**
	 * Lime Catalog order history.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_order_history()
	{ 
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/order-history.php';
	}
	
	/**
	 * Lime Catalog payment buttons. add to cart, buy now etc.
	 *
	 * @since   1.0.0
	 * @param   array $button_atts
	 * @return  void
	 */
	public function lmctlg_shortcode_payment_buttons( $button_atts )
	{ 
	  // defaults: 
	  // Buy Now Button
      $button_atts = shortcode_atts( array(
		'id'         => '0', 
		'page'       => 'checkout', // cart or checkout
		'label_one'  => __( ' - Buy Now', 'lime-catalog' ), // - Buy Now, - Add To Cart
		'label_two'  => __( ' + Checkout', 'lime-catalog' ), // + Checkout, + View Cart
		'color_one'  => '#ec7a5c', // orange
		'color_two'  => '#5F9530', // green
      ), $button_atts );
	  
	  //$shortcode = '[lmctlg_payment_button id="" page="" label_one="" color_one="" label_two="" color_two=""]';
	  
	  // payment button
	  return LMCTLG_Payment_Buttons::lmctlg_output_payment_button( $button_atts ); 

	}
	
	/**
	 * Lime Catalog order receipt.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function lmctlg_shortcode_order_receipt( $atts )
	{ 
	  // defaults
      $atts = shortcode_atts( array(
        'title' => __( '', 'lime-catalog' ), //e.g. Thank you for your purchase!
      ), $atts );
	  
      require_once LMCTLG_PLUGIN_DIR . 'public/shortcodes/order-receipt.php';
	}
	
}

?>