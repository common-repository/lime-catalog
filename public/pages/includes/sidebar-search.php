
<?php 
do_action( 'lmctlg_sidebar_search_form_before' ); // <- extensible
?>

<div class="lime-boxes">
<div class="lime-boxes-title"><?php _e( 'Search Products', 'lime-catalog' ); ?></div>

<?php echo do_shortcode('[lmctlg_search]'); ?>

</div>

<?php 
do_action( 'lmctlg_sidebar_search_form_after' ); // <- extensible
?>