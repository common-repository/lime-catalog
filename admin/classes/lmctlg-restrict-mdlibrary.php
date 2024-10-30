<?php 

/**
 * Restrict capabilities media library.
 *
 * @package     lime-catalog
 * @subpackage  Admin/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class LMCTLG_Restrict_MDLibrary {
	
	/**
	 * Restrict users to view only media library items they upload.
	 * source: http://stackoverflow.com/questions/28787575/wordpress-restrict-users-to-see-only-their-uploads
	 *
	 * @global $current_user
	 * @global $pagenow
	 *
	 * @since 1.0.0
	 * @param object $wp_query_obj
	 * @return object $wp_query_obj
	 */
	public function lmctlg_restrick_users_view_own_attachments( $wp_query_obj ) {
	
		global $current_user, $pagenow;
		
		// Front end, do nothing
		if( !is_admin() )
			return;
	
		//End the party if it isn't a valid user
		if( ! is_a( $current_user, 'WP_User') )
			return;
			
	    //End the journey if we are not viewing a media page - upload.php (Directly) or admin-ajax.php(Through an AJAX call)
		if( ! in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
			return;
			
	     // Admins can view all media
		if( ! current_user_can('administrator') )
			$wp_query_obj->set('author', $current_user->ID );
	
		return $wp_query_obj;
	}
	
}

?>