<?php
/*
  $Id: privacy.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Newsletter Subscriptions!');
define('HEADING_TITLE', 'Newsletter Subscription - What\'s Next?');
define('ERROR_ALREADY_SUBSCRIBED', 'You\'re already subscribed to our email list.  If you are not receiving emails from us please add service@mops.ca to your address book 
                                    or your safe list to ensure they aren\'t ending up in your spam folder.');
define('ERROR_NOT_SUBSCRIBED', 'Sorry we couldn\'t find that email address.  Did you use a different one when you subscribed?');
									
define('TEXT_INFORMATION_SUBSCRIBED', '<p><u><b>Subscribing</b></u><br>For your protection, we adhere to anti-spam guidelines and use a
       "double opt-in" process for our mailing list.  Now that you have submitted your email
		address you will receive a confirmation email that you\'ll be asked to reply to.</p>

        <p>By replying to the confirmation email, you have "double opted-in" which means that your email address is
        valid and that you would like to join our mailing list.  If you do not reply to 
        the confirming email you will not be added to our mailing list. This prevents anyone
        from being added accidentally or without their consent.</p>

		<p>Thank you for interest in joining our email list.</p>');
		
define('TEXT_INFORMATION_UNSUBSCRIBED', '<p><u><b>UnSubscribing</b></u><br>You have been removed from our mailing list. You don\'t need to anything further.  Your email subscription
		has been updated and you will no longer receive promotional emails from us. </p>');
		
define('TEXT_INFORMATION_OLD_LIST_UNSUBSCRIBED', '<p><u><b>UnSubscribing</b></u><br>If you are unsubscribing, you have already been removed from our mailing list. You don\'t need to anything further.  Your email subscription
		has been updated and you will no longer receive promotional emails from us. </p>');
		
		
?>