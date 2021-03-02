<?php namespace App\Services\Logs;

use App\Services\Logs\LogConnote;
use App\Services\Users\UserAdmin;

class LogConnoteRepository {

	public function put($connoteModel, $username = '', $notes = '')
	{
		if (empty($username)) {
			$userAdmin = new UserAdmin;
			$username = (!empty($userAdmin)) ? $userAdmin->getNickname() : 'customer';
		}

		$log = new LogConnote;
		$log->connote_id 	 = $connoteModel->id;
		$log->status 		 = $connoteModel->status;
		$log->action_by 	 = $username;
		$log->notes 		 = $notes;
		$log->save();

		return $log;
	}

}