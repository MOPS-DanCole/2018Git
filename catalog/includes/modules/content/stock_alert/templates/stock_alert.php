<!-- <style type="text/css">A.soldout{border: solid 1px red;padding: 2px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;cursor: pointer;text-decoration: none;font-family: Verdana, Arial, sans-serif;color:#FF0000;font-size: 12px;font-weight:bold;line-height: 1.5;}</style> -->
<!-- <div class="col-sm-12"> -->
	<b><?php echo ' - ' . $stock; ?></b>
	<div class="clearfix"></div>
<!-- </div> -->
<!-- BOF Stock Alert Modal -->
<div class="modal fade" id="stockalertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
<!-- EOF Stock Alert Modal -->

<!-- BOF Drop Ship Modal -->
<div class="modal fade" id="dropshipModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
             <h3 class="modal-title  drop-ship" id="myModalLabel">Why does this item ship in one or two days?</h3>
            </div>
            <div class="modal-body">
                <h4>This is an item that we arrange to ship directly from the manufacturer. We do this for a couple of reasons. <br><br>
					1. We don't want to pay to ship the item twice. Once from the manufacturer to us and then again to you. <br><br>
					2. Not stocking it also reduces our inventory, warehouse and other operating costs. <br><br>
					This results in a lower cost to us and allows us to pass those savings along to you. On the odd occasion
					when the item is not available to ship in the usual one of two days we will let you know as soon as possible.<br></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i><?php echo IMAGE_BUTTON_CLOSE; ?></button>
        </div>
    </div>
  </div>
</div>
<!-- EOF Drop Ship Modal -->

<!-- BOF 2 to 14 Modal -->
<div class="modal fade" id="2to14Modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
             <h3 class="modal-title" id="myModalLabel">Why does it take so long to get this item?</h3>
            </div>
            <div class="modal-body">
                <h4>This item is not stocked but instead picked up from one of our suppliers as orders are received.  We find it best to handle items that are updated frequently, are very specialized or just plain slow moving, in this manner. <br><br>
				This helps keep our operating cost low and in turn our prices lower.  It will take a little longer for the item to reach you but on the otherhand, your price will be lower as a result.<br></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i><?php echo IMAGE_BUTTON_CLOSE; ?></button>
        </div>
    </div>
  </div>
</div>
<!-- EOF 2 to 14 Modal -->

<!-- BOF Out of Stock Modal -->
<div class="modal fade" id="outofstockModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
             <h3 class="modal-title" id="myModalLabel">What is a $ave More Item?</h3>
            </div>
            <div class="modal-body">
                <h4>In an effort to lower our cost and bring you even greater savings most items are now being picked up at our suppliers on a weekly basis.<br><br>
					To see how much more you can save, by waiting a few days, please visit our <a href="http://www.mopsdirect.ca/direct2you/product_info.php?products_id=<?php echo $product_info['products_id'] ?>"><u><b>Direct2You</b></u></a> website.<br>
				</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i><?php echo IMAGE_BUTTON_CLOSE; ?></button>
        </div>
    </div>
  </div>
</div>
<!-- EOF Out of Stock Modal -->