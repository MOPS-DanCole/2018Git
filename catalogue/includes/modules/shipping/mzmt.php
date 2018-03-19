<?php
/*
  $Id: mzmt.php, v2.1a 20140125 Kymation Exp $
  $Portions From: mzmt.php,v 1.000 2004-10-29 Josh Dechant Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce
  Protions Copyright (c) 2004 Josh Dechant

  Released under the GNU General Public License
*/

  class mzmt {
    var $version = '2.1a';
    var $code = '';
    var $title = '';
    var $description = '';
    var $icon = '';
    var $enabled = false;
    var $num_tables = 0;
    var $num_geozones = 0;
    var $delivery_geozone = 0;
    var $geozone_mode = 'weight';
    var $order_total = 0;
    var $languages_array = array();
    var $current_delivery_table = 0;
    var $quotes = array();
    
    
    ////
    // Set up all of the default values available at this time
    function mzmt() {
      global $order;
      
      $this->code = 'mzmt';
      $this->sort_order = @MODULE_SHIPPING_MZMT_SORT_ORDER;
      $this->tax_class = @MODULE_SHIPPING_MZMT_TAX_CLASS;
      $this->enabled = ( ( @MODULE_SHIPPING_MZMT_STATUS == 'True' ) ? true : false );
      
      // When the language file has been included
      if( defined( 'MODULE_SHIPPING_MZMT_TEXT_TITLE' ) ) {
        $this->title = MODULE_SHIPPING_MZMT_TEXT_TITLE;
        $this->description = MODULE_SHIPPING_MZMT_TEXT_DESCRIPTION;
      }
      
      // Second pass and later, when the number of geo zones and tables have been set
      if( defined( 'MODULE_SHIPPING_MZMT_NUMBER_GEOZONES' ) ) {
        $this->num_geozones = MODULE_SHIPPING_MZMT_NUMBER_GEOZONES;
        $this->num_tables = MODULE_SHIPPING_MZMT_NUMBER_TABLES;
      
        if ($this->enabled == true) {
          $this->enabled = false;
          for($n = 1; $n <= $this->num_geozones; $n ++) {
            if ((( int ) constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_ID' ) > 0) && (( int ) constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_ID' ) == $this->getGeoZoneID ( $order->delivery ['country'] ['id'], $order->delivery ['zone_id'] ))) {
              $this->enabled = true;
              $this->delivery_geozone = $n;
              break;
            } elseif ((( int ) constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $n . '_ID' ) == 0) && ($n == ( int ) $this->num_geozones)) {
              $this->enabled = true;
              $this->delivery_geozone = $n;
              break;
            }
          }
        }
      }
      
      // Set the languages_array to the current store languages
      $this->get_languages();
    }
    
    
    ////
    // Get a quote or all quotes for a geo zone
    function quote( $method = '' ) {
      global $order, $shipping_weight, $shipping_num_boxes, $language;
      
      $combined_quote_weight = $shipping_num_boxes * $shipping_weight;
      $weight_string = '';
      if( tep_not_null( MODULE_SHIPPING_MZMT_WEIGHT_UNITS ) ) {
        $weight_string = ' (' . $combined_quote_weight . ' ' . MODULE_SHIPPING_MZMT_WEIGHT_UNITS . ')';
      }
      $this->quotes = array (
          'id' => $this->code,
          'module' => constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TEXT_TITLE_' . strtoupper( $language ) ) . $weight_string,
          'methods' => array () 
      );

      $this->determineTableMethod ( constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_MODE' ) );
      
      if ( $method != '' ) { // Single quote
        $j = $this->current_delivery_table = substr($method, 5);
        $table_number = substr ( $method, 5 );
        
        $shipping = $this->determineShipping ( preg_split ( "/[:,]/", constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $table_number ) ) );
        
        $this->quotes ['methods'] [] = array (
            'id' => 'table' . $table_number,
            'title' => constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $table_number . '_TEXT_WAY_' . strtoupper( $language ) ),
            'cost' => $shipping + constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_HANDLING' ) 
        );
        
      } else { // All applicable quotes
        for( $table_number = 1; $table_number <= $this->num_tables; $table_number ++ ) {
          if (! tep_not_null ( constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $table_number ) ))
            continue;

	 $this->current_delivery_table = $table_number;
          $shipping = $this->determineShipping ( preg_split ( "/[:,]/", constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $table_number ) ) );
          $this->quotes ['methods'] [] = array (
              'id' => 'table' . $table_number,
              'title' => constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_TABLE_' . $table_number . '_TEXT_WAY_' . strtoupper( $language ) ),
              'cost' => $shipping + constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_HANDLING' ) 
          );
        }
      }
      
      // If shipping is set as taxable, add the appropriate tax
      if ($this->tax_class > 0) {
        $this->quotes ['tax'] = tep_get_tax_rate ( $this->tax_class, $order->delivery ['country'] ['id'], $order->delivery ['zone_id'] );
      }
      
      // Add the icon if there is one
      if (tep_not_null ( constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_ICON' ) ))
        $this->quotes ['icon'] = tep_image ( DIR_WS_ICONS . constant ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $this->delivery_geozone . '_ICON' ), $this->title );
      
      return $this->quotes;
    } // function quote
    
    
    ////
    // Return the module status
    function check() {
      if (! isset ( $this->_check ) ) {
        $check_query = tep_db_query ( "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_MZMT_STATUS'" );
        $this->_check = tep_db_num_rows ( $check_query );
      }
      return $this->_check;
    }


    ////
    // Second pass of the initial edit, or the Update box has been checked.
    // This method is executed by modified code in admin/modules.php
    // It adds the Configuration table entries for the number of
    //   Zones and Tables set in the first pass, and modifies
    //   existing entries to allow for later updates.
    function update( $vars_array ) {
      // Check that we are actually in the second stage install process or later
      if( ( is_array( $vars_array ) &&
          array_key_exists( 'MODULE_SHIPPING_MZMT_NUMBER_GEOZONES', $vars_array ) &&
          $vars_array['MODULE_SHIPPING_MZMT_NUMBER_GEOZONES'] > 0 &&
          ( array_key_exists( 'MODULE_SHIPPING_MZMT_NUMBER_TABLES', $vars_array ) &&
          $vars_array['MODULE_SHIPPING_MZMT_NUMBER_TABLES'] > 0 ) ) ) {
        
        $this->num_geozones = $vars_array['MODULE_SHIPPING_MZMT_NUMBER_GEOZONES'];
        $this->num_tables = $vars_array['MODULE_SHIPPING_MZMT_NUMBER_TABLES'];
        
        // Add, remove, or modify the database entries for the selected number of Zones/Tables
        $this->zones_tables( $vars_array );
        
        // This part is only done on the second pass of the initial install
        if( $vars_array['MODULE_SHIPPING_MZMT_UPDATE_ZONES_TABLES'] != 'True' ) {
          // Modify these two entries to add an update warning message
          // Update must be run if the first two are changed as they make changes to the number of geo zones/tables
          $sql_data_array[] = array(
              'configuration_key' => 'MODULE_SHIPPING_MZMT_NUMBER_GEOZONES',
              'configuration_array' => array(
                  'configuration_description' => 'The number of shipping geo zones you want to use. ' . MODULE_SHIPPING_MZMT_UPDATE_WARNING
              )
          );
          $sql_data_array[] = array(
              'configuration_key' => 'MODULE_SHIPPING_MZMT_NUMBER_TABLES',
              'configuration_array' => array(
                  'configuration_description' => 'The number of shipping tables per geo zone. ' . MODULE_SHIPPING_MZMT_UPDATE_WARNING
              )
          );
        
          // Remove the second install message and replace it with the update checkbox
          $sql_data_array[] = array(
              'configuration_key' => 'MODULE_SHIPPING_MZMT_UPDATE_ZONES_TABLES',
              'configuration_array' => array(
                  'configuration_title' => 'Update',
                  'configuration_description' => 'Check if you want to change the number of geo zones or tables. <span style="color:red;"><b>WARNING:</b> This will remove all of the settings below.</span>',
                  'configuration_value' => '',
                  'set_function' => 'tep_cfg_mzmt_update( ',
                  'use_function' => ''
              )
          );
                
          // Use the above arrays to update the configuration table with the tables etc.
          foreach( $sql_data_array as $configuration_data ) {
            tep_db_perform( TABLE_CONFIGURATION, $configuration_data['configuration_array'], 'update', "configuration_key = '" . $configuration_data['configuration_key'] . "'");
          }
        } // if( $vars_array
      } // if( array_key_exists
    } // function update
      
    
    ////
    // Update existing, add new, and/or remove unwanted zones/tables
    function zones_tables( $vars_array ) {
      // Check whether an update has been requested
      // This is always the case for the first pass
      if( ! array_key_exists( 'MODULE_SHIPPING_MZMT_UPDATE_ZONES_TABLES', $vars_array ) || 
          ( array_key_exists( 'MODULE_SHIPPING_MZMT_UPDATE_ZONES_TABLES', $vars_array ) && 
          $vars_array ['MODULE_SHIPPING_MZMT_UPDATE_ZONES_TABLES'] == 'True' ) ) {
        // loop an arbitrary number of times, breaking out when we are done
        for( $zone = 1; $zone < 999; $zone ++ ) {
          switch (true) {
            case (! array_key_exists ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_ID', $vars_array ) && $zone <= $this->num_geozones) :
              // If the Zone does not exist, and we have less than the selected number of zones, we add the Zone
              tep_db_query ( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('<hr />Geo Zone " . $zone . "', 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "_ID', '', 'Enable this method for the following geo zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_geozones(', now())" );
              tep_db_query ( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Geo Zone " . $zone . " Table Mode', 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "_MODE', 'weight', 'The shipping cost is based on the total weight, total price, or total count of the items ordered.', '6', '0', 'tep_cfg_select_option(array(\'weight\', \'price\', \'count\'), ', now())" );
              tep_db_query ( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Geo Zone " . $zone . " Table Icon', 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "_ICON', '', 'The icon of the shipping method. Leave blank if none.', '6', '0', now())" );
              tep_db_query ( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Geo Zone " . $zone . " Handling Fee', 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "_HANDLING', '0', 'Handling Fee for this geo zone.', '6', '0', now())" );
            
              foreach ( $this->languages_array as $language ) {
                $lang = '_' . strtoupper ( $language );
                $language_name = ucfirst ( $language );
                tep_db_query ( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Geo Zone " . $zone . " Table Title in " . $language_name . "', 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "_TEXT_TITLE" . $lang . "', '', 'The title of the shipping method in " . $language_name . ".', '6', '0', now())" );
              }
            
              // Update the tables for this zone
              $this->update_tables ( $vars_array, $zone );
              break;
          
            case (array_key_exists ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_ID', $vars_array ) && $zone <= $this->num_geozones) :
              // The Zone data exists, and we are still within the selected number of zones,
              // so we just need to fix the Tables (if required)
              $this->update_tables ( $vars_array, $zone );
              break;
          
            case (array_key_exists ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_ID', $vars_array ) && $zone > $this->num_geozones) :
              // The zone data exists and we no longer want it, so remove the entries
              tep_db_query ( "delete from " . TABLE_CONFIGURATION . " where configuration_key like 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "%'" );
              $this->update_tables ( $vars_array, $zone );
              break;
          
            default :
            case (! array_key_exists ( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_ID', $vars_array ) && $zone > $this->num_geozones) :
              // The zone does not already exist and we do not need it. We're done here.
              // Break out of the loop and return.
              break 2;
          }
        }
      }
    } // function zones_tables


    ////
    // Update existing, add new, and/or remove unwanted tables
    // Done here so that we don't have to repeat this code multiple times in the zones_tables() method
    function update_tables( $vars_array, $zone ) {
      // loop an arbitrary number of times, breaking out when we are done
      for( $tables = 1; $tables < 999; $tables ++ ) {
        switch( true ) {
          case ( ! array_key_exists( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_TABLE_' . $tables, $vars_array ) && $tables <= $this->num_tables ) :
            // The table does not already exist and we need to create it
            tep_db_query ( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Geo Zone " . $zone . " Shipping Table " . $tables . "', 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "_TABLE_" . $tables . "', '', 'Shipping table " . $tables . " for this geo zone', '6', '0', now())" );
    
            foreach( $this->languages_array as $language ) {
              $lang = '_' . strtoupper( $language );
              $language_name = ucfirst( $language );
              tep_db_query ( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Geo Zone " . $zone . " Shipping Table " . $tables . " Name', 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "_TABLE_" . $tables . "_TEXT_WAY" . $lang . "', '', 'Shipping table " . $tables . " name for this geo zone in " . $language_name . "', '6', '0', now())" );
            }
            break;
    
          case ( array_key_exists( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_TABLE_' . $tables, $vars_array ) && $tables <= $this->num_tables ) :
            // The table already exists and we only need to update it
            // The normal update process handles this, so nothering to do here
            break;
    
          case ( array_key_exists( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_TABLE_' . $tables, $vars_array ) && $tables > $this->num_tables ) :
            // The table already exists and we no longer want it, so remove the entries
            tep_db_query ( "delete from " . TABLE_CONFIGURATION . " where configuration_key like 'MODULE_SHIPPING_MZMT_GEOZONE_" . $zone . "_TABLE_" . $tables . "%'" );
            break;
            	   
          default :
          case ( ! array_key_exists( 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_TABLE_' . $tables, $vars_array ) && $tables > $this->num_tables ) :
            // The table does not already exist and we do not need it. We're done here.
            // Break out of the loop and quit.
            break 2;
        } // switch( true )
      } // for( $tables = 1
    } // function update_tables
    
    
    ////
    // Initial install
    function install() {
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ( 'Module Version', 'MODULE_SHIPPING_MZMT_VERSION', '" . $this->version . "', 'The version of this module that you are running', '6', '0', 'tep_cfg_disabled(', now() ) ");
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_group_id, sort_order, use_function, set_function ) values ( 'MODULE_SHIPPING_MZMT_LANGUAGE_FILE_TEST', '6', '0', 'tep_cfg_mzmt_language_file_check', 'tep_cfg_do_nothing(' ) ");
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_group_id, sort_order, use_function, set_function ) values ( 'MODULE_SHIPPING_MZMT_MODULE_MODS_TEST', '6', '0', 'tep_cfg_mzmt_modules_mod_test', 'tep_cfg_do_nothing(' ) ");
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Multi-Geo Zone Multi-Table Shipping', 'MODULE_SHIPPING_MZMT_STATUS', 'True', 'Do you want to offer multi-region multi-table rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())" );
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_MZMT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())" );
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Prefix', 'MODULE_SHIPPING_MZMT_PREFIX', 'shp', 'Use only geo zones that start with this string. Leave blank to show all geo zones, including tax zones.', '6', '0', now())" );
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Weight Units', 'MODULE_SHIPPING_MZMT_WEIGHT_UNITS', 'lbs.', 'Show these units after the weight. If blank, no weight will be shown.', '6', '0', now())" );
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_MZMT_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())" );
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Geo Zones', 'MODULE_SHIPPING_MZMT_NUMBER_GEOZONES', '0', 'The number of shipping geo zones you want to use.', '6', '0', now())" );
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Tables/Geo Zone', 'MODULE_SHIPPING_MZMT_NUMBER_TABLES', '0', 'The number of shipping tables per geo zone.', '6', '0', now())" );
      tep_db_query ( "insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function ) values ( 'MODULE_SHIPPING_MZMT_UPDATE_ZONES_TABLES', 'True', '6', '0', 'tep_cfg_mzmt_warning_second_install', 'tep_cfg_do_nothing(' ) ");
      // The remaining configuration values will be added by the Update function once the numbers of geo zones and tables are known.
    }    
    
    ////
    // Uninstall
    function remove() {
      tep_db_query ( "delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode ( "', '", $this->keys () ) . "')" );
    }
    
    
    ////
    // Keys match the database configuration table's configuration_key field
    function keys() {
      $keys = array ();

      $keys [] = 'MODULE_SHIPPING_MZMT_VERSION';
      $keys [] = 'MODULE_SHIPPING_MZMT_LANGUAGE_FILE_TEST';
      $keys [] = 'MODULE_SHIPPING_MZMT_MODULE_MODS_TEST';
      $keys [] = 'MODULE_SHIPPING_MZMT_STATUS';
      $keys [] = 'MODULE_SHIPPING_MZMT_TAX_CLASS';
      $keys [] = 'MODULE_SHIPPING_MZMT_PREFIX';
      $keys [] = 'MODULE_SHIPPING_MZMT_WEIGHT_UNITS';
      $keys [] = 'MODULE_SHIPPING_MZMT_SORT_ORDER' ;
      $keys [] = 'MODULE_SHIPPING_MZMT_NUMBER_GEOZONES';
      $keys [] = 'MODULE_SHIPPING_MZMT_NUMBER_TABLES';
      $keys [] = 'MODULE_SHIPPING_MZMT_UPDATE_ZONES_TABLES';
      
      for( $zone = 1; $zone <= $this->num_geozones; $zone ++ ) {
        $keys [] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_ID';
        $keys [] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_MODE';
        $keys [] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_ICON';
        
        foreach( $this->languages_array as $language ) {
          $lang = strtoupper( $language );
          $keys [] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_TEXT_TITLE_' . $lang;
        }
        $keys [] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_HANDLING';
        
        for( $tables = 1; $tables <= $this->num_tables; $tables ++ ) {
          $keys [] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_TABLE_' . $tables;
          
          foreach( $this->languages_array as $language ) {
            $lang = strtoupper( $language );
            $keys [] = 'MODULE_SHIPPING_MZMT_GEOZONE_' . $zone . '_TABLE_' . $tables . '_TEXT_WAY_' . $lang;
          }
        }
      }
      
      return $keys;
    }

    
    ////
    // Get an array of installed languages
    function get_languages() {
      if( !class_exists( 'language' ) ) {
        include_once DIR_WS_CLASSES . 'language.php';
      }
      $language_class = new language;
      $languages = $language_class->catalog_languages;
      
      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }
    }
    
    
    ////
    // Set the correct order total value for the selected shipping basis
    function determineTableMethod($geozone_mode) {
      global $total_count, $shipping_weight;
      
      $this->geozone_mode = $geozone_mode;
      
      if ($this->geozone_mode == 'price') {
        $this->order_total = $_SESSION['cart']->show_total ();
      } elseif ($this->geozone_mode == 'count') {
        $this->order_total = $total_count;
      } else {
        $this->order_total = $shipping_weight;
      }
      
      return true;
    }
    
    
    ////
    // Return the shipping cost based on the table
    function determineShipping( $table_cost ) {
      global $shipping_num_boxes;

      for ($i=0, $n=sizeof($table_cost); $i<$n; $i+=2) {
        if ($this->order_total >= $table_cost[$i]) {
          $shipping_factor = $table_cost[$i+1];
          $previous_weight = $table_cost[$i-2];
          $previous_cost = $table_cost[$i-1];
          $current_weight = $table_cost[$i];
        }
      }

      if (substr_count($shipping_factor, '%') > 0) {
        $shipping = ((($this->order_total*10)/10)*((str_replace('%', '', $shipping_factor))/100));
      } elseif (substr_count($shipping_factor, '*') > 0) {
      	$weight_measure = $current_weight - $previous_weight;  //Find out how much is each additional factor eg. additional 0.5 lb or additional 1 lb
      	$add_cost = ceil((($this->order_total - $previous_weight)/$weight_measure)) * (str_replace('*', '', $shipping_factor)); //How many additional shipping cost
      	$shipping = str_replace('$', '', $previous_cost) + $add_cost;
      } else {
        $shipping = str_replace ( '$', '', $shipping_factor );
      }
      
      if ($this->geozone_mode == 'weight') {
        $shipping = $shipping * $shipping_num_boxes;
      }

        /**
         * Custom code BEGIN
         * {{{
         */

        /**
         * Define discounts.
         *
         * Array of key => value pairs.
         *      key   = order total threshold
         *      value = amount to discount
         */
        $discounts = array(
			'50.00' => 4.00, // Discount $4.00 for orders >= $50
            '100.00' => 7.00, // Discount $7.00 for orders >= $100
            '200.00' => 11.95, // Discount $11.95 for orders >= $200
        );

        /**
         * !NOTICE: You should not need to edit beyond this point
         */

        /**
         * Only run if this is table 1
         */
        if (strval($this->current_delivery_table) === '1')
        {
            /**
             * Start with no discount
             */
            $discount = 0;

            /**
             * Reverse sort the discount array
             */
            krsort($discounts);

            /**
             * Loop until we meet a threshold
             */
            foreach ($discounts as $order_total => $discounted_amount)
            {
             //  if ($_SESSION['cart']->show_total() >= $order_total)
            if ((!tep_session_is_registered('admin')) && ($_SESSION['cart']->show_total()) >= $order_total) 
		{
                    $discount = $discounted_amount;
                    break;
                }
            }

            /**
             * Apply the discount
             */
            if ($discount > 0)
            {
                $shipping -= $discount;
            }
        }

        /**
         * END Custom code
         * }}}
         */

      return $shipping;
    }
    
    
    ////
    // Check if the current zone matches one of the geo zones we have set up here
    function getGeoZoneID( $country_id, $zone_id ) {
      // Set the SQL for thegeo zone prefix if any.
      $prefix_sql = '';
      if( MODULE_SHIPPING_MZMT_PREFIX != '' ) {
        $prefix_sql = " and LOWER(gz.geo_zone_name) like '" . strtolower( MODULE_SHIPPING_MZMT_PREFIX ) . "%'";
      }
      
      // Check for a Geo Zone that explicity includes the country & specific zone (useful for splitting countries with zones up)
      $zone_query = tep_db_query ( "select gz.geo_zone_id from " . TABLE_GEO_ZONES . " gz left join " . TABLE_ZONES_TO_GEO_ZONES . " ztgz on (gz.geo_zone_id = ztgz.geo_zone_id) where ztgz.zone_country_id = '" . ( int ) $country_id . "' and ztgz.zone_id = '" . ( int ) $zone_id . "'" . $prefix_sql );
      
      if (tep_db_num_rows ( $zone_query )) {
        $zone = tep_db_fetch_array ( $zone_query );
        return $zone ['geo_zone_id'];
      } else {
        // No luck… Now check for a Geo Zone for the country and "All Zones" of the country.
        $zone_query = tep_db_query ( "select gz.geo_zone_id from " . TABLE_GEO_ZONES . " gz left join " . TABLE_ZONES_TO_GEO_ZONES . " ztgz on (gz.geo_zone_id = ztgz.geo_zone_id) where ztgz.zone_country_id = '" . ( int ) $country_id . "' and (ztgz.zone_id = '0' or ztgz.zone_id is NULL)" . $prefix_sql );
        
        if (tep_db_num_rows ( $zone_query )) {
          $zone = tep_db_fetch_array ( $zone_query );
          return $zone ['geo_zone_id'];
        } else {
          return false;
        }
      }
    }
  } // class
  
  
  ///
  // Function (not a method!) generates a pulldown menu filled with the available Geo Zones
  if( ! function_exists( 'tep_cfg_pull_down_geozones' ) ) {
    function tep_cfg_pull_down_geozones( $zone_class_id, $key = '' ) {
      $name = ( ( $key ) ? 'configuration[' . $key . ']' : 'configuration_value' );
      
      $zone_class_array = array (
        array (
          'id' => '0',
          'text' => 'Rest of the World' 
        ) 
      );
      
      $zone_class_query_raw = "
        select 
          geo_zone_id, geo_zone_name 
        from 
          " . TABLE_GEO_ZONES . " 
        where 
          LOWER(geo_zone_name) like '" . strtolower( MODULE_SHIPPING_MZMT_PREFIX ) . "%'
        order by 
          geo_zone_name
      ";
      $zone_class_query = tep_db_query( $zone_class_query_raw );
      
      while( $zone_class = tep_db_fetch_array ( $zone_class_query ) ) {
        $zone_class_array [] = array (
          'id' => $zone_class ['geo_zone_id'],
          'text' => $zone_class ['geo_zone_name'] 
        );
      }
      
      return tep_draw_pull_down_menu( $name, $zone_class_array, $zone_class_id );
    }
  }


  ////
  // Check whether admin/modules.php has been modified/replaced
  if( !function_exists( 'tep_cfg_mzmt_modules_mod_test' ) ) {
    function tep_cfg_mzmt_modules_mod_test() {
      $filename = DIR_FS_ADMIN . 'modules.php';
      if( file_exists( $filename ) ) {
        // Read the file into an array, one line per element
        $file_array = file( $filename );
  
        // Step through the file and check for a match with the selected code
        foreach ($file_array as $line) {
          // Check if the line matches one of the lines that should be removed
          if( trim( $line ) == '$module->update( $HTTP_POST_VARS[\'configuration\'] );' ) {
            // The critical line exists, so return success and quit
            return '<div style="margin-top:-2em;">' . tep_image( DIR_WS_ICONS . 'tick.gif', '', '16', '16', 'style="vertical-align:middle;"' ) . ' <span style="vertical-align:middle; font-weight:bold;">' . MODULE_SHIPPING_MZMT_MODULES . '</span></div>';
            break;
          }
        }
  
      } else {
        // The file was not found, so return an error
        return '<div style="margin-top:-2em;">' . tep_image( DIR_WS_ICONS . 'cross.gif', '', '16', '16', 'style="vertical-align:middle;"' ) . ' <span style="vertical-align:middle; font-weight:bold; color:red;">' . MODULE_SHIPPING_MZMT_MODULES_MISSING . '</span></div>';
  
      } // if( file_exists
  
      // The lines were not found in the file, so return an error message
      return '<div style="margin-top:-2em;">' . tep_image( DIR_WS_ICONS . 'cross.gif', '', '16', '16', 'style="vertical-align:middle;"' ) . ' <span style="vertical-align:middle; font-weight:bold; color:red;">' . MODULE_SHIPPING_MZMT_MODULES_NOT_MODIFIED . '</span></div>';
    } // function tep_cfg_mzmt_modules_mod_test
  } // if( !function_exists
  
  
  ////
  // Check whether the language file for this module exists
  // We should only need to check the Admin language, so that is taken from $language
  if (!function_exists('tep_cfg_mzmt_language_file_check')) {
    function tep_cfg_mzmt_language_file_check() {
      global $language;
  
      $language_file = DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/shipping/mzmt.php';
  
      if (file_exists($language_file) && is_file($language_file)) {
        return '<div style="margin-top:-1em;">' . tep_image(DIR_WS_ICONS . 'tick.gif', '', '16', '16', 'style="vertical-align:middle;"') . ' <span style="vertical-align:middle; font-weight:bold;">' . MODULE_SHIPPING_MZMT_LANGUAGE_FILE_FOUND . '</span></div>';
        break;
      } // if( file_exists
  
      // The language file was not found, so return an error message
      return '<div style="margin-top:-1em;">' . tep_image(DIR_WS_ICONS . 'cross.gif', '', '16', '16', 'style="vertical-align:middle;"') . ' <span style="vertical-align:middle; font-weight:bold; color:red;">' . MODULE_SHIPPING_MZMT_LANGUAGE_FILE_MISSING . '</span></div>';
    } // function tep_cfg_mzmt_language_file_check
  } // if( !function_exists
  
  
  ////
  // Show a warning message about second install step
  // This function is used only in the initial install,
  //   it is removed the first time the module is edited.
  if (!function_exists('tep_cfg_mzmt_warning_second_install')) {
    function tep_cfg_mzmt_warning_second_install() {
      return '<div style="margin-top:-1em;">' . tep_image(DIR_WS_IMAGES . 'ms_info.png', '', '16', '16', 'style="vertical-align:middle;"') . ' <span style="vertical-align:middle; font-weight:bold;">' . MODULE_SHIPPING_MZMT_TEXT_EXPLAIN_SECOND_STEP . '</span></div>';
    }
  }


  ////
  // Selector for the Update function
  // Can be set to True, but is reset to False once Update runs
  if( !function_exists( 'tep_cfg_mzmt_update' ) ) {
    function tep_cfg_mzmt_update( $key_value, $key ) {
      $string = '';
      $select_array = array(
          0 => 'True',
          1 => 'False'
      );
  
      for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
        $name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');
  
        if( $select_array[$i] != 'False' ) {
          $string .= '<input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '" /> ';
          $string .= $select_array[$i];
        }
      }
  
      return $string;
    }
  }


  ////
  // Function to show a disabled entry
  if( !function_exists( 'tep_cfg_disabled' ) ) {
    function tep_cfg_disabled( $value ) {
      return tep_draw_input_field( 'configuration_value', $value, ' disabled' );
    }
  }
  
  
  ////
  // Prevent input boxes showing for the output-only test functions
  if (!function_exists('tep_cfg_do_nothing')) {
    function tep_cfg_do_nothing() {
      return '';
    }
  }
   
?>