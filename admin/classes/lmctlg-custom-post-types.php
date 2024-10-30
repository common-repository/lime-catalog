<?php

/**
 * Admin Custom Post Types.
 *
 * @package     lime-catalog
 * @subpackage  Admin/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Custom_Post_Types {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties. class-custom-post-types.php
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name    The name of the plugin.
	 * @param      string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Custom Post Types.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_custom_post_types() {
		
		$lime_menu_icon = plugins_url( '/' . $this->plugin_name . '/admin/assets/images/lime-catalog-icon-v-3.png');
	 
		$item_labels = apply_filters( 'lmctlg_limecatalog_cpt_labels', array(
			'name'                  => __( 'Lime Catalog', 'lime-catalog' ),
			'singular_name'         => __( 'Item', 'lime-catalog' ),
			'add_new'               => __( 'Add New Item', 'lime-catalog' ),
			'add_new_item'          => __( 'Add New Item', 'lime-catalog' ),
			'edit_item'             => __( 'Edit Item', 'lime-catalog' ),
			'new_item'              => __( 'New Item', 'lime-catalog' ),
			'all_items'             => __( 'Items', 'lime-catalog' ),
			'view_item'             => __( 'View Item', 'lime-catalog' ),
			'search_items'          => __( 'Search Items', 'lime-catalog' ),
			'not_found'             => __( 'No Items found', 'lime-catalog' ),
			'not_found_in_trash'    => __( 'No Items found in Trash', 'lime-catalog' ),
			'parent_item_colon'     => __( '', 'lime-catalog' ),
			'menu_name'             => __( 'Lime Catalog', 'lime-catalog' ),
			'featured_image'        => __( 'Item Featured Image', 'lime-catalog' ),
			'set_featured_image'    => __( 'Set Item Featured Image', 'lime-catalog' ),
			'remove_featured_image' => __( 'Remove Item Featured Image', 'lime-catalog' ),
		) );
		
		$capabilities_items = array(
			'read_post'              => 'read_item',						
			'edit_posts'             => 'edit_items',	
			'publish_posts'          => 'publish_items',
			'edit_published_posts'   => 'edit_published_items',
			'delete_published_posts' => 'delete_published_items',
			'upload_files'           => 'upload_files',
			'edit_others_posts'      => 'edit_others_items',
			'delete_others_posts'    => 'delete_others_items',
			'read_private_posts'     => 'read_private_items',
			'edit_private_posts'     => 'edit_private_items',
			'delete_private_posts'   => 'delete_private_items',						
			'delete_posts'           => 'delete_items',	
		);
	 
		$item_args = apply_filters( 'lmctlg_limecatalog_cpt_args', array(
			'labels'              => $item_labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'limecatalog' ),
			'exclude_from_search' => false,
			'capability_type'     => array('item', 'items'),
			'map_meta_cap'        => true, // adding map_meta_cap will map the meta capabilities correctly 
			'capabilities'        => $capabilities_items,
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => '25',
			'menu_icon'           => $lime_menu_icon,
			'supports'            => apply_filters( 'lmctlg_limecatalog_cpt_supports', array( 'title', 'editor', 'thumbnail', 'revisions', 'author' ) ),
			'taxonomies'          => array('limecategory')
		) );
		// origin: 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'comments', 'page-attributes'
	 
		register_post_type( 'limecatalog', $item_args );
		
		// get options
		$lmctlg_general_options = get_option('lmctlg_general_options');
		
		// get options
		$lmctlg_cart_options = get_option('lmctlg_cart_options');
		
		// if shopping cart enabled
		if ( $lmctlg_cart_options['enable_shopping_cart'] == '1' ) 
		{
			/** Orders Post Type */
			$order_labels = apply_filters( 'lmctlg_lime_shop_orders_cpt_labels', array(
				'name'               => __( 'Orders', 'lime-catalog' ),
				'singular_name'      => __( 'Order', 'lime-catalog' ),
				'add_new'            => __( 'Add New', 'lime-catalog' ),
				'add_new_item'       => __( 'Add New Order', 'lime-catalog' ),
				'edit_item'          => __( 'Edit Order', 'lime-catalog' ),
				'new_item'           => __( 'New Order', 'lime-catalog' ),
				'view_item'          => __( 'View Order', 'lime-catalog' ),
				'search_items'       => __( 'Search Orders', 'lime-catalog' ),
				'not_found'          => __( 'No Orders found', 'lime-catalog' ),
				'not_found_in_trash' => __( 'No Orders found in Trash', 'lime-catalog' ),
				'parent_item_colon'  => __( 'Parent Order:', 'lime-catalog' ),
				'menu_name'          => __( 'Orders', 'lime-catalog' )
			) );
			
			// removed, only admin can read, edit etc. do not delete!!!
			$capabilities_orders = array(
			'read_post'              => 'read_order',						
			'edit_posts'             => 'edit_orders',	
			'publish_posts'          => 'publish_orders',
			'edit_published_posts'   => 'edit_published_orders',
			'delete_published_posts' => 'delete_published_orders',
			'upload_files'           => 'upload_files',
			'edit_others_posts'      => 'edit_others_orders',
			'delete_others_posts'    => 'delete_others_orders',
			'read_private_posts'     => 'read_private_orders',
			'edit_private_posts'     => 'edit_private_orders',
			'delete_private_posts'   => 'delete_private_orders',						
			'delete_posts'           => 'delete_orders',			
			);
	
			$order_args = apply_filters( 'lmctlg_lime_shop_orders_cpt_args', array(
				'labels'              => $order_labels,
				'hierarchical'        => false,
				'supports'            => apply_filters( 'lmctlg_lime_shop_orders_cpt_supports', array( 'title' ) ),
				'public'              => false, // it's not public, it shouldn't have it's own permalink, and so on
				'show_ui'             => true, // you should be able to edit in wp-admin
				'show_in_menu'        => 'edit.php?post_type=limecatalog', // !!! important, add parent custom post type
				'show_in_nav_menus'   => false, // you shouldn't be able to add it to menus
				'publicly_queryable'  => true, // you should be able to query it
				'exclude_from_search' => true, // you should exclude it from search results
				'has_archive'         => false, // it shouldn't have archive page
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false, // it shouldn't have rewrite rules
				// map_meta_cap will allow us to remap the existing capabilities with new capabilities to match the new custom post type
				'map_meta_cap'        => true, // Set to `false`, if users are not allowed to edit/delete existing posts
				'capability_type'     => array('order', 'orders'),
				// capabilities are what we are customising so lets remap them
				'capabilities'        => $capabilities_orders
			) );
			
			register_post_type( 'lime_shop_orders', $order_args );		
			
	    
		}
		
	}

	/**
	 * Set columns.
	 *
	 * @since 1.0.0
	 * @param array $columns
	 * @return void
	 */
	public function lmctlg_limecatalog_items_columns( $columns ) {
		$columns = array(
			'cb'         => '<input type="checkbox" />', // wp default
			'image'      => __( '', 'lime-catalog' ),
			'title'      => __( 'Name', 'lime-catalog' ), // wp default
			//'sku'        => __( 'SKU', 'lime-catalog' ),
			'price'      => __( 'Price', 'lime-catalog' ),
			'category'   => __( 'Categories', 'lime-catalog' ),
			'postid'     => __( 'ID', 'lime-catalog' ),
			'vendor'     => __( 'Vendor', 'lime-catalog' ),
			'date'       => __( 'Date', 'lime-catalog' ) // wp default
		);
	
		return apply_filters( 'lmctlg_limecatalog_cpt_columns', $columns );
	}
	
	/**
	 * Render columns.
	 *
	 * @global $post
	 *
	 * @since 1.0.0
	 * @param array $columns
	 * @param int $post_id
	 * @return void
	 */
    public function lmctlg_limecatalog_render_items_columns( $columns, $post_id ) {
		
		global $post;
		
		if ( get_post_type( $post_id ) == 'limecatalog' ) {
			
			switch( $columns ) {
				
				case 'image' :
		            
				  // check if has thumb
                  if ( has_post_thumbnail() ) {
					//the_post_thumbnail( 'lime-catalog-item-thumb' );
					//the_post_thumbnail( array(180, 130) );
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($post_id ) ); // get thumbnail data (array)
					//$thumbnail[0]; // thumbnail url
					echo '<img width="60px" src="' . esc_url( $thumbnail[0] ) . '" style="display:block; vertical-align: middle; padding-right:12px;">';
				  } else {
					 echo __( 'no image' ); 
				  }
		
					break;
					
				/* If displaying the 'sku' column. */
				/*
				case 'sku' :
					
					// Get the post meta.
					$item_sku = get_post_meta( $post_id, '_lmctlg_item_sku', true );
		            
					if( empty( $item_sku ) ) $item_sku = '-';
					
					echo __( $item_sku ); 
		
					break;
				*/
				
				/* If displaying the 'price' column. */
				case 'price' :
					
					/* Get the post meta. */
					$item_regular_price = get_post_meta( $post_id, '_lmctlg_item_regular_price', true );
					$item_sale_price    = get_post_meta( $post_id, '_lmctlg_item_price', true );
					
					// item regular price span
					$item_regular_price = LMCTLG_Amount::lmctlg_amount_public($amount=$item_regular_price);
					//$item_sale_price = LMCTLG_Amount::lmctlg_amount_public($amount=$item_sale_price);
					
					// item sale price span
					$item_sale_price = LMCTLG_Payment_Buttons::lmctlg_display_item_sale_price_public($post_id, $item_price=$item_sale_price, $display='first');
		            
					if( empty( $item_regular_price ) ) $item_regular_price = '';
					if( empty( $item_sale_price ) ) $item_sale_price = '';
					
					$item_regular_price = '<span style="text-decoration: line-through;">' . $item_regular_price . '</span>';
					echo __( $item_sale_price . ' ' . $item_regular_price ); 
		
					break;
					
				/* If displaying the 'category' column. */
				case 'category' :
					
					/* Get the terms. */
					$categories = get_the_terms( $post_id, 'limecategory' ); // taxonomy array
					
					if( ! empty( $categories ) ) {
						foreach($categories as $category => $value )
						{
							$url = 'edit.php?post_type=limecatalog&amp;' . $value->taxonomy . '=' . $value->slug;
							echo '<a href="' . esc_url($url) . '">' . esc_html( $value->name ) . '</a> ';
						}
					} else {
						_e( 'Undefined category', 'lime-catalog' );
					}
		
					break;
					
				/* If displaying the 'itemid' column. */
				
				case 'postid' :
		            
                   echo esc_html( $post_id );
		
					break;
					
				/* If displaying the 'postby' column. */
				
				case 'vendor' :
		           
				   if( ! empty( $post->post_author ) ) {
					   $author_id = $post->post_author;
					   $display_name = the_author_meta( 'display_name' , $author_id );
                       echo esc_html( $display_name );
				   }
		
					break;
		
				/* Just break out of the switch statement for everything else. */
				default :
					break;
			}
		}
	}
	
	/**
	 * Remove action buttons, Quick Edit, View etc.
	 * 
	 * @since 1.0.0
	 * @param array $actions
	 * @return array $actions
	 */
	public function lmctlg_orders_remove_row_actions( $actions )
	{
		global $post, $current_screen;
		
		//if( get_post_type() === 'lime_shop_orders' )
		//if( $current_screen->post_type === 'lime_shop_orders' )
		if( isset($_GET['post_type']) && $_GET['post_type'] == "lime_shop_orders" ) {
			//unset( $actions['edit'] );
			unset( $actions['view'] );
			//unset( $actions['trash'] );
			unset( $actions['inline hide-if-no-js'] );
			return $actions;
		} else {
		    return $actions;
		}
		
	}

	/**
	 * Set columns.
	 *
	 * @since 1.0.0
	 * @param array $columns
	 * @return void
	 */
	public function lmctlg_lime_shop_orders_columns( $columns ) {
		$columns = array(
			'cb'         => '<input type="checkbox" />', // wp default
			'title'      => __( 'ID', 'lime-catalog' ), // wp default
			'customer'   => __( 'Customer', 'lime-catalog' ),
			'status'     => __( 'Status', 'lime-catalog' ),
			'total'      => __( 'Total', 'lime-catalog' ),
			'gateway'    => __( 'Gateway', 'lime-catalog' ),
			'orderdate'  => __( 'Date', 'lime-catalog' ) // wp default
		);
	
		return apply_filters( 'lmctlg_lime_shop_orders_cpt_columns', $columns );
	}

	/**
	 * Render columns.
	 *
	 * @global $post
	 *
	 * @since 1.0.0
	 * @param array $columns
	 * @param int $post_id
	 * @return void
	 */
    public function lmctlg_lime_shop_render_orders_columns( $columns, $post_id ) {
		
		global $post;
		
		if ( get_post_type( $post_id ) == 'lime_shop_orders' ) {
			
			switch( $columns ) {
				
				case 'title' :
		            
                    echo esc_html( get_the_title() );
		
					break;
					
				case 'customer' :
					
					/* Get the post meta. */
					$cus_user_id = get_post_meta( $post_id, '_lmctlg_order_cus_user_id', true );
					if ( !empty($cus_user_id) ) {	
						$user = get_user_by( 'id', $cus_user_id );
						if ( !empty($user) ) {
							echo esc_html( $user->first_name ) . ' ' . esc_html( $user->last_name ) . '<br>';
							echo esc_html( $user->user_email );
						}
					}
		
					break;
					
				case 'status' :
					
					/* Get the post meta. */
					$order_status = get_post_meta( $post_id, '_order_status', true );
					if( empty( $order_status ) ) $order_status = '';
					
					$statuses = LMCTLG_Custom_Post_Statuses::lmctlg_order_custom_post_statuses();
					if ( ! empty( $statuses[$order_status] ) ) {
						$status = $statuses[$order_status];
		                echo esc_html( $status );
					} else {
						_e( 'None', 'lime-catalog' );
					}
					
					break;
					
				case 'total' :
					
					/* Get the post meta. */
					$order_total = get_post_meta( $post_id, '_order_total', true );
					$order_total = LMCTLG_Amount::lmctlg_format_amount($amount=$order_total);
					
					$order_currency = get_post_meta( $post_id, '_order_currency', true );
					
					// get the currency symbol
					$currency_symbol = LMCTLG_Amount::lmctlg_get_currency_data_symbol( $currency=$order_currency );
					// span
					$total = LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$order_total, $currency_symbol);

		            echo $total;
		
					break;
					
				case 'gateway' :
		            
					/* Get the post meta. */
					$order_gateway = get_post_meta( $post_id, '_order_gateway', true );
		            echo esc_html( $order_gateway );
		
					break;
					
				case 'orderdate' :
		            
					/* Get the post meta. */
					$order_date = get_post_meta( $post_id, '_order_date', true );
					$date  = date('Y F d',strtotime($order_date)); // format
		            echo esc_html( $date );
		
					break;
		
				/* Just break out of the switch statement for everything else. */
				default :
					break;
			}
		}
	}

	/**
	 * Orders custom messages for updates.
	 * 
	 * @global $post
	 *
	 * @since 1.0.0
	 * @param void $messages
	 * @return void $messages
	 */
	public function lmctlg_lime_shop_orders_update_messages( $messages )
	{
		
	  global $post;
	  $post_ID = $post->ID;
	  $post_type = 'lime_shop_orders'; // If you want a specific post type 
	 
	  if($post_type==$post->post_type) {
	
			$obj = get_post_type_object( $post_type );
			$singular = $obj->labels->singular_name;
		/*
			// get transient
			$order_receipt_sent = ''; // default
			// If transient exist
			if ( get_transient( 'lmctlg_order_receipt_sent' ) ) {
				$order_receipt_sent =  ' ' . __('Order receipt successfully sent!', 'lime-catalog');
				// delete transient
				delete_transient( 'lmctlg_order_receipt_sent' );
			} 
			*/
			
			$messages[$post_type] = array(
					0 => '', // Unused. Messages start at index 1.
					1 => sprintf( __( '%s updated.' ), esc_attr( $singular ), esc_url( get_permalink( $post_ID ) ), strtolower( $singular ) ),
					2 => __( 'Custom field updated.', 'lime-catalog' ),
					3 => __( 'Custom field deleted.', 'lime-catalog' ),
					4 => sprintf( __( '%s updated.', 'lime-catalog' ), esc_attr( $singular ) ),
					5 => isset( $_GET['revision']) ? sprintf( __('%2$s restored to revision from %1$s', 'lime-catalog' ), wp_post_revision_title( (int) $_GET['revision'], false ), esc_attr( $singular ) ) : false,
					6 => sprintf( __( '%s updated.' ), $singular, esc_url( get_permalink( $post_ID ) ), strtolower( $singular ) ),
					7 => sprintf( __( '%s saved.', 'lime-catalog' ), esc_attr( $singular ) ),
					8 => sprintf( __( '%s submitted.'), $singular, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), strtolower( $singular ) ),
					9 => sprintf( __( '%s scheduled for: <strong>%s</strong>.' ), $singular, date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ), strtolower( $singular ) ),
					10 => sprintf( __( '%s draft updated.'), $singular, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), strtolower( $singular ) )
			);
			
	  }
	
			return $messages;
	}



	
}

?>