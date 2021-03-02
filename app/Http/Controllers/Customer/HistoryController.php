<?php namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Customer\Controller;
use App\Services\Users\UserCustomer;
use App\Services\Bookings\BookingRepository;

class HistoryController extends Controller {

	public function getIndex()
	{
		$userCustomer = new UserCustomer;
		$customer_key = $userCustomer->getKey();

		$bookingRepo = new BookingRepository;
		$bookingModels = $bookingRepo->getByCustomerKey($customer_key);

		foreach ($bookingModels as $bookingModel) {
			$bookingModel = $bookingRepo->buildAttr($bookingModel);
		}

		$data = compact('bookingModels');
		return $this->view('customer.pages.history.index', $data);
	}
}