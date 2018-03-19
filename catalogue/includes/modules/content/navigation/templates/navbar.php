<nav class="navbar navbar-default navbar-no-corners navbar-no-margin navbar-transparent navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse-core-nav">
        <span class="sr-only"><?php echo HEADER_TOGGLE_NAV; ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="bs-navbar-collapse-core-nav">
        <ul class="nav navbar-nav">
		  <!-- <a class="mops-navbar text-uppercase" href="<?php echo tep_href_link('contact_us.php'); ?>"> -->
		  <?php echo '<li><a class="mops-navbar">' . sprintf(CONTACT_US, STORE_PHONE); ?></a></li>
		  <?php echo '<li><a class="mops-navbar" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . HEADER_HOME . '</a></li>'; ?>
          <?php echo '<li class="hidden-sm hidden-md"><a class="mops-navbar" href="' . tep_href_link(FILENAME_PRODUCTS_NEW) . '">' . HEADER_WHATS_NEW . '</a></li>'; ?>
          <?php echo '<li class="hidden-sm hidden-md"><a class="mops-navbar" href="' . tep_href_link(FILENAME_SPECIALS) . '">' . HEADER_SPECIALS . '</a></li>'; ?>
        <!--  <?php echo '<li><a class="mops-navbar" href="' . tep_href_link(FILENAME_REVIEWS) . '">' . HEADER_REVIEWS . '</a></li>'; ?> -->
        </ul>

		<ul class="nav navbar-nav navbar-right">
		
          <li class="dropdown">
            <a class="dropdown-toggle mops-navbar text-uppercase" data-toggle="dropdown" href="#"><?php echo (tep_session_is_registered('customer_id')) ? sprintf(HEADER_ACCOUNT_LOGGED_IN, $customer_first_name) : HEADER_ACCOUNT_LOGGED_OUT; ?></a>
            <ul class="dropdown-menu">
              <?php
              if (tep_session_is_registered('customer_id')) {
                echo '<li><a class="mops-navbar" href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '">' . HEADER_ACCOUNT_LOGOFF . '</a>';
              }
              else {
                 echo '<li><a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . HEADER_ACCOUNT_LOGIN . '</a>';
                 echo '<li><a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . HEADER_ACCOUNT_REGISTER . '</a>';
              }
              ?>
              <li class="divider"></li>
              <li><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . HEADER_ACCOUNT . '</a>'; ?></li>
              <li><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . HEADER_ACCOUNT_HISTORY . '</a>'; ?></li>
              <li><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . HEADER_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></li>
              <li><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . HEADER_ACCOUNT_PASSWORD . '</a>'; ?></li>
			  
			  <!-- Wishlist //-->
			  <li><?php echo '<a href="' . tep_href_link('wishlist.php', '', 'SSL') . '">' . HEADER_ACCOUNT_WISHLIST . '</a>'; ?></li>
			  <!-- EOF Wishlist //-->
			  
            </ul>
          </li>

<?php
        if ($cart->count_contents() > 0) {
?>

          <li class="dropdown">
            <a class="dropdown-toggle mops-navbar" data-toggle="dropdown" href="#">
              <?php echo sprintf(HEADER_CART_CONTENTS, $cart->count_contents()); ?>
           </a>

            <ul class="dropdown-menu mini-cart-width">
              <li>

                <div class="custom_scrollbar">
                  <table>
<?php
                    $products = $cart->get_products();
                    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
                      echo '<tr>';

                        echo '  <td>';
                          echo ($products[$i]['quantity']) . 'x' ;
                        echo '  </td>';

                        echo '  <td>';
                          echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO) . '?products_id=' . ($products[$i]['id']) .  '">' . ($products[$i]['name']) . '</a>';
                        echo '  </td>';

                        echo '  <td align="right">';
                          echo $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']);
                        echo '  </td>';

                      echo '</tr>';
                    }
?>

                  </table>
                </div>
              </li>

              <li class="divider"></li>

<?php

              echo '<li>
                      <span class="col-xs-6">
                        <a class="mops-navbar" href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">
                          <i class="glyphicon glyphicon-shopping-cart"></i> ' . HEADER_CART_VIEW_CART . '</a>
                      </span>
                      <span class="col-xs-6 text-right">
                        <a class="mops-navbar" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">'
                          . HEADER_CART_CHECKOUT . '
                        </a>
                      </span>
                    </li>';

?>
            </ul>
          </li>
<?php
        } else {
          echo '<li class="nav mops-navbar navbar-text">' . HEADER_CART_NO_CONTENTS . '</li>';
        }
?>		  
		  
		  
        </ul>
    </div>
  </div>
</nav>