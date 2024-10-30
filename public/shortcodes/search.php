    <!-- search-form -->
    <div class="lime-search-form">
    <form role="search" id="quicksearchform"  method="get" action="<?php echo esc_attr( site_url('/') ); ?>">     
    <input type="hidden" name="post_type" value="limecatalog" /> <!-- // hidden 'limecatalog' value -->          
    <span class="fieldsgroup">
    <input type="text" name="s" placeholder="Search Products"/>
    <!--<input type="submit" value="Go" id="submit-button" >-->
    </span>
    </form>
    </div>
    <!-- search-form end -->