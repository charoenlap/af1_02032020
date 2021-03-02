<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\Jobs\JobRepository;
use App\Services\Employees\EmployeeRepository;

class ApiNewFeedController extends ApiBaseController {

	protected $class = 'NewFeed';

	public function __construct()
	{
		$this->apiResponse 	= new ApiResponseService;
		$this->logApiRepo 	= new LogApiRepository;
		$this->bookingRepo  = new BookingRepository;
		$this->jobRepo  	= new JobRepository;
	}

	public function className()
	{
		return $this->class;
	}

	public function path($method)
	{
		$path = [
					'index' 	=> urlApi().'/feed',
					'store' 	=> urlApi().'/feed',
					'update' 	=> urlApi().'/feed/{key}',
					'destroy' 	=> urlApi().'/feed/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> 'Get New Feed',
					'store' 	=> '',
					'update' 	=> '',
					'destroy' 	=> '',
				];

		return isset($desc[$method]) ? $desc[$method] : '';
	}

	public function indexModel()
	{
		$input 	= [
			'token'		=> ['type' => 'string', 'req' => 'required', 'desc' => 'Verify Token'],
			'emp_id'	=> ['type' => 'string', 'req' => 'required', 'desc' => 'Employee ID'],
		];

	    $feed = [
			'type' 				=> ['type' => 'string', 'desc' => 'Type (booking/job)'],
			'id' 				=> ['type' => 'string', 'desc' => 'Booking Key or Job Key'],
			'title'				=> ['type' => 'string', 'desc' => 'Customer Name'],
	    	'subtitle'			=> ['type' => 'string', 'desc' => 'District'],
	    	'desc'				=> ['type' => 'string', 'desc' => 'Province'],
	    	'datetime'			=> ['type' => 'string', 'desc' => 'Date time for display'],
	    	'cod'				=> ['type' => 'string', 'desc' => 'COD 0 or 1'],
	    ];

	    $output = [
			'feeds' 	=> ['type' => 'array', 'desc' => 'New Feed List', 'data' => $feed],
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
		$bookingModels = $this->bookingRepo->getBookingTodayByEmpKey($request->input('emp_id'));
		// Get Jobs.
		$jobModels = $this->jobRepo->getTodayByEmpKey($request->input('emp_id'));

		$empRepo = new EmployeeRepository;
		$empModel = $empRepo->getByEmpKey($request->input('emp_id'));

		if (empty($empModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->dataNotFound();
		}
		// Data Not Found.
		// if ($bookingModels->count() + $jobModels->count() <= 0) {
		// }
		// Response.
		$response[] = $this->map($model['success'], $bookingModels, $jobModels, $empModel);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function map($schema, $bookings, $jobs, $empModel)
	{
		$results = [];
		$feed 	= $schema['feeds']['data'];

		foreach ($feed as $key => $result) {
			$feed[$key] = '';
		}

		foreach ($bookings as $key => $booking) {

			$booking = $this->bookingRepo->buildAttr($booking);
			$result = $feed;
			$result['type'] = 'booking';
			$result['id'] = $booking->key;
			$result['title'] = 'งานรับของ '.$booking->key;
			$result['subtitle'] = $booking->customer_name;
			$result['desc'] = $booking->district.' '.$booking->province;
			$result['datetime'] = 'เกิดขึ้นเมื่อ '.helperDateCountTimePassed($booking->updated_at, 'th', true);
			$result['cod'] = $booking->cod;
			$results['feeds'][$booking->updated_at.$booking->id] = $result;
		}

		foreach ($jobs as $key => $job) {

			$job = $this->jobRepo->buildAttr($job);
			$result = $feed;
			$result['type'] = 'job';
			$result['id'] = $job->key;
			$result['title'] = 'งานส่งของ '.$job->key;
			$result['subtitle'] = $job->connote->consignee_name;
			$result['desc'] = $job->district.' '.$job->province.' '.$job->postcode;
			$result['datetime'] = 'เกิดขึ้นเมื่อ '.helperDateCountTimePassed($job->updated_at, 'th', true);
			$results['feeds'][$job->updated_at.$job->id] = $result;
		}

		$hello['type'] = 'general';
		$hello['id'] = 0;
		$hello['title'] = 'สวัสดี '.$empModel->nickname.' วันนี้เป็นวันที่ดีของคุณ';
		$hello['subtitle'] = 'AirForceOne Express';
		$hello['desc'] = 'ถึงที่หมายฉับไว ปลอดภัยไร้อุบัติเหตุ';
		$hello['datetime'] = '';
		$results['feeds'][date('Y-m-d H:i:s')] = $hello;

		krsort($results['feeds']);
		$results['feeds'] = array_values($results['feeds']);

		return $results;
	}
}
