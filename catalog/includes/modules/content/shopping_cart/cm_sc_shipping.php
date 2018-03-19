<?php
/*
  $Id: cm_sc_shipping.php
  $Loc: catalog/includes/modules/content/shopping_cart/
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  2016 2 Page Checkout Shipping Module 1.2 BS 
  by @raiwa 
  info@sarplataygemas.com
  www.oscaddons.com

  Original version: Edwin Bekaert (edwin@ednique.com)

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/


  class cm_sc_shipping {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SC_SHIPPING_TITLE;
      $this->description = MODULE_CONTENT_SC_SHIPPING_DESCRIPTION;
      $this->description .= '<div class="secWarning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';

      if ( defined('MODULE_CONTENT_SC_SHIPPING_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SC_SHIPPING_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SC_SHIPPING_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $currencies, $currency, $request_type, $cart, $order, $total_count, $quotes, $method, $module, $shipping, $navigation, $cart_sid, $cart_country_id, $cart_zone, $cart_zip_code, $cart_address_id, $total_weight, $language, $customer_id, $sendto, $billto, $cartID, $customer_default_address_id;

      $content_width = (int)MODULE_CONTENT_SC_SHIPPING_CONTENT_WIDTH;

      if (($cart->count_contents() > 0)) {
      	?>
      	<style>
      	/*shipping estimator */
      	.estimator-padding {
      		padding-top:10px;
      		padding-bottom:10px;
      	}
      	</style>
      	<script>
      		function shipincart_submit(sid){
      			if(sid){
      				document.estimator.sid.value=sid;
      			}
      			document.estimator.submit();
      			return false;
      		}
      	</script>
      
      	<?php
      	$selected_shipping = null;
      	// shipping cost
      	require('includes/classes/http_client.php'); // shipping in basket
      
      	// include the order class (uses the sendto !)
      	if (!class_exists('order')) {
      		require('includes/classes/order.php');
      	}
      	$order = new order;
      	
		    // 2 Page Checkout Begin (set billing address to cart address)
		    // register a random ID in the session to check throughout the checkout procedure
		    // against alterations in the shopping cart contents
		    if (!tep_session_is_registered('cartID')) {
		    	tep_session_register('cartID');
		    }

		    $cartID = $cart->cartID = $cart->generate_cart_id();
		    // 2 Page Checkout End

      	if (tep_session_is_registered('customer_id')) {
      		// user is logged in
      		if (isset($_POST['address_id'])){
      			// user changed address
      			$order->delivery = array();
      			$sendto = $_POST['address_id'];
      			$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from address_book where customers_id = '" . $customer_id . "' and address_book_id = '" . $sendto . "'");
      			$addresses_array_changed = tep_db_fetch_array($addresses_query);
      			$country_info = tep_get_countries($addresses_array_changed['country_id'],true);
      			$order->delivery = array('name' => $addresses_array_changed['firstname'] . ' ' . $addresses_array_changed['lastname'], 
	        	    					        'company' => $addresses_array_changed['entry_company'],
	        	    					 'street_address' => $addresses_array_changed['street_address'],
	            								   'postcode' => $addresses_array_changed['postcode'],
	            						  		   'suburb' => $addresses_array_changed['suburb'],
		          										   'city' => $addresses_array_changed['city'],
		          										'country' => array('id' => $addresses_array_changed['country_id'], 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
		          								 'country_id' => $addresses_array_changed['country_id'],
  		                              'state' => $addresses_array_changed['state'],
		                              'zone_id' => $addresses_array_changed['zone_id'],
		          								 	'format_id' => tep_get_address_format_id($addresses_array_changed['country_id']));

		        
		      } elseif ( !tep_session_is_registered('sendto') ) {
		      	if ( tep_session_is_registered('cart_address_id') ) {
		      	// user once changed address
		      	$sendto = (isset($_POST['cart_address_id'])? $_POST['cart_address_id'] : null);
		      	} else {
		      	// first timer
		      	$sendto = (isset($_POST['customer_default_address_id'])? $_POST['customer_default_address_id'] : null);
		      	}
		      }
		      // set session now
		      $cart_address_id = $sendto;
		      tep_session_register('sendto');
		                            
		      // set $billto for virtual products to get correct tax
		      // 2 Page Checkout Begin (set billing address to cart address)
		      if ( (!tep_session_is_registered('billto') || (is_array($billto) && $billto['firstname'] == '' )) && $cart->get_content_type() == 'virtual' ) {
		      	tep_session_register('billto');
		      	if ( tep_not_null($cart_address_id) ) {
		      		$billto = $cart_address_id;
		      	} else {
		      		$billto = $customer_default_address_id;
		      	}
		    	}
		    	// 2 Page Checkout End

		      tep_session_register('cart_address_id');
		      // set shipping to null ! multipickup changes address to store address...
		      $shipping='';
		    } else {
		    	// user not logged in !
		    	if ( isset($_POST['country_id']) ) {
		    		// country is selected
		    		$country_info = tep_get_countries($_POST['country_id'],true);
		    		$cache_state_prov_values = tep_db_fetch_array(tep_db_query("select zone_code from zones where zone_country_id = '" . $_POST['country_id'] . "' and zone_id = '" . $_POST['zone_id'] . "'"));
		    		$cache_state_prov_code = $cache_state_prov_values['zone_code'];
		    		$order->delivery = array();
		    		$order->delivery = array('postcode' => (isset($_POST['zip_code'])? $_POST['zip_code'] : null),
			                                  'state' => $cache_state_prov_code,
			                                'country' => array('id' => $_POST['country_id'], 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
			                             'country_id' => $_POST['country_id'],
			      //add state zone_id
		                                  'zone_id' => $_POST['zone_id'],
		                                'format_id' => tep_get_address_format_id($_POST['country_id']));
		        $cart_country_id = $_POST['country_id'];
		        tep_session_register('cart_country_id');
		        //add state zone_id
		        $cart_zone = isset($_POST['zone_id'])? $_POST['zone_id'] : null;
		        tep_session_register('cart_zone');
		        $cart_zip_code = (isset($_POST['zip_code'])? $_POST['zip_code'] : null);
		        tep_session_register('cart_zip_code');
		      } elseif ( tep_session_is_registered('cart_country_id') ) {
		      	// session is available
		      	$country_info = tep_get_countries($cart_country_id,true);
		      	$order->delivery = array();
		      	$order->delivery = array('postcode' => $cart_zip_code,
		                                  'country' => array('id' => $cart_country_id, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
		                               'country_id' => $cart_country_id,
		                                'format_id' => tep_get_address_format_id($cart_country_id),
		                                  'zone_id' => $cart_zone);
		      } else {
		      	// first timer
		      	$cart_country_id = STORE_COUNTRY;
		        $cart_zone = STORE_ZONE;
		        $cart_zip_code = MODULE_CONTENT_SC_SHIPPING_DEFAULT_ZIP;
		      	tep_session_register('cart_country_id');
		      	tep_session_register('cart_zone');
		      	tep_session_register('cart_zip_code');
		      	$country_info = tep_get_countries(STORE_COUNTRY,true);
		      	$order->delivery = array();
		      	$order->delivery = array('postcode' => MODULE_CONTENT_SC_SHIPPING_DEFAULT_ZIP,
		                                  'country' => array('id' => STORE_COUNTRY, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                   'country_id' => STORE_COUNTRY,
		                                'format_id' => tep_get_address_format_id($cart_country_id),
		                                  'zone_id' => STORE_ZONE);
		      }
		      // set the cost to be able to calculate free shipping
		      $order->info = array('total' => $cart->show_total(), // TAX ????
                            'currency' => $currency,
                       'currency_value'=> $currencies->currencies[$currency]['value']);
        }

        // weight and count needed for shipping
        $total_weight = $cart->show_weight();
        $total_count = $cart->count_contents();
        require('includes/classes/shipping.php');
        $shipping_modules = new shipping;
        $quotes = $shipping_modules->quote();
        $order->info['subtotal'] = $cart->total;

        // set selections for displaying
        $selected_country = $order->delivery['country']['id'];
        $selected_address = $sendto;
        
        // eo shipping cost
        // check free shipping based on order total
        if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
        	switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
        	case 'national':
        		if ($order->delivery['country_id'] == STORE_COUNTRY)
        			$pass = true;
        		break;
        	case 'international':
        		if ($order->delivery['country_id'] != STORE_COUNTRY)
        			$pass = true;
        		break;
        	case 'both':
        		$pass = true;
        		break;
        	default:
        		$pass = false;
        		break;
        	}
        	$free_shipping = false;
        	if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
        		$free_shipping = true;
        		include('includes/languages/' . $language . '/modules/order_total/ot_shipping.php');
        	}
        } else {
        	$free_shipping = false;
        }
        // begin shipping cost
        if( !$free_shipping && $cart->get_content_type() !== 'virtual' ) {
        	if (isset($_POST['sid']) && tep_not_null($_POST['sid'])){
        		list($module, $method) = explode('_', $_POST['sid']);
        		$cart_sid = $_POST['sid'];
        		tep_session_register('cart_sid');
        	} elseif ( tep_session_is_registered('cart_sid') ) {
        		list($module, $method) = explode('_', $cart_sid);
        	} else {
        		$module = null;
        		$method = null;
        	}
        	
        	if ( tep_not_null($module) && sizeof($quotes) > 1 && tep_not_null($shipping_modules->quote($method, $module)) ) {
        		$selected_quote = $shipping_modules->quote($method, $module);
        		if( isset($selected_quote[0]['error'])) {
        			$selected_shipping = $shipping_modules->cheapest();
        			$order->info['shipping_method'] = $selected_shipping['title'];
        			$order->info['shipping_cost'] = $selected_shipping['cost'];
        			$order->info['total']+= $selected_shipping['cost'];
        		} else {
        			$order->info['shipping_method'] = $selected_quote[0]['module'] . (tep_not_null($selected_quote[0]['methods'][0]['title'])? ' (' . $selected_quote[0]['methods'][0]['title'] . ')': '' );
        			$order->info['shipping_cost'] = $selected_quote[0]['methods'][0]['cost'];
        			$order->info['total']+= $selected_quote[0]['methods'][0]['cost'];
        			$selected_shipping['title'] = $order->info['shipping_method'];
        			$selected_shipping['cost'] = $order->info['shipping_cost'];
        			$selected_shipping['id'] = $selected_quote[0]['id'].'_'.$selected_quote[0]['methods'][0]['id'];
        		}
        	} else {
        		$selected_shipping = $shipping_modules->cheapest();
        		$order->info['shipping_method'] = $selected_shipping['title'];
        		$order->info['shipping_cost'] = $selected_shipping['cost'];
        		$order->info['total']+= $selected_shipping['cost'];
        	}
        }
        // virtual products use free shipping
        if ( $cart->get_content_type() == 'virtual' ) {
        	$order->info['shipping_method'] = MODULE_CONTENT_SC_SHIPPING_SHIPPING_METHOD_FREE_TEXT . ' ' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_METHOD_ALL_DOWNLOADS;
        	$order->info['shipping_cost'] = 0;
        }
        if ( $free_shipping ) {
        	$order->info['shipping_method'] = MODULE_ORDER_TOTAL_SHIPPING_TITLE;
        	$order->info['shipping_cost'] = 0;
        }
        $shipping = $selected_shipping;
        if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
        // end of shipping cost
        // end free shipping based on order total
   
        $sc_order_shipping = '<div class="panel panel-default">';
        $sc_order_shipping .= '	<div class="panel-heading">';
        $sc_order_shipping .= '		<h3 class="panel-title">' . MODULE_CONTENT_SC_SHIPPING_MODULE_TITLE . '</h3>'; // BS panel heading
        $sc_order_shipping .= '	</div>';
        $sc_order_shipping .= '	<div class="panel-body">';
  
        $sc_order_shipping .= tep_draw_form('estimator', tep_href_link('shopping_cart.php', '', 'NONSSL'), 'post'); //'onSubmit="return check_form();"'
        $sc_order_shipping .= tep_draw_hidden_field('sid', $selected_shipping['id']);
        $sc_order_shipping .= '	<div class="row">';

        if ( tep_session_is_registered('customer_id') ) {
        	// logged in
        	if ( MODULE_CONTENT_SC_SHIPPING_SHOWWT == 'True' ) {
        		$showweight = '&nbsp;(' . $total_weight . '&nbsp;' . MODULE_CONTENT_SC_SHIPPING_WTUNIT . ')';
        	}

        	if ( MODULE_CONTENT_SC_SHIPPING_SHOWIC == 'True' ) {
        		$sc_order_shipping .= '<div class="col-sm-12">' . ($total_count == 1 ? ' <strong>' . MODULE_CONTENT_SC_SHIPPING_ITEM . '</strong>' : ' <strong>' . MODULE_CONTENT_SC_SHIPPING_ITEM . '</strong>') . '&nbsp;' . $total_count . ((isset($showweight))? $showweight : '')  . '</div>';
        	}
        		
        	$addresses_query = tep_db_query("select distinct address_book_id, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from address_book where customers_id = '" . $customer_id . "'");
        	// only display addresses if more than 1
        	if ( tep_db_num_rows($addresses_query) > 1 ) {
        		while ( $addresses = tep_db_fetch_array($addresses_query) ) {
        			$addresses_array[] = array('id' => $addresses['address_book_id'], 'text' => tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, 0, ' ', ' '));
        		}

        		$sc_order_shipping .= '<div class="col-sm-2 estimator-padding">' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_METHOD_ADDRESS . '</div><div class="col-sm-10 estimator-padding">'. tep_draw_pull_down_menu('address_id', $addresses_array, $selected_address, 'onchange="return shipincart_submit(\'\');"') . '</div>';
        	}
        	$sc_order_shipping .= '<div class="clearfix"></div>';
        	$sc_order_shipping .= '<div class="col-sm-3 estimator-padding"><strong>' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_METHOD_TO .'</strong></div><div class="col-sm-9 estimator-padding">'. tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>') . '</div>';

        } else {
        	// not logged in
        	$navigation->set_snapshot();
			$sc_order_shipping .= '<div class="col-sm-12">' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_OPTIONS_LOGIN .  '</div>';  // added to remove button
        	//$sc_order_shipping .= '<div class="col-sm-12">' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_OPTIONS_LOGIN . tep_draw_button(MODULE_CONTENT_SC_SHIPPING_BUTTON_LOGIN, 'glyphicon glyphicon-log-in', tep_href_link('login.php'), 'btn-success btn-sm') . '</div>';

        	if( MODULE_CONTENT_SC_SHIPPING_SHOWIC == 'True' ) {
        		$sc_order_shipping .= '<div class="col-sm-12 estimator-padding">' . ($total_count == 1 ? ' <strong>' . MODULE_CONTENT_SC_SHIPPING_ITEM . '</strong>' : ' <strong>' . MODULE_CONTENT_SC_SHIPPING_ITEM . '</strong>') . '&nbsp;' . $total_count . ((isset($showweight))? $showweight : '')  . '</div>';
        	}

        	if ( $cart->get_content_type() != 'virtual' ) {

        	  if ( MODULE_CONTENT_SC_SHIPPING_SHOWCDD == 'True' ) {
        	    $sc_order_shipping.= '<div class="form-group has-feedback">';
        	    if ( MODULE_CONTENT_SC_SHIPPING_SHOWSDD == 'True' || MODULE_CONTENT_SC_SHIPPING_SHOWZDD == 'True' ) {
        	      $sc_order_shipping.= '<label for="inputCountry" class="control-label col-sm-4">' .  ENTRY_COUNTRY . '</label>';
        	    } else {
        	      $sc_order_shipping.= '<label for="inputCountry" class="control-label col-sm-4">' .  ENTRY_COUNTRY . '</label>';
        	    }
        	    $sc_order_shipping.= '<div class="col-sm-8">' . tep_get_country_list('country_id', $selected_country, 'id="inputCountry"') . '</div>';
        	    $sc_order_shipping.= '</div>';
        		} else {
        			$sc_order_shipping.= tep_draw_hidden_field('country_id', STORE_COUNTRY);
        		}
        
        		//add state zone_id
        		$state_array[] = array('id' => '', 'text' => 'Please Select');
        		$state_query = tep_db_query("select zone_name, zone_id from zones where zone_country_id = '$selected_country' order by zone_country_id DESC, zone_name");
        		while ( $state_values = tep_db_fetch_array($state_query) ) {
        			$state_array[] = array('id' => $state_values['zone_id'],
	                                 'text' => $state_values['zone_name']);
	          }

	          if ( MODULE_CONTENT_SC_SHIPPING_SHOWSDD == 'True' && count($state_array) > 1) {
	            $sc_order_shipping.= '<div class="form-group has-feedback">';
	            $sc_order_shipping.= '<label for="inputState" class="control-label col-sm-4">' .  ENTRY_STATE . '</label>';
	            $sc_order_shipping.= '<div class="col-sm-8">' . tep_draw_pull_down_menu('zone_id', $state_array, (isset($_POST['zone_id'])? $_POST['zone_id'] : STORE_ZONE), 'id="inputState"') . '</div>';
	            $sc_order_shipping.= '</div>';
        		} else {
        			$sc_order_shipping.= tep_draw_hidden_field('zone_id', STORE_ZONE);
        		}

	          if ( MODULE_CONTENT_SC_SHIPPING_SHOWZDD == 'True' ) {
	            $sc_order_shipping.= '<div class="form-group has-feedback">';
	            $sc_order_shipping.= '<label for="inputZip" class="control-label col-sm-4">' .  ENTRY_POST_CODE . '</label>';
	            $sc_order_shipping.= '<div class="col-sm-4">' . tep_draw_input_field('zip_code', (isset($_POST['zip_code'])? $_POST['zip_code'] : MODULE_CONTENT_SC_SHIPPING_DEFAULT_ZIP), 'id="inputZip"', 'text', true, 'class="form-control" style="width: 125px;"') . '</div>';
	            $sc_order_shipping.= '</div>';
	            if ( MODULE_CONTENT_SC_SHIPPING_SHOWUB == 'True' ) {
	            	$sc_order_shipping.='<div class="col-sm-4 text-right"><a class="btn btn-default" role="button" href="_" onclick="return shipincart_submit(\'\');"><i class="glyphicon glyphicon-refresh"></i>&nbsp;'. IMAGE_BUTTON_UPDATE . ' </a></div>';
	            }
	          } else {
	            if ( MODULE_CONTENT_SC_SHIPPING_SHOWUB == 'True' ) {
	            	$sc_order_shipping.='<div class="col-sm-12 text-right"><a class="btn btn-default" role="button" href="_" onclick="return shipincart_submit(\'\');"><i class="glyphicon glyphicon-refresh"></i>&nbsp;'. IMAGE_BUTTON_UPDATE . ' </a></div>';
	            }
	          }
        	}
        }
    
        $sc_order_shipping .= '			</div><br>';
        $sc_order_shipping .= '			<table class="table table-condensed table-hover"';
        $sc_order_shipping .= '				<thead>';
          
        if ( $cart->get_content_type() == 'virtual' ) {
        	// virtual product-download
        	$sc_order_shipping .= '<tr><th><i>' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_METHOD_FREE_TEXT . ' ' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_METHOD_ALL_DOWNLOADS . '</i></th></tr>';
        } elseif ( $free_shipping==1 ) {
        	// order $total is free
        	$sc_order_shipping.='<tr><th><i>' . sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . '</i></th></tr>';
        } else {
        	// shipping display
        	if ( empty($quotes[0]['error']) || (!empty($quotes[1])&&empty($quotes[1]['error'])) ) {
        		$sc_order_shipping .= '<tr><th><strong>' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_METHOD_TEXT . '</strong></th><th class="text-right"><strong>' . MODULE_CONTENT_SC_SHIPPING_SHIPPING_METHOD_RATES . '</strong></th></tr>';
        
        		// added to Display Message when No Shipping Options are Available
        		$at_least_one_quote_printed = false;
        	} else {
        		$sc_order_shipping .= '<tr><th>&nbsp;</th></tr>';
        	}
      
        	$sc_order_shipping .= '				</thead>';
        	$sc_order_shipping .= '				<tbody>';
        		
        	if ( sizeof($quotes) ) {
        		for ( $i=0, $n=sizeof($quotes); $i<$n; $i++ ) {
        			if ( sizeof($quotes[$i]['methods'])==1 ) {
        				// simple shipping method
        				$thisquoteid = $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][0]['id'];
        				$sc_order_shipping .= '';
          
        				if ( !empty($quotes[$i]['error']) ) {
        					$sc_order_shipping .= '<tr><td>' . $quotes[$i]['module'] . '&nbsp;(' . $quotes[$i]['error'] . ')</td></tr>';
        				} else {
        					if ( $selected_shipping['id'] == $thisquoteid) {
        						$sc_order_shipping.='<tr class="success"><td onclick="return shipincart_submit(\''.$thisquoteid.'\');">' . tep_draw_radio_field('', true, true) . '<a href="_" title="' . MODULE_CONTENT_SC_SHIPPING_SELECT_THIS_METHOD .'">&nbsp;<strong>' . $quotes[$i]['module'] . '&nbsp;';
        						$sc_order_shipping.= (tep_not_null($quotes[$i]['methods'][0]['title'])? '(' . $quotes[$i]['methods'][0]['title'] . ')': '' ).'</strong>&nbsp;&nbsp;&nbsp;</a></td><td class="text-right"><strong>' . $currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], ((!empty($quotes[$i]['tax']))? $quotes[$i]['tax'] : null ))) . '</strong></td></tr>';
        					} else {
        						$sc_order_shipping .= '<tr><td onclick="return shipincart_submit(\''.$thisquoteid.'\');">' . tep_draw_radio_field('', false, false) . '<a href="_" title="' . MODULE_CONTENT_SC_SHIPPING_SELECT_THIS_METHOD . '">&nbsp;' . $quotes[$i]['module'] . '&nbsp;';
        						$sc_order_shipping .= (tep_not_null($quotes[$i]['methods'][0]['title'])? '(' . $quotes[$i]['methods'][0]['title'] . ')': '' ) . '&nbsp;&nbsp;&nbsp;</a></td><td class="text-right">' . $currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], ((!empty($quotes[$i]['tax']))? $quotes[$i]['tax'] : null ))) . '</td></tr>';
        					}
        				}
        				// added to Display Message when No Shipping Options are Available
        				$at_least_one_quote_printed = true;
		          	} elseif ( sizeof($quotes[$i]['methods'])>1 ) {
		          		// shipping method with sub methods (multipickup)
		          		for ( $j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++ ) {
		          			$thisquoteid = $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'];
		          			$sc_order_shipping .= '';
		          			if ( isset($quotes[$i]['error']) && $quotes[$i]['error'] == true ) {
		          				$sc_order_shipping .= '<tr><td>' . $quotes[$i]['module'] . '&nbsp;(' . $quotes[$i]['error'] . ')</td></tr>';
		          			} else {
		          				if ( $selected_shipping['id'] == $thisquoteid ) {
		          					$sc_order_shipping .= '<tr class="success"><td onclick="return shipincart_submit(\''.$thisquoteid.'\');"><a href="_" title="' . MODULE_CONTENT_SC_SHIPPING_SELECT_THIS_METHOD .'">' . tep_draw_radio_field('', true, true) . '&nbsp;<strong>'.$quotes[$i]['module'] . '&nbsp;';
		          					$sc_order_shipping .= (tep_not_null($quotes[$i]['methods'][$j]['title'])? '(' . $quotes[$i]['methods'][$j]['title'] . ')': '' ) . '</strong>&nbsp;&nbsp;&nbsp;</a></td><td class="text-right"><strong>' . $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . '</strong></td></tr>';
		          				} else {
		          					$sc_order_shipping .='<tr><td onclick="return shipincart_submit(\''.$thisquoteid.'\');"><a href="_" title="' . MODULE_CONTENT_SC_SHIPPING_SELECT_THIS_METHOD . '">' . tep_draw_radio_field('', false, false) . $quotes[$i]['module'] . '&nbsp;';
		          					$sc_order_shipping .= (tep_not_null($quotes[$i]['methods'][$j]['title'])? '(' . $quotes[$i]['methods'][$j]['title'] . ')': '' ) . '&nbsp;&nbsp;&nbsp;</a></td><td class="text-right">' . $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . '</td></tr>';
		          				}
		          			}
		          		}
		          		// added to Display Message when No Shipping Options are Available
		          		$at_least_one_quote_printed = true;
		          	}
		          }
		        } // end if size of quotes
		      } // end if not virtual
		      // added to Display Message when No Shipping Options are Available
		      if ( !$at_least_one_quote_printed ) {
		        $sc_order_shipping .= '<tr><td class="text-center">' . MODULE_CONTENT_SC_SHIPPING_NO_OPTIONS_MESSAGE . '</td></tr>';
		      }

		    $sc_order_shipping .= '				</tbody>';
		    $sc_order_shipping .= '			</table>';
  
		    $sc_order_shipping .= '  </form>';

		    $sc_order_shipping .= '	</div>'; // end body
		    $sc_order_shipping .= '</div>'; //end panel

		    tep_session_register('shipping');


      ob_start();
        include('includes/modules/content/' . $this->group . '/templates/shipping.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
		  } // Use only when cart_contents > 0
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_SC_SHIPPING_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shipping Module', 'MODULE_CONTENT_SC_SHIPPING_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_SHIPPING_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_SHIPPING_SORT_ORDER', '400', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display item count', 'MODULE_CONTENT_SC_SHIPPING_SHOWIC', 'True', 'Display item count?', '6', '4', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display shipping weight', 'MODULE_CONTENT_SC_SHIPPING_SHOWWT', 'True', 'Display shipping weight?', '6', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping weight unit', 'MODULE_CONTENT_SC_SHIPPING_WTUNIT', 'lbs', 'Shipping weight unit.', '6', '6', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Country drop-down menu', 'MODULE_CONTENT_SC_SHIPPING_SHOWCDD', 'True', 'Display Country drop-down menu?', '6', '7', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display State drop-down menu', 'MODULE_CONTENT_SC_SHIPPING_SHOWSDD', 'False', 'Display State drop-down menu?', '6', '8', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Zip Code Input Field', 'MODULE_CONTENT_SC_SHIPPING_SHOWZDD', 'False', 'Display Zip Code drop-down menu?', '6', '9', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Default ZIP', 'MODULE_CONTENT_SC_SHIPPING_DEFAULT_ZIP', '', 'The dafault Zip Code to show for first time load if \"Display Zip Code\" is enabled.', '6', '10', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Update button', 'MODULE_CONTENT_SC_SHIPPING_SHOWUB', 'True', 'Display Update button?', '6', '11', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_SC_SHIPPING_STATUS', 'MODULE_CONTENT_SC_SHIPPING_CONTENT_WIDTH', 'MODULE_CONTENT_SC_SHIPPING_SORT_ORDER', 'MODULE_CONTENT_SC_SHIPPING_SHOWIC', 'MODULE_CONTENT_SC_SHIPPING_SHOWWT', 'MODULE_CONTENT_SC_SHIPPING_WTUNIT', 'MODULE_CONTENT_SC_SHIPPING_SHOWCDD', 'MODULE_CONTENT_SC_SHIPPING_SHOWSDD', 'MODULE_CONTENT_SC_SHIPPING_SHOWZDD', 'MODULE_CONTENT_SC_SHIPPING_DEFAULT_ZIP', 'MODULE_CONTENT_SC_SHIPPING_SHOWUB');
    }
  }
