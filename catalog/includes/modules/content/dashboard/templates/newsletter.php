<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */
?>
<style>
/* http://callmenick.com/post/css-toggle-switch-examples */
.cmn-toggle {
  position: absolute;
  margin-left: -9999px;
  visibility: hidden;
}
.cmn-toggle + label {
  display: block;
  position: relative;
  cursor: pointer;
  outline: none;
  user-select: none;
}

input.cmn-toggle-round + label {
  padding: 2px;
  width: 60px;
  height: 30px;
  background-color: #dddddd;
  border-radius: 30px;
}
input.cmn-toggle-round + label:before,
input.cmn-toggle-round + label:after {
  display: block;
  position: absolute;
  top: 1px;
  left: 1px;
  bottom: 1px;
  content: "";
}
input.cmn-toggle-round + label:before {
  right: 1px;
  background-color: #f1f1f1;
  border-radius: 30px;
  transition: background 0.4s;
}
input.cmn-toggle-round + label:after {
  width: 28px;
  background-color: #fff;
  border-radius: 100%;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  transition: margin 0.4s;
}
input.cmn-toggle-round:checked + label:before {
  background-color: #8ce196;
}
input.cmn-toggle-round:checked + label:after {
  margin-left: 30px;
}
</style>
<script>
function checkCheckboxState() {

    if ($('#newscheck').is(':checked')) {
        var application_id = $('#newscheck').first().attr( "value" );
        var application_id = $('#newscheck').val();
	var email_address = $('#passwordModal input[name=username]').val();
        $.ajax({
            type: "POST",
            url: "ajax_dashboard.php",
            data: {id : application_id, email : email_address },
            success: function(msg) {
				//console.log("Data saved:" + msg);
				$('#newsletter_subscribed').text('<?php echo MODULE_CONTENT_DASHBOARD_NEWSLETTER_SUBSCRIBED; ?>');
				$('#newsletter_subscribed').removeClass('text-danger');
				$('#newsletter_subscribed').addClass('text-success');
            }
        });
    } else {
		var application_id = '0';
        $.ajax({
            type: "POST",
            url: "ajax_dashboard.php",
            data: {id : application_id},
            success: function(msg) {
                //console.log("Data saved:" + msg);
				$('#newsletter_subscribed').text('<?php echo MODULE_CONTENT_DASHBOARD_NEWSLETTER_UNSUBSCRIBED; ?>');
				$('#newsletter_subscribed').removeClass('text-success');
				$('#newsletter_subscribed').addClass('text-danger');
            }
        });
	}
    ;
}

function np_checkCheckboxState() {

    if ($('#newproductcheck').is(':checked')) {
        var application_id = $('#newproductcheck').first().attr( "value" );
        var application_id = $('#newproductcheck').val();
	var email_address = $('#passwordModal input[name=username]').val();
        $.ajax({
            type: "POST",
            url: "ajax_np_dashboard.php",
            data: {id : application_id, email : email_address },
            success: function(msg) {
				//console.log("Data saved:" + msg);
				$('#newproducts_subscribed').text('<?php echo MODULE_CONTENT_DASHBOARD_NEWSLETTER_SUBSCRIBED; ?>');
				$('#newproducts_subscribed').removeClass('text-danger');
				$('#newproducts_subscribed').addClass('text-success');
				
            }
        });
    } else {
	var application_id = '0';
	var email_address = $('#passwordModal input[name=username]').val();
        $.ajax({
            type: "POST",
            url: "ajax_np_dashboard.php",
            data: {id : application_id, email : email_address},
            success: function(msg) {
                //console.log("Data saved:" + msg);
				$('#newproducts_subscribed').text('<?php echo MODULE_CONTENT_DASHBOARD_NEWSLETTER_UNSUBSCRIBED; ?>');
				$('#newproducts_subscribed').removeClass('text-success');
				$('#newproducts_subscribed').addClass('text-danger');
            }
        });
	}
    ;
}
</script>
  <div class="panel dashboard panel-<?php echo $panel_style; ?> d-newsletter">
    <div class="panel-heading"><strong><i class="fa fa-bell"></i>&nbsp;<?php echo MODULE_CONTENT_DASHBOARD_NEWSLETTER_HEADING; ?></strong></div>
      <div class="panel-body">
	    <!-- Section regarding general newsletters. -->
		<div><strong><?php echo MODULE_CONTENT_DASHBOARD_GENERAL_NEWSLETTER_DETAILS; ?></strong></div>
		&nbsp;
		<div class="switch row"><?php echo MODULE_CONTENT_DASHBOARD_GENERAL_NEWSLETTER_DESCRIPTION; ?>
		  <div class="col-sm-2">
		    <?php echo tep_draw_checkbox_field('newsletter_general', '1', (($customer->details['newsletter'] == '1') ? true : false), 'onclick="checkCheckboxState();" class="cmn-toggle cmn-toggle-round" id="newscheck"'); ?>
		    <label for="newscheck"></label>
		  </div>
		  <div class="col-sm-10" id="newsletter_subscribed">
		    <?php echo (($customer->details['newsletter'] == '1') ? MODULE_CONTENT_DASHBOARD_NEWSLETTER_SUBSCRIBED : MODULE_CONTENT_DASHBOARD_NEWSLETTER_UNSUBSCRIBED); ?>
		  </div>
		</div>
		<hr>
		<!-- Section regarding new product notifications. -->
		<div><strong><?php echo MODULE_CONTENT_DASHBOARD_NP_NEWSLETTER_DETAILS; ?></strong></div>
		&nbsp;
		<div class="switch row"><?php echo MODULE_CONTENT_DASHBOARD_NP_NEWSLETTER_DESCRIPTION; ?></strong>
		  <div class="col-sm-2">
		    <?php echo tep_draw_checkbox_field('new_products', '1', (($subscription_check['subscription_new_products'] == '1') ? true : false), 'onclick="np_checkCheckboxState();" class="cmn-toggle cmn-toggle-round" id="newproductcheck"'); ?>
		    <label for="newproductcheck"></label>
		  </div>
		  <div class="col-sm-10" id="newproducts_subscribed">
		    <?php echo (($subscription_check['subscription_new_products'] == '1') ? MODULE_CONTENT_DASHBOARD_NEWSLETTER_SUBSCRIBED : MODULE_CONTENT_DASHBOARD_NEWSLETTER_UNSUBSCRIBED); ?>
		  </div>
		</div>
		<hr>
				<!-- Section regarding product notifications. -->
		<div><strong><?php echo MODULE_CONTENT_DASHBOARD_NOTIFICATION_DETAILS; ?></strong></div>
		<div>
		<?php 
		if ($notifications == true) {
		  $button_text = MODULE_CONTENT_DASHBOARD_NEWSLETTER_BUTTON_MORE;
		  if ($global_notifications == true) {
			echo MODULE_CONTENT_DASHBOARD_NOTIFICATION_GLOBAL;  
		  } else {
		    if ($products_check['total'] == '1') {
			  echo sprintf(MODULE_CONTENT_DASHBOARD_NOTIFICATION_SINGLE, $products_check['total']);
		    } else {
			  echo sprintf(MODULE_CONTENT_DASHBOARD_NOTIFICATION_PLURAL, $products_check['total']);
		    }
		  }
		} else {
			echo MODULE_CONTENT_DASHBOARD_NOTIFICATION_NONE;
			$button_text = MODULE_CONTENT_DASHBOARD_NEWSLETTER_BUTTON_ADD;
		}
		?>
		</div>
	  </div>
	  <div class="panel-footer"><?php echo tep_draw_button($button_text, 'fa fa-bell', tep_href_link('account_notifications.php', 'SSL'), 'primary', NULL, 'btn-' . $panel_style . ' btn-xs'); ?></div>
  </div>
