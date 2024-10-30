
<div id="tab_container">

	<?php 
	    
		/*
		echo '<pre>';
		print_r($lmctlg_currency_options);
		echo '</pre>';
		*/
    
    ?>
    
<form method="post" action="" id="lmctlg-currency-options-form">

<input type="hidden" name="lmctlg-currency-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_currency_options_form_nonce'); ?>"/>

    <table class="form-table">
        <h2 class="padding-top-15"><?php _e('Currency Options', 'lime-catalog'); ?></h2>
        <p><?php _e('The following options affect how currency displayed on the frontend.', 'lime-catalog'); ?></p>
        <tbody>
            <tr>
                <th scope="row"><?php _e('Currency', 'lime-catalog'); ?></th>
                <td>
                    <select class="small-text" name="catalog_currency" id="catalog_currency">
                        <option selected="selected" value="<?php echo esc_attr( $lmctlg_currency_options['catalog_currency'] ); ?>"><?php echo esc_attr( $lmctlg_currency_options['catalog_currency_name'] ); ?> (<?php echo esc_attr( strtoupper( $lmctlg_currency_options['catalog_currency'] ) ); ?>)</option>
                                <?php
                                foreach ( LMCTLG_Amount::lmctlg_available_currencies() as $currency_key => $currency_obj ) {
                                    $option = '<option value="' . $currency_key . '">';
                                    $option .= esc_attr( $currency_obj['name'] ) . ' (' . esc_attr( $currency_obj['code'] ) . ')';
                                    $option .= '</option>';
                                    echo $option;
                                }
                                ?>
                            </select>
                            <p class="description"><?php _e('Default currency.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Currency Position', 'lime-catalog'); ?></th>
                <td>
                    <select class="small-text" name="currency_position" id="currency_position">
                        <option selected="selected" value="<?php echo esc_attr( $lmctlg_currency_options['currency_position'] ); ?>"><?php echo esc_attr( $lmctlg_currency_options['currency_position'] ); ?></option>
                        <option value="<?php echo esc_attr( 'Left' ); ?>"><?php _e('Left', 'lime-catalog'); ?></option>
                        <option value="<?php echo esc_attr( 'Right' ); ?>"><?php _e('Right', 'lime-catalog'); ?></option>
                    </select>
                    <p class="description"><?php _e('Options: Left ($50.00) or Right (50.00$)', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Thousand Separator', 'lime-catalog'); ?></th>
                <td>
                    <select class="small-text" name="thousand_separator" id="thousand_separator">
                        <option selected="selected" value="<?php echo esc_attr( $lmctlg_currency_options['thousand_separator'] ); ?>"><?php echo esc_attr( $lmctlg_currency_options['thousand_separator'] ); ?></option>
                        <option value="<?php echo esc_attr( ',' ); ?>"><?php echo esc_attr( ',' ); ?></option>
                        <option value="<?php echo esc_attr( '.' ); ?>"><?php echo esc_attr( '.' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Decimal Separator', 'lime-catalog'); ?></th>
                <td>
                    <select class="small-text" name="decimal_separator" id="decimal_separator">
                        <option selected="selected" value="<?php echo esc_attr( $lmctlg_currency_options['decimal_separator'] ); ?>"><?php echo esc_attr( $lmctlg_currency_options['decimal_separator'] ); ?></option>    
                        <option value="<?php echo esc_attr( '.' ); ?>"><?php echo esc_attr( '.' ); ?></option>
                        <option value="<?php echo esc_attr( ',' ); ?>"><?php echo esc_attr( ',' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Number of Decimals', 'lime-catalog'); ?></th>
                <td>
                    <input class="small-text" type="number" value="<?php echo esc_attr( $lmctlg_currency_options['number_of_decimals'] ); ?>" name="number_of_decimals" id="number_of_decimals" >
                </td>
            </tr>
        </tbody>
    </table>
    
    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->