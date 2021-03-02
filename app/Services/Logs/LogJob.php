<?php namespace App\Services\Logs;

use Illuminate\Database\Eloquent\Model;

class LogJob extends Model {

	protected $table = 'log_jobs';

	public function job()
	{
		return $this->belongsTo('App\Services\Jobs\Job');
	}
}