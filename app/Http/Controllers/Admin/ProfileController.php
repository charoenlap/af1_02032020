<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Users\UserAdmin;
use App\Services\Employees\EmployeeRepository;

class ProfileController extends Controller {

	public function getIndex()
	{
		$userEmployee = new UserAdmin;
		$employee_key = $userEmployee->getEmpKey();

		$empRepo = new EmployeeRepository;
		$empModel = $empRepo->getByEmpKey($employee_key);
		$data = compact('empModel');
		return $this->view('admin.pages.profile.index', $data);
	}

	public function postUpdate($emp_id, Request $request)
	{
		if ($request->ajax()) {

			$empRepo = new EmployeeRepository;
			$empModel = $empRepo->updateData($emp_id, $request->input('empModel'));

			return ['status' => 'success', 'model' => $empModel];
		}
	}
}