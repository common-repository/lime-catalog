jQuery(document).ready(function($) {
								
		// This will add thousand separators while retaining the decimal part of a given number
		// 2056776401.50 = 2,056,776,401.50
		function lmctlg_format_price(n) {
		  n = n.toString()
		  while (true) {
			var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
			if (n == n2) break
			n = n2
		  }
		  return n
		};
	
		// output 1.234.567,89
		function lmctlg_format_price_DE (n) {
			return n
			   //.toFixed(2) // always two decimal digits
			   .replace(".", ",") // replace decimal point character with ,
			   .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
		};
	
		// thousand separator
		function lmctlg_thousand_separator(s) {
			return (""+s)
				.replace(/(\d+)(\d{3})(\d{3})$/  ,"$1 $2 $3" )
				.replace(/(\d+)(\d{3})$/         ,"$1 $2"    )
				.replace(/(\d+)(\d{3})(\d{3})\./ ,"$1 $2 $3.")
				.replace(/(\d+)(\d{3})\./        ,"$1 $2."   )
			;
		};
	
		// round number
		function round2Fixed(value) {
		  value = +value;
		
		  if (isNaN(value))
			return NaN;
		
		  // Shift
		  value = value.toString().split('e');
		  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + 2) : 2)));
		
		  // Shift back
		  value = value.toString().split('e');
		  return (+(value[0] + 'e' + (value[1] ? (+value[1] - 2) : -2))).toFixed(2);
		}
	
		function roundPrice(value, exp) {
		  if (typeof exp === 'undefined' || +exp === 0)
			return Math.round(value);
		
		  value = +value;
		  exp = +exp;
		
		  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
			return NaN;
		
		  // Shift
		  value = value.toString().split('e');
		  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));
		
		  // Shift back
		  value = value.toString().split('e');
		  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
		};

	
		// calculate total
		function calculatetotal() {
			
			var sum = 0;
			jQuery(".input-lmctlg-single-item-total").each(function() {      
				sum += +this.value;
			});
			return sum;
		};
	
	/*
	jQuery(document).ready(function($) {
		// not in use
		function lmctlg_set_cart_cookie() {
			
			if (jQuery.cookie('demo_cookie') ) { jQuery.cookie( 'demo_cookie', null) }
			// save cookie
			jQuery.cookie('demo_cookie', 'hello', {
								   expires : 10,           //expires in 10 days
								   path    : '/',
								   domain  : 'wp-test.codeweby.com',
								   secure  : false          //If set to true the secure attribute of the cookie
								});
			
			  alert('You have set the cookie: '+ jQuery.cookie('demo_cookie'));
		};
	});
	*/
	
		// get cookie
		function lmctlg_get_Cookie(name) {
			var dc = document.cookie;
			var prefix = name + "=";
			var begin = dc.indexOf("; " + prefix);
			if (begin == -1) {
				begin = dc.indexOf(prefix);
				if (begin != 0) return null;
			}
			else
			{
				begin += 2;
				var end = document.cookie.indexOf(";", begin);
				if (end == -1) {
				end = dc.length;
				}
			}
			return unescape(dc.substring(begin + prefix.length, end));
		}; 
	
		// Price Options select field on change - DOM ready
		jQuery(".lmctlg-payment-buttons-form-class").on("change", ".lmctlg-price-options", function (event) {
																								  
			var itemid   = jQuery(this).find(":selected").attr("lmctlg-data-item-id");
			//alert(itemid);
			//alert( $(this).find(":selected").val() );
			var item_price = jQuery(this).find(":selected").attr("lmctlg-data-price-option");
			var option_id  = jQuery(this).find(":selected").val(); // selected option id
			
			// update
			jQuery('#lmctlg_payment_button_' + itemid + ' input.lmctlg_item_price_class').val(item_price); // update hidden item price
			jQuery('#lmctlg_payment_button_' + itemid + ' span.lmctlg-item-price').html(item_price); // update public item price	
			
		});
	
		// Payment Buttons Process - Add to cart, Buy Now, Pay Now etc.
		jQuery('.lmctlg-payment-buttons-form-class').submit(paymentbuttonsSubmit);
		
		function paymentbuttonsSubmit(){
		//alert('clicked');
		// empty div before process
		jQuery('.show-return-data').empty();	
		
		  var lmctlg_cart_items_cookie_name = jQuery('.lmctlg_cart_items_cookie_name_class').val();
		  //alert(cart_items_cookie_name);
		
		  var form_Data = jQuery(this).serialize();
		  //alert(form_Data);
		  
		  var itemid   = jQuery(this).find("#lmctlg_item_id").val();
		  //alert(itemid);
		  
		  // shopping basket - check if cookie exist
		  var cartitems = lmctlg_get_Cookie(lmctlg_cart_items_cookie_name); // get cookie
		
		  if (cartitems == null) {
			// do cookie doesn't exist stuff;
			//alert('no cookie');
			
			// no cookie so add 1 item to basket
			jQuery('span.lime-shopping-basket-items').html('1');
			
		  }
		  else {
			// do cookie exists stuff
			//alert('have cookie');
			
			var cartArray = JSON.parse(cartitems); // json decode
			
			// look for array key - itemid 
			if( itemid in cartArray ) {
			  //alert('array key exist');	
			} else {
			  //alert('array key NOT exist');
			  // not in array so update shopping basket by 1
			  
			  // get value
			  var basket = parseInt( jQuery('.input-lmctlg-basket-items').val() ); // parseInt for sum values
			  //alert(basket);
			  var basketnew = basket + 1;
			  jQuery('span.lime-shopping-basket-items').html( basketnew );
			}
			
		  }
		  
		  
		  jQuery.ajax({
			type:"POST",
			url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
			data: {action: 'lmctlg_payment_buttons_process', formData:form_Data},
				success:function(data){
					
					//jQuery('.show-return-data').show().prepend( data );
					// fade out
					//$('.show-return-data').delay(3000).fadeOut(800);
					
					jQuery('#lmctlg_payment_button_' + itemid + ' #lmctlg-payment-button-1').hide();
					jQuery('#lmctlg_payment_button_' + itemid + ' #lmctlg-payment-button-2').show();
				
				}
		  });
		
		
		return false;
		};
	
		// remove from cart
		jQuery('.lmctlg-remove-from-cart-form').submit(removefromcartSubmit);
		
		function removefromcartSubmit(){
		
		// empty div before process
		jQuery('.show-return-data').empty();
		
		  var form = jQuery(this); //Store the context of this in a local variable 
		
		  var formData = jQuery(this).serialize();
		  //alert(formData);
		  
		  var thousandSeparator  = jQuery("input.input-lmctlg-thousand-separator").val();
		
		  jQuery.ajax({
			type:"POST",
			url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
			data: {action: 'lmctlg_remove_from_cart_form_process', formData:formData},
				success:function(data){
	
					var itemid  = form.attr('data-item-id');
					//alert(itemid);
					// remove closest row
					form.closest("tr").remove();
					
					// show return data
					//jQuery('.show-return-data').show().prepend( data );
					
					// calculate total
					var itemstotal = calculatetotal(); // .toFixed(2) Returns "10.80"
					
					var itemstotalfixed = itemstotal.toFixed(2); // .toFixed(2) Returns "10.80"
					
					// format price
					if ( thousandSeparator == ',' ) {
						var itemstotalFormatted = lmctlg_format_price(itemstotalfixed); // 4,567,354.68
					} else if ( thousandSeparator == '.' ) {
						var itemstotalFormatted = lmctlg_format_price_DE(itemstotalfixed);  // 4.567.354,68
					}
					
					// update total
					jQuery('.html-lmctlg-items-price-in-total span.lmctlg-item-price').html(itemstotalFormatted);
					// update input
					jQuery('input.input-lmctlg-items-price-in-total').val(itemstotalfixed);
				
				}
		  });
		
		return false;
		};
	
		// update cart
		jQuery('.lmctlg-update-cart').click(function() {
	
			var jsonObj = [];
			
			var thousandSeparator  = jQuery("input.input-lmctlg-thousand-separator").val();
			//alert(thousandSeparator);
			
			// jquery loop through each rows
			jQuery("#lmctlg-cart-table tr.lmctlg-cart-item").each(function() {
																		   
				var $this = jQuery(this);
				
				var itemid          = $this.find("input.input-lmctlg-item-id").val();
				var price           = $this.find("input.input-lmctlg-item-price").val();
				var itemname        = $this.find("input.input-lmctlg-item-name").val();
				var downloadable    = $this.find("input.input-lmctlg-item-downloadable").val();
				var quantity        = $this.find("input.input-lmctlg-item-quantity").val();
				var price_option_id = $this.find("input.input-lmctlg-price-option-id").val();
				
				// create array
				cartitem = {}
				cartitem ["item_id"] = itemid;
				cartitem ["item_price"] = price;
				cartitem ["item_name"] = itemname;
				cartitem ["item_downloadable"] = downloadable;
				cartitem ["item_quantity"] = quantity;
				cartitem ["price_option_id"] = price_option_id;
				jsonObj.push(cartitem);
				
				// calculate single row item price in total
				var singleitempriceintotal = price * quantity;
				
				var singleitempriceintotalfixed = singleitempriceintotal.toFixed(2); // .toFixed(2) Returns "10.80"
				
				// round value
				//var singleitempriceintotalrounded = round2Fixed(singleitempriceintotal);
				//var singleitempriceintotalrounded = roundPrice(singleitempriceintotal, 1).toFixed(2); // Returns "10.80"
				
				// format price
				if ( thousandSeparator == ',' ) {
					var singleitempriceintotalFormatted = lmctlg_format_price(singleitempriceintotalfixed); // 4,567,354.68
				} else if ( thousandSeparator == '.' ) {
					var singleitempriceintotalFormatted = lmctlg_format_price_DE(singleitempriceintotalfixed); // 4.567.354,68
				}
				
				// update each single total
				$this.find("div.html-lmctlg-single-item-total span.lmctlg-item-price").html(singleitempriceintotalFormatted);
				
				// update each input single total
				$this.find("input.input-lmctlg-single-item-total").val(singleitempriceintotalfixed);
				
			});
			
			// calculate total
			var itemstotal = calculatetotal();
			
			var itemstotalfixed = itemstotal.toFixed(2); // .toFixed(2) Returns "10.80"
	
			// round value
			//var itemstotalrounded = roundPrice(itemstotal, 1).toFixed(2); // Returns "10.80"
			
			// format price
			if ( thousandSeparator == ',' ) {
				var itemstotalFormatted = lmctlg_format_price(itemstotalfixed); // 4,567,354.68
			} else if ( thousandSeparator == '.' ) {
				var itemstotalFormatted = lmctlg_format_price_DE(itemstotalfixed); // 4,567,354.68
			}
			
			// update total
			jQuery('.html-lmctlg-items-price-in-total span.lmctlg-item-price').html(itemstotalFormatted);
			
			// remove commas
			//var itemstotalhidden = itemstotalFormatted.replace(/,/g, '');
			
			// update input
			jQuery('input.input-lmctlg-items-price-in-total').val(itemstotalfixed);
			
			
			  // then to get the JSON string
			  var jsonString = JSON.stringify(jsonObj);
			  // update cookie
			  jQuery.ajax({
				type:"POST",
				url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
				data: {action: 'lmctlg_update_cart_process', formData:jsonString},
					success:function(data){		
						// show return data
						jQuery('.show-update-button-return-data').show().prepend( data );
					}
			  });
			  return false;
			
		});
	
		// grid - small or large and list view
		jQuery('.lime-grid-buttons a').on('click', function(event){
			event.preventDefault();
			
			var setview = jQuery(this).attr("lime-grid-data-id");
			//alert(setview);
			
			if ( setview == 'normal' ) {
				var itemsview = 'lime-item-box-grid columns-3'; // three colums view
			} else if ( setview == 'large' ) {
				var itemsview = 'lime-item-box-grid columns-2'; // two colums view
			} else if ( setview == 'list' ) {
				var itemsview = 'lime-item-box-list-view'; // list view
			} else {
				// default
				var itemsview = 'lime-item-box-grid columns-3'; // three colums view
			}
			
			jQuery('#set-lime-catalog-grid-view').fadeOut(500, function(){
				
				jQuery("#set-lime-catalog-grid-view").attr('class', ''); // clear attr
				jQuery("#set-lime-catalog-grid-view").addClass( itemsview ); // add class								
													
				jQuery('#set-lime-catalog-grid-view').fadeIn(500);
				
			});
			
			// button set active
			jQuery('.lime-grid-buttons li a').removeClass("btn-lime btn-lime-sm btn-lime-grey lime-float-right active");
			jQuery(this).addClass("btn-lime btn-lime-sm btn-lime-grey lime-float-right active");
			
			  // set cookie
			  jQuery.ajax({
				type:"POST",
				url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
				data: {action: 'lmctlg_items_view_process', itemsview:itemsview},
					success:function(data){		
						// show return data
						//jQuery('.show-items-view-return-data').show().prepend( data );
					}
			  });
			  return false;
	
		});
	
		// Shopping Cart Checkout - payment method on change
		jQuery('.lmctlg_payment_gateway_radio').on('change', function() {
			
			var gateway = this.value;
			//alert(gateway);
			
			// for third party gateways - update current gateway hidden field value
			jQuery('.lmctlg-current-gateway').val(gateway);
			
			// gateway description
			jQuery( "#default_gateway_description" ).hide();
			jQuery( ".lime-checkout-gateway-description" ).hide();
			jQuery( "#" + gateway + "_gateway_description" ).show();
			
			// update gateway hidden field
			jQuery('input.lmctlg_default_gateway_class').val(gateway);
			
			// get fields
			//var personal_details    = jQuery('#' + gateway + '_personal_details').val();    // return 0 or 1 not in use
			var create_an_account   = jQuery('#' + gateway + '_create_an_account').val();   // return 0 or 1
			var credit_card_details = jQuery('#' + gateway + '_credit_card_details').val(); // return 0 or 1
			var billing_details     = jQuery('#' + gateway + '_billing_details').val();     // return 0 or 1
			
			if (credit_card_details == '1') {
				jQuery( ".lmctlg-credit-card-details-fields" ).show();
				// enable credit card details fields
				jQuery(".lmctlg-credit-card-details-fields").find("input,select").removeAttr('disabled'); // disable all inputs and selects in div
			} else {
				// disable credit card details fields
				jQuery(".lmctlg-credit-card-details-fields").find("input,select").attr("disabled", "disabled"); // disable all inputs and selects in div
				jQuery( ".lmctlg-credit-card-details-fields" ).hide();
			}
			
			if (billing_details == '1') {
				jQuery( ".lmctlg-billing-details-fields" ).show();
				// enable credit card details fields
				jQuery(".lmctlg-billing-details-fields").find("input,select").removeAttr('disabled'); // disable all inputs and selects in div
			} else {
				// disable credit card details fields
				jQuery(".lmctlg-billing-details-fields").find("input,select").attr("disabled", "disabled"); // disable all inputs and selects in div
				jQuery( ".lmctlg-billing-details-fields" ).hide();
			}
			
			
		});
	
		// Shopping Cart Checkout - Login Form toggle
		jQuery( "#lmctlg-show-hide-login-form" ).hide(); // default
		jQuery('#lmctlg-toggle-login-form').on('click', function() {
			  jQuery('#lmctlg-show-hide-login-form').fadeToggle(600); //.slideToggle('slow');
		});
	
		// Shopping Cart Checkout - Login Form Process (json)
		jQuery('#lmctlg-login-form').submit(loginformSubmit);
		
		function loginformSubmit(){
		
		// empty div before process
		jQuery('.show-login-form-return-data').empty();
		
		  var logformData = jQuery(this).serialize();
		  //alert(logformData);
		
		  jQuery.ajax({
			type:"POST",
			dataType: 'json',
			url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
			data: {action: 'lmctlg_login_form_process', formData:logformData},
				success:function(data){
					
					// returns json
					jQuery('.show-login-form-return-data').show().prepend( data.message );
					// fade out
					jQuery('.show-login-form-return-data').delay(5000).fadeOut(1600);
					
					// success - redirect (refress)
					if (data.loggedin == true){
						document.location.href = lmctlg_ajax_shopping_cart.lmctlg_login_redirect_url; // specified at wp_localize_script
					}
				
				}
		  });
		
		return false;
		};
	
		// Register Form Process (json)
		jQuery('#lmctlg-register-form').submit(registerformSubmit);
		
		function registerformSubmit(){
		
		// empty div before process
		jQuery('.show-register-form-return-data').empty();
		
		  var regformData = jQuery(this).serialize();
		  //alert(regformData);
		
		  jQuery.ajax({
			type:"POST",
			dataType: 'json',
			url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
			data: {action: 'lmctlg_register_form_process', formData:regformData},
				success:function(data){
					
					//alert(data.message);
					// returns json
					jQuery('.show-register-form-return-data').show().prepend( data.message );
					// fade out
					jQuery('.show-register-form-return-data').delay(15000).fadeOut(1600);
					
					/*
					// success - redirect (refress)
					if (data.success == true){
					   document.location.href = lmctlg_ajax_shopping_cart.lmctlg_register_redirect_url; // specified at wp_localize_script
					}
					*/
				}
		  });
		
		return false;
		};
	
		// Forgot PW Form Process (json)
		jQuery('#lmctlg-forgot-pw-form').submit(forgotpwformSubmit);
		
		function forgotpwformSubmit(){
		
		// empty div before process
		jQuery('.show-forgot-pw-form-return-data').empty();
		
		  var forgotpwformData = jQuery(this).serialize();
		  //alert(regformData);
		
		  jQuery.ajax({
			type:"POST",
			dataType: 'json',
			url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
			data: {action: 'lmctlg_forgot_pw_form_process', formData:forgotpwformData},
				success:function(data){
					
					// returns json
					jQuery('.show-forgot-pw-form-return-data').show().prepend( data.message );
					// fade out
					jQuery('.show-forgot-pw-form-return-data').delay(15000).fadeOut(1600);
					
				}
		  });
		
		return false;
		};
	
		// Contact Form Process (json)
		jQuery('#lmctlg-contact-form').submit(contactformSubmit);
		
		function contactformSubmit(){
		
		// empty div before process
		jQuery('.show-contact-form-return-data').empty();
		
		  var contactformData = jQuery(this).serialize();
		
		  jQuery.ajax({
			type:"POST",
			dataType: 'json',
			url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
			data: {action: 'lmctlg_contact_form_process', formData:contactformData},
				success:function(data){
					
					// returns json
					jQuery('.show-contact-form-return-data').show().prepend( data.message );
					// fade out
					//jQuery('.show-contact-form-return-data').delay(15000).fadeOut(1600);
					jQuery('#lmctlg-contact-form-holder').hide();
					
				}
		  });
		
		return false;
		};
	
	
		// Shopping Cart - Checkout Form Process (json) - Send Order Data to Gateway
		jQuery(".lmctlg-loading-img").hide();
		jQuery('#lmctlg-checkout-form').submit(checkoutformSubmit);
		function checkoutformSubmit(event){
        event.preventDefault();
			var form$ = jQuery("#lmctlg-checkout-form");
			
			// empty div before process
			jQuery('.show-checkout-form-return-data').empty();
				
			  var checkoutformData = jQuery(this).serialize();
			  //alert(checkoutformData);
				  jQuery.ajax({
					type:"POST",
					dataType: 'json',
					//dataType: "text json",
					url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
					data: {action: 'lmctlg_checkout_form_process', formData:checkoutformData},
						success:function(response){
							
							//alert(response.checkoutformdata.lmctlg_first_name); // output data example
							//alert(JSON.stringify(response));// alert json data
							
							/*
							// output returned data using .each
							var newHTML = [];
							jQuery.each(response.checkoutformdata, function(key,value){
								//alert(key + " / " + value );
								newHTML.push('<span>' + ' - ' + value + '</span><br>');	
							});
							jQuery('.show-checkout-form-return-data').html( newHTML.join("") );
							
							// validate checkout form
							if (response.checkoutformvalid == false){
								
								// show error messages
								// returns json
								jQuery('.show-checkout-form-return-data').show().prepend( response.message ); // response.message
								// fade out
								jQuery('.show-checkout-form-return-data').delay(10000).fadeOut(1600);
								
							} else {
								// process
								// checkout form valid, set hidden field to 1
								jQuery('.lmctlg_checkout_form_valid').val('1');
								//alert('ok');
								//alert(JSON.stringify(response.checkoutformvalid));// alert json data
							}
							*/
						
						}
						
				  });
		
		return false;
		};
	
		// Payment Gateway BACS - Process Payment
		jQuery(".lmctlg-loading-img").hide();
		jQuery('#lmctlg-checkout-form').submit(gatewayBacsSubmit);
		
		function gatewayBacsSubmit(event){
        event.preventDefault();
		var currentgateway = jQuery('.lmctlg-current-gateway').val();
		//alert(currentgateway);
		// load jQuery only if bacs gateway selected
		if ( currentgateway == 'bacs' ) { // should be the registered gateway name
			//alert('hi');
			// clear and hide error messages
			jQuery(".lmctlg-payment-gateway-messages").html('');
			jQuery(".lmctlg-payment-gateway-messages").hide();
				
			var checkoutformData = jQuery(this).serialize();
			//alert(checkoutformData);
			
			jQuery(".lmctlg-loading-img").show();
			// spinner
			var formloaderimg = lmctlg_ajax_shopping_cart.lmctlg_form_loader_img; // ajax wp_localize_script
			jQuery('.lmctlg-loading-img').html('<img src="' + formloaderimg + '" width="128" height="15" alt="loading..." />');
			jQuery('#lmctlg-checkout-form-submit').attr('disabled', 'disabled'); // disable submit button after form submit
			// ajax submit after 2 seconds so preloader visible until
			setTimeout(function() 
			{
				jQuery.ajax({
					type:"POST",
					dataType: 'json',
					url: lmctlg_ajax_shopping_cart.lmctlg_wp_ajax_url,
					data: {action: 'lmctlg_process_bacs_payment', formData:checkoutformData},
					success:function(response)
					{
						jQuery('#lmctlg-checkout-form-submit').attr("disabled", false); // re-enable the submit button
						
	                    //alert(JSON.stringify(response));// alert json data
						
						jQuery(".lmctlg-loading-img").hide();
						
						if (response.checkoutsuccess == false){
							// show error messages
							// returns json
							jQuery('.lmctlg-payment-gateway-messages').show().prepend( response.message ); // response.message
							// fade out
							//jQuery('.lmctlg-payment-gateway-messages').delay(16000).fadeOut(1600);	
						} else {
							// success
							jQuery('#lmctlg-returning-customer-div').hide(); // returning customer div
							jQuery('#lmctlg-show-hide-login-form').hide(); // hide login form
							jQuery('#lmctlg-payment-methods-holder').hide(); // hide login form
							jQuery('#lmctlg-checkout-form').hide(); // hide checkout form
							jQuery('#lmctlg-terms-link').hide(); // hide terms link
							
							// show thank you message or use redirect
							var redirect = lmctlg_ajax_shopping_cart.lmctlg_success_redirect_url; // specified on public wp_localize_script
							if ( redirect !== '0' ) {
								// redirect to specified page
								document.location.href = lmctlg_ajax_shopping_cart.lmctlg_success_redirect_url; // specified on public wp_localize_script
							} else {
								// show success message
								//alert(JSON.stringify(response.message));// alert json data
								jQuery('.lmctlg-payment-gateway-messages').show().prepend( response.message ); // response.message
							}
						}
	
					} // success end
				});
			
			}, 2000);
		
		}
		
		return false;
		};
	
		// All Forms Manage Country States
		jQuery('#lmctlg_billing_country').on('change', function() {	
			
			var countrycode  = jQuery("#lmctlg_billing_country option:selected").attr("data-billing-country-code");
			var statedropdown  = jQuery("#lmctlg_billing_country option:selected").attr("data-billing-state-drop-display");
			//alert(countrycode);
			
			if (statedropdown == '1') {
				
				jQuery('#lmctlg-billing-country-state-field').hide();
				jQuery('#lmctlg-billing-country-state-dropdown').show();
				
				jQuery("#lmctlg-billing-country-state-field :input").attr('disabled','disabled');   // This will disable all the inputs inside the div
				
				// manage dropdown
				jQuery('#lmctlg-billing-country-state-dropdown select').removeAttr('disabled'); // enable select
				jQuery('#lmctlg-billing-country-state-dropdown select').prop('selectedIndex',0); // clear selected
				jQuery("#lmctlg-billing-country-state-dropdown select").children('option').hide(); // hide all the options
				jQuery('#lmctlg-billing-country-state-dropdown #lmctlg_billing_states_' + countrycode).show(); // show options only for the selected country
				
				
			} else {
				
				jQuery('#lmctlg-billing-country-state-field').show();
				jQuery('#lmctlg-billing-country-state-dropdown').hide();
				
				jQuery("#lmctlg-billing-country-state-dropdown select").attr('disabled','disabled');   // disable select
	
				jQuery("#lmctlg-billing-country-state-field :input").removeAttr('disabled'); // This will enable all the inputs inside the div
					
			}
	
		});
	
		// login, register, forgot pw forms
		jQuery('.lime-log-reg-buttons a').on('click', function(event){
			event.preventDefault();
			
			var form_type = jQuery(this).attr("lime-form-type");
			
			if ( form_type == 'login' ) {
				jQuery('.lime-display-login-form').fadeIn(500);
				jQuery('.lime-display-register-form').hide();
				jQuery('.lime-display-forgot-pw-form').hide();
			} else if ( form_type == 'register' ) {
				jQuery('.lime-display-login-form').hide();
				jQuery('.lime-display-forgot-pw-form').hide();			
				jQuery('.lime-display-register-form').fadeIn(500);
			} else if ( form_type == 'forgot_pw' ) {
				jQuery('.lime-display-login-form').hide();
				jQuery('.lime-display-register-form').hide();			
				jQuery('.lime-display-forgot-pw-form').fadeIn(500);
			} else {
				// default
				jQuery('.lime-display-login-form').show();
				jQuery('.lime-display-register-form').hide();
				jQuery('.lime-display-forgot-pw-form').hide();
			}
	
		});
		
		
});
	

