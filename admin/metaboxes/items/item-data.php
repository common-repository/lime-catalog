
<?php

    // show fields only if shopping cart enabled on the settings page
    if ( $lmctlg_cart_options['enable_shopping_cart'] == '1' ) 
    {  
        $display_enable_quantity_field = '';
		$display_enable_price_options_field = '';
	} else {
		$display_enable_quantity_field = 'style="display:none;"';
		$display_enable_price_options_field = 'style="display:none;"';
	}

    if ( $lmctlg_cart_options['enable_shopping_cart'] == '1' ) 
    { 
		// PRICE OPTIONS
		// if price options enabled
		if ( $enable_price_options == '1' ) {
			$display_sale_price = 'style="display:none;"';
			$display_price_options_table = '';
		} else {
			$display_sale_price = '';
			$display_price_options_table = 'style="display:none;"';
		}
	} else {
		$display_sale_price = '';
		$display_price_options_table = 'style="display:none;"';
	}
?>

<table class="form-table"> 

    <tr>
    <th><label for="item_regular_price" class="item_regular_price_label"><?php  _e( 'Regular Price', 'lime-catalog' ); ?></label></th>
    <td>
    <?php echo esc_attr( $currencysymbol ); ?>	<input type="text" id="item_regular_price" name="item_regular_price" class="item_regular_price_field" placeholder="<?php echo  esc_attr__( '0.00', 'lime-catalog' ); ?>" value="<?php echo  esc_attr__( $item_regular_price ); ?>">
    <p class="description"><?php  _e( 'Enter Item regular price or leave blank.', 'lime-catalog' ); ?></p>
    </td>
    </tr>

    <tr id="display_sale_price" <?php echo $display_sale_price; ?>>
    <th><label for="item_price" class="item_price_label"><?php  _e( 'Sale Price', 'lime-catalog' ); ?></label></th>
    <td>
    <?php echo esc_attr( $currencysymbol ); ?>	<input type="text" id="item_price" name="item_price" class="item_price_field" placeholder="<?php echo esc_attr__( '0.00', 'lime-catalog' ); ?>" value="<?php echo esc_attr__( $item_price ); ?>">
    <p class="description"><?php  _e( 'You can change the default currency on the settings page.', 'lime-catalog' ); ?></p>
    </td>
    </tr>
    <tr id="display_enable_quantity_field" <?php echo $display_enable_quantity_field; ?> >
    <th><label for="enable_quantity_field" class="enable_quantity_field_label"><?php  _e( 'Quantity', 'lime-catalog' ); ?></label></th>
    <td>
<?php if( $enable_quantity_field == true ) { $quantity_field_checked = 'checked="checked"'; } else { $quantity_field_checked = ''; } ?>
  <input type="checkbox" id="enable_quantity_field" name="enable_quantity_field" class="lmctlg_enable_quantity_field_checkbox" value="1"<?php echo $quantity_field_checked; ?>>
    <p class="description"> <?php  _e( 'Display the Quantity field next to the payment button. ', 'lime-catalog' ); ?></p>
    </td>
    </tr>
    
    <tr <?php echo $display_enable_price_options_field; ?>>
    <th><label for="enable_price_options" class="enable_price_options_label"><?php  _e( 'Price Options', 'lime-catalog' ); ?></label></th>
    <td>
<?php if( $enable_price_options == true ) { $price_options_checked = 'checked="checked"'; } else { $price_options_checked = ''; } ?>
  <input type="checkbox" id="enable_price_options" name="enable_price_options" class="lmctlg_enable_price_options_checkbox" value="1"<?php echo $price_options_checked; ?>>
    <p class="description"> <?php  _e( 'Enable price options. ', 'lime-catalog' ); ?></p>
    </td>
    </tr>
    
</table>

<!-- table-responsive start -->
<div class="cw-table-responsive" id="lmctlg-price-options-table" <?php echo $display_price_options_table; ?>>

<table class="lmctlg-price-options-table" >
<!-- for jQuery -->
<input type="hidden" class="input-lmctlg-currency-symbol" name="lmctlg_currency_symbol" value="<?php echo esc_attr( $currencysymbol ); ?>"/>
<thead>
  <tr>
    <th id="th-price-option-id"><?php _e( 'ID', 'lime-catalog' ); ?></th>
    <th id="th-price-option-name"><?php _e( 'Option Name', 'lime-catalog' ); ?></th>
    <th id="th-price-option-price"><?php _e( 'Sale Price', 'lime-catalog' ); ?></th>
    <th id="th-price-option-default"><?php _e( 'Default', 'lime-catalog' ); ?></th>
    <th id="th-price-option-actions"></th>
    </tr>
</thead>

<tbody>	

<?php 

if ( ! empty ($price_options) && $price_options !== 'null' )  {

$price_options = json_decode($price_options, true);// convert back to array

// get last key of the array, this will be the counter number
end($price_options);
$lastKey = key($price_options);

$count = count( $price_options );

/*
print '<pre>';
print_r( $price_options );
print '</pre>';
*/

?>
<input type="hidden" class="input-lmctlg-counter" name="lmctlg_counter" value="<?php echo esc_attr__( $lastKey ); ?>"/>
<?php 

foreach( $price_options as $key ) {

$price = $key['option_price'];
$price = LMCTLG_Amount::lmctlg_format_amount($amount=$price);

?>
  
  <tr class="lmctlg-price-option-row">
  <input type="hidden" class="input-lmctlg-row-id" name="input_lmctlg_row_id[]" value="<?php echo esc_attr__( $key['option_id'] ); ?>"/>
    <td> 
    <span class="lmctlg_price_option_id" ><?php echo esc_attr__( $key['option_id'] ); ?></span>
    </td>   
    <td> 
    <input type="text" name="lmctlg_price_option_name[]" class="lmctlg_price_option_name_input" placeholder="Option Name" value="<?php echo  esc_attr__( $key['option_name'] ); ?>">
    </td>
    <td>
	<?php echo esc_attr__( $currencysymbol ); ?> <input type="text" name="lmctlg_price_option_price[]" class="lmctlg_price_option_price_input" placeholder="0.00" value="<?php echo  esc_attr__( $price ); ?>">
    </td>
    <td> 
    <?php if( $key['option_id'] == $price_default_option ) { $checked = 'checked="checked"'; } else { $checked = ''; } ?>
    <input type="radio" value="<?php echo esc_attr__( $key['option_id'] ); ?>" <?php echo $checked; ?> name="lmctlg_price_default" class="lmctlg_price_default_radio">
    </td>   
    <td> 
    <a class="lmctlg-remove-price-option" href="/" onclick="return false;"><?php _e( 'remove', 'lime-catalog' ); ?></a>
    </td>   
  </tr>	
  
<?php 
}

} else {
	
?>

 <input type="hidden" class="input-lmctlg-counter" name="lmctlg_counter" value="1"/>

  <tr id="1" class="lmctlg-price-option-row">
  <input type="hidden" class="input-lmctlg-row-id" name="input_lmctlg_row_id[]" value="1"/>
    <td> 
    <span class="lmctlg_price_option_id" >1</span>
    </td> 
    <td> 
    <input type="text" name="lmctlg_price_option_name[]" class="lmctlg_price_option_name_input" placeholder="Option Name" value="">
    </td>
    <td>
	<?php echo esc_attr__( $currencysymbol ); ?> <input type="text" name="lmctlg_price_option_price[]" class="lmctlg_price_option_price_input" placeholder="0.00" value="">
    </td>
    <td> 
    <input type="radio" value="1" name="lmctlg_price_default" class="lmctlg_price_default_radio">
    </td>   
    <td> 
    <a class="lmctlg-remove-price-option" href="/" onclick="return false;"><?php _e( 'remove', 'lime-catalog' ); ?></a>
    </td>   
  </tr>	
<?php 
}
?>
  
  <!-- hidden row for jQuery -->
  <tr class="lmctlg-price-insert-new-row">
  <input type="hidden" class="input-lmctlg-row-id" name="input_lmctlg_row_id[]" value="1"/>
    <td> 
    <span class="lmctlg_price_option_id" ></span>
    </td> 
    <td> 
    <input type="text" name="lmctlg_price_option_name[]" class="lmctlg_price_option_name_input" placeholder="Option Name" value="">
    </td>
    <td>
	<?php echo esc_attr__( $currencysymbol ); ?> <input type="text" name="lmctlg_price_option_price[]" class="lmctlg_price_option_price_input" placeholder="0.00" value="">
    </td>
    <td> 
    <input type="radio" value="1" name="lmctlg_price_default" class="lmctlg_price_default_radio">
    </td>   
    <td> 
    <a class="lmctlg-remove-price-option" href="/" onclick="return false;"><?php _e( 'remove', 'lime-catalog' ); ?></a>
    </td>   
  </tr>	

</tbody>

</table>

<p style="float: none; clear:both;" class="submit">
  <a style="margin: 12px 0 4px;" class="button lmctlg-add-price-option">Add Price Option</a>
</p>

</div>
<!-- table-responsive end -->	

<table class="form-table">    

    <tr>
    <th><label for="item_sku" class="item_sku_label"><?php  _e( 'SKU', 'lime-catalog' ); ?></label></th>
    <td>
  <input type="text" id="item_sku" name="item_sku" class="item_sku_field" value="<?php echo esc_attr__( $item_sku ); ?>">
    <p class="description"> <?php  _e( 'Stock Keeping Unit.', 'lime-catalog' ); ?></p>
    </td>
    </tr>
	<?php
    // DOWNLOADABLE PRODUCTS
    // show fields only if shopping cart enabled on the settings page
    if ( $lmctlg_cart_options['enable_shopping_cart'] == '1' ) 
    {
	?>
    <tr>
    <th><label for="item_downloadable" class="item_downloadable_label"><?php  _e( 'Downloadable Product', 'lime-catalog' ); ?></label></th>
    <td>
  <?php if( $item_downloadable == '1' ) { $downloadable = 'checked="checked"'; } else { $downloadable = ''; } ?>
  <input type="checkbox" id="item_downloadable" name="item_downloadable" class="item_downloadable_checkbox" value="1" <?php echo $downloadable; ?>>
    <p class="description"> <?php  _e( 'Enable downloadable product.', 'lime-catalog' ); ?></p>
    </td>
    </tr>
	<?php
    }
    ?>
</table>
	<?php
    // DOWNLOADABLE PRODUCTS
    // show fields only if shopping cart enabled on the settings page
    if ( $lmctlg_cart_options['enable_shopping_cart'] == '1' ) 
    {  
        // show if it is a downloadable product
        if ( $item_downloadable == '1' ) 
        {	
           $display_downloadable_table = '';
        } else {
           $display_downloadable_table = 'style="display:none;"';
        }
	} else {
           $display_downloadable_table = 'style="display:none;"';
        }
	// Table - Downloadable Items
    ?>
    <table class="form-table" id="show_downloadable_table" <?php echo $display_downloadable_table; ?> >
    
        <tr>
        <th><label for="item_file_name" class="item_file_name_label"><?php  _e( 'Downloadable File', 'lime-catalog' ); ?></label></th>
        <td>
      <input type="text" id="item_file_name" name="item_file_name" class="item_file_name_field" value="<?php echo esc_attr__( $item_file_name ); ?>" placeholder="file name">
      &nbsp;
      <input style="min-width: 60%;" type="text" id="lmctlg_item_file_url" name="item_file_url" class="item_file_url_field" value="<?php echo esc_attr__( $item_file_url ); ?>" placeholder="file url">
      <input type="button" value="Select File" class="button" id="lmctlg_upload_file_button">
        <p class="description"> <?php  _e( 'Downloadable file name and url.', 'lime-catalog' ); ?></p>
        </td>
        </tr>
    
        <tr>
        <th><label for="item_download_limit" class="item_download_limit_label"><?php  _e( 'Download Limit', 'lime-catalog' ); ?></label></th>
        <td>
      <input type="number" id="item_download_limit" name="item_download_limit" class="item_download_limit_field" value="<?php echo esc_attr__( $item_download_limit ); ?>">
        <p class="description"> <?php  _e( 'Leave blank for unlimited re-downloads.', 'lime-catalog' ); ?></p>
        </td>
        </tr>
    
        <tr>
        <th><label for="item_download_expiry" class="item_download_expiry_label"><?php  _e( 'Download Expiry', 'lime-catalog' ); ?></label></th>
        <td>
      <input type="number" id="item_download_expiry" name="item_download_expiry" class="item_download_expiry_field" value="<?php echo esc_attr__( $item_download_expiry ); ?>">
        <p class="description"> <?php  _e( 'Enter the number of days before the download link expires, or leave blank so it is never expires.', 'lime-catalog' ); ?></p>
        </td>
        </tr>
    
    </table>
    
    
    
    
    
    
	<?php   
    
    // TANGIBLE ITEMS
    // show fields only if shopping cart tangible items enabled (1) on the plugin activator page
    if ( $lmctlg_general_options['enable_tangible_items'] == 'do_not_enable' ) 
    {
        
    // Table - Tangible Items
    ?>
    <table class="form-table">	
        
        <tr>
        <th><label for="item_shipping" class="item_shipping_label"><?php  _e( 'Shipping', 'lime-catalog' ); ?></label></th>
        <td>
      <?php  //if( $item_downloadable == true ) { $downloadable = 'checked="checked"'; } else { $downloadable = ''; } ?>
      <input type="checkbox" id="item_shipping" name="item_shipping" class="item_shipping_field" value="1">
        <p class="description"> <?php  _e( 'Tick if product shipping required.', 'lime-catalog' ); ?></p>
        </td>
        </tr>
    
        <tr>
        <th><label for="item_weight" class="item_weight_label"><?php  _e( 'Item Weight', 'lime-catalog' ); ?></label></th>
        <td>
      <input type="text" id="item_weight" name="item_weight" class="item_weight_field" value="">
        <p class="description"> <?php  _e( 'Item weight is in (kg).', 'lime-catalog' ); ?></p>
        </td>
        </tr>
    
        <tr>
        <th><label for="item_dimensions" class="item_weight_label"><?php  _e( 'Item Dimensions', 'lime-catalog' ); ?></label></th>
        <td>
      <input type="text" id="item_lenght" name="item_lenght" class="item_lenght_field" value="" placeholder="lenght">
      <input type="text" id="item_width" name="item_width" class="item_width_field" value="" placeholder="width">
      <input type="text" id="item_height" name="item_height" class="item_height_field" value="" placeholder="height">
        <p class="description"> <?php  _e( 'Item dimensions is in (cm).', 'lime-catalog' ); ?></p>
        </td>
        </tr>
    
        <tr>
            <th><label for="item_shipping_options" class="item_shipping_options_label"><?php  _e( 'Shipping Options', 'lime-catalog' ); ?></label></th>
            <td>
                <select id="item_shipping_options" name="item_shipping_options" class="item_shipping_options_field">
                <option value="local_pickup"> <?php  _e( 'Local Pickup', 'lime-catalog' ); ?></option>
                <option value="free_shipping"> <?php  _e( 'Free Shipping', 'lime-catalog' ); ?></option>
                <option value="local_delivery"> <?php  _e( 'Local Delivery', 'lime-catalog' ); ?></option>
                <option value="flat_rate"> <?php  _e( 'Flat Rate', 'lime-catalog' ); ?></option>
                <option value="international_flat_rate"> <?php  _e( 'International Flat Rate', 'lime-catalog' ); ?></option>
                </select>
        <p class="description"> <?php  _e( 'Please select shipping option.', 'lime-catalog' ); ?></p>
            </td>
        </tr>
    
    </table>
	<?php    
    }
    ?>