<?php
class hook_shop_product_pi_corner_ribbons {
	
  function listen_corner_ribbons() {
	  
	 global $listing, $new_products, $product_info, $orders;
	 $ribbon_text = NULL;
	 $ribbon_color = NULL;
	 $override_css = NULL;

	 // set up stuff depending on whether we are on the index page or not
	 if ((basename($_SERVER['PHP_SELF']) == 'index.php') && (empty($listing))) {
				$new_product_query = tep_db_query( "select p.products_date_added, p.products_id from products p where p.products_id = '" . (int)$new_products['products_id'] . "' and p.products_date_added >= ( date_sub(now(), interval 30 day) )");
				$new_product = tep_db_fetch_array($new_product_query);	
				$on_special = tep_get_products_special_price($new_products['products_id']);
								
			} else { // we are on a product listing, specials or new products page.
				$new_product_query = tep_db_query( "select p.products_date_added, p.products_id from products p where p.products_id = '" . (int)$listing['products_id'] . "' and p.products_date_added >= ( date_sub(now(), interval 30 day) )");		
				$new_product = tep_db_fetch_array($new_product_query);	
				$on_special = tep_get_products_special_price($listing['products_id']);
		}
	 
	 if (empty($new_products)) {
			$override_css_right = "right:-6px;";
	 }
	 
	 // set up stuff for product_info page
 	 if (basename($_SERVER['PHP_SELF']) == 'product_info.php') {
			$new_product_query = tep_db_query( "select p.products_date_added, p.products_id from products p where p.products_id = '" . (int)$product_info['products_id'] . "' and p.products_date_added >= ( date_sub(now(), interval 30 day) )");
	        $new_product = tep_db_fetch_array($new_product_query);	
			$on_special = tep_get_products_special_price($product_info['products_id']);
			$override_css_right = "right:-32px;";
			$override_css_top = "top:-32px;";
	 }

	 // set up for also purchased items
	 if (!empty($orders)) {
			$new_product_query = tep_db_query( "select p.products_date_added, p.products_id from products p where p.products_id = '" . (int)$orders['products_id'] . "' and p.products_date_added >= ( date_sub(now(), interval 30 day) )");
	        $new_product = tep_db_fetch_array($new_product_query);
			$on_special = tep_get_products_special_price($orders['products_id']);
	 }
	 
     // determine ribbon to display - change order based on priority.
	 if (isset($on_special)) {
			$ribbon_text = "On Sale";
			$ribbon_color = "red";
	} elseif (tep_not_null($new_product['products_date_added'])) {
			$ribbon_text = "New Item";
			$ribbon_color = "blue";
	} elseif (($listing['products_price'] >= '200.00') && ($listing['products_weight'] == '0.00')) {
		    $ribbon_text = "Ships Free";
			$ribbon_color = "green";		
	} elseif (($product_info['products_price'] >= '200.00') && ($product_info['products_weight'] == '0.00')) {
		    $ribbon_text = "Ships Free";
			$ribbon_color = "green";	
	} elseif (($new_products['products_price'] >= '200.00') && ($new_products['products_weight'] == '0.00')) {
		    $ribbon_text = "Ships Free";
			$ribbon_color = "green";		 
	} 
	
	if (isset($ribbon_color)) { 
			$output = '<div class="'.$ribbon_color.' ribbon" style="'.$override_css_right . $override_css_top .'"><span>'.$ribbon_text.'</span></div>';
	}
	
	return $output;    
  } 

  function listen_corner_ribbons_css() {

      $css_output = <<<EOD
<link href="/includes/hooks/shop/product/css/ribbons.css" rel="stylesheet">	  
EOD;
		return $css_output;	 
  }	  
}
?>