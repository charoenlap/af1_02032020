<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Jobs\JobRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Logs\LogJobRepository;

class ApiJobCreateController extends ApiBaseController {

	protected $class = 'JobCreate';

	public function __construct()
	{
		$this->apiResponse 	= new ApiResponseService;
		$this->logApiRepo 	= new LogApiRepository;
		$this->connoteRepo  = new ConnoteRepository;
		$this->jobRepo  	= new JobRepository;
	}

	public function className()
	{
		return $this->class;
	}

	public function path($method)
	{
		$path = [
					'index' 	=> urlApi().'/job_create',
					'store' 	=> urlApi().'/job_create',
					'update' 	=> urlApi().'/job_create/{key}',
					'destroy' 	=> urlApi().'/job_create/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> '',
					'store' 	=> 'Add New Jobs',
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
			'connotes' 		=> ['type' => 'json', 'req' => 'required', 'desc' => 'ConNote in Json Format ([{no: xxxxx}])'],
		];

		$jobs = [
			'key' 			 	=> ['type' => 'string', 'desc' => 'Connote No.'],
			'title' 			=> ['type' => 'string', 'desc' => 'Title'],
			'subtitle' 			=> ['type' => 'string', 'desc' => 'SubTitle'],
			'desc'				=> ['type' => 'string', 'desc' => 'Description'],
			'status' 			=> ['type' => 'string', 'desc' => 'Status'],
			'connote_no' 		=> ['type' => 'string', 'desc' => 'ConNote Number'],
			'cod' 				=> ['type' => 'string', 'desc' => 'is COD (0 or 1)'],
			'express' 			=> ['type' => 'string', 'desc' => 'is Express (0 or 1)'],
			'service' 			=> ['type' => 'string', 'desc' => 'Service Type (oneway or return)'],
			'service_display' 	=> ['type' => 'string', 'desc' => 'Service Type for Display'],
			'updated' 			=> ['type' => 'string', 'desc' => 'Updated Date Time'],
			'updated_display' 	=> ['type' => 'string', 'desc' => 'Updated Date Time for Display'],
		];

	    $output = [
	    	'jobs' 			=> ['type' => 'array', 'desc' => 'Array of Jobs Data', 'data' => $jobs],
	    	'warning_msg' 	=> ['type' => 'string', 'desc' => 'Warning Message'],
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

		// CONNOTE JSON VALIDATION.
		$connotes = helperJsonDecodeToArray($request->input('connotes'));
		if (empty($connotes)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'validation_fail');
			return $this->apiResponse->customFail('ไม่พบเลข Connote');
		}
		$connote_array = [];
		foreach ($connotes as $connote) {
			// CHECK VALIDATION in CONNOTE.
			if (!isset($connote->no)) {
				$this->logApiRepo->fail($this->class, $request->method(), 'validation_fail');
				return $this->apiResponse->customFail('รพบเลข Connote ไม่ถูกต้อง');
			}
			// KEEP CONNOTE NUMBER.
			$connote_array[] = $connote->no;
		}

		// CHECK EMPLOYEE.
		$empRepo = new EmployeeRepository;
		$empModel = $empRepo->getByEmpKey($request->input('emp_id'));
		if (empty($empModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->customFail('ไม่พบข้อมูลพนักงาน');
		}

		// CHECK DULPICATE.
		$newJobs = [];
		$returnJobs = [];
		$dupJobs = [];

		$existJobs = $this->jobRepo->getByKeys($connote_array);

		foreach ($existJobs as $existJob) {

			if ($existJob->status == $this->jobRepo->complete
				&& $existJob->connote->service == $this->connoteRepo->return
				&& $existJob->flow == $this->jobRepo->flow_send) {

				$returnJobs[$existJob->key] = $existJob;

			} else {

				$dupJobs[$existJob->key] = $existJob;

			}
		}

		$log = new LogJobRepository;

		foreach ($connotes as $connote) {

			if (isset($dupJobs[$connote->no])) {
				continue;

			} elseif (isset($returnJobs[$connote->no])) {

				$jobModel = $returnJobs[$connote->no];
				$this->jobRepo->createJobSend($jobModel);
				$jobModel = $this->jobRepo->clearJobForReturn($jobModel, $empModel);
				$log->put($jobModel, $empModel->nickname);

			} else {

				$newJobs[] = $connote->no;
			}
		}

		$warning_msg = '';
		foreach ($dupJobs as $dupJob) {
			$warning_msg .= (($warning_msg == '') ? 'พบเลข connote ซ้ำ : ' : ',').$dupJob->key;
		}

		$connoteModels = $this->connoteRepo->getByKeys($newJobs);
		$jobModels = $this->jobRepo->createJobs($connoteModels, $empModel);

		// LOG.
		foreach ($jobModels as $jobModel) {
			$log->put($jobModel, $empModel->nickname);
		}

		// Response.
		$jobModels = $this->jobRepo->getTodayByEmpKey($empModel->emp_key);
		$response[] = $this->map($model['success'], $jobModels, $warning_msg);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function map($schema, $models, $warning_msg)
	{
		$results = [];
		$data = $schema['jobs']['data'];

		foreach ($data as $key => $result) {
			$data[$key] = '';
		}

		foreach ($models as $model) {

			$model = $this->jobRepo->buildAttr($model);

			$result = $data;
			$result['key'] = $model->key;
			$result['status'] = $model->status;
			$result['status_label'] = $model->status_label;
			$result['title'] = $model->key.' '.$model->connote->service_label.':'.(($model->cod) ? 'COD '.$model->cod_value.' บาท' : '');
			$result['subtitle'] = (!empty($model->connote->consignee_name) ? $model->connote->consignee_name : '').' @ '.(!empty($model->connote->consignee_company) ? $model->connote->consignee_company : 'ยังไม่มีชื่อผู้รับสินค้า');
			$result['desc'] = !empty($model->connote->consignee_address) ? $model->connote->consignee_address : 'ยังไม่มีที่อยู่ปลายทาง';
			$result['cod'] = ($model->cod) ? '1' : '0';
			$result['express'] = ($model->express) ? '1' : '0';
			$result['datetime'] = helperDateFormat($model->created_at, 'Y-m-d H:i:s');
			$result['datetime_display'] = 'ความเคลื่อนไหวล่าสุด '.helperDateFormat($model->created_at, 'H:i น.');
			$results['jobs'][] = $result;
		}

		$results['warning_msg'] = $warning_msg;
		return $results;
	}
}
