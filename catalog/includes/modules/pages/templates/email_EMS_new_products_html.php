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
                      <td align="center" valign="top" class="mobilePadding" style=" padding-top:40px; padding-right:40px; padding-bottom:10px; padding-left:40px;">
                        <p style="color:red; text-align:center;">&lt;To ensure receipt of our emails, please add service@mops.ca to your Address Book.&gt;</p>
						<table id="header" width="100%">
                          <tr>
							<!-- <td><img src="<?php echo ((defined(ENABLE_SSL_CATALOG)) ? ( ENABLE_SSL_CATALOG == 'true' ? HTTPS_CATALOG_SERVER . DIR_WS_HTTPS_CATALOG : HTTP_CATALOG_SERVER . DIR_WS_CATALOG ) : (ENABLE_SSL == 'true' ? HTTPS_SERVER . DIR_WS_HTTPS_CATALOG : HTTP_SERVER . DIR_WS_HTTPS_CATALOG)) . DIR_WS_IMAGES . STORE_LOGO; ?>" title="<?php echo STORE_NAME; ?>" alt="<?php echo STORE_NAME; ?>"></td>   -->                        
							<td><a href="http://www.aquariumsupplies.ca"><img src="http://www.aquariumsupplies.ca/images/MOPS_email_header.jpg" border="0" hspace="0" vspace="0" width="600" height="144" ></a></td> 
						  </tr>
                        </table>
                        
						<!-- <h1 style="text-align:left; color:#606060 !important; font-size:26px;"><?php echo $welcome_text; ?></h1>  -->
                        
						<table id="content" width="100%">
						    <tr><td width="100%" bgcolor="#ffffff" style="text-align:center;"><a style="font-weight:bold; text-decoration:none;" href="#"><div style="display:block; max-width:100% !important; width:93% !important; height:auto !important;background-color:#2489B3;padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px;border-radius:8px;color:#ffffff;font-size:24px;font-family:Arial, Helvetica, sans-serif;">New Products added recently!</div></a></td></tr>
							
							<tr><td><style="padding-left:10px;"><?php echo EMAIL_WELCOME; ?></td></tr>
																				
							<tr><td> 
							
	<!-- https://templates.mailchimp.com/development/responsive-email/responsive-column-layouts/   -->
							
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateColumns">
    <tr>
	    <!-- column 1 --> 
        <td align="center" valign="top" width="50%" class="templateColumnContainer">
            <table border="0" cellpadding="10" cellspacing="0" width="100%">
                <tr>
                    <td class="leftColumnContent">
						<?php echo '<a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][0]['products_id'] . '">
					    <img src="https://www.aquariumsupplies.ca/aquarium/images/thumbs/200x200_' . substr($_SESSION['ems'][0]['products_image'], strrpos($_SESSION['ems'][0]['products_image'], '/') + 1 ) . '"></a>'; ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="leftColumnContent">
                        <h2><?php echo $_SESSION['ems'][0]['products_name'];?></h2>
                        Lorem ipsum dolor sit amet.
                    </td>
                </tr>
            </table>
        </td>
		
		<!-- column 2 --> 
        <td align="center" valign="top" width="50%" class="templateColumnContainer">
            <table border="0" cellpadding="10" cellspacing="0" width="100%">
                <tr>
                    <td class="rightColumnContent">
 						<?php echo '<a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][1]['products_id'] . '">
					    <img src="https://www.aquariumsupplies.ca/aquarium/images/thumbs/200x200_' . substr($_SESSION['ems'][1]['products_image'], strrpos($_SESSION['ems'][1]['products_image'], '/') + 1 ) . '"></a>'; ?>
                   </td>
                </tr>
                <tr>
                    <td valign="top" class="rightColumnContent">
                        <h2><?php echo $_SESSION['ems'][1]['products_name'];?></h2>
                        Lorem ipsum dolor sit amet.
                    </td>
                </tr>
            </table>
        </td>
		
    </tr>
</table>
							
						</td></tr> 
														
							<tr><td><?php echo EMAIL_TEXT; ?></td></tr>
							<tr><td><?php echo EMAIL_CLOSE; ?></style></td></tr>
						                          
							<tr><td><p>Yours Very Truly,</p>
							<div style="text-align: left;"><img border="0" height="55" src="http://www.aquariumsupplies.ca/aquarium/newsletter/images/Dan_Cole_Signature.png" width="200" /></div>
							Dan Cole<br />
							<a href="http://www.aquariumsupplies.ca/aquarium">Mail Order Pet Supplies, Inc.</a>

							<p>PS: If I can help you select a product, answer a question or otherwise be of service please call me at 888-648-6677 or <a href="mailto:dancole@mops.ca">drop me a note at dancole@mops.ca</a>.</p>
							</td></tr>
							
					    </table>
                    
					  </td>
                    </tr>
                  </table>

                  <table id="footer" width="100%">
                    
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
