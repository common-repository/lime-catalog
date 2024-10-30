<div class="lime-single-item">			

<?php 

// get options
$lmctlg_cart_options = get_option('lmctlg_cart_options');

// the_content() should be inside the loop
if ( have_posts() ) : while ( have_posts() ) : the_post();

	$post_id = get_the_ID();
	$post_info = get_post( $post_id );
	// get options
	$lmctlg_general_options = get_option('lmctlg_general_options');
	
	$item_price = get_post_meta( $post_id, '_lmctlg_item_price', true );
    $item_sku   = get_post_meta( $post_id, '_lmctlg_item_sku', true );
	
	if( empty( $item_price ) ) $item_price = '';
	if( empty( $item_sku ) ) $item_sku = '';
	
	/*
	echo '<pre>';
	print_r($post_info);
	echo '</pre>';
	*/
	
	// default
	$display_item_sale_price_public_div = '';
    $display_item_sale_price_public = LMCTLG_Payment_Buttons::lmctlg_display_item_sale_price_public($post_id, $item_price, $display='first'); // return span (HTML)

    // display item price only if it's enabled in settings
    if ( $lmctlg_general_options['display_item_price'] == '1' ) {
		$enable_price_options   = get_post_meta( $post_id, '_lmctlg_enable_price_options', true );
		
		// if price options enabled
		if ( ! empty( $enable_price_options ) && $enable_price_options == '1' ) {
			$price_label = __( 'From', 'lime-catalog' );
		} else {
			$price_label = __( 'Price', 'lime-catalog' );
		}
			 
		$display_item_sale_price_public_div = '<div class="lime-item-price">' . esc_attr( $price_label ) . ' ' . $display_item_sale_price_public . '</div>';
	}
	
	//get_the_date();
 	?>
    
      <div class="lime-row">
        <div class="lime-col-7">
        
        <!-- jQuery message -->
        <div class="show-return-data"></div>
        
        <?php do_action( 'lmctlg_single_item_title_before' ); // <- extensible ?>
        	
            <!-- <div class="lime-title"></div> -->
            <h1><?php echo esc_attr( get_the_title( $post_id ) ); ?></h1>
            <?php echo $display_item_sale_price_public_div; ?>        
        </div><!--/ col -->
        
        <div class="lime-col-5">	
        
<!-- shopping cart - add to cart button  -->
<?php 
	// display Add to Cart Button for shopping cart
	if ( $lmctlg_cart_options['enable_shopping_cart'] == '1' ) {
		
?>
    <div class="lmctlg-text-align-payment-buttons">
        
    <?php 
	//  Add to Cart Shortcode
	// [ lmctlg_payment_button id="123" page="cart" label_one=" - Add To Cart" color_one="#ec7a5c" label_two=" + View Cart" color_two="#5F9530" ]
	echo do_shortcode('[lmctlg_payment_button id="' . esc_attr( $post_id ) . '" page="cart" label_one=" - Add To Cart" color_one="#ec7a5c" label_two=" + View Cart" color_two="#5F9530"]'); // payment button
	?>
        
    </div>
    
    <?php do_action( 'lmctlg_single_item_payment_button_after' ); // <- extensible ?>
		
<?php 
	}
?> 
           
        </div><!--/ col -->
      </div><!--/ row -->
    
    <hr class="lime-hr">
    
    <?php do_action( 'lmctlg_single_item_featured_img_before' ); // <- extensible ?>
    
    <div class="img-holder">
	<?php 
		// check if has thumb
		if ( has_post_thumbnail() ) {
			// ~~~~~~~~~ source: https://codex.wordpress.org/Post_Thumbnails 
			echo $thumbimg = the_post_thumbnail( array(600, 550) );
		} else {
			$src = plugins_url( '/lime-catalog/public/assets/images/no-image.jpg');
			//echo $thumbimg = '<img src="' . $src . '"/>';
		} 
	?>
    </div>
    
    <?php do_action( 'lmctlg_single_item_featured_img_after' ); // <- extensible ?>
    
    <?php do_action( 'lmctlg_single_item_description_before' ); // <- extensible ?>
    
    <div class="lime-content">
    <div class="lime-description"></div>
	<?php echo the_content(); ?>
    </div>
    
    <?php do_action( 'lmctlg_single_item_description_after' ); // <- extensible ?>
    
    <hr class="lime-hr">
    <?php if ( $item_sku !== '' ) { ?> 
      <div class="lime-row">
        <div class="lime-col-6">	
        <strong><?php _e( 'SKU:', 'lime-catalog' ); ?></strong> <?php esc_attr_e( $item_sku ); ?> 
        </div><!--/ col -->
      </div><!--/ row -->
    <?php } ?>  
    <br>
    
      <div class="lime-row">
        <div class="lime-col-6">
        &nbsp;	
           <?php 
			// if logged in edit link
	        edit_post_link( __( 'Edit', 'lime-catalog' ), '<span class="edit-post">', '</span>' );
		   ?>          
        </div><!--/ col -->
        
        <div class="lime-col-6 lime-back-button">	
        <a class="btn-lime btn-lime-sm btn-lime-grey lime-float-right" href="javascript: history.go(-1)"> <?php _e( 'Go Back', 'lime-catalog' ); ?> </a>
           
        </div><!--/ col -->
      </div><!--/ row -->

</div><!--/ lime-single-item -->

<?php 
endwhile;
endif;
?>