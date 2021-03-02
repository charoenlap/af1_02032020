<?php namespace App\Services\Cars;

use App\Services\Cars\Car;

class CarRepository {

	public function getAll()
	{
		return Car::all();
	}
}