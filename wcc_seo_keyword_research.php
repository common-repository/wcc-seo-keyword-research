<?php
/**
* Plugin Name: WCC SEO Keyword Research
* Description: Keyword Research Plugin by We Connect Code.
* Version: 1.0.0
* Author: WeConnectCode
**/
if ( ! function_exists( 'wskr_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wskr_fs() {
        global $wskr_fs;
        if ( ! isset( $wskr_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';
            $wskr_fs = fs_dynamic_init( array(
                'id'                  => '8536',
                'slug'                => 'wcc-seo-keyword-research',
                'type'                => 'plugin',
                'public_key'          => 'pk_957de3915d72023072d0ed161136a',
                'is_premium'          => true,
                'is_premium_only'     => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'trial'               => array(
                    'days'               => 7,
                    'is_require_payment' => false,
                ),
                'menu'                => array(
                    'support'        => false,
                ),
                // Set the SDK to work in a sandbox mode (for development & testing).
                // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
                'secret_key'          => 'sk_Sx3.EcD(*WHF[.qt?s4]Zxl.vWHSL',
            ) );
        }
        return $wskr_fs;
    }
    // Init Freemius.
    wskr_fs();
    // Signal that SDK was initiated.
    do_action( 'wskr_fs_loaded' );
}
global $wcc_seo_keyword_research_db_version;
$wcc_seo_keyword_research_db_version = '1.0';
register_activation_hook( __FILE__, 'wcc_seo_keyword_research_plugin_install' );
register_deactivation_hook( __FILE__, "wcc_seo_keyword_research_plugin_deactivated");

function wcc_seo_keyword_research_plugin_update_db_check() {
    global $wcc_seo_keyword_research_db_version;
    if ( get_site_option( 'wcc_seo_keyword_research_db_version' ) != $wcc_seo_keyword_research_db_version ) {
        wcc_seo_keyword_research_plugin_install();
    }
}
function wcc_seo_keyword_research_plugin_install() {
	global $wpdb;

	global $wcc_seo_keyword_research_db_version;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	add_option( 'wcc_seo_keyword_research_db_version', $wcc_seo_keyword_research_db_version );
}
function wcc_seo_keyword_research_plugin_deactivated()
{
	global $wpdb;
	
	delete_site_option( 'wcc_seo_keyword_research_api_key' );
	delete_site_option( 'wcc_seo_keyword_research_plugin_status' );
	delete_site_option( 'wcc_seo_keyword_research_result_number' );
	delete_site_option( 'wcc_seo_keyword_research_set_limit' );
	delete_site_option( 'wcc_seo_keyword_research_set_limit_value' );
	delete_site_option( 'wcc_seo_keyword_research_limit_search' );
	delete_site_option( 'wcc_seo_keyword_research_set_non_repeat_user_form' );
	delete_site_option( 'wcc_seo_keyword_research_set_non_repeat_value' );
	delete_site_option( 'wcc_seo_keyword_research_email_radio' );
}
add_action( 'plugins_loaded', 'wcc_seo_keyword_research_plugin_update_db_check' );
add_action('admin_menu', 'wcc_seo_keyword_research_review_main_menu');
add_action( 'wp_footer', 'wcc_seo_keyword_research_footer_scripts' );
function wcc_seo_keyword_research_footer_scripts(){  ?>
  <?php
}
	
function wcc_seo_keyword_research_review_main_menu(){
    add_menu_page( 'WCC SEO Keyword Research', 'WCC SEO Keyword Research', 'manage_options', 'wcc-seo-keyword-research', 'wcc_seo_keyword_research_menu_fun');
    
}
function wcc_seo_keyword_research_menu_fun(){
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_api'])) {
		$url = "https://crmentries.com/marketing_plugins/api/addWebsite";
		$api_key = get_option("wcc_seo_keyword_research_api_key");
		$request = array(
			"website" => get_home_url(),
			"plugin" => "WCC SEO Keyword Research",
			"first_name" => (isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : ""),
			"last_name" => (isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : ""),
			"email" => (isset($_POST['email']) ? sanitize_email($_POST['email']) : ""),
			"contact_no" => (isset($_POST['contact_no']) ? sanitize_text_field($_POST['contact_no']) : ""),
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
		$api_response = isset($api_response['body']) && $api_response['body'] ? json_decode($api_response['body'],1) : array();

		if(isset($api_response['success']) && $api_response['success']){
			$_SESSION['wcc_seo_keyword_research_api_key_success'] = "1";
			update_option("wcc_seo_keyword_research_api_key",$api_response['success']);
		}else{
			$_SESSION['error_message'] = "Something Wrong Try Again.";
		}
	}

	if(isset($_SESSION['wcc_seo_keyword_research_api_key_success'])){
		unset($_SESSION['wcc_seo_keyword_research_api_key_success']);
		require_once(plugin_dir_path( __FILE__ ) .'includes/api_key_success.php');
	}else{
		if(isset($_SESSION['wcc_seo_keyword_research_api_key_checked']) && $_SESSION['wcc_seo_keyword_research_api_key_checked']){
			require_once(plugin_dir_path( __FILE__ ) .'includes/setting.php');
		}else{
			$url = "https://crmentries.com/marketing_plugins/api/getWebsite";
			$api_key = get_option("wcc_seo_keyword_research_api_key");
			$request = array(
				"website" => get_home_url(),
				"api_key" => $api_key,
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
			$api_response = isset($api_response['body']) && $api_response['body'] ? json_decode($api_response['body'],1) : array();
			if(isset($api_response['success']) && $api_response['success']){
				$_SESSION['wcc_seo_keyword_research_api_key_checked'] = 1;
		    	require_once(plugin_dir_path( __FILE__ ) .'includes/setting.php');
			}else{
		    	require_once(plugin_dir_path( __FILE__ ) .'includes/getApiKey.php');
			}
		}
	}
}

function wcc_seo_keyword_research_start_session() {
	if(!session_id()) {
	session_start();
	}
}
add_action('init', 'wcc_seo_keyword_research_start_session', 1);


function wcc_seo_keyword_research_admin_enqueue($hook_suffix) {
    if($hook_suffix == 'toplevel_page_wcc-seo-keyword-research') {
        wp_register_style('wcc_seo_keyword_research_snippet_css', plugins_url('style.css',__FILE__ ));
	    wp_enqueue_style('wcc_seo_keyword_research_snippet_css');;
		wp_enqueue_script( 'wcc_seo_keyword_research_wNumb_js', plugins_url( 'js/wNumb.js', __FILE__ ));
		 wp_register_style('wcc_seo_keyword_research_fa_css', plugins_url('css/all.min.css',__FILE__ ));
	    wp_enqueue_style('wcc_seo_keyword_research_fa_css');
	    wp_register_style('wcc_seo_keyword_research_css', plugins_url('css/main.css',__FILE__ ));
	    wp_enqueue_style('wcc_seo_keyword_research_css');
	    wp_register_style('wcc_seo_keyword_research_nouislider_css', plugins_url('css/nouislider.min.css',__FILE__ ));
	    wp_enqueue_style('wcc_seo_keyword_research_nouislider_css');
	    wp_register_style('wcc_seo_keyword_research_sorter_default_css', plugins_url('css/sorter-default.min.css',__FILE__ ));
	    wp_enqueue_style('wcc_seo_keyword_research_sorter_default_css');
	    wp_register_style('wcc_seo_keyword_research_sorter_demo_css', plugins_url('css/sorter-demo.css',__FILE__ ));
	    wp_enqueue_style('wcc_seo_keyword_research_sorter_demo_css');
	    wp_enqueue_script( 'wcc_seo_keyword_research_html2pdf_js', plugins_url( 'js/html2pdf.bundle.min.js', __FILE__ ));
		wp_enqueue_script( 'wcc_seo_keyword_research_jquery_js', plugins_url( 'js/jquery.tablesorter.min.js', __FILE__ ));
		wp_enqueue_script( 'wcc_seo_keyword_research_nouislider_js', plugins_url( 'js/nouislider.min.js', __FILE__ ));
		wp_enqueue_script( 'wcc_seo_keyword_research_wcc_seo_keyword_research_js', plugins_url( 'js/custom.js', __FILE__ ));
		
    }
}
add_action('admin_enqueue_scripts', 'wcc_seo_keyword_research_admin_enqueue');

add_shortcode( 'wcc_seo_keyword_research', 'wcc_seo_keyword_research_func_direct' );
function wcc_seo_keyword_research_func_direct() {
	if ( ! is_admin() ) {
		$wcc_seo_keyword_research_plugin_status = get_option("wcc_seo_keyword_research_plugin_status");
		if($wcc_seo_keyword_research_plugin_status){
			$upload_dir   = wp_upload_dir();
			$wcc_seo_keyword_research_result_number = get_option("wcc_seo_keyword_research_result_number");
			$wcc_seo_keyword_research_set_limit = get_option("wcc_seo_keyword_research_set_limit");
			$wcc_seo_keyword_research_set_limit_value = get_option("wcc_seo_keyword_research_set_limit_value");
			$wcc_seo_keyword_research_limit_search = get_option("wcc_seo_keyword_research_limit_search");
			$wcc_seo_keyword_research_set_non_repeat_user_form = get_option("wcc_seo_keyword_research_set_non_repeat_user_form");
			$wcc_seo_keyword_research_set_non_repeat_value = get_option("wcc_seo_keyword_research_set_non_repeat_value");
			$wcc_seo_keyword_research_email_radio = get_option("wcc_seo_keyword_research_email_radio");
			$api_key = '88164459190acab973e2cd83b3256fe0';
			$keyword = '';
			$result_header = array();
			$full_result = array();
			$initial_result = array();
			$table = '';
			if ( isset($_POST['wcc_seo_keyword_research_keyword']) ) {
				$keyword = urlencode( strtolower( trim( strip_tags( sanitize_text_field($_POST['wcc_seo_keyword_research_keyword']) ) ) ) );
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
			require_once(plugin_dir_path( __FILE__ ) .'includes/plugin.php');
	    }
	}
}
add_action( 'wp_ajax_nopriv_wcc_seo_keyword_research_xls_export', "wcc_seo_keyword_research_xls_export_fun" );
add_action( 'wp_ajax_wcc_seo_keyword_research_xls_export', "wcc_seo_keyword_research_xls_export_fun" );
add_action( 'wp_ajax_nopriv_wcc_seo_keyword_research_email', "wcc_seo_keyword_research_email_fun" );
add_action( 'wp_ajax_wcc_seo_keyword_research_email', "wcc_seo_keyword_research_email_fun" );
add_action( 'wp_ajax_nopriv_wcc_seo_keyword_research_share', "wcc_seo_keyword_research_share_fun" );
add_action( 'wp_ajax_wcc_seo_keyword_research_share', "wcc_seo_keyword_research_share_fun" );
add_action( 'wp_ajax_nopriv_wcc_seo_keyword_research_user', "wcc_seo_keyword_research_user_fun" );
add_action( 'wp_ajax_wcc_seo_keyword_research_user', "wcc_seo_keyword_research_user_fun" );
function wcc_seo_keyword_research_email_fun(){
	$keyword	= sanitize_text_field($_POST['key']);
	$mail 		= sanitize_text_field($_POST['mail']);
	$option 	= sanitize_text_field($_POST['option']);
	$data 		= sanitize_text_field($_POST['data']);
	require_once(plugin_dir_path( __FILE__ ) .'includes/xlsxwriter.class.php');
	$wcc_seo_keyword_research_email_radio = get_option( 'wcc_seo_keyword_research_email_radio', 'html' );
	if ( $wcc_seo_keyword_research_email_radio == 'custom' ) {
		$user_option = sanitize_text_field($_POST['user']);
	}
	if ( !session_id() ) {
	    session_start();
	}
	$table = '';
	$result_header = $_SESSION['wcc_seo_keyword_research_result_header'];

	$result_header = array_map( 'esc_attr', $result_header );

	$full_result = $_SESSION['wcc_seo_keyword_research_full_result'];

	$full_result = array_map( 'esc_attr', $full_result );

	$email_address = sanitize_email( $mail );
	if ( $wcc_seo_keyword_research_email_radio == 'html' || ( $wcc_seo_keyword_research_email_radio == 'custom' && $user_option == 'html' ) ) {
		$table .= '<table cellpadding="5" cellspacing="0" border="1"><thead><tr><th>#</th>';
		foreach ( $result_header as $key => $value ) {
			$table .= '<th>' . trim( $value ) . '</th>';
		}
		$table .= '</tr></thead><tbody>';
		foreach ( $full_result as $key => $value ) {
			if ( $option == 'full' || in_array( $key, $data ) ) {
				$table .= '<tr><td><div><span>' . str_pad( ( $key + 1 ), 3, '0', STR_PAD_LEFT ) . '</span></div></td>';
				foreach ( $value as $sub_key => $item ) {
					$table .= '<td><div><span>' . trim( $item ) . '</span></div></td>';
				}
				$table .= '</tr>';
			}
		}
		$table .= '</tbody></table>';
	} else if ( $wcc_seo_keyword_research_email_radio == 'csv' || ( $wcc_seo_keyword_research_email_radio == 'custom' && $user_option == 'csv' ) ) {
		$file_name = 'CSV-' . uniqid() . '.csv';
		$full_file_name = '../email/' . $file_name;
		$email_file = fopen( $full_file_name, "w" ) or die( "Unable to open file!" );
		array_unshift( $result_header, '#' );
		fputcsv( $email_file, $result_header );
		foreach ($full_result as $key => $value) {
			if ( $option == 'full' || in_array( $key, $data ) ) {
				array_unshift( $value, $key + 1 );
				fputcsv( $email_file, $value );
			}
		}
		fclose( $email_file );
	} else if ( $wcc_seo_keyword_research_email_radio == 'xls' || ( $wcc_seo_keyword_research_email_radio == 'custom' && $user_option == 'xls' ) ) {
		$writer = new XLSXWriter();
		array_unshift( $_SESSION['wcc_seo_keyword_research_result_header'], '#');
		$writer->writeSheetRow( 'Sheet1', $_SESSION['wcc_seo_keyword_research_result_header'] );
		foreach ($_SESSION['wcc_seo_keyword_research_full_result'] as $key => $value) {
			if ( $option == 'full' || in_array( $key, $data ) ) {
				$key = str_pad( ( $key + 1 ), 3, '0', STR_PAD_LEFT );
				array_unshift( $value, $key );
				$writer->writeSheetRow( 'Sheet1', $value );
			}
		}
		$file_name_xls = 'Excel-' . uniqid() . '.xlsx';
		$writer->writeToFile( '../email/' . $file_name_xls );
	}
	if ( is_email( $email_address ) ) {
	    add_filter( 'wp_mail_content_type', function() { 
	        return "text/html"; 
	    } );
	    $subject = apply_filters( 'wcc_seo_keyword_research_email_subject', __( 'Search Result', 'ss-wcc_seo_keyword_research' ) );
	    if ( $wcc_seo_keyword_research_email_radio == 'html' || ( $wcc_seo_keyword_research_email_radio == 'custom' && $user_option == 'html' ) ) {
		    $message = apply_filters( 'wcc_seo_keyword_research_email_message', sprintf( __( 'Keyword: <strong style="padding-left: 3px;">%1$s</strong><br><br><br>%2$s', 'ss-wcc_seo_keyword_research' ), $keyword, $table ) );
		    $sent_mail = wp_mail( $email_address, $subject, $message );
		} else if ( $wcc_seo_keyword_research_email_radio == 'csv' || ( $wcc_seo_keyword_research_email_radio == 'custom' && $user_option == 'csv' ) ) {
			$message = apply_filters( 'wcc_seo_keyword_research_email_message', sprintf( __( 'Keyword: <strong style="padding-left: 3px;">%s</strong><br><br>', 'ss-wcc_seo_keyword_research' ), $keyword ) );
			$headers = '';
			$attachments = array( wcc_seo_keyword_research_PLUGIN_DIR . '/email/' . $file_name );
		    $sent_mail = wp_mail( $email_address, $subject, $message, $headers, $attachments );
		} else if ( $wcc_seo_keyword_research_email_radio == 'xls' || ( $wcc_seo_keyword_research_email_radio == 'custom' && $user_option == 'xls' ) ) {
			$message = apply_filters( 'wcc_seo_keyword_research_email_message', sprintf( __( 'Keyword: <strong style="padding-left: 3px;">%s</strong><br><br>', 'ss-wcc_seo_keyword_research' ), $keyword ) );
			$headers = '';
			$attachments = array( wcc_seo_keyword_research_PLUGIN_DIR . '/email/' . $file_name_xls );
		    $sent_mail = wp_mail( $email_address, $subject, $message, $headers, $attachments );
		}
	    if ( $sent_mail ) {
	        echo 'true';
	    } else {
	        echo 'There was a problem sending your email. Please try again or contact an admin.';
	    }
	} else {
	    echo 'Invalid Email address';
	}
	wp_die();
}
function wcc_seo_keyword_research_xls_export_fun(){
	require_once(plugin_dir_path( __FILE__ ) .'includes/wcc_seo_keyword_research-xls-export.php');
	wp_die();
}
function wcc_seo_keyword_research_share_fun(){
		
	$keyword	= sanitize_text_field($_POST['key']);
	$name 		= sanitize_text_field($_POST['name']);
	$shared_url = sanitize_text_field($_POST['url']);
	$option 	= sanitize_text_field($_POST['option']);
	$data 		= isset($_POST['data']) ? sanitize_text_field($_POST['data']) : ""; 
	if ( !session_id() ) {
	    session_start();
	}
	$save_data = array();
	$save_data['keyword'] = $keyword;
	$save_data['full_result'] = $_SESSION['wcc_seo_keyword_research_full_result'];
	$save_data['result_header'] = $_SESSION['wcc_seo_keyword_research_result_header'];

	$save_data['full_result'] = array_map( 'esc_attr', $save_data['full_result'] );
	$save_data['result_header'] = array_map( 'esc_attr', $save_data['result_header'] );

	$save_data['option'] = $option;
	$save_data['filter'] = $data;
	$json_result = json_encode( $save_data );
	$data = base64_encode( $json_result );
	$file_name = plugin_dir_path( __FILE__ ) .'share/' . $name . '.txt';
	$share_file = fopen( $file_name, "w" ) or die( "Unable to open file!" );
	fwrite( $share_file, $data );
	fclose( $share_file );

	echo 'true';
	wp_die();
}
function wcc_seo_keyword_research_user_fun(){

	$fname 		= sanitize_text_field($_POST['fname']);
	$lname 		= sanitize_text_field($_POST['lname']);
	$email 		= sanitize_email($_POST['email']);
	$phone 		= sanitize_text_field($_POST['phone']);
	$business 	= sanitize_text_field($_POST['business']);
	$website 	= sanitize_text_field($_POST['website']);
	$keyword 	= sanitize_text_field($_POST['keyword']);


	define( 'SHORTINIT', true );

	global $wpdb;

	$table_name = $wpdb->prefix . 'wcc_seo_keyword_research_user_data';

	$wpdb->insert( 
		$table_name, 
		array( 
			'keyword' 	=> $keyword, 
			'fname' 	=> $fname, 
			'lname' 	=> $lname, 
			'email' 	=> $email, 
			'phone' 	=> $phone, 
			'website' 	=> $website, 
			'business' 	=> $business, 
			'date' 		=> current_time( 'mysql' )
		) 
	);


	if ( !session_id() ) {
	    session_start();
	}

	$wcc_seo_keyword_research_full_result = $_SESSION['wcc_seo_keyword_research_full_result'];

	$_SESSION['wcc_seo_keyword_research_user_valid'] 	= 'valid';
	$_SESSION['wcc_seo_keyword_research_user_fname'] 	= $fname;
	$_SESSION['wcc_seo_keyword_research_user_lname'] 	= $lname;
	$_SESSION['wcc_seo_keyword_research_user_email'] 	= $email;
	$_SESSION['wcc_seo_keyword_research_user_phone'] 	= $phone;
	$_SESSION['wcc_seo_keyword_research_user_business'] = $business;
	$_SESSION['wcc_seo_keyword_research_user_website'] 	= $website;

	echo json_encode($wcc_seo_keyword_research_full_result);wp_die();
}