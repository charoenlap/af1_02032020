<?php 
	require_once('../required/connect.php');
	if(isset($_GET['info'])){
		// var_dump('test');
		foreach($_GET['info'] as $val){
			$result = $obj_db->getdata('points','id='.(int)$val[0]);
			$result_cus = $obj_db->getdata('customers','id='.(int)$_GET['customer_id']);
			// var_dump($result_cus->row);
			$connote = array(
				'key'					=> $val[3],// date('ym').sprintf('%05d', $val[0]).sprintf('%04d', rand(0, 9999)),
				'booking_id'			=> $_GET['booking_id'], 
				'status'				=> 'new', 
				'shipper_name'			=> $result_cus->row['person'], 
				'shipper_company'		=> $result_cus->row['name'], 
				'shipper_address'		=> $result_cus->row['address'], 
				'shipper_phone'			=> $result_cus->row['mobile'], 
				'consignee_name'		=> $result->row['name'], 
				'consignee_company'		=> $result->row['name'], 
				'consignee_address'		=> $result->row['address'], 
				'consignee_phone'		=> $result->row['mobile'], 
				'consignee_district'	=> $result->row['district'],
				'consignee_province'	=> $result->row['province'],
				'consignee_postcode'	=> $result->row['postcode'],
				'service'				=> 'return', 
				'cod'					=> ($_GET['cod']=='on'?1:0), 
				'express'				=> '0', 
				'cod_value'				=> $val[2], 
				'details'				=> json_encode(array('csn'=>array('address'=>$result->row['address'],'district'=>$result->row['district'],'province'=>$result->row['province'],'postcode'=>$result->row['postcode'],'customer_ref'=>$val[1]))), 
				'customer_ref'			=> $val[1],
				'created_at'			=> date('Y-m-d H:i:s'), 
				'updated_at'			=> date('Y-m-d H:i:s'), 
				'deleted_at'			=> '',
				'points_id'				=> $val[0],
				'customers_id'			=> $_GET['customer_id'],

			); 
			$result_insert = $obj_db->insert('connotes',$connote);
			// $insert_log = array(
			// 	'booking_id'			=> $_GET['booking_id'], 
			// 	'status'				=> 'pending',
			// 	'action_by'				=> $result_cus->row['name'],
			// 	'notes'					=> '',
			// 	'created_at'			=> date('Y-m-d H:i:s'),
			// 	'updated_at'			=> date('Y-m-d H:i:s')
			// );
			// $insert_log_result = $obj_db->insert('log_bookings',$insert_log);
			// echo "oo";
			// var_dump($insert_log_result);
		}
	}
?>