<?php namespace App\Services\Logs;

use Illuminate\Database\Eloquent\Model;

class LogConnote extends Model {

	protected $table = 'log_connotes';

	public function connote()
	{
		return $this->belongsTo('App\Services\Connotes\Connote');
	}
}