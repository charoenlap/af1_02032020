<?php namespace App\Services\Jobs;

use App\Services\Jobs\Job;
use Illuminate\Database\QueryException;
use App\Services\Topups\TopupRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\Jobs\JobSend;

class JobRepository {

	public $new  		= 'new';
	public $inprogress  = 'inprogress';
	public $complete  	= 'complete';
	public $cancel  	= 'cancel';
	public $fail  		= 'fail';

	public $label_new 			= 'รออนุมัติ';
	public $label_inprogress	= 'กำลังไปส่ง';
	public $label_complete 		= 'ส่งเรียบร้อย';
	public $label_fail 		 	= 'ส่งไม่สำเร็จ';
	public $label_cancel 		= 'ยกเลิกการส่ง';

	public $color_new 			= '#E98F2E'; //'orange';
	public $color_inprogress 	= '#3583CA'; //'blue';
	public $color_complete 		= '#36AB7A'; //'green';
	public $color_fail 		 	= '#9E9E9E'; //'grey';
	public $color_cancel 		= '#9E9E9E'; //'grey';

	public $flow_send = 'send';
	public $flow_return = 'return';
	public $label_flow_send = 'ส่งของ';
	public $label_flow_return = 'รับของกลับ';

	public function statusAll()
	{
		return [
			$this->new => $this->label_new,
			$this->inprogress => $this->label_inprogress,
			$this->complete => $this->label_complete,
			$this->fail => $this->label_fail,
			$this->cancel => $this->label_cancel,
		];
	}

	public function updateMsg($model, $msgModel)
	{
		$model->msg_key = $msgModel->emp_key;
		$model->msg_name = $msgModel->nickname;

		$model->save();

		return $model;
	}

	public function getByKey($key)
	{
		return Job::where('key', $key)->first();
	}
	public function getByKeys($keys = [])
	{
		return Job::whereIn('key', $keys)->get();
	}
	public function getById($id)
	{
		return Job::find($id);
	}
	public function getByIds($ids)
	{
		return Job::whereIn('id', $ids)->get();
	}
	public function getTodayByEmpKey($emp_key = '')
	{
		$models =  Job::with('connote');
		$models = $models->where('created_at', '>=', date('Y-m-d 00:00:00'));
		$models = $models->where('created_at', '<=', date('Y-m-d 23:59:59'));

		if (!empty($emp_key)) $models = $models->where('msg_key', $emp_key);

		return $models->orderBy('created_at', 'desc')->get();
	}
	public function getForApp($emp_key = '')
	{
		$models = Job::where(function($query){

			$query->where(function($q){
				$q->whereIn('status', ['new', 'inprogress']);
			});

			$query->orWhere(function($q){
				$q->whereIn('status', ['complete', 'cancel', 'fail']);
				$q->where('created_at', '>=', date('Y-m-d 00:00:00'));
				$q->where('created_at', '<=', date('Y-m-d 23:59:59'));
			});
		});

		if (!empty($emp_key)) $models = $models->where('msg_key', $emp_key);
		return $models->orderBy('created_at', 'desc')->with('connote')->get();
	}

	public function getBySearch($search,$page=0)
	{
		$page = ($page-1)*10;

		$models = new Job;

		if (!empty($search['start_date'])) {
			$models = $models->where('created_at', '>=', $search['start_date'].' 00:00:00');
		}
		if (!empty($search['end_date'])) {
			$models = $models->where('created_at', '<=', $search['end_date'].' 23:59:59');
		}
		if (!empty($search['status']) && $search['status'] !== 'all') {
			$models = $models->where('status', $search['status']);
		}
		if (!empty($search['connote_key'])) {
			$models = $models->where('key', 'LIKE', '%'.$search['connote_key'].'%');
		}
		if (!empty($search['booking_key'])) {
			$booking_key = $search['booking_key'];
			$models = $models->whereHas('connote.booking', function($q) use ($booking_key) {
				$q->where('key', 'LIKE', '%'.$booking_key.'%');
			});
		}
		if (!empty($search['customer_ref'])) {
			$customer_ref = $search['customer_ref'];
			$models = $models->whereHas('connote', function($q) use ($customer_ref) {
				$q->where('details', 'LIKE', '%'.$customer_ref.'%');
			});
		}

		if (!empty($search['sup_name'])) {
			$models = $models->where('sup_name', 'LIKE', '%'.$search['sup_name'].'%');
		}
		if (!empty($search['msg_name'])) {
			$models = $models->where('msg_name', 'LIKE', '%'.$search['msg_name'].'%');
		}

		$models = $models->with(['connote.booking.logs', 'connote.logs']);
		return $models->orderBy('created_at', 'DESC')->offset($page)->limit(10)->get();
	}

	public function buildAttr($model, $build_connote = false)
	{
		$model->status_label = $this->{'label_'.$model->status};
		$model->status_color = $this->{'color_'.$model->status};
		$model->flow_label = !empty($model->flow) ? $this->{'label_flow_'.$model->flow} : '';
		$model->cod_label = ($model->cod) ? config('labels.booking.cod.th') : config('labels.booking.booking.th');
		$model->created_label = helperThaiFormat($model->created_at, true).' เวลา '.helperDateFormat($model->created_at, 'H:i น.');
		$model->updated_label = helperThaiFormat($model->updated_at, true).' เวลา '.helperDateFormat($model->updated_at, 'H:i น.');
		$model->received_label = !empty($model->received_at)
		 						? helperThaiFormat($model->received_at, true).' เวลา '.helperDateFormat($model->received_at, 'H:i น.')
		 						: '';
		$photo = '';
		if($model->photo or $model->photo){
			if($model->photo){
				$photo = 'http://af1express.com/api/ajax/'.$model->id.'_sign.png';//urlContent().'/'.$model->photo;
			}
		}else{
			$photo =  urlAdminImage().'/placeholder.png';
		}
		$model->photo_url = $photo;//!empty($model->photo) ? urlContent().'/'.$model->photo : urlAdminImage().'/placeholder.png';
		$model->have_photo = !empty($photo) ? '1' : '0';
		$model->receiver_name = !empty($model->receiver_name) ? $model->receiver_name : '';

		// TOPUP.
		$topup_display = '';
		$topupRepo = new TopupRepository;
		if (!empty($model->topup)) {
			$topups = explode(',', $model->topup);
			foreach ($topups as $topup) {
				$topup_display .= empty($topup_display) ? $topupRepo->{'label_'.$topup} : ', '.$topupRepo->{'label_'.$topup};
			}
		}

		$model->topup_display = $topup_display;

		// LOG.
		$model->complete_datetime_label = '';
		$model->inprogress_datetime_label = '';
		$model->new_datetime_label = '';
		$model->complete_datetime_return = '';
		$model->inprogress_datetime_return = '';
		$model->new_datetime_return = '';

		if (!empty($model->logs)) {

			foreach ($model->logs as $log) {
				if ($log->status == $this->complete && $log->notes == $this->flow_send) {
					$model->complete_datetime_label = helperThaiFormat($log->created_at, true);
					$model->complete_datetime_label .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
				}
				if ($log->status == $this->inprogress && $log->notes == $this->flow_send) {
					$model->inprogress_datetime_label = helperThaiFormat($log->created_at, true);
					$model->inprogress_datetime_label .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
				}
				if ($log->status == $this->new && $log->notes == $this->flow_send) {
					$model->new_datetime_label = helperThaiFormat($log->created_at, true);
					$model->new_datetime_label .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
				}
				if ($log->status == $this->complete && $log->notes == $this->flow_return) {
					$model->complete_datetime_return = helperThaiFormat($log->created_at, true);
					$model->complete_datetime_return .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
				}
				if ($log->status == $this->inprogress && $log->notes == $this->flow_return) {
					$model->inprogress_datetime_return = helperThaiFormat($log->created_at, true);
					$model->inprogress_datetime_return .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
				}
				if ($log->status == $this->new && $log->notes == $this->flow_return) {
					$model->new_datetime_return = helperThaiFormat($log->created_at, true);
					$model->new_datetime_return .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
				}
			}
		}

		$connoteRepo = new ConnoteRepository;

		if ($build_connote && !empty($model->connote)) {
			$model->connote->key = !empty($model->connote->key) ? $model->connote->key : 'ยังไม่มีเลข Connote';
			$model->connote->service_label = $connoteRepo->{'label_'.$model->connote->service};
			$model->connote->express_label = $connoteRepo->{'label_express_'.$model->connote->express};
			$model->connote->url_pdf = \URL::route('home.gen_connote.index.get', $model->connote->key);
			$detail = helperJsonDecodeToArray($model->connote->details);
			$model->connote->customer_ref = !empty($detail['csn']->customer_ref) ? $detail['csn']->customer_ref : '';
			$model->connote->booking->get_datetime_label = helperThaiFormat($model->connote->booking->get_datetime, true).' เวลา '.helperDateFormat($model->connote->booking->get_datetime, 'H:i น.');

			$bookingRepo = new BookingRepository;
			$model->connote->booking = $bookingRepo->buildAttr($model->connote->booking);
		}

		if ($model['connote']['job_send']) {

			$model['connote']['job_send']['status_label'] = $this->{'label_'.$model['connote']['job_send']['status']};
			$model['connote']['job_send']['received_label'] = helperThaiFormat($model['connote']['job_send']['received_at'], true).' เวลา '.helperDateFormat($model['connote']['job_send']['received_at'], 'H:i น.');
			$model['connote']['job_send']['photo_url'] = !empty($model['connote']['job_send']['photo']) ? urlContent().'/'.$model['connote']['job_send']['photo'] : urlAdminImage().'/placeholder.png';
		}

		return $model;
	}

	public function createJobs($connoteModels, $empModel)
	{
		$models = [];

		foreach ($connoteModels as $connoteModel) {

 			try {

				$model = new Job;
				$model->connote_id = $connoteModel->id;
				$model->key = $connoteModel->key;
				$model->status = $this->new;
				$model->flow = $this->flow_send;
				$model->msg_key = $empModel->emp_key;
				$model->msg_name = $empModel->nickname;
				$model->consignee = $connoteModel->consignee_company;

				$detail = helperJsonDecodeToArray($connoteModel->details);

				if (!empty($detail['csn'])) {
					$model->address = !empty($detail['csn']->address) ? $detail['csn']->address : '';
					$model->district = !empty($detail['csn']->district) ? $detail['csn']->district : '';
					$model->province = !empty($detail['csn']->province) ? $detail['csn']->province : '';
					$model->postcode = !empty($detail['csn']->postcode) ? $detail['csn']->postcode : '';
				}

				$model->save();
				$models[] = $model;

 			} catch (QueryException $e){

				return false;
 			}
		}

		return $models;
	}

	public function createJobSend($jobModel)
	{
		$model = new JobSend;
        $model->connote_id = $jobModel->connote_id;
        $model->key = $jobModel->key;
        $model->status = $jobModel->status;
        $model->msg_key = $jobModel->msg_key;
        $model->msg_name = $jobModel->msg_name;
        $model->sup_key = $jobModel->sup_key;
        $model->sup_name = $jobModel->sup_name;
        $model->consignee = $jobModel->consignee;
        $model->address = $jobModel->address;
        $model->district = $jobModel->district;
        $model->province = $jobModel->province;
        $model->postcode = $jobModel->postcode;
        $model->photo = $jobModel->photo;
        $model->receiver_name = $jobModel->receiver_name;
        $model->received_at = $jobModel->received_at;
        $model->topup = $jobModel->topup;
        $model->notes = $jobModel->notes;
        $model->lat = $jobModel->lat;
        $model->lng = $jobModel->lng;
        $model->created_at = $jobModel->created_at;
        $model->updated_at = $jobModel->updated_at;
        $model->save();

        return $model;
	}

	public function clearJobForReturn($jobModel, $empModel)
	{
		$jobModel->status = $this->new;
		$jobModel->flow = $this->flow_return;
		$jobModel->msg_key = $empModel->emp_key;
		$jobModel->msg_name = $empModel->nickname;
		$jobModel->photo = null;
		$jobModel->receiver_name = null;
        $jobModel->received_at = null;
        $jobModel->sup_key = null;
        $jobModel->sup_name = null;
        $jobModel->topup = null;
        $jobModel->notes = null;
        $jobModel->lat = null;
        $jobModel->lng = null;

		$ctmModel = $jobModel->connote->booking->customer;
		if (!empty($ctmModel)) {
			$jobModel->consignee = $ctmModel->name;
			$jobModel->address = $ctmModel->address;
			$jobModel->district = $ctmModel->district;
			$jobModel->province = $ctmModel->province;
			$jobModel->postcode = $ctmModel->postcode;
		}

		$jobModel->save();

		return $jobModel;
	}

}