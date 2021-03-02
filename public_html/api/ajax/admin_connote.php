<?php 
	require_once('../required/connect.php');
	if(isset($_GET['type'])){
		if($_GET['type']=="getConnote"){

			// $page = (int)$_GET['page'];
			
			// if($page > 0){
			// 	$page = $page*10;
			// }
			// $limit = '10';

			// $where = " id <> '' ";
			// if (!empty($_GET['start_date'])) {
			// 	$where .= ' and created_at >='.$_GET['start_date'].' 00:00:00 ';
			// }
			// if (!empty($_GET['end_date'])) {
			// 	$where .= ' and created_at <='.$_GET['end_date'].' 23:59:59 ';
			// }
			// if (!empty($_GET['connote_key'])) {
			// 	$where .= ' and key LIKE %'.$_GET['connote_key'].'% ';
			// }
			// if (!empty($_GET['shipper_name'])) {
			// 	$where .= ' and shipper_name LIKE %'.$_GET['shipper_name'].'% ';
			// }
			// if (!empty($_GET['consignee_name'])) {
			// 	$where .= ' and consignee_name LIKE %'.$_GET['consignee_name'].'% ';
			// }
			// if (!empty($_GET['customer_ref'])) {
			// 	$where .= ' and details LIKE %'.$_GET['customer_ref'].'% ';
			// }
			
			// $where .= ' ORDER BY id ASC limit '.$page.','.$limit;

			// $result_booking = $obj_db->getdata('jobs',$where);
			// // $result = array(
			// // foreach($result_booking->rows as $val){
			// 	// $result[] = array(
					
			// 	// );
			// // }
			// echo json_encode($result_booking);
		}
	}
?>