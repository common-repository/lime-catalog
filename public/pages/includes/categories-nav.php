
<?php 

do_action( 'lmctlg_sidebar_categories_nav_before' ); // <- extensible

?>

<div class="lime-catalog-navigation">

<?php echo do_shortcode('[lmctlg_categories_nav]'); ?>

</div>

<?php 
do_action( 'lmctlg_sidebar_categories_nav_after' ); // <- extensible
?>