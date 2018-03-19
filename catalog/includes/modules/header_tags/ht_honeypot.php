<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class ht_honeypot {
    var $code = 'ht_honeypot';
    var $group = 'footer_scripts';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->title = MODULE_HEADER_TAGS_HONEYPOT_TITLE;
      $this->description = MODULE_HEADER_TAGS_HONEYPOT_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_HONEYPOT_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_HONEYPOT_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_HONEYPOT_STATUS == 'True');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate;

      if (tep_not_null(MODULE_HEADER_TAGS_HONEYPOT_PAGES)) {
        $pages_array = array();

        foreach (explode(';', MODULE_HEADER_TAGS_HONEYPOT_PAGES) as $page) {
          $page = trim($page);

          if (!empty($page)) {
            $pages_array[] = $page;
          }
        }

        if (in_array(basename($PHP_SELF), $pages_array)) {
          $data = <<<EOD
<script type="text/javascript">
function validateMyForm() {
 // The field is empty, submit the form.
 if(!document.getElementById("honeypot").value) { 
  return true;
 } 
  // the field has a value it's a spam bot
 else {
  return false;
 }
}
</script>
EOD;
         
         $oscTemplate->addBlock($data . "\n" ,  $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_HONEYPOT_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Grid List javascript', 'MODULE_HEADER_TAGS_HONEYPOT_STATUS', 'True', 'Do you want to enable the Grid/List Javascript module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_HEADER_TAGS_HONEYPOT_PAGES', '" . implode(';', $this->get_default_pages()) . "', 'The pages to add the Grid List JS Scripts to.', '6', '4', 'ht_honeypot_show_pages', 'ht_honeypot_edit_pages(', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_HONEYPOT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '5', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_HONEYPOT_STATUS', 'MODULE_HEADER_TAGS_HONEYPOT_PAGES', 'MODULE_HEADER_TAGS_HONEYPOT_SORT_ORDER');
    }

    function get_default_pages() {
      return array('contact_us.php',
                   'create_account.php',
                   'tell_a_friend.php');
    }
  }

  function ht_honeypot_show_pages($text) {
    return nl2br(implode("\n", explode(';', $text)));
  }

  function ht_honeypot_edit_pages($values, $key) {
    global $PHP_SELF;

    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
    $files_array = array();
	  if ($dir = @dir(DIR_FS_CATALOG)) {
	    while ($file = $dir->read()) {
	      if (!is_dir(DIR_FS_CATALOG . $file)) {
	        if (substr($file, strrpos($file, '.')) == $file_extension) {
            $files_array[] = $file;
          }
        }
      }
      sort($files_array);
      $dir->close();
    }

    $values_array = explode(';', $values);

    $output = '';
    foreach ($files_array as $file) {
      $output .= tep_draw_checkbox_field('ht_honeypot_file[]', $file, in_array($file, $values_array)) . '&nbsp;' . tep_output_string($file) . '<br />';
    }

    if (!empty($output)) {
      $output = '<br />' . substr($output, 0, -6);
    }

    $output .= tep_draw_hidden_field('configuration[' . $key . ']', '', 'id="htrn_files"');

    $output .= '<script>
                function htrn_update_cfg_value() {
                  var htrn_selected_files = \'\';

                  if ($(\'input[name="ht_honeypot_file[]"]\').length > 0) {
                    $(\'input[name="ht_honeypot_file[]"]:checked\').each(function() {
                      htrn_selected_files += $(this).attr(\'value\') + \';\';
                    });

                    if (htrn_selected_files.length > 0) {
                      htrn_selected_files = htrn_selected_files.substring(0, htrn_selected_files.length - 1);
                    }
                  }

                  $(\'#htrn_files\').val(htrn_selected_files);
                }

                $(function() {
                  htrn_update_cfg_value();

                  if ($(\'input[name="ht_honeypot_file[]"]\').length > 0) {
                    $(\'input[name="ht_honeypot_file[]"]\').change(function() {
                      htrn_update_cfg_value();
                    });
                  }
                });
                </script>';

    return $output;
  }
  
