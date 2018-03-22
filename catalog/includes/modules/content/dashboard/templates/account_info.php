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
  <div class="panel dashboard panel-<?php echo $panel_style; ?> d-account-info">
    <div class="panel-heading"><strong><i class="fa fa-user"></i>&nbsp;<?php echo MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_HEADING; ?></strong></div>
	  <table class="table">
		<tr>
		  <td><strong><?php echo MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_NAME; ?></strong></td>
		  <td><?php echo $customer->details['firstname'] . ' ' . $customer->details['lastname']; ?></td>
		</tr>
		<tr>
		  <td><strong><?php echo MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_EMAIL; ?></strong></td>
		  <td><?php echo $customer->details['email_address']; ?></td>
		</tr>
		<tr>
		  <td><strong><?php echo MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_PHONE; ?></strong></td>
		  <td><?php echo $customer->details['phone']; ?></td>
		</tr>
		<tr>
		  <td></td>
		  <td><span class="pull-right"><small><?php echo MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_MORE; ?></small></span></td>
		</tr>
	  </table>
	<div class="panel-footer"><?php echo tep_draw_button(MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_BUTTON_EDIT, 'fa fa-cog', null, 'primary', array ( params=>'data-toggle="modal" data-target="#accountModal"'), 'btn-' . $panel_style . ' btn-xs'); ?></div>
  </div>

    <!-- Change Account Info Modal -->
  <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><?php echo MODULE_CONTENT_DASHBOARD_ACCOUNT_INFO_MODAL_HEADING; ?></h4>
         </div>
        <div class="modal-body">
          <?php echo tep_draw_form('account_edit', tep_href_link('account.php', '', 'SSL'), 'post', 'class="form-horizontal"', true) . tep_draw_hidden_field('action', 'processAccount'); ?>
			<div class="contentContainer">			
			<script>
			  $(function(){
				$('#accountModal').on('shown.bs.modal', function () {
				$('#firstname').focus()
		      });
			});
			</script>
		      <div class="text-danger text-right"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
		      
			  <?php
			  if (ACCOUNT_GENDER == 'true') {
				if (isset($gender)) {
				  $male = ($gender == 'm') ? true : false;
				} else {
				  $male = ($account['customers_gender'] == 'm') ? true : false;
				}
			  $female = !$male;
			  ?>
			  <div class="form-group has-feedback">
				<label class="control-label col-sm-3"><?php echo ENTRY_GENDER; ?></label>
				<div class="col-sm-9">
				  <label class="radio-inline">
					<?php echo tep_draw_radio_field('gender', 'm', $male, 'required aria-required="true" aria-describedby="atGender"') . ' ' . MALE; ?>
				  </label>
				  <label class="radio-inline">
					<?php echo tep_draw_radio_field('gender', 'f', $female) . ' ' . FEMALE; ?>
				  </label>
				  <?php echo FORM_REQUIRED_INPUT; ?>
				  <?php if (tep_not_null(ENTRY_GENDER_TEXT)) echo '<span id="atGender" class="help-block">' . ENTRY_GENDER_TEXT . '</span>'; ?>
				</div>
		      </div>
			<?php
		    }
		    ?>
			
		    <div class="form-group has-feedback">
			  <label for="inputFirstName" class="control-label col-sm-3"><?php echo ENTRY_FIRST_NAME; ?></label>
			    <div class="col-sm-9">
				<?php echo tep_draw_input_field('firstname', $account['customers_firstname'], 'required aria-required="true" id="inputFirstName" placeholder="' . ENTRY_FIRST_NAME_TEXT . '"'); ?>
				<?php echo FORM_REQUIRED_INPUT; ?>
				</div>
		    </div>
		    <div class="form-group has-feedback">
			  <label for="inputLastName" class="control-label col-sm-3"><?php echo ENTRY_LAST_NAME; ?></label>
			  <div class="col-sm-9">
				<?php echo tep_draw_input_field('lastname', $account['customers_lastname'], 'required aria-required="true" id="inputLastName" placeholder="' . ENTRY_LAST_NAME_TEXT . '"'); ?>
				<?php echo FORM_REQUIRED_INPUT; ?>
			  </div>
		    </div>

		    <?php
		    if (ACCOUNT_DOB == 'true') {
		    ?>
		    <div class="form-group has-feedback">
			  <label for="inputName" class="control-label col-sm-3"><?php echo ENTRY_DATE_OF_BIRTH; ?></label>
			  <div class="col-sm-9">
				<?php echo tep_draw_input_field('dob', tep_date_short($account['customers_dob']), 'required aria-required="true" id="dob" placeholder="' . ENTRY_DATE_OF_BIRTH_TEXT . '"'); ?>
				<?php echo FORM_REQUIRED_INPUT; ?>
			  </div>
			</div>
			<?php
			}
			?>

			<div class="form-group has-feedback">
			  <label for="inputEmail" class="control-label col-sm-3"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
			  <div class="col-sm-9">
				<?php echo tep_draw_input_field('email_address', $account['customers_email_address'], 'required aria-required="true" id="inputEmail" placeholder="' . ENTRY_EMAIL_ADDRESS_TEXT . '"', 'email'); ?>
			    <?php echo FORM_REQUIRED_INPUT; ?>
			  </div>
		    </div>
		    <div class="form-group has-feedback">
			  <label for="inputTelephone" class="control-label col-sm-3"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
			  <div class="col-sm-9">
			    <?php echo tep_draw_input_field('telephone', $account['customers_telephone'], 'required aria-required="true" id="inputTelephone" placeholder="' . ENTRY_TELEPHONE_NUMBER_TEXT . '"', 'tel'); ?>
			    <?php echo FORM_REQUIRED_INPUT; ?>
			  </div>
		    </div>
		    <div class="form-group">
			  <label for="inputFax" class="control-label col-sm-3"><?php echo ENTRY_FAX_NUMBER; ?></label>
			  <div class="col-sm-9">
			    <?php echo tep_draw_input_field('fax', $account['customers_fax'], 'id="inputFax" placeholder="' . ENTRY_FAX_NUMBER_TEXT . '"'); ?>
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