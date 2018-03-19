<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

  class cm_d_password {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_DASHBOARD_PASSWORD_TITLE;
      $this->description = MODULE_CONTENT_DASHBOARD_PASSWORD_DESCRIPTION;

      if ( defined('MODULE_CONTENT_DASHBOARD_PASSWORD_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_DASHBOARD_PASSWORD_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_DASHBOARD_PASSWORD_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer, $languages_id, $customer_id, $PHP_SELF, $language, $sessiontoken, $messageStack;
      
      $content_width = MODULE_CONTENT_DASHBOARD_PASSWORD_CONTENT_WIDTH;
	  
	  $panel_style = MODULE_CONTENT_DASHBOARD_PASSWORD_PANEL_STYLE;
	  
	  if (isset($_POST['action']) && ($_POST['action'] == 'processPassword') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
		$password_current = tep_db_prepare_input($_POST['password_current']);
		$password_new = tep_db_prepare_input($_POST['password_new']);
		$password_confirmation = tep_db_prepare_input($_POST['password_confirmation']);

		$error = false;

		if (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
		  $error = true;

		  $messageStack->add('account', ENTRY_PASSWORD_NEW_ERROR);
		} elseif ($password_new != $password_confirmation) {
		  $error = true;

		  $messageStack->add('account', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
		}

		if ($error == false) {
		  $check_customer_query = tep_db_query("select customers_password from customers where customers_id = '" . (int)$customer_id . "'");
		  $check_customer = tep_db_fetch_array($check_customer_query);

		  if (tep_validate_password($password_current, $check_customer['customers_password'])) {
			tep_db_query("update customers set customers_password = '" . tep_encrypt_password($password_new) . "' where customers_id = '" . (int)$customer_id . "'");

			tep_db_query("update customers_info set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

			$messageStack->add_session('account', SUCCESS_PASSWORD_UPDATED, 'success');

			tep_redirect(tep_href_link('account.php', '', 'SSL'));
		  } else {
			$error = true;

			$messageStack->add('account', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
			//tep_redirect(tep_href_link('account.php', '', 'SSL'));
		  }
		}
	  }
	  
		ob_start();
		include('includes/modules/content/' . $this->group . '/templates/password.php');
		$template = ob_get_clean(); 
        
		$oscTemplate->addContent($template, $this->group);	  		
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_DASHBOARD_PASSWORD_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customer Orders Module', 'MODULE_CONTENT_DASHBOARD_PASSWORD_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Panel Style', 'MODULE_CONTENT_DASHBOARD_PASSWORD_PANEL_STYLE', 'info', 'What colour scheme would you like for this module? (info = light blue, primary = dark blue, success = green, warning = yellow, danger = red).', '6', '1', 'tep_cfg_select_option(array(\'info\', \'primary\', \'success\', \'warning\', \'danger\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_DASHBOARD_PASSWORD_SORT_ORDER', '600', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_DASHBOARD_PASSWORD_STATUS', 'MODULE_CONTENT_DASHBOARD_PASSWORD_PANEL_STYLE', 'MODULE_CONTENT_DASHBOARD_PASSWORD_SORT_ORDER');
    }
  }