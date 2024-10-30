
<?php 

$catalog_home_page = '0';

// get options
$lmctlg_general_options = get_option('lmctlg_general_options');

// get options
$lmctlg_cart_options = get_option('lmctlg_cart_options');
	
// if shopping cart option enabled
if ( isset($_GET['page'] ) && $lmctlg_cart_options['enable_shopping_cart'] == '1' ) {
	// sub pages ()shopping cart	
  if($_GET['page'] == "cart") { 
	$page_id = $lmctlg_cart_options['cart_page'];
	// check if value not 0 (0 value defined as default in class-cwctlg-activator.php)
	if ( $page_id !== '0' || ! empty($page_id) ) {
		// get page link by post id
		$page_link = get_permalink( $page_id );
		// if exist redirect to page
		wp_redirect( $page_link, 302 );
		exit();
	} else {
		//echo 'Postname ' . $post_name . ' NOT exist.';
		require_once LMCTLG_PLUGIN_DIR . 'public/shopping-cart/pages-front-end/cart.php';
	}
	
  } elseif($_GET['page'] == "checkout") { 
	$page_id = $lmctlg_cart_options['checkout_page'];
	// check if value not 0 (0 value defined as default in class-cwctlg-activator.php)
	if ( $page_id !== '0' || ! empty($page_id) ) {
		// get page link by post id
		$page_link = get_permalink( $page_id );
		// if exist redirect to page
		wp_redirect( $page_link, 302 );
		exit();
	} else {
		//echo 'Postname ' . $post_name . ' NOT exist.';
		require_once LMCTLG_PLUGIN_DIR . 'public/shopping-cart/pages-front-end/checkout.php';
	}
	
  } else {
	// home page
	$catalog_home_page = '1';
	//require_once LMCTLG_PLUGIN_DIR . 'public/pages/lime-catalog-home.php';
  }
	
} else {
	// DEFAULT - home page
	$catalog_home_page = '1';
	//require_once LMCTLG_PLUGIN_DIR . 'public/pages/lime-catalog-home.php';
}

if ( $catalog_home_page == '1' ) {

?>

<div class="lime-catalog-wrapper">	

<div class="lime-row">

<div class="lime-col-9">	

<div class="lime-row">
 <div class="lime-col-6">	
 <?php echo do_shortcode('[lmctlg_breadcrumbs]'); ?>
 </div><!--/ col -->

 <div class="lime-col-6">	
 <?php echo do_shortcode('[lmctlg_grid_or_list_view]'); ?>    
 </div><!--/ col --> 

</div><!--/ row -->

<?php

do_action( 'lmctlg_main_categories_before' ); // <- extensible

echo do_shortcode('[lmctlg_home_category_boxes]');

do_action( 'lmctlg_main_categories_after' ); // <- extensible

do_action( 'lmctlg_all_items_before' ); // <- extensible
  
echo do_shortcode('[lmctlg_home_products]');
	
?>

</div><!--/ col -->

<div class="lime-col-3">	

<div class="lime-sidebar">	

<?php 
echo do_shortcode('[lmctlg_sidebar_basket]');
echo do_shortcode('[lmctlg_sidebar_search]');
echo do_shortcode('[lmctlg_sidebar_nav]');
?>

</div><!--/ lime-sidebar -->

</div><!--/ col -->

</div><!--/ row -->

</div><!--/ lime-catalog-wrapper -->

<?php 
}
?>