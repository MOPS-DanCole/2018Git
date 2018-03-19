<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */
?>

  <div class="panel dashboard dashboard panel-<?php echo $panel_style; ?> d-basket">
    <div class="panel-heading"><strong><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo MODULE_CONTENT_DASHBOARD_BASKET_HEADING; ?></strong></div>
      <div class="panel-body">
	    <ul class="list-unstyled">
          <?php echo $cart_contents_string; ?>
        </ul>
	  </div>
	  <div class="panel-footer"><?php echo tep_draw_button(MODULE_CONTENT_DASHBOARD_BASKET_BUTTON_MORE, 'fa fa-shopping-cart', tep_href_link('shopping_cart.php', '', 'SSL'), 'primary', NULL, 'btn-' . $panel_style . ' btn-xs'); ?></div> 
  </div>

