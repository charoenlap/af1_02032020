<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\HashID\HashPasscode;

class ApiUserProfileController extends ApiBaseController {

	protected $class = 'UserProfile';

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
					'index' 	=> urlApi().'/profile',
					'store' 	=> urlApi().'/profile',
					'update' 	=> urlApi().'/profile/{key}',
					'destroy' 	=> urlApi().'/profile/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> '',
					'store' 	=> 'User Login',
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
	    	'emp_id' 		=> ['type' => 'string', 'desc' => 'Employee ID'],
	    	'nickname' 		=> ['type' => 'string', 'desc' => 'Nick Name'],
	    	'fullname' 		=> ['type' => 'string', 'desc' => 'Full Name'],
	    	'id_card' 		=> ['type' => 'string', 'desc' => 'ID Card'],
	    	'phone' 		=> ['type' => 'string', 'desc' => 'Phone Number'],
	    	'avatar' 		=> ['type' => 'string', 'desc' => 'Avatar'],
	    	'start_date' 	=> ['type' => 'string', 'desc' => 'Start Date in Job'],
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

		// Response.
		$response[] = $this->map($model['success'], $userModel);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function map($schema, $model)
	{
		$result = [];
		foreach ($schema as $key => $value) {
			$result[$key] = '';
		}

		$result['emp_id'] 		= $model->emp_key;
		$result['nickname'] 	= $model->nickname;
		$result['fullname'] 	= $model->title.' '.$model->firstname.' '.$model->lastname;
		$result['id_card'] 		= $model->id_card;
		$result['phone'] 		= $model->phone;
		$result['avatar'] 		= urlAdminImage().'/avatar.jpg';
		$result['start_date'] 	= helperThaiFormat($model->start_date);

		return $result;
	}
}
