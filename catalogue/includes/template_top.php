<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  $oscTemplate->buildBlocks();

  if (!$oscTemplate->hasBlocks('boxes_column_left')) {
    $oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
  }

  if (!$oscTemplate->hasBlocks('boxes_column_right')) {
    $oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
  }
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset="<?php echo CHARSET; ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php include(DIR_WS_INCLUDES . 'easy_meta_tags.php'); ?>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script src="ext/jquery/jquery-1.11.1.min.js"></script>

<link rel="icon" type="image/x-icon" href="images/favicon.ico"/> 
<link href="ext/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="custom.css" rel="stylesheet">
<link href="user.css" rel="stylesheet">
<link href="user_color.css" rel="stylesheet">

<!--[if lt IE 9]>
   <script src="ext/js/html5shiv.js"></script>
   <script src="ext/js/respond.min.js"></script>
   <script src="ext/js/excanvas.min.js"></script>
<![endif]-->
 
<!-- font awesome -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<?php echo $oscTemplate->getBlocks('header_tags'); ?>
</head>
<!-- <body>  -->
<body id="<?php echo basename($_SERVER['PHP_SELF'], ".php")."-page"?>">

  <?php echo $oscTemplate->getContent('navigation'); ?>
  
  <div id="bodyWrapper" class="<?php echo BOOTSTRAP_CONTAINER; ?>">
    <div class="row">

      <?php require(DIR_WS_INCLUDES . 'header.php'); ?>

      <div id="bodyContent" class="col-md-<?php echo $oscTemplate->getGridContentWidth(); ?> <?php echo ($oscTemplate->hasBlocks('boxes_column_left') ? 'col-md-push-' . $oscTemplate->getGridColumnWidth() : ''); ?>">
