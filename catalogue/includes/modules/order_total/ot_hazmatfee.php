<?php
/*
  Copyright (c) 2014, G Burton
  All rights reserved.

  Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

  1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

  2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

  3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

  class ot_hazmatfee {
    var $title, $output;

    function ot_hazmatfee() {
      $this->code = 'ot_hazmatfee';
      $this->title = MODULE_ORDER_TOTAL_HAZMATFEE_TITLE;
      $this->description = MODULE_ORDER_TOTAL_HAZMATFEE_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_HAZMATFEE_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_HAZMATFEE_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies, $cart;
      
      if (MODULE_ORDER_TOTAL_HAZMATFEE_STATUS == 'true') {
        $hazmat_products = explode(',', MODULE_ORDER_TOTAL_HAZMATFEE_PRODUCTS);
        $products = $cart->get_products();
        $hazmat_count = 0;
          
        for ($i=0, $n=sizeof($products); $i<$n; $i++) {
          if(in_array($products[$i]['id'], $hazmat_products)) {
            if (MODULE_ORDER_TOTAL_HAZMATFEE_CHARGING == 'Per Product') {
              $hazmat_count += $products[$i]['quantity'];
            }
            else {
              $hazmat_count = 1;
            }
          }
        }
        
        if ($hazmat_count > 0) {
          if ($order->delivery['country_id'] == STORE_COUNTRY) {
            $hazmat_fee = MODULE_ORDER_TOTAL_HAZMATFEE_FEE_INTERNAL * $hazmat_count;
          }
          else {
            $hazmat_fee = MODULE_ORDER_TOTAL_HAZMATFEE_FEE_EXTERNAL * $hazmat_count;
          }
          if ( ($order->info['total'] - $order->info['shipping_cost']) < MODULE_ORDER_TOTAL_HAZMATFEE_ORDER_UNDER ) {
            $hazmat_tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_HAZMATFEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
            $tax_description = tep_get_tax_description(MODULE_ORDER_TOTAL_HAZMATFEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);

            $order->info['tax'] += tep_calculate_tax($hazmat_fee, $hazmat_tax);
            $order->info['tax_groups']["$tax_description"] += tep_calculate_tax($hazmat_fee, $hazmat_tax);
            $order->info['total'] += $hazmat_fee + tep_calculate_tax($hazmat_fee, $hazmat_tax);
            
            $this->output[] = array('title' => $this->title . ':',
                                    'text' => $currencies->format(tep_add_tax($hazmat_fee, $hazmat_tax), true, $order->info['currency'], $order->info['currency_value']),
                                    'value' => tep_add_tax($hazmat_fee, $hazmat_tax));
          }
        }
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_HAZMATFEE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
        return array('MODULE_ORDER_TOTAL_HAZMATFEE_STATUS', 'MODULE_ORDER_TOTAL_HAZMATFEE_SORT_ORDER', 'MODULE_ORDER_TOTAL_HAZMATFEE_PRODUCTS', 'MODULE_ORDER_TOTAL_HAZMATFEE_ORDER_UNDER', 'MODULE_ORDER_TOTAL_HAZMATFEE_FEE_INTERNAL', 'MODULE_ORDER_TOTAL_HAZMATFEE_FEE_EXTERNAL', 'MODULE_ORDER_TOTAL_HAZMATFEE_CHARGING', 'MODULE_ORDER_TOTAL_HAZMATFEE_TAX_CLASS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display HAZMAT Fee', 'MODULE_ORDER_TOTAL_HAZMATFEE_STATUS', 'true', 'Do you want to display the HAZMAT fee?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_HAZMATFEE_SORT_ORDER', '25', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('HAZMAT Rated Products', 'MODULE_ORDER_TOTAL_HAZMATFEE_PRODUCTS', '11,13,23', 'Comma Separated List of Hazmat Rated Products.  (eg: 11,13,23)', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('HAZMAT Fee For Orders Under', 'MODULE_ORDER_TOTAL_HAZMATFEE_ORDER_UNDER', '200', 'Add the HAZMAT fee to orders under this amount.', '6', '4', 'currencies->format', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('HAZMAT Fee (home postage)', 'MODULE_ORDER_TOTAL_HAZMATFEE_FEE_INTERNAL', '35', 'HAZMAT fee (home)', '6', '5', 'currencies->format', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('HAZMAT Fee (worldwide postage)', 'MODULE_ORDER_TOTAL_HAZMATFEE_FEE_EXTERNAL', '55', 'HAZMAT fee (abroad)', '6', '5', 'currencies->format', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('HAZMAT Charging', 'MODULE_ORDER_TOTAL_HAZMATFEE_CHARGING', 'Per Product', 'How should the HAZMAT fee be applied ?', '6', '1','tep_cfg_select_option(array(\'Per Order\', \'Per Product\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_HAZMATFEE_TAX_CLASS', '0', 'Use the following tax class on the HAZMAT fee.', '6', '7', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }

