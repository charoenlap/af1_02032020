<?php namespace App\Services\Logs;

use App\Services\Logs\LogBooking;
use App\Services\Users\UserAdmin;

class LogBookingRepository {

	public function put($bookingModel, $username = '', $notes = '')
	{
		if (empty($username)) {
			$userAdmin = new UserAdmin;
			$username = $userAdmin->getNickname();
		}

		$log = new LogBooking;
		$log->booking_id 	 = $bookingModel->id;
		$log->status 		 = $bookingModel->status;
		$log->action_by		 = $username;
		$log->notes 		 = $notes;
		$log->save();

		return $log;
	}

}