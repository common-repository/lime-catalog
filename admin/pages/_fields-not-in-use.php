            <tr>
                <th scope="row"><?php _e('Thousands Separator', 'lime-catalog'); ?></th>
                <td>
                    <input class="small-text" type="text" value="<?php echo $lmctlg_currency_options['thousands_separator']; ?>" name="thousands_separator" id="thousands_separator"> 
                    <p class="description"><?php _e('The symbol (usually , or .) to separate thousands. by default: ,', 'lime-catalog'); ?> </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Decimal Separator', 'lime-catalog'); ?></th>
                <td>
                    <input class="small-text" type="text" value="<?php echo $lmctlg_currency_options['decimal_separator']; ?>" name="decimal_separator" id="decimal_separator"> 
                    <p class="description"><?php _e('The symbol (usually , or .) to separate thousands. by default: . ', 'lime-catalog'); ?> </p>
                </td>
            </tr>