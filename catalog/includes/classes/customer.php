<?php
/**
  * Customer Dashboard for osCommerce Online Merchant 2.3.4BS
  *
  * Author: frankl
  *
  * Portions @copyright (c) 2017 osCommerce; https://www.oscommerce.com
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

  class customer {
    var $customer_id, $languages_id;

    function __construct($customer_id = '') {
      $this->details = array();
      $this->products = array();
      $this->info = array();
	  $this->orders  = array();
	  $this->query($customer_id);
    }

    function query($customer_id) {
      global $languages_id;

      $customer_id = tep_db_prepare_input($customer_id);

      $customer_query = tep_db_query("select customers_id, customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_default_address_id, customers_telephone, customers_fax, customers_newsletter from customers where customers_id = '" . (int)$customer_id . "'");
      $customer = tep_db_fetch_array($customer_query);

      $customer_orders_query = tep_db_query("select distinct op.products_id from orders o, orders_products op, products p where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = '1' group by products_id order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
        if (tep_db_num_rows($customer_orders_query)) {
          $product_ids = '';
          while ($customer_orders = tep_db_fetch_array($customer_orders_query)) {
            $product_ids .= (int)$customer_orders['products_id'] . ',';
          }
          $product_ids = substr($product_ids, 0, -1);
		  
          //$customer_orders_string = NULL;
          
          $products_query = tep_db_query("select products_id, products_name from products_description where products_id in (" . $product_ids . ") and language_id = '" . (int)$languages_id . "' order by products_name");
		  //$count = tep_db_num_rows($products_query);
		  $index = 0;
          while ($products = tep_db_fetch_array($products_query)) {
            $products_image_query = tep_db_query("select products_image from products where products_id = '" . (int)$products['products_id'] . "'");
			$products_image = tep_db_fetch_array($products_image_query);
			//for ($i=0, $n=sizeof($products); $i<$n; $i++) {
			$this->products[$index] = array('name' => $products['products_name'],
											'id' => $products['products_id'],
											'image' => $products_image['products_image']);
			$index++;
			//}
		  }
		}
		
		$orders_total = tep_count_customer_orders();

		  if ($orders_total > 0) {
			$history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1' order by orders_id DESC";
			$history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
			$history_query = tep_db_query($history_split->sql_query);

			while ($history = tep_db_fetch_array($history_query)) {
			  $products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
			  $products = tep_db_fetch_array($products_query);

			  if (tep_not_null($history['delivery_name'])) {
				$order_type = 's';
				$order_name = $history['delivery_name'];
			  } else {
				$order_type = 'b';
				$order_name = $history['billing_name'];
			  }
			$index = 0;
			for ($i=0, $n=sizeof($products); $i<$n; $i++) {
			$this->orders[$index] = array('id' => $history['orders_id'],
										  'status' => $history['orders_status_name'],
										  'date' => tep_date_long($history['date_purchased']),
										  'type' => $order_type,
										  'name' => tep_output_string_protected($order_name),
										  'lines' => $products['count'],
										  'cost' => strip_tags($history['order_total']));
			$index++;
			}
			}
		  }
			  

      $this->details = array('id' => $customer['customers_id'],
                              'firstname' => $customer['customers_firstname'],
							  'lastname' => $customer['customers_lastname'],
                              'gender' => $customer['customers_gender'],
                              'dob' => $customer['customers_dob'],
                              'email_address' => $customer['customers_email_address'],
                              'default_address_id' => $customer['customers_default_address_id'],
                              'phone' => $customer['customers_telephone'],
                              'fax' => $customer['customers_fax'],
                              'newsletter' => $customer['customers_newsletter']);
		}
    }
?>
