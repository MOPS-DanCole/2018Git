<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_additional_shipping {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_additional_shipping() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));
    
      $this->title = MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_TITLE;
      $this->description = MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_DESCRIPTION;
      
      if ( defined('MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_STATUS') ) {
        
        $this->sort_order = MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_STATUS == 'True');
      }
    }

    function execute() {
		global $oscTemplate, $product_info, $languages_id, $HTTP_GET_VARS;

		if ($product_info['products_weight'] > 0) {
		    echo ' <b>-</b> <a data-toggle="modal" data-target="#additionalshippingModal" class="additionalshipping"><i class="fa fa-truck fa-lg"> - Additional Shipping Item</i></a>';
		}	
				        	  
        ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/additional_shipping.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);

      }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_STATUS');
    }

    function install() {  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Module', 'MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_STATUS', 'True', 'Activate Stock Notification Module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values	('Lower stock amount', 'MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_NOTIFY_LOWER_LIMIT', '3', 'This triggers the lower stock amount figure to be displayed', 6, 2, NULL, now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values	('Medium Stock amount', 'MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_NOTIFY_MEDIUM_LIMIT', '10', 'This triggers the Medium stock amount figure to be displayed', 6, 3, NULL, now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
    }
   
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_STATUS', 'MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_NOTIFY_LOWER_LIMIT', 'MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_NOTIFY_MEDIUM_LIMIT', 'MODULE_CONTENT_PRODUCT_INFO_ADDITIONAL_SHIPPING_SORT_ORDER');
    }
  }
