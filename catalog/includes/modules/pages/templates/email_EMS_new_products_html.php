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
							<td><a href="http://www.aquariumsupplies.ca"><img src="http://www.aquariumsupplies.ca/images/MOPS_email_header.jpg" border="0" hspace="0" vspace="0" width="600" height="144" ></a></td> 
						  </tr>
                        </table>
                        
						<table id="content" width="100%">
						  <tr>
							<td width="100%" bgcolor="#ffffff" style="text-align:center;">
							    <a style="font-weight:bold; text-decoration:none;" href="#">
							       <div style="display:block; height:auto !important;background-color:#2489B3;padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px;border-radius:8px;color:#ffffff;font-size:24px;font-family:Arial, Helvetica, sans-serif;">New Products added recently!
								   </div>
								</a>
							</td>
						  </tr>
							
					<tr><td> 
							
	<!-- https://templates.mailchimp.com/development/responsive-email/responsive-column-layouts/   -->
<!-- add css here --> 
<style type="text/css">
    .gt {
		font-size: 100%;
	}
</style>	
							
<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateColumns">
    <tr height="500">
	    <!-- column 0 --> 
        <td align="center" valign="top" width="50%" class="templateColumnContainer">
		   <div style="border: 1px solid black;">
            <table cellpadding="" cellspacing="0" width="100%">
			
                <tr><!-- Image --> 
                    <td class="leftColumnContent">
						<?php echo '<a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][0]['products_id'] . '">
					    <img src="https://www.aquariumsupplies.ca/aquarium/images/thumbs/200x200_' . substr($_SESSION['ems'][0]['products_image'], strrpos($_SESSION['ems'][0]['products_image'], '/') + 1 ) . '"></a>'; ?>
                    </td>
                </tr>
                <tr valign="bottom"><!-- Name --> 
                    <td valign="top" class="leftColumnContent">
                        <h2 style="margin-bottom: 0;"><u><?php echo $_SESSION['ems'][0]['products_name'];?></u></h2>
					</td>
				</tr>
				<tr><!-- Short Description --> 
                    <td valign="top" class="leftColumnContent">
                        <?php echo $_SESSION['ems'][0]['short_desc'];?>
                    </td>
                </tr>
				<tr><!-- Price --> 
                    <td valign="top" class="leftColumnContent">
						<p style="font-size:24px; color:#96c93d; text-align:center">
						<?php echo $_SESSION['ems'][0]['products_price'];?>
						</p>
                    </td>
                </tr>
				<tr><!-- Buttons --> 
                    <td valign="top" class="leftColumnContent">
						<div style="border-radius: 4px; color:white !important; background-color: #96c93d; border-color: #96c93d; text-decoration: underline; float: none; margin-bottom: 15px; margin-right: 40px; margin-left: 40px; padding: 6px 12px;">
						<?php echo '<center><a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][0]['products_id'] . '">BUY NOW</a></center>';?>
						</div>
                    </td>
                </tr>
				
            </table>
		   </div>
		</td>
		
   <!-- column 1 --> 
        <td align="center" valign="top" width="50%" class="templateColumnContainer">
		   <div style="border: 1px solid black;">
            <table cellpadding="" cellspacing="0" width="100%">
			
                <tr><!-- Image --> 
                    <td class="rightColumnContent">
						<?php echo '<a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][1]['products_id'] . '">
					    <img src="https://www.aquariumsupplies.ca/aquarium/images/thumbs/200x200_' . substr($_SESSION['ems'][1]['products_image'], strrpos($_SESSION['ems'][1]['products_image'], '/') + 1 ) . '"></a>'; ?>
                    </td>
                </tr>
                <tr valign="bottom"><!-- Name --> 
                    <td valign="top" class="rightColumnContent">
                        <h2 style="margin-bottom: 0;"><u><?php echo $_SESSION['ems'][1]['products_name'];?></u></h2>
					</td>
				</tr>
				<tr><!-- Short Description --> 
                    <td valign="top" class="rightColumnContent">
                        <?php echo $_SESSION['ems'][1]['short_desc'];?>
                    </td>
                </tr>
				<tr><!-- Price --> 
                    <td valign="top" class="rightColumnContent">
						<p style="font-size:24px; color:#96c93d; text-align:center">
						<?php echo $_SESSION['ems'][1]['products_price'];?>
						</p>
                    </td>
                </tr>
				<tr><!-- Buttons --> 
                    <td valign="top" class="rightColumnContent">
						<div style="border-radius: 4px; color:white !important; background-color: #96c93d; border-color: #96c93d; text-decoration: underline; float: none; margin-bottom: 15px; margin-right: 40px; margin-left: 40px; padding: 6px 12px;">
						<?php echo '<center><a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][1]['products_id'] . '">BUY NOW</a></center>';?>
						</div>
                    </td>
                </tr>
				
            </table>
		   </div>
		</td>
		
		</tr>
		<tr>
		
		<!-- column 2 --> 
        <td align="center" valign="top" width="50%" class="templateColumnContainer">
		   <div style="border: 1px solid black;">
            <table cellpadding="" cellspacing="0" width="100%">
                <tr><!-- Image --> 
                    <td class="leftColumnContent">
						<?php echo '<a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][2]['products_id'] . '">
					    <img src="https://www.aquariumsupplies.ca/aquarium/images/thumbs/200x200_' . substr($_SESSION['ems'][2]['products_image'], strrpos($_SESSION['ems'][2]['products_image'], '/') + 1 ) . '"></a>'; ?>
                    </td>
                </tr>
                <tr valign="bottom"><!-- Name --> 
                    <td valign="top" class="leftColumnContent">
                        <h2 style="margin-bottom: 0;"><u><?php echo $_SESSION['ems'][2]['products_name'];?></u></h2>
						</td>
					</tr>
				<tr><!-- Short Description --> 
                    <td valign="top" class="leftColumnContent">
                        <?php echo $_SESSION['ems'][2]['short_desc'];?>
                    </td>
                </tr>
				<tr><!-- Price --> 
                    <td valign="top" class="leftColumnContent">
						<p style="font-size:24px; color:#96c93d; text-align:center">
						<?php echo $_SESSION['ems'][2]['products_price'];?>
						</p>
                    </td>
                </tr>
				<tr><!-- Buttons --> 
                    <td valign="top" class="leftColumnContent">
						<div style="border-radius: 4px; color:white !important; background-color: #96c93d; border-color: #96c93d; text-decoration: underline; float: none; margin-bottom: 15px; margin-right: 40px; margin-left: 40px; padding: 6px 12px;">
						<?php echo '<center><a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][2]['products_id'] . '">BUY NOW</a></center>';?>
						</div>
                    </td>
                </tr>
            </table>
		   </div>
		</td>
		
		   <!-- column 3 --> 
        <td align="center" valign="top" width="50%" class="templateColumnContainer">
		   <div style="border: 1px solid black;">
            <table cellpadding="" cellspacing="0" width="100%">
                <tr><!-- Image --> 
                    <td class="rightColumnContent">
						<?php echo '<a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][3]['products_id'] . '">
					    <img src="https://www.aquariumsupplies.ca/aquarium/images/thumbs/200x200_' . substr($_SESSION['ems'][3]['products_image'], strrpos($_SESSION['ems'][3]['products_image'], '/') + 1 ) . '"></a>'; ?>
                    </td>
                </tr>
                <tr valign="bottom"><!-- Name --> 
                    <td valign="top" class="rightColumnContent">
                        <h2 style="margin-bottom: 0;"><u><?php echo $_SESSION['ems'][3]['products_name'];?></u></h2>
						</td>
					</tr>
				<tr><!-- Short Description --> 
                    <td valign="top" class="rightColumnContent">
                        <?php echo $_SESSION['ems'][3]['short_desc'];?>
                    </td>
                </tr>
				<tr><!-- Price --> 
                    <td valign="top" class="rightColumnContent">
						<p style="font-size:24px; color:#96c93d; text-align:center">
						<?php echo $_SESSION['ems'][3]['products_price'];?>
						</p>
                    </td>
                </tr>
				<tr><!-- Buttons --> 
                    <td valign="top" class="rightColumnContent">
						<div style="border-radius: 4px; color:white !important; background-color: #96c93d; border-color: #96c93d; text-decoration: underline; float: none; margin-bottom: 15px; margin-right: 40px; margin-left: 40px; padding: 6px 12px;">
						<?php echo '<center><a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][3]['products_id'] . '">BUY NOW</a></center>';?>
						</div>
                    </td>
                </tr>
				
            </table>
		   </div>
		</td>
		
		</tr>
		<tr>
		
		   <!-- column 4 --> 
        <td align="center" valign="top" width="50%" class="templateColumnContainer">
		   <div style="border: 1px solid black;">
            <table cellpadding="" cellspacing="0" width="100%">
                <tr><!-- Image --> 
                    <td class="leftColumnContent">
						<?php echo '<a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][4]['products_id'] . '">
					    <img src="https://www.aquariumsupplies.ca/aquarium/images/thumbs/200x200_' . substr($_SESSION['ems'][4]['products_image'], strrpos($_SESSION['ems'][4]['products_image'], '/') + 1 ) . '"></a>'; ?>
                    </td>
                </tr>
                <tr valign="bottom"><!-- Name --> 
                    <td valign="top" class="leftColumnContent">
                        <h2 style="margin-bottom: 0;"><u><?php echo $_SESSION['ems'][4]['products_name'];?></u></h2>
						</td>
					</tr>
				<tr><!-- Short Description --> 
                    <td valign="top" class="leftColumnContent">
                        <?php echo $_SESSION['ems'][4]['short_desc'];?>
                    </td>
                </tr>
				<tr><!-- Price --> 
                    <td valign="top" class="leftColumnContent">
						<p style="font-size:24px; color:#96c93d; text-align:center">
						<?php echo $_SESSION['ems'][4]['products_price'];?>
						</p>
                    </td>
                </tr>
				<tr><!-- Buttons --> 
                    <td valign="top" class="leftColumnContent">
						<div style="border-radius: 4px; color:white !important; background-color: #96c93d; border-color: #96c93d; text-decoration: underline; float: none; margin-bottom: 15px; margin-right: 40px; margin-left: 40px; padding: 6px 12px;">
						<?php echo '<center><a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][4]['products_id'] . '">BUY NOW</a></center>';?>
						</div>
                    </td>
                </tr>
            </table>
		   </div>
		</td>
		
		   <!-- column 5 --> 
        <td align="center" valign="top" width="50%" class="templateColumnContainer">
		   <div style="border: 1px solid black;">
            <table cellpadding="" cellspacing="0" width="100%">
                <tr><!-- Image --> 
                    <td class="rightColumnContent">
						<?php echo '<a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][5]['products_id'] . '">
					    <img src="https://www.aquariumsupplies.ca/aquarium/images/thumbs/200x200_' . substr($_SESSION['ems'][5]['products_image'], strrpos($_SESSION['ems'][5]['products_image'], '/') + 1 ) . '"></a>'; ?>
                    </td>
                </tr>
                <tr valign="bottom"><!-- Name --> 
                    <td valign="top" class="rightColumnContent">
                        <h2 style="margin-bottom: 0;"><u><?php echo $_SESSION['ems'][5]['products_name'];?></u></h2>
						</td>
					</tr>
				<tr><!-- Short Description --> 
                    <td valign="top" class="rightColumnContent">
                        <?php echo $_SESSION['ems'][5]['short_desc'];?>
                    </td>
                </tr>
				<tr><!-- Price --> 
                    <td valign="top" class="rightColumnContent">
						<p style="font-size:24px; color:#96c93d; text-align:center">
						<?php echo $_SESSION['ems'][5]['products_price'];?>
						</p>
                    </td>
                </tr>
				<tr><!-- Buttons --> 
                    <td valign="top" class="rightColumnContent">
						<div style="border-radius: 4px; color:white !important; background-color: #96c93d; border-color: #96c93d; text-decoration: underline; float: none; margin-bottom: 15px; margin-right: 40px; margin-left: 40px; padding: 6px 12px;">
						<?php echo '<center><a href="https://www.aquariumsupplies.ca/aquarium/product_info.php?products_id=' . $_SESSION['ems'][5]['products_id'] . '">BUY NOW</a></center>';?>
						</div>
                    </td>
                </tr>
            </table>
		   </div>
		</td>
		
    </tr>
</table>
							
						</td></tr> 
														
							<tr><td><?php echo EMAIL_TEXT; ?></td></tr>
							<tr><td><?php echo EMAIL_CLOSE; ?></td></tr>
						                          
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
