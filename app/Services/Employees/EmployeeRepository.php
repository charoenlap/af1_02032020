<?php namespace App\Services\Employees;

use App\Services\Employees\Employee;
use App\Services\Positions\PositionRepository;

class EmployeeRepository {

	public function getMsgAll()
	{
		$posRepo = new PositionRepository;
		$msg_id = $posRepo->getMsgId();
		return Employee::where('position_id', $msg_id)->get();
	}

	public function getEmpty()
	{
		$model = new Employee;
		$model->id = 0;
		$model->position_id = 1;
		return $model;
	}
	public function getByID($id)
	{
		return Employee::find($id);
	}
	public function getByEmpKey($emp_key)
	{
		return Employee::where('emp_key', $emp_key)->with('position')->first();
	}
	public function getByEmpKeyAndPassword($emp_key, $pwd)
	{
		return Employee::where('emp_key', $emp_key)
				->where('password', $pwd)
				->first();
	}
	public function getByEmpKeyPasswordAndIdCard($emp_key, $pwd, $id_card)
	{
		return Employee::where('emp_key', $emp_key)
				->where('password', $pwd)
				->where('id_card', $id_card)
				->first();
	}
	public function getActiveAll($paginate = 0)
	{
		return ($paginate > 0)
		 	? Employee::where('status', 'active')->paginate($paginate)
		 	: Employee::where('status', 'active')->get();
	}
	public function getBySearch($searchs)
	{
		$models = Employee::where('status', 'active');

		if (isset($searchs['emp_key'])) {
			$models = $models->where('emp_key', 'LIKE', '%'.$searchs['emp_key'].'%');
		}

		if (isset($searchs['name'])) {

			$models = $models->where(function($q) use ($searchs) {
				$q->where('nickname', 'LIKE', '%'.$searchs['name'].'%');
				$q->orWhere('firstname', 'LIKE', '%'.$searchs['name'].'%');
				$q->orWhere('lastname', 'LIKE', '%'.$searchs['name'].'%');
			});
		}

		if (isset($searchs['position'])) {
			$models = $models->where('position_id', $searchs['position']);
		}

		return $models->with('position')->paginate(10);
	}

	public function createData($inputs)
	{
		if (Employee::where('emp_key', $inputs['emp_key'])->count() > 0) return false;

		$model = new Employee;
		$model->emp_key = isset($inputs['emp_key']) ? $inputs['emp_key'] : '0';
		$model->nickname = isset($inputs['nickname']) ? $inputs['nickname'] : '';
		$model->title = isset($inputs['title']) ? $inputs['title'] : '';
		$model->firstname = isset($inputs['firstname']) ? $inputs['firstname'] : '';
		$model->lastname = isset($inputs['lastname']) ? $inputs['lastname'] : '';
		$model->phone = isset($inputs['phone']) ? $inputs['phone'] : '';
		$model->address = isset($inputs['address']) ? $inputs['address'] : '';
		$model->id_card = isset($inputs['id_card']) ? $inputs['id_card'] : '';
		$model->position_id = isset($inputs['position_id']) ? $inputs['position_id'] : 5;
		$model->save();

		return $model;
	}

	public function updateData($emp_id, $inputs)
	{
		if (Employee::where('id', '<>', $emp_id)->where('emp_key', $inputs['emp_key'])->count() > 0) return false;

		$model = Employee::where('id', $emp_id)->with('position')->first();

		if (empty($model)) return false;

		$model->emp_key = isset($inputs['emp_key']) ? $inputs['emp_key'] : '0';
		$model->nickname = isset($inputs['nickname']) ? $inputs['nickname'] : '';
		$model->title = isset($inputs['title']) ? $inputs['title'] : '';
		$model->firstname = isset($inputs['firstname']) ? $inputs['firstname'] : '';
		$model->lastname = isset($inputs['lastname']) ? $inputs['lastname'] : '';
		$model->phone = isset($inputs['phone']) ? $inputs['phone'] : '';
		$model->address = isset($inputs['address']) ? $inputs['address'] : '';
		$model->id_card = isset($inputs['id_card']) ? $inputs['id_card'] : '';
		$model->position_id = isset($inputs['position_id']) ? $inputs['position_id'] : 5;
		$model->save();

		return $model;
	}

	public function buildAttr($model)
	{
		$model->created_label = helperThaiFormat($model->created_at, true).' เวลา '.helperDateFormat($model->created_at, 'H:s น.');
		$model->updated_label = helperThaiFormat($model->updated_at, true).' เวลา '.helperDateFormat($model->updated_at, 'H:s น.');
		
		return $model;
	}
}