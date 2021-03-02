<?php namespace App\Services\Logs;

use App\Services\Logs\LogJob;
use App\Services\Users\UserAdmin;

class LogJobRepository {

	public function put($jobModel, $username = '')
	{
		if (empty($username)) {
			$userAdmin = new UserAdmin;
			$username = $userAdmin->getNickname();
		}

		$log = new LogJob;
		$log->job_id 	= $jobModel->id;
		$log->status 	= $jobModel->status;
		$log->action_by = $username;
		$log->notes 	= $jobModel->flow;
		$log->save();

		return $log;
	}

}