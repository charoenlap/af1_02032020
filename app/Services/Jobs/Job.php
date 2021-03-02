<?php namespace App\Services\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
	use SoftDeletes;
    protected $table = 'jobs';

    public function connote()
    {
    	return $this->belongsTo('App\Services\Connotes\Connote');
    }

    public function msg()
    {
    	return $this->hasOne('App\Services\Employees\Employee', 'emp_key', 'msg_key');
    }

    public function logs()
    {
        return $this->hasMany('App\Services\Logs\LogJob');
    }

}