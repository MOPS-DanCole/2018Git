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

  class ht_maintenance_mode {
    var $code = 'ht_maintenance_mode';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function ht_maintenance_mode() {
      $this->title = MODULE_HEADER_TAGS_MAINTENANCE_MODE_TITLE;
      $this->description = MODULE_HEADER_TAGS_MAINTENANCE_MODE_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_MAINTENANCE_MODE_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_MAINTENANCE_MODE_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_MAINTENANCE_MODE_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $PHP_SELF, $_SERVER;

      if (tep_not_null(MODULE_HEADER_TAGS_MAINTENANCE_ALLOWED_IP)) {
        $allowed_ip = MODULE_HEADER_TAGS_MAINTENANCE_ALLOWED_IP;
        $users_ip   = tep_get_ip_address();
        
        if ( ($allowed_ip != $users_ip) && ($PHP_SELF != 'offline.php') ) {
          tep_redirect(tep_href_link('offline.php'));
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_MAINTENANCE_MODE_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Maintenance Mode Module', 'MODULE_HEADER_TAGS_MAINTENANCE_MODE_STATUS', 'True', 'Add this module to your site?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allowed IP', 'MODULE_HEADER_TAGS_MAINTENANCE_ALLOWED_IP', '" . $this->get_admin_ip() . "', 'The IP address of you, the Shopowner.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_MAINTENANCE_MODE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_MAINTENANCE_MODE_STATUS', 'MODULE_HEADER_TAGS_MAINTENANCE_ALLOWED_IP', 'MODULE_HEADER_TAGS_MAINTENANCE_MODE_SORT_ORDER');
    }
    
    function get_admin_ip() {
      return tep_get_ip_address();
    }
  }
  