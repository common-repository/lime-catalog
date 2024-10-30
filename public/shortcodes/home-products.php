<?php 

// get options
$lmctlg_general_options = get_option('lmctlg_general_options');

  // set up or arguments for our custom query
  $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
  
  $query_args = array(
    'post_type'   => 'limecatalog',
	'post_status' => 'publish',
    'paged'       => $paged, // <- for pagination
	'order'       => $lmctlg_general_options['items_order'], // 'ASC'
	'orderby'     => $lmctlg_general_options['items_order_by'], // modified | title | name | ID | rand 
  );
  
	// only admin allowed
	if ( current_user_can( 'activate_plugins' ) ) {
		$query_args = array(
			'post_type'   => 'limecatalog',
			'post_status'      => array(        //(string / array) - use post status. Retrieves posts by Post Status, default value i'publish'.         
									'publish',  // - a published post or page.
									'private',  // - not visible to users who are not logged in.
									),
			'paged'       => $paged, // <- for pagination
			'order'       => $lmctlg_general_options['items_order'], // 'ASC'
			'orderby'     => $lmctlg_general_options['items_order_by'], // modified | title | name | ID | rand 
		);
	} else {
		$query_args = array(
			'post_type'   => 'limecatalog',
			'post_status' => 'publish',
			'paged'       => $paged, // <- for pagination
			'order'       => $lmctlg_general_options['items_order'], // 'ASC'
			'orderby'     => $lmctlg_general_options['items_order_by'], // modified | title | name | ID | rand 
		);
	}

  require_once LMCTLG_PLUGIN_DIR . 'public/pages/includes/lime-catalog-products.php';

?>