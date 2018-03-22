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
  <div class="panel dashboard panel-<?php echo $panel_style; ?> d-address">
    <div class="panel-heading"><strong><i class="fa fa-home"></i>&nbsp;<?php echo MODULE_CONTENT_DASHBOARD_ADDRESS_HEADING; ?></strong></div>
      <div class="panel-body">
		<div><strong><?php echo MODULE_CONTENT_DASHBOARD_ADDRESS_DETAILS; ?></strong></div>
	    <?php echo tep_address_label($customer_id, $customer->details['default_address_id'], true, ' ', '<br />'); ?>
	  </div>
	  <div class="panel-footer"><?php echo tep_draw_button(MODULE_CONTENT_DASHBOARD_ADDRESS_BUTTON_MORE, 'fa fa-address-card-o', tep_href_link('address_book.php', 'SSL'), 'primary', NULL, 'btn-' . $panel_style . ' btn-xs'); ?></div>
  </div>
