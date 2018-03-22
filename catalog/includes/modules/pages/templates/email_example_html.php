<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php include(DIR_FS_CATALOG . DIR_WS_MODULES . 'pages/templates/html_email_head.php'); ?>
</head>

<body style="background-color:#F2F2F2;">
<div style="max-width:600px; margin:0 auto;">
<h1>Dear <?php echo STORE_OWNER; ?>,</h1>
<p>put your HTML Demo Content here</p>
<p>You can use 2 external files here to uniform your letters. <br />
See: <strong>html_email_head.php</strong> and <strong>html_email_foot.php</strong> files<br />
in catalog/includes/modules/pages/templates directory.</p>
<p>Any html tags and php code enabled to display something.</p>
<p>Template Code Example: <pre>&lt;h2&gt;&lt;?php echo $email_variable; ?&gt;&lt;/h2&gt;</pre></p>
<p>Effect in mail: <h2><?php echo $email_variable; ?></h2></p>
<h3>Usage in core:</h3>
<p><textarea rows="6" cols="80"><?php echo EMAIL_EXAMPLE_CODE_HELP; ?></textarea></p>
<br />
<p><strong>Best Regards</strong>,<br />osCommerce Development Team</p>
</div>


</body>
</html>