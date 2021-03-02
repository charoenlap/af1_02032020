<?php 
	
	require_once('../required/connect.php');
	if($_GET['type']=='find'){
		$result = $obj_db->getdata('points',"`name` like '%".$_GET['datasearch']."%' 
			or address like '%".$_GET['datasearch']."%' 
			or district like '%".$_GET['datasearch']."%'  
			or postcode like '%".$_GET['datasearch']."%' 
			or person like '%".$_GET['datasearch']."%' 
			or mobile like '%".$_GET['datasearch']."%' 
			or office_tel like '%".$_GET['datasearch']."%' 
			");
		// echo "<pre>";
		// var_dump($result->rows);
		// echo "</pre>";
		echo json_encode($result->rows);
	}
	if($_GET['type']=='findid'){
		$return = array();
		$result = $obj_db->query("SELECT * FROM points WHERE id=".(int)$_GET['datasearch']." limit 0,1");
		$return = $result->row;
		$return['connote_key'] = date('ym').sprintf('%05d', $result->row['id']).sprintf('%04d', rand(0, 9999));
		// $return['query'] = "SELECT * FROM points WHERE id=".(int)$_GET['datasearch']." limit 0,1";
		echo json_encode($return);
	}
	if($_GET['type']=='addPoint'){
		$customer_id = (int)$_POST['customer_id'];

		$return = array(
			'status' => 'fail'
		);
		$result_cus = $obj_db->getdata("customers",'id='.$customer_id);

		$insert = array(
			'key'=>'',
			'customer_id'=>$customer_id,
			'customer_key'=>$result_cus->row['key'],
			'type'=>'consignee',
			'name' => $_POST['company'],
			'address' => $_POST['address'],
            'district' => $_POST['district'],
            'province' => $_POST['province'],
            'postcode' => $_POST['postcode'],
            'person' => $_POST['person'],
            'mobile' => $_POST['phone'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
            
		);
		$result = $obj_db->insert("points",$insert);
		if($result){
			$return = array(
				'status' => 'success',
				'id'=>$result,
				'connote_key'=>sprintf('D%05d', $result),
				'key'=>sprintf('D%05d', $result),
				'customer_key'=>$result_cus->row['key'],
				'customer_ref'=>''
			);
		}

		// $return = $result->row;
		// $return['connote_key'] = date('ym').sprintf('%05d', $result->row['id']).sprintf('%04d', rand(0, 9999));
		// $return['query'] = "SELECT * FROM points WHERE id=".(int)$_GET['datasearch']." limit 0,1";
		echo json_encode($return);
	}
?>