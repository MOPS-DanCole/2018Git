<?php
/*
  $Id:
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class cm_sc_discount_coupon {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SC_DISCOUNT_COUPON_TITLE;
      $this->description = MODULE_CONTENT_SC_DISCOUNT_COUPON_DESCRIPTION;
      
      if ( defined('MODULE_CONTENT_SC_DISCOUNT_COUPON_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SC_DISCOUNT_COUPON_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SC_DISCOUNT_COUPON_STATUS == 'True');
      }
    }
	
    function execute() {
      global $oscTemplate, $cart, $order;

      $content_width = (int)MODULE_CONTENT_SC_DISCOUNT_COUPON_CONTENT_WIDTH;
	  
	  if ($cart->count_contents() > 0) {
		
		$sc_discount_coupon = NULL;
		$sc_discount_coupon =  '<div class="panel panel-default">';
        $sc_discount_coupon .= '	<div class="panel-heading">';
        $sc_discount_coupon .= '		<h3 class="panel-title">' . MODULE_CONTENT_SC_DISCOUNT_COUPON_HEADER_TITLE . '</h3>'; // BS panel heading
        $sc_discount_coupon .= '	</div>';
        $sc_discount_coupon .= '  <div class="panel-body">';
		$sc_discount_coupon .=     tep_draw_form(coupon, 'dc_redirect.php', 'post');
  		$sc_discount_coupon .= '    <div class="alert sc-discount-coupon">' . TEXT_ENTER_DISCOUNT_COUPON_INFORMATION . '  ' . tep_draw_input_field('coupon', NULL, 'name="$coupon" placeholder="Enter Coupon Code", $coupon');
		$sc_discount_coupon .= '         <button type="submit" class="btn btn-default" value="coupon"><i class="glyphicon glyphicon-log-in"></i> Apply</button>';
		$sc_discount_coupon .= '    </div>';							
		$sc_discount_coupon .= '   </form>';
		$sc_discount_coupon .= '  </div>'; 
		$sc_discount_coupon .= '</div>';
    
      ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/discount_coupon.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
     }
	} // eof execute

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_SC_DISCOUNT_COUPON_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Discount Coupon Module', 'MODULE_CONTENT_SC_DISCOUNT_COUPON_STATUS', 'True', 'Do you want to add the module to your shopping cart?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_DISCOUNT_COUPON_CONTENT_WIDTH', '4', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_DISCOUNT_COUPON_SORT_ORDER', '600', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_SC_DISCOUNT_COUPON_STATUS', 'MODULE_CONTENT_SC_DISCOUNT_COUPON_CONTENT_WIDTH', 'MODULE_CONTENT_SC_DISCOUNT_COUPON_SORT_ORDER');
    }
  }
