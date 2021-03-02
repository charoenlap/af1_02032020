<?php namespace App\Http\Controllers\Admin;

use \Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Bookings\BookingRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Notifications\Firebase;
use App\Services\ThaiLocations\ThaiLocation;
use App\Services\Cars\CarRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Users\UserAdmin;
use App\Services\Customers\CustomerRepository;
use App\Services\Logs\LogBookingRepository;
use App\Services\Points\Point;
use Illuminate\Support\Facades\DB;
class BookingController extends Controller {

	public static $requiredPermissions = [
		'getIndex' 		=> 'booking.get',
		'getUpdate' 	=> 'booking.get',
		'postUpdate' 	=> 'booking.post',
		'postUpdateMsg' => 'booking.post',
		'postAddPoint' 	=> 'booking.post',
		'postRemove' 	=> 'booking.post',
	];

	public function getIndex(Request $request)
	{
		// KEEP SEARCH.
		$searchs = [];

		$searchs['start_date'] = $request->has('start_date') ? $request->input('start_date') : date('Y-m-d');
		// echo $searchs['start_date'];exit();
		$searchs['end_date'] = $request->has('end_date') ? $request->input('end_date') : date('Y-m-d');
		$searchs['status'] = $request->has('status') ? $request->input('status') : 'all';
		$searchs['booking_key'] = $request->has('booking_key') ? $request->input('booking_key') : '';
		$searchs['ctm_name'] = $request->has('ctm_name') ? $request->input('ctm_name') : '';
		$searchs['created_by'] = $request->has('created_by') ? $request->input('created_by') : '';
		$searchs['cs_name'] = $request->has('cs_name') ? $request->input('cs_name') : '';
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
		$bookingRepo = new BookingRepository;
		$bookingModels = $bookingRepo->getBySearch($searchs,$page);
		// var_dump($bookingModels);exit();
		// var_dump($searchs);exit();
		foreach ($bookingModels as $bookingModel) {
			// echo "<pre>";
			// var_dump($bookingModel);exit();
			$bookingModel = $bookingRepo->buildAttr($bookingModel, $build_connote = true);
		}

		// GET MSG.
		$empRepo = new EmployeeRepository;
		$msgModels = $empRepo->getMsgAll();

		$result_employee = DB::select("SELECT * FROM employees WHERE deleted_at IS NULL AND `status` = 'active' AND position_id = 5");
		$employee_array = array();
		foreach($result_employee as $val){
			$employee[] = array(
				'id' 		=> $val->id,
				'emp_key' 	=> $val->emp_key,
				'title' 	=> $val->title, 
				'nickname' 	=> $val->nickname, 
				'firstname' => $val->firstname, 
				'lastname' 	=> $val->lastname, 
				'phone' 	=> $val->phone, 
				'address' 	=> $val->address,
				'id_card' 	=> $val->id_card

			);
		}

		// echo "<pre>";
		// var_dump($msgModels->items);
		// echo "</pre>";
		// exit();

		$bookingStatuses = $bookingRepo->statusAll();
		$searchs['start_date'] = helperDateFormatDBToPicker($searchs['start_date']);
		$searchs['end_date'] = helperDateFormatDBToPicker($searchs['end_date']);




		$data = compact('employee', 'bookingModels', 'msgModels', 'searchs', 'bookingStatuses','page','next','prev','status','start_date','end_date');
		return $this->view('admin.pages.booking.index', $data);
	}
	// public function convert($origDate){
	// 	$date = str_replace('/', '-', $origDate );
	// 	$newDate = date("Y-m-d", strtotime($date));
	// 	return $newDate;
	// }
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

		// Add COD Value to Points.
		foreach ($ctmModels as $ctmModel) {
			$ctmModel = $this->buildPoint($ctmModel);
		}

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
			$bookingModel = $bookingRepo->buildAttr($bookingModel);

			if (!empty($request->input('pointChosens'))) {

				// BUILD CONNOTE.
				$connotes = [];
				foreach ($request->input('pointChosens') as $pointChosen) {

					if (empty($pointChosen['connote_key'])) continue;

					$connote = [];
					$connote['no'] = $pointChosen['connote_key'];
					$connote['service'] = 'return';
					$connote['value'] = isset($pointChosen['value']) ? $pointChosen['value'] : 0;
					$connote['person'] = $pointChosen['person'];
					$connote['company'] = $pointChosen['name'];
					$connote['company'] = $pointChosen['name'];
					$connote['phone'] = $pointChosen['mobile'];
					$connote['address'] = $pointChosen['address'];
					$connote['district'] = $pointChosen['district'];
					$connote['province'] = $pointChosen['province'];
					$connote['postcode'] = $pointChosen['postcode'];
					$connote['customer_ref'] = isset($pointChosen['customer_ref']) ? $pointChosen['customer_ref'] : '';
					$connote['point_id'] = isset($pointChosen['id']) ? $pointChosen['id'] : 0;

					$connotes[] = (object)$connote;
				}

				// CREATE CONNOTE.
				$connoteRepo = new ConnoteRepository;
				$connoteRepo->createConnotes($bookingModel, $connotes, $request->input('isCod'));
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

			$booking_id = $request->input('booking_id');
			$msg_id = $request->input('msg_id');

			$empRepo = new EmployeeRepository;
			$empModel = $empRepo->getByID($msg_id);

			if (empty($empModel)) {
				helperResponsePutFail('Data is not found.');
				return ['status' => 'fail','msg_id'=>$msg_id];
			}

			$bookingRepo = new BookingRepository;
			$bookingModel = $bookingRepo->getByID($booking_id);
			if (empty($bookingModel)) return ['status' => 'fail','booking_id'=>$booking_id];
			$bookingModel = $bookingRepo->updateMsg($bookingModel, $empModel);

			// NOTIFICATION.
			$notes = helperJsonDecodeToArray($empModel->notes);
			$response = '';

			if (!empty($notes['mobile_token'])) {

				$firebase = new Firebase;
				$params = $firebase->schmaInput($notes['mobile_token'], 'มีงานใหม่เข้ามา รหัส '.$bookingModel->key);
				$response = $firebase->post(json_encode($params));
			}

			$logBkg = new LogBookingRepository;
			$logBkg->put($bookingModel);

			helperResponsePutSuccess('บันทึกสำเร็จ');
			return ['status' => 'success', 'response' => $response];
		}
	}

	public function postAddPoint(Request $request)
	{
		if ($request->ajax()) {

			$this->checkFormRequest($request->all());

			$point = new Point;
			$point->customer_id = $request->input('customer_id');
			$point->customer_key = $request->input('customer_key');
			$point->person 		= $request->input('person');
			$point->name 		= $request->input('company');
			$point->address 	= $request->input('address');
			$point->district 	= $request->input('district');
			$point->province 	= $request->input('province');
			$point->postcode 	= $request->input('postcode');
			$point->mobile 		= $request->input('phone');

			$point->key = 'temp';
			$point->save();

			$point->key = sprintf('D%04d', $point->id);
			$point->save();

			$customerRepo = new CustomerRepository;
			$customerModel = $customerRepo->getByID($point->customer_id);
			$customerModel = $this->buildPoint($customerModel);

			return ['status' => 'success', 'model' => $customerModel->points];
		}
	}

	private function checkFormRequest($input)
	{
		$rules = [
			'person'		=> 'required',
			'phone'			=> 'required',
			'company'		=> 'required',
			'address'		=> 'required',
			'district'		=> 'required',
			'province'		=> 'required',
			'postcode'		=> 'required|numeric|digits:5',
		];

		$messages = [
			'person.required'	=> 'กรุณาใส่ชื่อผู้ติดต่อ',
			'phone.required'	=> 'กรุณาใส่เบอร์ติดต่อ',
			'company.required'	=> 'กรุณาใส่ชื่อบริษัท',
			'address.required'	=> 'กรุณาใส่ที่อยู่',
			'district.required'	=> 'กรุณาใส่ชื่ออำเภอ',
			'province.required'	=> 'กรุณาใส่ให้ครบ',
			'postcode.required'	=> 'กรุณาใส่รหัสไปรษณีย์',
			'postcode.numeric' 	=> 'กรุณาใส่รหัสไปรษณีย์เป็นตัวเลข',
			'postcode.digits' 	=> 'กรุณาใส่รหัสไปรษณีย์เป็นตัวเลข 5 หลัก',
		];

		$validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
    		helperReturnErrorFormRequestArray($validator->errors()->messages());
        }
	}

	public function postRemove(Request $request)
	{
		if ($request->ajax()) {

			$bookingRepo = new BookingRepository;
			$bookingModel = $bookingRepo->getByID($request->input('booking_id'));
			if (empty($bookingModel)) return ['status' => 'fail'];

			$bookingModel->status = $bookingRepo->cancel;
			$bookingModel->save();

			$logBkg = new LogBookingRepository;
			$logBkg->put($bookingModel);


			helperResponsePutSuccess('ลบสำเร็จ');
			return ['status' => 'success'];
		}
	}

	private function buildPoint($customerModel)
	{
		$conRepo = new ConnoteRepository;

		foreach ($customerModel->points as $point) {

			// $point->url_pdf = \URL::route('home.gen_connote.from_point.get', [$customerModel->key, $point->id]);

			$connote_key = $conRepo->getNewKey($point->id);
			$point->url_pdf = \URL::route('home.gen_connote.new.get', [$customerModel->key, $point->id, $connote_key]);

			$notes = helperJsonDecodeToArray($point->notes);
			$point->customer_ref = '';

			$point->value = '';
			$point->connote_key = $connote_key;
		}

		return $customerModel;
	}
}