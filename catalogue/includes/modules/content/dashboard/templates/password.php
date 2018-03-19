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

  <div class="panel dashboard panel-<?php echo $panel_style; ?> d-password">
    <div class="panel-heading"><strong><i class="fa fa-bell"></i>&nbsp;<?php echo MODULE_CONTENT_DASHBOARD_PASSWORD_HEADING; ?></strong></div>
      <div class="panel-body">
		<?php echo tep_draw_button(MODULE_CONTENT_DASHBOARD_PASSWORD_BUTTON, 'fa fa-key', null, 'primary', array ( params=>'data-toggle="modal" data-target="#passwordModal"'), 'btn-' . $panel_style . ''); ?>
	  </div>
  </div>

  <!-- Change Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo MODULE_CONTENT_DASHBOARD_PASSWORD_MODAL_HEADING; ?></h4>
      </div>
      <div class="modal-body">
        <?php echo tep_draw_form('account_password', tep_href_link('account.php', '', 'SSL'), 'post', 'class="form-horizontal"', true) . tep_draw_hidden_field('action', 'processPassword'); ?>

		<?php
		$customer_info_query = tep_db_query("select customers_email_address from customers where customers_id = '" . (int)$customer_id . "'");
		$customer_info = tep_db_fetch_array($customer_info_query);
		echo tep_draw_hidden_field('username', $customer_info['customers_email_address'], 'readonly autocomplete="username"'); 
		?>
			
		<div class="contentContainer">
		<script>
		$(function(){
		   $('#passwordModal').on('shown.bs.modal', function () {
			  $('#inputCurrent').focus()
		   });
		});
		</script>
		  <p class="text-danger text-right"><?php echo FORM_REQUIRED_INFORMATION; ?></p>

		  <div class="contentText">
			<div class="form-group has-feedback">
			  <label for="inputCurrent" class="control-label col-sm-3"><?php echo ENTRY_PASSWORD_CURRENT; ?></label>
			  <div class="col-sm-9">
				<?php echo tep_draw_input_field('password_current', NULL, 'required aria-required="true" autofocus="autofocus" id="inputCurrent" autocomplete="current-password" placeholder="' . ENTRY_PASSWORD_CURRENT_TEXT . '"', 'password'); ?>
				<?php echo FORM_REQUIRED_INPUT; ?>
			  </div>
			</div>
			<div class="form-group has-feedback">
			  <label for="inputPassword" class="control-label col-sm-3"><?php echo ENTRY_PASSWORD_NEW; ?></label>
			  <div class="col-sm-9">
				<?php echo tep_draw_input_field('password_new', NULL, 'required aria-required="true" id="inputPassword" autocomplete="new-password" placeholder="' . ENTRY_PASSWORD_NEW_TEXT . '"', 'password'); ?>
				<?php echo FORM_REQUIRED_INPUT; ?>
			  </div>
			</div>
			<div class="form-group has-feedback">
			  <label for="inputConfirmation" class="control-label col-sm-3"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
			  <div class="col-sm-9">
				<?php echo tep_draw_input_field('password_confirmation', NULL, 'required aria-required="true" id="inputConfirmation" autocomplete="new-password" placeholder="' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '"', 'password'); ?>
				<?php echo FORM_REQUIRED_INPUT; ?>
			  </div>
			</div>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
		<?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'fa fa-angle-left', null, 'primary', array ( params=>'data-dismiss="modal"'), 'btn-default'); ?>
        <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'fa fa-angle-right', null, 'primary', null, 'btn-success'); ?>
      </div>
    </div>
	</form>
  </div>
</div>