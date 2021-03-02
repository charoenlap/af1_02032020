<?php namespace App\Http\Controllers\Api\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\ApiResponseService;

use App\Services\Logs\LogApiRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Jobs\JobRepository;
use App\Services\Medias\MediaRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Logs\LogJobRepository;

class ApiJobUpdateController extends ApiBaseController {

	protected $class = 'JobUpdate';

	public function __construct()
	{
		$this->apiResponse 	= new ApiResponseService;
		$this->logApiRepo 	= new LogApiRepository;
		$this->jobRepo  	= new JobRepository;
		$this->mediaRepo  	= new MediaRepository;
		$this->connoteRepo = new ConnoteRepository;
	}

	public function className()
	{
		return $this->class;
	}

	public function path($method)
	{
		$path = [
					'index' 	=> urlApi().'/job_update',
					'store' 	=> urlApi().'/job_update',
					'update' 	=> urlApi().'/job_update/{key}',
					'destroy' 	=> urlApi().'/job_update/{key}',
				];

		return isset($path[$method]) ? $path[$method] : '';
	}

	public function description($method)
	{
		$desc = [
					'index' 	=> '',
					'store' 	=> 'Update Jobs',
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
			'key' 			=> ['type' => 'string', 'req' => 'required', 'desc' => 'Job Key'],
			'receiver' 		=> ['type' => 'string', 'req' => 'required', 'desc' => 'Receiver Name'],
			'file' 			=> ['type' => 'string', 'req' => 'optional', 'desc' => 'Image File'],
			'extension' 	=> ['type' => 'string', 'req' => 'optional', 'desc' => 'Image File Extension'],
			'lat' 			=> ['type' => 'string', 'req' => 'optional', 'desc' => 'Latitude'],
			'lng' 			=> ['type' => 'string', 'req' => 'optional', 'desc' => 'Langtitude'],
			'topup' 		=> ['type' => 'string', 'req' => 'optional', 'desc' => 'Topup'],
			'note' 			=> ['type' => 'string', 'req' => 'optional', 'desc' => 'Note'],
		];

		$output = [
			'key' 				=> ['type' => 'string', 'desc' => 'Connote Key'],
			'consignee_name' 	=> ['type' => 'string', 'desc' => 'Consignee Name'],
			'consignee_address' => ['type' => 'string', 'desc' => 'Consignee Address'],
			'consignee_phone'	=> ['type' => 'string', 'desc' => 'Consignee Phone'],
	    	'status'			=> ['type' => 'string', 'desc' => 'Status'],
	    	'status_label'		=> ['type' => 'string', 'desc' => 'Status Label'],
	    	'cod' 				=> ['type' => 'string', 'desc' => 'is COD (0 or 1)'],
	    	'express' 			=> ['type' => 'string', 'desc' => 'is Express (0 or 1)'],
	    	'datetime' 			=> ['type' => 'string', 'desc' => 'Date time'],
	    	'datetime_display'	=> ['type' => 'string', 'desc' => 'Date time for display'],
	    	'topup_dropdown'	=> ['type' => 'array', 'desc' => 'Array of Topup for Dropdown'],
	    	'receiver_name' 	=> ['type' => 'string', 'desc' => 'Receiver Name'],
	    	'received_at' 		=> ['type' => 'string', 'desc' => 'Received Date time'],
	    	'photo' 			=> ['type' => 'string', 'desc' => 'Photo URL'],
	    	'topup' 			=> ['type' => 'string', 'desc' => 'Topup Value'],
	    	'note' 				=> ['type' => 'string', 'desc' => 'Note'],

	    	'person'	 		=> ['type' => 'string', 'desc' => 'Person'],
			'phone'				=> ['type' => 'string', 'desc' => 'Phone'],
	    	'line_1'	 		=> ['type' => 'string', 'desc' => 'Line 1'],
			'line_2'	 		=> ['type' => 'string', 'desc' => 'Line 2'],
			'line_3'	 		=> ['type' => 'string', 'desc' => 'Line 3'],
			'line_4'	 		=> ['type' => 'string', 'desc' => 'Line 4'],
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

		// CHECK EMPLOYEE.
		$empRepo = new EmployeeRepository;
		$empModel = $empRepo->getByEmpKey($request->input('emp_id'));
		if (empty($empModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->customFail('ไม่พบข้อมูลพนักงาน');
		}

		// Get Job.
		$jobModel = $this->jobRepo->getByKey($request->input('key'));

		// Data Not Found.
		if (empty($jobModel)) {
			$this->logApiRepo->fail($this->class, $request->method(), 'data_not_found');
			return $this->apiResponse->customFail('ไม่พบเลขใบนำส่งนี้');
		}

		if ($jobModel->status != $this->jobRepo->inprogress) {
			$this->logApiRepo->fail($this->class, $request->method(), 'validation_fail');
			return $this->apiResponse->customFail('สถานะไม่ถูกต้อง');
		}

		// FILENAME REQUIRE ONLY SEND CASE.
		$filename = null;
		if ($jobModel->flow == $this->jobRepo->flow_send) {

			if (!empty($request->file('file'))) {
				$filename = $this->mediaRepo->createThumbnail($request->file('file'), $jobModel->key.'-');
			}

			// if (empty($filename)) {
			// 	$this->logApiRepo->fail($this->class, $request->method(), 'upload_fail');
			// 	return $this->apiResponse->customFail('อัพโหลดรูปไม่สำเร็จ');
			// }
		}

		// REMOVE OLD PHOTO.
		$this->mediaRepo->removeFile(helperDirContent().'/'.$jobModel->photo);

		// UPDATE JOB.
		$jobModel->status = $this->jobRepo->complete;
		$jobModel->photo = $filename;
		$jobModel->received_at = date('Y-m-d H:i:s');
		$jobModel->receiver_name = $request->input('receiver');
		$jobModel->topup = $request->input('topup');
		$jobModel->notes = $request->input('note');
		$jobModel->lat = $request->input('lat');
		$jobModel->lng = $request->input('lng');
		$jobModel->save();

		$log = new LogJobRepository;
		$log->put($jobModel, $empModel->nickname);

		// Response.
		$response[] = $this->map($model['success'], $jobModel);

		// Success.
		$this->logApiRepo->success($this->class, $request->method());
		return $this->apiResponse->success($response);
	}

	private function map($schema, $model)
	{
		$result = $schema;

		foreach ($result as $key => $value) {
			$result[$key] = '';
		}

		$model = $this->jobRepo->buildAttr($model);

		$result['key'] = $model->key;
		$result['consignee_name'] = !empty($model->connote->consignee_name) ? $model->connote->consignee_name : 'ยังไม่มีชื่อผู้รับ';
		$result['consignee_address'] = !empty($model->connote->consignee_address) ? $model->connote->consignee_address : 'ยังไม่มีที่อยู่ปลายทาง';
		$result['consignee_phone'] = !empty($model->connote->consignee_phone) ? $model->connote->consignee_phone : '000-000-0000';
		$result['status'] 			= $model->status;
		$result['status_label']		= $this->jobRepo->{'label_'.$model->status};
		$result['cod']				= $model->connote->cod;

		$result['express']	= $this->connoteRepo->{'label_express_'.$model->connote->cod};
		$result['datetime'] = helperDateFormat($model->created_at, 'Y-m-d H:i:s');
		$result['datetime_display'] = 'เวลาที่เริ่มส่งสินค้า '.helperDateFormat($model->created_at, 'H:i น.');

		$result['receiver_name'] = !empty($model->receiver_name) ? $model->receiver_name : '';
		$result['received_at'] = !empty($model->received_at)
								? helperThaiFormat($model->received_at, true).' เวลา '.helperDateFormat($model->received_at, 'H:i น.')
								: '';
		$result['photo']	= !empty($model->photo) ? urlContent().'/'.$model->photo : urlAdminImage().'/placeholder.png';
		$result['topup']	= !empty($model->topup_display) ? $model->topup_display : '';
		$result['note']		= !empty($model->notes) ? $model->notes : '';

		if ($model->flow == $this->jobRepo->flow_send) {

			$result['person'] = !empty($model->connote->consignee_name) ? $model->connote->consignee_name : 'ยังไม่มีชื่อผู้รับ';
			$result['line_1'] = $model->connote->service_label.' '.(($model->cod) ? '[COD '.$model->cod_value.' บาท]' : '[ไม่มี COD]');
			$result['line_2'] = (!empty($model->connote->consignee_company) ? $model->connote->consignee_company : '').' ';
			$result['line_2'] .= (!empty($model->connote->consignee_address) ? $model->connote->consignee_address : 'ยังไม่มีที่อยู่ปลายทาง');
			$result['line_3'] = 'เวลาที่เริ่มส่งสินค้า '.$model->new_datetime_label;
			$result['line_4'] = '';
			$result['phone'] = !empty($model->connote->consignee_phone) ? $model->connote->consignee_phone : '0000';
		}

		else if ($model->flow == $this->jobRepo->flow_return) {

			$result['person'] = !empty($model->connote->shipper_name) ? $model->connote->shipper_name : 'ยังไม่มีชื่อผู้รับ';
			$result['line_1'] = $model->connote->service_label.' '.(($model->cod) ? '[COD '.$model->cod_value.' บาท]' : '[ไม่มี COD]').' [RETURN]';
			$result['line_2'] = (!empty($model->connote->shipper_company) ? $model->connote->shipper_company : '').' ';
			$result['line_2'] .= (!empty($model->connote->shipper_address) ? $model->connote->shipper_address : 'ยังไม่มีที่อยู่ปลายทาง');
			$result['line_3'] = 'เวลาที่เริ่มส่งสินค้า '.$model->new_datetime_return;
			$result['line_4'] = '';
			$result['phone'] = !empty($model->connote->shipper_phone) ? $model->connote->shipper_phone : '0000';
		}

		return $result;
	}
}
