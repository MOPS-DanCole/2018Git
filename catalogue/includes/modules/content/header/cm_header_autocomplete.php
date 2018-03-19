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

  class cm_header_autocomplete {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_header_autocomplete() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_HEADER_AUTOCOMPLETE_TITLE;
      $this->description = MODULE_CONTENT_HEADER_AUTOCOMPLETE_DESCRIPTION;

      if ( defined('MODULE_CONTENT_HEADER_AUTOCOMPLETE_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_HEADER_AUTOCOMPLETE_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_HEADER_AUTOCOMPLETE_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $request_type, $languages_id;
      
      $content_width = MODULE_CONTENT_HEADER_AUTOCOMPLETE_CONTENT_WIDTH;

      $autocomplete_box  = '<div class="autocompletebox-margin">';
      $autocomplete_box .= tep_draw_form('quick_find', tep_href_link('advanced_search_result.php', '', $request_type, false), 'get', 'class="form-horizontal"');
      $autocomplete_box .= '  <div class="input-group">' .
                          tep_draw_input_field('keywords', '', 'list="products" required placeholder="' . TEXT_AUTOCOMPLETE_PLACEHOLDER . '"') . '<span class="input-group-btn"><button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i></button></span>' .
                     '  </div>';
                     
                     
      $autocomplete_box .= '<datalist id="products">';

      $products_query = tep_db_query("select pd.products_name from products p, products_description pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_status = 1 AND (p.products_site = 'ALL' OR p.products_site = '" . STORE_CURRENCY . "') order by pd.products_name");

      while ($products = tep_db_fetch_array($products_query)) {
        $autocomplete_box .= '<option value="' . $products['products_name'] . '">';
      }
      $autocomplete_box .= '</datalist>';
      
      $autocomplete_box .= tep_hide_session_id() . '</form>';
      $autocomplete_box .= '</div>';
      
      ob_start();
      include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/autocomplete.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_HEADER_AUTOCOMPLETE_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Autocomplete Search Box Module', 'MODULE_CONTENT_HEADER_AUTOCOMPLETE_STATUS', 'True', 'Do you want to enable the Autocomplete Search Box content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_HEADER_AUTOCOMPLETE_CONTENT_WIDTH', '4', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_HEADER_AUTOCOMPLETE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_HEADER_AUTOCOMPLETE_STATUS', 'MODULE_CONTENT_HEADER_AUTOCOMPLETE_CONTENT_WIDTH', 'MODULE_CONTENT_HEADER_AUTOCOMPLETE_SORT_ORDER');
    }
  }

