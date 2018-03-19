<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_wishlist {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_wishlist() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

    
      $this->title = MODULE_CONTENT_PRODUCT_INFO_WISHLIST_TITLE;
      $this->description = MODULE_CONTENT_PRODUCT_INFO_WISHLIST_DESCRIPTION;
      
      if ( defined('MODULE_CONTENT_PRODUCT_INFO_WISHLIST_STATUS') ) {
        
        $this->sort_order = MODULE_CONTENT_PRODUCT_INFO_WISHLIST_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_PRODUCT_INFO_WISHLIST_STATUS == 'True');
      }
    }

    function execute() {
      global $_GET, $oscTemplate;


          $data = '';
            
          $data .= '<div class="col-sm-12">' .
                   '  <div class="row alert alert-info">' .
                   '    <div class="col-sm-8 ">' . MODULE_CONTENT_PRODUCT_INFO_WISHLIST_TEXT_ENTRY  . '</div>' .
                   '    <div class="col-sm-4 text-right">' .  tep_draw_button(TEXT_ADD_WISHLIST, 'glyphicon glyphicon-heart', null, 'primary', array('params' => 'name="wishlist" value="wishlist"')) . '</div>' .
                   '   </div>' .
                   '</div>';
          
            
        ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/wishlist.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);

      }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_PRODUCT_INFO_WISHLIST_STATUS');
    }

    function install() {
      tep_db_query("insert into configuration_aquarium (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Module', 'MODULE_CONTENT_PRODUCT_INFO_WISHLIST_STATUS', 'True', 'Activate wish list module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration_aquarium (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Max Wish List', 'MAX_DISPLAY_WISHLIST_PRODUCTS', '10', 'How many wish list items to show per page on the main wishlist page', '6', '2', now(), now(), NULL, NULL)");
      tep_db_query("insert into configuration_aquarium (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Max Wish List Box', 'MAX_DISPLAY_WISHLIST_BOX', '4', 'How many wish list items to display in the infobox before it changes to a counter', '6', '3', now(), now(), NULL, NULL)");
      tep_db_query("insert into configuration_aquarium (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Display Wish List After Adding Product', 'WISHLIST_REDIRECT', 'Yes', 'Display the Wish List after adding a product (or stay on product_info.php page)', '6', '5', now(), now(), NULL, 'tep_cfg_select_option(array(\'Yes\', \'No\'),')");
      tep_db_query("insert into configuration_aquarium (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_WISHLIST_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '6', now())");
    }
   
  
    function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_PRODUCT_INFO_WISHLIST_STATUS', 'MODULE_CONTENT_PRODUCT_INFO_WISHLIST_SORT_ORDER', 'MAX_DISPLAY_WISHLIST_PRODUCTS', 'MAX_DISPLAY_WISHLIST_BOX', 'WISHLIST_REDIRECT');
    }
  }
