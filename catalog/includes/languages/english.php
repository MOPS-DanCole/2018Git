<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_ALL, array('en_US.UTF-8', 'en_US.UTF8', 'enu_usa'));

define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('JQUERY_DATEPICKER_I18N_CODE', ''); // leave empty for en_US; see http://jqueryui.com/demos/datepicker/#localization
define('JQUERY_DATEPICKER_FORMAT', 'mm/dd/yy'); // see http://docs.jquery.com/UI/Datepicker/formatDate

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'CAD');

// Global entries for the <html> tag
define('HTML_PARAMS', 'dir="ltr" lang="en"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', '<i class="fa fa-user"></i> Create an Account');
define('HEADER_TITLE_MY_ACCOUNT', 'My Account');
define('HEADER_TITLE_CART_CONTENTS', 'Cart Contents');
define('HEADER_TITLE_CHECKOUT', 'Checkout');
define('HEADER_TITLE_TOP', '<i class="fa fa-home"></i>');
define('HEADER_TITLE_CATALOG', 'Catalog');
define('HEADER_TITLE_LOGOFF', 'Log Off');
define('HEADER_TITLE_LOGIN', 'Log In');

// text for gender
define('MALE', 'M<span class="hidden-xs">ale</span>');
define('FEMALE', 'F<span class="hidden-xs">emale</span>');
define('MALE_ADDRESS', 'Mr.');
define('FEMALE_ADDRESS', 'Ms.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Delivery Information');
define('CHECKOUT_BAR_PAYMENT', 'Payment Information');
define('CHECKOUT_BAR_CONFIRMATION', 'Confirmation');
define('CHECKOUT_BAR_FINISHED', 'Finished!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Please Select');
define('TYPE_BELOW', 'Type Below');

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form.\n\nPlease make the following corrections:\n\n');

define('JS_REVIEW_TEXT', '* The \'Review Text\' must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.\n');
define('JS_REVIEW_RATING', '* You must rate the product for your review.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Please select a payment method for your order.\n');

define('JS_ERROR_SUBMITTED', 'This form has already been submitted. Please press Ok and wait for this process to be completed.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Please select a payment method for your order.');

define('CATEGORY_COMPANY', 'Company Details');
define('CATEGORY_PERSONAL', 'Your Personal Details');
define('CATEGORY_ADDRESS', 'Your Address');
define('CATEGORY_CONTACT', 'Your Contact Information');
define('CATEGORY_OPTIONS', 'Options');
define('CATEGORY_PASSWORD', 'Your Password');

define('ENTRY_COMPANY', 'Company Name');
define('ENTRY_COMPANY_TEXT', '');
// BOF Separate Pricing Per Customer
define('ENTRY_COMPANY_TAX_ID', 'Company\'s tax id number:');
define('ENTRY_COMPANY_TAX_ID_ERROR', '');
define('ENTRY_COMPANY_TAX_ID_TEXT', '');
// EOF Separate Pricing Per Customer
define('ENTRY_GENDER', 'Gender');
define('ENTRY_GENDER_ERROR', 'Please select your Gender.');
define('ENTRY_GENDER_TEXT', '');
define('ENTRY_FIRST_NAME', 'First Name');
define('ENTRY_FIRST_NAME_ERROR', 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_FIRST_NAME_TEXT', '');
define('ENTRY_LAST_NAME', 'Last Name');
define('ENTRY_LAST_NAME_ERROR', 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_LAST_NAME_TEXT', '');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', 'eg. 05/21/1970');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Your E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Your E-Mail Address does not appear to be valid - please make any necessary corrections.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Your E-Mail Address already exists in our records - please log in with the e-mail address or create an account with a different address.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '');
define('ENTRY_STREET_ADDRESS', 'Street Address');
define('ENTRY_STREET_ADDRESS_ERROR', 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_STREET_ADDRESS_TEXT', '');
define('ENTRY_SUBURB', 'Suburb');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Post Code');
define('ENTRY_POST_CODE_ERROR', 'Your Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');
define('ENTRY_POST_CODE_TEXT', '');
define('ENTRY_CITY', 'City');
define('ENTRY_CITY_ERROR', 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.');
define('ENTRY_CITY_TEXT', '');
define('ENTRY_STATE', 'State/Province');
define('ENTRY_STATE_ERROR', 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.');
define('ENTRY_STATE_ERROR_SELECT', 'Please select a state from the States pull down menu.');
define('ENTRY_STATE_TEXT', '');
define('ENTRY_COUNTRY', 'Country');
define('ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');
define('ENTRY_COUNTRY_TEXT', '');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Your Telephone Number must contain a minimum of ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '');
define('ENTRY_FAX_NUMBER', 'Secondary Number');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_PASSWORD', 'Password');
define('ENTRY_PASSWORD_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');
define('ENTRY_PASSWORD_TEXT', '');
define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '');
define('ENTRY_PASSWORD_CURRENT', 'Current Password');
define('ENTRY_PASSWORD_CURRENT_TEXT', '');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW', 'New Password');
define('ENTRY_PASSWORD_NEW_TEXT', '');
define('ENTRY_PASSWORD_NEW_ERROR', 'Your new Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your new Password.');
define('PASSWORD_HIDDEN', '--HIDDEN--');

define('ENTRY_OPTIONAL', 'Optional');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Result Pages:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> orders)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> reviews)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> new products)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> specials)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'First Page');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Previous Page');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Next Page');
define('PREVNEXT_TITLE_LAST_PAGE', 'Last Page');
define('PREVNEXT_TITLE_PAGE_NO', 'Page %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Previous Set of %d Pages');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Next Set of %d Pages');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Prev]');
define('PREVNEXT_BUTTON_NEXT', '[Next&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Add Address');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Address Book');
define('IMAGE_BUTTON_BACK', 'Back');
define('IMAGE_BUTTON_BUY_NOW', 'Buy Now');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Change Address');
define('IMAGE_BUTTON_CHECKOUT', 'Checkout');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirm Order');
define('IMAGE_BUTTON_CONTINUE', 'Continue');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continue Shopping');
define('IMAGE_BUTTON_DELETE', 'Delete');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Edit Account');
define('IMAGE_BUTTON_HISTORY', 'Order History');
define('IMAGE_BUTTON_LOGIN', 'Sign In');
define('IMAGE_BUTTON_IN_CART', 'Add to Cart');
define('IMAGE_BUTTON_PREORDER', 'Pre-Order Now');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Notifications');
define('IMAGE_BUTTON_QUICK_FIND', 'Quick Find');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Remove Notifications');
define('IMAGE_BUTTON_REVIEWS', 'Reviews');
define('IMAGE_BUTTON_SEARCH', 'Search');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Shipping Options');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Tell a Friend');
define('IMAGE_BUTTON_UPDATE', 'Update');
define('IMAGE_BUTTON_UPDATE_CART', 'Update Cart');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Write Review');

define('SMALL_IMAGE_BUTTON_DELETE', 'Delete');
define('SMALL_IMAGE_BUTTON_EDIT', 'Edit');
define('SMALL_IMAGE_BUTTON_VIEW', 'View');
define('SMALL_IMAGE_BUTTON_BUY', 'Buy');

define('ICON_ARROW_RIGHT', 'more');
define('ICON_CART', 'In Cart');
define('ICON_ERROR', 'Error');
define('ICON_SUCCESS', 'Success');
define('ICON_WARNING', 'Warning');

define('TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s!</span> Would you like to see which <a href="%s"><u>new products</u></a> are available to purchase?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="%s"><u>log yourself in</u></a> with your account information.</small>');
define('TEXT_GREETING_GUEST', 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="%s"><u>log yourself in</u></a>? Or would you prefer to <a href="%s"><u>create an account</u></a>?');

define('TEXT_SORT_PRODUCTS', 'Sort products ');
define('TEXT_DESCENDINGLY', 'descendingly');
define('TEXT_ASCENDINGLY', 'ascendingly');
define('TEXT_BY', ' by ');

define('TEXT_REVIEW_BY', 'by %s');
define('TEXT_REVIEW_WORD_COUNT', '%s words');
define('TEXT_REVIEW_RATING', 'Rating: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Date Added: %s');
define('TEXT_NO_REVIEWS', 'There are no reviews for this item at present.   If you use this product please let us know what you think of it.');

define('TEXT_NO_NEW_PRODUCTS', 'There are currently no products.');

define('TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');

define('TEXT_REQUIRED', '<span class="errorText">Required</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><strong><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</strong></font>');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid. Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid. Please check the number and try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s. If that number is correct, we do not accept that type of credit card. If it is wrong, please try again.');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a>');

// footer icons
// courtesy of Orman Clark for PremiumPixels.com
define('FOOTER_TEXT_PAYMENT_ICONS', tep_image(DIR_WS_ICONS . 'visa.png', 'Visa', '', '', '', false). tep_image(DIR_WS_ICONS . 'paypal.png', 'Paypal', '', '', '', false) . tep_image(DIR_WS_ICONS . 'mastercard.png', 'MasterCard', '', '', '', false));

// category views
define('TEXT_SHOW', 'View: ');
define('TEXT_VIEW', 'View: ');
define('TEXT_VIEW_LIST', ' List');
define('TEXT_VIEW_GRID', ' Grid');

// search placeholder
define('TEXT_SEARCH_PLACEHOLDER','Search');

// message for required inputs
//define('FORM_REQUIRED_INFORMATION', '<span class="glyphicon glyphicon-asterisk inputRequirement"></span> Required information');
//define('FORM_REQUIRED_INPUT', '<span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>');
define('FORM_REQUIRED_INFORMATION', '');
define('FORM_REQUIRED_INPUT', '');

// reviews
define('REVIEWS_TEXT_RATED', 'Rated %s by <cite title="%s">%s</cite>');
define('REVIEWS_TEXT_AVERAGE', 'Average rating based on %s review(s) %s');
define('REVIEWS_TEXT_TITLE', 'What our customers say...');

// grid/list
define('TEXT_SORT_BY', 'Sort By ');
// moved from index
define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Product Name');
define('TABLE_HEADING_MANUFACTURER', 'Manufacturer');
define('TABLE_HEADING_QUANTITY', 'Quantity');
define('TABLE_HEADING_PRICE', 'Price');
define('TABLE_HEADING_WEIGHT', 'Weight');
define('TABLE_HEADING_BUY_NOW', 'Buy Now');
define('TABLE_HEADING_LATEST_ADDED', 'Latest Products');

//header titles
define('HEADER_CART_CONTENTS', '<i class="fa fa-shopping-cart"></i> %s ITEM(S)');
define('HEADER_CART_NO_CONTENTS', '<i class="fa fa-shopping-cart"></i> 0 ITEMS');
define('HEADER_ACCOUNT_LOGGED_OUT', '<i class="fa fa-cog"></i> MY ACCOUNT');
define('HEADER_ACCOUNT_LOGGED_IN', '<i class="fa fa-heart-o"></i> %s - View Your Account');
define('HEADER_SITE_SETTINGS', '<i class="fa fa-cog"></i><span class="hidden-sm"> Site Settings</span> <span class="caret"></span>');
define('HEADER_TOGGLE_NAV', 'Toggle Navigation');
define('HEADER_HOME', '<i class="fa fa-home"></i> HOME');
define('HEADER_MAILING_LIST', '<i class="fa fa-envelope-o"></i> JOIN OUR EMAIL LIST');
define('HEADER_EMAIL_TITLE', '<i class="fa fa-envelope-o fa-2x"></i> Join our Mailing List to receive NEW Product announcements, advance notice about specials and other hobby related information!');
define('HEADER_WHATS_NEW', '<i class="fa fa-star"></i> NEW PRODUCTS');
define('HEADER_SPECIALS', '<i class="fa fa-usd"></i> SPECIALS');
define('HEADER_REVIEWS', '<i class="fa fa-pencil-square-o"></i> REVIEWS');
// header dropdowns
define('HEADER_ACCOUNT_LOGIN', '<i class="glyphicon glyphicon-log-in"></i> Log In');
define('HEADER_ACCOUNT_LOGOFF', '<i class="glyphicon glyphicon-log-out"></i> Log Off');
define('HEADER_ACCOUNT', 'My Account');
define('HEADER_ACCOUNT_HISTORY', 'My Orders');
define('HEADER_ACCOUNT_EDIT', 'My Details');
define('HEADER_ACCOUNT_ADDRESS_BOOK', 'My Address Book');
define('HEADER_ACCOUNT_PASSWORD', 'My Password');
define('HEADER_ACCOUNT_REGISTER', '<i class="glyphicon glyphicon-pencil"></i> Register');
define('HEADER_CART_HAS_CONTENTS', '%s item(s), %s');
define('HEADER_CART_VIEW_CART', 'View Cart');
define('HEADER_CART_CHECKOUT', '<i class="glyphicon glyphicon-chevron-right"></i> Checkout');
define('USER_LOCALIZATION', '<abbr title="Selected Language">L:</abbr> %s <abbr title="Selected Currency">C:</abbr> %s');

// product notifications
define('PRODUCT_SUBSCRIBED', '%s has been added to your Notification List');
define('PRODUCT_UNSUBSCRIBED', '%s has been removed from your Notification List');
define('PRODUCT_ADDED', '%s has been added to your Cart');
define('PRODUCT_REMOVED', '%s has been removed from your Cart');

// gablehauser template
define('CONTACT_US', '<i class="glyphicon glyphicon-phone-alt"></i> CALL US: %s');
define('OPEN_MAIN_MENU', 'Open Main Menu');
define('HEADER_SHOP', '<i class="fa fa-shopping-cart"></i> Shop <span class="fa fa-angle-double-down"></span>');
define('HEADER_CATEGORY', 'Categories  <span class="fa fa-angle-double-down"></span>');
define('HEADER_CONTACT_US', '<i class="fa fa-comments"></i> Contact Us');
define('HEADER_CART_CONTENTS_XS', '<a role="button" class="btn btn-info btn-block" href="' . tep_href_link('shopping_cart.php') . '"><i class="glyphicon glyphicon-shopping-cart"></i> %s item(s) totalling %s</a>');

//Wishlist
define('MY_WISHLIST_TITLE','Wish List');
define('MY_WISHLIST_VIEW','View the wish list I made');
define('IMAGE_BUTTON_WISHLIST', 'Wish List');
define('IMAGE_BUTTON_CLOSE', ' Close');
define('ENTRY_MESSAGE', 'Enter Your Message');
define('WISHLIST_ENTRY_NAME', 'Name');
define('ENTRY_WISH_NAME', 'Your Name');
define('ENTRY_WISH_EMAIL_ADDRESS', 'Your E-Mail Address');
define('HEADER_ACCOUNT_WISHLIST', 'My Wish List');

// http://www.linuxuk.co.uk - Notify when back in stock. Start
define('NOTIFY_MESSAGE', 'You be emailed confirmation of this notification');
define('NOTIFY_EMAIL','Your Email Address:');
define('NOTIFY_NAME', 'Your Name');
define('MESSAGE_1', 'Notify me when ');
define('MESSAGE_2', ' is re-stocked.');
define('NOTIFY_EMAIL_SUBJECT', 'A customer has requested to be Alerted when you re stock.');
define('NOTIFY_EMAIL_WELCOME', 'New Stock Alert');
define('NOTIFY_EMAIL_NAME', 'Customers name. ');
define('NOTIFY_EMAIL_EMAIL','Customers E Mail Address. ');
define('NOTIFY_EMAIL_PNAME', 'Products Name: ') ;
define('NOTIFY_REQUESTED', 'Product Requested: ');
define('NOTIFY_EMAIL_SUBJECT2', 'Your Stock alert Confirmation.');
define('CUSTOMER_NOTIFIED', 'Thank you for registering for your stock Alert from ' . STORE_NAME);
define('CUSTOMER_NOTIFIED1', 'We will let you know instantly via email, when we add new stock. Please see items details below');
define('CUSTOMER_NOTIFIED2', 'Products name: ');
define('CUSTOMER_NOTIFIED3', 'Please feel free to contact us at anytime, our email adress is: ');
define('CUSTOMER_NOTIFIED4', 'Thank you and have a great day.');
define('ENTRY_NAME', 'Your Full Name');
define('ENTRY_EMAIL', 'Enter Your E-Mail Address');
define('SUCCESS_TEXT1', 'Thank you, you will be notified when ');
define('SUCCESS_TEXT2', ' is back in stock.');
define('IMAGE_BUTTON_CLOSE', 'Close');
define('IMAGE_BUTTON_SEND', 'Send');
//define('NO_STOCK_MESSAGE', 'Out of Stock <i class="fa fa-info-circle"></i>');
define('TEXT_LOWER_LIMIT', ' Low Stock available');
define('TEXT_MEDIUM_LIMIT', ' Limited stock available');
define('TEXT_PLENTY_LIMIT', ' Stock Available in quantity');
// http://www.linuxuk.co.uk - Notify when back in stock. End

//kgt - discount coupons
define('ENTRY_DISCOUNT_COUPON_NO_ENTRY', 'Please enter your discount coupon code.');
define('ENTRY_DISCOUNT_COUPON_SUCCESS', 'The discount coupon code was accepted and has been applied to your order.');
define('ENTRY_DISCOUNT_COUPON_ERROR', 'The coupon code you have entered is not valid.');
define('ENTRY_DISCOUNT_COUPON_AVAILABLE_ERROR', 'The coupon code you have entered is no longer valid.');
define('ENTRY_DISCOUNT_COUPON_USE_ERROR', 'Our records show that you have used this coupon %s time(s).  You may not use this code more than %s time(s).');
define('ENTRY_DISCOUNT_COUPON_MIN_PRICE_ERROR', 'The minimum order total for this coupon is %s');
define('ENTRY_DISCOUNT_COUPON_MIN_QUANTITY_ERROR', 'The minimum number of products required for this coupon is %s');
define('ENTRY_DISCOUNT_COUPON_EXCLUSION_ERROR', 'Some or all of the products in your cart are excluded.' );
define('ENTRY_DISCOUNT_COUPON', 'Coupon Code:');
define('ENTRY_DISCOUNT_COUPON_SHIPPING_CALC_ERROR', 'Your calculated shipping charges have changed.');
//end kgt - discount coupons

// testimonials contribution
define('TEXT_DISPLAY_NUMBER_OF_TESTIMONIALS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> testimonials)');

// product description more
define('TEXT_PRODUCT_DESCRIPTION_MORE', '...read more.');