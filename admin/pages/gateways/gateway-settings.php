


<br class="clear">

<div id="tab_container">

<form method="post" action="" id="lmctlg-payment-gateway-options-form">

<input type="hidden" name="lmctlg-payment-gateway-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_payment_gateway_options_form_nonce'); ?>"/>

    <table class="form-table">
        <h2><?php _e('Payment Gateway Settings', 'lime-catalog'); ?></h2>
        <tbody>
    <tr>
    <th scope="row"><?php _e('Default Gateway', 'lime-catalog'); ?></th>
    <td>
        <select id="lmctlg_default_payment_gateway" name="lmctlg_default_payment_gateway">
        
<?php 
    $defaultgateway = $lmctlg_payment_gateway_options['default_payment_gateway'];

    $payment_gateways = LMCTLG_Payment_Gateways::lmctlg_payment_gateways();
	$json = json_encode( $payment_gateways ); // convert array to json

	$gateways_obj   = json_decode( $json ); // Translate into an object
	$gateways_array = json_decode( $json, true ); // Translate into an array
	
	// if has contents
	if(count($gateways_obj)>0)
	{	
	
	  if ( empty($defaultgateway) ) {
		 echo '<option selected="selected" value="">' . __('Please Select Gateway', 'lime-catalog') . '</option>';  
	  }
	
	  // Payment Methods
	  foreach( $gateways_obj as $gateway => $value )
	  {
		  
		if ( $defaultgateway == $gateway ) {
		  echo '<option selected="selected" value="' . esc_attr( $gateway ) . '">' . esc_attr( $value->payment_gateway_label ) . '</option>';  
		}
		
		   // check if option exist
		   if( get_option('lmctlg_gateway_' . $gateway . '_options') ){
			   // get options for gateways
			   $gateways_options = get_option('lmctlg_gateway_' . $gateway . '_options');
			   // check if gateway enabled
			   if ( $gateways_options['lmctlg_' . $gateway . '_enabled'] == '1' ) {
				   
                  echo '<option value="' . esc_attr( $gateway ) . '">' . esc_attr( $value->payment_gateway_label ) . '</option>';  
				 
			   }
		   }
		  
		
	  }
	  
	}
?>

        </select>
        <p class="description"><?php _e('Default gateway for checkout.', 'lime-catalog'); ?></p>
    </td>
</tr>

        </tbody>
    </table>
    
    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->