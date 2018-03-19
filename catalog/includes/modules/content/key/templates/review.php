<?php
/*
  Copyright (c) 2016, G Burton
  All rights reserved.

  Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

  1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

  2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

  3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
?>
<div class="col-sm-<?php echo $content_width; ?> key-review">
  <!-- <h4 class="page-header"><?php echo MODULE_CONTENT_KEY_REVIEW_HEADING_REVIEW; ?></h4> -->
  
  <div class="contentContainer">
  <div class="well well-lg">
  
  <?php
  $tabs    = '<ul class="nav nav-tabs" role="tablist">' . PHP_EOL;
  $content = '<div class="tab-content">' . PHP_EOL;
  
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    $tabs    .= '<li role="presentation"' . (($i == 0) ? ' class="active"' : '') . '><a href="#tab_' . $i . '" aria-controls="tab_' . $i . '" role="tab" data-toggle="tab">' . $order->products[$i]['name'] . '</a></li>' . PHP_EOL; 
    $content .= '<div role="tabpanel" class="tab-pane' . (($i == 0) ? ' active' : '') . '" id="tab_' . $i . '"><br>';
      
    // do we need to show a review or a form ?
    $existing_review = tep_db_query("select reviews_status, reviews_rating, reviews_text from reviews r left join reviews_description rd on r.reviews_id = rd.reviews_id where r.clubosc_key = '" . tep_db_input($_key) . "' and r.products_id = '" . (int)$order->products[$i]['id'] . "' LIMIT 1");
    // review already exists
    if (tep_db_num_rows($existing_review)) {
      $review = tep_db_fetch_array($existing_review);
      if ($review['reviews_status'] == 1) {
        $approved = MODULE_CONTENT_KEY_REVIEW_REVIEW_APPROVED;
        $label    = 'success';
      }
      else {
        $approved = MODULE_CONTENT_KEY_REVIEW_REVIEW_UNAPPROVED;
        $label    = 'warning';
      }
      $content .= '<blockquote class="review">';
      $content .= '  <p class="small">' . tep_output_string_protected($review['reviews_text'] . '...') . '</p><div class="clearfix"></div>';
      $content .= '  <footer>' . tep_draw_stars($review['reviews_rating']) . '<span class="pull-right label label-' . $label . '">' . $approved . '</span></footer>';
      $content .= '</blockquote>';
    }
    else {
      // show review form
      $content .= tep_draw_form('product_reviews_write', tep_href_link('key.php', 'key=' . $_key), 'post', 'class="form-horizontal"', true);
      $content .= tep_draw_hidden_field('products_id', (int)$order->products[$i]['id']);
      $content .= tep_draw_hidden_field('key', $_key);
      $content .= tep_draw_hidden_field('action', 'review');
      $content .= tep_draw_hidden_field('name', $orderkey_name_fic);
      //$content .= tep_draw_hidden_field('customers_id', $customerkey_id);
    
      $content .= '<div class="row">';
      $content .= '  <div class="col-sm-9">' . tep_draw_textarea_field('review', 'soft', null, null, NULL, 'required aria-required="true" id="inputReview" placeholder="' . sprintf(MODULE_CONTENT_KEY_REVIEW_PLACEHOLDER_REVIEW, $order->products[$i]['name']) . '"') . '</div>';
      $content .= '  <div class="col-sm-3">';
      $content .= '    <label class="radio-inline">' . tep_draw_radio_field('rating', '1') . '</label>';
      $content .= '    <label class="radio-inline">' . tep_draw_radio_field('rating', '2') . '</label>';
      $content .= '    <label class="radio-inline">' . tep_draw_radio_field('rating', '3') . '</label>';
      $content .= '    <label class="radio-inline">' . tep_draw_radio_field('rating', '4') . '</label>';
      $content .= '    <label class="radio-inline">' . tep_draw_radio_field('rating', '5', 'checked', 'required aria-required="true"') . '</label>';
      $content .= '    <div class="help-block justify" style="width: 150px; padding-top: 10px;">' . MODULE_CONTENT_KEY_REVIEW_TEXT_BAD . '<p class="pull-right">' . MODULE_CONTENT_KEY_REVIEW_TEXT_GOOD . '</p></div>';
      $content .= '  </div>';
      $content .= '</div>';
    
      $content .= '<div class="alert alert-info">' . sprintf(MODULE_CONTENT_KEY_REVIEW_REVIEWERS_NAME, $orderkey_name_fic) . '</div>';
      $content .= tep_draw_button(MODULE_CONTENT_KEY_REVIEW_SEND_REVIEW, 'glyphicon glyphicon-chevron-right', null, 'primary', null, 'btn-success btn-block');

      $content .= '</form>';         
    } 
    $content .= '</div>' . PHP_EOL;         
  } 
  $tabs    .= '</ul>' . PHP_EOL; 
  $content .= '</div>' . PHP_EOL; 

  echo $tabs;
  echo $content;
?> 

  </div>
 </div>
</div>
