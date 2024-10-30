
<?php 

	// get options
	$email_settings_options = get_option('lmctlg_order_email_settings_options');

?>

<br class="clear">

<div id="tab_container">

<form method="post" action="" id="lmctlg-order-email-settings-options-form">

<input type="hidden" name="lmctlg-order-email-settings-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_order_email_settings_options_form_nonce'); ?>"/>

<table class="form-table">
    <h2><?php _e('Email Settings', 'lime-catalog'); ?></h2>
    <tbody>
<tr>
    <th scope="row"><?php _e('Emails Logo', 'lime-catalog'); ?></th>
    <td>
        <input type="text" value="<?php echo stripslashes_deep( $email_settings_options['emails_logo'] ); ?>" name="lmctlg_emails_logo" id="lmctlg_emails_logo" class="regular-text">
        <input type="button" value="Select Image" class="button" id="lmctlg_upload_emails_logo_button">
         <p class="description"><?php _e('Please select logo image. The logo image displayed on HTML emails only.', 'lime-catalog'); ?></p>
    </td>
</tr>

    </tbody>
</table>
    
    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->