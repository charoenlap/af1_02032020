<?php namespace App\Services\Customers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model {

	use SoftDeletes;
	protected $table = 'customers';

	public function points()
	{
		return $this->hasMany('App\Services\Points\Point');
	}

	public function bookings()
	{
		return $this->hasMany('App\Services\Bookings\Booking');
	}
}
