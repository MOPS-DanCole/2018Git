<?php
/*
  $Id: cm_sc_order_total.php
  $Loc: catalog/includes/modules/content/shopping_cart/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  2015 Order Totals 3.3 BS by @raiwa info@sarplataygemas.com

  based on: shipping estimator
  Original version: Edwin Bekaert (edwin@ednique.com)

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/


  class cm_sc_order_total {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SC_ORDER_TOTAL_TITLE;
      $this->description = MODULE_CONTENT_SC_ORDER_TOTAL_DESCRIPTION;
      $this->description .= '<div class="secWarning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';

      if ( defined('MODULE_CONTENT_SC_ORDER_TOTAL_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SC_ORDER_TOTAL_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SC_ORDER_TOTAL_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $cart, $order, $customer_default_address_id, $billto, $sendto;

      $content_width = (int)MODULE_CONTENT_SC_ORDER_TOTAL_CONTENT_WIDTH;

      if (($cart->count_contents() > 0)) {     	
      	// BOF get taxes if not logged in
		    if ( !tep_session_is_registered('customer_id') ) { //guest
		    	if ( $cart->get_content_type() == 'virtual') {
		    		tep_session_register('billto');
		    		$billto = array('firstname' => null,
		    		 	 						  'lastname' => null,
		    		 	 						  'company' => null,
		    		 	 						  'street_address' => null,
		    		 	 						  'suburb' => null,
		    		 	 						  'postcode' => null,
		    		 	 						  'city' => null,
		    		 	 						  'zone_id' => STORE_ZONE,
		    		 	 						  'zone_name' => null,
		    		 	 						  'country_id' => STORE_COUNTRY,
		    		 	 						  'country_name' => null,
		    		 	 						  'country_iso_code_2' => null,
		    		 	 						  'country_iso_code_3' => null,
		    		 	 						  'address_format_id' => null,
		    		 	 						  'zone_name' => null
		    		 	 						  );
		    		 if (!class_exists('order')) {
		    		 	require('includes/classes/order.php');
		    		 }
		    		 $order = new order;
		      } elseif (defined('MODULE_CONTENT_SC_SHIPPING_STATUS') && MODULE_CONTENT_SC_SHIPPING_STATUS == 'True') {
		    		$products = $cart->get_products();
		    		for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		    			$products_tax = tep_get_tax_rate($products[$i]['tax_class_id'], $order->delivery['country_id'], $order->delivery['zone_id']);
		    			$products_tax_description = tep_get_tax_description($products[$i]['tax_class_id'], $order->delivery['country_id'], $order->delivery['zone_id']);
		    			if ( DISPLAY_PRICE_WITH_TAX == 'true' ) {
		    				//Modified by Strider 42 to correct the tax calculation when a customer is not logged in
		    				$tax_val = (($products[$i]['final_price']/100)*$products_tax)*$products[$i]['quantity'];
		    			} else {
		    				$tax_val = (($products[$i]['final_price']*$products_tax)/100)*$products[$i]['quantity'];
		    			}
		    			if ( !empty($order->info['tax']) ) {
		    				$order->info['tax'] += $tax_val;
		    			} else {
		    				$order->info['tax'] = $tax_val;
		    			}
		    			if ( !empty($order->info['tax_groups']) ) {
		    				$order->info['tax_groups']["$products_tax_description"] += $tax_val;
		    			} else {
		    				$order->info['tax_groups']["$products_tax_description"] = $tax_val;
		    			}
		    			
		    			// Modified by Strider 42 to correct the order total figure when shop displays prices with tax
		    			if ( DISPLAY_PRICE_WITH_TAX == 'true' ) {
		    				$order->info['total'];
		    			} else {
		    				$order->info['total']+=$tax_val;
		    			}
		    		}
		      } else {
		      	if (!tep_session_is_registered('sendto')) {
		      		tep_session_register('sendto');
		      		$sendto = array('firstname' => null,
		    		 	 						  	'lastname' => null,
		    		 	 						  	'company' => null,
		    		 	 						  	'street_address' => null,
		    		 	 						  	'suburb' => null,
		    		 	 						  	'postcode' => null,
		    		 	 						  	'city' => null,
		    		 	 						  	'zone_id' => STORE_ZONE,
		    		 	 						  	'zone_name' => null,
		    		 	 						  	'country_id' => STORE_COUNTRY,
		    		 	 						  	'country_name' => null,
		    		 	 						  	'country_iso_code_2' => null,
		    		 	 						  	'country_iso_code_3' => null,
		    		 	 						  	'address_format_id' => null,
		    		 	 						  	'zone_name' => null
		    		 	 						  	);
		    		 	if (!class_exists('order')) {
		    		 		require('includes/classes/order.php');
		    		 	}
		    		 	$order = new order;
		    		}
		    	}
		    } else {
		    	if ( $cart->get_content_type() == 'virtual' && (!defined('MODULE_CONTENT_SC_SHIPPING_STATUS') || (defined('MODULE_CONTENT_SC_SHIPPING_STATUS') && MODULE_CONTENT_SC_SHIPPING_STATUS != 'True')) ) {
		    		tep_session_register('billto');
		    		$billto = $customer_default_address_id;
		    	}
		    	if (!class_exists('order')) {
		    		require('includes/classes/order.php');
		    	}
		    	$order = new order;
		    }
		    	
			// MOPS added condition for discount coupon module
			$tax_on_discount = ($order->coupon->applied_discount["Unknown tax rate"] * $products_tax/100);
			$order->info['tax'] -= $tax_on_discount;
			$order->info['tax_groups']["$products_tax_description"] -= $tax_on_discount;
			$order->info['total'] -= $tax_on_discount;
						
		    	// EOF get taxes if not logged in (seems like less code than in order class)
		    	require('includes/classes/order_total.php');
		    	$order_total_modules = new order_total;
		    	$order_total_modules->process();

		    	if ( MODULE_ORDER_TOTAL_INSTALLED ) {
		    		$sc_order_total = '<div class="panel panel-default">';
		    		$sc_order_total .= '	<div class="panel-heading">';
		    		$sc_order_total .= '		<h3 class="panel-title">' . MODULE_CONTENT_SC_ORDER_TOTAL_MODULE_TITLE . '</h3>'; // BS panel heading
		    		$sc_order_total .= '	</div>';
		    		$sc_order_total .= '	<div class="panel-body">';
		    		$sc_order_total .= '		<table class="pull-right">';
		    		$sc_order_total .= 			$order_total_modules->output();
		    		$sc_order_total .= '		</table>';
		    		$sc_order_total .= ' </div>'; // end body
		    		$sc_order_total .= '</div>'; //end panel
 		    	}

		  
      ob_start();
        include('includes/modules/content/' . $this->group . '/templates/order_total.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
		  } // Use only when cart_contents > 0
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_SC_ORDER_TOTAL_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Order Total Module', 'MODULE_CONTENT_SC_ORDER_TOTAL_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_ORDER_TOTAL_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_ORDER_TOTAL_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.<br>Recommended to show this module last.', '6', '3', now())");
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_SC_ORDER_TOTAL_STATUS', 'MODULE_CONTENT_SC_ORDER_TOTAL_CONTENT_WIDTH', 'MODULE_CONTENT_SC_ORDER_TOTAL_SORT_ORDER');
    }
  }
