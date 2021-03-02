<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Logs\LogBookingRepository;
use App\Services\Employees\EmployeeRepository;

class ApiBookingUpdateController extends ApiBaseController {

	protected $class = 'BookingUpdate';

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
					'index' 	=> urlApi().'/booking_update',
					'store' 	=> urlApi().'/booking_update',
					'update' 	=> urlApi().'/booking_update/{key}',
					'destroy' 	=> urlApi().'/booking_update/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> '',
					'store' 	=> 'Update Booking Status',
					'update' 	=> '',
					'destroy' 	=> '',
				];

		return isset($desc[$method]) ? $desc[$method] : '';
	}

	public function storeModel()
	{
		$input 	= [
			'token'			=> ['type' => 'string', 'req' => 'required', 'desc' => 'Verify Token'],
			'emp_id'		=> ['type' => 'string', 'req' => 'required', 'desc' => 'Employee ID'],
			'booking_key'	=> ['type' => 'string', 'req' => 'required', 'desc' => 'Booking Key'],
			'new_status'	=> ['type' => 'string', 'req' => 'required', 'desc' => 'Booking Status (inprogress, complete, cancel'],
		];

	    $output = [
			'key' 				=> ['type' => 'string', 'desc' => 'Booking Key'],
	    	'status'			=> ['type' => 'string', 'desc' => 'Status'],
	    ];

	    return [
	        'parameter' 	=> $input,
	        'success'   	=> $output,
	    ];
	}

	public function store(Request $request)
	{
		// Get Model.
		$model = $this->storeModel();

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

		// GET BOOKING.
		$bookingModel = $this->bookingRepo->getByKey($request->input('booking_key'));

		// Data Not Found.
		if (empty($bookingModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->dataNotFound();
		}

		// UPDATE BOOKING.
		$result = $this->bookingRepo->updateStatus($bookingModel, $request->input('new_status'));

		// Status Invalid.
		if ($result['status'] != 'success') {
			$this->logApiRepo->fail($this->class, $request->method(), 'status_invalid');
			return $this->apiResponse->customFail($result['msg']);
		}

		$bookingModel = $result['data'];

		// LOG.
		$empRepo = new EmployeeRepository;
		$empModel = $empRepo->getByEmpKey($request->input('emp_id'));
		if (!empty($empModel)) {
			$logBkg = new LogBookingRepository;
			$logBkg->put($bookingModel, $empModel->nickname);
		}

		// Response.
		$response[] = $this->map($model['success'], $bookingModel);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function map($schema, $model)
	{
		$result = [];

		foreach ($schema as $key => $d) {
			$result[$key] = '';
		}

		$result['key'] = $model->key;
		$result['status'] = $model->status;

		return $result;
	}

}
