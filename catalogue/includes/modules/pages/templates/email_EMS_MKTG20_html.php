<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php include(DIR_FS_CATALOG . DIR_WS_MODULES . 'pages/templates/html_email_head.php'); ?>
</head>

<body style="background-color:#F2F2F2;">
  <center>
    <table id="bodyTable" cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" style="background-color:#F2F2F2;">
      <tbody>
        <tr>
          <td id="bodyCell" valign="top" align="center" style="padding:40px 20px;">
            <table id="contentContainer" cellspacing="0" cellpadding="0" border="0" style="max-width:600px !important; width:100% !important;">
              <tr>
                <td align="center" valign="top" style="padding-bottom:0px;">
				
                  <table id="emailBody" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#FFFFFF; border-collapse:separate !important; border-radius:4px;">
                    <tr>
                      <td align="left" valign="top" class="mobilePadding" style=" padding-top:10px; padding-right:40px; padding-bottom:10px; padding-left:40px;">
                        
					  <p style="color:red; text-align:center;">&lt;To ensure receipt of our emails, please add service@mops.ca to your Address Book.&gt;</p>
                      <p><img src="<?php echo (ENABLE_SSL_CATALOG == 'true' ? HTTPS_CATALOG_SERVER . DIR_WS_HTTPS_CATALOG : HTTP_CATALOG_SERVER . DIR_WS_CATALOG) . DIR_WS_IMAGES . 'MOPS_Email_Header.jpg'; ?>" title="<?php echo STORE_NAME; ?>" alt="<?php echo STORE_NAME; ?>" height="150" width="600"></p>
                      
					  <p align="center"><a href="http://www.aquariumsupplies.ca/aquarium"><img align="right" border="0" hspace="0" src="http://www.aquariumsupplies.ca/aquarium/newsletter/images/Exclusive-20.jpg" vspace="0" /></a></p>
						<div style="line-height: 8px;">&nbsp;</div> 
						<h1 style="color:#606060 !important; font-size:16px; text-align: left;"><?php echo $name; ?></h1>
						
                        <?php echo $nInfo->content; ?>
						
						<p>Yours Very Truly,</p>
						<div style="text-align: left;"><img border="0" height="55" src="http://www.aquariumsupplies.ca/aquarium/newsletter/images/Dan_Cole_Signature.png" width="200" /></div>
						Dan Cole<br />
						<a href="http://www.aquariumsupplies.ca/aquarium">Mail Order Pet Supplies, Inc.</a>

						<p>PS: Do you know someone who might like to receive offers like this too?  If so, please suggest that they buy their consumable products from us. You can even get them started by sharing this Customer Exclusive Coupon with them.  They'll appreciate it and so will we!</p>
                      </td>
                    </tr>
				</table>
				                    
	              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="emailFooterTable">
                    <tr valign="top"><td align="center">
                        <div class="footer" style="background-color: #e0e0e0; color: #468bba; padding: 10px; text-align: center; font-size: 10px;">
                          <div class="footerCompany">
                            <div style="background:#eee;border:1px solid #ccc;padding:5px 10px;"><span style="font-family:verdana,geneva,sans-serif;"><span style="font-size: 12px;"><span class="HOEnZb"><font color="#888888">NOTE: If you do not wish to receive any further emails like this </font></span><a href="<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>newsletter_subscriptions_thank_you.php?email=<?php echo $email_address; ?>&Action=ems_unsubscribe">just click here.</a><span class="HOEnZb"><font color="#888888"> We sent you this email since your email-address: </font></span><?php echo $email_address; ?><span class="HOEnZb"><font color="#888888"> is registered in our customer database. Please do not reply to this email. If you would like to contact us, please send a note to <a href="mailto:service@mops.ca" target="_blank"><u>service@mops.ca</u></a>.</font></span></span></span></div>
						  </div>
                          <div class="footerSignupInfo">
                         </div>
                          
                        </div>
                      </td>
                    </tr>
                  </table>								
							
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </center>
  <p><?php include(DIR_FS_CATALOG . DIR_WS_MODULES . 'pages/templates/html_email_foot.php'); ?></p>
</body>
</html>