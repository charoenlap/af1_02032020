<?php namespace App\Services\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobSend extends Model
{
	use SoftDeletes;
    protected $table = 'job_sends';

    public function connote()
    {
    	return $this->belongsTo('App\Services\Connotes\Connote');
    }

    public function msg()
    {
    	return $this->hasOne('App\Services\Employees\Employee', 'emp_key', 'msg_key');
    }

}