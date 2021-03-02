<?php namespace App\Services\Connotes;

use App\Services\Connotes\Connote;
use Illuminate\Database\QueryException;
use App\Services\Logs\LogConnoteRepository;
;
class ConnoteRepository {

	public $oneway = 'oneway';
	public $return = 'return';
	public $label_oneway = 'ส่งอย่างเดียว';
	public $label_return = 'ส่งและรับกลับ';
	public $label_express_0 = 'ส่งพัสดุ (Nextday delivery)';
	public $label_express_1 = 'ส่งพัสดุด่วน (Sameday Delivery)';

	public $temp = 'temp';
	public $pending = 'pending';
	public $confirm = 'confirm';
	public $cancel = 'cancel';

	public function schemaPiece($total = 5)
	{
		$pieces = [];

		for ($i = 0; $i < $total; $i++) {
			$pieces[] = ['width' => '', 'height' => '', 'length' => '', 'weight' => ''];
		}

		return (object)$pieces;
	}
	public function schemaConsigneeDetail()
	{
		return (object)['address' => '', 'district' => '', 'province' => '', 'postcode' => ''];
	}
	public function getExpressType()
	{
		return [
			['value' => 0, 'label' => $this->label_express_0],
			['value' => 1, 'label' => $this->label_express_1]
		];
	}
	public function getByKey($key)
	{
		return Connote::where('key', $key)->first();
	}
	public function getByKeys($keys = [])
	{
		return Connote::whereIn('key', $keys)->get();
	}
	public function getByKeyAndCustomerKeyWithRelation($customer_key, $key)
	{
		return Connote::where('key', $key)->whereHas('booking', function($q) use ($customer_key) {
			$q->where('customer_key', $customer_key);
		})->with(['booking.customer.points', 'job.msg'])->first();
	}
	public function getByKeyWithRelation($key)
	{
		return Connote::where('key', $key)->with(['booking.customer.points', 'job.msg'])->first();
	}
	public function getById($id)
	{
		return Connote::find($id);
	}
	public function getByIdWithRelation($id)
	{
		return Connote::where('id', $id)->with('booking.customer.points')->first();
	}
	public function getByIds($ids)
	{
		return Connote::whereIn('id', $ids)->get();
	}
	public function countAll()
	{
		return Connote::count();
	}
	public function getNewKey($point_id)
	{
		// $latest = Connote::latest()->first();
		// $id = (empty($latest)) ? 0 : $latest->id;
		return date('ym').sprintf('%05d', $point_id).sprintf('%04d', rand(0, 9999));//.sprintf('%06d', $id+$plus+1);
	}

	public function getBySearch($search,$page=0)
	{
		// if($page>1){
			$page = ($page-1)*10;
		// }

		$models = new Connote;
		// var_dump($search['start_date']);

		if (!empty($search['start_date'])) {
			$models = $models->where('created_at', '>=', $search['start_date'].' 00:00:00');
		}
		if (!empty($search['end_date'])) {
			$models = $models->where('created_at', '<=', $search['end_date'].' 23:59:59');
		}
		if (!empty($search['connote_key'])) {
			$models = $models->where('key', 'LIKE', '%'.$search['connote_key'].'%');
		}
		if (!empty($search['shipper_name'])) {
			$models = $models->where('shipper_name', 'LIKE', '%'.$search['shipper_name'].'%');
		}
		if (!empty($search['consignee_name'])) {
			$models = $models->where('consignee_name', 'LIKE', '%'.$search['consignee_name'].'%');
		}
		if (!empty($search['customer_ref'])) {
			$models = $models->where('details', 'LIKE', '%'.$search['customer_ref'].'%');
		}
		// echo $page;exit();
		$models->with('job')->count();
		// var_dump($models->with('job')->orderBy('id', 'ASC')->offset($page)->limit(10)->get());exit();
		return $models->with('job')->orderBy('id', 'ASC')->offset($page)->limit(10)->get();
	}
	public function countGetBySearch($search)
	{
		// if($page>1){
			// $page = ($page-1)*10;
		// }

		$models = new Connote;

		if (!empty($search['start_date'])) {
			$models = $models->where('created_at', '>=', $search['start_date'].' 00:00:00');
		}
		if (!empty($search['end_date'])) {
			$models = $models->where('created_at', '<=', $search['end_date'].' 23:59:59');
		}
		if (!empty($search['connote_key'])) {
			$models = $models->where('key', 'LIKE', '%'.$search['connote_key'].'%');
		}
		if (!empty($search['shipper_name'])) {
			$models = $models->where('shipper_name', 'LIKE', '%'.$search['shipper_name'].'%');
		}
		if (!empty($search['consignee_name'])) {
			$models = $models->where('consignee_name', 'LIKE', '%'.$search['consignee_name'].'%');
		}
		if (!empty($search['customer_ref'])) {
			$models = $models->where('details', 'LIKE', '%'.$search['customer_ref'].'%');
		}
		// echo $page;exit();
		// $models->with('job')->count();
		// var_dump($models->with('job')->orderBy('id', 'ASC')->offset($page)->limit(10)->get());exit();
		return $models->with('job')->count();
	}
	public function buildAttr($model)
	{
		$model->service_label = $this->{'label_'.$model->service};
		$model->express_label = $this->{'label_express_'.$model->express};
		$model->created_label = helperThaiFormat($model->created_at, true).' เวลา '.helperDateFormat($model->created_at, 'H:i น.');
		$model->updated_label = helperThaiFormat($model->updated_at, true).' เวลา '.helperDateFormat($model->updated_at, 'H:i น.');
		$model->cod_label = ($model->cod) ? config('labels.booking.cod.th') : config('labels.booking.booking.th');

		$details = helperJsonDecodeToArray($model->details);
		$model->detail_pieces = isset($details['pieces']) ? $details['pieces'] : $this->schemaPiece();
		$model->csn = !empty($details['csn']) ? $details['csn'] : $this->schemaConsigneeDetail();
		$model->customer_ref = !empty($model->csn->customer_ref) ? $model->csn->customer_ref : '';

		$model->pending_datetime_label = '';
		foreach ($model->logs as $log) {
			if ($log->status == $this->confirm) {
				$model->pending_datetime_label = helperThaiFormat($log->created_at, true);
				$model->pending_datetime_label .= ' เวลา '.helperDateFormat($log->created_at, 'H:i น.');
			}
		}
		return $model;
	}

	public function createConnotes($bookingModel, $inputs, $cod = 0, $from = 'web', $emp_name = '')
	{
		if (empty($inputs)) return true;

		$model_ids = [];

		foreach ($inputs as $input) {

 			try {

				$model = new Connote;

				if ($from == 'web') {

					$model->status = ($cod == 1) ? $this->pending : $this->temp;

				} else {

					$model->status = $this->confirm;
				}

				$model->key = !empty($input->no) ? $input->no : null;
				$model->booking_id = $bookingModel->id;
				$model->shipper_name = !empty($bookingModel->person_name) ? $bookingModel->person_name : '';
				$model->shipper_company = $bookingModel->customer_name;

				$model->shipper_address = $bookingModel->address.' ';
				$model->shipper_address .= $bookingModel->district;
				$model->shipper_address .= $bookingModel->province.' '.$bookingModel->postcode;

				$model->shipper_phone = !empty($bookingModel->person_mobile) ? $bookingModel->person_mobile : '';
				$model->express = $bookingModel->express;

				$detail = [];
				$detail['address'] = !empty($input->address) ? $input->address : '';
				$detail['district'] = !empty($input->district) ? $input->district : '';
				$detail['province'] = !empty($input->province) ? $input->province : '';
				$detail['postcode'] = !empty($input->postcode) ? $input->postcode : '';
				$detail['customer_ref'] = !empty($input->customer_ref) ? $input->customer_ref : '';
				$model->consignee_address = $detail['address'].' '.$detail['district'].' '.$detail['province'].' '.$detail['postcode'];

				$csn = $this->schemaConsigneeDetail();
				$model->details = helperJsonEncode(['csn' => $detail]);

				$model->consignee_name = !empty($input->person) ? $input->person : null;
				$model->consignee_company = !empty($input->company) ? $input->company : null;
				$model->consignee_phone = !empty($input->phone) ? $input->phone : null;

				$model->service = !empty($input->service) ? $input->service : $this->return;
				$model->cod = $cod;
				$model->cod_value = ($cod == 1) ? (!empty($input->value) ? $input->value : '0') : null;

				$model->save();
				$model_ids[] = $model->id;


				$log = new LogConnoteRepository;
				if ($from == 'web') $log->put($model);
				elseif ($from == 'app') $log->put($model, $emp_name);

 			} catch (QueryException $e){

				return false;
 			}
		}

		return $model_ids;
	}

	public function updateConnotes($models, $connotes, $emp_name = null)
	{
		foreach ($models as $model) {

			foreach ($connotes as $k => $connote) {

	 			try {

					if ($model->key == $connote->no) {

						$model->key = $connote->no;
						$model->status = $this->confirm;
						$model->service = $connote->service;
						$model->cod = '0';
						$model->save();

						$log = new LogConnoteRepository;
						$log->put($model, $emp_name);

						unset($connotes[$k]);
					}

				} catch (QueryException $e){

					return false;
				}
			}
		}

		return $connotes;
	}

	public function updateCodConnotes($models, $connotes, $emp_name = null)
	{
		$result = false;
		foreach ($models as $model) {
			foreach ($connotes as $update) {

				if ($model->key == $update->no) {
					$model->status = $this->confirm;
					$model->save();

					$log = new LogConnoteRepository;
					$log->put($model, $emp_name);

					$result = true;
				}
			}
		}

		return $result;
	}

	public function updateDataFromAdmin($model, $input)
	{
		$model->key = $input['key'];
		$model->shipper_name 	= $input['shipper_name'];
		$model->shipper_company = $input['shipper_company'];
		$model->shipper_address = $input['shipper_address'];
		$model->shipper_phone 	= $input['shipper_phone'];

		$model->consignee_name 		= $input['consignee_name'];
		$model->consignee_company 	= $input['consignee_company'];
		$model->consignee_address 	= $input['consignee_address'];
		$model->consignee_phone 	= $input['consignee_phone'];

		$model->service = $input['service'];
		$model->express = $input['express'];
		$model->cod_value = $input['cod_value'];

		$details = helperJsonDecodeToArray($model->details);
		$details['pieces'] = $input['detail_pieces'];

		$csn = !empty($details['csn']) ? (array)$details['csn'] : [];
		$csn['address'] = !empty($input['csn']['address']) ? $input['csn']['address'] : '';
		$csn['district'] = !empty($input['csn']['district']) ? $input['csn']['district'] : '';
		$csn['province'] = !empty($input['csn']['province']) ? $input['csn']['province'] : '';
		$csn['postcode'] = !empty($input['csn']['postcode']) ? $input['csn']['postcode'] : '';
		$csn['customer_ref'] = !empty($input['csn']['customer_ref']) ? $input['csn']['customer_ref'] : '';
		$details['csn'] = $csn;

		$model->details = helperJsonEncode($details);
		$model->save();

		if (!empty($model->job)) {
			$jobModel = $model->job;
			$jobModel->key = $model->key;
			$jobModel->save();
		}

		$log = new LogConnoteRepository;
		$log->put($model);

		return $model;
	}
}