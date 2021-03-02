<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Users\UserAdmin;
use App\Services\Excels\ExcelRepository;
use App\Services\Reports\ReportRepository;
use App\Services\Bookings\Booking;
use App\Services\Bookings\BookingRepository;
use App\Services\Customers\Customer;

class ReportBookingController extends Controller
{
	private $sheet_dir = 'reports';

	public static $requiredPermissions = [
		'getIndex'			=> 'report_booking.get',
		'postGetData'		=> 'report_booking.post',
		'postAdjustReport'	=> 'report_booking.post',
		'postGenerateExcel'	=> 'report_booking.post',
	];

	public function getIndex()
	{
		// DATE.
		$start_date = date('d/m/Y');
		$end_date 	= date('d/m/Y');
		$step 		= 'h';
		$customer_id = 0;
		$status = 'all';

		// SELECTED FIELDS.
		$adminObject = new UserAdmin;
		$adminModel = $adminObject->getModel();
		$adminNote = !empty($adminModel) ? helperJsonDecodeToArray($adminModel->notes) : [];

		if (empty($adminNote['report_booking_field'])) {

			$adminNote['report_booking_field'] = $this->getKeyFieldAll();
			$adminModel->notes = json_encode($adminNote);
			$adminModel->save();
		}

		$selected_field = $adminNote['report_booking_field'];

		// GET AND BUIKD DATA.
		$models 	 = $this->getData($start_date, $end_date, $customer_id, $status);
		$report_data = $this->mapForReport($models);
		$chart_data  = $this->buildChartData($start_date, $end_date, $step, $models);

		$fields = $this->getFields();
		$start_date = helperDateFormatDBToPicker($start_date);
		$end_date = helperDateFormatDBToPicker($end_date);

		$customerModels = Customer::orderBy('key')->get();
		$bookingRepo = new BookingRepository;
		$statusAll = $bookingRepo->statusAll();
		$data = compact('start_date', 'end_date', 'step', 'fields', 'report_data', 'chart_data', 'selected_field', 'customerModels', 'customer_id', 'status', 'statusAll');
		return $this->view('admin.pages.report.booking', $data);
	}

	public function postGetData(Request $request)
	{
		if ($request->ajax()) {

			$start_date = $request->input('start_date') ? $request->input('start_date') : date('d/m/Y');
			$end_date = $request->input('end_date') ? $request->input('end_date') : date('d/m/Y');
			$step = $request->input('step');
			$customer_id = $request->input('customer_id');
			$status = $request->input('status');

			// REPORT DATA.
			$models = $this->getData($start_date, $end_date, $customer_id, $status);
			$report_data = $this->mapForReport($models);
			$chart_data  = $this->buildChartData($start_date, $end_date, $step, $models);

			$start_date = helperDateFormatDBToPicker($start_date);
			$end_date 	= helperDateFormatDBToPicker($end_date);
			return compact('start_date', 'end_date', 'report_data', 'chart_data', 'customer_id', 'status');
		}
	}

	public function postAdjustReport(Request $request)
	{
		if ($request->ajax()) {

			$adminObject = new UserAdmin;
			$adminModel = $adminObject->getModel();

			$adminNote['report_booking_field'] = $request->has('selected_fields') ? $request->input('selected_fields') : "";
			$adminModel->notes = json_encode($adminNote);
			$adminModel->save();

			return ['status' => 'success'];
		}
	}
	public function postGenerateExcel(Request $request)
	{
		if ($request->ajax()) {

			$start_date = $request->has('start_date') ? $request->input('start_date') : date('d/m/Y');
			$end_date = $request->has('end_date') ? $request->input('end_date') : date('d/m/Y');
			$customer_id = $request->input('customer_id');
			$status = $request->input('status');

			// REPORT DATA.
			$models = $this->getData($start_date, $end_date, $customer_id, $status);
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

				$start_date = helperDateFormatPickerToDB($start_date);
				$end_date 	= helperDateFormatPickerToDB($end_date);
				$sheet_name = 'Booking_'.$start_date.'_'.$end_date.'';
				$sheet_title = 'Booking';

				$excelRepo = new ExcelRepository;
				$excelRepo->removeAllFileInTempExcelForlder($this->sheet_dir);
				$excelRepo->writeExcel($excel_data, $sheet_name, $this->sheet_dir, $sheet_title);
				return ['status' => 'success', 'base_url' => urlBase(), 'sheet_path' => $this->sheet_dir.'/'.$sheet_name.'.xlsx'];
			}

			return ['status' => 'fail'];
		}
	}

	private function getData($start_date, $end_date, $customer_id, $status)
	{
		$start_date = helperDateFormatPickerToDB($start_date).' 0:00:00';
		$end_date 	= helperDateFormatPickerToDB($end_date).(($end_date != date('d/m/Y')) ? ' 23:59:59' : ' '.date('H:i:s'));

		$models = Booking::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date);

		if ($customer_id != 0) $models = $models->where('customer_id', $customer_id);
		if ($status != 'all') $models = $models->where('status', $status);

		$models = $models->get();

		$bookingRepo = new BookingRepository;
		foreach ($models as $model) {
			$model = $bookingRepo->buildAttr($model);
		}
		return $models;
	}

	private function buildChartData($start_date, $end_date, $step, $models)
	{
		$start = helperDateFormatPickerToDB($start_date).' 0:00:00';
		$end = helperDateFormatPickerToDB($end_date).(($end_date != date('d/m/Y')) ? ' 23:59:59' : ' '.date('H:i:s'));

		$reportRepo = new ReportRepository;
		$data_sets = $reportRepo->genSetTime($start, $end, $step);

		$results = $reportRepo->map($data_sets, $models, 'created_at');

		return $results;
	}

	private function getFields()
	{
	 	$fields = [

		 	'en' => [
		 		'key' 				=> 'Booking ID',
				'person_name'		=> 'Customer Name',
				'person_mobile'		=> 'Customer Phone',
				'company'			=> 'Customer Company',
				'address'			=> 'Customer Address',
				'service'			=> 'Service Type',
				'cod' 				=> 'COD',
				'cod_value'			=> 'COD Value',
				'express' 			=> 'Express',
				'created_at'		=> 'Created at',
				'cs_datetime'		=> 'CS Datetime',
				'complete_datetime'	=> 'Completed at',
				'status'			=> 'Booking Status',
			],
			'th' =>	[
				'key' 				=> 'Connote ID',
				'person_name'		=> 'Customer Name',
				'person_mobile'		=> 'Customer Phone',
				'company'			=> 'Customer Company',
				'address'			=> 'Customer Address',
				'service'			=> 'Service Type',
				'cod' 				=> 'COD',
				'cod_value'			=> 'COD Value',
				'express' 			=> 'Express',
				'created_at'		=> 'Created at',
				'cs_datetime'		=> 'CS Datetime',
				'complete_datetime'	=> 'Completed at',
				'status'			=> 'Booking Status',
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
		$results = [];
		$no = 0;
		foreach ($models as $key => $model) {

			$results[$no]['key'] = $model->key;
			$results[$no]['person_name'] = $model->person_name;
			$results[$no]['company'] = $model->customer_name;
			$results[$no]['person_mobile'] = $model->person_mobile;
			$results[$no]['address'] = $model->address;
			$results[$no]['service'] = $model->service_label;
			$results[$no]['cod'] = $model->cod_label;
			$results[$no]['cod_value'] = $model->cod_value;
			$results[$no]['express'] = $model->express_label;
			$results[$no]['created_at'] = helperDateFormat($model->created_at, 'Y-m-d H:i:s');
			$results[$no]['cs_datetime'] = helperDateFormat($model->updated_at, 'Y-m-d H:i:s');
			$results[$no]['complete_datetime'] = $model->get_datetime;
			$results[$no]['status'] = $model->status_label;

			$no++;
		}

		return $results;
	}
}