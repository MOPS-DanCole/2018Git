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

  class cm_pi_fb_comments {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_fb_comments() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_FB_COMMENTS_PRODUCTS_TITLE;
      $this->description = MODULE_CONTENT_FB_COMMENTS_PRODUCTS_DESCRIPTION;
      $this->description .= '<div class="secWarning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';

      if ( defined('MODULE_CONTENT_FB_COMMENTS_PRODUCTS_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_FB_COMMENTS_PRODUCTS_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_FB_COMMENTS_PRODUCTS_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $product_info;

      $content_width = MODULE_CONTENT_FB_COMMENTS_PRODUCTS_CONTENT_WIDTH;
      
      $exclusions = explode(',', MODULE_CONTENT_FB_COMMENTS_PRODUCTS_EXCLUSIONS);
      
      if (!in_array((int)$product_info['products_id'], $exclusions)) {
        $_href        = tep_href_link('product_info.php', 'products_id=' . (int)$product_info['products_id'], 'NONSSL', false);
        $_numposts    = MODULE_CONTENT_FB_COMMENTS_PRODUCTS_NUMPOSTS;
        $_order_by    = MODULE_CONTENT_FB_COMMENTS_PRODUCTS_ORDERBY;
        $_colorscheme = MODULE_CONTENT_FB_COMMENTS_PRODUCTS_COLORSCHEME;
        $_language    = MODULE_CONTENT_FB_COMMENTS_PRODUCTS_LOCALIZATION;

$fb_comment_output = <<<EOD
<div class="fb-comments" data-href="{$_href}" data-numposts="{$_numposts}" data-width="100%" data-order-by="{$_order_by}" data-colorscheme="{$_colorscheme}"></div>
EOD;
   
        ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/fb_comments.php');
        $template = ob_get_clean();
      
        $oscTemplate->addContent($template, $this->group);
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_FB_COMMENTS_PRODUCTS_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable FaceBook Comments Module', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Comments to show?', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_NUMPOSTS', '10', 'How many Comments should show?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('In what order?', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_ORDERBY', 'reverse_time', 'Order of the Comments', '6', '0', 'tep_cfg_select_option(array(\'reverse_time\', \'time\', \'social\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('In what colour scheme?', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_COLORSCHEME', 'light', 'Order of the Comments', '6', '0', 'tep_cfg_select_option(array(\'light\', \'dark\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Exclusions', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_EXCLUSIONS', '1,2,3', 'Comma Separated List of Products that should NOT show the Comment Box.', '6', '0', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_FB_COMMENTS_PRODUCTS_STATUS', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_CONTENT_WIDTH', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_NUMPOSTS', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_ORDERBY', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_COLORSCHEME', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_EXCLUSIONS', 'MODULE_CONTENT_FB_COMMENTS_PRODUCTS_SORT_ORDER');
    }

  }
  