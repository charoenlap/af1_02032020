<?php namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Customer\Controller;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\Jobs\JobRepository;
use App\Services\Users\UserCustomer;

class TrackingController extends Controller {

	public function getIndex(Request $request)
	{
		$data = $this->getData($request->input('tracking_key'));
		return $this->view('customer.pages.tracking.index', $data);
	}

	public function postIndex(Request $request)
	{
		if ($request->ajax()) {

			return $this->getData($request->input('tracking_key'));
		}
	}

	private function getData($tracking_key)
    {
        $connoteModel = '';
        $bookingModel = '';
        $connoteRepo = new ConnoteRepository;
        $bookingRepo = new BookingRepository;
        $jobRepo = new JobRepository;
        $userCustomer = new UserCustomer;

        if (!empty($tracking_key)) {

            $connoteModel = $connoteRepo->getByKeyAndCustomerKeyWithRelation($userCustomer->getKey(), $tracking_key);

            if (!empty($connoteModel)) {

                $connoteModel = $connoteRepo->buildAttr($connoteModel);
                $connoteModel->job = !empty($connoteModel->job) ? $jobRepo->buildAttr($connoteModel->job) : [];
                $connoteModel->booking = $bookingRepo->buildAttr($connoteModel->booking);
                $connoteModel->send = !empty($connoteModel->job) ? $connoteModel->job->connote->job_send : [];

            } else {

                $bookingModel = $bookingRepo->getByKeyAndCustomerKey($userCustomer->getKey(), $tracking_key);

                if (!empty($bookingModel)) {

                    $bookingModel = $bookingRepo->buildAttr($bookingModel, $build_connote = true);

                    foreach ($bookingModel->connotes as $connote) {
                        $connote = $connoteRepo->buildAttr($connote);
                        $connote->job = !empty($connote->job) ? $jobRepo->buildAttr($connote->job) : [];
                    }
                }

            }
        }

        return [
            'status' => 'success',
            'tracking_key' => $tracking_key,
            'connoteModel' => $connoteModel,
            'bookingModel' => $bookingModel
        ];
    }

}