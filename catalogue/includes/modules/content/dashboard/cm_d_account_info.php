<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

  class cm_d_account_info {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_TITLE;
      $this->description = MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_DESCRIPTION;

      if ( defined('MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer, $languages_id, $customer_id, $sessiontoken, $messageStack;
      
      $content_width = MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_CONTENT_WIDTH;
	  
	  $panel_style = MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_PANEL_STYLE;
	  
	  if (isset($_POST['action']) && ($_POST['action'] == 'processAccount') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
		if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
		$firstname = tep_db_prepare_input($_POST['firstname']);
		$lastname = tep_db_prepare_input($_POST['lastname']);
		if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
		$email_address = tep_db_prepare_input($_POST['email_address']);
		$telephone = tep_db_prepare_input($_POST['telephone']);
		$fax = tep_db_prepare_input($_POST['fax']);

		$error = false;

		if (ACCOUNT_GENDER == 'true') {
		  if ( ($gender != 'm') && ($gender != 'f') ) {
			$error = true;

			$messageStack->add('account', ENTRY_GENDER_ERROR);
		  }
		}

		if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
		  $error = true;

		  $messageStack->add('account', ENTRY_FIRST_NAME_ERROR);
		}

		if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
		  $error = true;

		  $messageStack->add('account', ENTRY_LAST_NAME_ERROR);
		}

		if (ACCOUNT_DOB == 'true') {
		  if ((strlen($dob) < ENTRY_DOB_MIN_LENGTH) || (!empty($dob) && (!is_numeric(tep_date_raw($dob)) || !@checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))))) {
			$error = true;

			$messageStack->add('account', ENTRY_DATE_OF_BIRTH_ERROR);
		  }
		}

		if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
		  $error = true;

		  $messageStack->add('account', ENTRY_EMAIL_ADDRESS_ERROR);
		}

		if (!tep_validate_email($email_address)) {
		  $error = true;

		  $messageStack->add('account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
		}

		$check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$customer_id . "'");
		$check_email = tep_db_fetch_array($check_email_query);
		if ($check_email['total'] > 0) {
		  $error = true;

		  $messageStack->add('account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
		}

		if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
		  $error = true;

		  $messageStack->add('account', ENTRY_TELEPHONE_NUMBER_ERROR);
		}

		if ($error == false) {
		  $sql_data_array = array('customers_firstname' => $firstname,
								  'customers_lastname' => $lastname,
								  'customers_email_address' => $email_address,
								  'customers_telephone' => $telephone,
								  'customers_fax' => $fax);

		  if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
		  if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

		  tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");

		  tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

		  $sql_data_array = array('entry_firstname' => $firstname,
								  'entry_lastname' => $lastname);

		  tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$customer_default_address_id . "'");

		// reset the session variables
		  $customer_first_name = $firstname;

		  $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');

		  tep_redirect(tep_href_link('account.php', '', 'SSL'));
		}
	  }

	  $account_query = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
	  $account = tep_db_fetch_array($account_query);

	  ob_start();
	  include('includes/modules/content/' . $this->group . '/templates/account_info.php');
	  $template = ob_get_clean(); 
        
		$oscTemplate->addContent($template, $this->group);	  		
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customer Orders Module', 'MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Panel Style', 'MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_PANEL_STYLE', 'info', 'What colour scheme would you like for this module? (info = light blue, primary = dark blue, success = green, warning = yellow, danger = red).', '6', '1', 'tep_cfg_select_option(array(\'info\', \'primary\', \'success\', \'warning\', \'danger\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_SORT_ORDER', '400', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_STATUS', 'MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_PANEL_STYLE', 'MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_SORT_ORDER');
    }
  }