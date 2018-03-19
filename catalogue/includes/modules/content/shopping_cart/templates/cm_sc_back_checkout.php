<?php
/*
  $Id$
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2016 osCommerce
 
  Released under the GNU General Public License
*/
?>

<div id="cm_sc_back_checkout" class="col-sm-<?php echo (int)MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_CONTENT_WIDTH; ?>">
	 <div class="buttonSet">
    <?php
      if (isset($navigation->path[$back])) {
        echo tep_draw_button(IMAGE_BUTTON_CONTINUE_SHOPPING, 'fa fa-angle-double-left', tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']), 'primary', NULL, 'btn-primary' );
      } else {
        echo tep_draw_button(IMAGE_BUTTON_CONTINUE_SHOPPING, 'fa fa-angle-double-left', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'), 'primary', NULL, 'btn-primary' );
      }
    ?>
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CHECKOUT, 'fa fa-angle-right', tep_href_link(MODULE_CONTENT_SC_CHECKOUT_LINK, '', 'SSL'), 'primary', NULL, 'btn-success'); ?></span>
  </div>
</div>