<?php
/*
  $Id: spu.php,v 1.6 2016/03/27 mbs Exp $
  v. 1.6 by @raiwa info@sarplataygemas.com
  changes: 
  - variable digits support
  - use $order->delivery['postcode'] instead of customers main address postcode 
  - fix for non working list of various postcodes
  - tested with shipping estimator "ship in cart" http://addons.oscommerce.com/info/9066
  
  CONTRIB is Store Pickup Shipping Module (http://www.oscommerce.com/community/contributions,164)
  Based upon flat.php / spu.php by M. Halvorsen (http://www.arachnia-web.com)

  Made to work with latest check-out procedure by Matthijs (Mattice)
     >> e-mail:    mattice@xs4all.nl 
     >> site:      http://www.matthijs.org
 
  TO TRANSLATE IN GERMAN !!
  
osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
   CHANGES:
   - formatted to work with latest checkout procedure
   - removed icon references
   - updated the db queries

  
*/

  class spu {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function spu() {
	  global $order;
	
      $this->code = 'spu';
      $this->title = MODULE_SHIPPING_SPU_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_SPU_TEXT_DESCRIPTION;
  	  $this->sort_order = MODULE_SHIPPING_SPU_SORT_ORDER;
      $this->icon = '';
      $this->enabled = ((MODULE_SHIPPING_SPU_STATUS == 'True') ? true : false);
	  
    }
    
    function quote($method = '') {
    	global $order;  
	  
      $error = false;

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_SPU_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_SPU_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }
        if ($check_flag == false) {
          $error = true;
        }
      }
      
    	$dept_allow = explode(',', MODULE_SHIPPING_SPU_ZIP);
    	$cust_cp = substr($order->delivery['postcode'], 0, strlen($dept_allow['0']));

    	if ( !in_array($cust_cp, $dept_allow) && MODULE_SHIPPING_SPU_ZIP != '' ) {    	  
    	   $error = true;    	  
    	}

    	if ($error == false) {
    	  $this->quotes = array('id' => $this->code,
                          'module' => MODULE_SHIPPING_SPU_TEXT_TITLE,
                         'methods' => array(array('id' => $this->code,
                           'title' => MODULE_SHIPPING_SPU_TEXT_WAY,
                            'cost' =>  MODULE_SHIPPING_SPU_COST)));
        
        return $this->quotes; 
      } else {
        $this->quotes['error'] = ' ';
        return $this->quotes; 
      }
    
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);   

    }
	
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_SPU_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
	  }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Store Pick Up', 'MODULE_SHIPPING_SPU_STATUS', 'True', 'Do you want to offer Store Pickup?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store Pickup Cost', 'MODULE_SHIPPING_SPU_COST', '0.00', 'What is the pickup cost? (The Handling fee will NOT be added.)', '6', '0', now())");
  	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_SPU_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");																																																																																										  	  
  	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store Pick Up Zip Code Allowed', 'MODULE_SHIPPING_SPU_ZIP', '01,07,26,38,42,69,74', 'Comma separated values. All entries must have the same amount of digits. Example: 07,58,69 or 256,589,789', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_SPU_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    }


    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }

    function keys() {
      return array('MODULE_SHIPPING_SPU_STATUS', 'MODULE_SHIPPING_SPU_COST', 'MODULE_SHIPPING_SPU_SORT_ORDER', 'MODULE_SHIPPING_SPU_ZONE', 'MODULE_SHIPPING_SPU_ZIP');
    }
  }
?>