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

  class ht_popup {
    var $code = 'ht_popup';
    var $group = 'footer_scripts';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->title = MODULE_HEADER_TAGS_POPUP_TITLE;
      $this->description = MODULE_HEADER_TAGS_POPUP_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_POPUP_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_POPUP_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_POPUP_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate;
      
      $title = MODULE_HEADER_TAGS_POPUP_PUBLIC_TITLE;
     
      $html = <<<EOD
<div class="modal" id="doPopup" tabindex="-1" role="dialog" aria-labelledby="doPopupLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove"></span></button>
<h4 class="modal-title">{$title}</h4>
</div>
EOD;
 
  $data = '<div class="modal-body">' . 
          '<form name="myform" method="POST" action="https://www.cctomany.com/su.php"  onsubmit="DoSubmit()" />' .
		  '<label id="email_padding_bottom">Your Email Address:</label>' .
		  '<input type="text" name="Email" id="Email" required aria-required="true" placeholder="Enter Your E-Mail Address" class="form-control" />' .
		  '<p id="email_padding_top"><input type="radio" name="Action" value="join-mops-info" checked>Subscribe&nbsp;&nbsp;&nbsp;&nbsp;' .
		  '<input type="radio" name="Action" value="leave-mops-info">Unsubscribe</p>' .
		  '<div class="modal-footer">' . 	
	      '<input type="hidden" name="ThanksURL" value="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'newsletter_subscriptions_thank_you.php?email=">' .
		  '<button class="btn btn-primary" type="submit"> <i class="fa fa-paper-plane-o"></i> Send</button>' .
		  '</div>' .
		  '</form>' .
		  '  </div>' .
          '  </div>' .
          '  </div>' .
          '  </div>';
          
      $load = MODULE_HEADER_TAGS_POPUP_PAGE_LOAD;
      $time = (MODULE_HEADER_TAGS_POPUP_TIME == 'Multiple') ? 'sessionStorage.clickcount = 0;' : '';

      $js = <<<EOD
<script>$(document).ready(function() { if (sessionStorage.clickcount) { sessionStorage.clickcount = Number(sessionStorage.clickcount)+1; } else { sessionStorage.clickcount = 0; } if (sessionStorage.clickcount == {$load}) { $('#doPopup').fadeIn(); {$time} } $('.close').click(function(){ $('#doPopup').fadeOut(); });});</script>
EOD;

	  $js2 = <<<EOD
<script>function DoSubmit() { if ( document.myform.Action.value == "join-mops-info" ) { document.myform.ThanksURL.value = document.myform.ThanksURL.value + document.myform.Email.value + '&Action=subscribe'; }else{ document.myform.ThanksURL.value = document.myform.ThanksURL.value + document.myform.Email.value + '&Action=unsubscribe'; } return true; } </script>
EOD;

      $oscTemplate->addBlock($js, $this->group);
	  $oscTemplate->addBlock($js2, $this->group);
	  $oscTemplate->addBlock($html, $this->group);
	  $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_POPUP_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Exit Popup Module', 'MODULE_HEADER_TAGS_POPUP_STATUS', 'True', 'Adds a Popup Message for those who attempt to leave your site.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Popup After', 'MODULE_HEADER_TAGS_POPUP_PAGE_LOAD', '5', 'After viewing this many pages, the Popup will appear.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Popup Multiple', 'MODULE_HEADER_TAGS_POPUP_TIME', 'Multiple', 'The Popup will appear just once on the numbered page load, or every numbered page load.  Eg, if you set Multiple (and 5 in the box above), the popup will appear on the 5th, 10th, 15th (and so on) page load.', '6', '1', 'tep_cfg_select_option(array(\'One Time\', \'Multiple\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_POPUP_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_POPUP_STATUS', 'MODULE_HEADER_TAGS_POPUP_PAGE_LOAD', 'MODULE_HEADER_TAGS_POPUP_TIME', 'MODULE_HEADER_TAGS_POPUP_SORT_ORDER');
    }
  }
  