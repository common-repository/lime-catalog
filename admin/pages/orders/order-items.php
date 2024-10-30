
<?php 

  // get options
  $lmctlg_general_options = get_option('lmctlg_general_options');
  
  // get options
  $lmctlg_currency_options = get_option('lmctlg_currency_options');
  
  $catalog_currency       = $lmctlg_currency_options['catalog_currency']; // default currency
  $currency_data_symbol   = LMCTLG_Amount::lmctlg_get_currency_data_symbol( $currency=$catalog_currency );
  $currency_position      = $lmctlg_currency_options['currency_position']; // position : left or right
  $thousand_separator     = stripslashes( $lmctlg_currency_options['thousand_separator'] );
  
  $order_status      = get_post_meta( get_the_ID(), '_order_status', true );
  if( empty( $order_status ) ) $order_status = '';
?>

<input type="hidden" class="input-lmctlg-curr-data-symbol" name="lmctlg-curr-data-symbol" value="<?php echo esc_attr( $currency_data_symbol ); ?>"/>
<input type="hidden" class="input-lmctlg-curr-position" name="lmctlg-curr-position" value="<?php echo esc_attr( $currency_position ); ?>"/>
<input type="hidden" class="input-lmctlg-thousand-separator" name="lmctlg-thousand-separator" value="<?php echo esc_attr( $thousand_separator ); ?>"/>

<div class="padding-left-right-15">

<!-- table-responsive start -->
<div class="cw-table-responsive cw-admin-forms"> 

<table id="cwtable">

<tbody>  
  
  <tr>
    <td>
    <p><?php _e("Select Item", 'lime-catalog'); ?></p>
    <select class="selectfield lmctlg_order_select_item" name="order_select_item" id="order_select_item">
        <option selected="selected" value=""></option>
		<?php 
		// defaults
		$enable_price_options = '';
		$item_price           = '';
		$price_options        = '';
		
        $args=array(
          'post_type' => 'limecatalog',
			'post_status'      => array(        //(string / array) - use post status. Retrieves posts by Post Status, default value i'publish'.         
									'publish',  // - a published post or page.
									'private',  // - not visible to users who are not logged in.
									),
          'posts_per_page' => -1
        );
        $query = null;
        $query = new WP_Query($args);
        if( $query->have_posts() ) {
          while ($query->have_posts()) : $query->the_post();
		  
		  $post_id = get_the_ID();
		  
		  // CHECK IF PRICE OPTIONS ENABLED
		  $enable_price_options  = get_post_meta( $post_id, '_lmctlg_enable_price_options', true );
		  
		  // CHECK IF SOFTWARE LICENSING ENABLED
		  //$enable_software_licensing = get_post_meta( $post_id, '_enable_software_licensing', true );
		  //if( empty( $enable_software_licensing ) ) $enable_software_licensing = '';
		  
		  if( $enable_price_options == '1' ) {
			  $item_price = '0';
			  $price_options         = get_post_meta( $post_id, '_lmctlg_price_options', true ); // json
			  $price_options         = json_decode($price_options, true); // convert into array  
			  
			  
		  } else {
			  // get post meta _lmctlg_item_price
			  $item_price = get_post_meta( $post_id, '_lmctlg_item_price', true );
		  }
		  
		  // get post meta _lmctlg_item_downloadable
		  $item_downloadable = get_post_meta( $post_id, '_lmctlg_item_downloadable', true );
		  
		  if ( $item_downloadable == '1' ) {
			  $item_downloadable = 'downloadable';
		  } else {
			  // not downloadable
			 $item_downloadable = 'tangible'; 
		  }
		  
        ?>  
         <option data-enable-price-options="<?php echo esc_attr( $enable_price_options ); ?>" data-item-downloadable="<?php echo esc_attr( $item_downloadable ); ?>" data-curr-symbol="<?php echo esc_attr( $currency_data_symbol ); ?>" data-curr-position="<?php echo esc_attr( $currency_position ); ?>" data-item-name="<?php esc_attr(  the_title() ); ?>" data-item-price="<?php echo esc_attr( $item_price ); ?>"  value="<?php echo esc_attr( $post_id ); ?>"><?php esc_attr( the_title() ); ?></option>
        <?php  
          endwhile;
        }
        //wp_reset_query();  // Restore global post data stomped by the_post().
        // clean up after the query and pagination
        wp_reset_postdata(); 
        ?>
        
    </select>
    
<?php 

		// defaults
		$enable_price_options = '';
		$item_price           = '';
		$price_options        = '';
		
        $args=array(
          'post_type' => 'limecatalog',
			'post_status'      => array(        //(string / array) - use post status. Retrieves posts by Post Status, default value i'publish'.         
									'publish',  // - a published post or page.
									'private',  // - not visible to users who are not logged in.
									),
          'posts_per_page' => -1
        );
        $query = null;
        $query = new WP_Query($args);
        if( $query->have_posts() ) {
          while ($query->have_posts()) : $query->the_post();
		  
		  $post_id = get_the_ID();
		  
		  // CHECK IF PRICE OPTIONS ENABLED
		  $enable_price_options  = get_post_meta( $post_id, '_lmctlg_enable_price_options', true );
		  
		  if( $enable_price_options == '1' ) {
			  
			  $item_price = '0';
			  $price_options         = get_post_meta( $post_id, '_lmctlg_price_options', true ); // json
			  $price_options         = json_decode($price_options, true); // convert into array  
			  
		  
		  
			// PRICE OPTIONS
			if ( ! empty ($price_options) && $price_options !== 'null' )  {
			
?>
    
        <div id="display_price_option-<?php echo esc_attr( $post_id ); ?>" class="lmctlg_display_price_options_select_field">
        <select name="lmctlg_price_option_selector" id="lmctlg_price_option_selector" class="lmctlg_price_option_selector_class">
        <?php 

		
		    //$price_options = json_decode($price_options, true);// convert back to array
			echo '<option selected="selected" value="">' . __( 'Select Price Option', 'lime-catalog' ) . '</option>';
			foreach( $price_options as $key ) {
				
			 echo '<option data-price-option-id="' . esc_attr__( $key['option_id'] ) . '" data-price-option-name="' . esc_attr__( $key['option_name'] ) . '" value="' . esc_attr__( $key['option_price'] ) . '">' . esc_attr__( $key['option_name'] ) . ' (' . esc_attr( $currency_data_symbol ) .  esc_attr__( $key['option_price'] ) . ') </option>';
			 
			}
		 
        ?>
        </select>
        </div>

        <?php  
		   }
		}
          endwhile;
        }
        //wp_reset_query();  // Restore global post data stomped by the_post().
        // clean up after the query and pagination
        wp_reset_postdata(); 
        ?>       
        
    
    </td>
    
    <td>
    
    <?php 
	if ( $order_status == 'completed' ) {
	?>
    <p><?php _e( 'If order status is "Completed" items are no longer editable.', 'lime-catalog' ); ?></p>
    <?php 
	} else {
	?>
    <a class="lmctlg-add-new-item button" href="/" onclick="return false;"><?php _e( 'Add Item(s)', 'lime-catalog' ); ?></a>
    <?php 
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
    <th class="uppercase"><?php _e( 'Product Name', 'lime-catalog' ); ?></th>
    <th class="uppercase"><?php _e( 'Price', 'lime-catalog' ); ?></th>
    <th class="uppercase"><?php _e( 'Quantity', 'lime-catalog' ); ?></th>
    <th class="uppercase"><?php _e( 'Total', 'lime-catalog' ); ?></th>
    <th></th>
    </tr>
</thead>

<tbody>	


<?php
  
  // default
  $order_id = '';
  $orderintotal = '0';
  $order_total = array(); // create empty array
  $order_currency_symbol = '';
  $price_option_name = '';

  if(isset($_GET['post']))
  {
	$order_id = $_GET['post'];
	 
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
			$price_option_name_div = '<div class="html-lmctlg-price-option-name">' . esc_attr( $price_option_name ) . '</div>';
		}

?>
  <tr class="lmctlg-order-items">
    <!-- for jQuery -->
    <input type="hidden" class="input-lmctlg-item-id" name="lmctlg_item_id[]" value="<?php echo esc_attr( $item_id ); ?>"/>
    <input type="hidden" class="input-lmctlg-item-name" name="lmctlg_item_name[]" value="<?php echo esc_attr( $order_item_name ); ?>"/>
    <input type="hidden" class="input-lmctlg-item-price" name="lmctlg_item_price[]" value="<?php echo esc_attr( $item_price ); ?>"/>
    <input type="hidden" class="input-lmctlg-single-item-total" name="lmctlg_single_item_total[]" value="<?php echo esc_attr( $item_total ); ?>"/>
    <input type="hidden" class="input-lmctlg-item-downloadable" name="lmctlg_item_downloadable[]" value="<?php echo esc_attr( $order_item_type ); ?>"/>
    <input type="hidden" class="input-lmctlg-price-option-id" name="lmctlg_price_option_id[]" value="<?php echo esc_attr( $price_option_id ); ?>"/>
    <input type="hidden" class="input-lmctlg-price-option-name" name="lmctlg_price_option_name[]" value="<?php echo esc_attr( $price_option_name ); ?>"/>
    <td data-title="<?php _e( 'Product', 'lime-catalog' ); ?>">
	<?php 
	echo esc_attr( $order_item_name ); 
	echo $price_option_name_div;
	?>
    </td>
    <td data-title="<?php _e( 'Price', 'lime-catalog' ); ?>"> 
    <div class="html-lmctlg-single-item-price">
    <?php 
	$item_price = LMCTLG_Amount::lmctlg_format_amount($amount=$item_price);
	echo $itemPrice = LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$item_price, $order_currency_symbol); // return span
	?>
    </div>
    </td>
    <td data-title="<?php _e( 'Quantity', 'lime-catalog' ); ?>">
    <div class="lime-item-quantity">
     <input class="input-lmctlg-item-quantity" type="number" max="" min="1" value="<?php echo esc_attr( $item_quantity ); ?>" name="lmctlg_item_quantity[]" >
    </div>
    </td>
    <td data-title="<?php _e( 'Total', 'lime-catalog' ); ?>"> 
    <div class="html-lmctlg-single-item-total">
	<?php 
	$item_total = LMCTLG_Amount::lmctlg_format_amount($amount=$item_total);
	echo $itemTotal = LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$item_total, $order_currency_symbol); // return span
	?>
    </div>
    </td>
    <td> 
    
    <?php 
	if ( $order_status !== 'completed' ) {
	?>
  <a id="<?php echo esc_attr( $item_id ); ?>" data-item-id="<?php echo esc_attr( $item_id ); ?>" class="lmctlg-remove-item" href="/" onclick="return false;"><?php _e( 'remove', 'lime-catalog' ); ?></a>
    <?php 
	} 
	?>
   
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
    <div class="float-right margin-left-right-25"> 
    <input type="hidden" class="input-lmctlg-order-total" name="lmctlg-order-total" value="<?php echo esc_attr( $orderintotal_hidden ); ?>"/> <!-- for jQuery -->
    <div class="html-lmctlg-order-total order-total margin-top-bottom-10"><?php _e( 'Order Total:', 'lime-catalog' ); ?> 
    <span>
	<?php 
	echo LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$orderintotal, $order_currency_symbol); // return span
	?>
    </span>
    </div>
    <?php 
	if ( $order_status !== 'completed' ) {
	?>
    <a id="lmctlg-update-order-total" class="lmctlg-update-order-total button" href="/" onclick="return false;"><?php _e( 'Update Totals', 'lime-catalog' ); ?></a>
    <?php 
	} 
	?>
    </div>
    </td>
  </tr>
 
</tbody>

</table>

</div>
<!-- table-responsive end -->	




</div><!--/ padding-left-right-15 -->

