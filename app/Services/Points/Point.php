<?php namespace App\Services\Points;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point extends Model
{
	use SoftDeletes;
    protected $table = 'points';

    public function customer()
    {
    	return $this->belongsTo('App\Services\Customers\Customer');
    }
}