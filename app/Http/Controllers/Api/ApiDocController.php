<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiBaseController;

class ApiDocController extends ApiBaseController {

	protected $registors;

	public function __construct()
	{
		$this->registors = [
			'App\Http\Controllers\Api\ApiControllers\ApiUserRegisterController',
			'App\Http\Controllers\Api\ApiControllers\ApiUserLoginController',
			'App\Http\Controllers\Api\ApiControllers\ApiUserProfileController',
			'App\Http\Controllers\Api\ApiControllers\ApiUserHistoryController',
			'App\Http\Controllers\Api\ApiControllers\ApiNewFeedController',
			'App\Http\Controllers\Api\ApiControllers\ApiBookingListController',
			'App\Http\Controllers\Api\ApiControllers\ApiBookingCodDetailController',
			'App\Http\Controllers\Api\ApiControllers\ApiBookingDetailController',
			'App\Http\Controllers\Api\ApiControllers\ApiBookingUpdateController',
			'App\Http\Controllers\Api\ApiControllers\ApiJobListController',
			'App\Http\Controllers\Api\ApiControllers\ApiJobDetailController',
			'App\Http\Controllers\Api\ApiControllers\ApiJobCreateController',
			'App\Http\Controllers\Api\ApiControllers\ApiJobUpdateController',
		];

	}

	public function index()
	{
		$models = [];

		foreach ($this->registors as $registor) {

			$models[] = $this->getClass($registor);
		}

		return view('api.index', compact('models'));
	}

	private function getClass($c)
	{
		$class 		= new $c();
		$results	= ['class' => $class->className()];

		if (method_exists($class, 'index'))
		{
			$result	= [
						'verb'	  	  => 'GET',
						'path' 		  => $class->path('index'),
		 				'description' => $class->description('index')
		 			];

			$result = array_merge($result, $class->indexModel());
			$results['method'][] = $result;
		}

		if (method_exists($class, 'store'))
		{
			$result	= [
						'verb'	  	  => 'POST',
						'path' 		  => $class->path('store'),
		 				'description' => $class->description('store')
		 			];

			$result = array_merge($result, $class->storeModel());
			$results['method'][] = $result;
		}

		if (method_exists($class, 'update'))
		{
			$result	= [
						'verb'	  	  => 'PUT',
						'path' 		  => $class->path('update'),
		 				'description' => $class->description('update')
		 			];

			$result = array_merge($result, $class->updateModel());
			$results['method'][] = $result;
		}

		if (method_exists($class, 'destroy'))
		{
			$result	= [
						'verb'	  	  => 'DELETE',
						'path' 		  => $class->path('destroy'),
		 				'description' => $class->description('destroy')
		 			];

			$result = array_merge($result, $class->destroyModel());
			$results['method'][] = $result;
		}

		return $results;
	}
}