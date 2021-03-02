<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;
use App\Services\Logs\LogApiRepository;

use App\Services\Bookings\BookingRepository;

class ApiBookingListController extends ApiBaseController {

	protected $class = 'BookingList';

	public function __construct()
	{
		$this->apiResponse 	= new ApiResponseService;
		$this->logApiRepo 	= new LogApiRepository;
		$this->bookingRepo  = new BookingRepository;
	}

	public function className()
	{
		return $this->class;
	}

	public function path($method)
	{
		$path = [
					'index' 	=> urlApi().'/booking_list',
					'store' 	=> urlApi().'/booking_list',
					'update' 	=> urlApi().'/booking_list/{key}',
					'destroy' 	=> urlApi().'/booking_list/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> 'Get Booking List',
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
			'key' 				=> ['type' => 'string', 'desc' => 'Booking Key'],
	    	'status'			=> ['type' => 'string', 'desc' => 'Status'],
	    	'status_label'		=> ['type' => 'string', 'desc' => 'Status Label'],
			'title'				=> ['type' => 'string', 'desc' => 'Title'],
	    	'subtitle'			=> ['type' => 'string', 'desc' => 'SubTitle'],
	    	'desc'				=> ['type' => 'string', 'desc' => 'Description'],
	    	'cod' 				=> ['type' => 'string', 'desc' => 'is COD (0 or 1)'],
	    	'express' 			=> ['type' => 'string', 'desc' => 'is Express (0 or 1)'],
	    	'datetime' 			=> ['type' => 'string', 'desc' => 'Date time'],
	    	'datetime_display'	=> ['type' => 'string', 'desc' => 'Date time for display'],
	    ];

	    $output = [
			'bookings' 	=> ['type' => 'array', 'desc' => 'Booking List', 'data' => $feed],
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

		// GET BOOKING.
		$bookingModels = $this->bookingRepo->getForApp($request->input('emp_id'));

		if ($bookingModels->count() <= 0) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->dataNotFound();
		}

		// Response.
		$response[] = $this->mapIndex($model['success'], $bookingModels);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function mapIndex($schema, $models)
	{
		$results = [];
		$feed 	= $schema['bookings']['data'];

		foreach ($feed as $key => $result) {
			$feed[$key] = '';
		}

		foreach ($models as $model) {

			$model = $this->bookingRepo->buildAttr($model);

			$result = $feed;
			$result['key'] = $model->key;
			$result['status'] = $model->status;
			$result['status_label'] = $model->status_label;
			$result['title'] = $model->person_name.' '.$model->key.(($model->cod) ? ' [COD]' : '');
			$result['subtitle'] = $model->customer_name;
			$result['desc'] = $model->district.' '.$model->province.' '.$model->postcode;
			$result['cod'] = ($model->cod) ? '1' : '0';
			$result['express'] = ($model->express) ? '1' : '0';
			$result['datetime'] = $model->get_datetime;
			$result['datetime_display'] = 'เวลารับสินค้า '.helperDateFormat($model->get_datetime, 'H:i น.');
			$results['bookings'][] = $result;
		}

		return $results;
	}
}
