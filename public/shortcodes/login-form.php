
<!-- cw-form start -->
<div class="cw-form cw-form-maxwidth">

<!-- login form -->
<div id="lmctlg-login-form-holder" class="lime-margin-top-15">

<!-- jQuery -->
<div class="show-login-form-return-data"></div>

<div class="textlabel-forms-bold lime-uppercase"><?php echo esc_attr( $atts['title'] ); ?></div>

<form id="lmctlg-login-form" action="" enctype="multipart/form-data" method="post">

<input type="hidden" name="lmctlg-login-form-nonce" value="<?php echo wp_create_nonce('lmctlg_login_form_nonce'); ?>"/> 

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
    <label for="textinput"><?php _e( 'Password', 'lime-catalog' ); ?></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-lock"></i>
      <input type="password" id="lmctlg_password" name="lmctlg_password"value="" required >
    </div>
  </div>
  
</div>

<div class="r-row">

      <div class="c-col c-col-12">
        <label>
            <input type="checkbox" id="lmctlg_remember" name="lmctlg_remember" value="1" >
            <span class="lbl padding-8"><?php _e( 'Remember me', 'lime-catalog' ); ?></span>
        </label>
        
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
        <button class="submit-button buttons submitbutton lime-margin-top-bottom-15" type="submit" id="lmctlg-login-form-submit" name="lmctlg-login-form-submit">
          <i class="glyphicon glyphicon-log-in"></i> &nbsp; <?php _e('Login', 'lime-catalog'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

</form>

</div><!--/ login-form -->

</div>
<!-- cw-form end -->