<?php 

// subs extensible 
$subs = LMCTLG_Admin::lmctlg_admin_settings_general_subs();

// DEFAULTS
$pageslug = '';
$pagerequire = '';

if( isset($_GET['post_type']) && $_GET['post_type'] == "limecatalog") // post type 
{  

	if( isset($_GET['tab']) && $_GET['tab'] == "general-main")
	{
	
		if( isset( $_GET['sub'] ) )
		{
			$get_sub = $_GET['sub'];
		} else {
			$get_sub = '';
		}
		
		$baseurl = home_url() . '/wp-admin/edit.php?post_type=limecatalog&page=lime-settings&tab=general-main&sub=';
	
		echo '<div>';
		echo '<ul class="subsubsub">';
	
		foreach( $subs as $sub => $title )
		{  
		   // set current
		  if ( $get_sub == $sub ) {
			 $active   = 'current';
			 $pageslug = $sub;
		  } else {
			 $active = ''; 
		  }
		  
		  // set default page active
		  if ( empty ($get_sub) && $sub == 'general-catalog' ) {
			 $active_default  = 'current';
			 $pageslug = 'general-catalog';
		  } else {
			 $active_default = ''; 
		  }
		  
		  echo '<li>';
		  echo '<a href="' . esc_url( $baseurl . $sub ) . '" title="' . esc_attr( $title ) . '" class=" ' . esc_attr( $active ) . ' ' . esc_attr( $active_default ) . '"> ' . esc_html( $title ) . '</a> | &nbsp;';
		  echo '</li>';
		  
		}
		
		echo '</ul>';
		echo '</div>';
		
		echo '<br class="clear">';
		
		// page content
		if ( file_exists( LMCTLG_PLUGIN_DIR . 'admin/pages/general/' . $pageslug . '.php' ) ) {
		  require $pagerequire = plugin_dir_path( dirname( __FILE__ ) ) . 'pages/general/' . $pageslug . '.php';
		} else {
		  // make it extensible
		  do_action( 'lmctlg_admin_add_settings_general_sub_page' ); // <- extensible	
		}
		
	} else {
		
		$baseurl = home_url() . '/wp-admin/edit.php?post_type=limecatalog&page=lime-settings&tab=general-main&sub=';
	
		echo '<div>';
		echo '<ul class="subsubsub">';
	    
		$get_sub = 'general-catalog'; // default (main) page
		
		foreach( $subs as $sub => $title )
		{  
		   // set current
		  if ( $get_sub == $sub ) {
			 $active   = 'current';
			 $pageslug = $sub;
		  } else {
			 $active = ''; 
		  }
		  
		  // set default page active
		  if ( empty ($get_sub) && $sub == 'general-catalog' ) {
			 $active_default  = 'current';
			 $pageslug = 'general-catalog';
		  } else {
			 $active_default = ''; 
		  }
		  
		  echo '<li>';
		  echo '<a href="' . esc_url( $baseurl . $sub ) . '" title="' . esc_attr( $title ) . '" class=" ' . esc_attr( $active ) . ' ' . esc_attr( $active_default ) . '"> ' . esc_html( $title ) . '</a> | &nbsp;';
		  echo '</li>';
		  
		}
		
		echo '</ul>';
		echo '</div>';
		
		echo '<br class="clear">';
		
	    // default (main) page	
		require $pagerequire = plugin_dir_path( dirname( __FILE__ ) ) . 'pages/general/general-catalog.php';
	}

}

?>