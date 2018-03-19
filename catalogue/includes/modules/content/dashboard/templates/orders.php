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

  <div class="panel dashboard panel-<?php echo $panel_style; ?> d-orders">
    <div class="panel-heading"><strong><i class="fa fa-list-alt"></i>&nbsp;<?php echo MODULE_CONTENT_DASHBOARD_ORDERS_HEADING; ?></strong></div>
      <table class="table table-hover">
	    <tr>
		  <th><?php echo MODULE_CONTENT_DASHBOARD_ORDERS_ORDER_NUMBER; ?></th>
		  <th><?php echo MODULE_CONTENT_DASHBOARD_ORDERS_DATE; ?></th>
		  <th class="hidden-xs"><?php echo MODULE_CONTENT_DASHBOARD_ORDERS_NAME; ?></th>
		  <th class="hidden-xs"><?php echo MODULE_CONTENT_DASHBOARD_ORDERS_LINES; ?></th>
		  <th class="hidden-xs"><?php echo MODULE_CONTENT_DASHBOARD_ORDERS_COST; ?></th>
		  <th><?php echo MODULE_CONTENT_DASHBOARD_ORDERS_ORDER_STATUS; ?></th>
		</tr>
        <?php echo $order_lines; ?>
		
      </table>
	<div class="panel-footer"><?php echo tep_draw_button(MODULE_CONTENT_DASHBOARD_ORDERS_BUTTON_MORE, 'fa fa-file', tep_href_link('account_history.php', 'SSL'), 'primary', NULL, 'btn-' . $panel_style . ' btn-xs'); ?></div>  
  </div>

