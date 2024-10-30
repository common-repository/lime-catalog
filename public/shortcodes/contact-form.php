
<!-- cw-form start -->
<div class="cw-form cw-form-maxwidth">

<!-- jQuery -->
<div class="show-contact-form-return-data"></div>

<!-- contact form -->
<div id="lmctlg-contact-form-holder" class="lime-margin-top-15">

<div class="textlabel-forms-normal"><?php echo esc_attr( $atts['title'] ); ?></div>

<form id="lmctlg-contact-form" action="" enctype="multipart/form-data" method="post">

<input type="hidden" name="lmctlg-contact-form-nonce" value="<?php echo wp_create_nonce('lmctlg_contact_form_nonce'); ?>"/> 

<fieldset>

<div class="r-row">

  <div class="c-col c-col-5">
    <label for="lmctlg_firstname"><?php _e('First Name', 'lime-catalog'); ?> <span class="reqmark">*</span></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-user"></i>
      <input id="lmctlg_firstname" name="lmctlg_firstname" type="text" value="" required>
    </div>
  </div>
  
  <div class="c-col c-col-5">
    <label for="lmctlg_lastname"><?php _e('Last Name', 'lime-catalog'); ?> <span class="reqmark">*</span></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-user"></i>
      <input id="lmctlg_lastname" name="lmctlg_lastname" type="text" value="" required>
    </div>
  </div>

</div>

<div class="r-row">

  <div class="c-col c-col-5">
    <label for="lmctlg_email"><?php _e('E-mail', 'lime-catalog'); ?> <span class="reqmark">*</span></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-envelope"></i>
      <input id="lmctlg_email" name="lmctlg_email" type="email" value="" required>
    </div>
  </div>
  
    <div class="c-col c-col-5">
    <label for="lmctlg_telephone"><?php _e('Telephone', 'lime-catalog'); ?> </label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-phone"></i>
      <input id="lmctlg_telephone" name="lmctlg_telephone" type="text" value="">
    </div>
    </div>

</div>

<div class="r-row">

  <div class="c-col c-col-11">
    <label for="lmctlg_subject"><?php _e('Subject', 'lime-catalog'); ?> <span class="reqmark">*</span></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-tag"></i>
      <input id="lmctlg_subject" name="lmctlg_subject" type="text" value="" required>
    </div>
  </div>
  
</div>

<div class="r-row">

  <div class="c-col c-col-12">
    <label for="lmctlg_message"><?php _e('Message', 'lime-catalog'); ?> <span class="reqmark">*</span></label>
    <div class="inner-addon left-addon">
       <i class="glyphicon glyphicon-comment"></i>
      <textarea id="lmctlg_message" name="lmctlg_message" spellcheck="true" rows="10" placeholder="" required ></textarea>
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
        <button class="submit-button buttons submitbutton lime-margin-top-bottom-15" type="submit" id="lmctlg-contact-form-submit" name="lmctlg-contact-form-submit">
          <i class="glyphicon glyphicon-log-in"></i> &nbsp; <?php _e('Send', 'lime-catalog'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

</form>

</div><!--/ contact-form -->

</div>
<!-- cw-form end -->