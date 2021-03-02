<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Users\UserAdmin;
use App\Services\Connotes\Connote;
use App\Services\Excels\ExcelRepository;
use App\Services\Reports\ReportRepository;
use App\Services\Bookings\BookingRepository;
use App\Services\Connotes\ConnoteRepository;
use App\Services\Jobs\JobRepository;
use App\Services\Customers\Customer;

class ReportController extends Controller
{
	private $sheet_dir = 'reports';

	public static $requiredPermissions = [
		'getIndex'			=> 'report.get',
		'postGetData'		=> 'report.post',
		'postAdjustReport'	=> 'report.post',
		'postGenerateExcel'	=> 'report.post',
	];

	public function getIndex()
	{
		// DATE.
		$bkg_start = date('d/m/Y');
		$bkg_end = date('d/m/Y');
		$msg_start = date('d/m/Y');
		$msg_end = date('d/m/Y');
		$job_start = date('d/m/Y');
		$job_end = date('d/m/Y');
		$step 	= 'h';
		$status = 'all';
		$ctm_chosen = 'all';

		// SELECTED FIELDS.
		$adminObject = new UserAdmin;
		$adminModel = $adminObject->getModel();
		$adminNote = !empty($adminModel) ? helperJsonDecodeToArray($adminModel->notes) : [];
		// var_dump($adminNote);exit();
		
		if (empty($adminNote['report_field'])) {

			$adminNote['report_field'] = $this->getKeyFieldAll();

			$adminModel->notes = json_encode($adminNote);
			$adminModel->save();
		}

		$selected_field = $adminNote['report_field'];

		// GET AND BUIKD DATA.
		$models 	 = $this->getData($bkg_start, $bkg_end, $msg_start, $msg_end, $job_start, $job_end, $status, $ctm_chosen);
		$report_data = $this->mapForReport($models);
		$chart_data  = $this->buildChartData($bkg_start, $bkg_end, $step, $models);

		$fields = $this->getFields();
		$bkg_start = helperDateFormatDBToPicker($bkg_start);
		$bkg_end = helperDateFormatDBToPicker($bkg_end);

		$jobRepo = new JobRepository;

		// STATUS.
		$statusAll = array_merge(['pending' => 'ยังไม่เปิดงานส่ง'], $jobRepo->statusAll());
		// CUSTOMER COMPANY.
		$ctmAll = Customer::orderBy('name')->get();

		$data = compact('bkg_start', 'bkg_end', 'msg_start', 'msg_end', 'job_start', 'job_end', 'step', 'fields', 'report_data', 'chart_data', 'selected_field', 'statusAll', 'status', 'ctmAll', 'ctm_chosen');
		return $this->view('admin.pages.report.index', $data);
	}

	public function postGetData(Request $request)
	{
		if ($request->ajax()) {

			$bkg_start = $request->input('bkg_start') ? $request->input('bkg_start') : date('d/m/Y');
			$bkg_end = $request->input('bkg_end') ? $request->input('bkg_end') : date('d/m/Y');
			$msg_start = $request->input('msg_start') ? $request->input('msg_start') : date('d/m/Y');
			$msg_end = $request->input('msg_end') ? $request->input('msg_end') : date('d/m/Y');
			$job_start = $request->input('job_start') ? $request->input('job_start') : date('d/m/Y');
			$job_end = $request->input('job_end') ? $request->input('job_end') : date('d/m/Y');
			$step = $request->input('step');
			$status = $request->input('status');
			$ctm_chosen = $request->input('ctm_chosen');

			// REPORT DATA.
			$models = $this->getData($bkg_start, $bkg_end, $msg_start, $msg_end, $job_start, $job_end, $status, $ctm_chosen);
			$report_data = $this->mapForReport($models);
			$chart_data  = $this->buildChartData($bkg_start, $bkg_end, $step, $models);

			$bkg_start = helperDateFormatDBToPicker($bkg_start);
			$bkg_end 	= helperDateFormatDBToPicker($bkg_end);
			return compact('bkg_start', 'bkg_end', 'msg_start', 'msg_end', 'job_start', 'job_end', 'report_data', 'chart_data');
		}
	}

	public function postAdjustReport(Request $request)
	{
		if ($request->ajax()) {

			$adminObject = new UserAdmin;
			$adminModel = $adminObject->getModel();

			$adminNote['report_field'] = $request->has('selected_fields') ? $request->input('selected_fields') : "";
			$adminModel->notes = json_encode($adminNote);
			$adminModel->save();

			return ['status' => 'success'];
		}
	}
	public function postGenerateExcel(Request $request)
	{
		if ($request->ajax()) {

			$bkg_start = $request->has('bkg_start') ? $request->input('bkg_start') : date('d/m/Y');
			$bkg_end = $request->has('bkg_end') ? $request->input('bkg_end') : date('d/m/Y');
			$msg_start = $request->input('msg_start') ? $request->input('msg_start') : date('d/m/Y');
			$msg_end = $request->input('msg_end') ? $request->input('msg_end') : date('d/m/Y');
			$job_start = $request->input('job_start') ? $request->input('job_start') : date('d/m/Y');
			$job_end = $request->input('job_end') ? $request->input('job_end') : date('d/m/Y');
			$status = $request->input('status');
			$ctm_chosen = $request->input('ctm_chosen');

			// REPORT DATA.
			$models = $this->getData($bkg_start, $bkg_end, $msg_start, $msg_end, $job_start, $job_end, $status, $ctm_chosen);
			$report_data = $this->mapForReport($models);
			$fields = $this->getFields();
			$selected_fields = $request->input('selected_fields');
			if (empty($selected_fields)) $selected_fields = $fields;

			// Header at Row 0.
			foreach ($selected_fields as $selected_field) {
				$excel_data[0][] = isset($fields[$selected_field]) ? $fields[$selected_field] : '';
			}

			// Rows.
			foreach ($report_data as $key => $data) {

				$excel = [];

				foreach ($selected_fields as $selected_field) {

					$excel[] = isset($data[$selected_field]) ? $data[$selected_field] : '';
				}

				$excel_data[] = $excel;
			}

			if (!empty($excel_data)) {

				$sheet_name = 'Connote_'.date('Ymd_His');
				$sheet_title = 'Connote';

				$excelRepo = new ExcelRepository;
				$excelRepo->removeAllFileInTempExcelForlder($this->sheet_dir);
				$excelRepo->writeExcel($excel_data, $sheet_name, $this->sheet_dir, $sheet_title);
				return ['status' => 'success', 'base_url' => urlBase(), 'sheet_path' => $this->sheet_dir.'/'.$sheet_name.'.xlsx'];
			}

			return ['status' => 'fail'];
		}
	}

	private function getData($bkg_start, $bkg_end, $msg_start, $msg_end, $job_start, $job_end, $status, $ctm_chosen)
	{
		$bkg_start = helperDateFormatPickerToDB($bkg_start).' 0:00:00';
		$bkg_end = helperDateFormatPickerToDB($bkg_end).' 23:59:59';
		$msg_start = helperDateFormatPickerToDB($msg_start).' 0:00:00';
		$msg_end = helperDateFormatPickerToDB($msg_end).' 23:59:59';
		$job_start = helperDateFormatPickerToDB($job_start).' 0:00:00';
		$job_end = helperDateFormatPickerToDB($job_end).' 23:59:59';

		$closure = ['booking' => function($booking) use ($bkg_start, $bkg_end, $msg_start, $msg_end) {

			$booking->whereHas('logs', function($q) use ($bkg_start, $bkg_end) {
				$q->where('status', 'pending');
				$q->where('created_at', '>=', $bkg_start)->where('created_at', '<=', $bkg_end);
			});

			$booking->whereHas('logs', function($q) use ($msg_start, $msg_end) {
				$q->where('status', 'inprogress');
				$q->where('created_at', '>=', $msg_start)->where('created_at', '<=', $msg_end);
			});
		}];

		$models = Connote::whereHas('booking', $closure['booking']);
		$models = $models->with($closure);

		if ($status == 'complete') {

			$models = $models->whereHas('job', function($query) use ($job_start, $job_end) {
				$query->whereHas('logs', function($q) use ($job_start, $job_end) {
					$q->where('status', 'complete');
					$q->where('created_at', '>=', $job_start)->where('created_at', '<=', $job_end);
				});
			});
		}


		if ($status == 'pending') {

			$models = $models->doesntHave('job');

		} else if ($status != 'all') {

			$models = $models->whereHas('job', function($q) use ($status) {
				$q->where('status', $status);
			});
		}


		if ($ctm_chosen != 'all') {
			$models = $models->whereHas('booking', function($q) use ($ctm_chosen) {
				$q->where('customer_id', $ctm_chosen);
			});
		}

		return $models->limit(300)->get();
	}

	private function buildChartData($bkg_start, $bkg_end, $step, $models)
	{
		$start = helperDateFormatPickerToDB($bkg_start).' 0:00:00';
		$end = helperDateFormatPickerToDB($bkg_end).(($bkg_end != date('d/m/Y')) ? ' 23:59:59' : ' '.date('H:i:s'));

		$reportRepo = new ReportRepository;
		$data_sets = $reportRepo->genSetTime($start, $end, $step);

		$results = $reportRepo->map($data_sets, $models, 'created_at');

		return $results;
	}

	private function getFields()
	{
	 	$fields = [

		 	'en' =>	[
				'booking_no' => 'Booking No',
				'booking_datetime' => 'เวลาที่เริ่ม Booking',
				'company_id' => 'Company ID',
				'shipper_company' => 'บริษัทผู้ส่ง ',
				'shipper_district' => 'เขต อำเภอ',
				'shipper_province' => 'จังหวัด',
				'shipper_postcode' => 'รหัสไปรษณีย์',
				'shipper_name' => 'ชื่อผู้ส่ง ',
				'shipper_address' => 'ที่อยู่ผู้ส่ง',
				'shipper_phone' => 'เบอร์ติดต่อ ',
				'express' => 'Service Level',
				'service' => 'SERVICE TYPE',
				'size' => 'SIZE & WEIGHT',
				'pickup_datetime' => 'เวลาที่ Pickup',
				'booking_note' => 'หมายเหตุ',
				'customer_ref' => 'Customer REF',
				'job_start' => 'เวลาเริ่มส่ง',
				'consignee_name' => 'ชื่อผู้รับ',
				'consignee_phone' => 'เบอร์ติดต่อ',
				'consignee_company' => 'บริษัทผู้รับ',
				'consignee_address' => 'ที่อยู่ผู้รับ ',
				'consignee_district' => 'เขต อำเภอ',
				'consignee_province' => 'จังหวัด',
				'consignee_postcode' => 'รหัสไปร',
				'key' => 'เลข Connote',
				'status' => 'สถานะ',
				'receiver_name' => 'ชื่อผู้เซ็นรับของ',
				'complete_datetime' => 'เวลาที่ส่งสินค้า',
				'complete_datetime_return' => 'เวลาที่ Return Invoice',
				'job_note' => 'หมายเหตุ',
			],

			'th' =>	[
				'booking_no' => 'Booking No',
				'booking_datetime' => 'เวลาที่เริ่ม Booking',
				'company_id' => 'Company ID',
				'shipper_company' => 'บริษัทผู้ส่ง ',
				'shipper_district' => 'เขต อำเภอ',
				'shipper_province' => 'จังหวัด',
				'shipper_postcode' => 'รหัสไปรษณีย์',
				'shipper_name' => 'ชื่อผู้ส่ง ',
				'shipper_address' => 'ที่อยู่ผู้ส่ง',
				'shipper_phone' => 'เบอร์ติดต่อ ',
				'express' => 'Service Level',
				'service' => 'SERVICE TYPE',
				'size' => 'SIZE & WEIGHT',
				'pickup_datetime' => 'เวลาที่ Pickup',
				'booking_note' => 'หมายเหตุ',
				'customer_ref' => 'Customer REF',
				'job_start' => 'เวลาเริ่มส่ง',
				'consignee_name' => 'ชื่อผู้รับ',
				'consignee_phone' => 'เบอร์ติดต่อ',
				'consignee_company' => 'บริษัทผู้รับ',
				'consignee_address' => 'ที่อยู่ผู้รับ ',
				'consignee_district' => 'เขต อำเภอ',
				'consignee_province' => 'จังหวัด',
				'consignee_postcode' => 'รหัสไปร',
				'key' => 'เลข Connote',
				'status' => 'สถานะ',
				'receiver_name' => 'ชื่อผู้เซ็นรับของ',
				'complete_datetime' => 'เวลาที่ส่งสินค้า',
				'complete_datetime_return' => 'เวลาที่ Return Invoice',
				'job_note' => 'หมายเหตุ',
			]
		];

		return $fields[helperLang()];
	}

	private function getKeyFieldAll()
	{
		$all_field = $this->getFields();

		foreach ($all_field as $key => $field) {
			$report_field[] = $key;
		}
		return $report_field;
	}

	private function mapForReport($models)
	{
		$conRepo = new ConnoteRepository;
		$bkgRepo = new BookingRepository;
		$jobRepo = new JobRepository;

		$results = [];
		$no = 0;
		foreach ($models as $key => $model) {

			$model = $conRepo->buildAttr($model);
			$booking = $bkgRepo->buildAttr($model->booking);
			$job = !empty($model->job) ? $jobRepo->buildAttr($model->job) : null;

			$results[$no]['booking_no'] = $booking->key;
			$results[$no]['booking_datetime'] = $booking->pending_datetime_label;
			$results[$no]['company_id'] = $booking->customer_key;
			$results[$no]['shipper_name'] = $booking->person_name;
			$results[$no]['shipper_phone'] = $booking->person_mobile;
			$results[$no]['shipper_company'] = $booking->customer_name;
			$results[$no]['shipper_address'] = $booking->address;
			$results[$no]['shipper_district'] = $booking->district;
			$results[$no]['shipper_province'] = $booking->province;
			$results[$no]['shipper_postcode'] = $booking->postcode;
			$results[$no]['express'] = $model->express_label;
			$results[$no]['service'] = $model->service_label;

			$size_txt = '';
			foreach ($model->detail_pieces as $p => $piece) {
				if (empty($piece->width) && empty($piece->height) && empty($piece->length) && empty($piece->weight)) continue;
				$width = !empty($piece->width) ? str_replace(',', '', $piece->width) : 0;
				$height = !empty($piece->height) ? str_replace(',', '', $piece->height) : 0;
				$length = !empty($piece->length) ? str_replace(',', '', $piece->length) : 0;
				$weight = !empty($piece->weight) ? str_replace(',', '', $piece->weight) : 0;
				$size_txt .= (($p > 0) ? ',' : '').'{WIDTH:'.$width.',HEIGHT:'.$height.',LENGTH:'.$length.',WEIGHT:'.$weight.'}';
			}


			$results[$no]['size'] = '['.$size_txt.']';

			$results[$no]['pickup_datetime'] = $booking->complete_datetime_label;
			$results[$no]['booking_note'] = $booking->note_that;
			$results[$no]['customer_ref'] = $model->customer_ref;
			$results[$no]['job_start'] = !empty($job) ? $job->new_datetime_label : '';
			$results[$no]['consignee_name'] = $model->consignee_name;
			$results[$no]['consignee_phone'] = $model->consignee_phone;
			$results[$no]['consignee_company'] = $model->consignee_company;
			$results[$no]['consignee_address'] = $model->csn->address;
			$results[$no]['consignee_district'] = $model->csn->district;
			$results[$no]['consignee_province'] = $model->csn->province;
			$results[$no]['consignee_postcode'] = $model->csn->postcode;
			$results[$no]['key'] = $model->key;
			$results[$no]['status'] = !empty($job) ? $job->status_label : '';
			$results[$no]['receiver_name'] = !empty($job) ? $job->receiver_name : '';
			$results[$no]['complete_datetime'] = !empty($job) ? $job->complete_datetime_label : '';
			$results[$no]['complete_datetime_return'] = !empty($job) ? $job->complete_datetime_return : '';
			$results[$no]['job_note'] = !empty($job) ? $job->notes : '';

			$no++;
		}

		return $results;
	}
}