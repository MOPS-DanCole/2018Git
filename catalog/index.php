<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

 // global variable (session) $sppc_customer_group_id -> local variable DEFAULT_CUSTOMER_GROUP from config file
  $default_customer_group = DEFAULT_CUSTOMER_GROUP;
  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != $default_customer_group) {
  $customer_group_id = $_SESSION['sppc_customer_group_id'];
  } else {
   $customer_group_id = $default_customer_group;
  } 
  
// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "' ");
    $categories_products = tep_db_fetch_array($categories_products_query);
    if ($categories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

  require(DIR_WS_INCLUDES . 'template_top.php');

  if ($category_depth == 'nested') {
    $category_query = tep_db_query("select cd.categories_name, c.categories_image, cd.categories_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "' AND (c.categories_site = '" . STORE_CATEGORY . "' OR c.categories_site = '" . STORE_CATEGORY2 . "' OR c.categories_site = '" . STORE_CATEGORY3 . "' OR c.categories_site = '" . STORE_CATEGORY4 . "' OR c.categories_site = '" . STORE_CATEGORY5 . "') AND (c.categories_currency = 'ALL' OR c.categories_currency = '" . STORE_CURRENCY . "') ");
    $category = tep_db_fetch_array($category_query);
?>

<div class="page-header">
  <h1><?php echo $category['categories_name']; ?></h1>
</div>

<?php
  if ($messageStack->size('product_action') > 0) {
    echo $messageStack->output('product_action');
  }
?>

<?php
if (tep_not_null($category['categories_description'])) {
  ?>
  <div class="alert alert-info"><?php echo $category['categories_description']; ?></div>
  <?php
}
?>

<div class="contentContainer">
  <div class="well well-lg">	
    <div class="row">

<?php
    if (isset($cPath) && strpos('_', $cPath)) {
// check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id  AND (c.categories_site = '" . STORE_CATEGORY . "' OR c.categories_site = '" . STORE_CATEGORY2 . "' OR c.categories_site = '" . STORE_CATEGORY3 . "' OR c.categories_site = '" . STORE_CATEGORY4 . "' OR c.categories_site = '" . STORE_CATEGORY5 . "') and (c.categories_currency = 'ALL' OR c.categories_currency = '" . STORE_CURRENCY . "') and cd.language_id = '" . (int)$languages_id . "'");
        $categories = tep_db_fetch_array($categories_query);
        if ($categories['total'] < 1) {
          // do nothing, go through the loop
        } else {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' AND (c.categories_site = '" . STORE_CATEGORY . "' OR c.categories_site = '" . STORE_CATEGORY2 . "' OR c.categories_site = '" . STORE_CATEGORY3 . "' OR c.categories_site = '" . STORE_CATEGORY4 . "' OR c.categories_site = '" . STORE_CATEGORY5 . "') and (c.categories_currency = 'ALL' OR c.categories_currency = '" . STORE_CURRENCY . "') order by sort_order, cd.categories_name");
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' AND (c.categories_site = '" . STORE_CATEGORY . "' OR c.categories_site = '" . STORE_CATEGORY2 . "' OR c.categories_site = '" . STORE_CATEGORY3 . "' OR c.categories_site = '" . STORE_CATEGORY4 . "' OR c.categories_site = '" . STORE_CATEGORY5 . "') and (c.categories_currency = 'ALL' OR c.categories_currency = '" . STORE_CURRENCY . "') order by sort_order, cd.categories_name");
    }

    while ($categories = tep_db_fetch_array($categories_query)) {
      $cPath_new = tep_get_path($categories['categories_id']);
				
     echo '<div class="col-xs-6 col-sm-4">';
//      echo '  <div class="text-center">';
//      echo '    <a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '</a>';
      echo '    <div class="caption text-center">';
      echo '      <h5 class="category_box"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . $categories['categories_name'] . '</a></h5>';
      echo '    </div>';
//      echo '  </div>';
      echo '</div>';
    }

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;
?>
      </div>
	</div>
    
<?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?>
 
</div>

<?php
  } elseif ($category_depth == 'products' || (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id']))) {
// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }

// BOF Separate Pricing Per Customer
// this will build the table with specials prices for the retail group or update it if needed
// this function should have been added to includes/functions/database.php
   if ($customer_group_id == '0') {
   tep_db_check_age_specials_retail_table();
   }
   $status_product_prices_table = false;
   $status_need_to_get_prices = false;

   // find out if sorting by price has been requested
   //  if ( (isset($HTTP_GET_VARS['sort'])) && (ereg('[1-8][ad]', $HTTP_GET_VARS['sort']))           && (substr($HTTP_GET_VARS['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0' ){
     if ( (isset($HTTP_GET_VARS['sort'])) && (!preg_match('/[1-8][ad]/i', $HTTP_GET_VARS['sort'])) && (substr($HTTP_GET_VARS['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0' ){
   $_sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    if ($column_list[$_sort_col-1] == 'PRODUCT_LIST_PRICE') {
      $status_need_to_get_prices = true;
      }
   }

   if ($status_need_to_get_prices == true && $customer_group_id != '0') {
   $product_prices_table = TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id;
   // the table with product prices for a particular customer group is re-built only a number of times per hour
   // (setting in /includes/database_tables.php called MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE, in minutes)
   // to trigger the update the next function is called (new function that should have been
   // added to includes/functions/database.php)
   tep_db_check_age_products_group_prices_cg_table($customer_group_id);
   $status_product_prices_table = true;

   } // end if ($status_need_to_get_prices == true && $customer_group_id != '0')
// EOF Separate Pricing Per Customer
	
    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      switch ($column_list[$i]) {
        case 'PRODUCT_LIST_MODEL':
          $select_column_list .= 'p.products_model, ';
          break;
        case 'PRODUCT_LIST_NAME':
          $select_column_list .= 'pd.products_name, ';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $select_column_list .= 'm.manufacturers_name, ';
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $select_column_list .= 'p.products_quantity, ';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $select_column_list .= 'p.products_image, ';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $select_column_list .= 'p.products_weight, ';
          break;
      }
    }
	
	// P2C-SORT-EDIT-1
		$p2c = true;
	// show the products of a specified manufacturer
	 
	$filter_xid = $HTTP_GET_VARS['filter_id'];
	$manufacturers_id = $HTTP_GET_VARS['manufacturers_id'];
	
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
				
    if ( (!isset($HTTP_GET_VARS['sort'])) || (!preg_match('/^[1-8][ad]$/', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
// P2C-SORT-EDIT-3
	if ($p2c) { 
		$listing_sql .= " order by p2c.products_sort_order, pd.products_name";
		$_GET['sort'] = '0a';
	} else {
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
          $HTTP_GET_VARS['sort'] = $i+1 . 'a';
          $listing_sql .= " order by pd.products_name";
          break;
        }
      }
      } 
    } else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);

      switch ($column_list[$sort_col-1]) {
        case 'PRODUCT_LIST_MODEL':
          $listing_sql .= " order by p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_NAME':
          $listing_sql .= " order by pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $listing_sql .= " order by m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $listing_sql .= " order by p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_IMAGE':
          $listing_sql .= " order by pd.products_name";
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $listing_sql .= " order by p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_PRICE':
          $listing_sql .= " order by final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
      }
    }

    $catname = HEADING_TITLE;
    if (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id'])) {
      $image = tep_db_query("select manufacturers_image, manufacturers_name as catname from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $catname = $image['catname'];
    } elseif ($current_category_id) {
      $image = tep_db_query("select c.categories_image, cd.categories_name as catname, cd.categories_description as catdesc from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
      $image = tep_db_fetch_array($image);
      $catname = $image['catname'];
    }
?>

<div class="page-header">
  <h1><?php echo $catname; ?></h1>
</div>

<?php
if (tep_not_null($image['catdesc'])) {
  ?>
  <div class="alert alert-info"><?php echo $image['catdesc']; ?></div>
  <?php
}
?>

<div class="contentContainer">

<?php
// optional Product List Filter
    if (PRODUCT_LIST_FILTER > 0) {
      if (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name";
      } else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      }
      $filterlist_query = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist_query) > 1) {
        echo '<div>' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . '<p align="right">' . TEXT_SHOW . '&nbsp;';
        if (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id'])) {
          echo tep_draw_hidden_field('manufacturers_id', $HTTP_GET_VARS['manufacturers_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
          echo tep_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }
        echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo tep_hide_session_id() . '</p></form></div>' . "\n";
      }
    }

    include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
?>

</div>

<?php
  } else { // default page
?>

<div class="page-header">
  <h1><?php echo HEADING_TITLE; ?></h1>
</div>

<?php
  if ($messageStack->size('product_action') > 0) {
    echo $messageStack->output('product_action');
  }
?>

<div class="contentContainer">
  <div class="contentText">
    <?php echo tep_customer_greeting(); ?>
	<br><br>
  </div>

  <!-- added to install Facts and Quotes Module -->
  <div class="row">
   <?php echo $oscTemplate->getContent('index'); ?>
  </div>
  
<?php
    if (tep_not_null(TEXT_MAIN)) {
?>

  <div class="contentText">
    <?php echo TEXT_MAIN; ?>
  </div>

<?php
    }

    include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
    include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS);
?>

</div>

<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
