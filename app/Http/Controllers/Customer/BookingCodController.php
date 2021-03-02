<?php namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Customer\Controller;
use App\Services\Users\UserCustomer;
use App\Services\Customers\CustomerRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\ThaiLocations\ThaiLocation;
use App\Services\Cars\CarRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Logs\LogBookingRepository;

class BookingCodController extends Controller {

	public function getIndex()
	{
		$userCustomer = new UserCustomer;
		$customer_key = $userCustomer->getKey();

		$customerRepo = new CustomerRepository;
		$customerModel = $customerRepo->getByKey($customer_key);

		$thaiLocation = new ThaiLocation;
		$provinces = $thaiLocation->getProvince();

		$carRepo = new CarRepository;
		$carModels = $carRepo->getAll();

		$conRepo = new ConnoteRepository;
		$expresses = $conRepo->getExpressType();

		$data = compact('customerModel', 'provinces', 'carModels', 'expresses');
		return $this->view('customer.pages.booking_cod.index', $data);
	}

	public function postCreate(Request $request)
	{
		if ($request->ajax()) {

			if (!$request->has('codValues')) helperReturnErrorFormRequest('cod', 'กรุณาใส่จำนวนเงินเก็บปลายทาง');

			$bookingRepo = new BookingRepository;
			$bookingModel = $bookingRepo->createByCustomer($request);

			// BUILD CONNOTE.
			$connotes = [];
			foreach ($request->input('codValues') as $codValue) {
				$connote = [];
				$connote['no'] = '';
				$connote['shipper_phone'] = $request->has('person_mobile') ? $request->input('person_mobile'): '';
				$connote['person'] = isset($codValue['person']) ? $codValue['person'] : '';
				$connote['value'] = isset($codValue['cod_value']) ? $codValue['cod_value'] : 0;
				$connote['address'] = isset($codValue['address']) ? $codValue['address'] : '';
				$connote['district'] = isset($codValue['district']) ? $codValue['district'] : '';
				$connote['province'] = isset($codValue['province']) ? $codValue['province'] : '';
				$connote['postcode'] = isset($codValue['postcode']) ? $codValue['postcode'] : '';
				$connote['phone'] = isset($codValue['phone']) ? $codValue['phone'] : '';
				$connote['service'] = 'return';

				$connotes[] = (object)$connote;
			}

			// CREATE CONNOTE.
			$connoteRepo = new ConnoteRepository;
			$connoteRepo->createConnotes($bookingModel, $connotes, $cod = 1);

			$userCustomer = new UserCustomer;
			$logBkg = new LogBookingRepository;
			$logBkg->put($bookingModel, $userCustomer->getName());

			helperResponsePutSuccess('Create Complete');
			return ['status' => 'success', 'url' => \URL::route('home.tracking.index.get', ['booking_key' => $bookingModel->key])];
		}
	}
}