<?php namespace App\Http\Controllers\Customer;

use \Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Customer\Controller;
use App\Services\Users\UserCustomer;
use App\Services\Customers\CustomerRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\ThaiLocations\ThaiLocation;
use App\Services\Cars\CarRepository;
use App\Services\Logs\LogBookingRepository;
use App\Services\Points\Point;
use App\Services\Points\PointRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\PDFs\PDFRepository;

class BookingController extends Controller {

	public function getIndex()
	{
		$userCustomer = new UserCustomer;
		$customer_key = $userCustomer->getKey();

		$conRepo = new ConnoteRepository;
		$expresses = $conRepo->getExpressType();

		$customerRepo = new CustomerRepository;
		$customerModel = $customerRepo->getByKey($customer_key);

		// GET POINT.
		$pointRepo = new PointRepository;
		
		$pointModels = $pointRepo->getByID($customerModel->id);

		// var_dump($pointModels);exit();
		
		$pointModels = $this->buildPoint($customerModel->key, $pointModels);
		// echo "<pre>";
		// print_r($pointModels);
		// echo "</pre>";

		$thaiLocation = new ThaiLocation;                                                                                                                                                                                      
		$provinces = $thaiLocation->getProvince();

		$carRepo = new CarRepository;
		$carModels = $carRepo->getAll();

		$data = compact('customerModel', 'pointModels', 'provinces', 'carModels', 'expresses');
		return $this->view('customer.pages.booking.index', $data);
	}

	public function postCreate(Request $request)
	{
		if ($request->ajax()) {

			$bookingRepo = new BookingRepository;
			$bookingModel = $bookingRepo->createData($request, $created_by = 'customer');
			$bookingModel = $bookingRepo->buildAttr($bookingModel);

			// BUILD CONNOTE.
			$connotes = [];
			// if (!empty($request->input('pointChosens'))) {

			// 	foreach ($request->input('pointChosens') as $pointChosen) {

			// 		if (empty($pointChosen['connote_key'])) continue;

			// 		$connote = [];
			// 		$connote['no'] = $pointChosen['connote_key'];
			// 		$connote['shipper_phone'] = $request->has('person_mobile') ? $request->input('person_mobile'): '';
			// 		$connote['person'] = isset($pointChosen['person']) ? $pointChosen['person'] : '';
			// 		$connote['company'] = isset($pointChosen['name']) ? $pointChosen['name'] : '';
			// 		$connote['phone'] = isset($pointChosen['mobile']) ? $pointChosen['mobile'] : '';
			// 		$connote['address'] = isset($pointChosen['address']) ? $pointChosen['address'] : '';
			// 		$connote['district'] = isset($pointChosen['district']) ? $pointChosen['district'] : '';
			// 		$connote['province'] = isset($pointChosen['province']) ? $pointChosen['province'] : '';
			// 		$connote['postcode'] = isset($pointChosen['postcode']) ? $pointChosen['postcode'] : '';
			// 		$connote['value'] = isset($pointChosen['value']) ? $pointChosen['value'] : 0;
			// 		$connote['customer_ref'] = isset($pointChosen['customer_ref']) ? $pointChosen['customer_ref'] : '';
			// 		$connote['service'] = 'return';
			// 		$connote['point_id'] = isset($pointChosen['id']) ? $pointChosen['id'] : 0;

			// 		$connotes[] = (object)$connote;
			// 	}
			// }

			// CREATE CONNOTE.
			$userCustomer = new UserCustomer;
			$connoteRepo = new ConnoteRepository;
			$connoteRepo->createConnotes($bookingModel, $connotes, $request->input('isCod'), $from = 'web', $userCustomer->getName());

			// LOG BOOKING.
			$logBkg = new LogBookingRepository;
			$logBkg->put($bookingModel, $userCustomer->getName());

			helperResponsePutSuccess('Create Complete');
			return ['status' => 'success', 'url' => \URL::route('home.tracking.index.get', ['tracking_key' => $bookingModel->key]),'booking_id'=>$bookingModel->id];
		}
	}

	public function postAddPoint($customer_id, Request $request)
	{
		if ($request->ajax()) {

			$this->checkFormRequest($request->all());

			$userCustomer = new UserCustomer;
			$customer_key = $userCustomer->getKey();

			$point = new Point;
			$point->customer_id = $customer_id;
			$point->customer_key = $customer_key;
			$point->person 	= $request->input('person');
			$point->name 	= $request->input('company');
			$point->address 	= $request->input('address');
			$point->district 	= $request->input('district');
			$point->province 	= $request->input('province');
			$point->postcode 	= $request->input('postcode');
			$point->mobile 		= $request->input('phone');

			$point->key = 'temp';
			$point->save();

			$point->key = sprintf('D%04d', $point->id);
			$point->save();

			// GET POINT.
			$pointRepo = new PointRepository;
			$pointModels = $pointRepo->getByID($customer_id);
			$pointModels = $this->buildPoint($customer_key, $pointModels);

			return ['status' => 'success', 'model' => $pointModels];
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

	public function postRemovePoint($customer_id, Request $request)
	{
		if ($request->ajax()) {

			$userCustomer = new UserCustomer;
			$customer_key = $userCustomer->getKey();

			$pointModel = Point::find($request->input('point_id'));
			if(!empty($pointModel)) $pointModel->delete();

			// GET POINT.
			$pointRepo = new PointRepository;
			$pointModels = $pointRepo->getByID($customer_id);
			$pointModels = $this->buildPoint($customer_key, $pointModels);
			return ['status' => 'success', 'model' => $pointModels];
		}
	}

	private function buildPoint($customer_key, $points)
	{
		$conRepo = new ConnoteRepository;

		foreach ($points as $point) {

			$connote_key = $conRepo->getNewKey($point->id);
			$point->url_pdf = \URL::route('home.gen_connote.new.get', [$customer_key, $point->id, $connote_key]);

			// echo $customer_key.' '.$point->id.' '.$connote_key;
			// var_dump($point);
			// exit();
			
			$point->url_param = '';
			$notes = helperJsonDecodeToArray($point->notes);
			$point->customer_ref = '';
			$point->value = '';
			$point->connote_key = $connote_key;
		}

		return $points;
	}
}