<?php 
	require_once('../required/connect.php');
	header('Content-Type: application/json');
	date_default_timezone_set('Asia/Bangkok');

	$token_desc = "Test api";
	$minutes_expired_token = 5;
	$date_now = date('Y-m-d H:i:s');

	$token_type = array('create_booking','create_connote','get_status_booking','get_status_connote','get_connote_detail','get_booking_detail');
	$result = array();
	$result = http_response(404);
	$result['desc'] = 'Not found type';

	if(get('type')=='getToken'){
		if(method_post()){
			$result = array();
			$result = http_response(401);
			$username = post('username');
			$password = post('password');
			$result_login = login($username,$password);
			if($result_login['status_code']=='200'){
				$type = post('type');
				if(in_array($type,$token_type)){
					$user_id = $result_login['user_id'];

					$token_expired = add_time($date_now,'PT' . $minutes_expired_token . 'M');

					$text_token = $type.'_'.$date_now;
					$token = base64_encode($text_token);
					$data_get_token = array(
						'token'			=> $token,
						'token_type' 	=> $type,
						'token_desc' 	=> $token_desc,
						'token_expired' => $token_expired,
						'date_create'	=> $date_now,
						'user_id'		=> $user_id
					);
					$result_token = getToken($data_get_token);
					if($result_token['status_code']=='200'){
						$result = http_response(201);
						$result['token'] 			= $token;
						$result['token_type'] 		= $type;
						$result['token_expired'] 	= $token_expired;
						$result['date_create'] 		= $date_now;
					}else{
						$result = http_response(400);
						$result['desc'] = 'Not get token';
					}
				}else{
					$result = http_response(400);
					$result['desc'] = 'Not found type';
				}
			}else{
				$result = http_response(401);
				$result['desc'] = 'Not Authen';
			}
		}else{
			$result = http_response(405);
			$result['desc'] = 'Method bad';
		}
		
	}else if(get('type')=='create_booking'){
		if(method_post()){
			$result = array();
			$result = http_response(401);
			$token = post('token');
			$type = get('type');
			$data_check_token = array(
				'token'		=> $token,
				'type'		=> $type,
				'date_now' 	=> $date_now
			);
			$result_check_token = checkToken($data_check_token);
			if($result_check_token['status_code']=="200"){
				$data_insert_booking = array(
					'user_id' => $result_check_token['user_id'],
					'date_now' 	=> $date_now
				);
				$result_booking = createBooking($data_insert_booking);
				$result = http_response(200);
			}else{
				$result = http_response(404);
				$result['desc'] = 'Token fail';
			}
		}else{
			$result = http_response(405);
			$result['desc'] = 'Method bad';
		}
	}else{
		$result = http_response(400);
		$result['desc'] = 'Not found type';
	}
	echo json_encode($result);
	function createBooking($data=array()){ 
		global $obj_db;
		$result = array();
		$result_user = $obj_db->getdata('customers','id='.$data['user_id']);
		if($result_user->num_rows>0){
			$user = $result_user->row;
			$data_insert_booking = array(
				'status' 		=> 'pending',
				'customer_id' 	=> $user['id'],
				'customer_key'	=> $user['key'],
				'customer_name'	=> $user['name'],
				'address'		=> $user['address'],
				'district'		=> $user['district'],
				'province'		=> $user['province'],
				'postcode'		=> $user['postcode'],
				'person'		=> $user['person'],
				'cod'			=> '',
				'express'		=> '',
				'car_id'		=> '',
				'get_datetime'	=> '',
				'msg_key'		=> '',
				'msg_name'		=> '',
				'cs_key'		=> '',
				'cs_name'		=> '',
				'note'			=> '',
				'created_at'	=> $data['date_now'],
				// 'updated_at'	=> '',
				// 'deleted_at'	=> '',
				'signature'		=> '',
				'file'			=> '',
				'comment'		=> ''
			);

			$id_booking = $obj_db->insert('bookings',$data_insert_booking);

			$key = 'BA'.sprintf("%05d", $id_booking); 
			$data_update_booking = array(
				'key' => $key
			);
			$obj_db->update('bookings',$data_update_booking,'id='.$id_booking);
			$result = http_response(200);
			$result['booking_id'] 	= $id_booking;
			$result['key']			= $key;
		}else{
			$result = http_response(400);
		}
		return $result;
	}
	function checkToken($data=array()){
		global $obj_db;
		$result = http_response(404);
		$token 	= $obj_db->val($data['token']);
		$type 	= $obj_db->val($data['type']);
		$sql_token = "SELECT * FROM token 
		WHERE token = '".$token."' 
		AND token_expired >= '".$data['date_now']."' 
		AND token_type = '".$type."'";
		$result_token = $obj_db->query($sql_token);
		if($result_token->num_rows>0){
			$result 			= http_response(200);
			$result['user_id']	= $result_token->row['user_id'];
		}
		return $result;
	}
	function getToken($data=array()){
		global $obj_db;
		$result = http_response(404);
		$result_insert_token = $obj_db->insert('token',$data);
		if($result_insert_token){
			$result = http_response(200);
		}
		return $result;
	}
	function login($username,$password){
		global $obj_db;
		$result = http_response(404);
		$username = $obj_db->val($username);
		$password = $obj_db->val($password);
		$sql_login = "SELECT id  
		FROM customers 
		WHERE email='".$username."' 
		AND password=MD5('".$password."') 
		AND deleted_at IS NULL LIMIT 0,1";
		$result_login = $obj_db->query($sql_login);
		if($result_login->num_rows>0){
			$user_id 			= $result_login->row['id'];
			$result 			= http_response(200);
			$result['user_id'] 	= $user_id;
		}
		return $result;
	}
	function http_response($code){
		$result = array();
		$status_code = $code;
		$status_desc = array(
			'200' 	=> 	'Success',
			'201' 	=>	'Created',				// ได้สร้าง ไฟล์เสร็จแล้ว
			'202'	=>	'Accepted',
			'204' 	=>	'No Content',			// Server ทำงานเสร็จแล้ว แต่ไม่แจ้งรายละเอียด
			'400'	=>	'Bad Request',			// Request ผิดรูปแบบ
			'401'	=> 	'Unauthorized',			// ยังไม่ได้ Authen
			'403' 	=>	'Forbidden', 			// รู้ว่า Client เป็นใครแต่ สิทธิ์ในการใช้ข้อมูลไม่ถึง
			'404' 	=>	'Not Found',
			'405'	=>	'Method Not Allowed',
			'422' 	=>	'Unprocessable Entity' 	// ข้อมูลที่เข้ามาไม่ครบ
		);
		$result = array(
			'status_code' => $status_code,
			'status_desc' => $status_desc[$status_code]
		);
		return $result;
	}
	function method_post(){
		$result = false;
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$result = true;
		}
		return $result;
	}
	function post($val=""){
		$result = '';
		if(isset($_POST[$val])){
			$result = $_POST[$val];
		}
		return $result;
	}
	function get($val=""){
		$result = '';
		if(isset($_GET[$val])){
			$result = $_GET[$val];
		}
		return $result;
	}
	function add_time($date_start,$condition){
		$time = new DateTime($date_start);
		$time->add(new DateInterval($condition));
		$token_expired = $time->format('Y-m-d H:i:s');
		return $token_expired;
	}
?>