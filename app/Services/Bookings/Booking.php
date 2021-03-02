<?php namespace App\Services\Bookings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
	use SoftDeletes;
    protected $table = 'bookings';

    public function connotes()
    {
    	return $this->hasMany('App\Services\Connotes\Connote');
    }

    public function customer()
    {
        return $this->belongsTo('App\Services\Customers\Customer');
    }

    public function car()
    {
    	return $this->belongsTo('App\Services\Casr\Car');
    }

    public function logs()
    {
        return $this->hasMany('App\Services\Logs\LogBooking');
    }

    public function msg()
    {
        return $this->belongsTo('App\Services\Employees\Employee', 'msg_key', 'emp_key');
    }
}