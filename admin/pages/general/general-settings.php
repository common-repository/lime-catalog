
<div id="tab_container">
    
<form method="post" action="" id="lmctlg-save-settings-options-form">

<input type="hidden" name="lmctlg-save-settings-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_save_settings_options_form_nonce'); ?>"/>

    <table class="form-table">
        <h2 class="padding-top-15"><?php _e('Settings', 'lime-catalog'); ?></h2>
        <tbody>
            <tr>
                <th scope="row"><?php _e('Plugin Deactivator', 'lime-catalog'); ?></th>
                <td>
                    <input type="checkbox" value="1" name="lmctlg_plugin_deactivation_save_settings" id="lmctlg_plugin_deactivation_save_settings" <?php echo ($lmctlg_save_settings_options['lmctlg_plugin_deactivation_save_settings'] == '1') ? 'checked' : '' ?>>
                    <p class="description"><?php _e('Save site settings on plugin deactivation.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Plugin Uninstaller', 'lime-catalog'); ?></th>
                <td>
                    <input type="checkbox" value="1" name="lmctlg_plugin_uninstall_save_settings" id="lmctlg_plugin_uninstall_save_settings" <?php echo ($lmctlg_save_settings_options['lmctlg_plugin_uninstall_save_settings'] == '1') ? 'checked' : '' ?>>
                    <p class="description"><?php _e('Save site settings on plugin uninstallation.', 'lime-catalog'); ?></p>
                </td>
            </tr>
        </tbody>
    </table>

    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->