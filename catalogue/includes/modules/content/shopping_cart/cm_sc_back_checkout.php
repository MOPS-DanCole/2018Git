<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_sc_back_checkout {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_TITLE;
      $this->description = MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_DESCRIPTION;

      if ( defined('MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $cart, $navigation;
	  
      if ($cart->count_contents() > 0) {
        $back = sizeof($navigation->path)-2;

        ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/' . basename(__FILE__));
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
        
      } // end if $cart->count_contents() > 0
    }

    function  isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_STATUS');
    }

    function install() {
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Order SubTotal', 'MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");	
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_STATUS', 'MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_CONTENT_WIDTH', 'MODULE_CONTENT_SHOPPING_CART_BACK_CHECKOUT_SORT_ORDER');
    }
  }
?>
