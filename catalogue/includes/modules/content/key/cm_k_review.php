<?php
/*
  Copyright (c) 2016, G Burton
  All rights reserved.

  Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

  1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

  2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

  3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

  class cm_k_review {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_KEY_REVIEW_TITLE;
      $this->description = MODULE_CONTENT_KEY_REVIEW_DESCRIPTION;
      $this->description .= '<div class="secWarning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';

      if ( defined('MODULE_CONTENT_KEY_REVIEW_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_KEY_REVIEW_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_KEY_REVIEW_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $currencies, $languages_id, $messageStack; 
      
      if ( isset($_POST['action']) && ($_POST['action'] == 'review') ) {
        $products_id   = tep_db_prepare_input($_POST['products_id']);
        $customer      = tep_db_prepare_input($_POST['name']);
        $rating        = tep_db_prepare_input($_POST['rating']);
        $review        = tep_db_prepare_input($_POST['review']);
        $key           = tep_db_prepare_input($_POST['key']);
        $customers_id  = tep_db_prepare_input($_POST['customers_id']);

        tep_db_query("insert into reviews (products_id, customers_id, customers_name, reviews_rating, date_added, clubosc_key) values ('" . (int)$products_id . "', '" . (int)$customers_id . "', '" . tep_db_input($customer) . "', '" . tep_db_input($rating) . "', now(), '" . tep_db_input($key) . "')");

        $insert_id = tep_db_insert_id();

        tep_db_query("insert into reviews_description (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");

        $messageStack->add_session('product_reviews', MODULE_CONTENT_KEY_REVIEW_RECEIVED, 'success');
      }       
      
      $content_width = (int)MODULE_CONTENT_KEY_REVIEW_CONTENT_WIDTH;      
      
      $_key = tep_db_prepare_input($_GET['key']);  
      
      $ordered_query = tep_db_query("select 
      orders_id, customers_name, customers_id, customers_city from orders 
      where clubosc_key = '" . tep_db_input($_key) . "' limit 1");
      
      if (tep_db_num_rows($ordered_query)) {
        $ordered = tep_db_fetch_array($ordered_query);      
        $orderkey_id = $ordered['orders_id'];
        $customerkey_id = $ordered['customers_id'];

        $_name = preg_split('/[\s,._-]+/', $ordered['customers_name']);        
        $_lastname    = array_pop($_name);
        $_lastname_i  = $_lastname[0];
        $_firstname   = array_reverse($_name); 
        $_firstname   = end($_firstname);
        $_firstname_i = $_firstname[0];

        $orderkey_name = sprintf(MODULE_CONTENT_KEY_REVIEW_FULLNAME_USER, $ordered['customers_name']);
        $orderkey_name_fi = sprintf(MODULE_CONTENT_KEY_REVIEW_FI_USER, $_firstname, $_lastname_i);
        $orderkey_name_il = sprintf(MODULE_CONTENT_KEY_REVIEW_IL_USER, $_firstname_i, $_lastname);
        $orderkey_name_ai = sprintf(MODULE_CONTENT_KEY_REVIEW_AI_USER, $_firstname_i, $_lastname_i);        
        $orderkey_name_fic = sprintf(MODULE_CONTENT_KEY_REVIEW_CITY_USER, $_firstname, $_lastname_i, $ordered['customers_city']); 
        $orderkey_name_ob = sprintf(MODULE_CONTENT_KEY_REVIEW_OBFUSCATE_USER, $ordered['customers_id']);
      
        require_once('includes/classes/order.php');
        $order = new order($orderkey_id);

        ob_start();
        include('includes/modules/content/' . $this->group . '/templates/review.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_KEY_REVIEW_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable KEY Review Module', 'MODULE_CONTENT_KEY_REVIEW_STATUS', 'True', 'Should the module be shown?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_KEY_REVIEW_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_KEY_REVIEW_SORT_ORDER', '3000', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      if (tep_db_num_rows(tep_db_query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = 'reviews' AND COLUMN_NAME LIKE 'clubosc_key'")) != 1 ) {
        tep_db_query("alter table reviews add column clubosc_key VARCHAR(256) NULL");
      }
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_KEY_REVIEW_STATUS', 'MODULE_CONTENT_KEY_REVIEW_CONTENT_WIDTH', 'MODULE_CONTENT_KEY_REVIEW_SORT_ORDER');
    }
  }
  