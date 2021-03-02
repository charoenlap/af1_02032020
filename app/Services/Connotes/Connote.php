<?php namespace App\Services\Connotes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Connote extends Model
{
	use SoftDeletes;
    protected $table = 'connotes';

    public function booking()
    {
    	return $this->belongsTo('App\Services\Bookings\Booking');
    }

    public function job()
    {
    	return $this->hasOne('App\Services\Jobs\Job');
    }

    public function logs()
    {
        return $this->hasMany('App\Services\Logs\LogConnote');
    }

    public function job_send()
    {
        return $this->hasOne('App\Services\Jobs\JobSend');
    }
}