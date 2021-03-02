<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Users\UserAdmin;
use App\Services\Excels\ExcelRepository;
use App\Services\Reports\ReportRepository;
use App\Services\Customers\Customer;

class ReportCustomerController extends Controller
{
	private $sheet_dir = 'report_customer';

	public static $requiredPermissions = [
		'getIndex'			=> 'report_customer.get',
		'postGetData'		=> 'report_customer.post',
		'postAdjustReport'	=> 'report_customer.post',
		'postGenerateExcel'	=> 'report_customer.post',
	];

	public function getIndex()
	{
		// DATE.
		$start_date = date('d/m/Y');
		$end_date 	= date('d/m/Y');
		$step = 'h';
		$msg = 'all';

		// SELECTED FIELDS.
		$adminObject = new UserAdmin;
		$adminModel = $adminObject->getModel();
		$adminNote = !empty($adminModel) ? helperJsonDecodeToArray($adminModel->notes) : [];

		if (empty($adminNote['report_customer_field'])) {

			$adminNote['report_customer_field'] = $this->getKeyFieldAll();
			$adminModel->notes = json_encode($adminNote);
			$adminModel->save();
		}

		$selected_field = $adminNote['report_customer_field'];

		// GET AND BUIKD DATA.
		$models 	 = $this->getData($start_date, $end_date, $msg);
		$report_data = $this->mapForReport($models);
		$chart_data  = $this->buildChartData($start_date, $end_date, $step, $models);

		$fields = $this->getFields();
		$start_date = helperDateFormatDBToPicker($start_date);
		$end_date = helperDateFormatDBToPicker($end_date);

		$data = compact('start_date', 'end_date', 'step', 'fields', 'report_data', 'chart_data', 'selected_field');
		return $this->view('admin.pages.report.customer', $data);
	}

	public function postGetData(Request $request)
	{
		if ($request->ajax()) {

			$start_date = $request->input('start_date') ? $request->input('start_date') : date('d/m/Y');
			$end_date = $request->input('end_date') ? $request->input('end_date') : date('d/m/Y');
			$msg = $request->input('msg') ? $request->input('msg') : date('all');
			$step = $request->input('step');

			// REPORT DATA.
			$models = $this->getData($start_date, $end_date, $msg);
			$report_data = $this->mapForReport($models);
			$chart_data  = $this->buildChartData($start_date, $end_date, $step, $models);

			$start_date = helperDateFormatDBToPicker($start_date);
			$end_date 	= helperDateFormatDBToPicker($end_date);
			return compact('start_date', 'end_date', 'report_data', 'chart_data');
		}
	}

	public function postAdjustReport(Request $request)
	{
		if ($request->ajax()) {

			$adminObject = new UserAdmin;
			$adminModel = $adminObject->getModel();

			$adminNote['report_customer_field'] = $request->has('selected_fields') ? $request->input('selected_fields') : "";
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
			$msg = $request->has('msg') ? $request->input('msg') : 'all';

			// REPORT DATA.
			$models = $this->getData($start_date, $end_date, $msg);
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
				$sheet_name = 'Customer_'.$start_date.'_'.$end_date.'';
				$sheet_title = 'Customer';

				$excelRepo = new ExcelRepository;
				$excelRepo->removeAllFileInTempExcelForlder($this->sheet_dir);
				$excelRepo->writeExcel($excel_data, $sheet_name, $this->sheet_dir, $sheet_title);
				return ['status' => 'success', 'base_url' => urlBase(), 'sheet_path' => $this->sheet_dir.'/'.$sheet_name.'.xlsx'];
			}

			return ['status' => 'fail'];
		}
	}

	private function getData($start_date, $end_date, $msg)
	{
		$start_date = helperDateFormatPickerToDB($start_date).' 0:00:00';
		$end_date 	= helperDateFormatPickerToDB($end_date).(($end_date != date('d/m/Y')) ? ' 23:59:59' : ' '.date('H:i:s'));

		$results = [];

		$models = Customer::with(['bookings' => function($query) use ($start_date, $end_date) {
		 	$query->where('created_at', '>=', $start_date);
		 	$query->where('created_at', '<', $end_date);
		}]);

		$models = $models->get();

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
		 		'emp_key' 			=> 'Messenger ID',
				'nickname'			=> 'Name',
				'title'				=> 'Title',
				'firstname'			=> 'Firstname',
				'lastname'			=> 'Lastname',
				'phone'				=> 'Phone Number',
				'address' 			=> 'Address',
				'id_card'			=> 'Card ID',
				'bookingCount' 		=> 'Booking Count',
				'connoteCount' 		=> 'Connote Count',
			],
			'th' =>	[
				'emp_key' 			=> 'Messenger ID',
				'nickname'			=> 'Name',
				'title'				=> 'Title',
				'firstname'			=> 'Firstname',
				'lastname'			=> 'Lastname',
				'phone'				=> 'Phone Number',
				'address' 			=> 'Address',
				'id_card'			=> 'Card ID',
				'bookingCount' 		=> 'Booking Count',
				'connoteCount' 		=> 'Connote Count',
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

			$results[$no]['emp_key'] 		= $model->emp_key;
			$results[$no]['nickname'] 		= $model->nickname;
			$results[$no]['title'] 			= $model->title;
			$results[$no]['firstname'] 		= $model->firstname;
			$results[$no]['lastname']		= $model->lastname;
			$results[$no]['phone'] 			= $model->phone;
			$results[$no]['address'] 		= $model->address;
			$results[$no]['id_card'] 		= $model->id_card;
			$results[$no]['bookingCount'] 	= $model->bookingCount;
			$results[$no]['connoteCount'] 	= $model->connoteCount;

			$no++;
		}

		return $results;
	}
}