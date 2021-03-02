<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Users\UserAdmin;
use App\Services\Excels\ExcelRepository;
use App\Services\Reports\ReportRepository;
use App\Services\Bookings\Booking;
use App\Services\Bookings\BookingRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Jobs\Job;

class ReportMessengerController extends Controller
{
	private $sheet_dir = 'reports';

	public static $requiredPermissions = [
		'getIndex'			=> 'report_messenger.get',
		'postGetData'		=> 'report_messenger.post',
		'postAdjustReport'	=> 'report_messenger.post',
		'postGenerateExcel'	=> 'report_messenger.post',
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

		if (empty($adminNote['report_messenger_field'])) {

			$adminNote['report_messenger_field'] = $this->getKeyFieldAll();
			$adminModel->notes = json_encode($adminNote);
			$adminModel->save();
		}

		$selected_field = $adminNote['report_messenger_field'];

		// GET AND BUIKD DATA.
		$models 	 = $this->getData($start_date, $end_date, $msg);
		$report_data = $this->mapForReport($models);
		$chart_data  = $this->buildChartData($start_date, $end_date, $step, $models);

		$fields = $this->getFields();
		$start_date = helperDateFormatDBToPicker($start_date);
		$end_date = helperDateFormatDBToPicker($end_date);

		$empRepo = new EmployeeRepository;
		$msgModels = $empRepo->getMsgAll();

		$data = compact('start_date', 'end_date', 'step', 'fields', 'report_data', 'chart_data', 'selected_field', 'msgModels', 'msg');
		return $this->view('admin.pages.report.messenger', $data);
	}

	public function postGetData(Request $request)
	{
		if ($request->ajax()) {

			$start_date = $request->input('start_date') ? $request->input('start_date') : date('d/m/Y');
			$end_date = $request->input('end_date') ? $request->input('end_date') : date('d/m/Y');
			$msg = $request->input('msg');
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

			$adminNote['report_messenger_field'] = $request->has('selected_fields') ? $request->input('selected_fields') : "";
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
			$msg = $request->input('msg');

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
				$sheet_name = 'Messenger_'.$start_date.'_'.$end_date.'';
				$sheet_title = 'Messenger';

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

		$bookingModels = Booking::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date);

		if ($msg != 'all') $bookingModels = $bookingModels->where('id', $msg);
		$bookingModels = $bookingModels->get();

		$empRepo = new EmployeeRepository;
		$empModels = $empRepo->getMsgAll();

		foreach ($bookingModels as $bookingModel) {

			foreach ($empModels as $empModel) {

				if ($bookingModel->msg_key == $empModel->emp_key) {
					$results[$empModel->emp_key] = empty($results[$empModel->emp_key]) ? 1 : $results[$empModel->emp_key]+1;
				}
			}
		}

		foreach ($empModels as $empModel) {

			$empModel->bookingCount = 0;

			foreach ($results as $msg_key => $bookingCount) {

				if ($empModel->emp_key == $msg_key) {
					$empModel->bookingCount = $bookingCount;
				}
			}
		}

		$jobs = [];

		$jobModels = Job::where('received_at', '>=', $start_date)->where('received_at', '<', $end_date)->get();

		foreach ($jobModels as $jobModel) {

			foreach ($empModels as $empModel) {

				if ($jobModel->msg_key == $empModel->emp_key) {
					$jobs[$empModel->emp_key] = empty($jobs[$empModel->emp_key]) ? 1 : $jobs[$empModel->emp_key]+1;
				}
			}
		}

		foreach ($empModels as $empModel) {

			$empModel->connoteCount = 0;

			foreach ($jobs as $msg_key => $connoteCount) {

				if ($empModel->emp_key == $msg_key) {
					$empModel->connoteCount = $connoteCount;
				}
			}
		}

		return $empModels;
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