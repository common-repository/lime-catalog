<div class="lime-single-item">	

<div class="lime-shopping-cart-title"><?php _e( 'Shopping Cart', 'lime-catalog' ); ?></div>

        <!-- jQuery message 
        <div class="show-update-button-return-data"></div>-->

<?php 

// defaults
$total = '0';
$price_in_total = '0';

$limecatalogurl = home_url() . '/limecatalog/';

  // cookie name
  $cart_totals_cookie_name = LMCTLG_Cookies::lmctlg_cart_totals_cookie_name();
  
  // check if cookie exist
  if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_totals_cookie_name ) === true ) 
  {	
			
	// read the cookie
	$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_totals_cookie_name, $default = '');
	
	/*
	echo '<pre>';
	print_r( $cookie );
	echo '</pre>';
	*/
	
	$arr_cart_totals = json_decode($cookie, true); // convert to array
	$obj_cart_totals = json_decode($cookie); // convert to object
	
	/*
	echo '<pre>';
	print_r( $arr_cart_totals );
	echo '</pre>';
	*/
	
	// if cart has contents
	if(count($obj_cart_totals)>0)
	{
		$subtotal = $obj_cart_totals->subtotal;
		$total    = $obj_cart_totals->total;
	}
  }
	  
  // cookie name
  $cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();

  // check if cookie exist
  if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_items_cookie_name ) === true ) 
  {
	// read the cookie
	$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_items_cookie_name, $default = '');
	/*
	echo '<pre>';
	print_r( $cookie );
	echo '</pre>';
	*/
	$arr_cart_items = json_decode($cookie, true); // convert to array
	$obj_cart_items = json_decode($cookie); // convert to object
	
    /*
	echo '<pre>';
	print_r( $obj_cart_items );
	echo '</pre>';
	*/
	
	// if cart has contents
	if(count($obj_cart_items)>0)
	{	
	
	// get options
	$lmctlg_general_options = get_option('lmctlg_general_options');
	
	// get options
	$lmctlg_currency_options = get_option('lmctlg_currency_options');
	
	$thousand_separator     = sanitize_text_field( stripslashes( $lmctlg_currency_options['thousand_separator'] ) );
			
?>	

<input type="hidden" class="input-lmctlg-thousand-separator" name="lmctlg-thousand-separator" value="<?php echo esc_attr( $thousand_separator ); ?>"/>

<!-- table-responsive start -->
<div class="cw-table-responsive">

<table id="lmctlg-cart-table">

<thead>
  <tr>
    <th class="lime-uppercase" colspan="2"><?php _e( 'Product', 'lime-catalog' ); ?></th>
    <th class="lime-uppercase"><?php _e( 'Price', 'lime-catalog' ); ?></th>
    <th class="lime-uppercase"><?php _e( 'Quantity', 'lime-catalog' ); ?></th>
    <th class="lime-uppercase"><?php _e( 'Total', 'lime-catalog' ); ?></th>
    <th><?php _e( 'Refresh', 'lime-catalog' ); ?></th>
    <th><?php _e( 'Delete', 'lime-catalog' ); ?></th>
    </tr>
</thead>

<tbody>				
<?php 			
	  
	  // defaults
	  $display_price_option = '';
	  $price_option_id = '';	
		
	  foreach($obj_cart_items as $key=>$value)
	  {
			/*
			echo '<pre>';
			print_r( $value );
			echo '</pre>';
            */
			
			$item_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$value->item_price); // return span (HTML)
			$item_price        = LMCTLG_Amount::lmctlg_amount_hidden($amount=$value->item_price); // return string
			
			// Quantity Field
			$enable_quantity_field = get_post_meta( $value->item_id, '_lmctlg_enable_quantity_field', true ); 
			if( empty( $enable_quantity_field ) ) $enable_quantity_field = '0';
			
			// notset defined in class-lmctlg-payment-buttons.php
			// defaults
			$price_option_id = '';
			$display_price_option = ''; 
			//if ( $value->price_option_id != '' ) {
			if ( ! empty($value->price_option_id) ) {
				
				$price_option_id = $value->price_option_id;
				// get data
				$price_options = get_post_meta( $value->item_id, '_lmctlg_price_options', true ); // json
				
				if ( $price_options ) {
					$price_options = json_decode($price_options); // convert to object
					$price_option_name = $price_options->$price_option_id->option_name;
					
					$display_price_option = '<span style="display:block;">' . esc_attr( $price_option_name ) . '</span>';
				} else {
					$display_price_option = '';
				}
			}
			

?>	
  <tr class="lmctlg-cart-item">
    <td><div class="lime-cart-thumb-img">
	<?php 
	// source: https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/
	echo get_the_post_thumbnail( $value->item_id, 'thumbnail' ); // item id is the page ID 
	//echo get_the_post_thumbnail( $value->item_id, array( 200, 150) ); // Other resolutions
	?>
    </div></td>
    <td data-title="<?php _e( 'Product', 'lime-catalog' ); ?>">
    <input type="hidden" class="input-lmctlg-price-option-id" name="lmctlg-price-option-id" value="<?php echo esc_attr( $price_option_id ); ?>"/> <!-- for jQuery -->
    <input type="hidden" class="input-lmctlg-item-name" name="lmctlg-item-name" value="<?php echo esc_attr( $value->item_name ); ?>"/> <!-- for jQuery -->
    <input type="hidden" class="input-lmctlg-item-downloadable" name="lmctlg-item-downloadable" value="<?php echo esc_attr( $value->item_downloadable ); ?>"/> <!-- for jQuery -->
	<?php echo esc_attr( $value->item_name ); ?>
    <?php echo $display_price_option; // span ?>
    </td>
    <td data-title="<?php _e( 'Price', 'lime-catalog' ); ?>"> 
    <input type="hidden" class="input-lmctlg-item-id" name="lmctlg-item-id" value="<?php echo esc_attr( $value->item_id ); ?>"/> <!-- for jQuery -->
    <input type="hidden" class="input-lmctlg-item-price" name="lmctlg-item-price" value="<?php echo esc_attr( $item_price ); ?>"/> <!-- for jQuery -->
	<?php echo $item_price_public; // span ?>
    </td>
    <td data-title="<?php _e( 'Quantity', 'lime-catalog' ); ?>">
    <div class="lime-item-quantity">
     <?php if ( $enable_quantity_field == '1' ) { ?>
         <input class="input-lmctlg-item-quantity" type="number" max="" min="1" value="<?php echo esc_attr( $value->item_quantity ); ?>" name="lmctlg-item-quantity" >
     <?php } 
	       else { 
		   // if no quantity enabled set value to 1
		   ?>
           <input type="hidden" class="input-lmctlg-item-quantity" value="1" name="lmctlg-item-quantity" >
           <?php
		   echo esc_attr( $value->item_quantity ); 
		   } 
	 ?>
    </div>
    </td>
    <td data-title="<?php _e( 'Total', 'lime-catalog' ); ?>"> 
	<?php 
	// calculate total for single item
	$single_item_total = $item_price * $value->item_quantity;
	
    $item_price_public_total = LMCTLG_Amount::lmctlg_amount_public($amount=$single_item_total); // return span (HTML)
    $item_price_total        = LMCTLG_Amount::lmctlg_amount_hidden($amount=$single_item_total); // return string
	
	?>
    <input type="hidden" class="input-lmctlg-single-item-total" name="lmctlg-single-item-total" value="<?php echo esc_attr( $item_price_total ); ?>"/> <!-- for jQuery -->
    <div class="html-lmctlg-single-item-total"><?php echo $item_price_public_total; ?></div>
    </td>
    <td>
    <a class="lmctlg-update-cart btn-lime btn-lime-xs btn-lime-orange" id="lmctlg-update-cart" href="/" onclick="return false;"> <i class="glyphicon glyphicon-refresh"></i> </a>
    </td>	
    <td> 
    
    <form action="" method="post" id="<?php echo esc_attr( $value->item_id ); ?>" class="lmctlg-remove-from-cart-form" data-item-id="<?php echo esc_attr( $value->item_id ); ?>">
    
    <input type="hidden" name="lmctlg-remove-from-cart-form-nonce" value="<?php echo wp_create_nonce('lmctlg_remove_from_cart_form_nonce'); ?>"/>
    <input type="hidden" name="lmctlg_item_id" value="<?php echo esc_attr( $value->item_id ); ?>"/>
    
    <button class="remove-from-cart-button btn-lime btn-lime-xs btn-lime-orange" name="lmctlg-remove-from-cart-form-submit"  type="submit"> 
    <i class="glyphicon glyphicon-remove"></i>
    </button>
    
    </form>
    </td>
  </tr>				
<?php 
					
	  }
				
?>	
  <tr>
    <td colspan="7"> 
        <!-- jQuery message -->
        <div class="show-return-data"></div>
    </td>
    </tr>
  
</tbody>

</table>

</div>
<!-- table-responsive end -->				
<?php 	
				
    }
	  
  } 
  else 
  {
	//echo '<strong>No products found</strong> in your cart!';
	//echo 'Your cart is currently empty.';
	_e( 'Your cart is currently empty.', 'lime-catalog' );
	echo '<a class="btn-lime btn-lime-xs btn-lime-orange lime-float-right" href="' . esc_url( $limecatalogurl ) . '"> ' . __( '< Return to Shop', 'lime-catalog' ) . ' </a>';
  }


?>
</div><!--/ lime-single-item -->