<?php namespace App\Services\Bookings;

use App\Services\Bookings\Booking;
use App\Services\Customers\CustomerRepository;
use App\Services\Users\UserAdmin;
use App\Services\Connotes\ConnoteRepository;

class BookingRepository {

	public $pending 	= 'pending';
	public $new 		= 'new';
	public $inprogress 	= 'inprogress';
	public $complete 	= 'complete';
	public $cancel 		= 'cancel';

	public $label_pending 		= 'รออนุมัติ';
	public $label_new 			= 'เริ่มงานรับของ';
	public $label_inprogress	= 'กำลังไปรับ';
	public $label_complete 		= 'รับของเรียบร้อย';
	public $label_cancel 		= 'ยกเลิก';

	public $color_pending 		= '#F96868'; //'red';
	public $color_new 			= '#E98F2E'; //'orange';
	public $color_inprogress 	= '#3583CA'; //'blue';
	public $color_complete 		= '#36AB7A'; //'green';
	public $color_cancel 		= '#9E9E9E'; //'grey';

	public function statusAll()
	{
		return [
			$this->pending => $this->label_pending,
			$this->new => $this->label_new,
			$this->inprogress => $this->label_inprogress,
			$this->complete => $this->label_complete,
			$this->cancel => $this->label_cancel,
		];
	}
	public function getBookingToday()
	{
		return Booking::where('get_datetime', '>=', date('Y-m-d 00:00:00'))
				->where('get_datetime', '<=', date('Y-m-d 23:59:59'))
				->orderBy('get_datetime', 'desc')
				->get();
	}

	public function getBookingTodayByEmpKey($emp_key)
	{
		return Booking::where('msg_key', $emp_key)
			->where('get_datetime', '>=', date('Y-m-d 00:00:00'))
			->where('get_datetime', '<=', date('Y-m-d 23:59:59'))
		 	->orderBy('get_datetime', 'desc')->get();
	}

	public function getForApp($emp_key)
	{
		$model = Booking::where('msg_key', $emp_key);
		$model = $model->where(function($query){

			$query->where(function($q){
				$q->where('status', 'new');
				$q->where('get_datetime', '<=', date('Y-m-d 23:59:59'));
			});

			$query->orWhere(function($q){
				$q->where('status', 'inprogress');
			});

			$query->orWhere(function($q){
				$q->whereIn('status', ['complete', 'cancel']);
				$q->where('get_datetime', '>=', date('Y-m-d 00:00:00'));
				$q->where('get_datetime', '<=', date('Y-m-d 23:59:59'));
			});
		});

		return $model->orderBy('get_datetime', 'asc')->get();
	}

	public function getEmpty()
	{
		$model = new Booking;
		$model->id = 0;
		return $model;
	}
	public function getByID($id)
	{
		return Booking::where('id', $id)->with('connotes')->first();
	}
	public function getByKey($key)
	{
		return Booking::where('key', $key)->with('connotes')->first();
	}
	public function getByCustomerKey($key)
	{
		return Booking::where('customer_key', $key)->with('connotes')->orderBy('get_datetime', 'desc')->get();
	}
	public function getByKeyAndCustomerKey($customer_key, $key)
	{
		return Booking::where('key', $key)->where('customer_key', $customer_key)->with('connotes')->first();
	}
	public function getByMsgKeyLimitPreviousMonth($key, $month = 2)
	{
		$previous_date = date('Y-m-d 0:00:00', strtotime('-60 days'));

		return Booking::where('msg_key', $key)
		 		->where('get_datetime', '>=', $previous_date)
		 		->orderBy('get_datetime', 'desc')
		 		->get();
	}

	public function getBySearch($search,$page=0)
	{
		$page = ($page-1)*10;
		
		$models = new Booking;

		if (!empty($search['start_date'])) {
			$models = $models->where('get_datetime', '>=', $search['start_date'].' 00:00:00');
		}
		if (!empty($search['end_date'])) {
			$models = $models->where('get_datetime', '<=', $search['end_date'].' 23:59:59');
		}
		if (!empty($search['status']) && $search['status'] !== 'all') {
			$models = $models->where('status', $search['status']);
		}
		if (!empty($search['booking_key'])) {
			$models = $models->where('key', 'LIKE', '%'.$search['booking_key'].'%');
		}
		if (!empty($search['ctm_name'])) {
			$models = $models->where('customer_name', 'LIKE', '%'.$search['ctm_name'].'%');
		}
		if (!empty($search['created_by'])) {
			$models = $models->where('note', 'LIKE', '%'.$search['created_by'].'%');
		}
		if (!empty($search['cs_name'])) {
			$models = $models->where('cs_name', 'LIKE', '%'.$search['cs_name'].'%');
		}
		if (!empty($search['msg_name'])) {
			$models = $models->where('msg_name', 'LIKE', '%'.$search['msg_name'].'%');
		}

		$models = $models->with(['connotes' => function($q){
			$q->where('status', '<>', 'cancel');
		}]);
		$models = $models->with(['logs', 'msg']);
		// var_dump($models);exit();
		return $models->orderBy('get_datetime', 'DESC')->offset($page)->limit(10)->get();
	}

	public function buildAttr($model, $build_connote = false)
	{
		$model->status_label = $this->{'label_'.$model->status};
		$model->status_color = $this->{'color_'.$model->status};

		$model->cod_label = ($model->cod) ? config('labels.booking.cod.th') : config('labels.booking.booking.th');
		$model->is_cod = ($model->cod) ? 'มีเก็บเงินปลายทาง' : '';

		$persons = helperJsonDecodeToArray($model->person);
		$model->person_name = isset($persons['name']) ? $persons['name'] : '';
		$model->person_mobile = isset($persons['mobile']) ? $persons['mobile'] : '';
		$date = helperThaiFormat($model->get_datetime, true);
		$time = helperDateFormat($model->get_datetime, 'H:i น.');
		$model->get_datetime_label = $date.' เวลา '.$time;

		// LOG.
		$model->complete_datetime_label = '';
		$model->inprogress_datetime_label = '';
		$model->new_datetime_label = '';
		$model->pending_datetime_label = '';

		foreach ($model->logs as $log) {
			if ($log->status == $this->complete) {
				$model->complete_datetime_label = helperThaiFormat($log->created_at, true);
				$model->complete_datetime_label .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
			}
			if ($log->status == $this->inprogress) {
				$model->inprogress_datetime_label = helperThaiFormat($log->created_at, true);
				$model->inprogress_datetime_label .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
			}
			if ($log->status == $this->new) {
				$model->new_datetime_label = helperThaiFormat($log->created_at, true);
				$model->new_datetime_label .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
			}
			if ($log->status == $this->pending) {
				$model->pending_datetime_label = helperThaiFormat($log->created_at, true);
				$model->pending_datetime_label .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
			}
		}

		$model->created_label = helperThaiFormat($model->created_at, true).' เวลา '.helperDateFormat($model->created_at, 'H:i น.');
		$model->updated_label = helperThaiFormat($model->updated_at, true).' เวลา '.helperDateFormat($model->updated_at, 'H:i น.');

		$notes = helperJsonDecodeToArray($model->note);
		$model->created_by = isset($notes['created_by']) ? $notes['created_by'] : '';
		$model->size = isset($notes['size']) ? $notes['size'] : '';
		$model->note_that = isset($notes['note_that']) ? $notes['note_that'] : '';

		$connoteRepo = new ConnoteRepository;
		$model->express_label = $connoteRepo->{'label_express_'.$model->express};

		if ($build_connote) {

			foreach ($model->connotes as $connote) {
				$connote->key = !empty($connote->key) ? $connote->key : 'ยังไม่มีเลข Connote';
				$connote->service_label = $connoteRepo->{'label_'.$connote->service};
				$connote->express_label = $connoteRepo->{'label_express_'.$connote->express};
				$connote->url_pdf = \URL::route('home.gen_connote.index.get', $connote->key);
				$detail = helperJsonDecodeToArray($connote->details);
				$connote->customer_ref = !empty($detail['csn']->customer_ref) ? $detail['csn']->customer_ref : '';
			}
		}

		return $model;
	}

	public function createData($request, $created_by = 'cs')
	{
		$customerRepo = new CustomerRepository;
		$customerModel = $customerRepo->getById($request->input('customer_id'));
		if (empty($customerModel)) return false;
		$booking = new Booking;
		$booking->status = $this->pending;
		$booking->customer_id = $customerModel->id;
		$booking->customer_key = $customerModel->key;
		$booking->customer_name = $customerModel->name;
		$booking->address  = $request->input('address');
		$booking->district = $request->input('district');
		$booking->province = $request->input('province');
		$booking->postcode = $request->input('postcode');

		$person = [];
		$person['name'] = $request->input('person_name');
		$person['mobile'] = $request->input('person_mobile');
		$booking->person = helperJsonEncode($person);

		$booking->cod = $request->input('cod');
		$booking->express = $request->input('express');
		$booking->car_id = $request->input('car_id');
		$booking->get_datetime = helperDateFormatPickerToDB($request->input('get_date')).' '.$request->input('get_time');
		$booking->key = 'temp0000';

		$note = [];
		$userAdmin = new UserAdmin;
		$note['created_by'] 	= ($created_by == 'cs') ? $customerModel->name : $userAdmin->getNickname();
		$note['have_connote'] 	= $request->input('have_connote');
		$note['size'] 			= !empty($request->input('size')) ? $request->input('size') : '';
		$note['note_that'] 		= !empty($request->input('note_that')) ? $request->input('note_that') : '';

		if ($request->has('pointChosens')) {
			foreach ($request->input('pointChosens') as $pointChosen) {
				$note['points'][] = $pointChosen['id'];
			}
		}

		$booking->note = helperJsonEncode($note);
		$booking->save();

		$booking->key = sprintf('B%06d', $booking->id);
		$booking->save();
		return $booking;
	}

	public function updateMsg($model, $msgModel)
	{
		$model->status = $this->new;
		$model->msg_key = $msgModel->emp_key;
		$model->msg_name = $msgModel->nickname;

		$userAdmin = new UserAdmin;
		$model->cs_name = $userAdmin->getNickname();
		$model->cs_key = $userAdmin->getEmpKey();

		$model->save();

		return $model;
	}

	public function updateStatus($model, $new_status)
	{
		switch ($new_status) {

			case $this->inprogress:

				if ($model->status !== $this->new && $model->status !== $this->complete) return ['status' => 'fail', 'msg' => 'สถานะผิด'];

				break;

			case $this->complete:

				$connoteRepo = new ConnoteRepository;
				foreach ($model->connotes as $connote) {
					if ($connote->status != $connoteRepo->confirm) return ['status' => 'fail', 'msg' => 'ขาด Connote เลข '.$connote->key];
				}
				if ($model->status !== $this->inprogress) return ['status' => 'fail', 'msg' => 'สถานะผิด'];
				break;

			case $this->cancel:

				if ($model->status !== $this->new) return ['status' => 'fail', 'msg' => 'สถานะผิด'];
				break;
		}

		$model->status = $new_status;
		$model->save();
		return ['status' => 'success', 'data' => $model];
	}
}