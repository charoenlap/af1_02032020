<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Employees\EmployeeRepository;

class ApiBookingDetailController extends ApiBaseController {

	protected $class = 'BookingDetail';

	public function __construct()
	{
		$this->apiResponse 	= new ApiResponseService;
		$this->logApiRepo 	= new LogApiRepository;
		$this->bookingRepo  = new BookingRepository;
		$this->connoteRepo  = new ConnoteRepository;
	}

	public function className()
	{
		return $this->class;
	}

	public function path($method)
	{
		$path = [
					'index' 	=> urlApi().'/booking_detail',
					'store' 	=> urlApi().'/booking_detail',
					'update' 	=> urlApi().'/booking_detail/{key}',
					'destroy' 	=> urlApi().'/booking_detail/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> 'Get Booking Detail with Connotes',
					'store' 	=> 'Add New Connotes',
					'update' 	=> '',
					'destroy' 	=> '',
				];

		return isset($desc[$method]) ? $desc[$method] : '';
	}

	public function indexModel()
	{
		$input 	= [
			'token'			=> ['type' => 'string', 'req' => 'required', 'desc' => 'Verify Token'],
			'emp_id'		=> ['type' => 'string', 'req' => 'required', 'desc' => 'Employee ID'],
			'booking_key'	=> ['type' => 'string', 'req' => 'required', 'desc' => 'Booking Key'],
		];

		$connotes = [
			'order' 			=> ['type' => 'string', 'desc' => 'Order'],
			'connote_id' 		=> ['type' => 'string', 'desc' => 'ConNote ID'],
			'connote_no' 		=> ['type' => 'string', 'desc' => 'ConNote Number'],
			'service' 			=> ['type' => 'string', 'desc' => 'Service Type (oneway or return)'],
			'service_display' 	=> ['type' => 'string', 'desc' => 'Service Type for Display'],
			'cod_value' 		=> ['type' => 'string', 'desc' => 'Cash Value'],
			'cod_value_display' => ['type' => 'string', 'desc' => 'Cash Value for Display'],
			'updated' 			=> ['type' => 'string', 'desc' => 'Updated Date Time'],
			'updated_display' 	=> ['type' => 'string', 'desc' => 'Updated Date Time for Display'],
			'line_1'	 		=> ['type' => 'string', 'desc' => 'Line 1'],
			'line_2'	 		=> ['type' => 'string', 'desc' => 'Line 2'],
			'line_3'	 		=> ['type' => 'string', 'desc' => 'Line 3'],
		];

	    $output = [
			'key' 				=> ['type' => 'string', 'desc' => 'Booking Key'],
			'phone'				=> ['type' => 'string', 'desc' => 'Contact Number'],
	    	'status'			=> ['type' => 'string', 'desc' => 'Status'],
	    	'status_label'		=> ['type' => 'string', 'desc' => 'Status Label'],
			'person' 			=> ['type' => 'string', 'desc' => 'Person Name'],
			'name'				=> ['type' => 'string', 'desc' => 'Customer Name'],
			'address' 			=> ['type' => 'string', 'desc' => 'Address'],
	    	'district'			=> ['type' => 'string', 'desc' => 'District'],
	    	'province'			=> ['type' => 'string', 'desc' => 'Province'],
	    	'cod' 				=> ['type' => 'string', 'desc' => 'is COD (0 or 1)'],
	    	'express' 			=> ['type' => 'string', 'desc' => 'is Express (0 or 1)'],
	    	'datetime' 			=> ['type' => 'string', 'desc' => 'Date time'],
	    	'datetime_display'	=> ['type' => 'string', 'desc' => 'Date time for display'],
			'line_1'	 		=> ['type' => 'string', 'desc' => 'Line 1'],
			'line_2'	 		=> ['type' => 'string', 'desc' => 'Line 2'],
			'line_3'	 		=> ['type' => 'string', 'desc' => 'Line 3'],
			'line_4'	 		=> ['type' => 'string', 'desc' => 'Line 4'],
	    	'warning_msg'		=> ['type' => 'string', 'desc' => 'Warning Message'],
	    	'connotes' 			=> ['type' => 'array', 'desc' => 'Array of Connote Data', 'data' => $connotes],
	    ];

	    return [
	        'parameter' 	=> $input,
	        'success'   	=> $output,
	    ];
	}

	public function index(Request $request)
	{
		// Get Model.
		$model = $this->indexModel();

		// Permission.
		if (!$this->apiResponse->checkPermission($request)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'permission_denied');
			return $this->apiResponse->permissionFail();
		}

		// Validation.
		$valids = $this->apiResponse->checkValidation($request, $model['parameter']);
		if (!empty($valids)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'validation_fail');
			return $this->apiResponse->validationFail($valids);
		}

		// Get Booking.
		$bookingModel = $this->bookingRepo->getByKey($request->input('booking_key'));

		// Data Not Found.
		if (empty($bookingModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->dataNotFound();
		}

		// Response.
		$response[] = $this->map($model['success'], $bookingModel);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function map($schema, $model, $warning_msg = '')
	{
		$result = [];
		$connotes = [];
		$connote = [];

		foreach ($schema['connotes']['data'] as $key => $c) {
			$connote[$key] = '';
		}
		foreach ($schema as $key => $d) {
			$result[$key] = '';
		}

		$result['key'] = $model->key;
		$result['status'] = $model->status;
		$result['status_label'] = $this->bookingRepo->{'label_'.$model->status};
		$result['name'] = $model->customer_name;

		$persons = helperJsonDecodeToArray($model->person);
		$result['person'] = isset($persons['name']) ? $persons['name'] : '';
		$result['phone'] = isset($persons['mobile']) ? $persons['mobile'] : '';

		$result['address'] = $model->address;
		$result['district'] = $model->district;
		$result['province'] = $model->province;

		$result['cod'] = ($model->cod) ? '1' : '0';
		$result['express'] = ($model->express) ? '1' : '0';
		$result['datetime'] = $model->get_datetime;
		$result['datetime_display'] = 'เวลารับสินค้า '.helperDateFormat($model->get_datetime, 'H:i น.');
		$result['line_1'] = $model->customer_name;
		$result['line_2'] = $model->address.' '.$model->district.' '.$model->province.' '.$model->postcode;
		$result['line_3'] = 'เวลารับสินค้า '.helperDateFormat($model->get_datetime, 'H:i น.');
		$note = helperJsonDecodeToArray($model->note);
		$result['line_4'] = !empty($note['note_that']) ? $note['note_that'] : '';
		$result['warning_msg'] = $warning_msg;

		foreach($model->connotes as $i => $cn) {

			if ($cn->status == $this->connoteRepo->confirm
				|| $cn->status == $this->connoteRepo->pending) {

				$data = $connote;
				$data['order'] = $i+1;
				$data['connote_id'] = $cn->id;
				$data['connote_no'] = ($cn->status == $this->connoteRepo->confirm) ? $cn->key : '';
				$data['service'] = $cn->service;
				$data['service_display'] = $this->connoteRepo->{'label_'.$cn->service};
				$data['cod_value'] = !empty($cn->cod_value) ? $cn->cod_value : '';
				$data['cod_value_display'] = !empty($cn->cod_value) ? 'COD: '.number_format($cn->cod_value).' บาท' : '';
				$data['updated'] = $cn->updated_at->format('Y-m-d H:i:s');

				$date = helperThaiFormat($cn->updated_at->format('Y-m-d H:i:s'), true);
				$time = $cn->updated_at->format('เวลา H:i น.');
				$data['updated_display'] = $date.' '.$time;

				$data['line_1'] = !empty($data['connote_no']) ? $data['connote_no'] : 'ยังไม่มีเลข Connote';
				$data['line_2'] = !empty($cn->consignee_name) ? $cn->consignee_name.' @ '.$cn->consignee_company : 'ยังไม่มีที่อยู่ปลายทาง';
				$data['line_3'] = ($model->cod == '1') ? $data['cod_value_display'] : $data['service_display'];

				$connotes[] = $data;
			}
		}

		$result['connotes'] = $connotes;

		return $result;
	}

	public function storeModel()
	{
		$input 	= [
			'token'			=> ['type' => 'string', 'req' => 'required', 'desc' => 'Verify Token'],
			'emp_id'		=> ['type' => 'string', 'req' => 'required', 'desc' => 'Employee ID'],
			'booking_key'	=> ['type' => 'string', 'req' => 'required', 'desc' => 'Booking Key'],
			'connotes' 		=> ['type' => 'json', 'req' => 'required',
								'desc' => 'ConNote in Json Format ([{no: xxxxx, service: oneway or return, id: xxx}])'],
		];

	    $connotes = [
			'order' 			=> ['type' => 'string', 'desc' => 'Order'],
			'connote_id' 		=> ['type' => 'string', 'desc' => 'ConNote ID'],
			'connote_no' 		=> ['type' => 'string', 'desc' => 'ConNote Number'],
			'service' 			=> ['type' => 'string', 'desc' => 'Service Type (oneway or return)'],
			'service_display' 	=> ['type' => 'string', 'desc' => 'Service Type for Display'],
			'updated' 			=> ['type' => 'string', 'desc' => 'Updated Date Time'],
			'updated_display' 	=> ['type' => 'string', 'desc' => 'Updated Date Time for Display'],
			'line_1'	 		=> ['type' => 'string', 'desc' => 'Line 1'],
			'line_2'	 		=> ['type' => 'string', 'desc' => 'Line 2'],
			'line_3'	 		=> ['type' => 'string', 'desc' => 'Line 3'],
		];

	    $output = [
			'key' 				=> ['type' => 'string', 'desc' => 'Booking Key'],
	    	'status'			=> ['type' => 'string', 'desc' => 'Status'],
	    	'status_label'		=> ['type' => 'string', 'desc' => 'Status Label'],
	    	'person'			=> ['type' => 'string', 'desc' => 'Person Name'],
			'name'				=> ['type' => 'string', 'desc' => 'Customer Name'],
			'phone'				=> ['type' => 'string', 'desc' => 'Contact Number'],
			'address' 			=> ['type' => 'string', 'desc' => 'Address'],
	    	'district'			=> ['type' => 'string', 'desc' => 'District'],
	    	'province'			=> ['type' => 'string', 'desc' => 'Province'],
	    	'cod' 				=> ['type' => 'string', 'desc' => 'is COD (0 or 1)'],
	    	'express' 			=> ['type' => 'string', 'desc' => 'is Express (0 or 1)'],
	    	'datetime' 			=> ['type' => 'string', 'desc' => 'Date time'],
	    	'datetime_display'	=> ['type' => 'string', 'desc' => 'Date time for display'],
	    	'line_1'	 		=> ['type' => 'string', 'desc' => 'Line 1'],
			'line_2'	 		=> ['type' => 'string', 'desc' => 'Line 2'],
			'line_3'	 		=> ['type' => 'string', 'desc' => 'Line 3'],
			'line_4'	 		=> ['type' => 'string', 'desc' => 'Line 4'],
	    	'warning_msg'		=> ['type' => 'string', 'desc' => 'Warning Message'],
	    	'connotes' 			=> ['type' => 'array', 'desc' => 'Array of Connote Data', 'data' => $connotes],
	    ];

	    return [
	        'parameter' 	=> $input,
	        'success'   	=> $output,
	    ];
	}

	public function store(Request $request)
	{
		$model = $this->storeModel();

		// PERMISSION.
		if (!$this->apiResponse->checkPermission($request)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'permission_denied');
			return $this->apiResponse->permissionFail();
		}

		// INPUT VALIDATION.
		$valids = $this->apiResponse->checkValidation($request, $model['parameter']);
		if (!empty($valids)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'validation_fail');
			return $this->apiResponse->validationFail($valids);
		}

		// GET EMPLOYEE.
		$empRepo = new EmployeeRepository;
		$empModel = $empRepo->getByEmpKey($request->input('emp_id'));
		if (empty($empModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'permission_denied');
			return $this->apiResponse->permissionFail();
		}

		// CONNOTE JSON VALIDATION.
		$connotes = helperJsonDecodeToArray($request->input('connotes'));

		if (empty($connotes)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'validation_fail');
			return $this->apiResponse->customFail('ไม่พบเลข Connote');
		}

		// KEEP CONNOTE NUMBER.
		$connote_array = [];

		foreach ($connotes as $connote) {

			// CHECK VALIDATION in CONNOTE.
			if (!isset($connote->no) || !isset($connote->service)) {
				$this->logApiRepo->fail($this->class, $request->method(), 'validation_fail');
				return $this->apiResponse->customFail('พบเลข Connote ไม่ถูกต้อง');
			}

			$connote_array[] = $connote->no;
		}

		// GET BOOKING.
		$bookingModel = $this->bookingRepo->getByKey($request->input('booking_key'));

		// BOOKING NOT FOUND.
		if (empty($bookingModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->dataNotFound();
		}
		// BOOKING STATUS VALIDATION.
		if ($bookingModel->status != $this->bookingRepo->inprogress) {
			$this->logApiRepo->fail($this->class, $request->method(), 'validation_fail');
			return $this->apiResponse->customFail('สถานะไม่ถูกต้อง');
		}

		// BOOKING MODEL.
		$bookingModel = $this->bookingRepo->buildAttr($bookingModel);

		// SELECTION CONNOTE.
		$newConnotes = [];
		$updateConnotes = [];
		$dupConnotes = [];
		$existConnotes = $this->connoteRepo->getByKeys($connote_array);

		foreach ($connotes as $key => $connote):

			$found = false;

			foreach ($existConnotes as $existConnote):

				if ($connote->no == $existConnote->key) {

					$found = true;

				  	if ($existConnote->booking_id == $bookingModel->id) {

						$updateConnotes[] = $connote;

					} else {

						$dupConnotes[] = $connote;
					}
				}
			endforeach;

			if (!$found) $newConnotes[] = $connote;

		endforeach;

		// UPDATE COD CONNOTE.
		if ($bookingModel->cod == 1) {

			$connoteModels = $this->connoteRepo->updateCodConnotes($bookingModel->connotes, $updateConnotes, $empModel->nickname);

		// UPDATE NORMAL CONNOTE.
		} else {

			$connotes = $this->connoteRepo->updateConnotes($bookingModel->connotes, $updateConnotes, $empModel->nickname);
			$update_result = $this->connoteRepo->createConnotes($bookingModel, $newConnotes, $cod = 0, $from = 'app', $empModel->nickname);
		}

		$warning_msg = '';
		foreach ($dupConnotes as $dupConnote) {
			$warning_msg .= (($warning_msg == '') ? 'พบเลข connote ซ้ำกับงานอื่น : ' : ',').$dupConnote->no;
		}

		// Get Booking Again.
		$bookingModel = $this->bookingRepo->getByKey($request->input('booking_key'));

		// Response.
		$response[] = $this->map($model['success'], $bookingModel, $warning_msg);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}
}
