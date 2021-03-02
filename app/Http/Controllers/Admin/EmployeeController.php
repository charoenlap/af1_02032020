<?php namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Bookings\BookingRepository;
use App\Services\Employees\EmployeeRepository;
use App\Services\Positions\PositionRepository;
use App\Services\Medias\MediaRepository;

class EmployeeController extends Controller {

	public static $requiredPermissions = [
		'getIndex' 		=> 'employee.get',
		'getUpdate' 	=> 'employee.get',
		'postUpdate' 	=> 'employee.post',
		'postUpdatePwd' => 'employee.post',
		'postRemove' 	=> 'employee.post',
	];

	public function getIndex(Request $request)
	{
		// GET ALL POSITIONS.
		$posRepo = new PositionRepository;
		$posModels = $posRepo->getAll();

		// KEEP SEARCH.
		$searchs = [];
		if ($request->has('emp_key')) $searchs['emp_key'] = $request->input('emp_key');
		if ($request->has('name')) $searchs['name'] = $request->input('name');
		if ($request->has('position')) $searchs['position'] = $request->input('position');

		if ($request->has('position')) {
			foreach ($posModels as $posModel) {
				if ($posModel->id == $request->input('position')) {
					$searchs['pos_label'] = $posModel->label;
				}
			}
		}

		// GET EMPLOYEES.
		$empRepo = new EmployeeRepository;
		$empModels = $empRepo->getBySearch($searchs, 1);

		unset($searchs['position']);

		$data = compact('empModels', 'posModels', 'searchs');
		return $this->view('admin.pages.employee.index', $data);
	}

	public function getUpdate($emp_id)
	{
		// GET ALL POSITIONS.
		$posRepo = new PositionRepository;
		$posModels = $posRepo->getAll();

		// GET EMPLOYEE.
		$empRepo = new EmployeeRepository;
		$empModel = $empRepo->getByID($emp_id);
		if (empty($empModel)) $empModel = $empRepo->getEmpty();

		$empModel->avatar_url = !empty($empModel->avatar)
		 						? urlContent().'/'.$empModel->avatar
		 						: urlAdminImage().'/avatar.jpg';

		$data = compact('empModel', 'posModels');
		return $this->view('admin.pages.employee.update', $data);
	}

	public function postUpdate($emp_id, Request $request)
	{
		if ($request->ajax()) {

			$model = helperJsonDecodeToArray($request->input('empModel'));

			$this->checkFormRequest($model);

			$empRepo = new EmployeeRepository;

			// CASE CREATE.
			if ($emp_id == 0) {

				if ($empModel = $empRepo->createData($model)) {

					if (!empty($request->file('avatar'))) {

						$fileObject = $request->file('avatar');
						$mediaRepo = new MediaRepository;
						$empModel->avatar = $mediaRepo->uploadFile($fileObject);
						$empModel->save();
					}

					helperResponsePutSuccess('Create Complete');
					return ['status' => 'success', 'url' => \URL::route('admin.employee.index.get')];
				}

			// CASE UPDATE.
			} else {

				if ($empModel = $empRepo->updateData($emp_id, $model)) {

					if (!empty($request->file('avatar'))) {

						$fileObject = $request->file('avatar');
						$mediaRepo = new MediaRepository;
						$empModel->avatar = $mediaRepo->removeFile(helperDirContent().'/'.$empModel->avatar);
						$empModel->avatar = $mediaRepo->uploadFile($fileObject);
						$empModel->save();
					}

					helperResponsePutSuccess('Update Complete');
					return ['status' => 'success', 'url' => \URL::route('admin.employee.update.get', $empModel->id)];
				}

			}

			helperResponsePutFail('Update Fail');
			return ['status' => 'fail'];
		}
	}

	public function postUpdatePwd($emp_id, Request $request)
	{
		if ($request->ajax()) {

			$this->empValidationPwd($request);

			$empRepo = new EmployeeRepository;
			$empModel = $empRepo->getByID($emp_id);

			if (empty($empModel)) {
				helperResponsePutFail('Update Fail');
				return ['status' => 'fail'];
			}

			$empModel->password = $request->input('confirm_pwd');
			$empModel->save();

			helperResponsePutSuccess('Update Password Complete');
			return ['status' => 'success', 'url' => \URL::route('admin.employee.update.get', $empModel->id)];
		}
	}

	public function postRemove($emp_id, Request $request)
	{
		if ($request->ajax()) {

			$empRepo = new EmployeeRepository;
			$empModel = $empRepo->getByID($emp_id);

			if (empty($empModel)) {
				helperResponsePutFail('Remove Fail');
				return ['status' => 'fail'];
			}

			$empModel->delete();
			helperResponsePutSuccess('Remove Complete');
			return ['status' => 'success'];
		}
	}

	private function checkFormRequest($input)
	{
		$request = $input;

        $rules = [
			'emp_key'		=> 'required',
			'nickname'		=> 'required',
			'title' 		=> 'required',
			'firstname' 	=> 'required',
			'lastname' 		=> 'required',
			'phone' 		=> 'required',
			'address' 		=> 'required',
			'id_card' 		=> 'required|size:13',
		];

		$messages = [
			'emp_key.required' 		=> 'กรุณาใส่รหัสพนักงาน',
			'nickname.required' 	=> 'กรุณาใส่ชื่อเล่น',
			'title.required' 		=> 'กรุณาใส่คำนำหน้า',
			'firstname.required' 	=> 'กรุณาใส่ชื่อจริง',
			'lastname.required' 	=> 'กรุณาใส่นามสกุล',
			'phone.required' 		=> 'กรุณาใส่หมายเลขโทรศัพท์',
			'address.required' 		=> 'กรุณาใส่ที่อยู่',
			'id_card.required' 		=> 'กรุณาใส่เลขบัตรประจำตัวประชาชน',
			'id_card.size'			=> 'บัตรประจำตัวประชาชนไม่ครบ 13 หลัก',
		];

		$validator = Validator::make($request, $rules, $messages);

        if ($validator->fails()) {
    		helperReturnErrorFormRequestArray($validator->errors()->messages());
        }

	}

	private function empValidationPwd($request)
	{
		if (!$request->has('new_pwd') || $request->input('new_pwd') == '')
		return helperReturnErrorFormRequest('new_pwd', $message = 'กรุณาใส่รหัสผ่านใหม่');

		if (!preg_match("/^[0-9]*$/", $request->input('new_pwd')))
		return helperReturnErrorFormRequest('new_pwd', $message = 'กรุณาใส่รหัสผ่านใหม่เป็นตัวเลข');

		if (strlen($request->input('new_pwd')) !== 4)
		return helperReturnErrorFormRequest('new_pwd', $message = 'กรุณาใส่รหัสผ่านใหม่ให้ครบ 4 หลัก');

		if (!$request->has('confirm_pwd') || $request->input('confirm_pwd') == '')
		return helperReturnErrorFormRequest('confirm_pwd', $message = 'กรุณาใส่รหัสผ่านยืนยัน');

		if ($request->input('new_pwd') !== $request->input('confirm_pwd'))
		return helperReturnErrorFormRequest('confirm_pwd', $message = 'รหัสผ่านไม่ตรงกัน');

		return true;
	}

}