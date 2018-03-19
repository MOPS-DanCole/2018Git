<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

  class cm_d_address {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_DASHBOARD_ADDRESS_TITLE;
      $this->description = MODULE_CONTENT_DASHBOARD_ADDRESS_DESCRIPTION;

      if ( defined('MODULE_CONTENT_DASHBOARD_ADDRESS_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_DASHBOARD_ADDRESS_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_DASHBOARD_ADDRESS_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer, $languages_id, $customer_id;
      
      $content_width = MODULE_CONTENT_DASHBOARD_ADDRESS_CONTENT_WIDTH;
	  
	  $panel_style = MODULE_CONTENT_DASHBOARD_ADDRESS_PANEL_STYLE;
	  
	  global $oscTemplate;

		ob_start();
		include('includes/modules/content/' . $this->group . '/templates/address.php');
		$template = ob_get_clean(); 
        
		$oscTemplate->addContent($template, $this->group);	  		
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_DASHBOARD_ADDRESS_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customer Orders Module', 'MODULE_CONTENT_DASHBOARD_ADDRESS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Panel Style', 'MODULE_CONTENT_DASHBOARD_ADDRESS_PANEL_STYLE', 'info', 'What colour scheme would you like for this module? (info = light blue, primary = dark blue, success = green, warning = yellow, danger = red).', '6', '1', 'tep_cfg_select_option(array(\'info\', \'primary\', \'success\', \'warning\', \'danger\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_DASHBOARD_ADDRESS_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_DASHBOARD_ADDRESS_STATUS', 'MODULE_CONTENT_DASHBOARD_ADDRESS_PANEL_STYLE', 'MODULE_CONTENT_DASHBOARD_ADDRESS_SORT_ORDER');
    }
  }