<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\HashID\HashPasscode;
use App\Services\Positions\PositionRepository;

class ApiUserLoginController extends ApiBaseController {

	protected $class = 'UserLogin';

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
					'index' 	=> urlApi().'/login',
					'store' 	=> urlApi().'/login',
					'update' 	=> urlApi().'/login/{key}',
					'destroy' 	=> urlApi().'/login/{key}',
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

	public function storeModel()
	{
		$input 	= [
			'token'			=> ['type' => 'string', 'req' => 'required', 'desc' => 'Verify Token'],
			'emp_id'		=> ['type' => 'string', 'req' => 'required', 'desc' => 'Employee ID'],
			'passcode'		=> ['type' => 'string', 'req' => 'required', 'desc' => 'Passcode'],
			'mobile_token'	=> ['type' => 'string', 'req' => 'required', 'desc' => 'Mobile Token for Notification'],
		];

	    $output = [
			'token' 		=> ['type' => 'string', 'desc' => 'Token'],
	    	'emp_id' 		=> ['type' => 'string', 'desc' => 'Employee ID'],
	    	'nickname' 		=> ['type' => 'string', 'desc' => 'Nick Name'],
	    	'role' 			=> ['type' => 'string', 'desc' => 'Employee Role (head, sup, msg)'],
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
		if (!$this->apiResponse->checkPermissionBasic($request)) {
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
		$userModel = $this->employeeRepo->getByEmpKeyAndPassword($request->input('emp_id'), $request->input('passcode'));

		// Data Not Found.
		if (empty($userModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->dataNotFound();
		}

		// MOBILE TOKEN.
		$notes = helperJsonDecodeToArray($userModel->notes);
		$notes['mobile_token'] = $request->input('mobile_token');
		$userModel->notes = json_encode($notes);
		$userModel->save();

		// Response.
		$response[] = $this->mapStore($model['success'], $userModel);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function mapStore($schema, $model)
	{
		$result = [];
		$hashPasscode = new HashPasscode;
		foreach ($schema as $key => $value) {
			$result[$key] = '';
		}

		$result['token']	= $hashPasscode->gen();
		$result['emp_id'] 	= $model->emp_key;
		$result['nickname'] = 'คุณ'.$model->nickname;
		$result['role']		= !empty($model->position->key) ? $model->position->key : 'msg';

		$pstRepo = new PositionRepository;
		$result['is_sup']	= in_array($model->position_id, $pstRepo->idCanSeeIsSupForApp()) ? 1 : 0;
		return $result;
	}
}
