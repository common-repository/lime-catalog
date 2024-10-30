<div class="lime-single-item">	

<?php 

  // defaults
  $total = '0';
  $price_in_total = '0';
  // current user defaults
  $username    = '';
  $useremail   = '';
  $firstname   = '';
  $lastname    = '';
  $displayname = '';
  $userid      = '';
  
  $create_an_account   = '';
  $credit_card_details = '';
  $billing_details     = '';

  // cookie name
  $cart_totals_cookie_name = LMCTLG_Cookies::lmctlg_cart_totals_cookie_name();
  
  // check if cookie exist
  if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_totals_cookie_name ) === true ) 
  {	
			
	// read the cookie
	$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_totals_cookie_name, $default = '');
	$cart_totals = $cookie;
	
	/*
	echo '<pre>';
	print_r( $cookie );
	echo '</pre>';
	*/
	
	$arr_cart_totals = json_decode($cart_totals, true); // convert to array
	$obj_cart_totals = json_decode($cart_totals); // convert to object
	
	/*
	echo '<pre>';
	print_r( $arr_cart_totals );
	echo '</pre>';
	*/
	
	// if cart has contents
	if(count($obj_cart_totals)>0)
	{
		$subtotal = $obj_cart_totals->subtotal;
		$total    = $obj_cart_totals->total;
	}
  }
	
  // cookie name
  $cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();

  // check if cookie exist
  if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_items_cookie_name ) === true ) 
  {
	// read the cookie
	$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_items_cookie_name, $default = '');
	$cart_items = $cookie;
	/*
	echo '<pre>';
	print_r( $cookie );
	echo '</pre>';
	*/
	$arr_cart_items = json_decode($cart_items, true); // convert to array
	$obj_cart_items = json_decode($cart_items); // convert to object
    
	/*
	echo '<pre>';
	print_r( $arr_cart_items );
	echo '</pre>';
	*/
	
	// if cart has contents
	if(count($obj_cart_items)>0)
	{	
	
	  foreach($obj_cart_items as $key=>$value)
	  {
		//echo $value->item_id . '<br>'; 
		// Get the meta for all items
		$meta = get_post_meta( $value->item_id );
		
		/*
		echo '<pre>';
		print_r( $meta );
		echo '</pre>';
		*/
		
	  }
	  
	}
	
	
?>

<!-- cw-form start -->
<div class="cw-form cw-form-maxwidth">

<?php 
$loggedin = '1';
// check if user logged in, if not show login form
if ( ! is_user_logged_in() ) {
$loggedin = '0';
?>

<div id="lmctlg-returning-customer-div" class="cw-title lime-uppercase"><?php _e('Returning customer?', 'lime-catalog'); ?> <a id="lmctlg-toggle-login-form" href="/" onclick="return false;"><?php _e('Click here to login', 'lime-catalog'); ?></a></div>

<div id="lmctlg-show-hide-login-form"><!-- jQuery -->
<?php echo do_shortcode('[lmctlg_login_form]'); // login form ?>
</div>

<?php 
} // end is_user_logged_in
else {
	
  // if logged in get current user data
  $current_user = wp_get_current_user();
  
  /*
  echo '<pre>';
  print_r( $current_user );
  echo '</pre>';
  */
  
  $username    = $current_user->user_login;
  $useremail   = $current_user->user_email;
  $firstname   = $current_user->user_firstname;
  $lastname    = $current_user->user_lastname;
  $displayname = $current_user->display_name;
  $userid      = $current_user->ID;
  
}



?>

<div id="lmctlg-payment-methods-holder">

<hr class="lime-hr">

<div class="cw-title lime-uppercase"><?php _e('Select Payment Method', 'lime-catalog'); ?></div>

<div class="r-row lime-margin-bottom-15">

<?php 

	// get options
	$lmctlg_payment_gateway_options = get_option('lmctlg_payment_gateway_options');
	$defaultgateway = $lmctlg_payment_gateway_options['default_payment_gateway']; // option
	
    $payment_gateways = LMCTLG_Payment_Gateways::lmctlg_payment_gateways();
	$json = json_encode( $payment_gateways ); // convert array to json

	$gateways_obj   = json_decode( $json ); // Translate into an object
	$gateways_array = json_decode( $json, true ); // Translate into an array
	
	// if has contents
	if(count($gateways_obj)>0)
	{	
	
	  echo '<div class="c-col c-col-12">';
	  
	  foreach( $gateways_obj as $gateway => $value )
	  {
		 
		 $checked = '';
		 if ( $defaultgateway == $gateway ) {
			 
			$checked = 'checked="checked"';
			
			$create_an_account   = $value->create_an_account;   // return 0 or 1
			$credit_card_details = $value->credit_card_details; // return 0 or 1
			$billing_details     = $value->billing_details;     // return 0 or 1
			
		 }
		 
		// check if option exist
		if( get_option('lmctlg_gateway_' . $gateway . '_options') ){
		   // get options for gateways
		   $gateways_options = get_option('lmctlg_gateway_' . $gateway . '_options');
		   // check if gateway enabled
		   if ( $gateways_options['lmctlg_' . $gateway . '_enabled'] == '1' ) {
			   
			  echo '<label style="width: auto; margin-right:18px;">';
			  echo '<input type="radio" class="lmctlg_payment_gateway_radio" name="lmctlg_payment_gateway_radio" id="' . esc_attr( $gateway ) . '" value="' . esc_attr(  $gateway ) . '" ' . esc_attr( $checked ) . ' >';
			  echo '<span class="lbl padding-8">' . esc_attr( $gateways_options['lmctlg_' . $gateway . '_title'] ) . '</span>';
			  echo '</label>';
			  
			  echo '<input type="hidden" name="' . esc_attr( $gateway ) . '_create_an_account" id="' . esc_attr( $gateway ) . '_create_an_account" value="' . esc_attr(  $value->create_an_account ) . '"/>';
			  echo '<input type="hidden" name="' . esc_attr( $gateway ) . '_credit_card_details" id="' . esc_attr( $gateway ) . '_credit_card_details" value="' . esc_attr( $value->credit_card_details ) . '"/>';
			  echo '<input type="hidden" name="' . esc_attr( $gateway ) . '_billing_details" id="' . esc_attr( $gateway ) . '_billing_details" value="' . esc_attr( $value->billing_details ) . '"/>';

		   }
		}
		  
	  }
	  
	  
	  // gateway description
	  foreach( $gateways_obj as $gateway_desc => $value_desc )
	  { 
		// check if option exist
		if( get_option('lmctlg_gateway_' . $gateway_desc . '_options') ){
		   // get options for gateways
		   $gateways_options = get_option('lmctlg_gateway_' . $gateway_desc . '_options');
		   // check if gateway enabled
		   if ( $gateways_options['lmctlg_' . $gateway_desc . '_enabled'] == '1' ) {
			  
			  // get gateway option description
			  $description = $gateways_options['lmctlg_' . $gateway_desc . '_description'];
			  
			  if ( $defaultgateway == $gateway_desc ) {
			  // output gateway description
			  echo '<div id="default_gateway_description" class="lime-checkout-gateway-description">' . esc_attr( stripslashes_deep( $description ) ) . '</div>';
			  }
			  
			  echo '<div style="display:none;" id="' . esc_attr( $gateway_desc ) . '_gateway_description" class="lime-checkout-gateway-description">' . esc_attr( stripslashes_deep( $description ) ) . '</div>';

		   }
		}
		  
	  }
	 
	  
	  echo '</div>';
	  
	}

	/*
	echo '<pre>';
	print_r( $gateways_array );
	echo '</pre>';
	*/

?>

</div>
</div> <!--/ lmctlg-payment-methods-holder -->

<form id="lmctlg-checkout-form" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="lmctlg-checkout-form-nonce" value="<?php echo wp_create_nonce('lmctlg_checkout_form_nonce'); ?>"/>
<?php 
// for third party gateways - current gateway
echo '<input type="hidden" class="lmctlg-current-gateway" name="lmctlg_current_gateway" id="lmctlg_current_gateway" value="' . esc_attr( $defaultgateway ) . '"/>';
// default gatway ( db option)
echo '<input type="hidden" class="lmctlg_default_gateway_class" name="lmctlg_default_gateway" id="lmctlg_default_gateway" value="' . esc_attr( $defaultgateway ) . '"/>';
?>

<div class="lmctlg-personal-details-fields">

<div class="textlabel-forms-bold lime-uppercase"><?php _e('Personal Details', 'lime-catalog'); ?></div>
<fieldset>

<div class="r-row">

  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'First Name', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-user"></i>
      <input type="text" id="lmctlg_first_name" name="lmctlg_first_name" value="<?php echo esc_attr( $firstname ); ?>" required >
    </div>
  </div>
  
  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'Last Name', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-user"></i>
      <input type="text" id="lmctlg_last_name" name="lmctlg_last_name" value="<?php echo esc_attr( $lastname ); ?>" required >
    </div>
  </div>
  
</div>

<div class="r-row">

  <div class="c-col c-col-7">
    <label for="textinput"><?php _e( 'E-mail', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-envelope"></i>
      <input id="lmctlg_user_email" name="lmctlg_user_email" type="email" value="<?php echo esc_attr( $useremail ); ?>" required >
    </div>
  </div>
  
  <div class="c-col c-col-5">
    <label for="textinput"><?php _e( 'Phone', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-earphone"></i>
      <input type="text" id="lmctlg_phone" name="lmctlg_phone" value="">
    </div>
  </div>
  
</div>

<div class="r-row">

  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'Company', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-book"></i>
      <input type="text" id="lmctlg_company" name="lmctlg_company" value="">
    </div>
  </div>
  
</div>

</fieldset>

</div> <!--/ lmctlg-personal-details-fields -->


<?php 
// check if user logged in, if not show create an account fields
if ( ! is_user_logged_in() ) {
?>

<div class="lmctlg-create-an-account-fields">

<div class="textlabel-forms-bold lime-uppercase"><?php _e('Create an Account', 'lime-catalog'); ?></div>

<fieldset>

<div class="r-row">

  <div class="c-col c-col-8">
    <label for="textinput"><?php _e( 'Username', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-user"></i>
      <input type="text" id="lmctlg_username" name="lmctlg_username" value="" required >
    </div>
  </div>
  
</div>

<div class="r-row">

  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'Password', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-lock"></i>
      <input id="lmctlg_user_pass" name="lmctlg_user_pass" type="password" autocomplete="off" value="" required >
    </div>
  </div>
  
  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'Password Again', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-lock"></i>
      <input id="lmctlg_user_pass_again" name="lmctlg_user_pass_again" type="password" autocomplete="off" value="" required >
    </div>
  </div>
  
</div>

</fieldset>

</div> <!--/ lmctlg-create-an-account-fields -->

<?php 
} // end is_user_logged_in

if ( '1' !== $credit_card_details ) {
	$card_details_fields = 'style="display:none;"';
	$card_disabled = 'disabled="disabled"';
} else {
	$card_disabled = '';
	$card_details_fields = '';
}
?>

<div class="lmctlg-credit-card-details-fields" <?php echo $card_details_fields; ?> >

<div class="textlabel-forms-bold lime-uppercase"><?php _e('Credit Card Details', 'lime-catalog'); ?></div>
<fieldset>

<div class="r-row">

  <div class="c-col c-col-7">
    <label for="textinput"><?php _e('Card Number', 'lime-catalog'); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-calendar"></i>
      <input id="lmctlg_card_number" class="lmctlg-card-number" type="text" pattern="[0-9]{13,16}" size="20" autocomplete="off" value="" required <?php echo $card_disabled; ?> />
    </div>
  </div>
  
  <div class="c-col c-col-5">
    <label for="textinput"><?php _e('Card Expiry Date', 'lime-catalog'); ?> </label>
    <div class="c-col-no-padding c-col-5">
    <div class="no-icon">
          <select id="lmctlg_card_expiry_month" class="lmctlg-card-expiry-month" required <?php echo $card_disabled; ?> >
            <option value="" selected="selected">&nbsp;</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
      </div>
    </div>
    <div class="c-col-no-padding c-col-7"> 
    <div class="no-icon">
      <select id="lmctlg_card_expiry_year" class="lmctlg-card-expiry-year" required <?php echo $card_disabled; ?> >
          <option selected="selected" value="">&nbsp;</option>
            <?php 
			$year = date("Y");
			echo "<option value='" . esc_attr( $year ) . "'>" . esc_attr( $year ) . "</option>";
            $yearslist = $year;
            $x5=1;
            $value5 = $yearslist;
            while($x5<=35)
              {
               $value5 = $value5 + 1;
              echo "<option value='" . esc_attr( $value5 ) . "'>" . esc_attr( $value5 ) . "</option>";
              $x5++;
              } 
            ?>            
      </select>
    </div>
    </div>
  </div>
  
</div>

<div class="r-row">

  <div class="c-col c-col-8">
    <label for="textinput"><?php _e( 'Name on the Card', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-lock"></i>
      <input id="lmctlg_name_on_the_card"  class="lmctlg-name-on-the-card" type="text" autocomplete="off" value="" required <?php echo $card_disabled; ?> >
    </div>
  </div>
  
  <div class="c-col c-col-4">
    <label for="textinput"><?php _e( 'CVC/CVV', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-indent-left"></i>
      <input id="lmctlg_card_cvc" class="lmctlg-card-cvc" type="password" autocomplete="off" maxlength="6" pattern="[0-9]{3,4}" value="" required <?php echo $card_disabled; ?> />
    </div>
  </div>
  
</div>

<div class="r-row">
  
  <div class="c-col c-col-6">
  <div class="no-icon">
  <img class="cards-imgs" src="<?php echo esc_url( plugins_url( '/lime-catalog/assets/images/credit-cards.png') ); ?>" alt="Secure Payments" />
  </div>
  <div class="ssl-license-text"><?php _e('SSL encrypted secure payments.', 'lime-catalog'); ?></div>
  </div>
  
  <div class="c-col c-col-6">
  
  </div>

</div>

</fieldset>

</div> <!--/ lmctlg-credit-card-details-fields -->

<?php 

if ( '1' !== $billing_details ) {
	$billing_details_fields = 'style="display:none;"';
	$billing_disabled = 'disabled="disabled"';
} else {
	$billing_disabled = '';
	$billing_details_fields = '';
}
?>
<div class="lmctlg-billing-details-fields" <?php echo $billing_details_fields; ?> >

<div class="textlabel-forms-bold lime-uppercase"><?php _e('Billing Details', 'lime-catalog'); ?></div>

<fieldset>

<div class="r-row">

  <div class="c-col c-col-6">
    <label for="textinput"><?php _e('Country', 'lime-catalog'); ?> </label>
    <div class="no-icon">
          <select id="lmctlg_billing_country" name="lmctlg_billing_country" required <?php echo $billing_disabled; ?> >
            <option value="" selected="selected"><?php _e('Please Select ...', 'lime-catalog'); ?></option>     
<?php 

	  // Countries
	  $countries = lmctlg_country_list(); // function
	  foreach($countries as $countrycode=>$country)
	  {
		  
		$states = lmctlg_get_states( $countrycode ); // function
		
		// if returns empty country do not have states
		if (empty($states)) {
			$dropdisplay = '0';
		} else {
			$dropdisplay = '1';
		}
		
		echo '<option data-billing-country-code="' . esc_attr( $countrycode ) . '" data-billing-state-drop-display="' . esc_attr( $dropdisplay ) . '" value="' . esc_attr( $countrycode ) . '">' . esc_attr( $country ) . '</option>';  

	  }
	 
?>
            
          </select>
    </div>
  </div>
  
  <div class="c-col c-col-6">
   
   <div id="lmctlg-billing-country-state-field">
    <label for="textinput"><?php _e('State', 'lime-catalog'); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-tree-deciduous"></i>
      <input type="text" id="lmctlg_billing_state" name="lmctlg_billing_state" value="" <?php echo $billing_disabled; ?> > <!-- required -->
    </div> 
   </div>
   
  <div id="lmctlg-billing-country-state-dropdown" style="display:none;" > <!-- style="display:none;" -->
    <label for="textinput"><?php _e('State', 'lime-catalog'); ?> </label>
    <div class="no-icon">
    
<?php 

	echo '<select id="lmctlg_billing_state" name="lmctlg_billing_state" ' . $billing_disabled . ' >'; // required
	echo '<option value="" selected="selected">' . __('Please Select ...', 'lime-catalog') . '</option>';
			
	  // Countries
	  $getcountries = lmctlg_country_list(); // function
	  foreach($getcountries as $getcountrycode=>$getcountry)
	  {
		
		if ( $getcountrycode ) {
			
			$getstates = lmctlg_get_states( $getcountrycode ); // function
			
			  foreach($getstates as $key=>$value)
			  {
				 // convert accented chars to html
				$output = htmlentities($value, 0, "UTF-8");
				if ($output == "") {
					$output = htmlentities(utf8_encode($value), 0, "UTF-8");
				}
				//$trusted_value = lmctlg_replace_accents($value);
				echo '<option id="lmctlg_billing_states_' . esc_attr( $getcountrycode ) . '" value="' . esc_attr($output) . '">' . esc_attr($output) . '</option>'; 
			  }
			  
		} 
		

	  }
	  
	echo '</select>';
	  

?>
     
    </div>
  
   </div> 
    
     
  </div>

</div> 

<div class="r-row">

  <div class="c-col c-col-4">
    <label for="textinput"><?php _e('City', 'lime-catalog'); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-tree-deciduous"></i>
      <input type="text" id="lmctlg_billing_city" name="lmctlg_billing_city" autocomplete="off" value="" required <?php echo $billing_disabled; ?> >
    </div>
  </div>
  
  <div class="c-col c-col-8">
    <label for="textinput"><?php _e('Street Addr. 1', 'lime-catalog'); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-equalizer"></i>
      <input type="text" id="lmctlg_billing_addr_1" name="lmctlg_billing_addr_1" value="" autocomplete="off" required <?php echo $billing_disabled; ?> >
    </div>
  </div>
  
</div>

<div class="r-row">

  <div class="c-col c-col-8">
    <label for="textinput"><?php _e('Street Addr. 2', 'lime-catalog'); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-equalizer"></i>
      <input type="text" id="lmctlg_billing_addr_2" name="lmctlg_billing_addr_2" value="" autocomplete="off" <?php echo $billing_disabled; ?> >
    </div>
  </div>

  <div class="c-col c-col-4">
    <label for="textinput"><?php _e('Postcode/Zip', 'lime-catalog'); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-link"></i>
      <input type="text" id="lmctlg_billing_zip" name="lmctlg_billing_zip" value="" autocomplete="off" required <?php echo $billing_disabled; ?> >
    </div>
  </div>
  
</div>

</fieldset>

</div> <!--/ lmctlg-billing-details-fields -->

<div class="cw-footer">
  <div class="formsubmit">
    <div class="r-row">
      <div class="c-col c-col-6"> 
         &nbsp;<div class="lmctlg-loading-img"></div>
         </div>
       <div class="c-col c-col-6"> 
        <button style="width:100%;" class="btn-lime btn-lime-md btn-lime-orange lime-margin-top-bottom-15" type="submit" id="lmctlg-checkout-form-submit" name="lmctlg-checkout-form-submit">
          <i class="glyphicon glyphicon-log-in"></i> &nbsp; <?php _e('Place Order', 'lime-catalog'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

</form>

<?php 
// get options
$lmctlg_cart_options = get_option('lmctlg_cart_options');

$page_id = $lmctlg_cart_options['terms_page'];
// check if value not 0 
if ( $page_id !== '0' || ! empty($page_id) ) {
	// get page link by post id
	$page_link  = get_permalink( $page_id );
	$page_title = get_the_title( $page_id );
?>

<div id="lmctlg-terms-link" class="footer-extra-data margin-top-10">
<?php _e('By clicking the "Place Order" button, you agree with our ', 'lime-catalog'); ?> 
<a href="<?php echo esc_url( $page_link ); ?>"><?php echo esc_attr( $page_title ); ?></a>
</div>

<?php 
}
?>

</div>
<!-- cw-form end -->

<!-- jQuery payment gateway(s) messages -->
<div class="lmctlg-payment-gateway-messages"></div> 

<!-- jQuery -->
<div class="show-checkout-form-return-data"></div>

<?php 	
	
  }

?>

</div><!--/ lime-single-item -->