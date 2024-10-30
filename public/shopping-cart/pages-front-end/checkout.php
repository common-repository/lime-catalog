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
	echo 'Checkout';
	
	echo '</div>';
    */
?>

<?php 
if ( isset($_GET['page'] ) ) {
	if($_GET['page'] == "checkout")
	{
	   echo do_shortcode('[lmctlg_checkout]'); 
	}
}
?>

</div><!--/ col -->

<div class="lime-col-4">	

<div class="lime-sidebar">

<?php echo do_shortcode('[lmctlg_checkout_totals]'); ?>

</div><!--/ lime-sidebar -->

</div><!--/ col -->

</div><!--/ row -->

</div><!--/ lime-catalog-wrapper -->