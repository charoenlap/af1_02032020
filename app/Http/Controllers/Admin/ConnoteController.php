<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\Points\PointRepository;
use App\Services\Jobs\JobRepository;
use App\Services\ThaiLocations\ThaiLocation;

class ConnoteController extends Controller {

	public static $requiredPermissions = [
		'getIndex' 		=> 'connote.get',
		'getUpdate' 	=> 'connote.get',
		'postUpdate' 	=> 'connote.post',
	];

	public function getIndex(Request $request)
	{
		// KEEP SEARCH.
		$searchs = [];
		$searchs['start_date'] = $request->has('start_date') ? $request->input('start_date') : date('Y-m-d');
		$searchs['end_date'] = $request->has('end_date') ? $request->input('end_date') : date('Y-m-d');
		$searchs['connote_key'] = $request->has('connote_key') ? $request->input('connote_key') : '';
		$searchs['shipper_name'] = $request->has('shipper_name') ? $request->input('shipper_name') : '';
		$searchs['consignee_name'] = $request->has('consignee_name') ? $request->input('consignee_name') : '';
		$searchs['customer_ref'] = $request->has('customer_ref') ? $request->input('customer_ref') : '';

		$start_date = $searchs['start_date'];
		$end_date = $searchs['end_date'];

		// QUERY.
		$connoteRepo = new ConnoteRepository;
		// echo 'test'.$_GET['page'];
		$page = 1;

		if(isset($_GET['page'])){
			$page = (int)$_GET['page'];
		}
		$next = $page + 1;
		$prev = $page - 1;
		if($prev < 1){
			$prev = 1;
		}
		// echo Input::get('page');

		$connoteModels = $connoteRepo->getBySearch($searchs,$page);
		$count_total_row = $connoteRepo->countGetBySearch($searchs);
		// var_dump($connoteModels);exit();
		// echo count($connoteModels);exit();
		foreach ($connoteModels as $connoteModel) {
			$connoteModel = $connoteRepo->buildAttr($connoteModel);
		}
		$searchs['start_date'] = helperDateFormatDBToPicker($searchs['start_date']);
		$searchs['end_date'] = helperDateFormatDBToPicker($searchs['end_date']);
		// $page = 
		// ,'page','next','prev'
		$data = compact('connoteModels', 'searchs','page','next','prev','start_date','end_date','count_total_row');
		return $this->view('admin.pages.connote.index', $data);
	}

	public function getUpdate($connote_id)
	{
		$connoteRepo = new ConnoteRepository;
		$connoteModel = $connoteRepo->getByIdWithRelation($connote_id);
		$connoteModel = $connoteRepo->buildAttr($connoteModel);

		$thaiLocation = new ThaiLocation;
		$provinces = $thaiLocation->getProvince();

		$data = compact('connoteModel', 'connoteRepo', 'provinces');
		return $this->view('admin.pages.connote.update', $data);
	}

	public function postUpdate($connote_id, Request $request)
	{
		if ($request->ajax()) {

			$connoteRepo = new ConnoteRepository;
			$connoteModel = $connoteRepo->getById($connote_id);

			$input = $request->input('connoteModel');
			$modelByKey = $connoteRepo->getByKey($input['key']);
			if (!empty($modelByKey) && $modelByKey->id != $input['id']) {
				helperResponsePutFail('เลข Connote ซ้ำกับใบอื่น');
				return ['status' => 'fail', 'url' => \URL::route('admin.connote.update.get', $connote_id)];
			}

			$connoteModel = $connoteRepo->updateDataFromAdmin($connoteModel, $input);

			helperResponsePutSuccess('บันทึกสำเร็จ');
			return ['status' => 'success', 'url' => \URL::route('admin.connote.update.get', $connote_id)];
		}
	}

	public function postGenData($connote_id, Request $request)
	{
		if ($request->ajax()) {
			$connoteRepo = new ConnoteRepository;
			$connoteModel = $connoteRepo->getByIdWithRelation($connote_id);
			$connoteModel = $connoteRepo->buildAttr($connoteModel);
			return ['status' => 'success', 'connoteModel' => $connoteModel];
		}
	}

	public function postCancel($connote_id, Request $request)
	{
		if ($request->ajax()) {
			$connoteRepo = new ConnoteRepository;
			$connoteModel = $connoteRepo->getById($connote_id);

			$connoteModel->status = $connoteRepo->cancel;
			$connoteModel->save();

			$jobRepo = new JobRepository;
			$jobModel = $connoteModel->job;

			if (!empty($jobModel)) {

				if ($jobModel->status == $jobRepo->complete) {
					helperResponsePutFail('Connote นี้ ถูกส่งเรียบร้อยแล้ว');
					return ['status' => 'fail'];
				}

				$jobModel->status = $connoteRepo->cancel;
				$jobModel->save();
			}

			helperResponsePutSuccess('ยกเลิกสำเร็จ');
			return ['status' => 'success', 'connoteModel' => $connoteModel];
		}
	}
}