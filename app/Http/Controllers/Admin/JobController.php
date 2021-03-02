<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Jobs\JobRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Medias\MediaRepository;
use App\Services\Users\UserAdmin;
use App\Services\Logs\LogJobRepository;

class JobController extends Controller {

	public static $requiredPermissions = [
		'getIndex' 		=> 'employee.get',
		'getUpdate' 	=> 'employee.get',
		'postUpdate' 	=> 'employee.post',
		'postUpdateMsg' => 'employee.post',
	];

	public function getIndex(Request $request)
	{
		// KEEP SEARCH.
		$searchs = [];
		$searchs['start_date'] = $request->has('start_date') ? $request->input('start_date') : date('Y-m-d');
		$searchs['end_date'] = $request->has('end_date') ? $request->input('end_date') : date('Y-m-d');
		$searchs['status'] = $request->has('status') ? $request->input('status') : 'all';
		$searchs['connote_key'] = $request->has('connote_key') ? $request->input('connote_key') : '';
		$searchs['booking_key'] = $request->has('booking_key') ? $request->input('booking_key') : '';
		$searchs['customer_ref'] = $request->has('customer_ref') ? $request->input('customer_ref') : '';
		$searchs['sup_name'] = $request->has('sup_name') ? $request->input('sup_name') : '';
		$searchs['msg_name'] = $request->has('msg_name') ? $request->input('msg_name') : '';

		$start_date = $searchs['start_date'];
		$end_date = $searchs['end_date'];
		$status = $searchs['status'];
		
		$page = 1;

		if(isset($_GET['page'])){
			$page = (int)$_GET['page'];
		}
		$next = $page + 1;
		$prev = $page - 1;
		if($prev < 1){
			$prev = 1;
		}
		
		// QUERY.
		$jobRepo = new JobRepository;
		$jobModels = $jobRepo->getBySearch($searchs,$page);

		foreach ($jobModels as $jobModel) {
			$jobModel = $jobRepo->buildAttr($jobModel, $build_connote = true);
		}
		// echo "<pre>";
		// var_dump($jobModels);
		// echo "</pre>";
		// exit();
		// var_dump($jobModels);exit();

		// GET MSG.
		$empRepo = new EmployeeRepository;
		$msgModels = $empRepo->getMsgAll();

		$jobStatuses = $jobRepo->statusAll();

		$searchs['start_date'] = helperDateFormatDBToPicker($searchs['start_date']);
		$searchs['end_date'] = helperDateFormatDBToPicker($searchs['end_date']);

		$data = compact('jobModels', 'msgModels', 'searchs', 'jobStatuses','page','next','prev','status','start_date','end_date');
		return $this->view('admin.pages.job.index', $data);
	}

	public function getUpdate($bkg_id)
	{
		$bookingRepo = new BookingRepository;
		$bookingModel = $bookingRepo->getByID($bkg_id);
		if (empty($bookingModel)) $bookingModel = $bookingRepo->getEmpty();

		$thaiLocation = new ThaiLocation;
		$provinces = $thaiLocation->getProvince();

		$carRepo = new CarRepository;
		$carModels = $carRepo->getAll();

		$ctmRepo = new CustomerRepository;
		$ctmModels = $ctmRepo->getAll();

		$data = compact('ctmModels', 'provinces', 'carModels', 'bookingModel');
		return $this->view('admin.pages.booking.update', $data);
	}

	public function postUpdate($bkg_id, Request $request)
	{
		if ($request->ajax()) {

			if (empty($request->input('customer_id')))
			return helperReturnErrorFormRequest('customer_id', 'กรุณาใส่ลูกค้า');

			$bookingRepo = new BookingRepository;
			$bookingModel = $bookingRepo->createData($request);

			if ($request->input('isCod') === 'true') {

				// BUILD CONNOTE.
				$connotes = [];
				foreach ($request->input('codValues') as $codValue) {
					$connote = [];
					$connote['no'] = '';
					$connote['value'] = isset($codValue['value']) ? $codValue['value'] : 0;
					$connote['service'] = 'return';

					$connotes[] = (object)$connote;
				}

				// CREATE CONNOTE.
				$connoteRepo = new ConnoteRepository;
				$connoteRepo->createConnotes($bookingModel, $connotes, $cod = 1);
			}

			// LOG.
			$logBkg = new LogBookingRepository;
			$logBkg->put($bookingModel);

			helperResponsePutSuccess('บันทึกสำเร็จ');
			return ['status' => 'success', 'url' => \URL::route('admin.booking.index.get')];
		}
	}

	public function postUpdateMsg(Request $request)
	{
		if ($request->ajax()) {

			$job_id = $request->input('job_id');
			$msg_id = $request->input('msg_id');

			$empRepo = new EmployeeRepository;
			$empModel = $empRepo->getByID($msg_id);

			if (empty($empModel)) {
				helperResponsePutFail('Data is not found.');
				return ['status' => 'fail'];
			}

			$jobRepo = new JobRepository;
			$jobModel = $jobRepo->getById($job_id);
			if (empty($jobModel)) return ['status' => 'fail'];
			$jobModel = $jobRepo->updateMsg($jobModel, $empModel);


			helperResponsePutSuccess('บันทึกสำเร็จ');
			return ['status' => 'success', 'url' => \URL::route('admin.job.index.get')];
		}
	}

	public function postUpdatePhoto(Request $request)
	{
		if ($request->ajax()) {

			$model = helperJsonDecodeToArray($request->input('model'));

			$jobRepo = new JobRepository;
			$jobModel = $jobRepo->getById($model['id']);

			if (!empty($jobModel) && !empty($request->file('photo'))) {

				$fileObject = $request->file('photo');
				$mediaRepo = new MediaRepository;
				$jobModel->photo = $mediaRepo->removeFile(helperDirContent().'/'.$jobModel->photo);
				$jobModel->photo = $mediaRepo->uploadFile($fileObject);
				$jobModel->save();

				helperResponsePutSuccess('Update Job Image Complete');
				return ['status' => 'success'];
			}

			helperResponsePutFail('Update Job Image Fail');
			return ['status' => 'fail'];
		}
	}

	public function postApprove(Request $request)
	{
		if ($request->ajax()) {

			$job_id = $request->input('job_id');
			$jobRepo = new JobRepository;
			$jobModel = $jobRepo->getById($job_id);
			if (empty($jobModel)) return ['status' => 'fail'];
			$jobModel->status = $jobRepo->inprogress;
			$userAdmin = new UserAdmin;
			$jobModel->sup_key = $userAdmin->getEmpKey();
			$jobModel->sup_name = $userAdmin->getNickname();
			$jobModel->save();

			$log = new LogJobRepository;
			$log->put($jobModel, $userAdmin->getNickname());
			helperResponsePutSuccess('บันทึกสำเร็จ');
			return ['status' => 'success', 'url' => \URL::route('admin.job.index.get')];
		}
	}

	public function postSend(Request $request)
	{
		if ($request->ajax()) {

			$job_id = $request->input('job_id');
			$jobRepo = new JobRepository;
			$jobModel = $jobRepo->getById($job_id);
			if (empty($jobModel)) return ['status' => 'fail'];
			$jobModel->status = $jobRepo->complete;
			$jobModel->receiver_name = $request->input('receiver_name');
			$jobModel->received_at = date('Y-m-d H:i:s');
			$jobModel->save();

			$log = new LogJobRepository;
			$userAdmin = new UserAdmin;
			$log->put($jobModel, $userAdmin->getNickname());
			helperResponsePutSuccess('บันทึกสำเร็จ');
			return ['status' => 'success', 'url' => \URL::route('admin.job.index.get')];
		}
	}
}