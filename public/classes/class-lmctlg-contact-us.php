<?php

/**
 * Contact Class.
 *
 * @package     lime-catalog
 * @subpackage  public/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_Contact {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name    The name of the plugin.
	 * @param      string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Process the contact form.
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public function lmctlg_contact_form_process()
	{

		// get form data
		$formData = $_POST['formData'];
		// parse string
		parse_str($formData, $postdata);
		
	    // verify nonce
	    if ( wp_verify_nonce( $postdata['lmctlg-contact-form-nonce'], 'lmctlg_contact_form_nonce') )
	    {
			// sanitize form values
			$firstname      = sanitize_text_field( $postdata['lmctlg_firstname'] );
			$lastname       = sanitize_text_field( $postdata['lmctlg_lastname'] );
			$email          = sanitize_email( $postdata['lmctlg_email'] );
			$telephone      = sanitize_text_field( $postdata['lmctlg_telephone'] );
			$email_subject  = sanitize_text_field( $postdata['lmctlg_subject'] );
			$message        = wp_kses_post( $postdata['lmctlg_message'] );
			
			// get site name
			// source: https://developer.wordpress.org/reference/functions/get_bloginfo/
			if ( !empty( get_bloginfo('name') ) ) {
				$blog_name = get_bloginfo('name');
			} else {
				$blog_name = 'WordPress';
			}
			
			// get the blog administrator's email address
			$to = get_bloginfo('admin_email');
			
			$subject = esc_attr( $blog_name ) . " " . __( "Contact Form", "lime_catalog" );
			
			$sender = "From: " . esc_attr( $blog_name ) . " <" . esc_attr( $to ) . ">" . "\r\n";
			
			$emailbody = "\n";
			$emailbody .= __( "Date: ", "lime_catalog" ) . " " . esc_attr( date( 'Y-m-d H:i:s' ) ) . " \n\n";
			$emailbody .= __( "Sent From: ", "lime_catalog" ) . " " . esc_attr( $blog_name ) . __( " Contact Form ", "lime_catalog" ) . " \n\n";	
			$emailbody .= __( "Firstname: ", "lime_catalog" ) . " " . esc_attr( $firstname ) . " " .  __( "Lastname: ", "lime_catalog" ) . esc_attr( $lastname ) . " \n\n";
			$emailbody .= __( "Email: ", "lime_catalog" ) . " " . esc_attr( $email ) . " " .  __( "Phone: ", "lime_catalog" ) . esc_attr( $telephone ) . " \n\n";
			$emailbody .= __( "Subject: ", "lime_catalog" ) . " " . esc_attr( $email_subject ) . " \n\n";
			$emailbody .= __( "Message: ", "lime_catalog" ) . " \n\n";
			$emailbody .= esc_textarea( $message ) . " \n\n";
			
			$emailbody .= __( "With Kind Regards,  ", "lime_catalog" ) . " " . esc_attr( $blog_name ) .  " \n";
			$emailbody .= network_home_url( '/' ) .  " \n\n";
			
			$emailbody = stripslashes_deep( nl2br($emailbody) );
			
			$header = "MIME-Version: 1.0\r\n";
			$header .= "Content-Type: text/html; charset=UTF-8\r\n";
			$header .= $sender . "\r\n";
			
			$mail_errors = ''; // default
			
			// send email
			$mail_errors = LMCTLG_Emailer::lmctlg_emailer_send_email( $to, $subject, $emailbody, $header );
			
			// will return true only when $mail_errors = true
			if ( $mail_errors === true ) {
			    
				$error_message = __( "An unexpected error occurred while sending email.", "lime_catalog" );
                $print = LMCTLG_Validate::lmctlg_error_msg( $error_id='email_sending_error', $error_message );
				// return json
				echo json_encode(array('success'=>false, 'message'=>$print ));
			
			} else {
				
				$success_message = __( "Thank you! We have received your email.", "lime_catalog" );
                $print = LMCTLG_Validate::lmctlg_success_msg( $success_id='email_sent_successfully', $success_message );
				echo json_encode(array('success'=>true, 'message'=>$print ));						

			}
			
		}
		
	    #### important! #############
	    exit; // don't forget to exit!
		
	}
	
	
	
}

?>