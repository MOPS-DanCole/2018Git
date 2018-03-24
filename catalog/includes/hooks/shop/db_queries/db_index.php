<?php
class hook_shop_db_queries_db_index {
  function listen_index() {
	global $oscTemplate, $filter_xid, $manufacturers_id, $select_column_list, $languages_id, $current_category_id, $product_prices_table, $status_product_prices_table;

    if (isset($manufacturers_id) && !empty($manufacturers_id)) {
      if (isset($filter_xid) && tep_not_null($filter_xid)) {
	// We are asked to show only a specific category
	// BOF Separate Pricing Per Customer
	if ($status_product_prices_table == true) { // ok in mysql 5
	$listing_sql = "select " . $select_column_list . " p.products_id, SUBSTRING_INDEX(pd.products_description, ' ', 20) as products_description, pd.short_desc, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' AND (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "') AND (p.products_site = 'ALL' OR p.products_site = 'CAD') and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $manufacturers_id . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . $filter_xid . "' ";		
	} else { // either retail or no need to get correct special prices -- changed for mysql 5
    $listing_sql = "select " . $select_column_list . " p.products_id, SUBSTRING_INDEX(pd.products_description, ' ', 20) as products_description, pd.short_desc, p.manufacturers_id, p.products_price, p.products_weight, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' AND (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "') AND (p.products_site = 'ALL' OR p.products_site = 'CAD') and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $manufacturers_id . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . $filter_xid . "' ";
	} // end else { // either retail...
	// EOF Separate Pricing Per Customer
      } else {
	// We show them all
	// BOF Separate Pricing Per Customer
        if ($status_product_prices_table == true) { // ok in mysql 5
        $listing_sql = "select " . $select_column_list . " p.products_id, SUBSTRING_INDEX(pd.products_description, ' ', 20) as products_description, pd.short_desc, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' AND (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "') AND (p.products_site = 'ALL' OR p.products_site = 'CAD') and pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $manufacturers_id . "'";	
	} else { // either retail or no need to get correct special prices -- changed for mysql 5
        $listing_sql = "select " . $select_column_list . " p.products_id, SUBSTRING_INDEX(pd.products_description, ' ', 20) as products_description, pd.short_desc, p.manufacturers_id, p.products_price, p.products_weight, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' AND (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "') AND (p.products_site = 'ALL' OR p.products_site = 'CAD') and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $manufacturers_id . "'";
	} // end else { // either retail...
	// EOF Separate Pricing Per Customer
	
	// P2C-SORT-EDIT-2
	$p2c = false;
      }
    } else {
	// show the products in a given categorie
      if (isset($filter_xid) && tep_not_null($filter_xid)) {
	// We are asked to show only specific catgeory;  
	// BOF Separate Pricing Per Customer
        if ($status_product_prices_table == true) { // ok for mysql 5
        $listing_sql = "select " . $select_column_list . " p.products_id, SUBSTRING_INDEX(pd.products_description, ' ', 20) as products_description, pd.short_desc, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' AND (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "') AND (p.products_site = 'ALL' OR p.products_site = 'CAD') and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$filter_xid . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . $current_category_id . "'";	
        } else { // either retail or no need to get correct special prices -- ok in mysql 5
        $listing_sql = "select " . $select_column_list . " p.products_id, SUBSTRING_INDEX(pd.products_description, ' ', 20) as products_description, pd.short_desc, p.manufacturers_id, p.products_price, p.products_weight, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s using(products_id) where p.products_status = '1' AND (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "') AND (p.products_site = 'ALL' OR p.products_site = 'CAD') and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$filter_xid . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . $current_category_id . "'";
    	} // end else { // either retail...
	// EOF Separate Pricing Per Customer
      } else {
	// We show them all
	// BOF Separate Pricing Per Customer --last query changed for mysql 5 compatibility
        if ($status_product_prices_table == true) {
	// original, no need to change for mysql 5
	    $listing_sql = "select " . $select_column_list . " p.products_id, SUBSTRING_INDEX(pd.products_description, ' ', 20) as products_description, pd.short_desc, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' AND (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "') AND (p.products_site = 'ALL' OR p.products_site = 'CAD') and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
        } else { // either retail or no need to get correct special prices -- changed for mysql 5
        $listing_sql = "select " . $select_column_list . " p.products_id, SUBSTRING_INDEX(pd.products_description, ' ', 20) as products_description, pd.short_desc, p.manufacturers_id, p.products_price, p.products_weight, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' AND (p.products_category = '" . STORE_CATEGORY . "' OR p.products_category = '" . STORE_CATEGORY2 . "' OR p.products_category = '" . STORE_CATEGORY3 . "' OR p.products_category = '" . STORE_CATEGORY4 . "' OR p.products_category = '" . STORE_CATEGORY5 . "') AND (p.products_site = 'ALL' OR p.products_site = 'CAD') and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
      } // end else { // either retail...
	// EOF Separate Pricing per Customer

      }
    }
	
    return $listing_sql;    
  }  
}
?>