<?php 
	require_once('../required/connect.php');
	if(isset($_GET['type'])){
		if($_GET['type'] == 'report_connote'){
			$result = array();
			
			$book_date_start = date('Y-m-d');
			$book_date_end = date('Y-m-d');
			$job_date_start = '';
			$job_date_end = '';
			$msg_date_start = '';
			$msg_date_end = '';
			$status_chosen ='';
			$customers='';
			$where = '';
			if(isset($_GET['book_date_start'])){
				$date = str_replace('/', '-', $_GET['book_date_start'] );
				$book_date_start = date("Y-m-d", strtotime($date));
			}
			if(isset($_GET['book_date_end'])){
				$date = str_replace('/', '-', $_GET['book_date_end'] );
				$book_date_end = date("Y-m-d", strtotime($date));
			}
			if(isset($_GET['job_date_start'])){
				$date = str_replace('/', '-', $_GET['job_date_start'] );
				$job_date_start = date("Y-m-d", strtotime($date));
			}
			if(isset($_GET['job_date_end'])){
				$date = str_replace('/', '-', $_GET['job_date_end'] );
				$job_date_end = date("Y-m-d", strtotime($date));
			}
			if(isset($_GET['msg_date_start'])){
				$date = str_replace('/', '-', $_GET['msg_date_start'] );
				$msg_date_start = date("Y-m-d", strtotime($date));
			}
			if(isset($_GET['msg_date_end'])){
				$date = str_replace('/', '-', $_GET['msg_date_end'] );
				$msg_date_end = date("Y-m-d", strtotime($date));
			}
			if(isset($_GET['status_chosen'])){
				if($_GET['status_chosen']!='all'){
					$where .= ' and b.status='.$_GET['status_chosen'].' ';
				}
			}
			if(isset($_GET['customers'])){
				if($_GET['customers']!='all'){
					$where .= ' and b.customer_id='.$_GET['customers'].' ';
				}
			}
			$sql = "SELECT 
				b.`key` as booking_no,
				b.`created_at` as date_start,
				b.`customer_key` as company_key,
				b.`customer_name` as company_sender,
				b.`district` as company_district,
				b.`province` as company_province,
				b.`postcode` as company_postcode,
				b.`person` as company_person,
				b.`address` as company_address,
				b.`express` as service_level,
				c.service as service_type,
				'' as size_box,
				c.updated_at as time_pickup,
				'' as service_comment,
				'' as customer_ref,
				c.created_at as time_send,
				c.consignee_name as consignee_name,
				c.consignee_phone as consignee_phone,
				c.consignee_company as consignee_company,
				c.consignee_address as consignee_address,
				c.consignee_district as consignee_districe,
				c.consignee_province as consignee_province,
				c.consignee_postcode as consignee_postcode,
				c.`key` as connote_key,
				c.`status` as connotes_status,
				j.receiver_name as sign_name,
				j.received_at as time_send,
				IF(c.service='return','',j.received_at) as time_return,
				j.notes as return_comment,
				c.details as detail
			FROM connotes c 
			INNER JOIN bookings b ON c.booking_id = b.id 
			INNER JOIN jobs j ON j.connote_id = c.id
			WHERE (b.created_at >= '".$book_date_start." 0:00:00' AND b.created_at <= '".$book_date_end." 23:59:59') 
			".$where;
			
			$result_booking = $obj_db->query($sql);
			// echo "WHERE (b.created_at >= '".$book_date_start." 0:00:00' AND b.created_at <= '".$book_date_end." 23:59:59') ".$where;
			// $result = $result_booking->rows;
// var_dump($result_booking->rows);exit();
			$i=1;
			foreach($result_booking->rows as $val){
				$sender = json_decode($val['company_person'],true);
				$detail = json_decode($val['detail'],true);
				$note 	= json_decode($val['note'],true);
				$result[] = array(
					'no'				=> $i++,
					'booking_no'		=> $val['booking_no'],
					'date_start'		=> $val['date_start'],
					'company_key'		=> $val['company_key'],
					'company_sender'	=> $val['company_sender'],
					'company_district'	=> $val['company_district'],
					'company_province'	=> $val['company_province'],
					'company_postcode'	=> $val['company_postcode'],
					'company_person'	=> $sender['name'],
					'company_address'	=> $val['company_address'],
					'company_phone'		=> $sender['phone'],
					'service_level'		=> $val['service_level'],
					'service_type'		=> $val['service_type'],
					'size_box'			=> $detail['pieces'][0],
					'time_pickup'		=> $val['time_pickup'],
					'service_comment'	=> $note['note_that'],
					'customer_ref'		=> $detail['customer_ref'],
					'time_send'			=> $val['time_send'],
					'consignee_name'	=> $val['consignee_name'],
					'consignee_phone'	=> $val['consignee_phone'],
					'consignee_company'	=> $val['consignee_company'],
					'consignee_address'	=> $val['consignee_address'],
					'consignee_districe'=> $val['consignee_districe'],
					'consignee_province'=> $val['consignee_province'],
					'consignee_postcode'=> $val['consignee_postcode'],
					'connote_key'		=> $val['connote_key'],
					'connotes_status'	=> $val['connotes_status'],
					'sign_name'			=> $val['sign_name'],
					'time_send'			=> $val['time_send'],
					'time_return'		=> $val['time_return'],
					'return_comment'	=> $val['return_comment']
				);
			}
			// echo $sql;
			echo json_encode($result);
		}else if($_GET['type']=="get_customers"){
			$result = array();
			$result = $obj_db->query("SELECT * FROM customers");
			echo json_encode($result->rows);
		}
	}
?>