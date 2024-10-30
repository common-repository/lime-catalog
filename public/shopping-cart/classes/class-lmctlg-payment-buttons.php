<?php

/**
 * Shopping Cart - Payment_Buttons
 *
 * @package     lime-catalog
 * @subpackage  public/shopping-cart/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class LMCTLG_Payment_Buttons {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Payment buttons.
	 *
	 * @to-do This Method is not in use. Just an example.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $payment_buttons
	 */
    public static function lmctlg_payment_buttons() 
	{
		
		$payment_buttons = array(
			'add_to_cart' => array(
				'label_one'  => __( ' - Add To Cart', 'lime-catalog' ), 
				'label_two'  => __( ' + View Cart', 'lime-catalog' ),
				'color_one'  => '#ec7a5c',
				'color_two'  => '#ec7a5c',
				'page'       => 'cart', 
			),
			'buy_now' => array(
				'label_one'  => __( ' - Buy Now', 'lime-catalog' ),
				'label_two'  => __( ' + Checkout', 'lime-catalog' ),
				'color_one'  => '#ec7a5c',
				'color_two'  => '#ec7a5c',
				'page'       => 'checkout', 
			),
		);
	
		return apply_filters( 'lmctlg_payment_buttons', $payment_buttons ); // <- extensible

	}
	
	/**
	 * Display "download free" button if applicable.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @return string $output
	 */
    public static function lmctlg_output_free_download_button($post_id) 
	{
		if ( empty( $post_id ) )
		return;
		
		// defaults
		$output = '';
		// check post type
	    if ( get_post_type( $post_id ) == 'limecatalog' ) {
			// show download button for free items
			$item_price = get_post_meta( $post_id, '_lmctlg_item_price', true );
			$item_sale_price_hidden = LMCTLG_Amount::lmctlg_amount_hidden($amount=$item_price);
			
			// if price is 0 or no price set
			if ( $item_sale_price_hidden == '0' ) {
				$enable_price_options = get_post_meta( $post_id, '_lmctlg_enable_price_options', true );
				// check if price option not enabled
				if ( $enable_price_options !== '1' ) {
					$item_downloadable  = get_post_meta( $post_id, '_lmctlg_item_downloadable', true ); // checkbox
					// check if item downloadable
					if ( $item_downloadable == '1' ) {
						$item_file_name  = get_post_meta( $post_id, '_lmctlg_item_file_name', true );
						$item_file_url   = get_post_meta( $post_id, '_lmctlg_item_file_url', true );
						// check if file name and url not empty
						if ( !empty($item_file_name) && !empty($item_file_url) ) {
							// show download button
							
							// downloadable products create download url
							$secret_data = array(
								'post_id'    => intval( $post_id )
							);
							
							// convert array to json
							$secret_data_json = json_encode( $secret_data );
							$secret_data_json_enc = LMCTLG_Helper::lmctlg_base64url_encode($data=$secret_data_json);
							// e.g. http://limecatalog.com/lmctlg-file-dw-api/?action=freedownload&dwfile=42423432
							$download_link = home_url() . '/lmctlg-file-dw-api/?action=freedownload&dwfile=' . $secret_data_json_enc;
							
							$output .= '<div class="lmctlg-download-free-button" id="lmctlg-download-free-button">';
							
								$output .= '<a href="' . esc_url( $download_link ) . '">';
								$output .= '<button type="button" class="btn-lime btn-lime-md btn-lime-green" >';
								$output .= esc_attr('+ Free Download', 'lime-catalog');
								$output .= '</button>';
								$output .= '</a>';
							
							$output .= '</div>';
							
							return $output;
						}
					}
				}
			}
		}
		
	}
	
	/**
	 * Display payment buttons. e.g. add to cart, buy now
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $button_atts
	 * @return string $output
	 */
    public static function lmctlg_output_payment_button($button_atts) 
	{
		if ( empty( $button_atts ) )
		return;

		// button atts
		$post_id   = intval( $button_atts['id'] ); // id is the post_id
		$page      = sanitize_text_field( $button_atts['page'] ); // cart or checkout
		$label_one = sanitize_text_field( $button_atts['label_one'] );
		$label_two = sanitize_text_field( $button_atts['label_two'] );
		$color_one = sanitize_text_field( $button_atts['color_one'] );
		$color_two = sanitize_text_field( $button_atts['color_two'] );
		
		// if no post id
		if ( $post_id == '0' )
		return;
		
		$limecatalogurl = home_url() . '/limecatalog/'; // cpt = limecatalog
		
		// Meta Boxes, Retrieve an existing value from the database.
		$item_price            = get_post_meta( $post_id, '_lmctlg_item_price', true );
		$item_downloadable     = get_post_meta( $post_id, '_lmctlg_item_downloadable', true );
		$enable_quantity_field = get_post_meta( $post_id, '_lmctlg_enable_quantity_field', true ); 
		
		// Set default values.
		if( empty( $item_price ) ) $item_price = '0';
		if( empty( $item_downloadable ) ) $item_downloadable = '';
		if( empty( $enable_quantity_field ) ) $enable_quantity_field = '0';
		
		// software licensing also using that option (class-public.php)
		$button_data = array(
			'post_id'     => intval( $post_id ),
			'item_price'  => sanitize_text_field( $item_price )
		);
		
		$button_data = LMCTLG_Payment_Buttons::lmctlg_default_price_payment_buttons( $button_data );
		
	    $item_price  = sanitize_text_field( $button_data['item_price'] );
		/*
		echo '<pre>';
		print_r($item_price);
		echo '</pre>';
		*/
		// item sale  price
		$item_sale_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$item_price); // return span
		$item_sale_price_hidden = LMCTLG_Amount::lmctlg_amount_hidden($amount=$item_price); // return span
		
		$cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();
		$cart_totals_cookie_name = LMCTLG_Cookies::lmctlg_cart_totals_cookie_name();
		
		$output = '';
		
		$output .= '<div id="lmctlg_payment_button_' . esc_attr( $post_id ) . '" class="lmctlg-payment-buttons-wrapper">';
		
		$output .= '<form class="lmctlg-payment-buttons-form-class" action="" method="post" id="lmctlg-payment-buttons-form">';
		$output .= '<input type="hidden" name="lmctlg-payment-buttons-form-nonce" value="' . wp_create_nonce('lmctlg_payment_buttons_form_nonce') . '"/>';
		$output .= '<input type="hidden" name="lmctlg_item_id" id="lmctlg_item_id" value="' . esc_attr( $post_id ) . '"/>';
		$output .= '<input type="hidden" name="lmctlg_item_price" class="lmctlg_item_price_class"  value="' . esc_attr( $item_sale_price_hidden ) . '"/>';
		$output .= '<input type="hidden" name="lmctlg_item_name" value="' . esc_attr( get_the_title( $post_id ) ) . '"/>';
		$output .= '<input type="hidden" name="lmctlg_item_downloadable" value="' . esc_attr( $item_downloadable ) . '"/>';
		$output .= '<input type="hidden" name="lmctlg_cart_items_cookie_name" class="lmctlg_cart_items_cookie_name_class"  value="' . esc_attr( $cart_items_cookie_name ) . '"/>';
		$output .= '<input type="hidden" name="lmctlg_cart_totals_cookie_name" class="lmctlg_cart_totals_cookie_name_class"  value="' . esc_attr( $cart_totals_cookie_name ) . '"/>';
		
		// show free download button
		$free_download_button = LMCTLG_Payment_Buttons::lmctlg_output_free_download_button($post_id);
		if ( ! empty($free_download_button) ) {
			echo $free_download_button;
		} else {
		
			$output .= '<div class="lmctlg-payment-button-1" id="lmctlg-payment-button-1">';
			    
				// if quantity field enabled
				if ( $enable_quantity_field == '1' ) {
					$output .= '<div class="lmctlg-item-quantity-input">';
					$output .= '<input type="number"  max="" min="1" value="1" id="lmctlg_item_quantity" name="lmctlg_item_quantity">';
					$output .= '</div>';
				} else {
					$output .= '<input type="hidden" max="" min="1" value="1" id="lmctlg_item_quantity" name="lmctlg_item_quantity"/>';
				}
				
				$output .= '<div class="lmctlg-payment-button-1-submit"> ';
				$output .= '<button type="submit" class="btn-lime btn-lime-md btn-lime-no-bg" style="background:' . esc_attr( $color_one ) . ';" >';
				$output .= ' ' . $item_sale_price_public . ' ' . esc_attr( $label_one );
				$output .= '</button>';
				$output .= '</div>';
			
			$output .= '</div>';
			
			$output .= '<div class="lmctlg-payment-button-2" id="lmctlg-payment-button-2">';
			
				$output .= '<a href="' . esc_url( $limecatalogurl . '?page=' . $page ) . '' . '">';
				$output .= '<button type="button" class="btn-lime btn-lime-md btn-lime-no-bg" style="background:' . esc_attr( $color_two ) . ';" >';
				$output .= esc_attr( $label_two );
				$output .= '</button>';
				$output .= '</a>';
			
			$output .= '</div>';
		
		}
		
		$output .= LMCTLG_Payment_Buttons::lmctlg_output_price_options_select_field( $post_id );
		
		$output .= '</form>';
		
		$output .= '</div><!--/ payment button -->';
		
		return $output;
		
	}
	
	/**
	 * Set default price for payment buttons.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $button_data
	 * @return array $button_data
	 */
	public static function lmctlg_default_price_payment_buttons( $button_data ) 
	{	
		 $post_id    = intval( $button_data['post_id'] );
		 $item_price = sanitize_text_field( $button_data['item_price'] );
		 
		 $enable_price_options   = get_post_meta( $post_id, '_lmctlg_enable_price_options', true );
		 
		 // if price options enabled
		 if ( ! empty( $enable_price_options ) && $enable_price_options == '1' ) {
			 
			$price_default_option = get_post_meta( $post_id, '_lmctlg_price_default_option', true );
			$price_options = get_post_meta( $post_id, '_lmctlg_price_options', true ); // json
			
			$price_options = json_decode($price_options, true);// convert into array
			
			if ( ! empty( $price_options ) && ! empty( $price_default_option ) ) {
			
				foreach( $price_options as $key ) {
					
					// get default
					if( $key['option_id'] == $price_default_option ) {
						$item_price = esc_attr__( $key['option_price'] );
					}
					
				}
			} else {
				// if price options enabled but no option added display 0
				$item_price = '0';
			}
			
		 }
		 
		 $button_data = array(
			'post_id'     => intval( $post_id ),
			'item_price'  => sanitize_text_field( $item_price )
		 );
		
		 return $button_data;
	 
	}

	/**
	 * Product view page when payment button clicked process Ajax.
	 *
	 * @since  1.0.0
	 * @return void
	 */
    public function lmctlg_payment_buttons_process() 
	{
		// defaults
		$item_id           = '';
		$item_price        = '';
		$item_name         = '';
		$item_quantity     = '';
		$item_downloadable = '';
		$price_option_id   = '';
		
		// get form data
		$formData = $_POST['formData'];
		// parse string
		parse_str($formData, $postdata);
		
	    // verify nonce
	    if ( wp_verify_nonce( $postdata['lmctlg-payment-buttons-form-nonce'], 'lmctlg_payment_buttons_form_nonce') )
	    {	

			// get domain name
			$domain = LMCTLG_Helper::lmctlg_site_domain_name();
			
			$item_id            = isset( $postdata['lmctlg_item_id'] ) ? sanitize_text_field( $postdata['lmctlg_item_id'] ) : '';
			$item_price         = isset( $postdata['lmctlg_item_price'] ) ? sanitize_text_field( $postdata['lmctlg_item_price'] ) : '';
			$item_name          = isset( $postdata['lmctlg_item_name'] ) ? sanitize_text_field( $postdata['lmctlg_item_name'] ) : '';
			$item_quantity      = isset( $postdata['lmctlg_item_quantity'] ) ? sanitize_text_field( $postdata['lmctlg_item_quantity'] ) : '';
			$item_downloadable  = isset( $postdata['lmctlg_item_downloadable'] ) ? sanitize_text_field( $postdata['lmctlg_item_downloadable'] ) : '';
			$price_option_id    = isset( $postdata['lmctlg_price_options'] ) ? sanitize_text_field( $postdata['lmctlg_price_options'] ) : ''; // select field
			
			// if "Private:" set on item name, remove
			$item_name = str_replace("Private:", "", $item_name);
			
			if( ! empty( $price_option_id ) ) {
				$price_option_id = $price_option_id;
			} else {
				$price_option_id = ''; // notset
			}
			
			$item_total = $item_price * $item_quantity;
			$item_total = LMCTLG_Amount::lmctlg_amount_hidden($amount=$item_total); // format
			
			// initialize empty cart items array
			$cart_items=array();
			 
			// add new item on array
			$item = array(
				  'item_id'            => intval( $item_id ),
				  'item_price'         => $item_price,
				  'item_name'          => $item_name,
				  'item_quantity'      => $item_quantity,
				  'item_downloadable'  => $item_downloadable,
				  'item_total'         => $item_total,
				  'price_option_id'    => intval( $price_option_id )
			);
			
			$id = intval( $item['item_id'] );
			
			// add new item on array
			$cart_items[$id]=$item;
			
			// cookie name
			$cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();
			// check if cookie exist
			if( LMCTLG_Cookies::lmctlg_is_cookie_exist( $name=$cart_items_cookie_name ) === true ) {	
				// read the cookie
				$cookie = LMCTLG_Cookies::lmctlg_get_cookie($name=$cart_items_cookie_name, $default = '');
			} else {
				$cookie = '';
			}
			
			if ( ! empty($cookie) ) {
				$arr_cart_items = json_decode($cookie, true); // convert to array
				$obj_cart_items = json_decode($cookie); // convert to object
			} else {
				$arr_cart_items = array();
			}
			 
			// check if the item is in the array, if it is, do not add
			if(array_key_exists($id, $arr_cart_items)){
				// redirect to product list and tell the user it was already added to the cart
			}
			 
			else{
				
				$total = '0';
				// if cart has contents
				if(count($arr_cart_items)>0){
					
					foreach($arr_cart_items as $key=>$value){	
					// add old item to array, it will prevent duplicate keys
					$cart_items[$key]=$value;
						
						if ($value['item_price'] !== '0') {
						  // item total, item price x quantity
						  $itemtotal = $value['item_price'] * $value['item_quantity'];
						  // price in total
						  $total = $total + $itemtotal;
						}
						
					}
					// current item  price * quantity
					$curritem = $item_price * $item_quantity;
					// price in total + current item  price
					$total = $total + $curritem;
					
				} else {
					// if cart empty add single item price
					$total = $item_price * $item_quantity;
				}
				
				// save totals in cookie
				$total = LMCTLG_Amount::lmctlg_amount_hidden($amount=$total);
				LMCTLG_Cart::lmctlg_cart_totals($subtotal=$total, $total);

				// cookie name
				$cart_items_cookie_name  = LMCTLG_Cookies::lmctlg_cart_items_cookie_name();
		
				// put item to cookie
				$value = json_encode($cart_items, true); // convert to array
				// set cookie, expires in 1 day
				$set_cookie = LMCTLG_Cookies::lmctlg_set_cookie($name=$cart_items_cookie_name, $value, $expiry = 86400, $path = '/', $domain, $secure = false, $httponly = false );
			
			}
			
	
        } 
		
	    #### important! #############
	    exit; // don't forget to exit!

    }
	
	/**
	 * Output select field for payment buttons.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @return string $output
	 */
	public static function lmctlg_output_price_options_select_field( $post_id ) 
	{	
		 $enable_price_options  = get_post_meta( $post_id, '_lmctlg_enable_price_options', true );
		 
		 // if price options enabled
		 if ( ! empty( $enable_price_options) && $enable_price_options== '1' ) {
			  
			  $output = '';
			  
			  $price_default_option = get_post_meta( $post_id, '_lmctlg_price_default_option', true );
			  
			  if ( ! empty( $price_default_option ) ) {
				  
				  $price_options = get_post_meta( $post_id, '_lmctlg_price_options', true ); // json  
				  
				  if ( ! empty( $price_options ) && $price_options !== 'null' ) {
					  
					  $price_options = json_decode($price_options, true);// convert into array	
			  
					  $output .= '<div class="lmctlg-price-options">';
					  $output .= '<label>';
					  $output .= '<select id="lmctlg_price_options" name="lmctlg_price_options" >';
					  
					  $selected = ''; // default
					  foreach( $price_options as $key ) {
						  
					  $option_id = intval( $key['option_id'] );
					  $item_price = sanitize_text_field( $key['option_price'] );
					  $item_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$item_price); // return span (HTML)
					  $item_price_hidden = LMCTLG_Amount::lmctlg_amount_hidden($amount=$item_price); // return string
						
						// get default
						if( $key['option_id'] == $price_default_option ) {
							$selected = 'selected="selected"';
							$output .= '<option ' . esc_attr( $selected ) . ' lmctlg-data-item-id="' . esc_attr__( $post_id ) . '" lmctlg-data-price-option="' . esc_attr__( $item_price_hidden ) . '" value="' . esc_attr__( $option_id ) . '">' . esc_attr__( $key['option_name'] ) . ' (' . $item_price_public . ') </option>';
						}
						// exclude selected
						if( $key['option_id'] !== $price_default_option ) {
							$output .= '<option lmctlg-data-item-id="' . esc_attr__( $post_id ) . '" lmctlg-data-price-option="' . esc_attr__( $item_price_hidden ) . '" value="' . esc_attr__( $option_id ) . '">' . esc_attr__( $key['option_name'] ) . ' (' . $item_price_public . ') </option>';
						}
						
					  }
					  
					  $output .= '</select>';
					  $output .= '</label>';
					  $output .= '</div>';
					  
					  return $output;
				  
				  } else {
					return;   
				  }
			  
			  }
		
		 } else {
			return; 
		 }
				
	}
  
	/**
	 * Display Item Sale price on items listing and item view pages.
	 * Options Display values: default, first
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @param  string $item_price
	 * @param  string $display
	 * @return html $item_sale_price_public
	 */
    public static function lmctlg_display_item_sale_price_public($post_id, $item_price, $display='default') 
	{
		if ( empty( $post_id ) )
		return;
		
		$item_sale_price_public = ''; // default
		$enable_price_options   = get_post_meta( $post_id, '_lmctlg_enable_price_options', true );
		 // if price options enabled
		 if ( ! empty( $enable_price_options ) && $enable_price_options == '1' ) {
	
			$price_default_option = get_post_meta( $post_id, '_lmctlg_price_default_option', true );
			$price_options = get_post_meta( $post_id, '_lmctlg_price_options', true ); // json
			
			$price_options = json_decode($price_options, true);// convert into array
			/*
			echo '<pre>';
			print_r($price_options);
			echo '</pre>';
			*/
			if ( ! empty( $price_options ) && ! empty( $price_default_option ) ) {
				  foreach( $price_options as $key ) {
					  
					  if ( $display == 'default' ) {
						  // get default
						  if( $key['option_id'] == $price_default_option ) {
							  $option_price_default = $key['option_price'];
							  $item_sale_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$option_price_default);
						  }
					  } elseif ( $display == 'first' ) {
						  // get first element of the array
						  if ($key === reset($price_options)) {
							  $option_price_default = $key['option_price'];
							  $item_sale_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$option_price_default); 
						  } 
					  } else {
						  // get default
						  if( $key['option_id'] == $price_default_option ) {
							  $option_price_default = $key['option_price'];
							  $item_sale_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$option_price_default);
						  }
					  }
					  
					  // get last element of the array
					  if ($key === end($price_options)) {
						 
					  }
	
				  }
				  //$price_label = 'From';
				  
			} else {
				// if price options enabled but no option added display 0
				$amount = '0';
				$item_sale_price_public = LMCTLG_Amount::lmctlg_amount_public($amount);
			}
	
		} else {
				// item sale price
				$item_sale_price_public = LMCTLG_Amount::lmctlg_amount_public($amount=$item_price);
		}
		
		return $item_sale_price_public;
		
	}
	

	
}

?>