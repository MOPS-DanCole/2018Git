<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_MAIN', '');
define('TABLE_HEADING_NEW_PRODUCTS', 'New Products For %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Upcoming Products');
define('TABLE_HEADING_DATE_EXPECTED', 'Date Expected');
define('HEADING_TITLE', 'Welcome to ' . STORE_NAME);

define('TEXT_NO_PRODUCTS', 'There are no products available in this category.');
define('TEXT_NUMBER_OF_PRODUCTS', 'Number of Products: ');
define('TEXT_SHOW', '<strong>Show:</strong>');
define('TEXT_BUY', 'Buy 1 \'');
define('TEXT_NOW', '\' now');
define('TEXT_ALL_CATEGORIES', 'All Categories');
define('TEXT_ALL_MANUFACTURERS', 'All Manufacturers');
define('TEXT_ALL_ITEMS', 'All');

if ( ($category_depth == 'top') && (!isset($HTTP_GET_VARS['manufacturers_id'])) ) {
  define('META_SEO_TITLE', 'Aquarium Supplies at Mail Order Pet Supplies');
  define('META_SEO_DESCRIPTION', 'We offer 1000s of brand name aquarium supply products as well as helpful articles, tips and other information of interest to the aquatic enthusiast. See us for all your aquarium supplies!');
  define('META_SEO_KEYWORDS', 'Mail Order Pet Supplies, your discount aquarium supply specialists!'); 	
  }

?>
