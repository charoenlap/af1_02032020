<?php 

	header('Access-Control-Allow-Origin: *');  
	// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');  
	ob_start();
	session_start();
	// error_reporting(E_ALL);
	ini_set('display_errors', 'off');
	// error_reporting(E_ALL);
	// ini_set('display_errors', 'ON');

	define('PREFIX', '');

	// define('ADMIN_EMAIL', '');

	// define('UPLOAD_IMG_PATH', 'uploads/image/');
	// define('UPLOAD_PROFILE_PATH', 'uploads/profile/');
	// define('UPLOAD_CONTENT_PATH', 'uploads/content/');
	// define('PROFILE_STUDENT_PATH', $mdir.'uploads/students/profiles/');
	// #Facebook

	// # localhost app for test
	// define('fb_app_id', '');
	// define('fb_app_secret', '');
	// define('fb_link_callback', 'index.php?route=facebook/page_login_fb_callback');
	// define('DEFAULT_LIMIT_PAGE', '20');

	// if(!isset($_SESSION['lang'])){
	// 	$_SESSION['lang'] = "th";
	// }
	// if(isset($_GET['lang'])){
	// 	$_SESSION['lang'] = $_GET['lang'];
	// }
	// $lang = $_SESSION['lang'];
	// if($lang=="th"){
	// 	$lang_no = 1;
	// }else{
	// 	$lang_no = 2;
	// }

	$PRIVATEhost_home="localhost";

	$PRIVATEuser_home="af1express_af1";
	$PRIVATEpassword_home="LJGcbrHBs2";
	$PRIVATEdb_home="af1express_af1";
	// $PRIVATEuser_home="root";
	// $PRIVATEpassword_home="root";
	// $PRIVATEdb_home="af1_test"

	date_default_timezone_set('Asia/Bangkok');
	
	// require_once('model/db.php');
	require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_db.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_pic.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_product.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_content.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_user.php'; 
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_permission.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_utility.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_dal.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_question.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_job.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_customer.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_master.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class/class_course.php';

	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'phpMailer/class.phpmailer.php';
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'language.php';
	// echo $lang_no;
	global $obj_db;
	$obj_db 	= new db($PRIVATEhost_home,$PRIVATEdb_home,$PRIVATEuser_home,$PRIVATEpassword_home);
	
	// $user 		= new user($obj_db);
	// $obj_pic 	= new pic();
	// $obj_pro 	= new product();
	// $obj_con 	= new content();
	// $user 		= new user($obj_db);
	// $permission = new permission($obj_db);
	// $dal		= new DataAccess($obj_db);
	// $utility 	= new Utility(); 
	// $dbUtility 	= new DBUtility(); 
	// $question 	= new question($obj_db); 
	// $job 		= new job($obj_db); 
	// $cus 		= new customer($obj_db);
	// $master 	= new master($obj_db);

	// extract($_GET);
	// extract($_POST);
	// extract($_COOKIE);
	// extract($_FILES); 
	// extract($_REQUEST); 
	// $ntime= time();
	// define('HOST_API', 'https://www.fsoftpro.com/projects/tp_app/api_tp/public/');
	$landingpage = "login";
	// $lan = $language[$lang_no];

	// $mdir = "http://af1express.com/api/";
	
	// require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'function.php';
	// $para = getpara();
	// $setting = $obj_db->getdata('setting where id=1');
	// $setting = $setting->rows;
	// $setting = $setting[0];
	// var_dump($setting);
	// $setting = mysql_fetch_assoc(mysql_query("select * from sl_setting  where id =1"));


	/*$email_send = "no-reply@chiiwii.com <no-reply@chiiwii.com>";
	$email_username = "info@chiiwii.com";
	$email_password = "12345";
	$email_host = "mail.chiiwii.com"; // smtp.gmail.com
	$email_port = "465"; // or ( 465 or 587 gmail )
	$email_stmpsecure = "ssl";  
	$email_bcc = "";
	$email_detail = "";
	$email_detail_header = "";
	$email_detail_footer = "";*/
	// $email_send = "labour.email.send@gmail.com <info@chiiwii.com>";
	// $email_username = "labour.email.send@gmail.com";
	// $email_password = "Labour12345";
	
	
	/*
	$email_send = "mahidol.email@gmail.com <mahidol.email@gmail.com>";
	$email_username = "mahidol.email@gmail.com";
	$email_password = "Mhd12345678";
	*/
	// $email_host = "smtp.gmail.com"; // smtp.gmail.com
	// $email_port = "465"; // or ( 465 or 587 gmail )
	// $email_stmpsecure = "ssl";  
	// $email_bcc = "";
	// $email_detail = "";
	// $email_detail_header = "";
	// $email_detail_footer = "";
?>