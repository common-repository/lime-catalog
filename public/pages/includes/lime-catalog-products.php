<?php 

 $custom_query = new WP_Query( apply_filters( 'lmctlg_products_list', $query_args ) ) ; // <- extensible

	/*
	echo '<pre>';
	print_r($custom_query);
	echo '</pre>';
	*/
	
 if( $custom_query->have_posts() ) {
/*		
	if ($termmain == '') {
		$termmain = __( 'All Items', 'lime-catalog' );
	} else {
		$termmain = $termmain;
	}
*/	

    do_action( 'lmctlg_produsts_list_before' ); // <- extensible

	// cookie name
	$items_view_cookie_name  = LMCTLG_Cookies::lmctlg_items_view_cookie_name();
		
	// check if cookie exist
	if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$items_view_cookie_name ) === true )
	{		
		// read the cookie
		$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$items_view_cookie_name, $default = '');
		// Items View: Normal, Large or List View
		$itemsview = $cookie;
	
	} else {
	    // default 
	    // $itemsview = 'lime-item-box-grid columns-3';
		// get option
		$itemsview = $lmctlg_general_options['default_items_view'];
		// switch
		$itemsview = LMCTLG_Admin::lmctlg_default_items_view_switch( $itemsview );
	}
	
	?>
<div class="lime-catalog-items">
  <ul id="set-lime-catalog-grid-view" class="<?php echo esc_attr( $itemsview ); ?>"> <!-- grid view: lime-item-box-grid columns-3 or list view: lime-item-box-list-view -->
	<?php 
	
    while ($custom_query->have_posts()) : $custom_query->the_post(); 
	
	$post_id = get_the_ID();
	
	// Meta Boxes, Retrieve an existing value from the database.
	$item_regular_price = get_post_meta( get_the_ID(), '_lmctlg_item_regular_price', true );
	$item_price = get_post_meta( get_the_ID(), '_lmctlg_item_price', true );
	$item_currency = get_post_meta( get_the_ID(), '_lmctlg_item_currency', true );
	$item_short_desc = get_post_meta( get_the_ID(), '_lmctlg_item_short_desc', true );
	
	// Set default values.
	if( empty( $item_regular_price ) ) $item_regular_price = '';
	if( empty( $item_price ) ) $item_price = '0';
	if( empty( $item_currency ) ) $item_currency = '';
	if( empty( $item_short_desc ) ) $item_short_desc = '';

	?>

    <li>
    
	<?php 
    $display_item_regular_price_span = ''; // default
	if ( $item_regular_price !== '0' && $item_regular_price !== '' ) {
		// regular price
		$item_regular_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$item_regular_price); // return span
		
		// display item price
		if ( $lmctlg_general_options['display_item_price'] == '1' ) {
			$display_item_regular_price_span = '<span class="lime-item-regular-price">' . $item_regular_price_public . '</span>';
		}
	}
	
	// item sale  price
    //$item_sale_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$item_price);
    
	// default
	$display_item_sale_price_public_span = '';
    // display item price only if it's enabled in settings
    if ( $lmctlg_general_options['display_item_price'] == '1' ) {
        //$display_item_sale_price_public = $item_sale_price_public;
		$display_item_sale_price_public = LMCTLG_Payment_Buttons::lmctlg_display_item_sale_price_public($post_id, $item_price, $display='first'); // return span
		
		//$price_label = __( 'From', 'lime-catalog' );
		$price_label = '';
		$display_item_sale_price_public_span = '<span class="lime-item-price">' . esc_attr( $price_label ) . ' ' . $display_item_sale_price_public . '</span>';
		
    } else {
        $display_item_sale_price_public = '';
    }
    
    // Display Short Description
    if ( $lmctlg_general_options['display_item_short_desc'] == '1' ) {
        $display_item_short_desc = $item_short_desc; // get meta box data
    } else {
        $display_item_short_desc = '';
    }
    
    // display item thumb image
    if ( $lmctlg_general_options['display_item_thumb_img'] == '1' ) {
    
     echo '<div class="thumb-container">';
     
        // check if has thumb
        if ( has_post_thumbnail() ) {
            // custom image size
			?>
			<a href="<?php echo esc_url( the_permalink() ); ?>" alt="<?php echo esc_attr( the_title_attribute() ); ?>">
			<?php the_post_thumbnail( 'lime-catalog-item-thumb' ); ?>
            </a>
			<?php
        } else {
            $src = plugins_url( '/lime-catalog/public/assets/images/no-image.jpg');
            $thumbimg = '<img src="' . esc_url( $src ) . '"/>';
			//echo '<a href="' . esc_url( the_permalink() ) . '" alt="' . esc_attr( the_title_attribute() ) . '">';
			?>
			<a href="<?php echo esc_url( the_permalink() ); ?>" alt="<?php echo esc_attr( the_title_attribute() ); ?>"><?php echo $thumbimg; ?></a>
			<?php
			
        }
        
      echo '</div>';

    }
    
    ?>
       
        
        <div class="item-title">
        <a href="<?php echo esc_url( the_permalink() ); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( '%s', 'lime-catalog' ), the_title_attribute() ) ); ?>">
        <?php 
		//echo LMCTLG_Public::lmctlg_shorten_title('...', 7); 
		echo esc_attr( the_title_attribute() );
		?>
        </a>
        </div>
        
        <div class="lime-item-price-holder">
        <?php echo $display_item_regular_price_span; ?>
		<?php echo $display_item_sale_price_public_span; ?> 
        </div>
        
        <div class="item-short-desc">
        <?php echo esc_attr( $display_item_short_desc ); ?>
        </div>
        
    </li>
 
<?php
    endwhile;
?>
  </ul>
</div><!--/ lime-catalog-items -->
<?php 
	
	do_action( 'lmctlg_produsts_list_after' ); // <- extensible
	
	// pagination
	require_once LMCTLG_PLUGIN_DIR . 'public/pages/includes/pagination.php';
	if (function_exists("lime_catalog_pagination")) {
	  echo '<div class="lime-pagination-holder">';
	  lime_catalog_pagination($custom_query->max_num_pages);
	  echo '</div>';
	}
	
	
 }

// clean up after the query and pagination
wp_reset_postdata(); 

?>