<?php
 /*
  $Id$ explode_category_tree

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  
  extended class author: G.L. Walker
  Copyright (c) 2014 G.L. Walker

  Released under the GNU General Public License
*/
  class explode_category_tree extends category_tree {
    
    var $root_category_id = 0,
    		$parent_group_start_string = null,
    		$parent_group_end_string = null,
    		$parent_group_apply_to_root = false,
    		$root_start_string = '<li class="dropdown">',
    		$root_end_string = '</li>',
    		$parent_start_string = '<ul class="dropdown-menu">',
    		$parent_end_string = '</ul>',
    		$child_start_string = '<li>',
    		$child_end_string = '</li>';
    
    function _buildHoz($parent_id, $level = 0) {
      if(isset($this->_data[$parent_id])) {
        foreach($this->_data[$parent_id] as $category_id => $category) {
          if($this->breadcrumb_usage === true) {
            $category_link = $this->buildBreadcrumb($category_id);
          } else {
            $category_link = $category_id;
          }
          if(($this->follow_cpath === true) && in_array($category_id, $this->cpath_array)) {
            $link_title = $this->cpath_start_string . $category['name'] . $this->cpath_end_string;
          } else {
            $link_title = $category['name'];
          }

		  if (isset($this->_data[$category_id]) && ($level != 0)) {
            $result .= '<li class="dropdown dropdown-submenu"><a href="#" tabindex="-1" class="dropdown-toggle" data-toggle="dropdown">';
            $caret = false;
          } elseif(isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level + 1))) {
            $result .= $this->root_start_string;
            $result .= '<a href="#" tabindex="-1" class="dropdown-toggle" data-toggle="dropdown">';
            $caret =   '<span class="caret"></span>';
			
          } else {
            $result .= $this->child_start_string;
            $result .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $category_link) . '">';
            $caret = false;
          }
		  
          $result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level);
          $result .= $link_title . (($caret != false) ? $caret : null) . '</a>';
		  
          if(isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level + 1))) {
            // uncomment below to show parent category link //
            $root_link_title =  '<span class="glyphicon glyphicon-th-list"></span>&nbsp;' . $link_title . '<li class="divider"></li>';
            // divider added for clarity - comment out if you no like //
            //$root_link_title .= '<li class="visible-xs divider"></li>';
			
            $result .= $this->parent_start_string;
            $result .= '<li><a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $category_link) . '">' . $root_link_title . '</a></li>';
            $result .= $this->_buildHoz($category_id, $level + 1);
            $result .= $this->parent_end_string;
            $result .= $this->child_end_string;
          } else {
            $result .= $this->root_end_string;
          }
        }
      }
      return $result;
    }
    
    //New function: 
    function setNewRootCategoryID($root_category_id) {
      $this->root_category_id = $root_category_id;
    }

    function getExTree() {
      return $this->_buildHoz($this->root_category_id);
    }
  }
/* end explode_category_tree */