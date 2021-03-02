<?php namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Home\Controller;
use App\Services\Customers\CustomerRepository;
use App\Services\PDFs\PDFRepository;
use App\Services\Connotes\ConnoteRepository;

class MainController extends Controller {

	public function getIndex()
	{
		$data = [];//compact('meta');
		return $this->view('home.pages.main.index', $data);
	}

	private function dataForPDF()
	{
		return (object)[
			'person'	=> '',
			'company'	=> '',
			'address'	=> '',
			'district'	=> '',
			'province'	=> '',
			'postcode'	=> '',
			'mobile'	=> '',
			'customer_ref' => '',
			'cod' => '',
		];
	}

	public function getGenNewConnote($customer_key, $point_id, $connote_key,$cod,$cr,$price, Request $request)
	{
		global $obj_db;

		// echo $customer_key;exit();
		$customerRepo = new CustomerRepository;
		$customerModel = $obj_db->getdataobj('customers',"`id`='".$customer_key."'");


		// var_dump($point);exit();
		// $customerModel = $customerRepo->getByKey($customer_key);
		// echo "<pre>";
		// var_dump($customerModel);exit();
		$pointModel = [];
		if(isset($customerModel->points)){
			foreach ($customerModel->points as $point) {
				if ($point_id == $point->id){
					$pointModel = $point;
				}
			}
		}

		$shipper = $this->dataForPDF();
		if(isset($customerModel)){
			$shipper->person = $customerModel->person;
			$shipper->company = $customerModel->name;
			$shipper->address = $customerModel->address;
			$shipper->district = $customerModel->district;
			$shipper->province = $customerModel->province;
			$shipper->postcode = $customerModel->postcode;
			$shipper->mobile = $customerModel->mobile;
		}
		$point = $this->dataForPDF();
		// var_dump($request);exit();
		if (!empty($pointModel)) {
			$point->person = $pointModel->person;
			$point->company = $pointModel->name;
			$point->address = $pointModel->address;
			$point->district = $pointModel->district;
			$point->province = $pointModel->province;
			$point->postcode = $pointModel->postcode;
			$point->mobile = $pointModel->mobile;
			if (!empty($request->input('customer_ref'))){
				$point->customer_ref = $request->input('customer_ref');
			}
			if (!empty($request->input('cod'))){
				$point->cod = $request->input('cod');
			}
		}

		// var_dump($cod);
		// exit();
		$pdfRepo = new PDFRepository;
		$point = $obj_db->getdataobj('points',"`id`='".$point_id."'");
		// $point = $obj_db->getdata('points',"`id`='".$point_id."'");
		// var_dump($point);exit();
		$point->company = $point->name;
		
		// var_dump($point);exit();
		return $pdfRepo->genConnote($shipper, $point, $connote_key,$cod,$cr,$price);
		die();
	}

	public function getGenConnote($connote_key)
	{
		// $connoteRepo = new ConnoteRepository;
		// $connoteModel = $connoteRepo->getByKey($connote_key);

		// if (empty($connoteModel)) return view('errors.page_not_found');
		global $obj_db;
		$connoteModel = $obj_db->getdataobj('connotes',"`key`='".$connote_key."'");
		$connoteModel->booking = $obj_db->getdataobj('bookings',"`id`='".$connoteModel->booking_id."'");
		// if (empty($connoteModel)) return view('errors.page_not_found');

		$details = helperJsonDecodeToArray($connoteModel->details);
		// var_dump($details);exit();
		$consignee = $details['csn'];

		$shipper = $this->dataForPDF();
		$shipper->person = $connoteModel->shipper_name;
		$shipper->company = $connoteModel->shipper_company;

		$shipper->address = $connoteModel->booking->address;
		$shipper->district = $connoteModel->booking->district;
		$shipper->province = $connoteModel->booking->province;
		$shipper->postcode = $connoteModel->booking->postcode;
		$shipper->mobile = $connoteModel->shipper_phone;

		$point = $this->dataForPDF();
		$point->person = $connoteModel->consignee_name;
		$point->company = $connoteModel->consignee_company;
		$point->address = $connoteModel->consignee_address;
		$point->district = $connoteModel->consignee_district;
		$point->province = $connoteModel->consignee_province;
		$point->postcode = $connoteModel->consignee_postcode;
		$point->mobile = $connoteModel->consignee_phone;

		$point->cod = !empty($connoteModel->cod_value) ? $connoteModel->cod_value : '';
		$details = helperJsonDecodeToArray($connoteModel->details);
		$csn = !empty($details['csn']) ? $details['csn'] : '';
		// var_dump($details);exit();
		$point->customer_ref = (!empty($details['csn']->customer_ref)?$details['csn']->customer_ref:$connoteModel->customer_ref);//!empty($csn) ? $csn->customer_ref : '';
		// echo $point->customer_ref;exit();
		$cod=$connoteModel->cod;
		$cr=$point->customer_ref;
		$price=$connoteModel->cod_value;
		// var_dump($connoteModel->customer_ref);exit();
		$pdfRepo = new PDFRepository;
		return $pdfRepo->genConnote($shipper, $point, $connoteModel->key,$cod,$cr,$price);
		die();
	}
}