<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  class tp_email_example {
    var $group = 'email_example';
    var $template = '';
    var $title = 'oscTemplate Example';
    var $description = 'Template Examples<br />preview demo with <strong>hard coded</strong> variables<br /><br /><strong>Usage:</strong> Only view template example helps';
    var $section = 'undefined'; // could be shop, admin, payment_modules or undefined
    var $version = '1.01';

    // oscTemplate class use this function
    function prepare() {
      global $oscTemplate;
      $oscTemplate->_email_data['example']['enable_osc_mail'] = 'True';
      $GLOBALS['mimemessage'] = new email(array('X-Mailer: osCommerce'));
      $mimemessage = $GLOBALS['mimemessage'];
    }

    // oscTemplate class render data by this way
    function build() {
      global $oscTemplate, $mimemessage, $something_need;

      if ($oscTemplate->_email_data['example']['enable_osc_mail'] == 'True') {
        $email_variable = 'Here is an email text' . "\n\n";

        ob_start();

        if (EMAIL_USE_HTML == 'false') {
          include(DIR_WS_MODULES . 'pages/templates/email_example_text.php');
        } else {
          include(DIR_WS_MODULES . 'pages/templates/email_example_html.php');
        }

        $this->template = ob_get_clean();

        if (EMAIL_USE_HTML == 'true') {
          $this->template = tep_convert_linefeeds(array("\r\n", "\n", "\r"), '', $this->template);
        }
      }
    }

    // admin static viewer function for previews
    function preview() {
      global $mode, $language, $something_need;

      require(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/email_example.php');

      $email_variable = 'Here is an email text' . "\n\n";

      ob_start();
      if ($mode == 'text') {
        include(DIR_FS_CATALOG . DIR_WS_MODULES . 'pages/templates/email_example_text.php');
      } else {
        include(DIR_FS_CATALOG . DIR_WS_MODULES . 'pages/templates/email_example_html.php');
      }

      $this->template = ob_get_clean();
    }

    // previews data for administration
    function info() {
      return array('group' => $this->group,
                   'title' => $this->title,
                   'description' => $this->description,
                   'section' => $this->section,
                   'version' => $this->version);
    }
  }
?>
