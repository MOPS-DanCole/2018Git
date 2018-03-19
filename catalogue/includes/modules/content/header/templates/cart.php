<div class="col-sm-<?php echo $content_width; ?>">
  <div class="cart-details hidden-xs">
    <span class="label label-success label-circle pull-right"><a href="<?php echo tep_href_link('shopping_cart.php'); ?>">
	<?php echo $cart->count_contents(); ?>
	</a></span>
  </div>
  <div class="visible-xs text-center-xs cart-details-xs">
    <?php echo sprintf(HEADER_CART_CONTENTS_XS, $cart->count_contents(), $currencies->format($cart->show_total())); ?>
    <div class="clearfix"></div>
    <br>
  </div>
</div>