<?php
/*
  $Id$ Twitter Typeahead Autocomplete Search v1.4 for oscommerce 2.3.4BS

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  class ht_twitter_typeahead {
    var $code = 'ht_twitter_typeahead';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function ht_twitter_typeahead() {
      global $PHP_SELF;

      $this->title = MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_TITLE;
      $this->description = MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_STATUS == 'True');
      }

      if ( $this->enabled && basename($PHP_SELF) == 'login.php' ) {
        if ( class_exists('cm_paypal_login') ) {
          $ppl = new cm_paypal_login();
          if ( $ppl->enabled === true ) {
            $this->enabled = false;
          }
        }
      }
    }

    function execute() {
      global $oscTemplate, $PHP_SELF, $request_type;

      $oscTemplate->addBlock('<link href="ext/typeahead/css/typehead.js-bootstrap3.less/0.2.3/twitter-typeahead.css" rel="stylesheet">' . "\n", $this->group);
      $oscTemplate->addBlock('<link rel="stylesheet" href="ext/typeahead/css/ht-twitter-typeahead.css">' . "\n", $this->group);
      
      $oscTemplate->addBlock('<script src="ext/typeahead/js/twitter-typeahead/0.11.1/typeahead.bundle.modified.js"></script>' . "\n", 'footer_scripts');
      $oscTemplate->addBlock('<script src="ext/jquery/cookie.js"></script>' . "\n", 'footer_scripts');

      ob_start();
	  include('ext/typeahead/js/ht-twitter-typeahead.js.php');
      $script = ob_get_clean();

      $oscTemplate->addBlock($script, 'footer_scripts');
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Twitter Typeahead Autocomplete Search Module', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_STATUS', 'True', 'Do you want to add Twitter Typeahead Autocomplete Search to your shop?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Delay Time While User Types (rateLimitWait)', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_RATELIMITWAIT', '500', 'Set a value for the delay time (to wait while the user is typing) to limit requests to the server. This value will be used by the rateLimitBy setting below. In millisecs (ms). Default: 500.', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Delay Time Method (rateLimitBy)', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_RATELIMITBY', 'debounce', 'Method used to rate-limit server requests (while the user is typing). Select either \"debounce\" (will send server requests after typing is complete) or \"throttle\" (will send server requests at intervals set above). Default: debounce.', '6', '2', 'tep_cfg_select_option(array(\'debounce\', \'throttle\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Autocomplete List Height', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_MAX_HEIGHT', '292', 'Set the Typeahead Autocomplete List Height, in pixels (px). Scrollbar appears when required. Default: 292', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max Items in Autocomplete List', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_LIMIT_LIST', '10', 'Set the maximum number of items to appear in the Typeahead Autocomplete List. Default: 10', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Min Characters Required', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_MIN_LENGTH', '2', 'Set the minimum number of typed characters required in the search field before the Typeahead Autocomplete List appears. Default: 2 (2 is the minimum)', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Sort List', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_SORT_LIST', 'true', 'Sort the Typeahead Autocomplete List so that the closest matching results are at the top.', '6', '6', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Highlight Matching Keywords', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_KEYWORD_HIGHLIGHT', 'true', 'Highlight matching keywords in the Typeahead Autocomplete List', '6', '7', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Highlight Colour', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_HIGHLIGHT_COLOR', '5543C6', 'Set the highlight colour for matching keywords in the Typeahead Autocomplete List. Default: 5543C6 (blue) Note: preceding hash # not required.', '6', '8', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Bold Highlighted Matching Keywords', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_HIGHLIGHT_BOLD', 'true', 'Also bold the highlighted matching keywords in the Typeahead Autocomplete List.', '6', '9', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Typeahead Hint', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_KEYWORD_HINT', 'true', 'Show Typeahead hint (in search field) when a certain number of search characters are typed i.e. Google autocomplete like behaviour.', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Focus on Typed Keywords', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_FOCUS_ON_KEYWORD', 'true', 'Re-focus on typed keywords when user clicks away from search field and back again, otherwise focused item in Typeahead Autocomplete List will be used i.e. Google like behaviour.', '6', '11', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Product Preview', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_PRODUCT_PREVIEW', 'true', 'Show product preview (in product info area) when user keys up/down and pauses on an item in the Typeahead Autocomplete List.', '6', '12', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Delay for Product Preview', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_PRODUCT_PREVIEW_DELAY', '600', 'When \"Show Product Preview\" is true, set the delay/pause time for product preview to appear when users keys up/down and pauses on an item in the Typeahead Autocomplete List, in millisecs (ms). Default: 600.', '6', '13', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Popover Info', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_SHOW_POPOVER_INFO', 'true', 'Show popover information regarding the selecting/highlighting methods for items in the Typeahead Autocomplete List.', '6', '14', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show No Image Icon', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_DISPLAY_NO_IMAGE_ICON', 'true', 'When products have no image, show the no-image icon in place of the missing images in the Typeahead Autocomplete List, otherwise show blank.', '6', '15', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Include Manufacturers', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_INCLUDE_MANUFACTURER', 'true', 'Include manufactures in search results in the Typeahead Autocomplete List. (Searching manufacturers is default osCommerce behaviour in normal search results.)', '6', '16', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_SORT_ORDER', '0', 'Sort order. Lowest is first.', '6', '17', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_STATUS', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_RATELIMITWAIT', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_RATELIMITBY', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_MAX_HEIGHT', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_LIMIT_LIST', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_MIN_LENGTH', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_SORT_LIST', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_KEYWORD_HIGHLIGHT', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_HIGHLIGHT_COLOR', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_HIGHLIGHT_BOLD',
                   'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_KEYWORD_HINT', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_FOCUS_ON_KEYWORD', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_PRODUCT_PREVIEW', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_PRODUCT_PREVIEW_DELAY', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_SHOW_POPOVER_INFO', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_DISPLAY_NO_IMAGE_ICON', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_INCLUDE_MANUFACTURER', 'MODULE_HEADER_TAGS_TWITTER_TYPEAHEAD_SORT_ORDER');
    }
  }
?>
