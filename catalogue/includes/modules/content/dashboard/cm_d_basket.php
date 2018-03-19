<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

  class cm_d_basket {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_DASHBOARD_BASKET_TITLE;
      $this->description = MODULE_CONTENT_DASHBOARD_BASKET_DESCRIPTION;

      if ( defined('MODULE_CONTENT_DASHBOARD_BASKET_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_DASHBOARD_BASKET_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_DASHBOARD_BASKET_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer, $languages_id, $customer_id, $cart, $new_products_in_cart, $currencies;
      
      $content_width = MODULE_CONTENT_DASHBOARD_BASKET_CONTENT_WIDTH;
	  
	  $panel_style = MODULE_CONTENT_DASHBOARD_BASKET_PANEL_STYLE;

      $cart_contents_string = '';

      if ($cart->count_contents() > 0) {
        $cart_contents_string = NULL;
        $products = $cart->get_products();
        for ($i=0, $n=sizeof($products); $i<$n; $i++) {

          $cart_contents_string .= '<li';
          if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
            $cart_contents_string .= ' class="newItemInCart"';
          }
          $cart_contents_string .= '>';

          $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;';

          $cart_contents_string .= '<a href="' . tep_href_link('product_info.php', 'products_id=' . $products[$i]['id']) . '">';

          $cart_contents_string .= $products[$i]['name'];

          $cart_contents_string .= '</a></li>';

          if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
            tep_session_unregister('new_products_id_in_cart');
          }
        }

        $cart_contents_string .= '<li class="text-right"><hr>' . $currencies->format($cart->show_total()) . '</li>';

      } else {
        $cart_contents_string .= '<p>' . MODULE_CONTENT_DASHBOARD_BASKET_EMPTY . '</p>';
      }

		ob_start();
		include('includes/modules/content/' . $this->group . '/templates/basket.php');
		$template = ob_get_clean(); 
        
		$oscTemplate->addContent($template, $this->group);	  		
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_DASHBOARD_BASKET_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customer Orders Module', 'MODULE_CONTENT_DASHBOARD_BASKET_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Panel Style', 'MODULE_CONTENT_DASHBOARD_BASKET_PANEL_STYLE', 'info', 'What colour scheme would you like for this module? (info = light blue, primary = dark blue, success = green, warning = yellow, danger = red).', '6', '1', 'tep_cfg_select_option(array(\'info\', \'primary\', \'success\', \'warning\', \'danger\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_DASHBOARD_BASKET_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_DASHBOARD_BASKET_STATUS', 'MODULE_CONTENT_DASHBOARD_BASKET_PANEL_STYLE', 'MODULE_CONTENT_DASHBOARD_BASKET_SORT_ORDER');
    }
  }