<?php namespace App\Services\Logs;

use App\Services\BaseRepository;
use App\Services\Logs\Log;
use App\Services\Admins\AdminObject;

class LogRepository extends BaseRepository {

	public $excepts = ['ajax_center', 'report_remove'];

	public function put($key = '', $value = [], $user_id = '')
	{
		$adminObject = new AdminObject;

		$log = new Log;
		$log->user_id 		= ($user_id == '') ? $adminObject->getKey() : $user_id;
		$log->key 			= ($key != '') ? $key : NULL;
		$log->value 		= !empty($value) ? json_encode($value) : NULL;
		$log->action_date 	= date('Y-m-d');
		$log->action_time 	= date('H:i:s');
		// $log->save();

		return $log;
	}
}