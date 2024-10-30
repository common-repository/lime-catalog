
<?php 
// cookie name
$order_id_cookie_name    = LMCTLG_Cookies::lmctlg_order_id_cookie_name();

// show order receipt on the success page
// check if cookie exist
if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$order_id_cookie_name ) === true ) 
{		
// read the cookie
$order_id = LMCTLG_Cookies::lmctlg_get_cookie($name=$order_id_cookie_name, $default = '');

	// get postmeta order data
	$order_date            = get_post_meta( $order_id, '_order_date', true );
	$cus_user_id           = get_post_meta( $order_id, '_lmctlg_order_cus_user_id', true );
	$order_currency        = get_post_meta( $order_id, '_order_currency', true );
	$order_total           = get_post_meta( $order_id, '_order_total', true );
	$order_status          = get_post_meta( $order_id, '_order_status', true );
	$order_plugin_version  = get_post_meta( $order_id, '_order_plugin_version', true );
	$order_gateway         = get_post_meta( $order_id, '_order_gateway', true );
	$order_key             = get_post_meta( $order_id, '_order_key', true );
	$order_transaction_id  = get_post_meta( $order_id, '_order_transaction_id', true );
	
	// User data
	$user_customer      = get_user_by( 'id', $cus_user_id );
	$user_email         = $user_customer->user_email;
	$user_first_name    = $user_customer->first_name;
	$user_last_name     = $user_customer->last_name;

	// Billing Details
	$first_name         = get_post_meta( $order_id, '_first_name', true );
	$last_name          = get_post_meta( $order_id, '_last_name', true );
	$email              = get_post_meta( $order_id, '_email', true );
	$phone              = get_post_meta( $order_id, '_phone', true );
	$company            = get_post_meta( $order_id, '_company', true );
	
	$billing_addr_1     = get_post_meta( $order_id, '_billing_addr_1', true );
	$billing_addr_2     = get_post_meta( $order_id, '_billing_addr_2', true );
	$billing_country    = get_post_meta( $order_id, '_billing_country', true );
	$billing_state      = get_post_meta( $order_id, '_billing_state', true );
	$billing_city       = get_post_meta( $order_id, '_billing_city', true );
	$billing_zip        = get_post_meta( $order_id, '_billing_zip', true );
	
	// Set default values.
	if( empty( $cus_user_id ) ) $cus_user_id = '';
	if( empty( $first_name ) ) $first_name = '';
	if( empty( $last_name ) ) $last_name = '';
	if( empty( $email ) ) $email = '';
	if( empty( $phone ) ) $phone = '';
	if( empty( $company ) ) $company = '';
	if( empty( $billing_addr_1 ) ) $billing_addr_1 = '';
	if( empty( $billing_addr_2 ) ) $billing_addr_2 = '';
	if( empty( $billing_country ) ) $billing_country = '';
	if( empty( $billing_state ) ) $billing_state = '';
	if( empty( $billing_city ) ) $billing_city = '';
	if( empty( $billing_zip ) ) $billing_zip = '';
	
	if ( ! empty($billing_country) ) {
		// Countries
		$countries    = lmctlg_country_list(); // function
		$country_name = $countries[$billing_country]; // get country name
	} else {
		$country_name = '';
	}
	
	// get site title
	$blog_title = ''; // default
	if ( !empty( get_bloginfo('name') ) ) {
		$blog_title = get_bloginfo('name');
	}
	
?>
        
        <div class="lime-boxes">
        
        <div class="lime-boxes-title font-size-16"><?php echo esc_attr( $atts['title'] ); ?></div> <!-- Thank you for your purchase! -->
        
<!-- table-responsive start -->
<div class="cw-table-responsive">

<table id="lmctlg-order-items-table">

<thead>
  <tr>
    <th class="uppercase"><?php echo esc_attr( $blog_title ) . ' ' . __( 'Receipt', 'lime-catalog' ); ?></th>
    <th class="uppercase"></th>
    </tr>
</thead>

<tbody>	

  <tr class="lmctlg-order-items">
    <td>
	<?php 
		// order date
		if ( ! empty($order_date) ) {
			$order_date_f = LMCTLG_Helper::formatDateTime( $date=$order_date );
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Order Date: ', 'lime-catalog' ) . '</span> ' . esc_attr( $order_date_f ) . '</span>';
		}
		// order id
		if ( ! empty($order_id) ) {
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Order ID: ', 'lime-catalog' ) . '</span> ' . esc_attr( $order_id ) . '</span>';
		}
		// order key
		if ( ! empty($order_key) ) {
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Order Key: ', 'lime-catalog' ) . '</span> ' . esc_attr( $order_key ) . '</span>';
		}
	?>
    </td>
    <td>
	<?php 
		// Transaction ID
		if ( ! empty($order_transaction_id) ) {
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Transaction ID: ', 'lime-catalog' ) . '</span> ' . esc_attr( $order_transaction_id ) . '</span>';
		}
		// Order Gateway
		if ( ! empty($order_gateway) ) {
			$payment_gateways = LMCTLG_Payment_Gateways::lmctlg_payment_gateways();
			$payment_gateway_label = $payment_gateways[$order_gateway]['payment_gateway_label'];
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Payment Method: ', 'lime-catalog' ) . '</span> ' . esc_attr( $payment_gateway_label ) . '</span>';
		}
		// order status
		if ( ! empty($order_status) ) {
			$statuses = LMCTLG_Custom_Post_Statuses::lmctlg_order_custom_post_statuses();
			if ( ! empty( $statuses[$order_status] ) ) {
				$status = $statuses[$order_status];
				echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Order Status: ', 'lime-catalog' ) . '</span> ' . esc_attr( $status ) . '</span>';
			} else {
				echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Order Status: ', 'lime-catalog' ) . '</span> ' . '' . '</span>';
			}
		}
    ?>
    </td>
  </tr>	
  
</tbody>

</table>

</div>
<!-- table-responsive end -->	

<!-- table-responsive start -->
<div class="cw-table-responsive">

<table id="lmctlg-order-items-table">

<thead>
  <tr>
    <th class="uppercase"><?php _e( 'Customer', 'lime-catalog' ); ?></th>
    <th class="uppercase"><?php _e( 'Bill To', 'lime-catalog' ); ?></th>
    </tr>
</thead>

<tbody>	

  <tr class="lmctlg-order-items">
    <td>
<?php 

		// First Name
		if ( ! empty($first_name) ) {
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'First Name: ', 'lime-catalog' ) . '</span> ' . esc_attr( $first_name ) . '</span>';
		}
		
		// Last Name
		if ( ! empty($last_name) ) {
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Last Name: ', 'lime-catalog' ) . '</span> ' . esc_attr( $last_name ) . '</span>';
		}
		
		// Email
		if ( ! empty($email) ) {
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Email: ', 'lime-catalog' ) . '</span> ' . esc_attr( $email ) . '</span>';
		}
		
		// Phone
		if ( ! empty($phone) ) {
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Phone: ', 'lime-catalog' ) . '</span> ' . esc_attr( $phone ) . '</span>';
		}
		
		// Company
		if ( ! empty($company) ) {
			echo '<span class="lime-display-block"><span class="lime-strong">' . __( 'Company: ', 'lime-catalog' ) . '</span> ' . esc_attr( $company ) . '</span>';
		}

?>
    </td>
    <td>
<?php 

		// Addr 1
		if ( ! empty($billing_addr_1) ) {
			echo '<span class="lime-display-block lime-line-height-1-4-em">' . esc_attr( $billing_addr_1 ) . '</span>';
		}
		
		// Addr 2
		if ( ! empty($billing_addr_2) ) {
			echo '<span class="lime-display-block lime-line-height-1-4-em">' . esc_attr( $billing_addr_2 ) . '</span>';
		}
		
		// State
		if ( ! empty($billing_state) ) {
			echo '<span class="lime-display-block lime-line-height-1-4-em">' . esc_attr( $billing_state ) . '</span>';
		}

		// City
		if ( ! empty($billing_city) ) {
			echo '<span class="lime-display-block lime-line-height-1-4-em">' . esc_attr( $billing_city ) . '</span>';
		}

		// Country
		if ( ! empty($country_name) ) {
			echo '<span class="lime-display-block lime-line-height-1-4-em">' . esc_attr( $country_name ) . '</span>';
		}

		// Zip
		if ( ! empty($billing_zip) ) {
			echo '<span class="lime-display-block lime-line-height-1-4-em">' . esc_attr( $billing_zip ) . '</span>';
		}
		
?>
    </td>
  </tr>	
  
</tbody>

</table>

</div>
<!-- table-responsive end -->
        
        
<!-- table-responsive start -->
<div class="cw-table-responsive">

<table id="lmctlg-order-items-table">

<thead>
  <tr>
    <th class="uppercase"><?php _e( 'Product', 'lime-catalog' ); ?></th>
    <th class="uppercase"><?php _e( 'Price', 'lime-catalog' ); ?></th>
    <th class="uppercase"><?php _e( 'Quantity', 'lime-catalog' ); ?></th>
    <th class="uppercase"><?php _e( 'Total', 'lime-catalog' ); ?></th>
    </tr>
</thead>

<tbody>	


<?php
  
  // default
  $orderintotal = '0';
  $order_total = array(); // create empty array
  $order_currency_symbol = '';
  $price_option_name = '';

  if( ! empty($order_id) )
  {
	 
	$order_currency = get_post_meta( $order_id, '_order_currency', true );
	// get the currency symbol
	$order_currency_symbol = LMCTLG_Amount::lmctlg_get_currency_data_symbol( $currency=$order_currency );
	
	$ordered_items = LMCTLG_DB_Order_Items::lmctlg_select_order_items( $order_id );
	
	foreach($ordered_items as $item )
	{
		$order_item_id = $item['order_item_id'];
		$item_meta = LMCTLG_DB_Order_Items::lmctlg_select_order_item_meta( $order_item_id );
		
		foreach($item_meta as $key => $value )
		{
			// item ID 
			if ( $value['meta_key'] == '_item_id' ) {
				$item_id = $value['meta_value'];
			}
			// item price
			if ( $value['meta_key'] == '_item_price' ) {
				$item_price = $value['meta_value'];
			}
			// item quantity
			if ( $value['meta_key'] == '_item_quantity' ) {
				$item_quantity = $value['meta_value'];
			}
			// item total

			if ( $value['meta_key'] == '_item_total' ) {
				$item_total = $value['meta_value'];
			}
			
		}
		// save in array
		$order_total[] = $item_price * $item_quantity;
		
		$order_item_name   = $item['order_item_name'];
		$order_item_type   = $item['order_item_type'];
		$price_option_id   = $item['price_option_id'];
		$price_option_name = $item['price_option_name'];
		
		$price_option_name_div = ''; // default
		if ( ! empty ($price_option_name) && $price_option_name !== 'null' )  {
			$price_option_name_div = '<div class="html-lmctlg-price-option-name">' . esc_attr(  $price_option_name ) . '</div>';
		}

?>
  <tr class="lmctlg-order-items">
    <td data-title="<?php _e( 'Product', 'lime-catalog' ); ?>">
	<?php 
	
	$download_data = ''; // default
	$site_home_url = home_url();
	// send download file(s) data only if order status = completed
	if ( $order_status == 'completed' ) {
	
		if ( $order_item_type == 'downloadable' ) {
			// get item data, postmeta
			$item_downloadable  = get_post_meta( $item_id, '_lmctlg_item_downloadable', true ); // the checkbox data 
			// check again if file is downloadable
			if ( $item_downloadable == '1' ) {
				
				// get file data, downloadable items
				$item_file_name        = get_post_meta( $item_id, '_lmctlg_item_file_name', true );
				$item_file_url         = get_post_meta( $item_id, '_lmctlg_item_file_url', true );
				//$item_download_limit   = get_post_meta( $item_id, '_lmctlg_item_download_limit', true );
				//$item_download_expiry  = get_post_meta( $item_id, '_lmctlg_item_download_expiry', true );
				
				// GET DOWNLOAD
				$download = LMCTLG_DB_Order_Downloads::lmctlg_select_single_download( $order_id, $item_id );
				
				/*
				echo '<pre>';
				print_r($download);
				echo '</pre>';
				*/
				
				if ( !empty( $download ) ) {
				  $download_limit       = $download[0]['download_limit'];
				  $download_order_date  = $download[0]['order_date'];
				  $download_expiry_date = $download[0]['download_expiry_date'];
				  $download_count       = $download[0]['download_count'];
				}
				
				/*
				// download expiry date
				$current_date = date('Y-m-d H:i:s');
				// order data : order date
				$order_date = get_post_meta( $order_id, '_order_date', true );
				// order date + item download expiry 
				$add_days = strtotime(date("Y-m-d H:i:s", strtotime($order_date)) . "+" . $item_download_expiry . " days");
				$download_expiry_date = date('Y-m-d H:i:s', $add_days);
				*/
				
				if ( $download_limit == '' || $download_limit == '0' ) {
					// unlimited downloads
					$download_limit = __( 'Unlimited', 'lime-catalog' );
				} else {
					$download_limit = $download_limit;
				}
				
				if ( $download_expiry_date == '0000-00-00' ) {
					$download_expiry_date = __( 'Never Expires', 'lime-catalog' );
				} else {
					$download_expiry_date = LMCTLG_Helper::formatDate( $date=$download_expiry_date );
				}
				
				// downloadable products create download url
				$secret_data = array(
					'post_id'    => intval( $item_id ), // db posts, item id is the post id !!!
					'order_id'   => intval( $order_id ), // db posts
					'order_key'  => sanitize_text_field( $order_key )
				);
				
				// convert array to json
				$secret_data_json = json_encode( $secret_data );
				$secret_data_json_enc = LMCTLG_Helper::lmctlg_base64url_encode($data=$secret_data_json);	
				
				$file_link = '<a href="' . esc_url( $site_home_url . '/lmctlg-file-dw-api/?action=download&dwfile=' . $secret_data_json_enc ) . '">' . esc_attr( $item_file_name ) . '</a>';
				
				$file_download_urls[] = $file_link; // save in array for later usage
				
				// downloadable file link
				$download_data = "";
				$download_data .= '<span style="display:block;">' . __( 'Download File: ', 'lime-catalog' ) . $file_link . '</span>';
				$download_data .= '<span style="display:block;">' . __( 'Download Limit: ', 'lime-catalog' ) . esc_attr( $download_limit ) . '</span>';
				$download_data .= '<span style="display:block;">' . __( 'Download Expiry Date: ', 'lime-catalog' ) . esc_attr( $download_expiry_date ) . '</span>';
				
				echo '<span class="lime-strong">' . esc_attr( $order_item_name ) . '</span>';
				echo $price_option_name_div;
				echo $download_data;
				
			} else {
		    echo '<span class="lime-strong">' . esc_attr( $order_item_name ) . '</span>'; 
			echo $price_option_name_div;
	        }
			
		} else {
		    echo '<span class="lime-strong">' . esc_attr( $order_item_name ) . '</span>';
			echo $price_option_name_div;
	    }
	
	} else {
		echo '<span class="lime-strong">' . esc_attr( $order_item_name ) . '</span>';
		echo $price_option_name_div;
	}
	
	?>
    </td>
    <td data-title="<?php _e( 'Price', 'lime-catalog' ); ?>"> 
    <div class="html-lmctlg-single-item-price">
    <?php 
	$item_price = LMCTLG_Amount::lmctlg_format_amount($amount=$item_price); // return string
	echo $itemPrice = LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$item_price, $order_currency_symbol); // return span (HTML)
	?>
    </div>
    </td>
    <td data-title="<?php _e( 'Quantity', 'lime-catalog' ); ?>">
    <div class="lime-item-quantity">
     <?php echo esc_attr( $item_quantity ); ?>
    </div>
    </td>
    <td data-title="<?php _e( 'Total', 'lime-catalog' ); ?>"> 
    <div class="html-lmctlg-single-item-total">
	<?php 
	$item_total = LMCTLG_Amount::lmctlg_format_amount($amount=$item_total);  // return string
	echo $itemTotal = LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$item_total, $order_currency_symbol); // return span (HTML)
	?>
    </div>
    </td>
  </tr>				

<?php 
	
	}

  }
    
	// order total
	foreach($order_total as $total )
	{	
		//echo $total . ' <br>';	
		$orderintotal = $orderintotal + $total;
	}
	$orderintotal = LMCTLG_Amount::lmctlg_format_amount($amount=$orderintotal);
	$orderintotal_hidden = LMCTLG_Amount::lmctlg_format_amount_to_string($orderintotal);
	
?>

  <tr>
    <td colspan="5">
    <div class="lime-float-right lime-margin-left-right-25"> 
    <div class="html-lmctlg-order-total order-total lime-margin-top-bottom-5 font-size-14"><?php _e( 'Order Total:', 'lime-catalog' ); ?> 
    <span>
	<?php 
	echo LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$orderintotal, $order_currency_symbol); // return span (HTML)
	?>
    </span>
    </div>
    </div>
    </td>
  </tr>
 
</tbody>

</table>

</div>
<!-- table-responsive end -->	

		
        </div><!--/ lime-boxes -->	
        
<?php 
} // end if cookie
?>