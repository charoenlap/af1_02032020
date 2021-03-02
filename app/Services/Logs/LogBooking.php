<?php namespace App\Services\Logs;

use Illuminate\Database\Eloquent\Model;

class LogBooking extends Model {

	protected $table = 'log_bookings';

	public function booking()
	{
		return $this->belongsTo('App\Services\Bookings\Booking');
	}
}