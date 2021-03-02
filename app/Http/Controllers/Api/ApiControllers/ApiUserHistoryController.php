<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\HashID\HashPasscode;
use App\Services\Bookings\BookingRepository;

class ApiUserHistoryController extends ApiBaseController {

	protected $class = 'UserHistory';

	public function __construct()
	{
		$this->apiResponse 	= new ApiResponseService;
		$this->logApiRepo 	= new LogApiRepository;
		$this->employeeRepo = new EmployeeRepository;
	}

	public function className()
	{
		return $this->class;
	}

	public function path($method)
	{
		$path = [
					'index' 	=> urlApi().'/history',
					'store' 	=> urlApi().'/history',
					'update' 	=> urlApi().'/history/{key}',
					'destroy' 	=> urlApi().'/history/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> '',
					'store' 	=> 'User History',
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
		];

	    $output = [
	    	'type'				=> ['type' => 'string', 'desc' => 'Type'],
			'title'				=> ['type' => 'string', 'desc' => 'Title'],
	    	'subtitle'			=> ['type' => 'string', 'desc' => 'SubTitle'],
	    	'desc'				=> ['type' => 'string', 'desc' => 'Description'],
	    	'datetime_display'	=> ['type' => 'string', 'desc' => 'Date time for display'],
	    	'details'			=> ['type' => 'JSON', 'desc' => 'Details (JSON Format)'],
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

		// Get User.
		$userModel = $this->employeeRepo->getByEmpKey($request->input('emp_id'));

		// Data Not Found.
		if (empty($userModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->dataNotFound();
		}

		$bookingRepo = new BookingRepository;
 		$models = $bookingRepo->getByMsgKeyLimitPreviousMonth($userModel->emp_key);

		// Response.
		$response = $this->mapBooking($model['success'], $models);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function mapBooking($schema, $models)
	{
		$results = [];
		$data = [];
		foreach ($schema as $key => $value) {
			$data[$key] = '';
		}

		foreach ($models as $model) {
			$bookingRepo = new BookingRepository;
			$model = $bookingRepo->buildAttr($model);

			$result = $data;
			$result['type'] 		= 'booking';
			$result['title'] 		= config('labels.menu.booking.th').' '.$model->key;
			$result['subtitle'] 	= '';
			$result['desc'] 		= '';
			$result['datetime_display'] = $model->get_datetime_label;
			$details = [];
			$details['รหัสรับสินค้า'] 	= $model->key;
			$details['ชื่อลูกค้า'] 	= $model->person_name;
			$details['เบอร์ลูกค้า']  	= $model->person_mobile;
			$details['ชื่อบริษัท'] 	= $model->customer_name;
			$details['รหัสบริษัท'] 		= $model->customer_key;
			$details['สถานที่'] 		= $model->address.' '.$model->district.' '.$model->province.' '.$model->postcode;
			$details['รับสินค้าเวลา'] 	= $model->get_datetime_label;
			$details['ชื่อ CS'] 		= $model->cs_name;
			$result['details'] 		= [$details];

			$results[] = $result;
		}

		return $results;
	}
}
