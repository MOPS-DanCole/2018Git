<?php
/*
  $Id: cm_sc_product_listing_enhanced.php
  $Loc: catalog/includes/modules/content/shopping_cart/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class cm_sc_product_listing_enhanced {
    public $code = '';
    public $group = '';
    public $title = '';
    public $description = '';
    public $sort_order = '';
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_TITLE;
      $this->description = MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_DESCRIPTION;

      if ( defined('MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_STATUS == 'True');
      }
    }

    public function execute() {
      global $oscTemplate, $cart, $currencies, $any_out_of_stock;
	    
	    if ($cart->count_contents() > 0) {
	      $any_out_of_stock = 0;
        
        ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/' . basename(__FILE__));
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
			} // end if $cart->count_contents() > 0
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_STATUS');
    }

    public function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Product Listing', 'MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Check Stock Level', 'MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_STOCK_CHECK', 'True', 'Check to see if sufficent stock is available and warn the customer if not.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    }

    public function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      $keys = array();
      
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_STATUS';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_CONTENT_WIDTH';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_LISTING_SORT_ORDER';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_STOCK_CHECK';
      
      return $keys;
    }
  
    /**
     * Attributes data
     * 
     * Retrieves the attributes data for a specific product, option, and value, 
     * and returns the data as an associative array.
     * 
     * @global integer $languages_id
     * @param integer $products_id
     * @param integer $option
     * @param integer $value
     * @return array  The attributes data
     */
    protected function attributes_values( $products_id, $option, $value ) {
      global $languages_id;
      
      $attributes_query_raw = "
        select 
          popt.products_options_name, 
          poval.products_options_values_name, 
          pa.options_values_price, 
          pa.price_prefix
        from 
          " . TABLE_PRODUCTS_OPTIONS . " popt
          join " . TABLE_PRODUCTS_ATTRIBUTES . " pa
            on (pa.options_id = popt.products_options_id)
          join " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval
            on (pa.options_values_id = poval.products_options_values_id)
        where 
          pa.products_id = '" . (int)$products_id . "'
          and pa.options_id = '" . (int)$option . "'
          and pa.options_values_id = '" . (int)$value . "'
          and popt.language_id = '" . (int)$languages_id . "'
          and poval.language_id = '" . (int)$languages_id . "'";
      $attributes_query = tep_db_query( $attributes_query_raw );
      $attributes_values = tep_db_fetch_array( $attributes_query );
      
      return $attributes_values;
    }
    
    /**
     * Get product attributes
     * 
     * Get the option name and value name -- and the ID for each one -- for each 
     * option that has been selected for a product in the cart. This should 
     * really be done in the shopping_cart class, but it's not there so we have 
     * to do it here.
     * 
     * @param array $product
     * @return type
     */
    protected function get_product_attributes( $product ) {
      $attribute_options = false;
    
      if (isset($product['attributes']) && is_array($product['attributes'])) {
        $attribute = 0;
        while (list($option, $value) = each($product['attributes'])) {
          $attribute_options[$attribute] = $this->attributes_values( $product['id'], $option, $value );

          $attribute_options[$attribute]['options_id'] = $option;
          $attribute_options[$attribute]['options_values_id'] = $value;
          
          $attribute++;
        }
      }
      
      return $attribute_options;
    }
    
    /**
     * Does a product have attributes?
     * 
     * Find out whether the product in the cart has attributes. Again, this 
     * should be done in the shopping_class cart.
     * 
     * @param array $product
     * @return boolean
     */
    protected function has_product_attributes( $product ) {
      $has_attributes = false;
    
      if ($this->get_product_attributes( $product ) !== false) {
        $has_attributes = true;
      }
      
      return $has_attributes;
    }
    
    /**
     * 
     * @param integer $products_id
     * @param integer $products_quantity
     * @return string  The stock check notice or an empty string.
     */
    public function stock_check( $products_id, $products_quantity = 1 ) {
      $stock_check = '';
      
      if( MODULE_CONTENT_SHOPPING_CART_PRODUCT_ENHANCED_STOCK_CHECK == 'True' && $products_id > 0 ) {
        $stock_check = tep_check_stock( (int)$products_id, (int)$products_quantity );
      }

      return $stock_check;
    }
    
    /**
     * Out of stock
     * 
     * Is there sufficient stock of this product to fill the current order?
     * 
     * @param integer $products_id
     * @param integer $products_quantity
     * @return boolean  True if sufficient stock exists
     */
    protected function is_out_of_stock( $products_id, $products_quantity = 1 ) {
      $is_out_of_stock = false;
      
      if( tep_not_null( $this->stock_check( $products_id, $products_quantity ) ) ) {
        $is_out_of_stock = true;
      }
      
      return $is_out_of_stock;
    }
    
  }
  