<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

  class cm_d_recent {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_DASHBOARD_RECENT_TITLE;
      $this->description = MODULE_CONTENT_DASHBOARD_RECENT_DESCRIPTION;

      if ( defined('MODULE_CONTENT_DASHBOARD_RECENT_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_DASHBOARD_RECENT_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_DASHBOARD_RECENT_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer, $languages_id, $customer_id, $PHP_SELF;
      
      $content_width = MODULE_CONTENT_DASHBOARD_RECENT_CONTENT_WIDTH;
	  
	  $panel_style = MODULE_CONTENT_DASHBOARD_RECENT_PANEL_STYLE;

      $cart_contents_string = '';
	  
	  $show_button = false;

     if (sizeof($customer->products) > 0) {
        $recent_contents_string = NULL;
        $products = $customer->products;
		//$recent_contents_string .= '<li><div><pre>' . var_dump($customer->products) . '</pre></div></li>';
	    for ($i=0, $n=sizeof($customer->products); $i<$n; $i++) {

          $recent_contents_string .= '<li>';

          $recent_contents_string .= '<a href="' . tep_href_link('product_info.php', 'products_id=' . $products[$i]['id']) . '">';

          $recent_contents_string .= $products[$i]['name'];

          $recent_contents_string .= '</a><span class="pull-right"><a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products[$i]['id']) . '" class=""><span data-id-product="' . $products[$i]['id'] . '" class="fa fa-cart-plus btn-buy-dashboard"></span></a></span></li>';
		  
        }
      }
	  
	  if (sizeof($customer->products) > MODULE_CONTENT_DASHBOARD_RECENT_NUMBER) {
		  $show_button = true;
	  }

		ob_start();
		include('includes/modules/content/' . $this->group . '/templates/recent.php');
		$template = ob_get_clean(); 
        
		$oscTemplate->addContent($template, $this->group);	  		
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_DASHBOARD_RECENT_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customer Orders Module', 'MODULE_CONTENT_DASHBOARD_RECENT_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number to show', 'MODULE_CONTENT_DASHBOARD_RECENT_NUMBER', '4', 'How many recently ordered products do you want to display to the customer.', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Panel Style', 'MODULE_CONTENT_DASHBOARD_RECENT_PANEL_STYLE', 'info', 'What colour scheme would you like for this module? (info = light blue, primary = dark blue, success = green, warning = yellow, danger = red).', '6', '1', 'tep_cfg_select_option(array(\'info\', \'primary\', \'success\', \'warning\', \'danger\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_DASHBOARD_RECENT_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_DASHBOARD_RECENT_STATUS', 'MODULE_CONTENT_DASHBOARD_RECENT_NUMBER', 'MODULE_CONTENT_DASHBOARD_RECENT_PANEL_STYLE', 'MODULE_CONTENT_DASHBOARD_RECENT_SORT_ORDER');
    }
  }