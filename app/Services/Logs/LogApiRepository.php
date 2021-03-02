<?php namespace App\Services\Logs;

use App\Services\Logs\LogApi;

class LogApiRepository {

	public $excepts = ['ajax_center', 'register'];

	public function success($class, $method)
	{
		$this->put($class, ['method' => $method, 'status' => 'success']);
	}

	public function fail($class, $method, $msg)
	{
		$this->put($class, ['method' => $method, 'status' => 'fail', 'msg' => $msg]);
	}

	public function put($key = '', $value = [])
	{
		$log = new LogApi;
		$log->ip_address 	= \Request::ip();
		$log->key 			= ($key != '') ? $key : NULL;
		$log->value 		= !empty($value) ? json_encode($value) : NULL;
		$log->action_date 	= date('Y-m-d');
		$log->action_time 	= date('H:i:s');
		$log->save();

		return $log;
	}

}