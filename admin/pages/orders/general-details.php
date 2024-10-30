


<div class="padding-left-right-15">

<div class="order-details-title"><?php _e( 'Order #', 'lime-catalog' ); ?><?php echo $id = get_the_ID(); ?> <?php _e( 'details', 'lime-catalog' ); ?>
<?php 
if ( ! empty($order_gateway) ) {
$payment_gateways = LMCTLG_Payment_Gateways::lmctlg_payment_gateways();

$payment_gateway_label = ''; // default
if ( !empty($payment_gateways[$order_gateway]['payment_gateway_label']) ) {
	$payment_gateway_label = $payment_gateways[$order_gateway]['payment_gateway_label'];
}

echo '<span>' . __( 'Payment via', 'lime-catalog' ) . ' ' . esc_html( $payment_gateway_label ) . '</span>';

}
?>
</div>

<!-- table-responsive start -->
<div class="cw-table-responsive cw-admin-forms"> 

<table id="cwtable">

<tbody>  
  
  <tr>
    <td>
    <p><?php _e("Order Date", 'lime-catalog'); ?></p>
    <?php 
	if ( !empty($order_date) ) {
		$orderdate = $order_date;
	} else {
		$orderdate = date("Y-m-d H:i:s");
	}
	?>
    <input class="inputfield" id="_order_date" name="_order_date" type="text" value="<?php echo esc_attr__( $orderdate ); ?>">
    </td>
    <td>
    <p><?php _e("Customer", 'lime-catalog'); ?></p>
    <select class="selectfield lmctlg_select_customer_class" name="_lmctlg_order_cus_user_id" id="_lmctlg_order_cus_user_id">
		<?php 
		if ( ! empty($cus_user_id) ) {
			$user_customer = get_user_by( 'id', $cus_user_id );
			echo '<option selected="selected" data-cus-user-email="' . esc_attr( $user_customer->user_email ) . '" value="' . esc_attr__( $cus_user_id ) . '">';
			echo esc_attr__( '#' . $cus_user_id . ' ' . $user_customer->first_name . ' ' . $user_customer->last_name . ' - ' . $user_customer->user_email );
			echo '</option>';
		} else {
			echo '<option selected="selected" value="">';
			echo '';
			echo '</option>';
		}
        
        $users = get_users();
        // Array of WP_User objects.
        foreach ( $users as $user ) {
            echo'<option data-cus-user-email="' . esc_attr( $user->user_email ) . '" value="' . esc_html( $user->ID ) . '">' . esc_html( '#' . $user->ID . ' ' . $user->first_name . ' ' . $user->last_name . ' - ' . $user->user_email ) . '</option>';
        }
        
        ?>
    </select>
    </td>
    <td>
    <p><?php _e("Order Status", 'lime-catalog'); ?></p>
    <?php 
	$status = ''; // default
	$statuses = LMCTLG_Custom_Post_Statuses::lmctlg_order_custom_post_statuses();
	if ( !empty($order_status) ) {
		$status = $statuses[$order_status];
	}
	?>
    <select class="selectfield" name="_order_status" id="_order_status">
        <option selected="selected" value="<?php echo esc_attr__( $order_status ); ?>"><?php echo esc_attr__( $status ); ?></option>
        <?php 
			
			foreach($statuses as $status => $value )
			{
				echo '<option value="' . esc_attr( $status ) . '">' . esc_attr( $value ) . '</option>'; 
			}
		?>
    </select>
    </td>
  </tr> 
  
  <tr>
    <td>
    <p><?php _e("Payment Method", 'lime-catalog'); ?></p>
    
        <select class="selectfield" id="_order_gateway" name="_order_gateway">
        
		<?php 
		
            $payment_gateways = LMCTLG_Payment_Gateways::lmctlg_payment_gateways();
            $json = json_encode( $payment_gateways ); // convert array to json
        
            $gateways_obj   = json_decode( $json ); // Translate into an object
            $gateways_array = json_decode( $json, true ); // Translate into an array
			
			if ( empty($order_gateway) ) {
				echo '<option selected="selected" value=""> </option>';
			}
			
            // if has contents
            if(count($gateways_obj)>0)
            {	
              // Payment Methods
              foreach( $gateways_obj as $gateway => $value )
              {
				  
				if ( ! empty($order_gateway) ) {
					
					if ( $order_gateway == $gateway ) {
					  echo '<option selected="selected" value="' . esc_attr( $gateway ) . '">' . esc_attr( $value->payment_gateway_label ) . '</option>';  
					}

				}  

				   // check if option exist
				   if( get_option('lmctlg_gateway_' . esc_attr( $gateway ) . '_options') ){
					   // get options for gateways
					   $gateways_options = get_option('lmctlg_gateway_' . esc_attr( $gateway ) . '_options');
					   // check if gateway enabled
					   if ( $gateways_options['lmctlg_' . esc_attr( $gateway ) . '_enabled'] == '1' ) {
						   
						  echo '<option value="' . esc_attr( $gateway ) . '">' . esc_attr( $value->payment_gateway_label ) . '</option>';  
						 
					   }
				   }
                  
                
              }
              
            }
        ?>

        </select>
    
    </td>
    <td>
    <p><?php _e("Transaction ID", 'lime-catalog'); ?></p>
    <input class="inputfield" id="_order_transaction_id" name="_order_transaction_id" type="text" value="<?php echo esc_attr__( $transaction_id ); ?>">
    </td>
    <td>&nbsp;</td>
  </tr> 
  
</tbody>

</table>


</div>
<!-- table-responsive end -->

</div><!--/ padding-left-right-15 -->

