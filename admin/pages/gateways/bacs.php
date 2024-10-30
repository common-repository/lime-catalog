

<br class="clear">

<div id="tab_container">

<form method="post" action="" id="lmctlg-bacs-options-form">

<input type="hidden" name="lmctlg-bacs-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_bacs_options_form_nonce'); ?>"/>

<table class="form-table">
    <h2><?php _e('Direct Bank Transfer (BACS)', 'lime-catalog'); ?></h2>
    <tbody>
<tr>
    <th scope="row"><?php _e('Enable/Disable', 'lime-catalog'); ?></th>
    <td>
        <input type="checkbox" value="1" id="lmctlg_bacs_enabled" name="lmctlg_bacs_enabled" <?php echo ($lmctlg_gateway_bacs_options['lmctlg_bacs_enabled'] == '1') ? 'checked' : '' ?>>
        <p class="description"><?php _e('Enable direct bank transfer as a payment method.', 'lime-catalog'); ?></p>
    </td>
</tr>
<tr>
    <th scope="row"><?php _e('Billing Details', 'lime-catalog'); ?></th>
    <td>
        <input type="checkbox" value="1" id="lmctlg_bacs_show_billing_details" name="lmctlg_bacs_show_billing_details" <?php echo ($lmctlg_gateway_bacs_options['lmctlg_bacs_show_billing_details'] == '1') ? 'checked' : '' ?>>
        <p class="description"><?php _e('Display billing details fields on the checkout page.', 'lime-catalog'); ?></p>
    </td>
</tr>
<tr>
    <th scope="row"><?php _e('Title', 'lime-catalog'); ?></th>
    <td>
        <input type="text" value="<?php echo esc_attr( stripslashes_deep( $lmctlg_gateway_bacs_options['lmctlg_bacs_title'] ) ); ?>" name="lmctlg_bacs_title" id="lmctlg_bacs_title" class="regular-text">
    </td>
</tr>
<tr valign="top">
    <th scope="row">
        <label for="lmctlg_bacs_description"><?php _e('Description', 'lime-catalog'); ?></label>
    </th>
    <td>
        <textarea class="widefat" rows="4" cols="90" name="lmctlg_bacs_description" id="lmctlg_bacs_description"><?php echo esc_attr( stripslashes_deep( $lmctlg_gateway_bacs_options['lmctlg_bacs_description'] ) ); ?></textarea>
        <p class="description"><?php _e('Payment method short description will be shown on the checkout page.', 'lime-catalog'); ?></p>
    </td>
</tr>

    </tbody>
</table>

<table id="lmctlg_bacs_notes_table" class="form-table" style="width:100%;" >

<tr>
    <td>
<label for="lmctlg_bacs_notes"><strong><?php _e('Notes', 'lime-catalog'); ?></strong></label>
<?php 
	// <textarea  style="width:100%;" id="product_desc_section" name="product_desc_section" class="product_desc_section_textarea" rows="24" cols="70"></textarea>
	// add wysiwyg editor in Wordpress meta box
	// source: https://codex.wordpress.org/Function_Reference/wp_editor	
	// only low case [a-z], no hyphens and underscores
	$editor_id = 'lmctlg_bacs_notes_notes_editor';
	$content   = stripslashes_deep( $lmctlg_gateway_bacs_options['lmctlg_bacs_notes'] );
	// 'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ), // note that spaces in this list seem to cause an issue
	wp_editor( $content, $editor_id, array(
		'wpautop'       => true, // remove <br> and <p> tags if set to true
		'media_buttons' => false,
		'textarea_name' => 'lmctlg_bacs_notes',
		'textarea_rows' => 8,
		'teeny'         => true
	) );
?>
<p class="description"><?php _e('Notes will be added to the order receipt email.', 'lime-catalog'); ?></p>
    </td>
</tr>

</table>

<table id="lmctlg_bacs_bank_account_details_table" class="form-table" style="width:100%;" >

<tr>
    <td>
<label for="lmctlg_bacs_bank_account_details"><strong><?php _e('Bank Account Details', 'lime-catalog'); ?></strong></label>
<?php 
	// <textarea  style="width:100%;" id="product_desc_section" name="product_desc_section" class="product_desc_section_textarea" rows="24" cols="70"></textarea>
	// add wysiwyg editor in Wordpress meta box
	// source: https://codex.wordpress.org/Function_Reference/wp_editor	
	// only low case [a-z], no hyphens and underscores
	$editor_id = 'lmctlg_bacs_bank_account_details_editor';
	$content   = stripslashes_deep( $lmctlg_gateway_bacs_options['lmctlg_bacs_bank_account_details'] );
	// 'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ), // note that spaces in this list seem to cause an issue
	wp_editor( $content, $editor_id, array(
		'wpautop'       => true, // remove <br> and <p> tags if set to true
		'media_buttons' => false,
		'textarea_name' => 'lmctlg_bacs_bank_account_details',
		'textarea_rows' => 12,
		'teeny'         => true
	) );
?>
<p class="description"><?php _e('Bank account details will be added to the order receipt email. Add your bank account details e.g. Account Name, Bank Name, Account Number, Sort Code, IBAN, BIC / Swift', 'lime-catalog'); ?></p>
    </td>
</tr>

</table>
    
    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->