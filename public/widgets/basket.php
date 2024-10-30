<?php

/**
 * Basket widget class.
 *
 * @package     lime-catalog
 * @subpackage  Public/widgets
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
	
add_action( 'widgets_init', function(){
   register_widget( 'LMCTLG_Basket_Widget' );
});
 
class LMCTLG_Basket_Widget extends WP_Widget  {

	/**
	 * Sets up the widgets name etc.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		
		$widget_ops = array( 
			'classname'   => 'LMCTLG_Basket_Widget',
			'description' => __( 'Display the shopping basket on your sidebar.', 'lime-catalog' )
		);
		
		parent::__construct( 'LMCTLG_Basket_Widget', 'Lime Catalog - Shopping Basket', $widget_ops ); 

	}
	
	/**
	 * Outputs the content of the widget.
	 *
	 * @since 1.0.0
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {  

		// If we're not on a single post, bail.
		/*
		if ( ! is_single() || get_post_type() != 'limecatalog' ) {
			return;
		}
		*/
		
		// check if custom post type exist
		if ( post_type_exists('limecatalog') == false ) {
		  // echo 'CPT NOT exist';
		   return;
		}
	    
		$basket_title = '';
		//store the options in variables
        $basket_title = $instance['lmctlg_basket_title'];
		
		if ( ! empty($basket_title) ) {
			$basket_title = $basket_title;
		}
		
		// This function takes an associative array and returns its keys as variables. 
		// It enables us to echo out $before_widget instead of $args['before_widget'], thus simplifying our code a little.
		extract( $args ); 
		 
		echo $before_widget;
		
		if ( $basket_title ) {
			echo $before_title . $basket_title . $after_title;
		}
							 
		// basket 
		echo do_shortcode('[lmctlg_basket]');
		
		echo $after_widget;
		 
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @since 1.0.0
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		//Check if option1 exists, if its null, set default 
		if ( isset( $instance[ 'lmctlg_basket_title' ] ) ) {
			$basket_title = $instance[ 'lmctlg_basket_title' ];
		}
		else {
			$basket_title = __('Cart', 'lime-catalog');
		}
		
		?>	
		<p>
		<label for="<?php echo $this->get_field_id( 'lmctlg_basket_title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'lmctlg_basket_title' ); ?>" name="<?php echo $this->get_field_name( 'lmctlg_basket_title' ); ?>" type="text" value="<?php echo esc_attr( $basket_title ); ?>" />
		</p>
		<?php 
		
	}

	/**
	 * Processing widget options on save.
	 *
	 * @since 1.0.0
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['lmctlg_basket_title'] = ( ! empty( $new_instance['lmctlg_basket_title'] ) ) ? strip_tags( $new_instance['lmctlg_basket_title'] ) : '';
		return $instance;
	}

	
	
}

?>