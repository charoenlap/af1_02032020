<?php namespace App\Services\Users;

use App\Services\Employees\Employee;
use App\Services\Positions\PositionRepository;

class UserAdmin {

	protected $emp_key 		= '';
	protected $nickname 	= '';
	protected $firstname	= '';
	protected $lastname		= '';
	public $session 		= 'mw-airforceone-admin-online';

	public function __construct()
	{
		if (\Session::has($this->session)) {
			$model = \Session::get($this->session);
			$this->emp_key 		= $model->emp_key;
			$this->nickname 	= $model->nickname;
			$this->firstname 	= $model->firstname;
			$this->lastname 	= $model->lastname;
		}
	}

	public function isLogged()
	{
		return \Session::has($this->session);
	}
	public function setUp($model)
	{
        \Session::put($this->session, $model);
	}
	public function getEmpKey()
	{
		return $this->emp_key;
	}
	public function getNickname()
	{
		return $this->nickname;
	}
	public function getFullname()
	{
		return $this->nickname.' '.$this->firstname.' '.$this->lastname;
	}

	public function getModel()
	{
		return Employee::where('emp_key', $this->emp_key)->first();
	}

	public function getPermission()
	{
		$admin = $this->getModel();
		if (empty($admin)) return [];

		$position = $admin->position;

		$positionRepo = new PositionRepository;
		return $positionRepo->access($position->key);
	}

	public function canSeeTheseMenu($menu = [])
	{
		$accesses = $this->getPermission();

		foreach ($accesses as $access) {

			if (strpos($access, '.get')) {

				if (in_array(str_replace('.get', '', $access), $menu)) return true;
			}
		}

		return false;
	}
}