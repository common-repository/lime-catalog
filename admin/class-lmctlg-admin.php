<?php

/**
 * Admin Main class.
 *
 * @package     lime_catalog
 * @subpackage  Admin
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class LMCTLG_Admin {

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
	 * @since      1.0.0
	 * @param      string    $plugin_name  The name of this plugin.
	 * @param      string    $version      The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * If multisite enabled add mimes.
	 *
	 * @since     1.0.0
	 * @param     array    $existing_mimes
     * @return    array    $existing_mimes
	 */
	public function lmctlg_add_mimes_multisite( $existing_mimes=array() ) {
		if ( is_multisite() ) {
			// add your extension to the mimes array as below
			$existing_mimes['zip']     = 'application/zip';
			$existing_mimes['gz|gzip'] = 'application/x-gzip';
			$existing_mimes['rar']     = 'application/rar';
		} 
		return $existing_mimes;
	}
	
	/**
	 * Create custom upload dir.
	 *
	 * @since      1.0.0
	 * @param      array    $pathdata
     * @return     array    $pathdata
	 */
	public function lmctlg_custom_prefix_upload_dir( $pathdata ) {
		global $current_user,$pagenow;
		$posttype = 'limecatalog';
		if ( ( 'async-upload.php' == $pagenow || 'media-upload.php' == $pagenow ) && false !== strpos( wp_get_referer(),'post_type=' . $posttype ) ) {
			$custom_dir = '/lime-catalog-uploads';
			if ( empty( $pathdata[ 'subdir' ] ) ) {
				$pathdata[ 'path' ] = $pathdata[ 'path' ] . $custom_dir;
				$pathdata[ 'url' ] = $pathdata[ 'url' ] . $custom_dir;
				$pathdata[ 'subdir' ] = '/lime-catalog-uploads-sub';
			} else {
				$new_subdir = $custom_dir . $pathdata[ 'subdir' ];

				$pathdata[ 'path' ] = str_replace( $pathdata[ 'subdir' ], $new_subdir, $pathdata[ 'path' ] );
				$pathdata[ 'url' ] = str_replace( $pathdata[ 'subdir' ], $new_subdir, $pathdata[ 'url' ] );
				$pathdata[ 'subdir' ] = str_replace( $pathdata[ 'subdir' ], $new_subdir, $pathdata[ 'subdir' ] );
			}
		}

		return $pathdata;
	}
	
	
	/**
	 * Handle upload.
	 *
	 * @since      1.0.0
	 * @param      array    $pathdata
	 */
	public function lmctlg_load_custom_upload_filter() {
		global $pagenow;
	    $posttype = 'limecatalog';
		if ( ! empty( $_REQUEST['post_id'] ) && ( 'async-upload.php' == $pagenow || 'media-upload.php' == $pagenow ) ) {
			if ( $posttype == get_post_type( $_REQUEST['post_id'] ) ) {
				add_filter( 'upload_dir', array($this, 'lmctlg_set_custom_upload_dir') );
			}
		}
	}
	
	/**
	 * custom upload folder for media uploads - e.g. custom post type featured images, donloadable products.
	 *
	 * @since      1.0.0
	 * @param      array    $upload
     * @return     array    $upload
	 */
	public function lmctlg_set_custom_upload_dir($upload) {
		$upload['subdir']   = '/lime-catalog-uploads' . $upload['subdir'];
		$upload['path'] = $upload['basedir'] . $upload['subdir'];
		$upload['url'] = $upload['baseurl'] . $upload['subdir'];
		return $upload;
	}

	/**
	 * Create custom image sizes.
	 *
	 * @since      1.0.0
     * @return     void
	 */
	public function lmctlg_custom_image_sizes() {
		
		// source: https://developer.wordpress.org/reference/functions/add_image_size/
		add_image_size( 'lime-catalog-item-thumb', 440, 330, true ); // 220 pixels wide by 180 pixels tall, hard crop mode
		add_image_size( 'lime-catalog-item-view', 640, 480, true ); // 220 pixels wide by 180 pixels tall, hard crop mode
		
		/* Additional Image Sizes by KB */
		add_image_size( 'lime-catalog-small-size-p', '300', '400', true ); /* portrait */
		add_image_size( 'lime-catalog-small-size-l', '400', '300', true ); /* landscape */
		
	}

	/**
	 * Show custom image sizes.
	 *
	 * @since      1.0.0
	 * @param      array    $sizes
     * @return     array    $sizes
	 */
	public function lmctlg_show_image_sizes($sizes) {
		$sizes['lime-catalog-item-thumb'] = __( 'Lime Catalog Item Thumb 440 x 330', 'lime-catalog' );
		$sizes['lime-catalog-item-view'] = __( 'Lime Catalog Item View 640 x 480', 'lime-catalog' );
		$sizes['lime-catalog-small-size-p'] = __( 'Lime Catalog Small Portrait', 'lime-catalog' );
		$sizes['lime-catalog-small-size-l'] = __( 'Lime Catalog Small Landscape', 'lime-catalog' );
		return $sizes;
	}

	/**
	 * Create admin submenu and pages.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_add_admin_menu() 
	{
		// get options
		$lmctlg_cart_options = get_option('lmctlg_cart_options');
		// if shopping cart enabled
		if ( $lmctlg_cart_options['enable_shopping_cart'] == '1' ) 
		{
			// Downloads
			add_submenu_page (  
							$parent_slug = 'edit.php?post_type=limecatalog', 
							$page_title  = __( 'Lime Catalog - Order Downloads', 'lime-catalog' ), 
							$menu_title  = __( 'Downloads', 'lime-catalog' ), 
							$capability  = 'manage_options', // manage_options for only admin, admin, editor, user etc.
							$menu_slug   = 'lime-order-downloads', 
							$function    = array( $this, 'lmctlg_order_downloads_page')
							);
		}
	    // Settings
		add_submenu_page (  
						$parent_slug = 'edit.php?post_type=limecatalog', 
						$page_title  = __( 'Lime Catalog - Settings', 'lime-catalog' ), 
						$menu_title  = __( 'Settings', 'lime-catalog' ), 
						$capability  = 'manage_options', // manage_options for only admin, admin, editor, user etc.
						$menu_slug   = 'lime-settings', 
						$function    = array( $this, 'lmctlg_settings_page')
						);
	}
	
	/**
	 * Admin order downloads page. List ordered downloads.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_order_downloads_page() 
	{
		$newDownloadsListTable = new LMCTLG_Downloads_List_Table();
		echo '<div class="wrap"><h2>' . __( 'Lime Catalog - Manage Downloads', 'lime-catalog' ) . '</h2>'; 
		
		$s_query = ''; // def
        //Fetch, prepare, sort, and filter our data...
        if( isset($_POST['s']) ){
		   $string = $_POST['s'];
           $newDownloadsListTable->search_items( $string );
        } else {
           $newDownloadsListTable->prepare_items();
        }
		
		// display the search box
		echo '<form method="post">';
		echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '" />';
		$newDownloadsListTable->search_box('search', 's');
		echo '</form>';
		
		$newDownloadsListTable->display(); // call display() to actually display the table
		echo '</div>'; 
	}
	
	/**
	 * Admin settings MAIN page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_settings_page() 
	{
		// get options
		$lmctlg_general_options = get_option('lmctlg_general_options');
		// get options
		$lmctlg_currency_options = get_option('lmctlg_currency_options');
		// get options
		$lmctlg_cart_options = get_option('lmctlg_cart_options');
		// get options
		$lmctlg_save_settings_options = get_option('lmctlg_save_settings_options');
		// get options
		$lmctlg_payment_gateway_options = get_option('lmctlg_payment_gateway_options');
		// get options
		$lmctlg_template_options = get_option('lmctlg_template_options');
		// get options
		$lmctlg_gateway_bacs_options = get_option('lmctlg_gateway_bacs_options');
		//echo 'Settigs page';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pages/settings-main-page.php';
	}
	
	/**
	 * Output admin forms validation messages.
	 *
	 * @since      1.0.0
	 * @param      array    $validation
	 * @param      string   $type
     * @return     void
	 */
    public static function adminFormsValidation($validation='', $type='success') 
	{
		$output = '';
		
	    if ( $validation != '') {
		
			if ($type == 'success') {
				$type = 'notice notice-success is-dismissible'; // css
			} elseif ($type == 'error') {
				$type = 'notice notice-error'; // css
			}
			
			// display validation error messages
			if( $validation != '' ) {
				//$output .= '<div class="cw-form-msgs">';
				foreach ($validation as $validate ) {
				  $output .= '<div class="' . esc_attr( $type ) . '" id="setting-error-" >';
				  $output .= '<p><strong>' . esc_attr( $validate ) . '</strong></p>'; 
				  $output .= '<button class="notice-dismiss" type="button"><span class="screen-reader-text">' . __( 'Dismiss this notice.', 'lime-catalog' ) . '</span></button>'; 
				  $output .= '</div>';
				}
				//$output .= '</div>';
			}
			return $output;	
		
		} else {
			return false;
		}
	}
	
	/**
	 * Settings general sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_general_options_form_process() 
	{
		
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-general-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-general-options-form-nonce'], 'lmctlg_general_options_form_nonce') )
	    {
			// Items Options
			$default_items_view    = sanitize_text_field( $_POST['default_items_view'] );
			
			// Checkbox - Price
			if( isset( $_POST['display_item_thumb_img'] ) ) {
				$display_item_thumb_img = '1';
			} else {
				$display_item_thumb_img = '0';
			}
			
			// Checkbox - Price
			if( isset( $_POST['display_item_price'] ) ) {
				$display_item_price = '1';
			} else {
				$display_item_price = '0';
			}
			
			// Checkbox - Display item short description on item listing page.
			if( isset( $_POST['display_item_short_desc'] ) ) {
				$display_item_short_desc = '1';
			} else {
				$display_item_short_desc = '0';
			}
			
			$number_of_items_per_page = sanitize_text_field( $_POST['number_of_items_per_page'] );
			$items_order_by           = sanitize_text_field( $_POST['items_order_by'] );	
			$items_order_radio        = sanitize_text_field( $_POST['items_order'] ); // radio
			if ( $items_order_radio == 'ASC' ) {
				$items_order = 'ASC';
			} else {
				$items_order = 'DESC';
			}

			// Category Options
			
			// Checkbox - // Display Main Categories on the catalog homepage and sub category boxes on the catalog pages
			if( isset( $_POST['display_category_boxes'] ) ) {
				$display_category_boxes = '1';
			} else {
				$display_category_boxes = '0';
			}
			
			$category_order_by    = sanitize_text_field( $_POST['category_order_by'] );
			// radio
			$category_order_radio = sanitize_text_field( $_POST['category_order'] );
			if ( $category_order_radio == 'ASC' ) {
				$category_order = 'ASC';
			} else {
				$category_order = 'DESC';
			}

			// Parent Menu Options
			$parent_menu_order_by = sanitize_text_field( $_POST['parent_menu_order_by'] );
			// radio
			$parent_menu_order_radio   = sanitize_text_field( $_POST['parent_menu_order'] );
			if ( $parent_menu_order_radio == 'ASC' ) {
				$parent_menu_order = 'ASC';
			} else {
				$parent_menu_order = 'DESC';
			}
			
			// Sub Menu Options
			$sub_menu_order_by = sanitize_text_field( $_POST['sub_menu_order_by'] );
			// radio
			$sub_menu_order_radio        = sanitize_text_field( $_POST['sub_menu_order'] );
			if ( $sub_menu_order_radio == 'ASC' ) {
				$sub_menu_order = 'ASC';
			} else {
				$sub_menu_order = 'DESC';
			}
			
			$arr = array(
				'enable_tangible_items' => '0', // important!!! leave this 0 until tangible items codes created
				'display_item_price' => $display_item_price,
				'default_items_view' => $default_items_view,
				'display_item_thumb_img' => $display_item_thumb_img,
				'display_item_short_desc' => $display_item_short_desc,
				'number_of_items_per_page' => $number_of_items_per_page,
				'items_order_by' => $items_order_by,
				'items_order' => $items_order, 
				'display_category_boxes' => $display_category_boxes,
				'category_order_by' => $category_order_by, 
				'category_order' => $category_order, 
				'parent_menu_order_by' => $parent_menu_order_by, 
				'parent_menu_order' => $parent_menu_order,
				'sub_menu_order_by' => $sub_menu_order_by, 
				'sub_menu_order' => $sub_menu_order,
				'save_options_settings' => '1'
			);

            update_option('lmctlg_general_options', $arr);  
			// success message
			$validation[] = __('General settings has been updated. ', 'lime-catalog');
			// validation
			echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');
	    }
	  }

    }
	
	/**
	 * Settings currency sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_currency_options_form_process() 
	{	
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-currency-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-currency-options-form-nonce'], 'lmctlg_currency_options_form_nonce') )
	    {
			// Currency
			$catalog_currency      = sanitize_text_field( $_POST['catalog_currency'] );
            
			// get currency name
			$catalog_currency_name = LMCTLG_Amount::lmctlg_get_currency_name( $catalog_currency );
			
			$currency_position     = sanitize_text_field( $_POST['currency_position'] );
			
			$thousand_separator    = sanitize_text_field( $_POST['thousand_separator'] );
			$decimal_separator     = sanitize_text_field( $_POST['decimal_separator'] );
			$number_of_decimals    = sanitize_text_field( $_POST['number_of_decimals'] );
			
			$arr = array(
				'catalog_currency' => $catalog_currency,
				'catalog_currency_name' => sanitize_text_field( $catalog_currency_name ),
				'currency_position' => $currency_position, // Left or Right
				'thousand_separator' => $thousand_separator,
				'decimal_separator' => $decimal_separator,
				'number_of_decimals' => $number_of_decimals
			);

            update_option('lmctlg_currency_options', $arr);
				  
			// success message
			$validation[] = __('Currency settings has been updated. ', 'lime-catalog');
			// validation
			echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error'); 
	    }
	  }

    }
	
	/**
	 * Settings cart sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_cart_options_form_process() 
	{
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-cart-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-cart-options-form-nonce'], 'lmctlg_cart_options_form_nonce') )
	    {
			
			// Checkbox - Shopping Cart
			if( isset( $_POST['enable_shopping_cart'] ) ) {
				$enable_shopping_cart = '1';
			} else {
				$enable_shopping_cart = '0';
			}
			
			$cart_page            = sanitize_text_field( $_POST['cart_page'] );
			$checkout_page        = sanitize_text_field( $_POST['checkout_page'] );
			$terms_page           = sanitize_text_field( $_POST['terms_page'] );
			$success_page         = sanitize_text_field( $_POST['success_page'] );
			$order_history_page   = sanitize_text_field( $_POST['order_history_page'] );
			$login_redirect_page  = sanitize_text_field( $_POST['login_redirect_page'] );
			
			$arr = array(
				'enable_shopping_cart' => $enable_shopping_cart,
				'cart_page'            => $cart_page,
				'terms_page'           => $terms_page,
				'checkout_page'        => $checkout_page,
				'success_page'         => $success_page,
				'order_history_page'   => $order_history_page,
				'login_redirect_page'  => $login_redirect_page
			);

            update_option('lmctlg_cart_options', $arr);
				  
			// success message
			$validation[] = __('Cart settings has been updated. ', 'lime-catalog');
			// validation
			echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');  
	    }
	  }

    }
	
	/**
	 * Settings save settings sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_save_settings_options_form_process() 
	{
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-save-settings-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-save-settings-options-form-nonce'], 'lmctlg_save_settings_options_form_nonce') )
	    {
			
			// Checkbox - Plugin Deactivation
			if( isset( $_POST['lmctlg_plugin_deactivation_save_settings'] ) ) {
				$plugin_deactivation = '1';
			} else {
				$plugin_deactivation = '0';
			}
			
			// Checkbox - Plugin Uninstall
			if( isset( $_POST['lmctlg_plugin_uninstall_save_settings'] ) ) {
				$plugin_uninstall = '1';
			} else {
				$plugin_uninstall = '0';
			}
			
			$arr = array(
				'lmctlg_plugin_deactivation_save_settings' => $plugin_deactivation,
				'lmctlg_plugin_uninstall_save_settings'    => $plugin_uninstall
			);

            update_option('lmctlg_save_settings_options', $arr);
				  
			// success message
			$validation[] = __('Settings has been updated. ', 'lime-catalog');
			// validation
			echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');  
	    }
	  }

    }

	/**
	 * Switch items view.
	 *
	 * @since      1.0.0
     * @return     string    $item_view
	 */
    public static function lmctlg_default_items_view_switch( $itemsview ) 
	{
		if ( !empty($itemsview) ) {
			$item_view = '';
			if ( $itemsview == 'Normal' ) {
				$item_view = 'lime-item-box-grid columns-3';
			} elseif ( $itemsview == 'Large' ) {
				$item_view = 'lime-item-box-grid columns-2';
			} elseif ( $itemsview == 'List' ) {
				$item_view = 'lime-item-box-list-view';
			} else {
			   // default	
			   $item_view = 'lime-item-box-grid columns-3';
			}
			return $item_view;
		}
	}
	
	/**
	 * Settings payment gateway sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_payment_gateway_settings_form_process() 
	{	
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-payment-gateway-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-payment-gateway-options-form-nonce'], 'lmctlg_payment_gateway_options_form_nonce') )
	    {
			
            $default_payment_gateway = sanitize_text_field( $_POST['lmctlg_default_payment_gateway'] );
			
			$arr = array(
				'default_payment_gateway' => $default_payment_gateway
			);
	
			update_option('lmctlg_payment_gateway_options', $arr);
				  
				  // success message
				  $validation[] = __('Payment Gateway settings has been updated. ', 'lime-catalog');
				  // validation
				  echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');
		  
	    }
	  }

    }
	
	/**
	 * Settings payment gateways bacs sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_payment_gateway_bacs_form_process() 
	{
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-bacs-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-bacs-options-form-nonce'], 'lmctlg_bacs_options_form_nonce') )
	    {
			// Checkbox
			if( isset( $_POST['lmctlg_bacs_enabled'] ) ) {
				$lmctlg_bacs_enabled = '1';
			} else {
				$lmctlg_bacs_enabled = '0';
			}
			
			// Checkbox - show billing details fields on checkout
			if( isset( $_POST['lmctlg_bacs_show_billing_details'] ) ) {
				$lmctlg_bacs_show_billing_details = '1';
			} else {
				$lmctlg_bacs_show_billing_details = '0';
			}
			
            $lmctlg_bacs_title                = sanitize_text_field( $_POST['lmctlg_bacs_title'] );
			$lmctlg_bacs_description          = wp_kses_post( $_POST['lmctlg_bacs_description'] );
			$lmctlg_bacs_notes                = wp_kses_post( $_POST['lmctlg_bacs_notes'] ); 
			$lmctlg_bacs_bank_account_details = wp_kses_post( $_POST['lmctlg_bacs_bank_account_details'] ); 
			
			$arr = array(
				'lmctlg_bacs_enabled'              => $lmctlg_bacs_enabled,
				'lmctlg_bacs_show_billing_details' => $lmctlg_bacs_show_billing_details,
				'lmctlg_bacs_title'                => $lmctlg_bacs_title,
				'lmctlg_bacs_description'          => $lmctlg_bacs_description,
				'lmctlg_bacs_notes'                => $lmctlg_bacs_notes,
				'lmctlg_bacs_bank_account_details' => $lmctlg_bacs_bank_account_details
			);
	
			update_option('lmctlg_gateway_bacs_options', $arr);
				  
				  // success message
				  $validation[] = __('BACS settings has been updated. ', 'lime-catalog');
				  // validation
				  echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');
		  
	    }
	  }

    }
	
	/**
	 * Settings template sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_template_options_form_process() 
	{
		
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-template-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-template-options-form-nonce'], 'lmctlg_template_options_form_nonce') )
	    {
			// MAKE IT SAFE
			// use addslashes before you save the option. Then when displaying the code use the stripslashes
			$inner_template_header = wp_kses_post($_POST['inner_template_header']); // "wp_kses_post" Sanitize content for allowed HTML tags for post content. 
			$inner_template_footer = wp_kses_post($_POST['inner_template_footer']); // "wp_kses_post" Sanitize content for allowed HTML tags for post content. 
			
			// PaymeButtons for Stripe options
			$arr = array(
				'inner_template_header' => $inner_template_header,
				'inner_template_footer' => $inner_template_footer,
			);

            update_option('lmctlg_template_options', $arr);
				  
				  // success message
				  $validation[] = __('Template settings has been updated. ', 'lime-catalog');
				  // validation
				  echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');
		  
	    }
	  }

    }

	/**
	 * Settings page tabs.
	 *
	 * @since      1.0.0
     * @return     array   $tabs
	 */
    public static function lmctlg_admin_settings_tabs() 
	{
		$tabs = array(
		'general-main'      => __( 'General', 'lime-catalog' ),
		'template-options'  => __( 'Template', 'lime-catalog' ),
		'gateway-main'      => __( 'Payment Gateways', 'lime-catalog' ),
		'emails-main'       => __( 'Emails', 'lime-catalog' ),
		);
		return apply_filters( 'lmctlg_admin_settings_tabs', $tabs ); // <- extensible
	}

	/**
	 * Settings general page sub tabs.
	 *
	 * @since      1.0.0
     * @return     array   $subs
	 */
    public static function lmctlg_admin_settings_general_subs() 
	{
		$subs = array(
		'general-catalog'  => __( 'Catalog', 'lime-catalog' ),
		'general-currency' => __( 'Currency', 'lime-catalog' ),
		'general-cart'     => __( 'Shopping Cart', 'lime-catalog' ),
		'general-settings' => __( 'Settings', 'lime-catalog' ),
		);
		return apply_filters( 'lmctlg_admin_settings_general_subs', $subs ); // <- extensible
	}

	/**
	 * Settings email page sub tabs.
	 *
	 * @since      1.0.0
     * @return     array   $subs
	 */
    public static function lmctlg_admin_settings_emails_subs() 
	{
		$subs = array(
		'email-settings'      => __( 'Email Settings', 'lime-catalog' ),
		'order-receipts'      => __( 'Order Receipts', 'lime-catalog' ),
		'order-notifications' => __( 'Order Notifications', 'lime-catalog' ),
		);
		return apply_filters( 'lmctlg_admin_settings_emails_subs', $subs ); // <- extensible
	}
	
	/**
	 * Settings emails page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_order_email_settings_options_form_process() 
	{
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-order-email-settings-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-order-email-settings-options-form-nonce'], 'lmctlg_order_email_settings_options_form_nonce') )
	    {
            $emails_logo      = sanitize_text_field( $_POST['lmctlg_emails_logo'] );
			
			$arr = array(
				'emails_logo' => $emails_logo
			);

            update_option('lmctlg_order_email_settings_options', $arr);
				  
				  // success message
				  $validation[] = __('Email settings has been updated. ', 'lime-catalog');
				  // validation
				  echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');
		  
	    }
	  }

    }
	
	/**
	 * Settings emails order receipts sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_order_receipts_options_form_process() 
	{
		
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-order-receipts-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-order-receipts-options-form-nonce'], 'lmctlg_order_receipts_options_form_nonce') )
	    {
            $from_name         = sanitize_text_field( $_POST['lmctlg_from_name'] );
			$from_email        = sanitize_text_field( $_POST['lmctlg_from_email'] );
			$subject           = sanitize_text_field( $_POST['lmctlg_subject'] );
			$email_content     = wp_kses_post( $_POST['lmctlg_email_content'] );
			
			// PaymeButtons for Stripe options
			$arr = array(
				'from_name'      => $from_name,
				'from_email'     => $from_email,
				'subject'        => $subject,
				'email_content'  => $email_content
			);

            update_option('lmctlg_order_receipts_options', $arr);
				  
				  // success message
				  $validation[] = __('Order receipt settings has been updated. ', 'lime-catalog');
				  // validation
				  echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');
		  
	    }
	  }

    }
	
	/**
	 * Settings emails order notifications sub page.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public function lmctlg_order_notifications_options_form_process() 
	{
		
	  // store validation results in array
	  $validation = array();
	  if ( isset($_POST['lmctlg-order-notifications-options-form-nonce']) ) {
	    // verify nonce
	    if ( wp_verify_nonce( $_POST['lmctlg-order-notifications-options-form-nonce'], 'lmctlg_order_notifications_options_form_nonce') )
	    {
            $notifications_enabled  = sanitize_text_field( $_POST['lmctlg_notifications_enabled'] );
			$subject                = sanitize_text_field( $_POST['lmctlg_subject'] );
			$email_content          = wp_kses_post( $_POST['lmctlg_email_content'] );
			$send_to                = sanitize_text_field( $_POST['lmctlg_send_to'] );
			
			// PaymeButtons for Stripe options
			$arr = array(
				'notifications_enabled' => $notifications_enabled,
				'subject'               => $subject,
				'email_content'         => $email_content,
				'send_to'               => $send_to 
			);

            update_option('lmctlg_order_notifications_options', $arr);
				  
				  // success message
				  $validation[] = __('Order notification settings has been updated. ', 'lime-catalog');
				  // validation
				  echo LMCTLG_Admin::adminFormsValidation($validation, $type='success');
		          
        } else {
		  
		  // error message
		  $validation[] = __('Failed to save data.', 'lime-catalog');
		  // validation
		  echo LMCTLG_Admin::adminFormsValidation($validation, $type='error');
		  
	    }
	  }

    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since      1.0.0
     * @return     void
	 */
	public function enqueue_styles() {
		// main style
		wp_enqueue_style( 'lmctlg-back-end', plugin_dir_url( __FILE__ ) . 'assets/css/lmctlg-back-end.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'lmctlg-table-responsive', plugin_dir_url( __FILE__ ) . 'assets/css/table-responsive.css', array(), $this->version, 'all' );
		
		//jQuery UI theme css file for date picker
		wp_enqueue_style('lmctlg-admin-ui-css','https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',array(),"1.9.0",false);
			
		// load only if we are at the right pages
		// check if get page
		if ( isset( $_GET["page"] ) ) {
			// allowed pages
			$allowedpagesArray = array(
							 'lime-settings'
							);
			foreach($allowedpagesArray as $key)
			{
				if ( $_GET["page"] == $key ) {
					//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/lmctlg-admin.css', array(), $this->version, 'all' );
				}
			}	
			global $post_type;
			if( 'limecatalog' == $post_type ) { // limecatalog; lime_shop_orders
				
			}
		
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since      1.0.0
     * @return     void
	 */
	public function enqueue_scripts() {
	    wp_enqueue_media();
	    // if true load at footer
		wp_enqueue_script( 'lmctlg-admin-js', plugin_dir_url( __FILE__ ) . 'assets/js/lmctlg-admin.js', array( 'jquery' ), $this->version, false );
		
		wp_localize_script( 'lmctlg-admin-js', 'lmctlg_admin_js', array( 
			'lmctlg_admin_wp_ajax_url' => admin_url( 'admin-ajax.php' )
		));
		
		//jQuery UI date picker file
		wp_enqueue_script('jquery-ui-datepicker');
	}

}

?>
