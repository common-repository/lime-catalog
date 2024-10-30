
        <!-- payment button -->
        <div class="lmctlg-payment-buttons-wrapper">       
            <form class="lmctlg-payment-buttons-form-class" action="" method="post" id="lmctlg-payment-buttons-form">
              <input type="hidden" name="lmctlg-payment-buttons-form-nonce" value="<?php echo wp_create_nonce('lmctlg_payment_buttons_form_nonce'); ?>"/>
              <input type="hidden" name="lmctlg_item_id" id="lmctlg_item_id" value="<?php echo esc_attr( $postid ); ?>"/>
              <input type="hidden" name="lmctlg_item_price" value="<?php echo esc_attr( $item_sale_price_hidden ); ?>"/>
              <input type="hidden" name="lmctlg_item_name" value="<?php echo esc_attr( get_the_title( $postid ) ); ?>"/>
              <input type="hidden" name="lmctlg_item_downloadable" value="<?php echo esc_attr( $item_downloadable ); ?>"/>
            
                <div class="lmctlg-payment-button-1" id="lmctlg-payment-button-1">
                
                    <div class="lmctlg-item-quantity-input"> 
                        <input type="number"  max="" min="1" value="1" id="lmctlg_item_quantity" name="lmctlg_item_quantity"> 
                    </div> 
                    
                    <div class="lmctlg-payment-button-1-submit"> 
                    <button type="submit" class="btn-lime btn-lime-md btn-lime-orange" > 
                    <?php echo ' ' . $item_sale_price_public . ' '; esc_attr_e('- Add to Cart', 'lime-catalog'); ?>
                    </button>
                    </div>
                    
                </div>
                
                <div class="lmctlg-payment-button-2" id="lmctlg-payment-button-2">
                    <a href="<?php echo esc_url( $limecatalogurl . '?page=cart' ); ?>">
                    <button type="button" class="btn-lime btn-lime-md btn-lime-green" > 
                    <?php esc_attr_e('+ View Cart', 'lime-catalog'); ?> 
                    </button>
                    </a>
                </div>
               
               <!--
                <div class="lmctlg-item-variable-prices">
                    <label>
                      <select id="lmctlg_item_price_AAAAAAAAAAA" name="lmctlg_item_price_AAAAAAAAAAA" required>
                        <option value="01">Single Site - $29.00</option>
                        <option value="02">2-5 Sites - $49.00</option>
                        <option value="02">6-10 Sites - $79.00</option>
                        <option value="03">Unlimited Sites - $129.00</option>
                      </select>
                    </label>
                </div>
                --> 
            
            </form>
        </div><!--/ payment button -->