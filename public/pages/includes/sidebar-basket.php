
<?php 

// get options
$lmctlg_general_options = get_option('lmctlg_general_options');

// get options
$lmctlg_cart_options = get_option('lmctlg_cart_options');

// if shopping cart enabled
if ( $lmctlg_cart_options['enable_shopping_cart'] == '1' ) {

do_action( 'lmctlg_sidebar_basket_before' ); // <- extensible 
?>

<div class="lime-boxes">

<div class="lime-shopping-basket-holder">
<?php 
$limecatalogurl = home_url() . '/limecatalog/';
?>

<?php echo do_shortcode('[lmctlg_basket]'); ?>


</div>

</div>

<?php 
do_action( 'lmctlg_sidebar_basket_after' ); // <- extensible

}
?>