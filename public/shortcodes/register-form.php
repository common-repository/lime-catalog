
<!-- cw-form start -->
<div class="cw-form cw-form-maxwidth">

<?php 
// check if user registration is enabled
if ( get_option( 'users_can_register' ) ) {
?>

<!-- register form -->
<div id="lmctlg-register-form-holder" class="lime-margin-top-15">

<!-- jQuery -->
<div class="show-register-form-return-data"></div>

<div class="textlabel-forms-bold lime-uppercase"><?php echo esc_attr( $atts['title'] ); ?></div>

<form id="lmctlg-register-form" action="" enctype="multipart/form-data" method="post">

<input type="hidden" name="lmctlg-register-form-nonce" value="<?php echo wp_create_nonce('lmctlg_register_form_nonce'); ?>"/>
<input type="hidden" name="lmctlg_user_role" value="<?php echo esc_attr( $atts['role'] ); ?>"/>

<fieldset>

<div class="r-row">

  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'Username', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-user"></i>
      <input type="text" id="lmctlg_username" name="lmctlg_username" value="" required >
    </div>
  </div>
  
  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'E-mail', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-envelope"></i>
      <input type="text" id="lmctlg_user_email" name="lmctlg_user_email" value="" required >
    </div>
  </div>
  
</div>

<div class="r-row">

  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'Password', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-lock"></i>
      <input id="lmctlg_user_pass" name="lmctlg_user_pass" type="password" autocomplete="off" value="" required >
    </div>
  </div>
  
  <div class="c-col c-col-6">
    <label for="textinput"><?php _e( 'Password Again', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-lock"></i>
      <input id="lmctlg_user_pass_again" name="lmctlg_user_pass_again" type="password" autocomplete="off" value="" required >
    </div>
  </div>
  
</div>

</fieldset>

<div class="cw-footer">
  <div class="formsubmit">
    <div class="r-row">
      <div class="c-col c-col-6"> 
         &nbsp;<div class="loading-img"></div>
         </div>
       <div class="c-col c-col-6"> 
        <button class="submit-button buttons submitbutton lime-margin-top-bottom-15" type="submit" id="lmctlg-register-form-submit" name="lmctlg-register-form-submit">
          <i class="glyphicon glyphicon-log-in"></i> &nbsp; <?php _e('Register', 'lime-catalog'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

</form>

</div><!--/ register-form -->

<?php 
} else {
?>

<div class="cw-form-msgs">
<div id="lmctlg_error_id_user_registaration_disabled" class="form-messages alert-danger">
 <?php _e('Sorry! User Registration is disabled.', 'lime-catalog'); ?></div>
</div>

<?php 
}
?>

</div>
<!-- cw-form end -->