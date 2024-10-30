<?php 

// check if user logged in, if not show forms
if ( ! is_user_logged_in() ) {
    echo do_shortcode('[lmctlg_login_manager role="' . $atts['role'] . '"]'); // login manager
} else {
	
    echo do_shortcode('[lmctlg_order_history]'); // order history
	
	echo '<br><br>';
	
} // else end
?>