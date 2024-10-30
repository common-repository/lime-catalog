<div class="lime-boxes">

<div class="lime-padding-left-right-15">
<div class="lime-boxes-title font-size-16"><?php _e( 'Your order', 'lime-catalog' ); ?></div>

<!-- table-responsive start -->
<div class="cw-table-responsive">

<table id="cwtable">

<thead>
  <tr>
    <th class="lime-uppercase"><?php _e( 'Product', 'lime-catalog' ); ?></th>
    <th class="lime-uppercase"><?php _e( 'Item Total', 'lime-catalog' ); ?></th>
    </tr>
</thead>

<tbody>
<?php

  // defaults
  $total = '0';
  $subtotal = '0';
  $price_in_total = '0';
  
  // cookie name
  $cart_totals_cookie_name = LMCTLG_Cookies::lmctlg_cart_totals_cookie_name();
  
  // check if cookie exist
  if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_totals_cookie_name ) === true ) 
  {	
			
	// read the cookie
	$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_totals_cookie_name, $default = '');
	$cart_totals = $cookie;
	
	/*
	echo '<pre>';
	print_r( $cookie );
	echo '</pre>';
	*/
	
	$arr_cart_totals = json_decode($cart_totals, true); // convert to array
	$obj_cart_totals = json_decode($cart_totals); // convert to object
	
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
	print_r( $arr_cart_items );
	echo '</pre>';
	*/
	
	// if cart has contents
	if(count($obj_cart_items)>0)
	{	
	
	  foreach($obj_cart_items as $key=>$value)
	  {
			/*
			echo '<pre>';
			print_r( $value );
			echo '</pre>';
			*/
			
			//echo $value['item_name'] . '<br>';
			//echo $value->item_name . '<br>';
			
			if ($value->item_price !== '0') {
				
			  //$item_price        = LMCTLG_Amount::lmctlg_amount_hidden($amount=$value->item_price);

			}
			
			$display_price_option = ''; // default
			
			// notset defined in class-lmctlg-payment-buttons.php
			if ( $value->price_option_id !== '' ) {
			//if ( ! empty($value->price_option_id) ) {
				
				$price_option_id = $value->price_option_id;
				// get data
				$price_options = get_post_meta( $value->item_id, '_lmctlg_price_options', true ); // json
				if ( $price_options !== '' ) {
					$price_options = json_decode($price_options); // convert to object
					$price_option_name = $price_options->$price_option_id->option_name;
					
					$display_price_option = '<span style="display:block;">' . esc_attr( $price_option_name ) . '</span>';
				} else {
					$display_price_option = '';
				}
			}
			
?>
  <tr>
    <td><?php echo esc_attr( $value->item_name ); ?>  x <?php echo esc_attr( $value->item_quantity ); ?> <?php echo $display_price_option; // span ?></td>
    <td>
    <?php
	
	// calculate total for single item
	$single_item_total = $value->item_price * $value->item_quantity;
	
    $item_price_public_total = LMCTLG_Amount::lmctlg_amount_public($amount=$single_item_total); // return span (HTML)
    $item_price_total        = LMCTLG_Amount::lmctlg_amount_hidden($amount=$single_item_total); // return string
	
	echo $item_price_public_total;
	
	?>
    </td>
  </tr> 

<?php 
	  }
	}
  }
?>  
   
  <tr>
    <td colspan="2">
	<?php 
	
    $public_total = LMCTLG_Amount::lmctlg_amount_public($amount=$total); // return span (HTML)
    $hidden_total = LMCTLG_Amount::lmctlg_amount_hidden($amount=$total); // return string
	
	?>
    <input type="hidden" class="input-lmctlg-items-price-in-total" name="lmctlg-items-price-in-total" value="<?php echo esc_attr( $hidden_total ); ?>"/> <!-- for jQuery -->
	<span style="font-size:20px; font-weight: normal; color: rgba(0, 0, 0, 0.45);"><?php _e( 'Total', 'lime-catalog' ); ?> </span> 
	<span style="font-size:20px; font-weight: normal;"><?php echo $public_total; // span ?></span>
    </td>
  </tr>   
  
</tbody>

</table>


</div>
<!-- table-responsive end -->

</div><!--/ lime-padding-left-right-15 -->

</div><!--/ lime-boxes -->