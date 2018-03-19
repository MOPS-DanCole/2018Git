<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_stock_alert {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_stock_alert() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

    
      $this->title = MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_TITLE;
      $this->description = MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_DESCRIPTION;
      
      if ( defined('MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_STATUS') ) {
        
        $this->sort_order = MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_STATUS == 'True');
      }
    }

    function execute() {
		 global $oscTemplate, $product_info, $languages_id, $HTTP_GET_VARS;

		 //$product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
	     //$product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.short_desc, p.products_special_order_item, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_weight, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' AND (p.products_site = 'ALL' OR p.products_site = 'CAD') AND p.products_category = '" . STORE_SITE . "' ");

		 //$product_info = tep_db_fetch_array($product_info_query);
		
		 $stock = '';
		
		if(tep_session_is_registered('notify')) {
		 
		 $stock .= '<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert">×</button>
						' .  SUCCESS_TEXT1 . '' . $product_info['products_name'] . ' ' . SUCCESS_TEXT2 . '
					</div>';
					
			tep_session_unregister('notify');
        }
         
		if (($product_info['products_special_order_item'] == 3) && ($product_info['products_quantity'] < 1 )) {
			$stock .= '<span class="product_info"><b><font color="RED">Available for pickup only - Currently Back Ordered.</font></b></span>';
		} 
		elseif(($product_info['products_special_order_item'] == 3) && ($product_info['products_quantity'] > 0 )) {
		    $stock .= '<span class="product_info"><b><font color=#5A2BB1>Available for pickup only.</font></b></span>';
		}
        elseif(($product_info['products_special_order_item'] == 2) && ($product_info['products_quantity'] < 1 )) {
		    $stock .= '<span class="product_info"><b><font color=#33cc00>In Stock.</font></b></span>';
		}
        elseif(($product_info['products_special_order_item'] == 4) && ($product_info['products_quantity'] < 1 )) {
		    $stock .= '<a data-toggle="modal" data-target="#dropshipModal" class="soldout"><span class="drop-ship">'  . DROP_SHIP_MESSAGE . '</span></a>';
		}
		elseif(($product_info['products_special_order_item'] == 1) && ($product_info['products_quantity'] < 1 )) {
		    $stock .= '<a data-toggle="modal" data-target="#2to14Modal" class="soldout"><span class="drop-ship">'  . TWO_TO_SEVEN_MESSAGE . '</span></a>';
		}
		elseif(($product_info['products_special_order_item'] != 3) && ($product_info['products_quantity'] > 0)) {
		    $stock .= '<span class="product_info"><b><font color=#33cc00>In Stock</font></b></span>';
		}
		else {
			$stock .= '<a data-toggle="modal" data-target="#outofstockModal" class="soldout"><span class="mops-link">'  . NO_STOCK_MESSAGE . '</span></a>';
		}
		
        	  
        ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/stock_alert.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);

      }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Module', 'MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_STATUS', 'True', 'Activate Stock Notification Module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values	('Lower stock amount', 'MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_NOTIFY_LOWER_LIMIT', '3', 'This triggers the lower stock amount figure to be displayed', 6, 2, NULL, now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values	('Medium Stock amount', 'MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_NOTIFY_MEDIUM_LIMIT', '10', 'This triggers the Medium stock amount figure to be displayed', 6, 3, NULL, now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");

//	  tep_db_query("drop table if exists stock_notification");
//	  tep_db_query("create table if not exists stock_notification (
//					snid INT(11) NOT NULL AUTO_INCREMENT, 
//					sn_date VARCHAR(50) NULL DEFAULT NULL, 
//					sn_notify INT(1) NOT NULL,
//					sn_email VARCHAR(50) NOT NULL,
//					product_id VARCHAR(10) NOT NULL,
//					sn_name VARCHAR(50) NOT NULL,
//					sn_P_name VARCHAR(100) NULL DEFAULT NULL,
//					PRIMARY KEY (snid),
//					INDEX idx_snid (snid) )
//					");			
//
    }
   
  
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
//     tep_db_query("drop table if exists stock_notification");
    }

    function keys() {
      return array('MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_STATUS', 'MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_NOTIFY_LOWER_LIMIT', 'MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_NOTIFY_MEDIUM_LIMIT', 'MODULE_CONTENT_PRODUCT_INFO_STOCK_ALERT_SORT_ORDER');
    }
  }
