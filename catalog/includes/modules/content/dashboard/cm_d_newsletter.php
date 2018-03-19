<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */
  
  class cm_d_newsletter {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_DASHBOARD_NEWSLETTER_TITLE;
      $this->description = MODULE_CONTENT_DASHBOARD_NEWSLETTER_DESCRIPTION;

      if ( defined('MODULE_CONTENT_DASHBOARD_NEWSLETTER_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_DASHBOARD_NEWSLETTER_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_DASHBOARD_NEWSLETTER_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer, $languages_id, $customer_id;
      
      $content_width = MODULE_CONTENT_DASHBOARD_NEWSLETTER_CONTENT_WIDTH;
	  
	  $panel_style = MODULE_CONTENT_DASHBOARD_NEWSLETTER_PANEL_STYLE;
	  
	  $notifications = false;
	  $global_notifications = false;
	  
	  //determine if the following query is valid or if we're really wanting the newsletter setting in the customers table.
	  $global_query = tep_db_query("select global_product_notifications from customers_info where customers_info_id = '" . (int)$customer_id . "'");
	  $global = tep_db_fetch_array($global_query);
	  $products_check_query = tep_db_query("select count(*) as total from products_notifications where customers_id = '" . (int)$customer_id . "'");
	  if ($global['global_product_notifications']) {
		  $notifications = true;
		  $global_notifications = true;
	  } else {
		 $products_check_query = tep_db_query("select count(*) as total from products_notifications where customers_id = '" . (int)$customer_id . "'");
		 $products_check = tep_db_fetch_array($products_check_query);
		 if ($products_check['total'] > '0') {
			$notifications = true; 
		 }
	  }
  
      $email_address = $customer->details['email_address'];
	  $newsletter_query = tep_db_query("select subscription_addresse_email, subscription_new_products from newsletter_subscription where subscription_addresse_email = '" . $email_address . "'");
	  $subscription_check = tep_db_fetch_array($newsletter_query);
	  
	  //if user is not in the newsletter_subscription database we'll add them. 
	  if (empty($subscription_check['subscription_addresse_email'])) {
			$sql_subscribe_newsletter = array('subscription_addresse_email' => $email_address,
											  'subscription_date_creation' => 'now()',
                                              'subscription_newsletter' => '0',
											  'subscription_new_products' => '0');
            tep_db_perform('newsletter_subscription', $sql_subscribe_newsletter);
	  }	    
		
	  ob_start();
	  include('includes/modules/content/' . $this->group . '/templates/newsletter.php');
	  $template = ob_get_clean(); 
        
	  $oscTemplate->addContent($template, $this->group);	  		
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_DASHBOARD_NEWSLETTER_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customer Orders Module', 'MODULE_CONTENT_DASHBOARD_NEWSLETTER_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Panel Style', 'MODULE_CONTENT_DASHBOARD_NEWSLETTER_PANEL_STYLE', 'info', 'What colour scheme would you like for this module? (info = light blue, primary = dark blue, success = green, warning = yellow, danger = red).', '6', '1', 'tep_cfg_select_option(array(\'info\', \'primary\', \'success\', \'warning\', \'danger\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_DASHBOARD_NEWSLETTER_SORT_ORDER', '700', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_DASHBOARD_NEWSLETTER_STATUS', 'MODULE_CONTENT_DASHBOARD_NEWSLETTER_PANEL_STYLE', 'MODULE_CONTENT_DASHBOARD_NEWSLETTER_SORT_ORDER');
    }
  }