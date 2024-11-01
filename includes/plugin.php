<?php

	

	if ( isset( $_GET['share'] ) ) {

		$wcc_seo_keyword_research_share_status = 'open';

		$code = sanitize_text_field($_GET['share']);

		$wcc_seo_keyword_research_file_path =  plugin_dir_path( __FILE__ ) .'../share/'. $code . '.txt';

		$wcc_seo_keyword_research_read_file = fopen( $wcc_seo_keyword_research_file_path, 'r' ) or die( 'Unable to open file!' );

		$wcc_seo_keyword_research_initial_data = fread( $wcc_seo_keyword_research_read_file, filesize( $wcc_seo_keyword_research_file_path ) );

		$wcc_seo_keyword_research_convert = base64_decode( urldecode( $wcc_seo_keyword_research_initial_data ) );

		$wcc_seo_keyword_research_array = json_decode( $wcc_seo_keyword_research_convert );

		

		fclose( $wcc_seo_keyword_research_read_file );

		$wcc_seo_keyword_research_keyword = $wcc_seo_keyword_research_array->keyword;

		$result_header = $wcc_seo_keyword_research_array->result_header;

		$full_result = $wcc_seo_keyword_research_array->full_result;

		$view_option = $wcc_seo_keyword_research_array->option;

		$wcc_seo_keyword_research_filters = $wcc_seo_keyword_research_array->filter;

		$table .= '<table id="wcc_seo_keyword_research_valid_search_result" class="wcc_seo_keyword_research_search_result"><thead><tr><th>#</th>';

		foreach ( $result_header as $key => $value ) {

			$table .= '<th>' . trim( $value ) . '</th>';

		}

		$table .= '</tr></thead><tbody>';

		$wcc_seo_keyword_research_csv_export_cla = 'active';		

		foreach ( $full_result as $key => $value ) {

			$disabled_cla = $disable_cpc_cla = $disable_com_cla = $disable_nor_cla = $disable_rel_cla = '';

			if ( $view_option == 'current' ) {

				if ( isset($wcc_seo_keyword_research_filters->disabled) && in_array( $key, $wcc_seo_keyword_research_filters->disabled ) ) {

					$disabled_cla = 'disabled';

				}

				if ( isset($wcc_seo_keyword_research_filters->disable_cpc) && in_array( $key, $wcc_seo_keyword_research_filters->disable_cpc ) ) {

					$disable_cpc_cla = 'disable_cpc';

				}

				if ( isset($wcc_seo_keyword_research_filters->disable_com) && in_array( $key, $wcc_seo_keyword_research_filters->disable_com ) ) {

					$disable_com_cla = 'disable_com';

				}

				if ( isset($wcc_seo_keyword_research_filters->disable_nor) && in_array( $key, $wcc_seo_keyword_research_filters->disable_nor ) ) {

					$disable_nor_cla = 'disable_nor';

				}

				if ( isset($wcc_seo_keyword_research_filters->disable_rel) && in_array( $key, $wcc_seo_keyword_research_filters->disable_rel ) ) {

					$disable_rel_cla = 'disable_rel';

				}

			}

			$table .= '<tr id="item_' . $key . '" item_num="item_' . $key . '" dis_search="' . $disabled_cla . '" dis_cpc="' . $disable_cpc_cla . '" dis_com="' . $disable_com_cla . '" dis_nor="' . $disable_nor_cla . '" dis_rel="' . $disable_rel_cla . '"><td><div class="num_col"><span>' . str_pad( ( $key + 1 ), 3, '0', STR_PAD_LEFT ) . '</span></div></td>';

			foreach ( $value as $sub_key => $item ) {

				$table .= '<td><div><span>' . trim( $item ) . '</span></div></td>';

			}

			$table .= '</tr>';

		}

		$table .= '</tbody></table>';

	}

	if ( $_POST && $keyword != '' ) {

		$ch = curl_init();

		$url = 'https://api.semrush.com/?type=phrase_related&key=' . $api_key . '&phrase=' . $keyword . '&export_columns=Ph,Nq,Cp,Co,Nr,Rr&database=us&display_limit=' . $wcc_seo_keyword_research_result_number . '&display_sort=nq_desc&display_filter=%2B|Nq|Gt|' . $wcc_seo_keyword_research_result_number;

	    

	    $request = array(

		);



		$api_response = wp_remote_post($url,array(

			    'method'      => 'POST',

			    'timeout'     => 45,

			    'redirection' => 5,

			    'httpversion' => '1.0',

			    'blocking'    => true,

			    'headers'     => array(),

			    'body'        => $request,

			    'cookies'     => array()

		));



		$data = isset($api_response['body']) && $api_response['body'] ? $api_response['body'] : array();







	    $extract_data = explode( "\n", $data );

	    $count = count( $extract_data );

		foreach ( $extract_data as $key => $value ) {

			$result = explode( ";", $value );

			if ( $key == 0 ) {

				$result_header = $result;

			} else { 

				/*if ( $key >= ( $count - 10 ) ) {

				}*/

				array_push( $full_result, $result );

				array_push( $initial_result, $result );

			}

		}

		$_SESSION['wcc_seo_keyword_research_result_header'] = $result_header;

		$_SESSION['wcc_seo_keyword_research_full_result'] = $full_result;

		if ( true || isset( $_SESSION['wcc_seo_keyword_research_user_valid'] ) && $_SESSION['wcc_seo_keyword_research_user_valid'] == 'valid' && $wcc_seo_keyword_research_set_non_repeat_value == 'Yes' ) {

			$table .= '<table id="wcc_seo_keyword_research_valid_search_result" class="wcc_seo_keyword_research_search_result"><thead><tr><th>#</th>';

			foreach ( $result_header as $key => $value ) {

				$table .= '<th>' . trim( $value ) . '</th>';

			}

			$table .= '</tr></thead><tbody>';

			$wcc_seo_keyword_research_csv_export_cla = 'active';

			foreach ( $full_result as $key => $value ) {

				$table .= '<tr id="item_' . $key . '" item_num="item_' . $key . '"><td><div class="num_col"><span>' . str_pad( ( $key + 1 ), 3, '0', STR_PAD_LEFT ) . '</span></div></td>';

				foreach ( $value as $sub_key => $item ) {

					$table .= '<td><div><span>' . trim( $item ) . '</span></div></td>';

				}

				$table .= '</tr>';

			}

		} else {

			$table .= '<table id="wcc_seo_keyword_research_search_result" class="wcc_seo_keyword_research_search_result"><thead><tr><th>#</th>';

			foreach ( $result_header as $key => $value ) {

				$table .= '<th>' . trim( $value ) . '</th>';

			}

			$table .= '</tr></thead><tbody>';

			$wcc_seo_keyword_research_showmore_cla = 'active';

			foreach ( $initial_result as $key => $value ) {

				$table .= '<tr item_num="item_' . $key . '"><td><div class="num_col"><span>' . str_pad( ( $key + 1 ), 3, '0', STR_PAD_LEFT ) . '</span></div></td>';

				foreach ( $value as $sub_key => $item ) {

					$table .= '<td><div><span>' . trim( $item ) . '</span></div></td>';

				}

				$table .= '</tr>';

			}	

		}

		$table .= '</tbody></table>';

		if ( isset( $_SESSION['wcc_seo_keyword_research_user_limit'] ) && $wcc_seo_keyword_research_set_limit_value == 'Yes' ) {

			$_SESSION['wcc_seo_keyword_research_user_limit'] -= 1;

		}

	}

?>

<div id="wcc_seo_keyword_research_wrapper" class="wcc-full wcc-keyword-pad-0-15">

	<div id="wcc_seo_keyword_research_header" class="wcc-keyword-header-1">

		<h2>Keyword Search Tool</h2>

	</div>

	<div id="wcc_seo_keyword_research_main">

		<form id="wcc_seo_keyword_research_search_form" name="wcc_seo_keyword_research_search_form" class="wcc_seo_keyword_research_search_form" method="POST">

			<input type="text" name="wcc_seo_keyword_research_keyword" id="wcc_seo_keyword_research_keyword" class="form-control wcc_seo_keyword_research_keyword" value="<?php echo esc_attr($wcc_seo_keyword_research_keyword ? $wcc_seo_keyword_research_keyword : (isset($_POST['wcc_seo_keyword_research_keyword']) ? trim( $_POST['wcc_seo_keyword_research_keyword'] ) : ""))  ?>" placeholder="Enter keyword" valid_disable="<?php echo esc_attr( ( isset( $_SESSION['wcc_seo_keyword_research_user_limit'] ) && isset($_SESSION['wcc_seo_keyword_research_user_limit']) <= 0 ) ? 'Yes' : 'No' )  ?>" valid_number="<?php echo esc_attr(isset($_SESSION['wcc_seo_keyword_research_user_limit']) ? $_SESSION['wcc_seo_keyword_research_user_limit'] : "") ?>" <?php echo esc_attr($wcc_seo_keyword_research_keyword ? 'readonly' : '')  ?>>

			<input type="submit" name="" class="" id="wcc_seo_keyword_research_submit" value="Search Keyword" <?php echo esc_attr($wcc_seo_keyword_research_keyword ? 'disabled' : '')  ?>>

		</form>

		<div class="wcc_seo_keyword_research_table_wrapper" view_status="<?php echo esc_attr($view_option) ?>" share_status="<?php echo esc_attr($wcc_seo_keyword_research_share_status) ?>">

			<div class="function_btns <?php echo esc_attr($wcc_seo_keyword_research_csv_export_cla) ?>">

				<div class="results_btns">

					<button class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_share_results" title="Share Results">Share Results</button>

					<button class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_email_results" title="E-Mail Results">E-Mail Results</button>

					<button class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_search_results" title="Search">Search</button>

					<button class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_filter_results" title="Filter">Filter</button>

				</div>


			</div>

				<div id="wcc_seo_keyword_research_email_box" class="wcc_seo_keyword_research_functional_area" status="close" user_cus="<?php echo esc_attr($wcc_seo_keyword_research_email_cus_status) ?>">

				<div id="wcc_seo_keyword_research_user_radio" class="<?php echo esc_attr($wcc_seo_keyword_research_email_radio_cla) ?>">

					<input type="radio" id="wcc_seo_keyword_research_email_cus_html" name="wcc_seo_keyword_research_email_cus" class="wcc_seo_keyword_research_email_cus" value="html" checked>

					<label for="wcc_seo_keyword_research_email_cus_html">Html Table</label>

					<input type="radio" id="wcc_seo_keyword_research_email_cus_csv" name="wcc_seo_keyword_research_email_cus" class="wcc_seo_keyword_research_email_cus" value="csv">

					<label for="wcc_seo_keyword_research_email_cus_csv">CSV</label>

					<input type="radio" id="wcc_seo_keyword_research_email_cus_xls" name="wcc_seo_keyword_research_email_cus" class="wcc_seo_keyword_research_email_cus" value="xls">

					<label for="wcc_seo_keyword_research_email_cus_xls">XLSX</label>

				</div>

				<input type="text" name="wcc_seo_keyword_research_send_email" id="wcc_seo_keyword_research_send_email" class="wcc_seo_keyword_research_send_email form-control">

				<button class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_send_email_btn" title="Send">Send</button>

			</div>

			<div class="mail_notification">
				
			</div>

			<div id="wcc_seo_keyword_research_shared_url" class="wcc_seo_keyword_research_functional_area">

				<h4>Copy and share the following URL:</h4>

				<div class="wcc_seo_keyword_research_share_url"><a href=""></a></div>

			</div>

			<div id="wcc_seo_keyword_research_search_box" class="wcc_seo_keyword_research_functional_area" status="close">

				<input type="text" name="wcc_seo_keyword_research_search_input" id="wcc_seo_keyword_research_search_input" class="wcc_seo_keyword_research_search_input form-control">

			</div>

			<div id="wcc_seo_keyword_research_filter_box" class="wcc_seo_keyword_research_functional_area" status="close">

				<div id="wcc_seo_keyword_research_filter_wrapper">

					<div id="wcc_seo_keyword_research_cpc_filter"></div>

					<div id="wcc_seo_keyword_research_com_filter"></div>

					<div id="wcc_seo_keyword_research_nor_filter"></div>

					<div id="wcc_seo_keyword_research_rel_filter"></div>

				</div>

			</div>

			<div class="function_btns <?php echo esc_attr($wcc_seo_keyword_research_csv_export_cla) ?>" style="padding-top: 10px;	">

				<div class="export_btns">

					<div id="wcc_seo_keyword_research_view_radio" style="text-align: right;">

						<input type="radio" id="wcc_seo_keyword_research_current_view" name="wcc_seo_keyword_research_view_cus" class="wcc_seo_keyword_research_view_cus" value="current" checked>

						<label for="wcc_seo_keyword_research_current_view">Current View</label>

						<input type="radio" id="wcc_seo_keyword_research_full_view" name="wcc_seo_keyword_research_view_cus" class="wcc_seo_keyword_research_view_cus" value="full">

						<label for="wcc_seo_keyword_research_full_view">Full View</label>

					</div>

				</div>

			</div>

			<hr/>

			<div class="function_btns <?php echo esc_attr($wcc_seo_keyword_research_csv_export_cla) ?>" style="padding-top: 10px;	">

				<h3 style="text-align: left;">Export Data</h3>

			</div>

			<div class="function_btns <?php echo esc_attr($wcc_seo_keyword_research_csv_export_cla) ?>" style="padding-top: 10px;	">

				<div class="export_btns">

					<a class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_print_result" title="Print Results">Print Results</a>

					<a class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_csv_export" title="CSV Export">CSV Export</a>

					<a class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_xls_export" title="XLS Export">XLS Export</a>

					<a class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_pdf_export" title="PDF Export">PDF Export</a>

					<a class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_txt_export" title="Text Export" download="Search Result.txt">Text Export</a>

					<a class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_json_export" title="Json Export" download="Search Result.json">Json Export</a>

					<a class="btn wcc-keywords-btn-sm" id="wcc_seo_keyword_research_xml_export" title="XML Export" download="Search Result.xml">XML Export</a>

				</div> 

			</div>

		

			<div class="table_functions">

				

			</div>

			<?php _e( $table, 'wcc-seo-keyword-research' ); ?>

		</div>

	</div>

	<div id="wcc_seo_keyword_research_footer">

		<span id="wcc_seo_keyword_research_blank" wcc_seo_keyword_research_url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" wcc_seo_keyword_research_file_url="<?php echo esc_url(plugins_url("/", __FILE__ )); ?>"></span>

		<iframe id="wcc_seo_keyword_research_xls_blank" home_path="<?php echo esc_url(get_home_url()) ?>"></iframe>

	</div>

	<div id="wcc_seo_keyword_research_modal">

		<div id="wcc_seo_keyword_research_modal_overaly"></div>

		<div id="wcc_seo_keyword_research_modal_main">

			<div id="wcc_seo_keyword_research_modal_inner">

				<h3>User Information</h3>

				<div class="wcc_seo_keyword_research_user_item wcc_seo_keyword_research_user_item_fname">

					<label class="wcc_seo_keyword_research_user_label">First Name <span class="wcc_seo_keyword_research_ast">*</span></label>

					<input type="text" id="wcc_seo_keyword_research_user_fname" class="wcc_seo_keyword_research_user_input form-control wcc_seo_keyword_research_user_fname">

					<span class="wcc_seo_keyword_research_input_error">Please enter your first name</span>

				</div>

				<div class="wcc_seo_keyword_research_user_item wcc_seo_keyword_research_user_item_lname">

					<label class="wcc_seo_keyword_research_user_label">Last Name <span class="wcc_seo_keyword_research_ast">*</span></label>

					<input type="text" id="wcc_seo_keyword_research_user_lname" class="wcc_seo_keyword_research_user_input form-control wcc_seo_keyword_research_user_lname">

					<span class="wcc_seo_keyword_research_input_error">Please enter your last name</span>

				</div>

				<div class="wcc_seo_keyword_research_user_item wcc_seo_keyword_research_user_item_email">

					<label class="wcc_seo_keyword_research_user_label">E-Mail <span class="wcc_seo_keyword_research_ast">*</span></label>

					<input type="text" id="wcc_seo_keyword_research_user_email" class="wcc_seo_keyword_research_user_input form-control wcc_seo_keyword_research_user_email">

					<span class="wcc_seo_keyword_research_input_error">Please enter valid email address</span>

				</div>

				<div class="wcc_seo_keyword_research_user_item wcc_seo_keyword_research_user_item_phone">

					<label class="wcc_seo_keyword_research_user_label">Phone Number <span class="wcc_seo_keyword_research_ast">*</span></label>

					<input type="text" id="wcc_seo_keyword_research_user_phone" class="wcc_seo_keyword_research_user_input form-control wcc_seo_keyword_research_user_phone">

					<span class="wcc_seo_keyword_research_input_error">Please enter valid phone number</span>

				</div>

				<div class="wcc_seo_keyword_research_user_item wcc_seo_keyword_research_user_item_business">

					<label class="wcc_seo_keyword_research_user_label">Business Name <span class="wcc_seo_keyword_research_ast">*</span></label>

					<input type="text" id="wcc_seo_keyword_research_user_business" class="wcc_seo_keyword_research_user_input form-control wcc_seo_keyword_research_user_business">

					<span class="wcc_seo_keyword_research_input_error">Please enter your business name</span>

				</div>

				<div class="wcc_seo_keyword_research_user_item wcc_seo_keyword_research_user_item_website">

					<label class="wcc_seo_keyword_research_user_label">Business Website Address <span class="wcc_seo_keyword_research_ast">*</span></label>

					<input type="text" id="wcc_seo_keyword_research_user_website" class="wcc_seo_keyword_research_user_input form-control wcc_seo_keyword_research_user_website">

					<span class="wcc_seo_keyword_research_input_error">Please enter valid website address</span>

				</div>

				<div class="wcc_seo_keyword_research_user_item_submit">

					<a id="wcc_seo_keyword_research_user_submit" title="Submit">Submit</a>

				</div>

				<img id="wcc_seo_keyword_research_modal_close" src="<?php echo esc_url(plugins_url('../img/tool/close.svg',__FILE__ )) ?>" alt="">

			</div>

		</div>

	</div>

</div>