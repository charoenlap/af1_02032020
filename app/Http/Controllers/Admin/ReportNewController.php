<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
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

class ReportNewController extends Controller
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
		global $mdir;
		// ->leftJoin('jobs','connotes.id','jobs.connote_id')

		// var_dump($connotes_data);exit();

		$result = array();
			
		$book_date_start = date('Y-m-d');
		$book_date_end = date('Y-m-d');
		$job_date_start = '';
		$job_date_end = '';
		$msg_date_start = '';
		$msg_date_end = '';
		$status_chosen ='';
		$customers='';
		$where = '';
		if(isset($_GET['book_date_start'])){
			$date = str_replace('/', '-', $_GET['book_date_start'] );
			$book_date_start = date("Y-m-d", strtotime($date));
		}
		if(isset($_GET['book_date_end'])){
			$date = str_replace('/', '-', $_GET['book_date_end'] );
			$book_date_end = date("Y-m-d", strtotime($date));
		}
		if(isset($_GET['job_date_start'])){
			$date = str_replace('/', '-', $_GET['job_date_start'] );
			$job_date_start = date("Y-m-d", strtotime($date));
		}
		if(isset($_GET['job_date_end'])){
			$date = str_replace('/', '-', $_GET['job_date_end'] );
			$job_date_end = date("Y-m-d", strtotime($date));
		}
		if(isset($_GET['msg_date_start'])){
			$date = str_replace('/', '-', $_GET['msg_date_start'] );
			$msg_date_start = date("Y-m-d", strtotime($date));
		}
		if(isset($_GET['msg_date_end'])){
			$date = str_replace('/', '-', $_GET['msg_date_end'] );
			$msg_date_end = date("Y-m-d", strtotime($date));
		}
		if(isset($_GET['status_chosen'])){
			if($_GET['status_chosen']!='all'){
				$where .= " and b.status='".$_GET['status_chosen']."' ";
			}
			$status_chosen =$_GET['status_chosen'];
			// echo $where;
			// exit();
		}
		if(isset($_GET['customers'])){
			if($_GET['customers']!='all'){
				$where .= ' and b.customer_id='.$_GET['customers'].' ';
			}
		}
		if(!empty($job_date_start) and !empty($job_date_end)){
			// $where .= " and (j.received_at >= '".$job_date_start." 0:00:00' AND j.received_at <= '".$job_date_end." 23:59:59')" ;
		}
		if(!empty($msg_date_start) and !empty($msg_date_end)) {
			// $where .= " and (c.created_at >= '".$msg_date_start." 0:00:00' AND c.created_at <= '".$msg_date_end." 23:59:59')" ;
		}
		$connotes_data_join = DB::table('bookings')
		// ->leftJoin('connotes','bookings.id','=','connotes.booking_id')
		// ->leftJoin('jobs','connotes.id','=','jobs.connote_id')
		// ->select('*', 'bookings.key as bookings_key','bookings.created_at as bookings_created_at')
		->where('bookings.created_at','>=',$book_date_start)
		// ->where('bookings.created_at','<=',$book_date_end)
		->offset(0)
		->limit(300)
		->get();
		// dd($connotes_data_join);

		$sql = "SELECT 
				b.`key` as booking_no,
				b.`created_at` as date_start,
				b.`customer_key` as company_key,
				b.`customer_name` as company_sender,
				b.`district` as company_district,
				b.`province` as company_province,
				b.`postcode` as company_postcode,
				b.`person` as company_person,
				b.`address` as company_address,
				b.`express` as service_level,
				b.`comment` as comment,
				c.service as service_type,
				'' as size_box,
				c.updated_at as time_pickup,
				'' as service_comment,
				'' as customer_ref,
				c.created_at as time_send,
				c.consignee_name as consignee_name,
				c.consignee_phone as consignee_phone,
				c.consignee_company as consignee_company,
				c.consignee_address as consignee_address,
				c.consignee_district as consignee_districe,
				c.consignee_province as consignee_province,
				c.consignee_postcode as consignee_postcode,
				c.`key` as connote_key,
				c.`status` as connotes_status,
				j.receiver_name as sign_name,
				j.received_at as time_send,
				IF(c.service='return','',j.received_at) as time_return,
				j.notes as return_comment,
				c.details as detail
			FROM bookings b 
			LEFT JOIN connotes c ON c.booking_id = b.id 
			LEFT JOIN jobs j ON j.connote_id = c.id
			WHERE (b.created_at >= '".$book_date_start." 0:00:00' AND b.created_at <= '".$book_date_end." 23:59:59') ".$where;
		$result_report = DB::select($sql);
		$result_report_data = array();
		$i=1;
		// var_dump($sql);exit();
		foreach($result_report as $val){

			$sender = json_decode($val->company_person,true);
			// var_dump($sender);exit();
			$detail = json_decode($val->detail,true);
			// var_dump($detail);exit();
			$note 	= json_decode($val->return_comment,true);
			$result_report_data[] = array(
				'no'				=> $i++,
				'booking_no'		=> $val->booking_no,
				'date_start'		=> $val->date_start,
				'company_key'		=> $val->company_key,
				'company_sender'	=> $val->company_sender,
				'company_district'	=> $val->company_district,
				'company_province'	=> $val->company_province,
				'company_postcode'	=> $val->company_postcode,
				'company_person'	=> $sender['name'],
				'company_address'	=> $val->company_address,
				'company_phone'		=> $sender['mobile'],
				'service_level'		=> $val->service_level,
				'service_type'		=> $val->service_type,
				'size_box'			=> '',//$detail['pieces'][0],
				'time_pickup'		=> $val->time_pickup,
				'service_comment'	=> $note['note_that'],
				'customer_ref'		=> $detail['csn']['customer_ref'],
				'time_send'			=> $val->time_send,
				'consignee_name'	=> $val->consignee_name,
				'consignee_phone'	=> $val->consignee_phone,
				'consignee_company'	=> $val->consignee_company,
				'consignee_address'	=> $val->consignee_address,
				'consignee_districe'=> $val->consignee_districe,
				'consignee_province'=> $val->consignee_province,
				'consignee_postcode'=> $val->consignee_postcode,
				'connote_key'		=> $val->connote_key,
				'connotes_status'	=> $val->connotes_status,
				'sign_name'			=> $val->sign_name,
				'time_send'			=> $val->time_send,
				'time_return'		=> $val->time_return,
				'return_comment'	=> $val->return_comment,
				'comment'	=> $val->comment
			);
		}
		$result_report_data = (object)$result_report_data;
		// var_dump($result_report_data);exit();
		// $object = new stdClass();
		// var_dump($result_report_data);
		// exit();
		// var_dump($result_book);
		// echo "a";
		// exit();
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

		$bkg_start = helperDateFormatPickerToDB($bkg_start).' 0:00:00';
		$bkg_end = helperDateFormatPickerToDB($bkg_end).' 23:59:59';
		$msg_start = helperDateFormatPickerToDB($msg_start).' 0:00:00';
		$msg_end = helperDateFormatPickerToDB($msg_end).' 23:59:59';
		$job_start = helperDateFormatPickerToDB($job_start).' 0:00:00';
		$job_end = helperDateFormatPickerToDB($job_end).' 23:59:59';

		// $models = Connote::leftJoin('bookings', 'connotes.booking_id', '=', 'bookings.id')
		// 	->where()
  //           ->get();
  //       var_dump($models);
  //       exit();

		// // SELECTED FIELDS.
		// $adminObject = new UserAdmin;
		// $adminModel = $adminObject->getModel();
		// $adminNote = !empty($adminModel) ? helperJsonDecodeToArray($adminModel->notes) : [];

		// if (empty($adminNote['report_field'])) {

		// 	$adminNote['report_field'] = $this->getKeyFieldAll();
		// 	$adminModel->notes = json_encode($adminNote);
		// 	$adminModel->save();
		// }

		// $selected_field = $adminNote['report_field'];

		// // GET AND BUIKD DATA.
		// $models 	 = $this->getData($bkg_start, $bkg_end, $msg_start, $msg_end, $job_start, $job_end, $status, $ctm_chosen);
		// $report_data = $this->mapForReport($models);
		// $chart_data  = $this->buildChartData($bkg_start, $bkg_end, $step, $models);

		// $fields = $this->getFields();
		$bkg_start = helperDateFormatDBToPicker($bkg_start);
		$bkg_end = helperDateFormatDBToPicker($bkg_end);

		$jobRepo = new JobRepository;

		// // STATUS.
		$statusAll = array_merge(['pending' => 'ยังไม่เปิดงานส่ง'], $jobRepo->statusAll());
		// // CUSTOMER COMPANY.
		// $ctmAll = Customer::orderBy('name')->get();
		$ctmAll = Customer::orderBy('name')->get();
		// var_dump($ctmAll);exit();




		$customers = (isset($_GET['customers'])?$_GET['customers']:'');

		$result_customer = DB::select('SELECT * FROM customers');
		$customer_array = array();
		foreach($result_customer as $val){
			$customer_array[] = array(
				'id' => $val->id,
				'name' => $val->name,
				'selected' => ($val->id==$customers?'selected':'')
			);
		}
		$data = compact('connotes_data_join','result_report_data','bkg_start', 'bkg_end', 'msg_start', 'msg_end', 'job_start', 'job_end', 'step', 'statusAll', 'status', 'ctmAll', 'ctm_chosen','mdir','customers','status_chosen','customer_array');
		// $data = array();
		return $this->view('admin.pages.report.index_new', $data);
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