<?php namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Customer\Controller;
use App\Services\Users\UserCustomer;
use App\Services\Customers\CustomerRepository;

class ProfileController extends Controller {

	public function getIndex()
	{
		$userCustomer = new UserCustomer;
		$customer_key = $userCustomer->getKey();

		$ctmRepo = new CustomerRepository;
		$ctmModel = $ctmRepo->getByKey($customer_key);

		$data = compact('ctmModel');
		return $this->view('customer.pages.profile.index', $data);
	}

	public function postUpdate($ctm_id, Request $request)
	{
		if ($request->ajax()) {

			$ctmRepo = new CustomerRepository;
			$ctmModel = $ctmRepo->updateData($ctm_id, $request->input('ctmModel'));

			return ['status' => 'success', 'model' => $ctmModel];
		}
	}

	private function buildAttr($model)
	{
		$bookingRepo = new BookingRepository;

		$model->status_label = $bookingRepo->{'label_'.$model->status};
		$model->status_color = $bookingRepo->{'color_'.$model->status};

		$model->cod_label = ($model->cod) ? config('labels.booking.cod.th') : config('labels.booking.booking.th');

		$date = helperThaiFormat($model->get_datetime);
		$time = helperDateFormat($model->get_datetime, 'H:i น.');
		$model->get_datetime_label = $date.' '.$time;

		$persons = helperJsonDecodeToArray($model->person);
		$model->person_name = isset($persons['name']) ? $persons['name'] : '';

		return $model;
	}
}