<?php 

// get options
$lmctlg_general_options = get_option('lmctlg_general_options');

// default for includes/lime-catalog-products.php 
$termmain = '';
 
// source: https://developer.wordpress.org/reference/functions/get_terms/
// Since 4.5.0, taxonomies should be passed via the ‘taxonomy’ argument in the $args array
$terms = get_terms( array(
	'taxonomy' => 'limecategory',
	'hide_empty' => true, // if term category empty 
	'parent' => 0, // get only parent terms
	'order' => $lmctlg_general_options['category_order'], // 'ASC'
	'orderby' => $lmctlg_general_options['category_order_by'], // modified | title | name | ID | rand
) );
 
// Prior to 4.5.0, the first parameter of get_terms() was a taxonomy or list of taxonomies
//$terms = get_terms( 'limecategory', $args );

/*
echo '<pre>';
print_r($terms);
echo '</pre>';
*/
	
// Display Main Categories on the catalog homepage and sub category boxes on the catalog pages 
if ( $lmctlg_general_options['display_category_boxes'] == '1' ) {
?>

<div class="lime-catalog-items">
  <ul class="lime-item-box-grid columns-3">

<?php 
	
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$count = count( $terms );
		$i = 0;
		
		foreach ( $terms as $term ) 
		{
			$i++;
			
			// retrieving the values on a custom taxonomy
			// get the term id
			$t_id = $term->term_id;
			$term_meta = get_option( "lmctlg_limecategory_taxonomy_" . $t_id );
			//$custom_term_meta = $term_meta['custom_term_meta'];
			$lime_catalog_thumb_img = $term_meta['lime_catalog_thumb_img'];
			
			if ( $lime_catalog_thumb_img == '' ) {
				$thumbimg =  plugins_url( '/lime-catalog/public/assets/images/no-image.jpg');
			} else {
				$thumbimg = $lime_catalog_thumb_img;
			}
			
			$termname = $term->name;
			// shorten term name
			//$termname = LMCTLG_Public::lmctlg_shorten_text($termname, $limit='8');
			
?>

    <li>
    
        <div class="thumb-container">
<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'View all post filed under %s', 'lime-catalog' ), $term->name ) ); ?>">
<img alt="<?php echo esc_attr( sprintf( __( 'View all post filed under %s', 'lime-catalog' ), $term->name ) ); ?>" src="<?php echo esc_url( $thumbimg ); ?>" />
</a>
        </div>
        
        <div class="category-title">
<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'View all post filed under %s', 'lime-catalog' ), $term->name ) ); ?>">
<?php echo $termname; ?>
</a>
        </div>
        
    </li>
            
<?php
			
		}
		
?>
  </ul>
</div><!--/ lime-catalog-items -->
<?php 
		
	}
	
// clean up after the query and pagination
wp_reset_postdata(); 

echo '<hr class="lime-hr">';

} // if end Display Categories

?>