<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

// register hook and call css
//  $OSCOM_Hooks->register('product');
//  echo $OSCOM_Hooks->call('product', 'corner_ribbons_css');

  if (isset($HTTP_GET_VARS['products_id'])) {
    $orders_query = tep_db_query("select p.products_id, p.products_image, pd.products_name from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where opa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$HTTP_GET_VARS['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "')  AND (p.products_site = 'ALL' OR p.products_site = '" . STORE_SITE . "') group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($orders_query);
		
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {

      $also_pur_prods_content = NULL;

      while ($orders = tep_db_fetch_array($orders_query)) {
        $also_pur_prods_content .= '<div class="col-sm-6 col-md-4">';
		$also_pur_prods_content .=  $OSCOM_Hooks->call('product', 'corner_ribbons');
        $also_pur_prods_content .= '  <div class="thumbnail equal-height">';
//      $also_pur_prods_content .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
        $also_pur_prods_content .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_PRODUCT_IMAGES . substr($orders['products_image'],2), $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
        $also_pur_prods_content .= '    <div class="caption">';
        $also_pur_prods_content .= '      <h5 class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></h5>';
        $also_pur_prods_content .= '    </div>';
        $also_pur_prods_content .= '  </div>';
        $also_pur_prods_content .= '</div>';
      }

?>

  <br />

  <h3 class="mops_h3"><?php echo TEXT_ALSO_PURCHASED_PRODUCTS; ?></h3>
  
  <div class="row">
    <?php echo $also_pur_prods_content; ?>
  </div>


<?php
    } // end if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED)
  }
?>
