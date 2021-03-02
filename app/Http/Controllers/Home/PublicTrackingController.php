<?php namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Home\Controller;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\Jobs\JobRepository;

class PublicTrackingController extends Controller {

	public function getIndex(Request $request)
	{
		$data = $this->getData($request->input('tracking_key'));
		return $this->view('home.pages.tracking.index', $data);
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
        // $bookingRepo = new BookingRepository;
        $jobRepo = new JobRepository;

        if (!empty($tracking_key)) {

            if (!empty($connoteModel = $connoteRepo->getByKey($tracking_key))) {

                $job = !empty($connoteModel->job_send) ? $connoteModel->job_send : $connoteModel->job;
                $connoteModel->job = !empty($job) ? $jobRepo->buildAttr($job) : [];
                $connoteModel = $connoteRepo->buildAttr($connoteModel);
            }
        }

        return [
            'status' => 'success',
            'tracking_key' => $tracking_key,
            'connoteModel' => $connoteModel,
            // 'bookingModel' => $bookingModel
        ];
    }
}