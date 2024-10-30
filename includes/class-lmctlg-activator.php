<?php

/**
 * Fired during plugin activation
 *
 * @link       https://limecatalog.com
 * @since      1.0.0
 *
 * @package    Lime Catalog
 * @subpackage Lime Catalog/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Lime Catalog
 * @subpackage Lime Catalog/includes
 * @author     Attila Abraham
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 
 
class LMCTLG_Activator {
	
	/**
	 * Choose to save the site settings on plugin deactivation or uninstallation.
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_default_save_settings_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_save_settings_options') )
			return;
		
			$arr = array(
				'lmctlg_plugin_deactivation_save_settings' => '1', // should be 1
				'lmctlg_plugin_uninstall_save_settings'    => '0' // should be 0
			);
	
			update_option('lmctlg_save_settings_options', $arr);

	}

	/**
	 * Install order items db table.
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_order_items_table_install() {
		
		// sql to create your table
		// NOTICE that:
		// 1. each field MUST be in separate line
		// 2. There must be two spaces between PRIMARY KEY and its name
		//    Like this: PRIMARY KEY[space][space](id)
		// otherwise dbDelta will not work
		
		global $lmctlg_order_items_db_version;
		$lmctlg_order_items_db_version = '1.0.0';
	
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'lmctlg_order_items'; // do not forget about tables prefix 
		
		// check if table exist
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		
		    //table not in database. Create new table
			$charset_collate = $wpdb->get_charset_collate();
			$sql =
			"CREATE TABLE {$table_name} (
			order_item_id bigint(20) NOT NULL AUTO_INCREMENT,
			order_item_name longtext NOT NULL,
			order_item_type varchar(255) NOT NULL,
			price_option_id bigint(20) NOT NULL,
			price_option_name varchar(255) NOT NULL,
			order_id bigint(20) NOT NULL,
			PRIMARY KEY (order_item_id)
			) $charset_collate;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
			
			update_option( 'lmctlg_order_items_db_version', $lmctlg_order_items_db_version ); // save version in options
		
		}
	}

	/**
	 * Install order item meta db table.
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_order_itemmeta_table_install() {
		
		// sql to create your table
		// NOTICE that:
		// 1. each field MUST be in separate line
		// 2. There must be two spaces between PRIMARY KEY and its name
		//    Like this: PRIMARY KEY[space][space](id)
		// otherwise dbDelta will not work
		
		global $lmctlg_order_itemmeta_db_version;
		$lmctlg_order_itemmeta_db_version = '1.0.0';
	
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'lmctlg_order_itemmeta'; // do not forget about tables prefix 
		
		// check if table exist
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		
		    //table not in database. Create new table
			$charset_collate = $wpdb->get_charset_collate();
			$sql =
			"CREATE TABLE {$table_name} (
			meta_id bigint(20) NOT NULL AUTO_INCREMENT,
			order_item_id bigint(20) NOT NULL,
			meta_key varchar(255) NULL,
			meta_value longtext NULL,
			PRIMARY KEY (meta_id)
			) $charset_collate;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
			
			update_option( 'lmctlg_order_itemmeta_db_version', $lmctlg_order_itemmeta_db_version ); // save version in options
		
		}
	}

	/**
	 * Install order downloads db table.
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_order_downloads_table_install() {
		
		// sql to create your table
		// NOTICE that:
		// 1. each field MUST be in separate line
		// 2. There must be two spaces between PRIMARY KEY and its name
		//    Like this: PRIMARY KEY[space][space](id)
		// otherwise dbDelta will not work
		
		global $lmctlg_order_downloads_db_version;
		$lmctlg_order_downloads_db_version = '1.0.0';
	
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'lmctlg_order_downloads'; // do not forget about tables prefix 
		
		// check if table exist
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		
		    //table not in database. Create new table
			$charset_collate = $wpdb->get_charset_collate();
			$sql =
			"CREATE TABLE {$table_name} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			order_id bigint(20) NOT NULL,
			item_id bigint(20) NOT NULL,
			user_id bigint(20) NOT NULL,
			user_email varchar(255) NOT NULL,
			order_key varchar(355) NOT NULL,
			download_limit varchar(10) NOT NULL,
			order_date datetime NOT NULL,
			download_expiry_date date NOT NULL,
			download_count bigint(20) NOT NULL,
			PRIMARY KEY (id)
			) $charset_collate;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
			
			update_option( 'lmctlg_order_downloads_db_version', $lmctlg_order_downloads_db_version ); // save version in options
		
		}
	}

	/**
	 * General settings option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_default_general_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_general_options') )
			return;
	
			$arr = array(
				'enable_tangible_items' => '0', // important!!! leave this 0 until tangible items codes created
				'default_items_view' => 'Normal',
				'display_item_thumb_img' => '0',
				'display_item_price' => '0',
				'display_item_short_desc' => '0',
				'number_of_items_per_page' => '6',
				'items_order_by' => 'ID', // ID, date, title...
				'items_order' => 'DESC', // ASC, DESC
				'display_category_boxes' => '0', // Display Main Categories on the catalog homepage and sub category boxes on the catalog pages
				'category_order_by' => 'ID', // ID, date, title...
				'category_order' => 'ASC', // ASC, DESC
				'parent_menu_order_by' => 'ID', // ID, date, title...
				'parent_menu_order' => 'DESC', // ASC, DESC
				'sub_menu_order_by' => 'ID', // ID, date, title...
				'sub_menu_order' => 'ASC', // ASC, DESC
				'save_options_settings' => '1',
			);
	
			update_option('lmctlg_general_options', $arr);

	}

	/**
	 * General settings currency option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_default_currency_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_currency_options') )
			return;
	
			$arr = array(
				'catalog_currency' => 'usd',
				'catalog_currency_name' => 'United States Dollar',
				'currency_position' => 'Left',
				'thousand_separator' => ',',
				'decimal_separator' => '.',
				'number_of_decimals' => '2'
			);
	
			update_option('lmctlg_currency_options', $arr);

	}

	/**
	 * General settings cart option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_default_cart_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_cart_options') )
			return;
	
			$arr = array(
				'enable_shopping_cart' => '0',
				'cart_page'            => '0',
				'terms_page'           => '0',
				'checkout_page'        => '0',
				'success_page'         => '0',
				'order_history_page'   => '0',
				'login_redirect_page'  => '0',
			);
	
			update_option('lmctlg_cart_options', $arr);

	}

	/**
	 * Payment Gateway settings option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_payment_gateway_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_payment_gateway_options') )
			return;
			
			$default_payment_gateway = 'bacs';
			
			$arr = array(
				'default_payment_gateway' => $default_payment_gateway
			);
	
			update_option('lmctlg_payment_gateway_options', $arr);

	}
	
	/**
	 * Payment Gateway bacs option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_gateway_bacs_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_gateway_bacs_options') )
			return;
			
		$lmctlg_bacs_description = "Complete your payment directly into our bank account.";
			
		$lmctlg_bacs_notes = "Complete your payment directly into our bank account. Don't forget to include your Order ID as the payment reference. Your order will not begin processing until the funds have cleared in our account.";
		
			$n_br = "\n"; // \n or </br>
			$po   = ""; // <p>
			$pc   = ""; // </p>
			
			// the email template content
			$bank_account_details = $n_br;
			$bank_account_details .= $po . "<strong>Account Holder: </strong> Your Name " . $pc . $n_br;
			$bank_account_details .= $po . "<strong>Bank Name: </strong> Your Bank " . $pc . $n_br;
			$bank_account_details .= $po . "<strong>Bank Account Number: </strong> 0000 0000 0000 0000 " . $pc . $n_br;
			$bank_account_details .= $po . "<strong>Sort Code: </strong> 0000 0000 " . $pc . $n_br . $n_br;
			
			$bank_account_details .= $po . "<strong>International Bank Account Number (IBAN): </strong>  " . $pc . $n_br;
			$bank_account_details .= $po . "<strong>BIC / Swift: </strong>  " . $pc . $n_br;
			
			$arr = apply_filters( 'lmctlg_gateway_bacs_options_filters', array( // <- extensible
				'lmctlg_bacs_enabled' => '1',
				'lmctlg_bacs_show_billing_details' => '1',
				'lmctlg_bacs_title' => 'Direct Bank Transfer',
				'lmctlg_bacs_description' => $lmctlg_bacs_description,
				'lmctlg_bacs_notes' => $lmctlg_bacs_notes,
				'lmctlg_bacs_bank_account_details' => $bank_account_details
			) );
	
			update_option('lmctlg_gateway_bacs_options', $arr);

	}

	/**
	 * Template settings option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_default_template_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_template_options') )
			return;
			
			$inner_header = '<div class="container" style="margin-left: auto; margin-right: auto;">';
			$inner_footer = '</div>';
			
			$arr = array(
				'inner_template_header' => $inner_header,
				'inner_template_footer' => $inner_footer
			);
	
			update_option('lmctlg_template_options', $arr);

	}

	/**
	 * Email settings option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_email_settings_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_order_email_settings_options') )
			return;
			
			$arr = array(
				'emails_logo'      => ''
			);

            update_option('lmctlg_order_email_settings_options', $arr);

	}

	/**
	 * Email settings order receipt option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_order_receipts_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_order_receipts_options') )
			return;
			
			// defaults
			$displayname = '';
			$email       = '';
			$admin_email = '';
			
			// check if user logged in
			if ( is_user_logged_in() ) {
			  // if logged in get current user data
			  $current_user = wp_get_current_user();
			  
			  $username    = $current_user->user_login;
			  $email       = $current_user->user_email;
			  $first_name  = $current_user->user_firstname;
			  $last_name   = $current_user->user_lastname;
			  $displayname = $current_user->display_name;
			
			}
			
			// get site title
			if ( !empty( get_bloginfo('name') ) ) {
				$blog_title = get_bloginfo('name');
			} else {
				$blog_title = 'WordPress';
			}
			
			$admin_email = get_bloginfo('admin_email');
			
			$n_br = "\n"; // \n or </br>
			$po   = ""; // <p>
			$pc   = ""; // </p>
			
			// the email template content
			$email_content = $n_br;
			$email_content .= "<h3>Dear [user_first_name], thank you for your purchase!</h3> " . $n_br;
			
			$email_content .= $po . "<strong>Order Date:</strong> [order_date] " . $pc . $n_br;
			$email_content .= $po . "<strong>Order ID:</strong> #[order_id] " . $pc . $n_br;
			$email_content .= $po . "<strong>Transaction ID:</strong> [transaction_id] " . $pc . $n_br;
			$email_content .= $po . "<strong>Order Key:</strong> [order_key] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "<strong>Billing Details:</strong>" . $pc . $n_br . $n_br;
			$email_content .= $po . "[billing_addr_1] [billing_addr_2]" . $pc . $n_br;
			$email_content .= $po . "[billing_city]" . $pc . $n_br;
			$email_content .= $po . "[billing_state]" . $pc . $n_br;
			$email_content .= $po . "[billing_country]" . $pc . $n_br;
			$email_content .= $po . "[billing_zip]" . $pc . $n_br . $n_br;
			
			$email_content .= $po . "<strong>Items:</strong> " . $pc . $n_br . $n_br;
			$email_content .= "[items] " . $n_br;
			
			$email_content .= $po . "<strong>Order Total:</strong> [order_total] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "<strong>Order Status:</strong> [order_status] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "[payment_gateway_data] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "With Kind Regards, [from_name] " . $pc . $n_br;
			$email_content .= $po . "[current_site_url] " . $pc . $n_br;
			
			$subject = __( 'Order Receipt', 'lime-catalog' );
			
			$arr = array(
				'from_name'      => $blog_title,
				'from_email'     => $admin_email,
				'subject'        => $subject,
				'email_content'  => $email_content
			);
	
			update_option('lmctlg_order_receipts_options', $arr);

	}

	/**
	 * Email settings order notifications option
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_order_notifications_options() 
	{
		// check if option exist
		if ( get_option('lmctlg_order_notifications_options') )
			return;
			
			// defaults
			$displayname = '';
			$email       = '';
			$admin_email = '';
			
			// check if user logged in
			if ( is_user_logged_in() ) {
			  // if logged in get current user data
			  $current_user = wp_get_current_user();
			  
			  $username    = $current_user->user_login;
			  $email       = $current_user->user_email;
			  $first_name  = $current_user->user_firstname;
			  $last_name   = $current_user->user_lastname;
			  $displayname = $current_user->display_name;
			
			}
			
			$admin_email = get_bloginfo('admin_email');
			
			$n_br = "\n"; // \n or </br>
			$po   = ""; // <p>
			$pc   = ""; // </p>
			
			// the email template content
			$email_content = $n_br;
			$email_content .= "<h3>New Order Received!</h3> " . $n_br;
			
			$email_content .= $po . "<strong>First Name:</strong> [user_first_name] " . $pc . $n_br;
			$email_content .= $po . "<strong>Last Name:</strong> [user_last_name] " . $pc . $n_br;
			$email_content .= $po . "<strong>Email:</strong> [user_email] " . $pc . $n_br;
			$email_content .= $po . "<strong>Phone:</strong> [user_phone] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "<strong>Order Date:</strong> [order_date] " . $pc . $n_br;
			$email_content .= $po . "<strong>Order ID:</strong> #[order_id] " . $pc . $n_br;
			$email_content .= $po . "<strong>Transaction ID:</strong> [transaction_id] " . $pc . $n_br;
			$email_content .= $po . "<strong>Order Key:</strong> [order_key] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "<strong>Items:</strong> " . $pc . $n_br . $n_br;
			$email_content .= "[items] " . $n_br;
			
			$email_content .= $po . "<strong>Order Total:</strong> [order_total] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "<strong>Order Status:</strong> [order_status] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "<strong>Payment Gateway:</strong> [payment_gateway] " . $pc . $n_br . $n_br;
			
			$email_content .= $po . "Have a great day! " . $pc . $n_br;
			$email_content .= $po . "[current_site_url] " . $pc . $n_br;
			
			$subject = __( 'Order Notification', 'lime-catalog' );
			
			$arr = array(
				'notifications_enabled'    => '1',
				'send_to'                  => $admin_email, 
				'subject'                  => $subject,
				'email_content'            => $email_content
			);

            update_option('lmctlg_order_notifications_options', $arr);

	}

	/**
	 * Create custom folder for uploads and creates index and htaccess files
	 *
	 * @since    1.0.0
	 */
    public static function lmctlg_create_custom_upload_dir()
    {
		
		$upload_dir = wp_upload_dir(); // wp upload dir ARRAY
		//print_r( $upload_dir );
		$upload_dir_path = $upload_dir['path']; 
		// custom folder for uploads
		$customfolder = 'lime-catalog-uploads'; // <- check if folder exist, if not create
		// custom media folder dir path
		$uploadpath = $upload_dir_path . '/' . $customfolder;
		
		// create sub dir
		wp_mkdir_p( $uploadpath );
		
		if ( wp_mkdir_p( $uploadpath ) === TRUE )
		{
			//echo "Folder $customfolder successfully created";
			
			// create index.php
			if ( ! file_exists( $uploadpath . '/index.php' ) && wp_is_writable( $uploadpath ) ) {
				file_put_contents( $uploadpath . '/index.php', '<?php' . PHP_EOL . '// Silence is golden.' );
			}
			
			$htrules = LMCTLG_Activator::lmctlg_upload_dir_htaccess();
			// create .htaccess
			if ( ! file_exists( $uploadpath . '/.htaccess' ) && wp_is_writable( $uploadpath ) ) {
				file_put_contents( $uploadpath . '/.htaccess', $htrules );
			}
			
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Create htaccess file content
	 *
	 * @since    1.0.0
	 */
    public static function lmctlg_upload_dir_htaccess()
    {
			// Prevent directory browsing and direct access to all files, except images (they must be allowed for featured images / thumbnails)
			$allowed_filetypes = apply_filters( 'lmctlg_upload_dir_allowed_filetypes', array( 'jpg', 'jpeg', 'png', 'gif', 'mp3', 'ogg', 'zip', 'rar' ) );
			$htrules = "Options -Indexes\n";
			$htrules .= "deny from all\n";
			$htrules .= "<FilesMatch '\.(" . implode( '|', $allowed_filetypes ) . ")$'>\n";
			$htrules .= "Order Allow,Deny\n";
			$htrules .= "Allow from all\n";
			$htrules .= "</FilesMatch>\n";
			
			$htrules = apply_filters( 'lmctlg_upload_dir_htaccess_rules', $htrules );
			return $htrules;
			
	}

	/**
	 * Create custom roles
	 *
	 * @since    1.0.0
	 */
    public static function lmctlg_custom_roles()
    {
		 add_role('lime_subscriber',
					'Lime Subscriber',
					array(
						'read' => true,
						'read_item' => true,
						'read_order' => true,
						//'edit_posts' => false,
						//'delete_posts' => false,
						//'publish_posts' => false,
						//'upload_files' => false,
					)
		         );
		 
		 add_role('lime_customer',
					'Lime Customer',
					array(
						'read' => true,
						'read_item' => true,
						'read_order' => true,
						//'edit_posts' => false,
						//'delete_posts' => false,
						//'publish_posts' => false,
						//'upload_files' => false,
					)
		         );
		 
		 
	}
	
	/**
	 * This is how you would flush rewrite rules when a plugin is activated
	 *
	 * @since    1.0.0
	 */
	public static function lmctlg_flush_rewrite_rules() {
	   // check if custom post types exist
       //if ( post_type_exists('limecatalog') && post_type_exists('lime_shop_orders') ) {
	   if ( post_type_exists('limecatalog') ) {
		   flush_rewrite_rules( false ); // soft flush. Default is true (hard), update rewrite rules
	   }
	}

}

?>
