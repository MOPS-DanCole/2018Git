<?php
/*
  $Id: wishlist.php, revision 3  2014/11/21 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_WISHLIST', 'My Wish List');
define('NAVBAR_TITLE_PUBLIC_WISHLIST', '&rsquo;s Wish List');
define('HEADING_TITLE', 'My Wish List');
define('HEADING_TITLE2', '&rsquo;s Wish List contains:');
define('BOX_TEXT_PRICE', 'Total Price');
define('BOX_TEXT_PRODUCT', 'Product Name');
define('BOX_TEXT_IMAGE', 'Image');
define('BOX_TEXT_QTY', 'Quantity');
define('BOX_TEXT_SELECT', 'Select');
define('BOX_TEXT_SALE_EXPIRES', 'Sale Expires: ');

define('BOX_TEXT_VIEW', 'Show');
define('BOX_TEXT_HELP', 'Help');
define('BOX_WISHLIST_EMPTY', '0 items');
define('BOX_TEXT_NO_ITEMS', 'You have not added any products to your Wish List.');
define('TEXT_WISHLIST_HELP', 'Click here for help on using your Wish List.');
define('TEXT_WISHLIST_TOTAL', 'Total Wish List Value: ');

define('TEXT_NAME', 'Name');
define('TEXT_EMAIL', 'Email');
define('TEXT_YOUR_NAME', 'Your Name');
define('TEXT_YOUR_EMAIL', 'Your Email');
define('TEXT_MESSAGE', 'Message');
define('TEXT_ITEM_IN_CART', 'Item is in Cart');
define('TEXT_ITEM_NOT_AVAILABLE', 'Item no longer available');
define('TEXT_DISPLAY_NUMBER_OF_WISHLIST', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> items on this wish list)');
define('WISHLIST_EMAIL_TEXT', 'If you would like to email your wish list to multiple friends or family, just enter their name\'s and email\'s in each row.  You don\'t have to fill every box up, you can just fill in for however many people to whom you want to email your wish list link.  Then fill out a short message you would like to include in with your email in the text box provided.  This message will be added to all of the emails you send. Click the Send Wish List button to send the emails.');
define('WISHLIST_EMAIL_TEXT_GUEST', 'If you would like to email your wish list to multiple friends or family, please enter your name and email address.  Then enter their name\'s and email\'s in each row.  You don\'t have to fill every box up, you can just fill in for however many people to whom you want to email your wish list products.  Then fill out a short message you would like to include in with your email in the text box provided.  This message will be added to all of the emails you send. Click the Send Wish List button to send the emails.');
define('WISHLIST_EMAIL_SUBJECT', 'has sent you their wish list from ' . STORE_NAME);  //Customers name will be automatically added to the beginning of this.
define('WISHLIST_SENT', 'Your wish list has been sent.');
define('WISHLIST_EMAIL_LINK', '

%s\'s public wish list is located here:
<a href="%s">%s</a>

Thank you,
' . STORE_NAME); //$from_name = Customers name  $link = public wishlist link

define('WISHLIST_EMAIL_GUEST', ' wishes for the products listed above.

Thank you,
' . STORE_NAME);
define('WISHLIST_EMAIL_BUTTON', 'Send Wish List Email');
define('TEXT_WISHLIST_ADD_EMAIL', ' Add New Recipient');
define('TEXT_WISHLIST_REMOVE_EMAIL', ' Remove New Recipient');

define('ERROR_YOUR_NAME' , 'Please enter your Name.');
define('ERROR_YOUR_EMAIL' , 'Please enter your Email.');
define('ERROR_VALID_EMAIL' , 'Please enter a valid email address.');
define('ERROR_ONE_EMAIL' , 'You must include at least one name and email.');
define('ERROR_ENTER_EMAIL' , 'Please enter an email address.');
define('ERROR_ENTER_NAME' , 'Please enter the email recipents name.');
define('ERROR_MESSAGE', 'Please include a brief message.');
define('BUTTON_TEXT_ADD_CART', 'Add <span class="hidden-xs hidden-sm hidden-md">Checked Items<br /></span> To Shopping Cart');
define('BUTTON_TEXT_DELETE', 'Delete Checked Items <span class="hidden-xs hidden-sm hidden-md"><br />From Wish List</span>');
define('BUTTON_UPDATE_QTY', 'Update Item Quantities');
define('ERROR_ACTION_RECORDER', 'Error: An e-mail has already been sent. Please try again in %s minutes.');
define('ERROR_INVALID_LINK', 'Error: Your message may not contain links to other web sites!');
define('ERROR_SPAM_BLOCKED', 'ERROR! Attempt to send spam by accessing this script from another web site has been detected and blocked!');
define('TEXT_SPAM_SUBJECT', 'Attempted Wish List spam was blocked.');
define('TEXT_SPAM_MESSAGE', "Warning! The site detected an attempt to send spam from another web site using the Wish List script.\n\nDate and Time: %s\nCustomer ID: %s\nFrom Name: %s\nFrom Email: %s\n\n\nAttempted to connect from: %s\n\nRemote Address: %s  Port: %s\nUser Agent: %s\n\n\nThe following is the attempted message:\n\n\n");
define('TEXT_SPAM_NO_ID', 'No customer ID, not logged in');
// Wish List Help
define('TEXT_WISHLIST_HELP_TITLE', 'Wish List Help');
define('TEXT_INFORMATION', 'If you are a guest user on this site your wish list will remain only as long as your browser is open.<br /><br />Log in to your accout (or create one) and your wish list will be permanently saved.<br /><br />Items in a permanently saved wish list will remain there until:<ol><li>You remove it from your wish list yourself.</li><li>You transfer the item from your wish list to your shopping cart.</li><li>The item is permanently deleted from the web site.</li></ol><br />When viewing your wish list you may click the product name or image to view details about the product.<br /><br />Whan a guest user emails friends a wish list they will receive the list of products. If you are logged in to your account and email the wish list they will receive a link to a page where they can view your permanent wish list.<br /><br />The Add to Cart button on the wish list page transfers the checked items from your wish list to your shopping cart, thus removing them from the wish list. Whatever quantity is set for the product at the time of transfer is the quantity that will be added to the shopping cart. You do not have to Update Quantities first if you decide to buy a different quantity than you originally had in your wish list.<br /><br />The Update Quantities button will update the quantities for all items in the wish list. If the quantity is set less than one the product will be deleted from the wish list when the quantities are updated. If you change a quantity and then leave the Wish List page without clicking on the Update Quantities button the change will not be saved.');
?>
