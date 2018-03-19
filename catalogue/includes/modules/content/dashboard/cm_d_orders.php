<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

  class cm_d_orders {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_DASHBOARD_ORDERS_TITLE;
      $this->description = MODULE_CONTENT_DASHBOARD_ORDERS_DESCRIPTION;

      if ( defined('MODULE_CONTENT_DASHBOARD_ORDERS_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_DASHBOARD_ORDERS_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_DASHBOARD_ORDERS_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer, $languages_id, $customer_id;
      
      $content_width = MODULE_CONTENT_DASHBOARD_ORDERS_CONTENT_WIDTH;
	  
	  $panel_style = MODULE_CONTENT_DASHBOARD_ORDERS_PANEL_STYLE;
      
      $orders_total = tep_count_customer_orders();
	  
	  $order_lines = '';
		if ($orders_total > 0) {
		  $history_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from orders o, orders_total ot, orders_status s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1' order by orders_id DESC LIMIT " . MODULE_CONTENT_DASHBOARD_ORDERS_NUMBER . "");
		  while ($history = tep_db_fetch_array($history_query)) {
			$products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
			$products = tep_db_fetch_array($products_query);

			if (tep_not_null($history['delivery_name'])) {
			  $order_type = 's';
			  $order_name = $history['delivery_name'];
			} else {
			  $order_type = 'b';
			  $order_name = $history['billing_name'];
			}
			$order_lines .= '	    <tr>' . "\n" . 
							'	      <td><a href="' . tep_href_link('account_history_info.php', 'order_id=' . $history['orders_id'], 'SSL') . '">' . $history['orders_id'] . '</a></td>' . "\n" . 
							'	      <td>' . tep_date_short($history['date_purchased']) . '</td>' . "\n" . 
							'	      <td class="hidden-xs">' . tep_output_string_protected($order_name) . '</td>' . "\n" . 
							'	      <td class="hidden-xs">' . $products['count'] . '</td>' . "\n" . 
							'	      <td class="hidden-xs">' . strip_tags($history['order_total']) . '</td>' . "\n" . 
							'	      <td>' . $history['orders_status_name'] . '</td>' . "\n" . 
							'	    <tr>' . "\n";
		  }
      
		  $data = $order_lines;
		  ob_start();
		  include('includes/modules/content/' . $this->group . '/templates/orders.php');
		  $template = ob_get_clean(); 
        
		  $oscTemplate->addContent($template, $this->group);
		}	  		
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_DASHBOARD_ORDERS_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customer Orders Module', 'MODULE_CONTENT_DASHBOARD_ORDERS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number to show', 'MODULE_CONTENT_DASHBOARD_ORDERS_NUMBER', '4', 'How many orders do you want to display to the customer.', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Panel Style', 'MODULE_CONTENT_DASHBOARD_ORDERS_PANEL_STYLE', 'info', 'What colour scheme would you like for this module? (info = light blue<br> primary = dark blue, success = green, warning = yellow, danger = red).', '6', '1', 'tep_cfg_select_option(array(\'info\', \'primary\', \'success\', \'warning\', \'danger\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_DASHBOARD_ORDERS_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_DASHBOARD_ORDERS_STATUS', 'MODULE_CONTENT_DASHBOARD_ORDERS_NUMBER', 'MODULE_CONTENT_DASHBOARD_ORDERS_PANEL_STYLE', 'MODULE_CONTENT_DASHBOARD_ORDERS_SORT_ORDER');
    }
  }