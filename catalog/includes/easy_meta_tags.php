<?php
// Easy Meta Tags 1.2.5 for osCommerce MS2.2/RC2.2/2.31

//index page metas
if (basename($PHP_SELF) == FILENAME_DEFAULT) { 
if (isset($HTTP_GET_VARS['products_id'])){
$product_meta_query = tep_db_query("select products_name, short_desc from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
$product_meta = tep_db_fetch_array($product_meta_query);
$meta_title_tag = $meta_description; 
$meta_description = preg_replace('/<[^>]*>/', '', $product_meta['short_desc']);
$meta_description = preg_replace('/\s\s+/', ' ',$meta_description);

if (strlen($meta_description) > 200){
$meta_description_tag = substr($meta_description, 0, 200) . '...';
}else {
$meta_description_tag = $meta_description;
}
$meta_keywords_tag = $product_meta['products_name'];
}
if ((isset($_GET['cPath'])) && (!isset($HTTP_GET_VARS['products_id']))) {
	$category_name_query = tep_db_query("select categories_seo_title, cd.categories_seo_description, cd.categories_seo_keywords, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
	$category_name = tep_db_fetch_array($category_name_query); 
if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] != '1')) {
	$page = '-' . $HTTP_GET_VARS['page'];
	}
$meta_title_tag = $category_name['categories_seo_title'] . (isset($page) ? $page : '');
$meta_description_tag = $category_name['categories_seo_description'] . (isset($page) ? $page : '') . ' : ' . STORE_NAME;
$meta_keywords_tag = $category_name['categories_seo_keywords'] . (isset($page) ? $page : '');

if (($category_name['categories_seo_description']) == '') {
	$meta_title_tag = $category_name['categories_name'] . (isset($page) ? $page : '');
	$meta_description_tag = $category_name['categories_name'] . (isset($page) ? $page : '') . ' : ' . STORE_NAME;
	$meta_keywords_tag = $category_name['categories_name']. (isset($page) ? $page : '');
	}
 }
if ((isset($HTTP_GET_VARS['manufacturers_id'])) && (!isset($HTTP_GET_VARS['products_id']))) { 
$manufacturers_name_query = tep_db_query("select m.manufacturers_name from " . TABLE_MANUFACTURERS . " m where m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
$manufacturers_name = tep_db_fetch_array($manufacturers_name_query); 
 
$meta_title_tag = $manufacturers_name['manufacturers_name'];
$meta_description_tag = $manufacturers_name['manufacturers_name'] . ' : ' . STORE_NAME;
$meta_keywords_tag = $manufacturers_name['manufacturers_name'];
 }
if ((!isset($_GET['cPath'])) && (!isset($HTTP_GET_VARS['manufacturers_id'])) && (!isset($HTTP_GET_VARS['products_id']))) { 
$meta_title_tag = META_SEO_TITLE;
$meta_description_tag = META_SEO_DESCRIPTION;
$meta_keywords_tag = META_SEO_KEYWORDS; 
 }
// product info metas
} elseif  (basename($PHP_SELF) == FILENAME_PRODUCT_INFO) {
$product_meta_query = tep_db_query("select products_name, short_desc from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
$product_meta = tep_db_fetch_array($product_meta_query);
$meta_title_tag = $product_meta['products_name']; 
$meta_description = preg_replace('/<[^>]*>/', '', $product_meta['short_desc']);
$meta_description = preg_replace('/\s\s+/', ' ',$meta_description);

if (strlen($meta_description) > 200){
$meta_description_tag = substr($meta_description, 0, 200) . '...';
}else {
$meta_description_tag = $meta_description;
}
$meta_keywords_tag = $product_meta['products_name'];
// product reviews info metas
} elseif  (basename($PHP_SELF) == FILENAME_PRODUCT_REVIEWS_INFO) {
$reviews_meta_query = tep_db_query("select rd.reviews_text, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS_DESCRIPTION . " pd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.products_id = pd.products_id and pd.language_id = '". (int)$languages_id . "'");
$reviews_meta = tep_db_fetch_array($reviews_meta_query);
$meta_title_tag = NAVBAR_TITLE . ' :: ' . $reviews_meta['products_name']; 
$meta_description = preg_replace('/<[^>]*>/', '', $reviews_meta['reviews_text']);
$meta_description = preg_replace('/\s\s+/', ' ',$meta_description);
if (strlen($meta_description) > 200){
$meta_description_tag = substr($meta_description, 0, 200) . '...';
}else {
$meta_description_tag = $meta_description;
}
$meta_keywords_tag = NAVBAR_TITLE . ' ' . $reviews_meta['products_name'];
 } else {
//other pages  
if (HEADING_TITLE != 'HEADING_TITLE') {
$heading_title = HEADING_TITLE;	
} else { 
$heading_title = basename($PHP_SELF); 
$patterns[0] = '/.php/';
$patterns[1] = '/.html/';
$patterns[2] = '/-/';
$patterns[3] = '/_/';
$replacements[0] = '';
$replacements[1] = '';
$replacements[2] = ' ';
$replacements[3] = ' ';
$heading_title = preg_replace($patterns, $replacements, $heading_title);
$heading_title = ucwords($heading_title);
}
$meta_title_tag = $heading_title;  
$meta_description_tag = $heading_title . ' : ' . STORE_NAME;
$meta_keywords_tag = $heading_title;
}

echo '<title>' . $meta_title_tag . '</title>' . "\n";
echo '<meta name="description" content="' . $meta_description_tag . '" />' . "\n";
echo '<meta name="keywords" content="' . $meta_keywords_tag . '" />' . "\n";

?>