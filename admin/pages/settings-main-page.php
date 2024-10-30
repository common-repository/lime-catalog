
<div class="wrap">
<h1>Lime Catalog <?php _e('Settings', 'lime-catalog'); ?></h1>
 <h1 class="nav-tab-wrapper">
<?php

// tabs extensible 
$tabs = LMCTLG_Admin::lmctlg_admin_settings_tabs();

// DEFAULTS
$pageslug = '';
$pagerequire = '';

if( isset($_GET['post_type']) && $_GET['post_type'] == "limecatalog") // post type
{  

	if( isset( $_GET['tab'] ) )
	{
		$get_tab = $_GET['tab'];
	} else {
		$get_tab = '';
	}
	
	$baseurl = home_url() . '/wp-admin/edit.php?post_type=limecatalog&page=lime-settings&tab=';

	foreach( $tabs as $tab => $title )
	{  
	   // set current
	  if ( $get_tab == $tab ) {
		 $active   = 'nav-tab-active';
		 $pageslug = $tab;
	  } else {
		 $active = ''; 
	  }
	  
	  // set default page active
	  if ( empty ($get_tab) && $tab == 'general-main' ) {
		 $active_default  = 'nav-tab-active';
		 $pageslug = 'general-main';
	  } else {
		 $active_default = ''; 
	  }
		 
	  echo '<a href="' . esc_url( $baseurl . $tab ) . '" title="' . esc_attr( $title ) . '" class="nav-tab ' . esc_attr( $active ) . ' ' . esc_attr( $active_default ) . '"> ' . esc_html( $title ) . '</a>';
	  
	}

}

?>
 </h1>
<?php

if( isset($_GET['post_type']) && $_GET['post_type'] == "limecatalog") // post type
{  
  
  // page content
  if ( file_exists( LMCTLG_PLUGIN_DIR . 'admin/pages/' . $pageslug . '.php' ) ) {
	 require $pagerequire = LMCTLG_PLUGIN_DIR . 'admin/pages/' . $pageslug . '.php';
  } else {
	// make it extensible
	do_action( 'lmctlg_admin_add_settings_main_page' ); // <- extensible
	//echo 'Page Not Exist.'; // test
  }
  
}

?>
    
</div><!--/ .wrap -->