<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Jobs\JobRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Positions\PositionRepository;

class ApiMsgListForSupController extends ApiBaseController {

	protected $class = 'MsgListForSup';

	public function __construct()
	{
		$this->apiResponse 	= new ApiResponseService;
		$this->logApiRepo 	= new LogApiRepository;
		$this->jobRepo  	= new JobRepository;
	}

	public function className()
	{
		return $this->class;
	}

	public function path($method)
	{
		$path = [
					'index' 	=> urlApi().'/msg_list_for_sup',
					'store' 	=> urlApi().'/msg_list_for_sup',
					'update' 	=> urlApi().'/msg_list_for_sup/{key}',
					'destroy' 	=> urlApi().'/msg_list_for_sup/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> 'Get Job List',
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

	   	$msgs = [
			'key' 			 	=> ['type' => 'string', 'desc' => 'Massenger ID'],
			'value' 			=> ['type' => 'string', 'desc' => 'Massenger Name'],
		];

	    $output = [
	    	'msgs' 			=> ['type' => 'array', 'desc' => 'Array of Massenger Data', 'data' => $msgs],
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

		// CHECK EMPLOYEE.
		$empRepo = new EmployeeRepository;
		$empModel = $empRepo->getByEmpKey($request->input('emp_id'));
		if (empty($empModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->customFail('ไม่พบข้อมูลพนักงาน');
		}
		// CHECK SUP.
		$pstRepo = new PositionRepository;
		if (!in_array($empModel->position_id, [$pstRepo->id_headsup, $pstRepo->id_sup])) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->customFail('เฉพาะ Supervisor');
		}

		// Response.
		$msgModels = $empRepo->getMsgAll();
		$response[] = $this->map($model['success'], $msgModels);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function map($schema, $models)
	{
		$results = [];
		$data = $schema['msgs']['data'];

		foreach ($data as $key => $result) {
			$data[$key] = '';
		}

		$results['msgs'][] = ['key' => 'all', 'value' => 'ทั้งหมด'];

		foreach ($models as $model) {

			$result = $data;
			$result['key'] = $model->emp_key;
			$result['value'] = $model->nickname.' '.$model->firstname;
			$results['msgs'][] = $result;
		}

		return $results;
	}
}
