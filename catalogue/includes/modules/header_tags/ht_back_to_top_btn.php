<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright © 2015 osCommerce

  Released under the GNU General Public License
*/

  class ht_back_to_top_btn {
    var $code = 'ht_back_to_top_btn';
    var $group = 'footer_scripts';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function ht_back_to_top_btn() {
      $this->title = MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_TITLE;
      $this->description = MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_DESCRIPTION;
      
      if ( defined('MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_STATUS == 'True');
      }
    }

     function execute() {
       global  $oscTemplate;

$vertical_position = MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_VERTICAL_POSITION;
$speed = MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_SCROLL_SPEED;

$output = <<<EOD
    <script>
$(document).ready(function(){
      $('body').append('<div id="toTop" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-chevron-up"></span></div>');
        $(window).scroll(function () {
            if ($(this).scrollTop() > $vertical_position) {
                $('#toTop').fadeIn();
            } else {
                $('#toTop').fadeOut();
            }
        });
    $('#toTop').click(function(){
        $("html, body").animate({ scrollTop: 0 }, $speed);
        return false;
    });
});
</script>   
EOD;

      $oscTemplate->addBlock($output, $this->group);
    }    
    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Back to Top Module', 'MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_STATUS', 'True', 'Do you want to enable the Back to top module?', '6', '', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_SORT_ORDER', '1500', 'Sort order of display. Lowest is displayed first.', '6', '', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Vertical Position', 'MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_VERTICAL_POSITION', '150', 'Defines where, when scrolling down, the button is displayed. Default 150.', '6', '', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Scroll Speed ', 'MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_SCROLL_SPEED', '500', 'Defines the scrolling up speed in ms. Low value = fast - high value =slow. Default 500.', '6', '', now())");
    
    
    
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_STATUS', 'MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_SORT_ORDER', 'MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_VERTICAL_POSITION', 'MODULE_HEADER_TAGS_BACK_TO_TOP_BTN_SCROLL_SPEED');
    }
  }

?>