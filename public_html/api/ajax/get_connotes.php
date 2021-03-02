<?php 
	require_once('../required/connect.php');
	if(isset($_GET['booking_id'])){
		$result_booking = $obj_db->getdata('connotes','booking_id='.(int)$_GET['booking_id']);
		$result = array();
		foreach($result_booking->rows as $val){
			$key = (empty($val['key'])?'ยังไม่มีเลข Connote':$val['key']);
			$detail = json_decode($val['details'],true);
			// $customer_ref = $val['customer_ref'];
			// var_dump($detail['csn']['address']);
			// exit();
			$customer_ref = (!empty($val['customer_ref'])?$val['customer_ref']:$detail['csn']['customer_ref']);

			$result[] = array(
				'key'=>$key,
				'status'=>$val['status'],
				'consignee_name'=>$val['consignee_name'],
				'consignee_company'=>$val['consignee_company'],
				'consignee_address'=>$val['consignee_address'],
				'service_label'=>(isset($val['service_label'])?$val['service_label']:''),
				'url_pdf'=>'http://af1express.com/gen_connote/'.$key,
				'cod_value'=>$val['cod_value'],
				'cod'=>$val['cod'],
				'customer_ref'=>$customer_ref
			);
		}
		// echo "<pre>";
		// var_dump($result);
		// echo "</pre>";
		echo json_encode($result);
	}
	if(isset($_GET['key'])){
		$result_booking = $obj_db->getdata('bookings',"`key`='".$_GET['key']."'");
		$result_connotes = $obj_db->getdata('
			connotes 
			LEFT JOIN bookings ON connotes.booking_id = bookings.id 
			LEFT JOIN jobs ON connotes.id = jobs.connote_id','booking_id='.(int)$result_booking->row['id'],
			'*,connotes.`key` as key_connote,jobs.`status` AS `job_status` ');
		// $result = array();
		// echo "<pre>";
		// var_dump($result_connotes->rows);exit();
		foreach($result_connotes->rows as $index => $val){
			// $key = (empty($val['key'])?'ยังไม่มีเลข Connote':$val['key']);
			$detail = json_decode($val['details'],true);
			// var_dump($detail);exit();

			// foreach ($detail['pieces'] as $key => $value) {
			// 	# code...
			// }
			$customer_ref = '';
			$customer_ref = (!empty($detail['csn']['customer_ref'])?$detail['csn']['customer_ref']:$val['customer_ref']);
			// $customer_ref = (!empty($detail['csn']->customer_ref)?$detail['csn']->customer_ref:$val['customer_ref']);
			$result[] = array(
				'key_connote'=>$val['key_connote'],
				'api'=>'update',
				'status'=>$val['status'],
				'consignee_name'=>$val['consignee_name'],
				'consignee_company'=>$val['consignee_company'],
				'consignee_address'=>$val['consignee_address'],
				'pending_datetime_label'=>$val['pending_datetime_label'],
				'job'=>array(
					'status_label'=>$val['job_status'],
					'new_datetime_label'=>$val['get_datetime'],
					'inprogress_datetime_label'=>$val['updated_at'],
					'complete_datetime_label'=>$val['received_at'],
					'receiver_name'=>$val['receiver_name']
				),
				'csn'=>$detail['csn'],
				'detail_pieces'=>$detail['pieces'],
				'customer_ref'=>$customer_ref,
				'service_label'=>(isset($val['service_label'])?$val['service_label']:''),
				'cod_value'=>$val['cod_value'],

				
				// 'url_pdf'=>'http://af1express.com/gen_connote/'.$key,
				// 'cod'=>$val['cod'],
				// 'customer_ref'=>$val['customer_ref']
			);
		}
		echo json_encode($result);
	}
?>