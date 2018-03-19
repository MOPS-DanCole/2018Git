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
                      <td align="center" valign="top" class="mobilePadding" style=" padding-top:40px; padding-right:40px; padding-bottom:30px; padding-left:40px;">
                        
						<!-- change the header  -->
						<table border="0" cellpadding="0" cellspacing="0" align="center" width="600">
							<tr><td align="center">To ensure receipt of our emails, please add service@mops.ca to your Address Book.<br><br></td></tr>
							<tr valign="top"><td><a href="http://www.aquariumsupplies.ca"><img src="http://www.aquariumsupplies.ca/images/MOPS_email_header.jpg" border="0" hspace="0" vspace="0" width="600" height="144" ></a></td></tr>
						</table>

                        <table id="content" width="94%">
						
							<tr><td><p>
						<?php
							if (isset($welcome_text)) {
								echo $welcome_text; 
							} else {
								echo EMAIL_SALUTATION;
							}	
						?>	
							</p></td></tr>
								
							<tr><td><?php echo EMAIL_TEXT; ?></td></tr>
							<tr><td><?php echo EMAIL_CLOSE; ?></td></tr>
						    <tr><td><?php echo EMAIL_SIGNATURE; ?></td></tr>                      
						  						  
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
