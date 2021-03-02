<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Jobs\JobRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Topups\TopupRepository;
use App\Services\Connotes\ConnoteRepository;

class ApiJobListController extends ApiBaseController {

	protected $class = 'JobList';

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
					'index' 	=> urlApi().'/job_list',
					'store' 	=> urlApi().'/job_list',
					'update' 	=> urlApi().'/job_list/{key}',
					'destroy' 	=> urlApi().'/job_list/{key}',
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
	    	'count_n'		=> ['type' => 'array', 'desc' => 'Count New Job'],
	    	'count_p'		=> ['type' => 'array', 'desc' => 'Count In Progress Job'],
	    	'count_s'		=> ['type' => 'array', 'desc' => 'Count Complete Job'],
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

		// GET JOBS.
		$jobModels = $this->jobRepo->getForApp($empModel->emp_key);
		if ($jobModels->count() <= 0) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->customFail('ไม่พบข้อมูล');
		}

		// Response.
		$response[] = $this->map($model['success'], $jobModels);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function map($schema, $models)
	{
		$results = [];
		$data = $schema['jobs']['data'];

		foreach ($data as $key => $result) {
			$data[$key] = '';
		}

		$results['count_n'] = 0;
		$results['count_p'] = 0;
		$results['count_s'] = 0;

		foreach ($models as $model) {

			$model = $this->jobRepo->buildAttr($model);

			$connoteRepo = new ConnoteRepository;
			$model->connote = $connoteRepo->buildAttr($model->connote);

			$result = $data;
			$result['key'] = $model->key;
			$result['status'] = $model->status;
			$result['status_label'] = $model->status_label;
			$result['title'] = $model->key.(($model->flow == $this->jobRepo->flow_return) ? ' [R]' : '');
			// $result['title'] .= (($model->connote->cod) ? 'COD '.$model->connote->cod_value.' บาท' : 'ไม่มี COD');
			$result['subtitle'] = (!empty($model->connote->consignee_name) ? $model->connote->consignee_name : '').' @ '.(!empty($model->connote->consignee_company) ? $model->connote->consignee_company : 'ยังไม่มีชื่อผู้รับสินค้า');
			$result['desc'] = !empty($model->connote->consignee_address) ? $model->connote->consignee_address : 'ยังไม่มีที่อยู่ปลายทาง';
			$result['cod'] = ($model->cod) ? '1' : '0';
			$result['express'] = ($model->express) ? '1' : '0';
			$result['datetime'] = helperDateFormat($model->created_at, 'Y-m-d H:i:s');
			$result['datetime_display'] = $model->connote->service_label.':'.(($model->connote->cod) ? 'COD '.$model->connote->cod_value.' บาท' : 'ไม่มี COD');
			//'ความเคลื่อนไหวล่าสุด '.helperDateFormat($model->created_at, 'H:i น.');
			$results['jobs'][] = $result;

			switch ($model->status) {
				case $this->jobRepo->new: $results['count_n']++; break;
				case $this->jobRepo->inprogress: $results['count_p']++; break;
				case $this->jobRepo->complete: $results['count_s']++; break;
			}

			if ($model->flow == $this->jobRepo->flow_return) {
				$results['count_s']++;
			}
		}


		return $results;
	}
}
