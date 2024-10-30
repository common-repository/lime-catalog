<?php 

// get options
$lmctlg_general_options = get_option('lmctlg_general_options');

	// Here is how you would query the term slug
	if( is_tax() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		$slug = $term->slug;
		$termmain = $term->name;
	}

	// set up or arguments for our custom query
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	// only admin allowed
	if ( current_user_can( 'activate_plugins' ) ) {
		$query_args = array(
			'post_type'        => 'limecatalog',
			'taxonomy'         => 'limecategory',
			'post_status'      => array(        //(string / array) - use post status. Retrieves posts by Post Status, default value i'publish'.         
									'publish',  // - a published post or page.
									'private',  // - not visible to users who are not logged in.
									),
			'limecategory'     => $slug, // <- list items for category
			'paged'            => $paged, // <- for pagination
			'posts_per_page'   => $lmctlg_general_options['number_of_items_per_page'], // ### get option from database ### 
			//'posts_per_archive_page'   => '5',
			'order'            => $lmctlg_general_options['items_order'], // 'ASC'
			'orderby'          => $lmctlg_general_options['items_order_by'], // modified | title | name | ID | rand
		);
	} else {
		$query_args = array(
			'post_type'        => 'limecatalog',
			'taxonomy'         => 'limecategory',
			'post_status'      => 'publish',
			'limecategory'     => $slug, // <- list items for category
			'paged'            => $paged, // <- for pagination
			'posts_per_page'   => $lmctlg_general_options['number_of_items_per_page'], // ### get option from database ### 
			//'posts_per_archive_page'   => '5',
			'order'            => $lmctlg_general_options['items_order'], // 'ASC'
			'orderby'          => $lmctlg_general_options['items_order_by'], // modified | title | name | ID | rand
		);
	}

	/*
	echo '<pre>';
	print_r($query_args);
	echo '</pre>';
	*/

require_once LMCTLG_PLUGIN_DIR . 'public/pages/includes/lime-catalog-products.php';

?>