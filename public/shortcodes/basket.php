<?php 
// defaults
$basketCount = '0';

$limecatalogurl = home_url() . '/limecatalog/';

	// cookie name
	$cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();
	// check if cookie exist
	if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_items_cookie_name ) === true ) 
	{	
		// read the cookie
		$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_items_cookie_name, $default = '');
	
		$arr_cart_items = json_decode($cookie, true); // convert to array
		$obj_cart_items = json_decode($cookie); // convert to object
		
		// if cart has contents
		if(count($arr_cart_items)>0)
		{
			$basketCount = count($arr_cart_items); // count array
		}
  }
?>

<div class="lime-shopping-basket">
<a href="<?php echo esc_url( $limecatalogurl . '?page=cart' ); ?>"> 
<i class="glyphicon glyphicon-shopping-cart basket-icon"></i> &nbsp; 
</a>
<span class="basket-text">
<input type="hidden" class="input-lmctlg-arr-cart-items" name="lmctlg-arr-cart-items" value="<?php echo esc_attr( $basketCount ); ?>"/> <!-- for jQuery -->
<input type="hidden" class="input-lmctlg-basket-items" name="lmctlg-basket-items" value="<?php echo esc_attr( $basketCount ); ?>"/> <!-- for jQuery -->
<span class="lime-shopping-basket-items"><?php echo esc_attr( $basketCount ); ?></span>
 <?php _e( 'item(s) in my ', 'lime-catalog' ); ?>  
 <a class="lime-shopping-basket-link" href="<?php echo esc_url( $limecatalogurl . '?page=cart' ); ?>"> <?php _e( 'cart', 'lime-catalog' ); ?> </a>
</span>
</div>