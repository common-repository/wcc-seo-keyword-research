<?php

/**

 * Return full search result in order to generate xls, pdf file

 */



$keyword 	= sanitize_text_field($_POST['key']);

$option 	= sanitize_text_field($_POST['option']);

$data 		= sanitize_text_field($_POST['data']);





require_once( 'xlsxwriter.class.php' );



if ( !session_id() ) {

    session_start();

}



$export_data = array();



$export_data['result_header'] = $_SESSION['wcc_keyword_research_result_header'];

array_unshift( $export_data['result_header'], '#');



$export_data['full_result'] = $_SESSION['wcc_keyword_research_full_result'];



$writer = new XLSXWriter();



$writer->writeSheetRow( 'Sheet1', $export_data['result_header'] );

foreach ($export_data['full_result'] as $key => $value) {

	if ( $option == 'full' || in_array( $key, $data ) ) {

		$key = str_pad( ( $key + 1 ), 3, '0', STR_PAD_LEFT );

		array_unshift( $value, $key );

		$writer->writeSheetRow( 'Sheet1', $value );

	}

}



$file_name = 'Excel-' . uniqid() . '.xlsx';

$writer->writeToFile( plugin_dir_path( __FILE__ ).'../export/' . $file_name );





echo esc_attr($file_name);