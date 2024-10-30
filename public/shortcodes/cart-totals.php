<div class="lime-boxes">

<?php 

  // defaults
  $total    = '0';
  $subtotal = '0';
  
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
?>

<div class="lime-padding-left-right-15">
<div class="lime-boxes-title font-size-16"><?php _e( 'Cart Totals', 'lime-catalog' ); ?></div>

<!-- table-responsive start -->
<div class="cw-table-responsive">

<table id="cwtable">

<tbody>

  <tr>
    <td class="lime-uppercase font-size-20"><?php _e( 'Total', 'lime-catalog' ); ?></td>
    <td class="font-size-20">
	<?php 
	
    $public_total = LMCTLG_Amount::lmctlg_amount_public($amount=$total); // return span
    $hidden_total = LMCTLG_Amount::lmctlg_amount_hidden($amount=$total); // return span
	
	?>
    <input type="hidden" class="input-lmctlg-items-price-in-total" name="lmctlg-items-price-in-total" value="<?php echo $hidden_total; ?>"/> <!-- for jQuery -->
    <div class="html-lmctlg-items-price-in-total"><?php echo $public_total; ?></div>
    </td>
  </tr>   
  
</tbody>

</table>

    <div class="lime-margin-top-15">
    <a class="lmctlg-update-cart btn-lime btn-lime-md btn-lime-silver lime-width-90" id="lmctlg-update-cart" href="<?php echo esc_url( '/' ); ?>" onclick="return false;"> <?php _e( 'Update Cart', 'lime-catalog' ); ?> </a>
    </div>
    
    <div>
    <a class="btn-lime btn-lime-md btn-lime-orange lime-width-90" id="lmctlg-proceed-to-checkout"  href="<?php echo esc_url( $limecatalogurl . '?page=checkout' ); ?>"> <?php _e( 'Proceed to Checkout', 'lime-catalog' ); ?> </a>
    </div>
    
    <div>
    <a class="btn-lime btn-lime-md btn-lime-silver lime-width-90" id="lmctlg-continue-shopping"  href="<?php echo esc_url( $limecatalogurl ); ?>"> <?php _e( 'Continue Shopping', 'lime-catalog' ); ?> </a>
    </div>


</div>
<!-- table-responsive end -->

</div><!--/ lime-padding-left-right-15 -->

</div><!--/ lime-boxes -->