<?php 
	
do_action( 'lmctlg_subcategories_before' ); // <- extensible	

// get options
$lmctlg_general_options = get_option('lmctlg_general_options');

	// Here is how you would query the term slug
	if( is_tax() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		$slug = $term->slug;
		$termmain = $term->name;
	}

// source: https://codex.wordpress.org/Function_Reference/get_term_children
// Used to get an array of children taxonomies
$term_id = $term->term_id;
$taxonomy_name = 'limecategory';
$termchildren = get_term_children( $term_id, $taxonomy_name );

$limecatalogurl = home_url() . '/limecatalog/';

// Display Main Categories on the catalog homepage and sub category boxes on the catalog pages 
if ( $lmctlg_general_options['display_category_boxes'] == '1' ) {

if ( ! empty( $termchildren ) && ! is_wp_error( $termchildren ) ) {

?>
<div class="lime-catalog-items">
  <ul class="lime-item-box-grid columns-3">
<?php

foreach ( $termchildren as $child ) {
   $term = get_term_by( 'id', $child, $taxonomy_name );
   
	// retrieving the values on a custom taxonomy
	// get the term id
	$t_id = $term->term_id;
	$term_meta = get_option( "lmctlg_limecategory_taxonomy_" . $t_id );
	//$custom_term_meta = $term_meta['custom_term_meta'];
	$lime_catalog_thumb_img = $term_meta['lime_catalog_thumb_img'];
	
	if ( $lime_catalog_thumb_img == '' ) {
		$thumbimg =  plugins_url() . '/' . $this->plugin_name . '/public/assets/images/no-image.jpg';
	} else {
		$thumbimg = $lime_catalog_thumb_img;
	}
	
	$termname = $term->name;
	// shorten term name
	//$termname = LMCTLG_Public::lmctlg_shorten_text($termname, $limit='8');
   
?>

    <li>
    
        <div class="thumb-container">
            <a href="<?php echo esc_url( get_term_link( $child, $taxonomy_name ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'View all posts under %s', 'lime-catalog' ), $term->name ) ); ?>">
            <img alt="<?php echo esc_attr( sprintf( __( 'View all posts under %s', 'lime-catalog' ), $term->name ) ); ?>" src="<?php echo esc_url( $thumbimg ); ?>" class="lime-catalog-category-thumb-img">
            </a>
        </div>
        
        <div class="category-title">
            <a href="<?php echo esc_url( get_term_link( $child, $taxonomy_name ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'View all posts under %s', 'lime-catalog' ), $term->name ) ); ?>">
            <?php echo esc_attr( $termname ); ?>
            </a>
        </div>
        
    </li>
            
<?php

}

?>
  </ul>
</div><!--/ lime-catalog-items -->
<?php 

echo '<hr class="lime-hr">';

}

// clean up after the query and pagination
wp_reset_postdata(); 

} // if end Display Categories

?>