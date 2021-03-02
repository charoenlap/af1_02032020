<?php 
	// var_dump($_SERVER);
	require_once($_SERVER["DOCUMENT_ROOT"].'/api/required/connect.php');
	if(isset($_GET['type'])){
		if($_GET['type']=="login"){
			$result = array();
			$emp_key = str_pad($_GET['emp_key'], 4, '0', STR_PAD_LEFT);
			$id_card = str_pad($_GET['id_card'], 13, '0', STR_PAD_LEFT);
			$result_emp = $obj_db->getdata('employees',"id_card = '".$id_card."' AND emp_key = '".$emp_key."' AND password = '".$_GET['password']."' and `status`='active'");
			if($result_emp->num_rows > 0 ){
				foreach($result_emp->rows as $val){
					$result = array(
						'result'		=>  'success',
						'emp_key'		=>	$val['emp_key'],
						'nickname'		=>	$val['nickname'],
						'position_id'	=>	$val['position_id'],
						'branch_id' 	=> 	$val['branch_id'],
						'firstname' 	=> 	$val['firstname'],
						'lastname' 		=> 	$val['lastname'],
						'phone' 		=> 	$val['phone'],
						'address' 		=> 	$val['address'],
					);
				}
			}else{
				$result = array(
					'result' 	=> 'fail',
					'desc'		=> 'Not found'
				);
			}
			echo json_encode($result);
		}
		if($_GET['type']=="getJob"){
			$result = array();
			$msg_key = str_pad($_GET['msg_key'], 4, '0', STR_PAD_LEFT);
			$sql = "bookings 
			LEFT JOIN customers ON bookings.customer_id = customers.id";
			$where = "bookings.msg_key='".$msg_key."' 
			AND (bookings.`status`='inprogress' or bookings.`status`='new') 
			AND bookings.created_at >= '2019-12-31 23:59:59'";
			// echo $sql."bookings.msg_key='".$msg_key."' AND (bookings.`status`='inprogress' or bookings.`status`='new') AND bookings.created_at >= '2019-12-31 23:59:59'";
			$result_job = $obj_db->getdata($sql,$where,"*,bookings.id AS id_booking,bookings.key AS `key`");
			if($result_job->num_rows > 0 ){
				$result['result'] = 'success';
				foreach($result_job->rows as $val){
					$result['data'][] = array(
						'person'		=>	$val['person'],
						'id'			=>	$val['id'],
						'key'			=>	$val['key'],
						'customer_name' => 	$val['customer_name'],
						'district' 		=> 	$val['district'],
						'province' 		=> 	$val['province'],
						'postcode' 		=> 	$val['postcode'],
						'cod' 			=> 	($val['cod']=='1'?'COD':''),
						'get_datetime' 	=> 	$val['get_datetime'],
						'id_booking' 	=>	$val['id_booking'],
						'address'		=> 	$val['address'],
						'mobile'		=>	$val['mobile'],
						'status'		=>	$val['status']
					);
				}
			}else{
				$result = array(
					'result' 	=> 'fail',
					'desc'		=> 'Not found'
				);
			}
			echo json_encode($result);
		}
		if($_GET['type']=="getJobDetail"){
			$result = array();
			$msg_key = (isset($_GET['msg_key'])?$_GET['msg_key']:'');
			$id = (isset($_GET['id'])?$_GET['id']:'');
			$msg_key = str_pad($msg_key, 4, '0', STR_PAD_LEFT);
			$sql = "bookings 
			LEFT JOIN customers ON bookings.customer_id = customers.id
			LEFT JOIN connotes ON connotes.booking_id = bookings.id 
			";
			$result_job = $obj_db->getdata($sql,"bookings.id = '".$id."'","*,bookings.id AS id_booking,bookings.address AS address");
			$val = $result_job->row;
			$sql_connote = "connotes";
			$result_connote = $obj_db->getdata($sql_connote,'booking_id='.$val['id_booking']);
			if($result_job->num_rows > 0 ){
				$result['result'] = 'success';
				// foreach($result_job->rows as $val){
					$result['connotes'] = $result_connote->rows;
					$result['count_connotes'] = $result_connote->num_rows;
					$result['data'] = array(
						'person'		=>	$val['person'],
						'id'			=>	$val['id'],
						'key'			=>	$val['key'],
						'customer_name' => 	$val['customer_name'],
						'district' 		=> 	$val['district'],
						'province' 		=> 	$val['province'],
						'postcode' 		=> 	$val['postcode'],
						'cod' 			=> 	($val['cod']=='1'?'COD':''),
						'get_datetime' 	=> 	$val['get_datetime'],
						'id_booking' 	=>	$val['id_booking'],
						'address'		=> 	$val['address'],
						'mobile'		=>	$val['mobile'],
						'status'		=>	$val['status']
					);
				// }
			}else{
				$result = array(
					'result' 	=> 'fail',
					'desc'		=> 'Not found'
				);
			}
			echo json_encode($result);
		}
		if($_GET['type']=="getJobDetailCount"){
			$result = array();
			$msg_key = (isset($_GET['msg_key'])?$_GET['msg_key']:'');
			$id = (isset($_GET['id'])?$_GET['id']:'');
			$msg_key = str_pad($msg_key, 4, '0', STR_PAD_LEFT);
			$sql = "bookings JOIN customers ON bookings.customer_id = customers.id";
			$result_job = $obj_db->getdata($sql,"bookings.id = '".$id."'","*,bookings.id AS id_booking,bookings.address AS address");
			$val = $result_job->row;
			$sql_connote = "connotes";
			$result_connote = $obj_db->getdata($sql_connote,'booking_id='.$val['id_booking'],'COUNT(booking_id) AS connote_count');
			if($result_job->num_rows > 0 ){
				$result['result'] = 'success';
				// foreach($result_job->rows as $val){
					$result['connote_count'] = $result_connote->row['connote_count'];
					$result['data'] = array(
						'person'		=>	$val['person'],
						'id'			=>	$val['id'],
						'key'			=>	$val['key'],
						'customer_name' => 	$val['customer_name'],
						'district' 		=> 	$val['district'],
						'province' 		=> 	$val['province'],
						'postcode' 		=> 	$val['postcode'],
						'cod' 			=> 	($val['cod']=='1'?'COD':''),
						'get_datetime' 	=> 	$val['get_datetime'],
						'id_booking' 	=>	$val['id_booking'],
						'address'		=> 	$val['address'],
						'mobile'		=>	$val['mobile'],
						'status'		=>	$val['status']
					);
				// }
			}else{
				$result = array(
					'result' 	=> 'fail',
					'desc'		=> 'Not found'
				);
			}
			echo json_encode($result);
		}
		if($_GET['type']=="updateBooking"){
			$result = array(
				'result' 	=> 'success',
				'desc'		=> ''
			);
			$status = $_GET['status'];
			$sql_update = '';
			if($status=="pending"){
				$sql_update = "UPDATE bookings SET `status`='".$status."' WHERE id='".$_GET['id']."'";
			}else{
				$sql_update = "UPDATE bookings SET `status`='".$status."' WHERE id='".$_GET['id']."'";
			}
			$result_update = $obj_db->query($sql_update);
			echo json_encode($result);
		}
		if($_GET['type']=="deleteJob"){
			$sql_delete = '';
			if(!empty($_GET['id'])){
				$sql_delete = "DELETE FROM `jobs` WHERE `key`='".$_GET['id']."'";
				$result_delete = $obj_db->query($sql_delete);
				$result = array(
					'result' 	=> 'success',
					'desc'		=> $sql_delete
				);
			}else{
				$result = array(
					'result' 	=> 'fail',
					'desc'		=> ''
				);
			}
			echo json_encode($result);
		}
		if($_GET['type']=="confirmJob"){
			$result = array(
				'result'=>'success'
			); 
			$comment = (isset($_GET['comment'])?$_GET['comment']:'');
			$booking_id = (int)(isset($_GET['booking_id'])?$_GET['booking_id']:'');
			$new_connote = array();
			$arr_new_connote = array();
			$arr_new_connote = json_decode((isset($_GET['barcode'])?$_GET['barcode']:''),true);
			// $result['comment'] = $_GET['comment'];
			// $result['request_post'] = $_POST;
			// $result['request_get'] = $_GET;
			// $result['request_request'] = $_REQUEST;

			foreach($arr_new_connote as $val){
				$new_connote[] = $val;
				$sql_insert_connote = "INSERT INTO connotes(`key`,`status`,`booking_id`,`service`,`cod`,`express`,`comment`,`created_at`) 
				VALUES ('".$val."','temp','".$booking_id."','oneway','0','0','".$comment."','".date('Y-m-d H:i:s')."')";
				$result['sql'][] = $sql_insert_connote;
				$result_insert_connote = $obj_db->query($sql_insert_connote);
			}

			$image_sign = '';
			$image_file = '';
			
			if(isset($_POST['signature'])){
				if(!empty($_POST['signature']) AND $_POST['signature']!='undefined'){
					$img_signature = $_POST['signature'];
					$image_sign = $booking_id.'_sign.png';
					createImage($booking_id.'_sign','data'.$img_signature,'png','uploads/sign/');
				}
			}
			if(isset($_POST['image'])){
				if(!empty($_POST['image']) AND $_POST['image']!='undefined'){
					$img_file = $_POST['image'];
					$image_file = $booking_id.'_file.jpeg';
					createImage($booking_id.'_file','data'.$img_file,'jpeg','uploads/file/');
				}
			}

			$sql_update = "UPDATE bookings SET 
							`status`='complete',
							`signature`='".$booking_id.'_sign.png'."',
							`file`='".$booking_id.'_file.jpeg'."',
							`comment` = '".$comment."' 
							WHERE id='".$booking_id."'";
			$result_update = $obj_db->query($sql_update);

			$sql_update = "UPDATE connotes SET `status`='comfirm' WHERE bookings='".$booking_id."'";
			$result_update = $obj_db->query($sql_update);
			echo json_encode($result);
		}
	}
	if($_GET['type']=="saveConnote"){
		$result = array(
			'result' => 'fail'
		);
		$status = 0;
		// var_dump($_POST);
		$booking_id = (int)$_GET['booking_id'];
		if($booking_id>0){
			// $arr_new_connote = json_decode((isset($_POST['barcode'])?$_POST['barcode']:''),true);
			$sql_select_booking = "SELECT * FROM bookings WHERE id = '".$booking_id."'";
			$result_booking = $obj_db->query($sql_select_booking)->row;
			$detail_person = json_decode($result_booking['person'],true);

			// var_dump($result_booking['person']);
			// exit();
			$connote = array();
			$connote_arr = explode(',',$_GET['barcode']);
			// var_dump($connote_arr);

			// $result =  array('result'=>json_decode($connote,true));
			// echo json_encode($result);

			// exit();
			foreach($connote_arr as $val){
				$status = 1;
				$data_insert = array(
					'booking_id' 		=> $booking_id,
					'status' 			=> 'temp',
					'key'				=> $val,
					'shipper_name' 		=> $detail_person['name'],
					'shipper_company' 	=> $result_booking['customer_name'],
					'shipper_address'	=> $result_booking['address'],
					'shipper_phone' 	=> $detail_person['phone']
				);
				$obj_db->insert('connotes',$data_insert);
			}
			if($status == 1){
				$result = array(
					'result' => 'success'
				);
			}
		}else{

		}
		echo json_encode($result);
	}
	if($_GET['type']=="getSend"){
		$result = array();
		$msg_key = str_pad($_GET['msg_key'], 4, '0', STR_PAD_LEFT);
		$sql = "jobs
		LEFT JOIN connotes
		ON jobs.connote_id = connotes.id 
		LEFT JOIN bookings
		ON connotes.booking_id = bookings.id";
		$result_job = $obj_db->getdata($sql,"jobs.msg_key='".$msg_key."' 
			AND (jobs.`status` = 'new' OR jobs.`status` = 'inprogress') 
			AND (jobs.created_at >= '2019-12-31 23:59:59') 
			OR (jobs.`status` ='complete' 
				AND jobs.created_at >= '".date('Y-m-d')." 00:00:00' 
				AND jobs.msg_key='".$msg_key."')","*,jobs.id AS id_jobs,jobs.key AS `key`,jobs.`status` as status");

		if($result_job->num_rows > 0 ){
			$result['result'] = 'success';
			foreach($result_job->rows as $val){
				$result['data'][] = array(
					'person'			=>	'',
					'id'				=>	$val['id'],
					'key'				=>	$val['key'],
					'consignee_company' => 	$val['consignee_company'],
					'customer_name' 	=> 	$val['consignee'],
					'shipper_company'	=> 	$val['shipper_company'],
					'district' 			=> 	$val['consignee_district'],
					'province' 			=> 	$val['consignee_province'],
					'postcode' 			=> 	$val['consignee_postcode'],
					'flow' 				=> 	$val['flow'],
					'cod' 				=> 	'', // remove
					'get_datetime' 		=> 	$val['get_datetime'],
					'id_jobs' 			=>	$val['id_jobs'],
					'address'			=> 	$val['consignee_address'],
					'status'			=>	$val['status'],
					'service'			=>	$val['service'],
					'text'				=>	($val['status']=="complete"?'success':'warning')
				);
			}
		}else{
			$result = array(
				'result' 	=> 'fail',
				'desc'		=> 'Not found'
			);
		}
		echo json_encode($result);
	}
	if($_GET['type']=="getSendDetail"){
		$result = array();
		$msg_key = str_pad($_GET['msg_key'], 4, '0', STR_PAD_LEFT);
		$sql = "jobs";
		$result_job = $obj_db->getdata($sql,"jobs.id = '".$_GET['id']."'","*,jobs.id AS id_jobs,jobs.address AS address,jobs.`status` AS status,jobs.consignee");
		$val = $result_job->row;
		// $result_booking = $obj_db->query($sql_select_booking)->row;
			// $detail_person = json_decode($result_job['person'],true);
		// $sql_connote = "connotes";
		// $result_connote = $obj_db->getdata($sql,'jobs.id='.$val['id_booking']);
		if($result_job->num_rows > 0 ){
			$result['result'] = 'success';
			// foreach($result_job->rows as $val){
				// $result['connotes'] = $result_connote->rows;
				$result['data'] = array(
					'person'		=>	$val['consignee'],
					'id'			=>	$val['id'],
					'key'			=>	$val['key'],
					'customer_name' => 	$val['consignee'],
					'district' 		=> 	$val['district'],
					'province' 		=> 	$val['province'],
					'postcode' 		=> 	$val['postcode'],
					'address' 		=> 	$val['address'],
					'flow' 			=> 	$val['flow'],
					'cod' 			=> 	'', // remove
					'get_datetime' 	=> 	$val['get_datetime'],
					'id_jobs' 		=>	$val['id_jobs'],
					'address'		=> 	$val['address'],
					'status'		=>	$val['status']
				);
			// }
		}else{
			$result = array(
				'result' 	=> 'fail',
				'desc'		=> 'Not found'
			);
		}
		echo json_encode($result);
	}
	if($_GET['type']=="updateSend"){
		// echo "test";exit();
		$result = array(
			'result' 	=> 'success',
			'desc'		=> 'updateSend' 
		);
		$status = $_GET['status'];
		$sql_update = '';
		if($status=="pending"){
			$sql_update = "UPDATE jobs SET `status`='".$status."' WHERE jobs.id='".$_GET['id']."'";
		}else{
			$sql_update = "UPDATE jobs SET `status`='".$status."' WHERE jobs.id='".$_GET['id']."'";
		}
		$result_update = $obj_db->query($sql_update);
		echo json_encode($result);
	}
	if($_GET['type']=="confirmSend"){
		$result = array(
			'result'=>'success'
		); 
		$jobs_id = (int)$_GET['jobs_id'];
		$new_connote = array();
		$arr_new_connote = array();
		$arr_new_connote = json_decode($_GET['barcode'],true);
		foreach($arr_new_connote as $val){
			$new_connote[] = $val;
			$sql_insert_connote = "INSERT INTO connotes
			(`key`		,`status`	,`booking_id`		,`service`	,`cod`	,`express`	,`comment`		,`created_at`) 
				VALUES 
			('".$val."'	,'temp'		,'".$booking_id."'	,'oneway'	,'0'	,'0'		,'".$comment."'	,'".date('Y-m-d H:i:s')."')";
			$result['sql'][] = $sql_insert_connote;
			$result_insert_connote = $obj_db->query($sql_insert_connote);
		}
		$image_sign = '';
		$image_file = '';
		if(isset($_POST['signature'])){
			if(!empty($_POST['signature']) AND $_POST['signature']!='undefined'){
				$img_signature = $_POST['signature'];
				$image_sign = $jobs_id.'_sign.png';
				createImage($jobs_id.'_sign','data'.$img_signature,'png','uploads/sign/');
			}
		}
		if(isset($_POST['image'])){
			if(!empty($_POST['image']) AND $_POST['image']!='undefined'){
				$img_file = $_POST['image'];
				$image_file = $jobs_id.'_file.jpeg';
				createImage($jobs_id.'_file','data'.$img_file,'jpeg','uploads/file/');
			}
		}

		

		// $sql_update = "UPDATE bookings SET `status`='complete' WHERE id='".$booking_id."'";
		// $result_update = $obj_db->query($sql_update);

		$sql_update = "UPDATE jobs SET `status`='complete',photo='".$image_sign."',photo_file='".$image_file."' WHERE jobs.id='".$jobs_id."'";
		$result_update = $obj_db->query($sql_update);
		echo json_encode($result);
	}
	if($_GET['type']=="createJob"){
		$result = array(
			'result' 	=> 'fail',
			'desc'		=> 'createJob' 
		);
		// var_dump($result);
		$sql_emp = "SELECT * FROM employees WHERE `emp_key`='".$_GET['id']."'";
		$result_emp = $obj_db->query($sql_emp);

		$sql_connote = "SELECT * FROM connotes WHERE `key`='".$_GET['connote_id']."'";
		$result_connote = $obj_db->query($sql_connote);

		$sql_job = "SELECT * FROM jobs WHERE `key`='".$_GET['connote_id']."'";
		$result_job = $obj_db->query($sql_job);
		// echo $result_connote->num_rows;
		if($result_connote->num_rows > 0 AND $result_emp->num_rows >0){
			// var_dump($result_connote);
			$result_emp = $result_emp->row;
			$result_connote = $result_connote->row;

			//$sql_connote_check = "SELECT * FROM job WHERE `connote_id`='".$result_connote['id']."'";
			//$result_connot_check = $obj_db->query($sql_connote_check);
			if($result_job->num_rows==0){
				$date 			= date('Y-m-d H:i:s');
				$connote_id 	= $result_connote['id'];
				$key 			= $result_connote['key'];
				$status 		= 'new';
				$flow 			= 'send';
				$msg_key 		= $result_emp['emp_key'];
				$msg_name 		= $result_emp['nickname'];
				$sup_key 		= '';
				$sup_name 		= '';
				$consignee 		= $result_connote['consignee_name'];
				$address 		= $result_connote['consignee_address'];
				$district 		= $result_connote['consignee_district'];
				$province 		= $result_connote['consignee_province'];
				$postcode 		= $result_connote['consignee_postcode'];
				// $photo 			= '';
				// $photo_file 	= '';
				// $receiver_name 	= '';
				// $received_at 	= '';
				// $topup 			= '';
				$notes 			= '';
				$lat 			= '';
				$lng 			= '';
				$created_at 	= $date;
				// $updated_at 	= '';
				// $deleted_at 	= '';

				$data_insert = array(
					'connote_id' 	=> $connote_id,
					'key'			=> $key,
					'status'		=> $status,
					'flow'			=> $flow,
					'msg_key'		=> $msg_key,
					'msg_name'		=> $msg_name,
					'sup_key'		=> $sup_key,
					'sup_name'		=> $sup_name,
					'consignee'		=> $consignee, 
					'address'		=> $address,
					'district'		=> $district,
					'province'		=> $province,
					'postcode'		=> $postcode,
					// 'photo'			=> $photo,
					// 'photo_file'	=> $photo_file,
					// 'receiver_name'	=> $receiver_name,
					// 'received_at'	=> $received_at,
					// 'topup'			=> $topup,
					'notes'			=> $notes,
					'lat'			=> $lat,
					'lng'			=> $lng,
					'created_at'	=> $created_at
					// 'updated_at'	=> $updated_at,
					// 'deleted_at'	=> $deleted_at
				);
				// var_dump($data_insert);
				$result_insert_job = $obj_db->insert('jobs',$data_insert);
				// var_dump($result_insert_job);
				// $obj_db->query($sql_update);
				$result = array(
					'result' 	=> 'success',
					'desc'		=> 'createJob success' 
				);
			}else{
				$result = array(
					'result' 	=> 'fail',
					'desc'		=> 'Dupplicate have in system job id '.$result_job->row['id'] 
				);
			}
		}else{
			if($result_connote->num_rows == 0){
				$result = array(
					'result' 	=> 'fail',
					'desc'		=> 'Not found connote_id '.$_GET['connote_id'].' count:'.$result_connote->num_rows
				);
			}elseif($result_emp->num_rows >0){
				$result = array(
				'result' 	=> 'fail',
				'desc'		=> 'Not found emp_id '.$_GET['id']
			);
			}
			
		}
		echo json_encode($result);
	}
	function createImage($name='',$data = '',$type = '',$path=''){
		if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
		    $data = substr($data, strpos($data, ',') + 1);
		    $type = strtolower($type[1]); // jpg, png, gif

		    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
		        // throw new \Exception('invalid image type');
		    }

		    $data = base64_decode($data);

		    if ($data === false) {
		        // throw new \Exception('base64_decode failed');
		    }
		} else {
		    // throw new \Exception('did not match data URI with image data');
		}

		@file_put_contents($path."$name.{$type}", $data);
	}
?>