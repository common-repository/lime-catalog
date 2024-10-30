

<br class="clear">

<div id="tab_container">

<form method="post" action="" id="lmctlg-paypal-standard-options-form">

<input type="hidden" name="lmctlg-paypal-standard-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_paypal_standard_options_form_nonce'); ?>"/>

    <table class="form-table">
        <h2><?php _e('PayPal Standard', 'lime-catalog'); ?></h2>
        <tbody>
<tr>
    <th scope="row"><?php _e('Enable/Disable', 'lime-catalog'); ?></th>
    <td>
        <input type="checkbox" value="1" id="lmctlg_paypal_standard_enabled" name="lmctlg_paypal_standard_enabled">
        <p class="description"><?php _e('Enable PayPal Standard', 'lime-catalog'); ?></p>
    </td>
</tr>
<tr>
    <th scope="row"><?php _e('Title', 'lime-catalog'); ?></th>
    <td>
        <input type="text" value="PayPal Standard" name="lmctlg_paypal_standard_title" id="lmctlg_paypal_standard_title" class="regular-text">
    </td>
</tr>
<tr valign="top">
    <th scope="row">
        <label for="lmctlg_paypal_standard_description"><?php _e('Description', 'lime-catalog'); ?></label>
    </th>
    <td>
        <textarea class="widefat" rows="4" cols="90" name="lmctlg_paypal_standard_description" id="lmctlg_paypal_standard_description"><?php _e('Secure payments via PayPal.', 'lime-catalog'); ?></textarea>
        <p class="description"><?php _e('Description will be shown on the checkout page.', 'lime-catalog'); ?></p>
    </td>
</tr>
<tr>
    <th scope="row"><?php _e('PayPal Email', 'lime-catalog'); ?></th>
    <td>
        <input type="text" value="" name="lmctlg_paypal_email" id="lmctlg_paypal_email" class="regular-text">
        <p class="description"><?php _e('Your PayPal email address for payments.', 'lime-catalog'); ?></p>
    </td>
</tr>
<tr>
    <th scope="row"><?php _e('PayPal Page Style', 'lime-catalog'); ?></th>
    <td>
        <input type="text" value="" name="lmctlg_paypal_page_style" id="lmctlg_paypal_page_style" class="regular-text">
        <p class="description"><?php _e('Enter the name of the page style you wish to use or leave blank for default.', 'lime-catalog'); ?></p>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><?php _e('PayPal Sandbox', 'lime-catalog'); ?></th>
    <td>
       <input type="checkbox" value="1" id="lmctlg_paypal_testmode" name="lmctlg_paypal_testmode" class=""> 
	   <p class="description"><?php _e('Enable PayPal sandbox', 'lime-catalog'); ?></p>
       <p class="description"><?php _e('PayPal sandbox for test payments. Sign up for a developer account', 'lime-catalog'); ?> 
       <a href="<?php echo esc_url( 'https://developer.paypal.com/' ); ?>" target="_blank"><?php _e('here', 'lime-catalog'); ?></a>.
       </p>
    </td>
</tr>

        </tbody>
    </table>
    
    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->