<?php namespace App\Services\Employees;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
	use SoftDeletes;
    protected $table = 'employees';

    public function position()
    {
    	return $this->belongsTo('App\Services\Positions\Position');
    }
}