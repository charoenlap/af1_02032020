<?php namespace App\Services\Positions;

use App\Services\Positions\Position;

class PositionRepository {

	public $admin 	= 'admin';
	public $cs 		= 'cs';
	public $headsup = 'headsup';
	public $sup 	= 'sup';
	public $msg 	= 'msg';
	public $id_admin 	= '1';
	public $id_cs 		= '2';
	public $id_headsup 	= '3';
	public $id_sup 		= '4';
	public $id_msg 		= '5';

	public $session = 'airforce-online-role';

	public function all()
	{
		return [
			$this->admin,
			$this->cs,
			$this->headsup,
			$this->sup,
			$this->msg,
		];
	}

	public function idCanSeeIsSupForApp()
	{
		return [$this->id_headsup, $this->id_sup];
	}
	public function getIdByKey($key)
	{
		return $this->{'id_'.$key};
	}
	public function getMsgId()
	{
		return $this->getIdByKey($this->msg);
	}
	public function getByKey($key)
	{
		return Position::where('key', $key)->first();
	}
	public function getById($id)
	{
		return Position::find($id);
	}
	public function getAll()
	{
		return Position::all();
	}

	public function getConfigForView()
	{
		$results = [];

		foreach (config('permissions') as $permission) {

			list($class, $method) = explode('.', $permission);
			$results[$class][] = ['method' => $method, 'label' => ($method == 'get') ? 'Read' : 'Write', 'value' => $permission];
		}

		return $results;
	}

	public function except($key)
	{
		$posModel = (\Session::has($this->session)) ? \Session::get($this->session) : Position::where('key', $key)->first();
		\Session::put($this->session, $posModel);

		return helperJsonDecodeToArray($posModel->except);
	}

	public function access($role)
	{
		if (!in_array($role, $this->all())) return [];

		return array_diff(config('permissions'), $this->except($role));
	}

	public function buildAccessForView($role)
	{
		$result = [];
		$posModel = $this->getByKey($role);
		$access = array_diff(config('permissions'), helperJsonDecodeToArray($posModel->except));

		foreach ($access as $ac) {
			list($module, $method) = explode('.', $ac);
			$method = str_replace('get', '[READ]', str_replace('post', '[WRITE]', $method));
			$result[$module] = !empty($result[$module]) ? $result[$module].' '.$method : $method;
		}

		return $result;
	}
}