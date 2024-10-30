<?php 

if ( isset($_GET['page'] ) ) {
	// sub pages ()shopping cart	
  if($_GET['page'] == "cart") { 
	// cart
	require_once LMCTLG_PLUGIN_DIR . 'public/shopping-cart/pages-front-end/cart.php';
  } elseif($_GET['page'] == "checkout") { 
	// checkout
	require_once LMCTLG_PLUGIN_DIR . 'public/shopping-cart/pages-front-end/checkout.php';
  } else {
	// home page
	require_once LMCTLG_PLUGIN_DIR . 'public/pages/lime-catalog-home.php';
  }
	
} else {
	// DEFAULT - home page
	require_once LMCTLG_PLUGIN_DIR . 'public/pages/lime-catalog-home.php';
}

?>