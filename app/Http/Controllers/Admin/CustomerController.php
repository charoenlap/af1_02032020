<?php namespace App\Http\Controllers\Admin;

use \Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Customers\CustomerRepository;
use App\Services\ThaiLocations\ThaiLocation;
use App\Services\Points\PointRepository;
use App\Services\Excels\ExcelRepository;

class CustomerController extends Controller {

	public static $requiredPermissions = [
		'getIndex' 		=> 'customer.get',
		'getUpdate' 	=> 'customer.get',
		'postUpdate' 	=> 'customer.post',
		'postUpdatePwd' 	=> 'customer.post',
		'getPoint' 			=> 'customer.get',
		'getPointUpdate' 	=> 'customer.get',
		'postPointUpdate' 	=> 'customer.post',
		'postPointRemove' 	=> 'customer.post',
		'getPointExcel' 		=> 'customer.get',
		'postPointReadExcel' 	=> 'customer.post',
		'postPointImportExcel' 	=> 'customer.post',
	];

	public function getIndex(Request $request)
	{
		// KEEP SEARCH.
		$searchs = [];
		if ($request->has('key')) $searchs['key'] = $request->input('key');
		if ($request->has('name')) $searchs['name'] = $request->input('name');

		// GET CUSTOMER.
		$ctmRepo = new CustomerRepository;
		$ctmModels = $ctmRepo->getSearch($searchs);

		$data = compact('ctmModels','searchs');
		return $this->view('admin.pages.customer.index', $data);
	}

	public function getUpdate($ctm_id)
	{
		// GET CUSTOMER.
		$ctmRepo = new CustomerRepository;
		$ctmModel = $ctmRepo->getByID($ctm_id);
		if (empty($ctmModel)) $ctmModel = $ctmRepo->getEmpty();
		$ctmModel->key = substr($ctmModel->key, 0, 3);

		$thaiLocation = new ThaiLocation;
		$provinces = $thaiLocation->getProvince();

		$data = compact('ctmModel', 'provinces');
		return $this->view('admin.pages.customer.update', $data);
	}

	public function postUpdate($ctm_id, Request $request)
	{
		if ($request->ajax()) {

			$this->checkFormRequestCustomer($request->input('ctmModel'));

			$input = $request->input('ctmModel');
			$ctmRepo = new CustomerRepository;

			// CASE CREATE.
			if ($input['id'] == 0) {

				$ctmModel = $ctmRepo->createData($input);
				helperResponsePutSuccess('Create Complete');
				return ['status' => 'success', 'url' => \URL::route('admin.customer.index.get')];

			// CASE UPDATE.
			} else {

				if ($ctmModel = $ctmRepo->updateData($input['id'], $input)) {

					helperResponsePutSuccess('Update Complete');
					return ['status' => 'success', 'url' => \URL::route('admin.customer.update.get', $ctmModel->id)];
				}

				return ['status' => 'fail'];
			}
		}
	}

	public function postUpdatePwd($ctm_id, Request $request)
	{
		if ($request->ajax()) {

			$this->customValidationPwd($request);

			$ctmRepo = new CustomerRepository;
			$ctmModel = $ctmRepo->getByID($ctm_id);

			if (empty($ctmModel)) {
				helperResponsePutFail('Update Fail');
				return ['status' => 'fail'];
			}

			$ctmModel->password = $request->input('confirm_pwd');
			$ctmModel->save();

			helperResponsePutSuccess('Update Password Complete');
			return ['status' => 'success', 'url' => \URL::route('admin.customer.update.get', $ctmModel->id)];
		}
	}
	public function postRemove($ctm_id, Request $request)
	{
		if ($request->ajax()) {

			$ctmRepo = new CustomerRepository;
			$ctmModel = $ctmRepo->getByID($ctm_id);

			if (empty($ctmModel)) {
				helperResponsePutFail('Remove Fail');
				return ['status' => 'fail'];
			}

			$ctmModel->delete();
			helperResponsePutSuccess('Remove Complete');
			return ['status' => 'success'];
		}
	}

	//POINT.
	public function getPoint($customer_id)
	{
		// GET POINT.
		$pointRepo = new PointRepository;
		$pointModels = $pointRepo->getByID($customer_id);

		foreach ($pointModels as $pointModel) {
			$notes = helperJsonDecodeToArray($pointModel->notes);
			$pointModel->customer_ref = !empty($notes['customer_ref']) ? $notes['customer_ref'] : '';
			$pointModel->update_url = \URL::route('admin.customer.point_update.get', [$customer_id, $pointModel->id]);
			$pointModel->remove_url = \URL::route('admin.customer.point_remove.post', [$customer_id, $pointModel->id]);
		}

		$thaiLocation = new ThaiLocation;
		$provinces = $thaiLocation->getProvince();

		$ctmRepo = new CustomerRepository;
		$ctmModel = $ctmRepo->getByID($customer_id);

		$data = compact('ctmModel', 'pointModels', 'provinces');
		return $this->view('admin.pages.customer.point', $data);
	}

	public function getPointUpdate($customer_id, $point_id)
	{
		// GET POINT.
		$pointRepo = new PointRepository;
		$pointModel = $pointRepo->getByIdPoint($point_id);
		if (empty($pointModel)) $pointModel = $pointRepo->getEmpty();

		$notes = helperJsonDecodeToArray($pointModel->notes);
		$pointModel->customer_ref = !empty($notes['customer_ref']) ? $notes['customer_ref'] : '';

		$thaiLocation = new ThaiLocation;
		$provinces = $thaiLocation->getProvince();

		$ctmRepo = new CustomerRepository;
		$ctmModel = $ctmRepo->getByID($customer_id);

		$data = compact('ctmModel','pointModel', 'provinces');
		return $this->view('admin.pages.customer.point_update', $data);
	}

	public function postPointUpdate($customer_id, $point_id, Request $request)
	{
		if ($request->ajax()) {

			$this->checkFormRequestPoint($request->all());

			$ctmRepo = new CustomerRepository;
			$ctmModel = $ctmRepo->getByID($customer_id);

			$pointRepo = new PointRepository;

			// CASE CREATE.
			if ($point_id == 0) {

				$pointModel = $pointRepo->createData($request, $customer_id, $ctmModel->key);
				helperResponsePutSuccess('Create Consignee Address Complete');
				return [
					'status' => 'success',
					'url' => \URL::route('admin.customer.point.get', $customer_id),
					'pointModel' => $pointModel,
				];

			// CASE UPDATE.
			} else {

				if ($pointModel = $pointRepo->updateData($point_id, $request)) {

					helperResponsePutSuccess('Update Complete');
					return [
						'status' => 'success',
						'url' => \URL::route('admin.customer.point_update.get', [$customer_id, $pointModel->id]),
						'pointModel' => $pointModel,
					];
				}

				return ['status' => 'fail'];
			}
		}
	}

	public function postPointRemove($customer_id, $point_id,Request $request)
	{
		if ($request->ajax()) {

			$pointRepo = new PointRepository;
			$pointModel = $pointRepo->getByIdPoint($point_id);

			if (empty($pointModel)) {
				helperResponsePutFail('Remove Fail');
				return ['status' => 'fail'];
			}

			$pointModel->delete();
			helperResponsePutSuccess('Remove Complete');
			return ['status' => 'success'];
		}
	}

	public function getPointExcel($ctm_id)
	{
		// GET CUSTOMER.
		$ctmRepo = new CustomerRepository;
		$ctmModel = $ctmRepo->getByID($ctm_id);
		if (empty($ctmModel)) return \Redirect::route('admin.customer.index.get');

		$data = compact('ctmModel');
		return $this->view('admin.pages.customer.point_excel', $data);
	}

	public function postPointReadExcel($ctm_id, Request $request)
	{
		if ($request->ajax()) {

			if (empty($request->file('excel'))) return helperReturnErrorFormRequest('invalid', 'Please choose excel file');
			$excelRepo = new ExcelRepository;
			$excelArray = $excelRepo->readExcel($request->file('excel'));

			$data = [];
			foreach ($excelArray as $r => $row) {
				if ($r <= 1) continue;
				if (empty($row['A']) || empty($row['B'])) continue;
				$data[] = $row;
			}

			return ['status' => 'success', 'data' => $data];
		}
	}

	public function postPointImportExcel($ctm_id, Request $request)
	{
		if ($request->ajax()) {

			$ctmRepo = new CustomerRepository;
			$ctmModel = $ctmRepo->getByID($ctm_id);
			if (empty($ctmModel)) return ['status' => 'fail'];

			$data = $request->input('pointData');
			if (empty($data)) return ['status' => 'fail'];

			$pointRepo = new PointRepository;
			$models = $pointRepo->createFromExcel($ctmModel, $data);

			helperResponsePutSuccess('Import Employee Complete');
			return ['status' => 'success', 'url' => \URL::route('admin.customer.point.get', $ctmModel->id)];
		}
	}

	private function checkFormRequestPoint($input)
	{
		$rules = [
			'person'		=> 'required',
			'name'			=> 'required',
			'address'		=> 'required',
			'district'		=> 'required',
			'province'		=> 'required',
			'postcode'		=> 'required|numeric|digits:5',
			'mobile'			=> 'required',
		];

		$messages = [
			'person.required'	=> 'กรุณาใส่ชื่อผู้ติดต่อ',
			'name.required'		=> 'กรุณาใส่ชื่อบริษัท',
			'mobile.required'	=> 'กรุณาใส่เบอร์ติดต่อ',
			'address.required'	=> 'กรุณาใส่ที่อยู่',
			'district.required'	=> 'กรุณาใส่ชื่ออำเภอ',
			'province.required'	=> 'กรุณาใส่ให้ครบ',
			'postcode.required'	=> 'กรุณาใส่รหัสไปรษณีย์',
			'postcode.numeric' 	=> 'กรุณาใส่รหัสไปรษณีย์เป็นตัวเลข',
			'postcode.digits' 	=> 'กรุณาใส่รหัสไปรษณีย์เป็นตัวเลข 5 หลัก',
		];

		$validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
    		helperReturnErrorFormRequestArray($validator->errors()->messages());
        }
	}

	private function checkFormRequestCustomer($input)
	{
		$rules = [
			'key'			=> 'required|size:3',
			'person'		=> 'required',
			'mobile'		=> 'required',
			'email'			=> 'required|email',
			'name'			=> 'required',
			'address'		=> 'required',
			'district'		=> 'required',
			'province'		=> 'required',
			'postcode'		=> 'required|numeric|digits:5',
		];

		$messages = [
			'key.required'		=> 'กรุณาใส่รหัสลูกค้า',
			'key.digits'		=> 'กรุณาใส่รหัสลูกค้า 3 หลัก',
			'person.required'	=> 'กรุณาใส่ชื่อผู้ติดต่อ',
			'mobile.required'	=> 'กรุณาใส่เบอร์ติดต่อ',
			'email.required'	=> 'กรุณาใส่ Email ติดต่อ',
			'email.email'		=> 'กรุณาใส่ Email ให้ถูกต้อง',
			'name.required'		=> 'กรุณาใส่ชื่อบริษัท',
			'address.required'	=> 'กรุณาใส่ที่อยู่',
			'district.required'	=> 'กรุณาใส่ชื่ออำเภอ',
			'province.required'	=> 'กรุณาใส่ให้ครบ',
			'postcode.required'	=> 'กรุณาใส่รหัสไปรษณีย์',
			'postcode.numeric' 	=> 'กรุณาใส่รหัสไปรษณีย์เป็นตัวเลข',
			'postcode.digits' 	=> 'กรุณาใส่รหัสไปรษณีย์เป็นตัวเลข 5 หลัก',
		];

		$validator = Validator::make($input, $rules, $messages);
		if ($validator->fails()) {
    		helperReturnErrorFormRequestArray($validator->errors()->messages());
        }
	}

	private function customValidationPwd($request)
	{
		if (!$request->has('new_pwd') || $request->input('new_pwd') == '')
		return helperReturnErrorFormRequest('new_pwd', 'กรุณาใส่รหัสผ่านใหม่');

		if (!$request->has('confirm_pwd') || $request->input('confirm_pwd') == '')
		return helperReturnErrorFormRequest('confirm_pwd', 'กรุณาใส่รหัสผ่านยืนยัน');

		if ($request->input('new_pwd') !== $request->input('confirm_pwd'))
		return helperReturnErrorFormRequest('confirm_pwd', 'รหัสผ่านไม่ตรงกัน');

		return true;
	}
}