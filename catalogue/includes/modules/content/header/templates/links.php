<div class="clearfix"></div>
<nav class="navbar navbar-default navbar-no-corners navbar-roboto" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse-2">
          <span class="sr-only">Toggle Navigation</span>
          <span class="glyphicon glyphicon-chevron-down"></span>
          <span class="text-menu text-center"><?php echo OPEN_MAIN_MENU; ?></span>
          <span class="glyphicon glyphicon-chevron-down"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="bs-navbar-collapse-2">
      <div class="container-fluid">
         <ul class="nav navbar-nav">
       
           <li class="header-link">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">Aquarium <span class="fa fa-angle-double-down"></span></a>
              <?php echo build_hoz('dropdown-menu', 1);?>
           </li>
         
		   <li class="header-link">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">Pond <span class="fa fa-angle-double-down"></span></a>
              <?php echo build_hoz('dropdown-menu', 173);?>
           </li>
		 
		   <li class="header-link">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">Reptile <span class="fa fa-angle-double-down"></span></a>
              <?php echo build_hoz('dropdown-menu', 125);?>
           </li>
			
          <?php
		  echo '<li class="header-link hidden-sm hidden-md"><a href="" data-toggle="modal" data-target="#myemailsubModal">' . HEADER_MAILING_LIST . '</a></li>';
		  echo '<li class="header-link hidden-sm hidden-md"><a href="' . tep_href_link('contact_us.php') . '">' . HEADER_CONTACT_US . '</a></li>';
		 ?>	
		
		  <li class="dropdown header-link">
            <a class="dropdown-toggle text-uppercase" data-toggle="dropdown" href="#"><?php echo "OUR OTHER SITES "; ?><span class="fa fa-angle-double-down"></span></a>
            <ul class="dropdown-menu">
              <?php
			    // echo '<li class="header-link"><a href="https://www.aquariumsupplies.ca/aquarium/index.php "title="For the Tropical Fish Hobbyist!">AquariumSupplies.ca</a></li>';
			    echo '<li class="header-link"><a href="https://www.aquariumsupplies.ca/direct2you/index.php "title="Plan ahead and save!">Direct2You</a></li>';
				echo '<li class="header-link"><a href="https://www.aquariumsupplies.ca/pond/index.php "title="For the Pond Enthusiast!">PondShop.ca</a></li>';
				echo '<li class="header-link"><a href="https://www.aquariumsupplies.ca/reptile/index.php "title="For the Reptile Hobbyist!">ReptileShop.ca</a></li>';
				echo '<li class="header-link"><a href="https://www.facebook.com/mops.ca "title="Like us on FaceBook!">Like us on FaceBook</a></li>';
              ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

<!-- BOF Subscribers Email List Modal -->  
 <div class="modal fade" id="myemailsubModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo HEADER_EMAIL_TITLE; ?></h4>
      </div>
   
    <div class="modal-body">
	<form method="POST" action="https://www.cctomany.com/su.php">
 		<label id="email_padding_bottom"><?php echo NOTIFY_EMAIL ;?></label>
		<?php echo tep_draw_input_field('Email','','id="Email" required aria-required="true" placeholder="' . ENTRY_EMAIL . '"');?>
		<p id="email_padding_top"><input type="radio" name="Action" value="join-mops-info" checked>Subscribe&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="Action" value="leave-mops-info">Unsubscribe</p>	
	    <input type="hidden" name="ThanksURL" value="<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG;?>newsletter_subscriptions_thank_you.php">
    
	</div> <!-- EOF modal-body //-->
   
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i><?php echo IMAGE_BUTTON_CLOSE; ?></button>
		<?php echo tep_draw_button(IMAGE_BUTTON_SEND, 'glyphicon glyphicon-send', null, 'primary', null, 'btn-primary'); ?>
    </div> <!-- EOF modal-footer //-->
	</form>
    </div> <!-- EOF modal-content //-->
  </div> <!-- EOF modal-dialog //-->
</div> <!-- EOF modal fade //-->
<!-- BOF Subscribers Email List Modal -->  
 
 </nav>

<script> 
$(document).ready(function() {

  if(window.location.href.indexOf('#myemailsubModal') != -1) {
    $('#myemailsubModal').modal('show');
  }

});
</script> 
