<?php 

	// check if user logged in
	if ( ! is_user_logged_in() ) {
		
?>		
<div class="lime-log-reg-buttons">
 <a onclick="return false;" href="/" lime-form-type="login" class="btn-lime btn-lime-sm btn-lime-light-green"><?php _e( 'Login', 'lime-catalog' ); ?></a>
 <a onclick="return false;" href="/" lime-form-type="register" class="btn-lime btn-lime-sm btn-lime-light-green"><?php _e( 'Register', 'lime-catalog' ); ?></a>
 <a onclick="return false;" href="/" lime-form-type="forgot_pw" class="btn-lime btn-lime-sm btn-lime-light-green"><?php _e( 'Forgot Password', 'lime-catalog' ); ?></a>
 </div>
 
<div class="lime-display-login-form">
<?php echo do_shortcode('[lmctlg_login_form]'); // login form ?>
</div>

<div class="lime-display-register-form">
<?php echo do_shortcode('[lmctlg_register_form title="Create an Account" role="' . $atts['role'] . '"]'); // register form, roles= lime_subscriber or lime_customer ?>
</div>

<div class="lime-display-forgot-pw-form">
<?php echo do_shortcode('[lmctlg_forgot_pw_form]'); // forgot password form ?>
</div>
<?php 

    } // end if logged in
	else {
		// redirect to page if login_redirect_page defined in Admin/Settings/General/Shopping Cart
		// get options
		$lmctlg_cart_options = get_option('lmctlg_cart_options');
		$redirect_page_id = $lmctlg_cart_options['login_redirect_page']; // redirect page id
		
		// redirect to page if exist
		if ( $redirect_page_id !== '0' && ! empty($redirect_page_id)  ) {
			// get page link by id
			$page_link = get_permalink( $redirect_page_id );
			// if exist redirect to page
			wp_redirect( $page_link, 302 );
			exit();
		} else {
			// default
			$login_redirect_url = admin_url('index.php'); // default,wp-admin/index.php
			wp_redirect( $login_redirect_url, 302 );
			exit();
		}
		
	}

?>