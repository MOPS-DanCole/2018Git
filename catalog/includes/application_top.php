<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// check support for register_globals
  if (function_exists('ini_get') && (ini_get('register_globals') == false) && (PHP_VERSION < 4.3) ) {
    exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory. Please use PHP 4.3+ if register_globals cannot be enabled on the server.');
  }

// load server configuration parameters
  if (file_exists('includes/local/configure.php')) { // for developers
    include('includes/local/configure.php');
  } else {
    include('includes/configure.php');
  }

  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }

// define the project version --- obsolete, now retrieved with tep_get_version()
  define('PROJECT_VERSION', 'osCommerce Online Merchant v2.3');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  $req = parse_url($HTTP_SERVER_VARS['SCRIPT_NAME']);
  $PHP_SELF = substr($req['path'], ($request_type == 'NONSSL') ? strlen(DIR_WS_HTTP_CATALOG) : strlen(DIR_WS_HTTPS_CATALOG));

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set the application parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// if gzip_compression is enabled, start to buffer the output
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && !headers_sent() ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      if (PHP_VERSION < '5.4' || PHP_VERSION > '5.4.5') { // see PHP bug 55544
        if (PHP_VERSION >= '4.0.4') {
          ob_start('ob_gzhandler');
        } elseif (PHP_VERSION >= '4.0.1') {
          include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
          ob_start();
          ob_implicit_flush();
        }
      }
    } elseif (function_exists('ini_set')) {
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }

// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen(getenv('PATH_INFO')) > 1) {
      $GET_array = array();
      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
      $vars = explode('/', substr(getenv('PATH_INFO'), 1));
      do_magic_quotes_gpc($vars);
      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {
        if (strpos($vars[$i], '[]')) {
          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];
        } else {
          $HTTP_GET_VARS[$vars[$i]] = $vars[$i+1];
        }
        $i++;
      }

      if (sizeof($GET_array) > 0) {
        while (list($key, $value) = each($GET_array)) {
          $HTTP_GET_VARS[$key] = $value;
        }
      }
    }
  }

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// include cache functions if enabled
  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');
  
// include wishlist class
  require(DIR_WS_CLASSES . 'wishlist.php');

// include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCsid');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

  @ini_set('session.use_only_cookies', (SESSION_FORCE_COOKIE_USE == 'True') ? 1 : 0);

// set the session ID if it exists
  if ( SESSION_FORCE_COOKIE_USE == 'False' ) {
    if ( isset($HTTP_GET_VARS[tep_session_name()]) && (!isset($HTTP_COOKIE_VARS[tep_session_name()]) || ($HTTP_COOKIE_VARS[tep_session_name()] != $HTTP_GET_VARS[tep_session_name()])) ) {
      tep_session_id($HTTP_GET_VARS[tep_session_name()]);
    } elseif ( isset($HTTP_POST_VARS[tep_session_name()]) && (!isset($HTTP_COOKIE_VARS[tep_session_name()]) || ($HTTP_COOKIE_VARS[tep_session_name()] != $HTTP_POST_VARS[tep_session_name()])) ) {
      tep_session_id($HTTP_POST_VARS[tep_session_name()]);
    }
  }

// start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

    if (isset($HTTP_COOKIE_VARS['cookie_test'])) {
      tep_session_start();
      $session_started = true;
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (tep_not_null($user_agent)) {
      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (tep_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
            break;
          }
        }
      }
    }

    if ($spider_flag == false) {
      tep_session_start();
      $session_started = true;
    }
  } else {
    tep_session_start();
    $session_started = true;
  }

  if ( ($session_started == true) && (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }

// initialize a session token
  if (!tep_session_is_registered('sessiontoken')) {
    $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());
    tep_session_register('sessiontoken');
  }

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!tep_session_is_registered('SSL_SESSION_ID')) {
      $SESSION_SSL_ID = $ssl_session_id;
      tep_session_register('SESSION_SSL_ID');
    }

    if ($SESSION_SSL_ID != $ssl_session_id) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
  }

// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!tep_session_is_registered('SESSION_USER_AGENT')) {
      $SESSION_USER_AGENT = $http_user_agent;
      tep_session_register('SESSION_USER_AGENT');
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = tep_get_ip_address();
    if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {
      $SESSION_IP_ADDRESS = $ip_address;
      tep_session_register('SESSION_IP_ADDRESS');
    }

    if ($SESSION_IP_ADDRESS != $ip_address) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// create the shopping cart
  if (!tep_session_is_registered('cart') || !is_object($cart)) {
    tep_session_register('cart');
    $cart = new shoppingCart;
  }

// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// set the language
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {
      $lng->set_language($HTTP_GET_VARS['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }

  $language="english"; 
// include the language translations
  $_system_locale_numeric = setlocale(LC_NUMERIC, 0);
  require(DIR_WS_LANGUAGES . $language . '.php');
  setlocale(LC_NUMERIC, $_system_locale_numeric); // Prevent LC_ALL from setting LC_NUMERIC to a locale with 1,0 float/decimal values instead of 1.0 (see bug #634)

// Ultimate SEO URLs v2.2d
 if ((!defined(SEO_ENABLED)) || (SEO_ENABLED == 'true')) {
   include_once(DIR_WS_CLASSES . 'seo.class.php');
   if ( !is_object($seo_urls) ){
     $seo_urls = new SEO_URL($languages_id);
   }
 }

// currency
  if (!tep_session_is_registered('currency') || isset($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!tep_session_is_registered('currency')) tep_session_register('currency');

    if (isset($HTTP_GET_VARS['currency']) && $currencies->is_set($HTTP_GET_VARS['currency'])) {
      $currency = $HTTP_GET_VARS['currency'];
    } else {
      $currency = ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && $currencies->is_set(LANGUAGE_CURRENCY)) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }

// navigation history
  if (!tep_session_is_registered('navigation') || !is_object($navigation)) {
    tep_session_register('navigation');
    $navigation = new navigationHistory;
  }
  $navigation->add_current_page();

// action recorder
  include('includes/classes/action_recorder.php');
// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'alertbox.php');
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// wishlist data
  if(!tep_session_is_registered('wishList')) {
  	tep_session_register('wishList');
  	$wishList = new wishlist;
  }

//Wishlist actions (must be before shopping cart actions)
  if (isset($HTTP_POST_VARS['wishlist'])) {
	  if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id'])) {
      $attributes = isset($HTTP_POST_VARS['id']) ? $HTTP_POST_VARS['id'] : '';
      $wishList->add_wishlist($HTTP_POST_VARS['products_id'], $wishList->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $attributes))+1, $attributes);
	  }
		if (WISHLIST_REDIRECT ==  'No') tep_redirect(tep_href_link('product_info.php', 'products_id=' . $HTTP_POST_VARS['products_id']));
	  tep_redirect(tep_href_link('wishlist.php'));
  }
  
// Shopping cart actions
  if (isset($HTTP_GET_VARS['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
      $goto = $PHP_SELF;
      if ($HTTP_GET_VARS['action'] == 'buy_now') {
        $parameters = array('action', 'pid', 'products_id');
      } else {
        $parameters = array('action', 'pid');
      }
    }
    switch ($HTTP_GET_VARS['action']) {
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {
                                if (in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {
                                  $cart->remove($HTTP_POST_VARS['products_id'][$i]);
                                  $messageStack->add_session('product_action', sprintf(PRODUCT_REMOVED, tep_get_products_name($HTTP_POST_VARS['products_id'][$i])), 'warning');
                                } else {
                                  $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
                                  $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes, false);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
 // customer adds a product from the products page
          case 'add_product' :  if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id'])) {
                                 $attributes = isset($HTTP_POST_VARS['id']) ? $HTTP_POST_VARS['id'] : '';
                                 $quantity   = (isset($_POST['cart_quantity']) ? (int)$_POST['cart_quantity'] : 1);
								 $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $attributes))+$quantity, $attributes);
                                 }
							     $messageStack->add_session('product_action', sprintf(PRODUCT_ADDED, tep_get_products_name((int)$HTTP_POST_VARS['products_id'])), 'success');
                                 tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                                 break;	

      // customer removes a product from their shopping cart
      case 'remove_product' : if (isset($HTTP_GET_VARS['products_id'])) {
                                $cart->remove($HTTP_GET_VARS['products_id']);
                                $messageStack->add_session('product_action', sprintf(PRODUCT_REMOVED, tep_get_products_name($HTTP_GET_VARS['products_id'])), 'warning');
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // performed by the 'buy now' button in product listings and review page
      case 'buy_now' :        if (isset($HTTP_GET_VARS['products_id'])) {
                                if (tep_has_product_attributes($HTTP_GET_VARS['products_id'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']));
                                } else {
                                  $cart->add_cart($HTTP_GET_VARS['products_id'], $cart->get_quantity($HTTP_GET_VARS['products_id'])+1);
                                  $messageStack->add_session('product_action', sprintf(PRODUCT_ADDED, tep_get_products_name((int)$HTTP_GET_VARS['products_id'])), 'success');
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      case 'notify' :         if (tep_session_is_registered('customer_id')) {
                                if (isset($HTTP_GET_VARS['products_id'])) {
                                  $notify = $HTTP_GET_VARS['products_id'];
                                } elseif (isset($HTTP_GET_VARS['notify'])) {
                                  $notify = $HTTP_GET_VARS['notify'];
                                } elseif (isset($HTTP_POST_VARS['notify'])) {
                                  $notify = $HTTP_POST_VARS['notify'];
                                } else {
                                  tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action', 'notify'))));
                                }
                                if (!is_array($notify)) $notify = array($notify);
                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$notify[$i] . "' and customers_id = '" . (int)$customer_id . "'");
                                  $check = tep_db_fetch_array($check_query);
                                  if ($check['count'] < 1) {
                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . (int)$notify[$i] . "', '" . (int)$customer_id . "', now())");
                                  }
                                }
                                $messageStack->add_session('product_action', sprintf(PRODUCT_SUBSCRIBED, tep_get_products_name((int)$HTTP_GET_VARS['products_id'])), 'success');
                                tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action', 'notify'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'notify_remove' :  if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['products_id'])) {
                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                                $check = tep_db_fetch_array($check_query);
                                if ($check['count'] > 0) {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                                }
                                $messageStack->add_session('product_action', sprintf(PRODUCT_UNSUBSCRIBED, tep_get_products_name((int)$HTTP_GET_VARS['products_id'])), 'warning');
                                tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'cust_order' :     if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['pid'])) {
                                if (tep_has_product_attributes($HTTP_GET_VARS['pid'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['pid']));
                                } else {
                                  $cart->add_cart($HTTP_GET_VARS['pid'], $cart->get_quantity($HTTP_GET_VARS['pid'])+1);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // http://www.linuxuk.co.uk - Notify when back in stock. Start
      case 'sn_notify':
                              tep_session_register('notify');
                              $sn_Date = tep_db_prepare_input(date("m/d/Y h:i",time()));
                              $sn_email = tep_db_prepare_input(tep_sanitize_string(trim($_POST['sn_email'])));
                              $product_id = tep_db_prepare_input($_POST['product_id']);
                              $sn_name = tep_db_prepare_input(tep_sanitize_string(trim($_POST['sn_name'])));
                              $sn_P_name = tep_db_prepare_input(tep_sanitize_string($_POST['product_name']));
                              tep_db_query("insert into `stock_notification` (sn_date,sn_email,product_id,sn_name,sn_P_name,sn_notify) VALUES ('".$sn_Date."','".$sn_email."','".$product_id."','".$sn_name."','".$sn_P_name."',1);");
                              // Email - Start
                              $new_notify_email = NOTIFY_EMAIL_WELCOME ."\n\n"
                              . "===================================================\n"
                              . NOTIFY_EMAIL_NAME . ($sn_name ==""? 'Guest': $sn_name) . "\n"
                              . NOTIFY_EMAIL_EMAIL . $sn_email . "\n"
                              . NOTIFY_EMAIL_PNAME . stripslashes($sn_P_name) . "\n"
                              . NOTIFY_REQUESTED . (EMAIL_USE_HTML=='true'?'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_id, 'NONSSL') . '">' .'View Item'  .'</a>' . "\n" :'HTML email is not enabled, please set to true to use this linked feature');
                              // Email Shop owener to notify of Alert
                              tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, NOTIFY_EMAIL_SUBJECT, $new_notify_email, $sn_name, $sn_email);
                              // Email the customer to confirm and thank them.
                              $email_notify_text =  CUSTOMER_NOTIFIED ."\n\n"
                              . "===================================================\n"
                              . CUSTOMER_NOTIFIED1 . "\n"
                              . CUSTOMER_NOTIFIED2 . stripslashes($sn_P_name) . "\n"
                              . (EMAIL_USE_HTML=='true'?NOTIFY_REQUESTED .'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_id, 'NONSSL') . '">' .'View Item'  .'</a>' . "\n" :'') ."\n"
                              . CUSTOMER_NOTIFIED3 . STORE_OWNER_EMAIL_ADDRESS ."\n"
                              . CUSTOMER_NOTIFIED4 . "\n";

                              tep_mail($sn_name, $sn_email, NOTIFY_EMAIL_SUBJECT2, $email_notify_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                              // Return to the Product info page/

                              tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_POST['product_id']));
                              break;
      // http://www.linuxuk.co.uk - Notify when back in stock. End
			      
    }
  }

// include the who's online functions
  require(DIR_WS_FUNCTIONS . 'whos_online.php');
  tep_update_whos_online();

// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// infobox
  require(DIR_WS_CLASSES . 'boxes.php');

// auto activate and expire banners
  require(DIR_WS_FUNCTIONS . 'banner.php');
  tep_activate_banners();
  tep_expire_banners();

// auto expire special products
  require(DIR_WS_FUNCTIONS . 'specials.php');
  tep_expire_specials();

  require(DIR_WS_CLASSES . 'osc_template.php');
  $oscTemplate = new oscTemplate();

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
  } elseif (isset($HTTP_GET_VARS['products_id']) && !isset($HTTP_GET_VARS['manufacturers_id'])) {
    $cPath = tep_get_product_path($HTTP_GET_VARS['products_id']);
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 1; // set to default category to display category name for the title on the index page.
  }

// include category tree class
  require(DIR_WS_CLASSES . 'category_tree.php');
  
// include extended category tree class
  require(DIR_WS_CLASSES . 'explode_category_tree.php');  

// include the breadcrumb class and start the breadcrumb trail
  require(DIR_WS_CLASSES . 'breadcrumb.php');
  $breadcrumb = new breadcrumb;

  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
  $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

// add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath_array)) {
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      // header tags seo - reloaded
      $categories_query = tep_db_query("select coalesce(NULLIF(categories_seo_title, ''), categories_name) as categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      // eof
     if (tep_db_num_rows($categories_query) > 0) {
        $categories = tep_db_fetch_array($categories_query);
        $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
      } else {
        break;
      }
    }
  } elseif (isset($HTTP_GET_VARS['manufacturers_id'])) {
    // header tags seo - reloaded
    $manufacturers_query = tep_db_query("select coalesce(NULLIF(manufacturers_seo_title, ''), manufacturers_name) as manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
    // eof
    if (tep_db_num_rows($manufacturers_query)) {
      $manufacturers = tep_db_fetch_array($manufacturers_query);
      $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id']));
    }
  }

// add the products model to the breadcrumb trail
  if (isset($HTTP_GET_VARS['products_id'])) {
// BOF HEADER TAGS SEO - RELOADED
    $model_query = tep_db_query("select coalesce(NULLIF(pd.products_seo_title, ''), pd.products_name) as products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
// EOF HEADER TAGS SEO - RELOADED
	if (tep_db_num_rows($model_query)) {
      $model = tep_db_fetch_array($model_query);
      $breadcrumb->add($model['products_name'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $HTTP_GET_VARS['products_id']));
    }
  }
  
require(DIR_FS_CATALOG . 'includes/classes/hooks.php');
$OSCOM_Hooks = new hooks('shop');