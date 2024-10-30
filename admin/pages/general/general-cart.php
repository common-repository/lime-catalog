
<div id="tab_container">

	<?php 
	    
		/*
		echo '<pre>';
		print_r($lmctlg_cart_options);
		echo '</pre>';
		*/
		
        global $wp_query;
		
		$query_args = array(
		'post_type'        => 'page',
		'post_status'      => 'publish',
		'order'            => 'ASC', // 'ASC'
		'orderby'          => 'ID', // modified | title | name | ID | rand
		);
    
    ?>
    
<form method="post" action="" id="lmctlg-cart-options-form">

<input type="hidden" name="lmctlg-cart-options-form-nonce" value="<?php echo wp_create_nonce('lmctlg_cart_options_form_nonce'); ?>"/>

    <table class="form-table">
        <h2 class="padding-top-15"><?php _e('Shopping Cart', 'lime-catalog'); ?></h2>
        <tbody>
            <tr>
                <th scope="row"><?php _e('Enable Shopping Cart', 'lime-catalog'); ?></th>
                <td>
                    <input type="checkbox" value="1" name="enable_shopping_cart" id="enable_shopping_cart" <?php echo ($lmctlg_cart_options['enable_shopping_cart'] == '1') ? 'checked' : '' ?>>
                    <p class="description"><?php _e('Activate the ecommerce online store.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Cart Page', 'lime-catalog'); ?></th>
                <td>
                <select class="small-text" name="cart_page" id="cart_page">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_cart_options['cart_page'] ); ?>">
					<?php 
					$id_cart = $lmctlg_cart_options['cart_page']; 
					if ( $id_cart !== '0' ) {
					  echo esc_attr( get_the_title( $lmctlg_cart_options['cart_page'] ) ); 
					}
					?>
                    </option>
					<?php
					$cart_query = new WP_Query( $query_args );
					
					if( $cart_query->have_posts() ) {
						echo '<option value="0">&nbsp;</option>'; // default
						while ($cart_query->have_posts()) : $cart_query->the_post(); 
						
						$post_id_cart = get_the_ID();
						echo '<option value="' . esc_attr( $post_id_cart ) . '">' . esc_attr( get_the_title($post_id_cart) ) . '</option>';
						
						endwhile;
					
					} 
					// clean up after the query and pagination
					wp_reset_postdata(); 
                    ?>
                        </select>
                        <p class="description"><?php _e('Shopping Cart page. Shortcodes [lmctlg_cart] and [lmctlg_cart_totals] must be on this page.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Checkout Page', 'lime-catalog'); ?></th>
                <td>
                <select class="small-text" name="checkout_page" id="checkout_page">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_cart_options['checkout_page'] ); ?>">
					<?php 
					$id_checkout = $lmctlg_cart_options['checkout_page']; 
					if ( $id_checkout !== '0' ) {
					  echo esc_attr( get_the_title( $lmctlg_cart_options['checkout_page'] ) ); 
					}
					?>
                    </option>
					<?php
					$checkout_query = new WP_Query( $query_args );
					
					if( $checkout_query->have_posts() ) {
						echo '<option value="0">&nbsp;</option>'; // default
						while ($checkout_query->have_posts()) : $checkout_query->the_post(); 
						
						$post_id_checkout = get_the_ID();
						echo '<option value="' . esc_attr( $post_id_checkout ) . '">' . esc_attr( get_the_title($post_id_checkout) ) . '</option>';
						
						endwhile;
					
					} 
					// clean up after the query and pagination
					wp_reset_postdata(); 
                    ?>
                        </select>
                        <p class="description"><?php _e('Checkout page. Shortcodes [lmctlg_checkout_totals] and [lmctlg_checkout] must be on this page.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Terms Page', 'lime-catalog'); ?></th>
                <td>
                <select class="small-text" name="terms_page" id="terms_page">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_cart_options['terms_page'] ); ?>">
					<?php 
					$id_terms = $lmctlg_cart_options['terms_page']; 
					if ( $id_terms !== '0' ) {
					  echo esc_attr( get_the_title( $lmctlg_cart_options['terms_page'] ) ); 
					}
					?>
                    </option>
					<?php
					$terms_query = new WP_Query( $query_args );
					
					if( $terms_query->have_posts() ) {
						echo '<option value="0">&nbsp;</option>'; // default
						while ($terms_query->have_posts()) : $terms_query->the_post(); 
						
						$post_id_terms = get_the_ID();
						echo '<option value="' . esc_attr( $post_id_terms ) . '">' . esc_attr( get_the_title($post_id_terms) ) . '</option>';
						
						endwhile;
					
					} 
					// clean up after the query and pagination
					wp_reset_postdata(); 
                    ?>
                        </select>
                        <p class="description"><?php _e('Select your terms & conditions page. This page link will be visible on the checkout page.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Success Page', 'lime-catalog'); ?></th>
                <td>
                <select class="small-text" name="success_page" id="success_page">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_cart_options['success_page'] ); ?>">
                    <?php
					$id_success = $lmctlg_cart_options['success_page']; 
					if ( $id_success !== '0' ) {
					  echo esc_attr( get_the_title( $lmctlg_cart_options['success_page'] ) ); 
					}
					?>
                    </option>
					<?php
					$success_query = new WP_Query( $query_args );
					
					if( $success_query->have_posts() ) {
						echo '<option value="0">&nbsp;</option>'; // default
						while ($success_query->have_posts()) : $success_query->the_post(); 
						
						$post_id_success = get_the_ID();
						echo '<option value="' . esc_attr( $post_id_success ) . '">' . esc_attr( get_the_title($post_id_success) ) . '</option>';
						
						endwhile;
					
					} 
					// clean up after the query and pagination
					wp_reset_postdata(); 
					// You can use [lmctlg_order_receipt] shortcode on this page.
                    ?>
                        </select>
                        <p class="description"><?php _e('Buyers will be redirected to this page upon successful payments. You can use [lmctlg_order_receipt] shortcode on this page.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Order History Page', 'lime-catalog'); ?></th>
                <td>
                <select class="small-text" name="order_history_page" id="order_history_page">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_cart_options['order_history_page'] ); ?>">
                    <?php
					$id_order_history = $lmctlg_cart_options['order_history_page']; 
					if ( $id_order_history !== '0' ) {
					  echo esc_attr( get_the_title( $lmctlg_cart_options['order_history_page'] ) ); 
					}
					?>
                    </option>
					<?php
					$order_history_query = new WP_Query( $query_args );
					
					if( $order_history_query->have_posts() ) {
						echo '<option value="0">&nbsp;</option>'; // default
						while ($order_history_query->have_posts()) : $order_history_query->the_post(); 
						
						$post_id_order_history = get_the_ID();
						echo '<option value="' . esc_attr( $post_id_order_history ) . '">' . esc_attr( get_the_title($post_id_order_history) ) . '</option>';
						
						endwhile;
					
					} 
					// clean up after the query and pagination
					wp_reset_postdata(); 
                    ?>
                        </select>
                        <p class="description"><?php _e('Current user order history page. Shortcode [lmctlg_order_history] must be on this page.', 'lime-catalog'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Login Redirect Page', 'lime-catalog'); ?></th>
                <td>
                <select class="small-text" name="login_redirect_page" id="login_redirect_page">
                    <option selected="selected" value="<?php echo esc_attr( $lmctlg_cart_options['login_redirect_page'] ); ?>">
                    <?php
					
					$id_login_redirect = $lmctlg_cart_options['login_redirect_page']; 
					if ( $id_login_redirect !== '0' ) {
					  echo esc_attr( get_the_title( $lmctlg_cart_options['login_redirect_page'] ) ); 
					} else {
						_e('Dashboard', 'lime-catalog');
					}
					?>
                    </option>
					<?php
					$login_redirect_query = new WP_Query( $query_args );
					
					if( $login_redirect_query->have_posts() ) {
						//echo '<option value="0">&nbsp;</option>'; // default
						echo '<option value="0">' . __('Dashboard', 'lime-catalog') . '</option>'; // default,wp-admin/index.php
						while ($login_redirect_query->have_posts()) : $login_redirect_query->the_post(); 
						
						$post_id_login_redirect = get_the_ID();
						echo '<option value="' . esc_attr( $post_id_login_redirect ) . '">' . esc_attr( get_the_title($post_id_login_redirect) ) . '</option>';
						
						endwhile;
					
					} 
					// clean up after the query and pagination
					wp_reset_postdata(); 
                    ?>
                        </select>
                        <p class="description"><?php _e('Users will be redirected to this page upon successful login.', 'lime-catalog'); ?></p>
                </td>
            </tr>
        </tbody>
    </table>

    <?php submit_button(); ?>
</form>

</div><!--/ #tab_container-->