<?php
/*
  $Id$ cm_sc_checkout.php
  $Loc: catalog/includes/modules/content/shopping_cart/
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  2016 2 Page Checkout Shipping Module 1.1 BS 
  by @raiwa 
  info@sarplataygemas.com
  www.oscaddons.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class cm_sc_checkout {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SC_CHECKOUT_TITLE;
      $this->description = MODULE_CONTENT_SC_CHECKOUT_DESCRIPTION;

      if ( defined('MODULE_CONTENT_SC_CHECKOUT_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SC_CHECKOUT_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SC_CHECKOUT_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $payment_modules, $any_out_of_stock, $cart;

      $content_width = (int)MODULE_CONTENT_SC_CHECKOUT_CONTENT_WIDTH;
	  
	    if ($cart->count_contents() > 0) {
	    	
	    	$sc_checkout = '<div class="buttonSet">
	    										<a href="' . tep_href_link(MODULE_CONTENT_SC_CHECKOUT_LINK, '', 'SSL') . '" class="btn btn-success">' . IMAGE_BUTTON_CHECKOUT . ' <span class="fa fa-angle-right"></span></a>' . 
      								 '</div>';
		  
      	ob_start();
      	include('includes/modules/content/' . $this->group . '/templates/checkout.php');
      	$template = ob_get_clean();

      	$oscTemplate->addContent($template, $this->group);
      } // end if $cart->count_contents() > 0
    }

    function  isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_SC_CHECKOUT_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Checkout Button', 'MODULE_CONTENT_SC_CHECKOUT_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");	
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_CHECKOUT_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Checkout button Link', 'MODULE_CONTENT_SC_CHECKOUT_LINK', 'checkout_payment.php', 'Which page the checkout button will link to.', '6', '1', 'tep_cfg_select_option(array(\'checkout_shipping.php\', \'checkout_payment.php\', \'checkout_confirmation.php\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_CHECKOUT_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . "  where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_SC_CHECKOUT_STATUS', 'MODULE_CONTENT_SC_CHECKOUT_CONTENT_WIDTH', 'MODULE_CONTENT_SC_CHECKOUT_LINK', 'MODULE_CONTENT_SC_CHECKOUT_SORT_ORDER');
    }
  }
?>
