<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if(isset($_POST['save_settig'])){

        $post = array(

          "wcc_seo_keyword_research_plugin_status" => isset($_POST['wcc_seo_keyword_research_plugin_status']) ? sanitize_text_field($_POST['wcc_seo_keyword_research_plugin_status']) : "",

          "wcc_seo_keyword_research_result_number" => isset($_POST['wcc_seo_keyword_research_result_number']) ? sanitize_text_field($_POST['wcc_seo_keyword_research_result_number']) : "",

          "wcc_seo_keyword_research_set_limit" => isset($_POST['wcc_seo_keyword_research_set_limit']) ? sanitize_text_field($_POST['wcc_seo_keyword_research_set_limit']) : "",

          "wcc_seo_keyword_research_set_limit_value" => isset($_POST['wcc_seo_keyword_research_set_limit_value']) ? sanitize_text_field($_POST['wcc_seo_keyword_research_set_limit_value']) : "",

          "wcc_seo_keyword_research_limit_search" => isset($_POST['wcc_seo_keyword_research_limit_search']) ? sanitize_text_field($_POST['wcc_seo_keyword_research_limit_search']) : "",

          "wcc_seo_keyword_research_set_non_repeat_user_form" => isset($_POST['wcc_seo_keyword_research_set_non_repeat_user_form']) ? sanitize_text_field($_POST['wcc_seo_keyword_research_set_non_repeat_user_form']) : "",

          "wcc_seo_keyword_research_set_non_repeat_value" => isset($_POST['wcc_seo_keyword_research_set_non_repeat_value']) ? sanitize_text_field($_POST['wcc_seo_keyword_research_set_non_repeat_value']) : "",

          "wcc_seo_keyword_research_email_radio" => isset($_POST['wcc_seo_keyword_research_email_radio']) ? sanitize_text_field($_POST['wcc_seo_keyword_research_email_radio']) : "",

        );

        foreach ($post as $key => $value) {

          update_option($key,$value);

        }

      $_SESSION['e_msg'] = 'Setting Updated Successfully.';

    }

  }

?>

<?php $wcc_seo_keyword_research_plugin_status = get_option("wcc_seo_keyword_research_plugin_status"); ?>

<?php $wcc_seo_keyword_research_result_number = get_option("wcc_seo_keyword_research_result_number"); ?>

<?php $wcc_seo_keyword_research_set_limit = get_option("wcc_seo_keyword_research_set_limit"); ?>

<?php $wcc_seo_keyword_research_set_limit_value = get_option("wcc_seo_keyword_research_set_limit_value"); ?>

<?php $wcc_seo_keyword_research_limit_search = get_option("wcc_seo_keyword_research_limit_search"); ?>

<?php $wcc_seo_keyword_research_set_non_repeat_user_form = get_option("wcc_seo_keyword_research_set_non_repeat_user_form"); ?>

<?php $wcc_seo_keyword_research_set_non_repeat_value = get_option("wcc_seo_keyword_research_set_non_repeat_value"); ?>

<?php $wcc_seo_keyword_research_email_radio = get_option("wcc_seo_keyword_research_email_radio"); ?>



<?php 

$upload_dir   = wp_upload_dir();



$api_key = '88164459190acab973e2cd83b3256fe0';

$keyword = '';

$result_header = array();

$full_result = array();

$initial_result = array();

$table = '';

if ( isset($_POST['wcc_seo_keyword_research_keyword']) ) {

  $keyword = urlencode( strtolower( trim( strip_tags( $_POST['wcc_seo_keyword_research_keyword'] ) ) ) );

}



if ( $wcc_seo_keyword_research_set_limit_value == 'Yes' && !isset( $_SESSION['wcc_seo_keyword_research_user_limit'] ) ) {

  $_SESSION['wcc_seo_keyword_research_user_limit'] = $wcc_seo_keyword_research_limit_search;

}

if ( $wcc_seo_keyword_research_set_limit_value == 'No' ) {

  unset( $_SESSION['wcc_seo_keyword_research_user_limit'] );

}

if ( $wcc_seo_keyword_research_set_non_repeat_value == 'No' ) {

  unset( $_SESSION['wcc_seo_keyword_research_user_valid'] );

}

if ( $wcc_seo_keyword_research_email_radio == 'custom' ) {

  $wcc_seo_keyword_research_email_radio_cla = 'active';

  $wcc_seo_keyword_research_email_cus_status = 'active';

} else {

  $wcc_seo_keyword_research_email_radio_cla = '';

  $wcc_seo_keyword_research_email_cus_status = 'none';

}

$wcc_seo_keyword_research_share_status = 'close';

$view_option = 'current';

$wcc_seo_keyword_research_keyword = "";

$result_header = "";

$full_result = array();

$view_option = "";

$wcc_seo_keyword_research_csv_export_cla = "";

$wcc_seo_keyword_research_filters = "";

$wcc_seo_keyword_research_showmore_cla = "";



?>



<?php if(isset($_SESSION['e_msg']) && $_SESSION['e_msg']){ ?>
<div id="setting-error-settings_updated" class="notice notice-success is-dismissible"> 
  <p><strong>Success : <?php echo esc_html($_SESSION['e_msg']); ?></strong></p>
</div>
<br/>

<?php unset($_SESSION['e_msg']); } ?>



  <div class="wcc-mt-3">

    <div class="tab wcc-keyword-tab">

      <div style="text-align: center; padding-bottom: 20px">

        <img src="<?php echo esc_url(plugins_url( '../img/logo.svg', __FILE__ )) ?>" width="150px" title="WCC SEO Keyword Research">

        <h4 style="margin-bottom: 0px">WCC SEO Keyword Research</h4>
        <h4 style="margin-bottom: 0px">Version 1.0.0</h4>
        <p>Powered By <a href="https://www.virtualbrix.com" style="color: #3f51b5;font-weight: 700;" title="Go to VirtualBrix's Website" target="_blank">VirtualBrix</a></p>

      </div>

      <hr/>

      <div class="vb_tab_btn">

        <button id="defaultOpen" title="Search Keyword" type="button" class="tablinks" onclick="openCity(event, 'search_keyword_tab')"><img class="vb_na_img" src="<?php echo esc_url(plugins_url( '../img/list.svg', __FILE__ )) ?>"><img class="vb_a_img" src="<?php echo esc_url(plugins_url( '../img/list-h.svg', __FILE__ )) ?>"> Search Keyword</button>

        <button title="Settings" type="button" class="tablinks" onclick="openCity(event, 'general_tab')"><img class="vb_na_img" src="<?php echo esc_url(plugins_url( '../img/setting-lines.png', __FILE__ )) ?>"><img class="vb_a_img" src="<?php echo esc_url(plugins_url( '../img/setting-lines-h.svg', __FILE__ )) ?>"> Settings</button>

      </div>

    </div>
    
    <div id="general_tab" class="tabcontent">

      <div class="wcc-full">

        <form action="" method="post" class="wpforms-form">

          <table class="wp-list-table wcc-keywords-table"  style="float: left;width: 100%"> 

            <tbody>


              <tr>

                <td width="200"><label class="form-label" for="wcc_seo_keyword_research_plugin_status">Set the number of results:</label></td>

                <td>

                  <input type="number" name="wcc_seo_keyword_research_result_number" id="wcc_seo_keyword_research_result_number" class="form-control" value="<?php echo esc_attr($wcc_seo_keyword_research_result_number); ?>" min="10" max="1000">

                </td>

              </tr>

              <tr>

                <td width="200"><label class="form-label" for="wcc_seo_keyword_research_plugin_status">Set the search limitation per user:</label></td>

                <td>

                  <input type="checkbox" name="wcc_seo_keyword_research_set_limit" id="wcc_seo_keyword_research_set_limit" class="wcc_seo_keyword_research_set_limit" <?php echo esc_attr($wcc_seo_keyword_research_set_limit ? esc_attr('checked') : ''); ?>>

                  <input type="hidden" name="wcc_seo_keyword_research_set_limit_value" id="wcc_seo_keyword_research_set_limit_value" class="wcc_seo_keyword_research_set_limit_value" value="<?php echo esc_attr($wcc_seo_keyword_research_set_limit_value); ?>">

                </td>

              </tr>

              <tr class="wcc_seo_keyword_research_form_row_sub" style="<?php echo esc_attr($wcc_seo_keyword_research_set_limit ? '' : 'display: none'); ?>">

                <td width="200"><label class="form-label" for="wcc_seo_keyword_research_plugin_status">Limit of search:</label></td>

                <td>

                  <input type="number" name="wcc_seo_keyword_research_limit_search" id="wcc_seo_keyword_research_limit_search" class="wcc_seo_keyword_research_limit_search form-control" value="<?php echo esc_attr($wcc_seo_keyword_research_limit_search); ?>" min="1">

                </td>

              </tr>

              <tr>

                <td width="200"><label class="form-label" for="wcc_seo_keyword_research_plugin_status">Set non-repeat user form input:</label></td>

                <td>

                  <input type="checkbox" name="wcc_seo_keyword_research_set_non_repeat_user_form" id="wcc_seo_keyword_research_set_non_repeat_user_form" class="wcc_seo_keyword_research_set_non_repeat_user_form" <?php echo esc_attr($wcc_seo_keyword_research_set_non_repeat_user_form ? esc_attr('checked') : ''); ?>>

                  <input type="hidden" name="wcc_seo_keyword_research_set_non_repeat_value" id="wcc_seo_keyword_research_set_non_repeat_value" class="wcc_seo_keyword_research_set_non_repeat_value" value="<?php echo esc_attr($wcc_seo_keyword_research_set_non_repeat_value); ?>">

                </td>

              </tr>

              <tr>

                <td width="200"><label class="form-label" for="wcc_seo_keyword_research_plugin_status">Set Email option:</label></td>

                <td>                

                  <div class="wcc_seo_keyword_research_radio_group">

                    <input type="radio" id="wcc_seo_keyword_research_email_table" name="wcc_seo_keyword_research_email_radio" class="wcc_seo_keyword_research_email_radio" value="html" <?php echo esc_attr(( !$wcc_seo_keyword_research_email_radio || $wcc_seo_keyword_research_email_radio == 'html' ) ? esc_attr('checked') : ''); ?>>

                    <label for="wcc_seo_keyword_research_email_table">Html Table</label><br>

                    <input type="radio" id="wcc_seo_keyword_research_email_csv" name="wcc_seo_keyword_research_email_radio" class="wcc_seo_keyword_research_email_radio" value="csv" <?php echo esc_attr(( $wcc_seo_keyword_research_email_radio == 'csv' ) ? esc_attr('checked') : ''); ?>>

                    <label for="wcc_seo_keyword_research_email_csv">CSV Attachment</label><br>

                    <input type="radio" id="wcc_seo_keyword_research_email_xls" name="wcc_seo_keyword_research_email_radio" class="wcc_seo_keyword_research_email_radio" value="xls" <?php echo esc_attr(( $wcc_seo_keyword_research_email_radio == 'xls' ) ? esc_attr('checked') : ''); ?>>

                    <label for="wcc_seo_keyword_research_email_xls">XLSX Attachment</label><br>

                    <input type="radio" id="wcc_seo_keyword_research_email_custom" name="wcc_seo_keyword_research_email_radio" class="wcc_seo_keyword_research_email_radio" value="custom" <?php echo esc_attr(( $wcc_seo_keyword_research_email_radio == 'custom' ) ? esc_attr('checked') : ''); ?>>

                    <label for="wcc_seo_keyword_research_email_custom">User Option</label>

                  </div>

                </td>

              </tr>

              <tr>

                <td width="200"></td>

                <td>

                  <input type="submit" name="save_settig" value="Save Settings" title="Save Settings" class="wcc-keywords-btn">

                </td>

              </tr>

            </tbody>

          </table>

        </form>

      </div>

    </div>

    <div id="search_keyword_tab" class="tabcontent">

      <?php require_once("plugin.php"); ?>

    </div>

    <div id="searched_user_data_tab" class="tabcontent">

    



        <h1><?php _e( '<strong>Searched User Data</strong>', 'wcc-seo-keyword-research' ); ?></h1>



        <hr />



        <table id="srst_user_data_table" class="wp-list-table widefat fixed striped srst_user_data_table">



          <thead>



            <tr>



              <th width="5%"><?php _e( 'ID', 'wcc-seo-keyword-research' ); ?></th>



              <th width="10%"><?php _e( 'Search Keyword', 'wcc-seo-keyword-research' ); ?></th>



              <th width="12%"><?php _e( 'First Name', 'wcc-seo-keyword-research' ); ?></th>



              <th width="12%"><?php _e( 'Last Name', 'wcc-seo-keyword-research' ); ?></th>



              <th width="12%"><?php _e( 'E-Mail', 'wcc-seo-keyword-research' ); ?></th>



              <th width="10%"><?php _e( 'Phone', 'wcc-seo-keyword-research' ); ?></th>



              <th width="15%"><?php _e( 'Website', 'wcc-seo-keyword-research' ); ?></th>



              <th width="12%"><?php _e( 'Business Name', 'wcc-seo-keyword-research' ); ?></th>       



              <th width="12%"><?php _e( 'Date/Time', 'wcc-seo-keyword-research' ); ?></th>



            </tr>



          </thead>



          <tbody>



          <?php



            global $wpdb;







            $table_name = $wpdb->prefix . 'wcc_seo_keyword_research_user_data';



            $data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY ID DESC");







            foreach ($data as $key => $value) {



            ?>



            <tr>



              <td><?php echo esc_html($value->id); ?></td>



              <td><?php echo esc_html($value->keyword); ?></td>



              <td><?php echo esc_html($value->fname); ?></td>



              <td><?php echo esc_html($value->lname); ?></td>



              <td><?php echo esc_html($value->email); ?></td>



              <td><?php echo esc_html($value->phone); ?></td>



              <td><?php echo esc_html($value->website); ?></td>



              <td><?php echo esc_html($value->business); ?></td>



              <td><?php echo esc_html($value->date); ?></td>



            </tr>



            <?php



            }



          ?>



          </tbody>



        </table>





    </div>  

    <div class="vb_clear_both"></div>

  </div>



<script>

function openCity(evt, cityName) {

  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");

  for (i = 0; i < tabcontent.length; i++) {

    tabcontent[i].style.display = "none";

  }

  tablinks = document.getElementsByClassName("tablinks");

  for (i = 0; i < tablinks.length; i++) {

    tablinks[i].className = tablinks[i].className.replace(" active", "");

  }

  document.getElementById(cityName).style.display = "block";

  evt.currentTarget.className += " active";

}

// Get the element with id="defaultOpen" and click on it

document.getElementById("defaultOpen").click();

/**

 * SEMRush Search Tool admin custom Script

 */

jQuery(document).ready(function($) {

  function copyToClipboard( element ) {

    var $temp = $("<input>");

    $("body").append($temp);

    $temp.val(element).select();

    document.execCommand("copy");

    $temp.remove();

  }

  $('#wcc_seo_keyword_research_shortcode').on('click', function() {

    var content = $(this).val();

        var element = $('.wcc_seo_keyword_research_short_text').text();

        copyToClipboard( element );

        $(this).val("Copied!");

        setTimeout(function() {

          $('#wcc_seo_keyword_research_shortcode').val(content);

      }, 1200);

  });

  $('#wcc_seo_keyword_research_set_limit').on('click', function() {

    if ( $(this).is( ":checked" ) ) {

      $('#wcc_seo_keyword_research_set_limit_value').val('Yes');

      $('.wcc_seo_keyword_research_form_row_sub').show();

    } else {

      $('#wcc_seo_keyword_research_set_limit_value').val('');

      $('.wcc_seo_keyword_research_form_row_sub').hide();

    }

  });

  $('#wcc_seo_keyword_research_set_non_repeat_user_form').on('click', function() {

    if ( $(this).is( ":checked" ) ) {

      $('#wcc_seo_keyword_research_set_non_repeat_value').val('Yes');

    } else {

      $('#wcc_seo_keyword_research_set_non_repeat_value').val('');

    }

  });

  $("#wcc_seo_keyword_research_result_number").on("change paste keyup", function() {

    var current = $.trim( $(this).val() );

    if ( current < 0 ){

      $("#wcc_seo_keyword_research_result_number").val(10);

    }

    if ( current > 1000 ){

      $("#wcc_seo_keyword_research_result_number").val(1000);

    }

  });

});

</script>

