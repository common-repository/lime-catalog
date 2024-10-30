
<?php 

	// get options
	$order_notifications_options = get_option('lmctlg_order_notifications_options');

?>

<br class="clear">

<div id="tab_container">

<form method="post" action="" id="lmctlg-order-notifications-options-form">

<input type="hidden" name="lmctlg-order-notifications-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_order_notifications_options_form_nonce'); ?>"/>

<table class="form-table">
    <h2><?php _e('Order Notifications', 'lime-catalog'); ?></h2>
    <p class="description"><?php _e('You will receive an order notification email for each sales.', 'lime-catalog'); ?></p>
    <tbody>
<tr>
    <th scope="row"><?php _e('Enable/Disable', 'lime-catalog'); ?></th>
    <td>
        <input type="checkbox" value="1" id="lmctlg_notifications_enabled" name="lmctlg_notifications_enabled" <?php echo ($order_notifications_options['notifications_enabled'] == '1') ? 'checked' : '' ?>>
        <p class="description"><?php _e('Enable/Disable order notification email.', 'lime-catalog'); ?></p>
    </td>
</tr>  

<tr>
    <th scope="row"><?php _e('Subject', 'lime-catalog'); ?></th>
    <td>
        <input type="text" value="<?php echo esc_attr( stripslashes_deep( $order_notifications_options['subject'] ) ); ?>" name="lmctlg_subject" id="lmctlg_subject" class="regular-text">
         <p class="description"><?php _e('Email subject of the order notifications.', 'lime-catalog'); ?></p>
    </td>
</tr>

<tr valign="top">
    <th scope="row">
        <label for="lmctlg_send_to"><?php _e('Send To', 'lime-catalog'); ?></label>
    </th>
    <td>
<textarea class="widefat" rows="3" cols="90" name="lmctlg_send_to">
<?php echo esc_textarea( stripslashes_deep( $order_notifications_options['send_to'] ) ); ?>
</textarea>
        <p class="description"> <?php _e('Send email notification to email(s) upon sales. Separate email addresses by comma ( , ).', 'lime-catalog'); ?></p>
        <p class="description"> <?php _e('Example for multiple recipients: example1@example.com, example2@example.com, example3@example.com etc.', 'lime-catalog'); ?></p>
    </td>
</tr>
<tr> 

    </tbody>
</table>

<table id="lmctlg_order_notification_email_content_table" class="form-table" style="width:100%;" >

<tr>

    <td>
<label for="lmctlg_email_content"><strong><?php _e('Email Content', 'lime-catalog'); ?></strong></label>
<?php 
	// <textarea  style="width:100%;" id="product_desc_section" name="product_desc_section" class="product_desc_section_textarea" rows="24" cols="70"></textarea>
	// add wysiwyg editor in Wordpress meta box
	// source: https://codex.wordpress.org/Function_Reference/wp_editor	
	// only low case [a-z], no hyphens and underscores
	$editor_id = 'lmctlg_order_notification_email_content_editor';
	$content = stripslashes_deep( $order_notifications_options['email_content'] );
	// 'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ), // note that spaces in this list seem to cause an issue
	wp_editor( $content, $editor_id, array(
		'wpautop'       => true, // remove <br> and <p> tags if set to true
		'media_buttons' => false,
		'textarea_name' => 'lmctlg_email_content',
		'textarea_rows' => 38,
		'teeny'         => true
	) );
?>
        
        <br>
        
        <p class="description"> <?php _e('The following template tags are available:', 'lime-catalog'); ?> </p>
        
        <br>
        
        <p class="description"> <?php _e('[user_first_name]  - user (buyer) first name', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[user_last_name]   - user (buyer) last name', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[user_email]       - user (buyer) email', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[user_company]     - user (buyer) company or business name', 'lime-catalog'); ?> </p>
        
        <br>
        
        <p class="description"> <?php _e('[billing_country]  - billing country', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[billing_city]     - billing city', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[billing_state]    - billing state', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[billing_addr_1]   - billing address line 1', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[billing_addr_2]   - billing address line 2', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[billing_zip]      - billing zip', 'lime-catalog'); ?> </p>
        
        <br>
        
        <p class="description"> <?php _e('[items]            - order items list', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[order_total]      - order total', 'lime-catalog'); ?> </p>
        
        <br>
        
        <p class="description"> <?php _e('[order_status]     - the status of the order E.g. Completed, Failed etc.', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[order_id]         - order ID', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[transaction_id]   - transaction id', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[order_key]        - order key', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[order_date]       - order date', 'lime-catalog'); ?> </p>
        
        <br>
        
        <p class="description"> <?php _e('[payment_gateway]       - gateway base name', 'lime-catalog'); ?> </p>
        <p class="description"> <?php _e('[payment_gateway_data]  - gateway title (name), gateway notes, bank account details (only for BACS)', 'lime-catalog'); ?> </p>

    </td>
</tr>

</table>
    
    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->