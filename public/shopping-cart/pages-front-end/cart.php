<div class="lime-catalog-wrapper">	

<div class="lime-row">

<div class="lime-col-8">

<?php 
/*
	echo '<div class="breadcrumbs">';
	
	// catalog home
	$limecataloghome = home_url() . '/limecatalog/';
	echo '<a href="' . esc_url( $limecataloghome ) . '" alt="' . esc_attr( __( 'Home', 'lime-catalog' ) ) .'">' . __( 'Home', 'lime-catalog' ) .'</a> > ';
	// cart
	echo 'Cart';
	
	echo '</div>';
*/
?>

<?php 
// get page is only for custom cart
if ( isset($_GET['page'] ) ) {
	//echo 'action';
if($_GET['page'] == "cart")
{ 

echo do_shortcode('[lmctlg_cart]'); 

}
}
?>

</div><!--/ col -->

<div class="lime-col-4">	

<div class="lime-sidebar">

<?php echo do_shortcode('[lmctlg_cart_totals]'); ?>

</div><!--/ lime-sidebar -->

</div><!--/ col -->

</div><!--/ row -->

</div><!--/ lime-catalog-wrapper -->