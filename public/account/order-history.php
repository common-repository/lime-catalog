<?php 

		global $wpdb;
		
		// GET DB LICENSE DATA
		$postmeta = $wpdb->prefix . 'postmeta'; // table, do not forget about tables prefix
		//  and meta_key = '_lmctlg_lic_order_id'
		$sql  = "
				SELECT *
				FROM $postmeta
				WHERE meta_key = '_lmctlg_order_cus_user_id' and meta_value = '$user_id' ORDER BY meta_id DESC
				";
		// save each result in array		
		$results = $wpdb->get_results( $sql, ARRAY_A ); // returns array: ARRAY_A
		
		########## UPDATE LICENSE(S) ##########
		if ( ! empty($results) ) {	
			
			/*
			echo '<pre>';
			print_r($results);
			echo '</pre>';
			*/
			
			// get current page url
			//$curr_page_url = esc_url(the_permalink());
			
			foreach( $results as $result )
			{
				// get licenses post id
				if ( $result['meta_key'] == '_lmctlg_order_cus_user_id' ) {
					

					// save in array
					$order_post_ids[] = $result['post_id'];
	
				}
			}
			
		?>
        
        <div class="lime-boxes">
        
        <div class="lime-boxes-title font-size-16"><?php _e( 'Order History', 'lime-catalog' ); ?></div>
                
        <!-- table-responsive start -->
        <div class="cw-table-responsive">
        
        <table id="cwtable">
        
        <thead>
          <tr>
            <th class="lime-uppercase"><?php _e( 'ID', 'lime-catalog' ); ?></th>
            <th class="lime-uppercase"><?php _e( 'Date', 'lime-catalog' ); ?></th>
            <th class="lime-uppercase"><?php _e( 'Status', 'lime-catalog' ); ?></th>
            <th class="lime-uppercase"><?php _e( 'Total', 'lime-catalog' ); ?></th>
            <th class="lime-uppercase"><?php _e( 'Details', 'lime-catalog' ); ?></th>
            </tr>
        </thead>
        
        <tbody>
        <?php
			
			// License item ids
			foreach( $order_post_ids as $order_post_id )
			{	
			
				//echo $order_post_id . '<br>';
				// Order Items
				// get data from 'lmctlg_order_items' where order_id = $order_post_id 
				
		 ?>			
          <tr>
            <td><?php echo '#' . $order_post_id; ?></td>
            <td>
            <?php 
            $order_date = get_post_meta( $order_post_id, '_order_date', true );
			$order_date = LMCTLG_Helper::formatDate( $date=$order_date );
            echo esc_attr( $order_date );
            ?>
            </td>
            <td>
            <?php 
            $order_status = get_post_meta( $order_post_id, '_order_status', true );
			$statuses = LMCTLG_Custom_Post_Statuses::lmctlg_order_custom_post_statuses();
			if ( ! empty( $statuses[$order_status] ) ) {
				$status = $statuses[$order_status];
				echo esc_attr( $status );
			} else {
				_e( 'None', 'lime-catalog' );
			}
            //echo $order_status;
            ?>
            </td>
            <td>
            <?php 
            $order_currency = get_post_meta( $order_post_id, '_order_currency', true ); 
            $order_total = get_post_meta( $order_post_id, '_order_total', true );
			// get the currency symbol
	        $order_currency_symbol = LMCTLG_Amount::lmctlg_get_currency_data_symbol( $currency=$order_currency );
			echo LMCTLG_Amount::lmctlg_orders_currency_symbol_position($amount=$order_total, $order_currency_symbol); // return span
            ?>
            </td>
            <td>
            <a href="<?php echo esc_url( the_permalink() . '?view-order=' . $order_post_id ); ?>"><?php _e( 'View', 'lime-catalog' ); ?></a>
            </td>
          </tr> 
		<?php		
			} // end foreach
		?>
        </tbody>
        
        </table>
        
        
        </div>
        <!-- table-responsive end -->
        
        </div><!--/ lime-boxes -->	
		<?php
		
		} // end if results
		else {
			echo '<p>' . __( "You haven't got any orders yet", 'lime-catalog' ) . '</p>';
		}

?>