
<div id="tab_container">

	<?php 
	    
		/*
		echo '<pre>';
		print_r($lmctlg_general_options);
		echo '</pre>';
		*/
    
    ?>
    
<form method="post" action="" id="lmctlg-general-options-form">

<input type="hidden" name="lmctlg-general-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_general_options_form_nonce'); ?>"/>
    
<table class="form-table">
<h2 class="padding-top-15"><?php _e('Item Options', 'lime-catalog'); ?></h2>
<p><?php _e('The following options affect how items (products) are displayed on the frontend.', 'lime-catalog'); ?></p>
    <tbody>
            <tr>
                <th scope="row"><?php _e('Items View', 'lime-catalog'); ?></th>
                <td>
                    <select class="small-text" name="default_items_view" id="default_items_view">
                        <option selected="selected" value="<?php echo esc_attr( $lmctlg_general_options['default_items_view'] ); ?>"><?php echo esc_attr( $lmctlg_general_options['default_items_view'] ); ?></option>
                        <option value="Normal"><?php _e('Normal', 'lime-catalog'); ?></option>
                        <option value="Large"><?php _e('Large', 'lime-catalog'); ?></option>
                        <option value="List"><?php _e('List', 'lime-catalog'); ?></option>
                    </select>
                    <p class="description"><?php _e('Select default item view option, Normal, Large or List view.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Display Item Thumb Image', 'lime-catalog'); ?></th>
                <td>
                <input type="checkbox" value="1" name="display_item_thumb_img" id="display_item_thumb_img" <?php echo ($lmctlg_general_options['display_item_thumb_img'] == '1') ? 'checked' : '' ?>>
                    <p class="description"><?php _e('Display item thumb image on item listing page.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Display Item Price', 'lime-catalog'); ?></th>
                <td>
                <input type="checkbox" value="1" name="display_item_price" id="display_item_price" <?php echo ($lmctlg_general_options['display_item_price'] == '1') ? 'checked' : '' ?>>
                    <p class="description"><?php _e('Display item price on item listing page.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Display Short Description', 'lime-catalog'); ?></th>
                <td>
                <input type="checkbox" value="1" name="display_item_short_desc" id="display_item_short_desc" <?php echo ($lmctlg_general_options['display_item_short_desc'] == '1') ? 'checked' : '' ?>>
                    <p class="description"><?php _e('Display item short description on item listing page.', 'lime-catalog'); ?></p>
                </td>
            </tr>
        <tr>
            <th scope="row"><?php _e('Items per page', 'lime-catalog'); ?></th>
            <td>
             <input type="number" value="<?php echo esc_attr( $lmctlg_general_options['number_of_items_per_page'] ); ?>" name="number_of_items_per_page" class="small-text" id="number_of_items_per_page" step="1">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Items order by', 'lime-catalog'); ?></th>
            <td>
            
<?php 

    // format items order by
    if ( $lmctlg_general_options['items_order_by'] == 'modified' ) {
        $items_order_by = 'last modified';
    } elseif ( $lmctlg_general_options['items_order_by'] == 'rand' ) {
        $items_order_by = 'random';
    } else {
        $items_order_by = $lmctlg_general_options['items_order_by'];
    }

?>
            
                <select class="small-text" name="items_order_by" id="items_order_by">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_general_options['items_order_by'] ); ?>"><?php echo esc_attr( $items_order_by ); ?></option>
                    <option value="ID"><?php _e('ID', 'lime-catalog'); ?></option>
                    <option value="author"><?php _e('author', 'lime-catalog'); ?></option>
                    <option value="title"><?php _e('title', 'lime-catalog'); ?></option>
                    <option value="name"><?php _e('name', 'lime-catalog'); ?></option>
                    <option value="date"><?php _e('date', 'lime-catalog'); ?></option>
                    <option value="modified"><?php _e('last modified', 'lime-catalog'); ?></option>
                    <option value="rand"><?php _e('random', 'lime-catalog'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Items order', 'lime-catalog'); ?></th>
            <td>
                <p>
                    <input type="radio" value="<?php echo esc_attr( 'ASC' ); ?>" name="items_order" id="items_order_asc" <?php echo ($lmctlg_general_options['items_order'] == 'ASC') ? 'checked' : '' ?>><?php _e('ASC', 'lime-catalog'); ?>
                </p>
                <p>
                    <input type="radio" value="<?php echo esc_attr( 'DESC' ); ?>" name="items_order" id="items_order_decs" <?php echo ($lmctlg_general_options['items_order'] == 'DESC') ? 'checked' : '' ?>><?php _e('DESC', 'lime-catalog'); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>

<table class="form-table">
<h2><?php _e('Category Options', 'lime-catalog'); ?></h2>
<p><?php _e('The following options affect how categories are displayed on the frontend.', 'lime-catalog'); ?></p>
    <tbody>
            <tr>
                <th scope="row"><?php _e('Display Category Boxes', 'lime-catalog'); ?></th>
                <td>
                <input type="checkbox" value="1" name="display_category_boxes" id="display_category_boxes" <?php echo ($lmctlg_general_options['display_category_boxes'] == '1') ? 'checked' : '' ?>>
                    <p class="description"><?php _e('Display category boxes on the catalog pages.', 'lime-catalog'); ?></p>
                </td>
            </tr>
        <tr>
            <th scope="row"><?php _e('Category order by', 'lime-catalog'); ?></th>
            <td>
            
<?php 

    // format category order by
    if ( $lmctlg_general_options['category_order_by'] == 'modified' ) {
        $category_order_by = 'last modified';
    } elseif ( $lmctlg_general_options['category_order_by'] == 'rand' ) {
        $category_order_by = 'random';
    } else {
        $category_order_by = $lmctlg_general_options['category_order_by'];
    }

?>
            
                <select class="small-text" name="category_order_by" id="category_order_by">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_general_options['category_order_by'] ); ?>"><?php echo esc_attr( $category_order_by ); ?></option>
                    <option value="ID"><?php _e('ID', 'lime-catalog'); ?></option>
                    <option value="author"><?php _e('author', 'lime-catalog'); ?></option>
                    <option value="title"><?php _e('title', 'lime-catalog'); ?></option>
                    <option value="name"><?php _e('name', 'lime-catalog'); ?></option>
                    <option value="date"><?php _e('date', 'lime-catalog'); ?></option>
                    <option value="modified"><?php _e('last modified', 'lime-catalog'); ?></option>
                    <option value="rand"><?php _e('random', 'lime-catalog'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Category order', 'lime-catalog'); ?></th>
            <td>
                <p>
                    <input type="radio" value="<?php echo esc_attr( 'ASC' ); ?>" name="category_order" id="items_order_asc" <?php echo ($lmctlg_general_options['category_order'] == 'ASC') ? 'checked' : '' ?>><?php _e('ASC', 'lime-catalog'); ?>
                </p>
                <p>
                    <input type="radio" value="<?php echo esc_attr( 'DESC' ); ?>" name="category_order" id="items_order_decs" <?php echo ($lmctlg_general_options['category_order'] == 'DESC') ? 'checked' : '' ?>><?php _e('DESC', 'lime-catalog'); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>

<table class="form-table">
<h2><?php _e('Sidebar Parent Menu Options', 'lime-catalog'); ?></h2>
<p><?php _e('The following options affect how parent menu(s) are displayed on the frontend.', 'lime-catalog'); ?></p>
    <tbody>
        <tr>
            <th scope="row"><?php _e('Parent Menu order by', 'lime-catalog'); ?></th>
            <td>
            
<?php 

    // format parent menu order by
    if ( $lmctlg_general_options['parent_menu_order_by'] == 'modified' ) {
        $parent_menu_order_by = 'last modified';
    } elseif ( $lmctlg_general_options['parent_menu_order_by'] == 'rand' ) {
        $parent_menu_order_by = 'random';
    } else {
        $parent_menu_order_by = $lmctlg_general_options['parent_menu_order_by'];
    }

?>
            
                <select class="small-text" name="parent_menu_order_by" id="parent_menu_order_by">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_general_options['parent_menu_order_by'] ); ?>"><?php echo esc_attr( $parent_menu_order_by ); ?></option>
                    <option value="ID"><?php _e('ID', 'lime-catalog'); ?></option>
                    <option value="author"><?php _e('author', 'lime-catalog'); ?></option>
                    <option value="title"><?php _e('title', 'lime-catalog'); ?></option>
                    <option value="name"><?php _e('name', 'lime-catalog'); ?></option>
                    <option value="date"><?php _e('date', 'lime-catalog'); ?></option>
                    <option value="modified"><?php _e('last modified', 'lime-catalog'); ?></option>
                    <option value="rand"><?php _e('random', 'lime-catalog'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Parent Menu order', 'lime-catalog'); ?></th>
            <td>
                <p>
                    <input type="radio" value="<?php echo esc_attr( 'ASC' ); ?>" name="parent_menu_order" id="parent_menu_order_asc" <?php echo ($lmctlg_general_options['parent_menu_order'] == 'ASC') ? 'checked' : '' ?>> <?php _e('ASC', 'lime-catalog'); ?>
                </p>
                <p>
                    <input type="radio" value="<?php echo esc_attr( 'DESC' ); ?>" name="parent_menu_order" id="parent_menu_order_decs" <?php echo ($lmctlg_general_options['parent_menu_order'] == 'DESC') ? 'checked' : '' ?>> <?php _e('DESC', 'lime-catalog'); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>

<table class="form-table">
<h2><?php _e('Sidebar Sub Menu Options', 'lime-catalog'); ?></h2>
<p><?php _e('The following options affect how sub menu(s) are displayed on the frontend.', 'lime-catalog'); ?></p>
    <tbody>
        <tr>
            <th scope="row"><?php _e('Sub Menu order by', 'lime-catalog'); ?></th>
            <td>
            
<?php 

    // format sub menu order by
    if ( $lmctlg_general_options['sub_menu_order_by'] == 'modified' ) {
        $sub_menu_order_by = 'last modified';
    } elseif ( $lmctlg_general_options['sub_menu_order_by'] == 'rand' ) {
        $sub_menu_order_by = 'random';
    } else {
        $sub_menu_order_by = $lmctlg_general_options['sub_menu_order_by'];
    }

?>
            
                <select class="small-text" name="sub_menu_order_by" id="sub_menu_order_by">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_general_options['sub_menu_order_by'] ); ?>"><?php echo esc_attr( $sub_menu_order_by ); ?></option>
                    <option value="ID"><?php _e('ID', 'lime-catalog'); ?></option>
                    <option value="author"><?php _e('author', 'lime-catalog'); ?></option>
                    <option value="title"><?php _e('title', 'lime-catalog'); ?></option>
                    <option value="name"><?php _e('name', 'lime-catalog'); ?></option>
                    <option value="date"><?php _e('date', 'lime-catalog'); ?></option>
                    <option value="modified"><?php _e('last modified', 'lime-catalog'); ?></option>
                    <option value="rand"><?php _e('random', 'lime-catalog'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Sub Menu order', 'lime-catalog'); ?></th>
            <td>
                <p>
                    <input type="radio" value="<?php echo esc_attr( 'ASC' ); ?>" name="sub_menu_order" id="sub_menu_order_asc" <?php echo ($lmctlg_general_options['sub_menu_order'] == 'ASC') ? 'checked' : '' ?>> <?php _e('ASC', 'lime-catalog'); ?>
                </p>
                <p>
                    <input type="radio" value="<?php echo esc_attr( 'DESC' ); ?>" name="sub_menu_order" id="sub_menu_order_decs" <?php echo ($lmctlg_general_options['sub_menu_order'] == 'DESC') ? 'checked' : '' ?>> <?php _e('DESC', 'lime-catalog'); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>
    
    
    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->