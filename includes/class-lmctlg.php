<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current 
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
final class LimeCatalog {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'lime-catalog'; //plugin-name
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		$this->load_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lmctlg-loader.php';
		$this->loader = new LMCTLG_Loader();
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lmctlg-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-lmctlg-public.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lmctlg-i18n.php';
		
		// WP Rest API
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-wp-rest-api.php';

		// WP Cron
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-wp-cron.php';
		
		// Cookies
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-cookies.php';
		
		// shopping cart - File Download API
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/api/class-lmctlg-file-download-api.php';
		
		// Orders Api
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/api/lmctlg-orders-api.php';
		
		// Custom Data Tables : lmctlg_order_items and lmctlg_order_itemmeta
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/custom-db-tables/lmctlg-db-tables-items.php';	
		// Custom Data Table : lmctlg_order_downloads
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/custom-db-tables/lmctlg-db-table-downloads.php';	
		
		// shopping cart - Single Order Data
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-single-order-data.php';
		
		// shopping cart - error log
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-error-log.php';
		// shopping cart - form validation
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-validate.php';
		// shopping cart - Validate Order Form
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-validate-order-form.php';

		// Admin Notices
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-admin-notices.php';
		// custom post types
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-custom-post-types.php';
		// custom post types Capabilities
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-cpts-capabilities.php';
		// taxonomies
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-taxonomies.php';
		// Restrict Capabilities Media Library
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-restrict-mdlibrary.php';
		// items metaboxes
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-items-metaboxes.php';
		// orders metaboxes
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-orders-metaboxes.php';

		// Custom Post Statuses
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-custom-post-statuses.php';
		
		// Export Table to CSV = to-do not finish
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-export-table-to-csv.php';
		
		// Downloads List Table
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-downloads-list-table.php';
		
		// Admin Manage Downloads
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/lmctlg-manage-downloads.php';
		
		// Helper
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-helper.php';

		// emailer
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-emailer.php';

		// template system
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-template-system.php';
		
		// shortcodes
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-shortcodes.php';
		
		// login register
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-login-register.php';
		
		// contact
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-lmctlg-contact-us.php';
		
		// shopping cart - payment buttons
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-payment-buttons.php';
		
		// shopping cart - cart
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-cart.php';
		
		// shopping cart - checkout
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-checkout.php';
		
		// shopping cart - amount 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-amount.php';
		
		// shopping cart - countries
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/countries/countries.php'; // functions
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/countries/class-lmctlg-countries.php';
		
		// shopping cart - payment gateways
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-payment-gateways.php';
		
		// shopping cart - process order
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-process-order.php';
		
		// shopping cart - process gateways
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/payment-gateways/bacs.php';
		
		// shopping cart - Downloadable Products
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-downloadable-products.php';
		
		// shopping cart - Send Notification Emails
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shopping-cart/classes/class-lmctlg-notification-emails.php';
		
		// Basket Widget
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/widgets/basket.php';
		// Search Widget
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/widgets/search.php';
		// Categories Nav Widget
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/widgets/categories-nav.php';

	}
	
	private function load_shortcodes() 
	{
		$plugin_shortcodes = new LMCTLG_shortcodes( $this->get_plugin_name(), $this->get_version() );
		
		// pages
		add_shortcode('lime_catalog_home_page', array( $plugin_shortcodes, 'lmctlg_home_page') );
		add_shortcode('lime_catalog_products_list', array( $plugin_shortcodes, 'lmctlg_products_list') );
		add_shortcode('lime_catalog_single_product_view', array( $plugin_shortcodes, 'lmctlg_single_product_view') );
		add_shortcode('lime_catalog_search_results', array( $plugin_shortcodes, 'lmctlg_search_results_page') );
		
		// templates
		add_shortcode('lmctlg_inner_template_header', array( $plugin_shortcodes, 'lmctlg_inner_template_header') );
		add_shortcode('lmctlg_inner_template_footer', array( $plugin_shortcodes, 'lmctlg_inner_template_footer') );
		
		// products
		add_shortcode('lmctlg_products', array( $plugin_shortcodes, 'lmctlg_shortcode_products') ); // products
		
		// home page
		add_shortcode('lmctlg_home_products', array( $plugin_shortcodes, 'lmctlg_shortcode_home_products') ); // home page - products
		add_shortcode('lmctlg_home_category_boxes', array( $plugin_shortcodes, 'lmctlg_shortcode_category_boxes') ); // home page - category boxes
		
		// sidebar - basket
		add_shortcode('lmctlg_sidebar_basket', array( $plugin_shortcodes, 'lmctlg_shortcode_sidebar_basket') ); // sidebar - basket
		// sidebar - search
		add_shortcode('lmctlg_sidebar_search', array( $plugin_shortcodes, 'lmctlg_shortcode_sidebar_search') ); // sidebar - search
		// sidebar - nav
		add_shortcode('lmctlg_sidebar_nav', array( $plugin_shortcodes, 'lmctlg_shortcode_sidebar_nav') ); // sidebar - nav
		
		// grid or list view selector
		add_shortcode('lmctlg_grid_or_list_view', array( $plugin_shortcodes, 'lmctlg_shortcode_grid_or_list_view') ); // grid or list view selector
		// product list - sub category boxes
        add_shortcode('lmctlg_sub_category_boxes', array( $plugin_shortcodes, 'lmctlg_shortcode_sub_category_boxes') ); // sub category boxes
		// breadcrumbs
        add_shortcode('lmctlg_breadcrumbs', array( $plugin_shortcodes, 'lmctlg_shortcode_breadcrumbs') ); // breadcrumbs
		// search results
        add_shortcode('lmctlg_search_results', array( $plugin_shortcodes, 'lmctlg_shortcode_search_results') ); // search results
		// single product view
        add_shortcode('lmctlg_product_view', array( $plugin_shortcodes, 'lmctlg_shortcode_product_view') ); // single product view
		
		// catalog - search
        add_shortcode('lmctlg_search', array( $plugin_shortcodes, 'lmctlg_shortcode_search') ); // catalog - search
		// catalog - categories nav
        add_shortcode('lmctlg_categories_nav', array( $plugin_shortcodes, 'lmctlg_shortcode_categories_nav') ); // catalog - categories nav
		// catalog - login form
        add_shortcode('lmctlg_login_form', array( $plugin_shortcodes, 'lmctlg_shortcode_login_form') ); // catalog - login form
		// catalog - register form
        add_shortcode('lmctlg_register_form', array( $plugin_shortcodes, 'lmctlg_shortcode_register_form') ); // catalog - register form
		// catalog - forgot password form
        add_shortcode('lmctlg_forgot_pw_form', array( $plugin_shortcodes, 'lmctlg_shortcode_forgot_pw_form') ); // catalog - forgot password form
		// catalog - account
        add_shortcode('lmctlg_account', array( $plugin_shortcodes, 'lmctlg_shortcode_account') ); // catalog - account
		// catalog - login, register and forgot password forms
        add_shortcode('lmctlg_login_manager', array( $plugin_shortcodes, 'lmctlg_shortcode_login_manager') ); // catalog - login manager
		// catalog - contact form
        add_shortcode('lmctlg_contact_form', array( $plugin_shortcodes, 'lmctlg_shortcode_contact_form') ); // catalog - contact form
		// catalog - order history
        add_shortcode('lmctlg_order_history', array( $plugin_shortcodes, 'lmctlg_shortcode_order_history') ); // catalog - order history
		// catalog - payment buttons, add to cart, buy now etc.
        add_shortcode('lmctlg_payment_button', array( $plugin_shortcodes, 'lmctlg_shortcode_payment_buttons') ); // catalog - payment buttons
		// catalog - order receipt
        add_shortcode('lmctlg_order_receipt', array( $plugin_shortcodes, 'lmctlg_shortcode_order_receipt') ); // catalog - order receipt
		
		// shopping cart - cart
        add_shortcode('lmctlg_cart', array( $plugin_shortcodes, 'lmctlg_shortcode_cart') ); // shopping cart - cart
		// shopping cart - cart totals
        add_shortcode('lmctlg_cart_totals', array( $plugin_shortcodes, 'lmctlg_shortcode_cart_totals') ); // shopping cart - cart totals
		// shopping cart - checkout
        add_shortcode('lmctlg_checkout', array( $plugin_shortcodes, 'lmctlg_shortcode_checkout') ); // shopping cart - checkout
		// shopping cart - checkout totals
        add_shortcode('lmctlg_checkout_totals', array( $plugin_shortcodes, 'lmctlg_shortcode_checkout_totals') ); // shopping cart - checkout totals
		// shopping cart - basket
        add_shortcode('lmctlg_basket', array( $plugin_shortcodes, 'lmctlg_shortcode_basket') ); // shopping cart - basket
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new LMCTLG_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
        //$this->loader->add_action( 'init', $plugin_i18n, 'load_i18n_debug' );
		//$this->loader->add_action( 'load_textdomain', $plugin_i18n, 'debug_load_textdomain', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin       = new LMCTLG_Admin( $this->get_plugin_name(), $this->get_version() );
		$customposttypes    = new LMCTLG_Custom_Post_Types( $this->get_plugin_name(), $this->get_version() );
		$cpts_caps          = new LMCTLG_CPTS_Capabilities( $this->get_plugin_name(), $this->get_version() );
		$restrict_md_lib    = new LMCTLG_Restrict_MDLibrary( $this->get_plugin_name(), $this->get_version() );
		$taxonomies         = new LMCTLG_Taxonomies( $this->get_plugin_name(), $this->get_version() );
		$items_metaboxes    = new LMCTLG_Items_Metaboxes( $this->get_plugin_name(), $this->get_version() );
		$ordersmetaboxes    = new LMCTLG_Orders_Metaboxes( $this->get_plugin_name(), $this->get_version() );
		$custompoststatuses = new LMCTLG_Custom_Post_Statuses( $this->get_plugin_name(), $this->get_version() );
		$admin_notices      = new LMCTLG_Admin_Notices( $this->get_plugin_name(), $this->get_version() );
		$manage_downloads   = new LMCTLG_Admin_Manage_Downloads( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// custom upload folder
		$this->loader->add_action( 'admin_init', $plugin_admin, 'lmctlg_load_custom_upload_filter' ); 
		//$this->loader->add_filter( 'upload_dir', $plugin_admin, 'lmctlg_custom_prefix_upload_dir' ); right now not in use
		
		$this->loader->add_filter( 'upload_mimes', $plugin_admin, 'lmctlg_add_mimes_multisite' ); // mimes
		
		// register register_post_type
		$this->loader->add_action( 'init', $customposttypes, 'lmctlg_custom_post_types' );
		$this->loader->add_filter( 'manage_edit-limecatalog_columns', $customposttypes, 'lmctlg_limecatalog_items_columns' ); // use: manage_edit-{$post_type}_columns
		$this->loader->add_action( 'manage_limecatalog_posts_custom_column', $customposttypes, 'lmctlg_limecatalog_render_items_columns', 10, 2 ); //  use: manage_{$post_type}_posts_custom_column
		$this->loader->add_filter( 'manage_edit-lime_shop_orders_columns', $customposttypes, 'lmctlg_lime_shop_orders_columns' ); // use: manage_edit-{$post_type}_columns
		$this->loader->add_action( 'manage_lime_shop_orders_posts_custom_column', $customposttypes, 'lmctlg_lime_shop_render_orders_columns', 10, 2 ); //  use: manage_{$post_type}_posts_custom_column
		$this->loader->add_filter( 'post_row_actions', $customposttypes, 'lmctlg_orders_remove_row_actions', 10, 2 ); // post_row_actions , remove Quick Edit, View etc.
		
		// capabilities
		$this->loader->add_action( 'init', $cpts_caps, 'lmctlg_manage_items_capabilities' ); // items, use: init
		$this->loader->add_action( 'init', $cpts_caps, 'lmctlg_manage_orders_capabilities' ); // orders, use: init 
		$this->loader->add_action( 'init', $cpts_caps, 'lmctlg_manage_limecategory_taxonomy_capabilities' );
		
		// restrick capabilities media library
		$this->loader->add_action( 'pre_get_posts', $restrict_md_lib, 'lmctlg_restrick_users_view_own_attachments', 10, 1 );
		
		// register register_taxonomy
		$this->loader->add_action( 'init', $taxonomies, 'lmctlg_limecategory_taxonomy' ); // should use init	
		
		// custom post type: 'lime_shop_orders', custom messages for updates
		$this->loader->add_filter( 'post_updated_messages', $customposttypes, 'lmctlg_lime_shop_orders_update_messages' ); 

		// Adding Dashboard Menu - settings
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'lmctlg_add_admin_menu' );
		
		// Source: https://pippinsplugins.com/adding-custom-meta-fields-to-taxonomies/
		// The first parameter is what determines the taxonomy that this field gets added to. 
		// It uses this format: {$taxonomy_name}_add_form_fields.
        $this->loader->add_action( 'limecategory_add_form_fields', $items_metaboxes, 'lmctlg_limecategory_tax_add_new_meta_field', 10, 2 );	
		// It uses this format: {$taxonomy_name}_edit_form_fields.
		$this->loader->add_action( 'limecategory_edit_form_fields', $items_metaboxes, 'lmctlg_limecategory_tax_edit_meta_field', 10, 2 );
		// It uses this format: edited_{$taxonomy_name}.
		$this->loader->add_action( 'edited_limecategory', $items_metaboxes, 'lmctlg_limecategory_save_tax_custom_meta', 10, 2 );
		// It uses this format: create_{$taxonomy_name}.
		$this->loader->add_action( 'create_limecategory', $items_metaboxes, 'lmctlg_limecategory_save_tax_custom_meta', 10, 2 );
		
		// custom image sizes
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_custom_image_sizes' ); // use init or after_setup_theme
		$this->loader->add_filter( 'image_size_names_choose', $plugin_admin, 'lmctlg_show_image_sizes' ); // use: image_size_names_choose
		
		// Meta Boxes
		$this->loader->add_action( 'add_meta_boxes', $items_metaboxes, 'lmctlg_add_metabox_item_data' );	// meta box item data
		$this->loader->add_action( 'save_post', $items_metaboxes, 'lmctlg_save_metabox_item_data', 10, 2 );	// save meta box item data
		$this->loader->add_action( 'add_meta_boxes', $items_metaboxes, 'lmctlg_add_metabox_item_short_desc' );	// meta box item short desc
		$this->loader->add_action( 'save_post', $items_metaboxes, 'lmctlg_save_metabox_item_short_desc', 10, 2 );	// save meta box short desc
		$this->loader->add_action( 'add_meta_boxes', $items_metaboxes, 'lmctlg_add_metabox_payment_button_shortcode' );	// payment button shortcode
		
		// Meta Boxes - Save Order Title
		$this->loader->add_action( 'save_post', $ordersmetaboxes, 'lmctlg_order_save_new_post_title', 10, 1 );	// save new post title
		
		// Meta Boxes - Order General Details
		$this->loader->add_action( 'add_meta_boxes', $ordersmetaboxes, 'lmctlg_add_metabox_order_general_details' );	// meta box general details
		$this->loader->add_action( 'save_post', $ordersmetaboxes, 'lmctlg_save_metabox_order_general_details', 10, 1 );	// save meta box general details
		// Meta Boxes - Order Billing Details
		$this->loader->add_action( 'add_meta_boxes', $ordersmetaboxes, 'lmctlg_add_metabox_order_billing_details' );	// meta box order details
		$this->loader->add_action( 'save_post', $ordersmetaboxes, 'lmctlg_save_metabox_order_billing_details', 10, 1 );	// save meta box order details
		// Meta Boxes - Order Items
		$this->loader->add_action( 'add_meta_boxes', $ordersmetaboxes, 'lmctlg_add_metabox_order_items' );	// meta box order items
		$this->loader->add_action( 'save_post', $ordersmetaboxes, 'lmctlg_save_metabox_order_items', 10, 1 );	// save meta box order items
		
		// Meta Boxes - Resend Order Receipt (Important!!! fire after all metaboxes)
		$this->loader->add_action( 'save_post', $ordersmetaboxes, 'lmctlg_order_resend_order_receipt', 10, 1 );	// resend order receipt
		
		// It's important to note the 'before_delete_post' hook runs only when the WordPress user empties the Trash
		$this->loader->add_action( 'before_delete_post', $ordersmetaboxes, 'lmctlg_delete_single_order_data_if_empties_the_trash' );	// delete order data from custom tables
		
		// remove
		$this->loader->add_action( 'init', $ordersmetaboxes, 'lmctlg_remove_custom_post_type_support' );
		// hide publishing actions for publish metabox
		$this->loader->add_action( 'post_submitbox_misc_actions', $ordersmetaboxes, 'lmctlg_hide_publishing_actions' );
		
		// // publish meta add data (publish button)
		$this->loader->add_action( 'post_submitbox_misc_actions', $ordersmetaboxes, 'lmctlg_publish_meta_data' );
		
		// Admin Notices
		$this->loader->add_action( 'admin_notices', $admin_notices, 'lmctlg_admin_notice_order_receipt_sent' );
		
		// Settings - General Options
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_general_options_form_process' );
		
		// Settings - Currency Options
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_currency_options_form_process' );
		
		// Settings - Cart Options
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_cart_options_form_process' );
		
		// General Settings - Save Settings Options
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_save_settings_options_form_process' );
		
		// Settings - Template Options
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_template_options_form_process' );
		
		// Settings - Payment Gateways - main
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_payment_gateway_settings_form_process' );
		
		// Settings - Payment Gateways - BACS
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_payment_gateway_bacs_form_process' );
		
		// Settings - Emails - Email Settings
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_order_email_settings_options_form_process' );
		// Settings - Emails - Order Receipts
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_order_receipts_options_form_process' );
		// Settings - Emails - Order Notifications
		$this->loader->add_action( 'init', $plugin_admin, 'lmctlg_order_notifications_options_form_process' );

		// custom post status - completed
		$this->loader->add_action( 'init', $custompoststatuses, 'lmctlg_order_custom_post_status_completed' );
		// custom post status - processing
		$this->loader->add_action( 'init', $custompoststatuses, 'lmctlg_order_custom_post_status_processing' );
		// custom post status - pending
		$this->loader->add_action( 'init', $custompoststatuses, 'lmctlg_order_custom_post_status_pending' );
		// custom post status - failed
		$this->loader->add_action( 'init', $custompoststatuses, 'lmctlg_order_custom_post_status_failed' );
		// custom post status - cancelled
		$this->loader->add_action( 'init', $custompoststatuses, 'lmctlg_order_custom_post_status_cancelled' );
		// custom post status - refunded
		$this->loader->add_action( 'init', $custompoststatuses, 'lmctlg_order_custom_post_status_refunded' );
		// custom post status - on hold
		$this->loader->add_action( 'init', $custompoststatuses, 'lmctlg_order_custom_post_status_on_hold' );
		
		// process update downloads
		$this->loader->add_action( 'wp_ajax_lmctlg_update_download_data_form_process', $manage_downloads, 'lmctlg_update_download_data_form_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_update_download_data_form_process', $manage_downloads, 'lmctlg_update_download_data_form_process' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public   = new LMCTLG_Public( $this->get_plugin_name(), $this->get_version() );
		$rest_api        = new LMCTLG_Manage_Wp_Rest_Api( $this->get_plugin_name(), $this->get_version() );
		
		$template        = new LMCTLG_Template_System( $this->get_plugin_name(), $this->get_version() );
		
		$payment_buttons = new LMCTLG_Payment_Buttons( $this->get_plugin_name(), $this->get_version() );
		
		$lime_cart       = new LMCTLG_Cart( $this->get_plugin_name(), $this->get_version() );
		$lime_checkout   = new LMCTLG_Checkout( $this->get_plugin_name(), $this->get_version() );
		$lime_amount     = new LMCTLG_Amount( $this->get_plugin_name(), $this->get_version() );
		
		$login_register  = new LMCTLG_Login_Register( $this->get_plugin_name(), $this->get_version() );
		
		$contact         = new LMCTLG_Contact( $this->get_plugin_name(), $this->get_version() );
		
		$countries       = new LMCTLG_Countries( $this->get_plugin_name(), $this->get_version() );
		
		// shopping cart - process gateways
		$gateway_bacs    = new LMCTLG_Gateway_Bacs( $this->get_plugin_name(), $this->get_version() );
		
		// shopping cart - File Download API
		$file_download   = new LMCTLG_File_Download_Api( $this->get_plugin_name(), $this->get_version() );
		
		//START WITH THIS!!!! allow redirection, even if the theme starts to send output to the browser
		$this->loader->add_action( 'init', $plugin_public, 'lmctlg_do_output_buffer' );
		
		$this->loader->add_action( 'init', $rest_api, 'lmctlg_manage_wp_rest_api' ); // manage wp rest api
		
		// File Download API Endpoint
		$this->loader->add_action( 'init', $file_download, 'endpoint' );
		$this->loader->add_filter( 'query_vars', $file_download, 'query_vars_filter', 10, 1  );
		$this->loader->add_action( 'template_redirect', $file_download, 'api_listener' );
		
        // <<<<<<<< load template pages >>>>>>>>>>>>
		
		$this->loader->add_filter( 'archive_template', $template, 'lmctlg_load_archive_template' ); // use WP  -> archive_template
		$this->loader->add_filter( 'taxonomy_template', $template, 'lmctlg_load_taxonomy_template' ); // use WP  -> taxonomy_template
		$this->loader->add_filter( 'single_template', $template, 'lmctlg_load_single_template' ); // use WP  -> single_template
		$this->loader->add_filter( 'template_include', $template, 'lmctlg_template_chooser_search' ); // use WP  -> template_include
		
		// remove hardcoded width and height from thumbnail images
		$this->loader->add_filter( 'post_thumbnail_html', $plugin_public, 'lmctlg_remove_thumbnail_dimensions', 10, 3 ); // use WP  -> post_thumbnail_html
		
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'lmctlg_limecategory_exclude_children' );
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'lmctlg_limecategory_tax_query', 1 );
		
		// SHOPPING CART
		// ajax public, wp_ajax_nopriv_
		// ajax payment buttons process
		$this->loader->add_action( 'wp_ajax_lmctlg_payment_buttons_process', $payment_buttons, 'lmctlg_payment_buttons_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_payment_buttons_process', $payment_buttons, 'lmctlg_payment_buttons_process' );
		
		// Remove from cart form process
		$this->loader->add_action( 'wp_ajax_lmctlg_remove_from_cart_form_process', $lime_cart, 'lmctlg_remove_from_cart_form_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_remove_from_cart_form_process', $lime_cart, 'lmctlg_remove_from_cart_form_process' );
		// Update cart button
		$this->loader->add_action( 'wp_ajax_lmctlg_update_cart_process', $lime_cart, 'lmctlg_update_cart_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_update_cart_process', $lime_cart, 'lmctlg_update_cart_process' );
		// Items View: Normal, Large or List View
		$this->loader->add_action( 'wp_ajax_lmctlg_items_view_process', $plugin_public, 'lmctlg_items_view_process' ); // Items View: Normal, Large or List View
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_items_view_process', $plugin_public, 'lmctlg_items_view_process' );
		// login form
		$this->loader->add_action( 'wp_ajax_lmctlg_login_form_process', $login_register, 'lmctlg_login_form_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_login_form_process', $login_register, 'lmctlg_login_form_process' );
		// register form
		$this->loader->add_action( 'wp_ajax_lmctlg_register_form_process', $login_register, 'lmctlg_register_form_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_register_form_process', $login_register, 'lmctlg_register_form_process' );
		// forgot pw form
		$this->loader->add_action( 'wp_ajax_lmctlg_forgot_pw_form_process', $login_register, 'lmctlg_forgot_pw_form_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_forgot_pw_form_process', $login_register, 'lmctlg_forgot_pw_form_process' );
		// contact form
		$this->loader->add_action( 'wp_ajax_lmctlg_contact_form_process', $contact, 'lmctlg_contact_form_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_contact_form_process', $contact, 'lmctlg_contact_form_process' );
		// checkout form
		$this->loader->add_action( 'wp_ajax_lmctlg_checkout_form_process', $lime_checkout, 'lmctlg_checkout_form_process' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_checkout_form_process', $lime_checkout, 'lmctlg_checkout_form_process' );
		
		// shopping cart - process gateways
		// send order data for BACS gateway
		$this->loader->add_action( 'lmctlg_gateway_bacs', $gateway_bacs, 'lmctlg_order_data_for_gateway_bacs' ); // uses: lmctlg_gateway_$gateway
		$this->loader->add_action( 'wp_ajax_lmctlg_process_bacs_payment', $gateway_bacs, 'lmctlg_process_bacs_payment' );
		$this->loader->add_action( 'wp_ajax_nopriv_lmctlg_process_bacs_payment', $gateway_bacs, 'lmctlg_process_bacs_payment' );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 15 ); // ### Important! Load style after theme style (15)
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

?>
