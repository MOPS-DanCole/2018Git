<!-- <style type="text/css">A.soldout{border: solid 1px red;padding: 2px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;cursor: pointer;text-decoration: none;font-family: Verdana, Arial, sans-serif;color:#FF0000;font-size: 12px;font-weight:bold;line-height: 1.5;}</style> -->
<!-- <div class="col-sm-12"> -->
	<b><?php echo ' - ' . $stock; ?></b>
	<div class="clearfix"></div>
<!-- </div> -->
<!-- BOF Additional Shipping Modal -->
<div class="modal fade" id="additionalshippingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo MESSAGE_1 .'"'. $product_info['products_name']. '"'. MESSAGE_2; ?></h4>
      </div>
      <div class="modal-body">
        <?php echo tep_draw_form('sn_notify', tep_href_link(FILENAME_PRODUCT_INFO, '&action=sn_notify'), 'post', null, true); ?>      
      <label><?php echo NOTIFY_EMAIL ;?></label>
        <?php echo tep_draw_input_field('sn_email','','id="sn_email" required aria-required="true" placeholder="' . ENTRY_EMAIL . '"');?>
      <label><?php echo NOTIFY_NAME ;?></label>
        <?php echo tep_draw_input_field('sn_name','','id="sn_name" required aria-required="true" placeholder="' . ENTRY_NAME . '"');?>
        <?php echo tep_draw_hidden_field('product_id', $product_info['products_id'], 'id="product_id"');?>
        <?php echo tep_draw_hidden_field('product_name', $product_info['products_name'], 'id="product_name"');?>
       </div> <!-- EOF modal-body //-->
       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i><?php echo IMAGE_BUTTON_CLOSE; ?></button>
		<?php echo tep_draw_button(IMAGE_BUTTON_SEND, 'glyphicon glyphicon-send', null, 'primary', null, 'btn-primary'); ?>
      </div> <!-- EOF modal-footer //-->
    </form>
	</div> <!-- EOF modal-content //-->
  </div> <!-- EOF modal-dialog //-->
</div> <!-- EOF modal fade //-->
<!-- EOF Additional Shipping Modal -->

<!-- BOF Drop Ship Modal -->
<div class="modal fade" id="dropshipModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
             <h3 class="modal-title" id="myModalLabel">What is a Special Order or Drop Ship Item?</h3>
            </div>
            <div class="modal-body">
                <h4>A special order item is one that we don't stock but rather order from the manufacturer as we receive orders for those items.  Some items are very specialized or unique and don't sell very often or they may be items which are updated or changed frequently.  In these circumstances we don't stock the item to ensure that you are getting the most current and up to date product and are not paying the carrying cost for slow moving items to sit in stock.  To keep our operating cost low and in turn, our prices low, we strive to turn our inventory over quickly.<br><br>

A Drop Ship item is one that is shipped directly to you by the manufacturer.   We do this for a couple of reasons.  Like a Special Order Item it maybe that the item doesn't sell quickly or is changed frequently but just as likely, it is an item that requires special shipping due to its weight or size.  In these circumstances we don't want to pay to ship the item twice. Once from the manufacturer to us and then again to ship it to you.  Having it shipped directly to you from the manufacturer keeps the cost lower than it would be otherwise and we're able to pass those savings along to you.  A good example of an item we may drop ship is an aquarium chiller.<br><br>

Because these items are sourced when ordered they may take a little longer to reach you but on the otherhand, the price will be lower.<br></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i><?php echo IMAGE_BUTTON_CLOSE; ?></button>
        </div>
    </div>
  </div>
</div>
<!-- EOF Additional Shipping Modal -->