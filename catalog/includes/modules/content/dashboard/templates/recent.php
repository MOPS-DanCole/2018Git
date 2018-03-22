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
  <div class="panel dashboard dashboard panel-<?php echo $panel_style; ?> d-recent">
    <div class="panel-heading"><strong><i class="fa fa-clone"></i>&nbsp;<?php echo MODULE_CONTENT_DASHBOARD_RECENT_HEADING; ?></strong></div>
      <div class="panel-body">
	    <ul class="list-unstyled">
          <?php echo $recent_contents_string; ?>
        </ul>
	  </div>
	  <div class="panel-footer">
	  <?php 
	  if($show_button == true) {
		  echo tep_draw_button(MODULE_CONTENT_DASHBOARD_RECENT_BUTTON_MORE, 'fa fa-list-ul ', tep_href_link('account_ordered.php', 'SSL'), 'primary', NULL, 'btn-' . $panel_style . ' btn-xs');
	  }	else {
		  echo tep_draw_button(MODULE_CONTENT_DASHBOARD_RECENT_ORDERS_BUTTON_MORE, 'fa fa-file', tep_href_link('account_history.php', '', 'SSL'), 'primary', NULL, 'btn-' . $panel_style . ' btn-xs');
	  }	  
	  ?>
	  </div> 
  </div>

