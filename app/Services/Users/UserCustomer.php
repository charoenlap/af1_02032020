<?php namespace App\Services\Users;

use App\Services\Customers\Customer;

class UserCustomer {

	protected $key 		= '';
	protected $name 	= '';
	protected $email	= '';
	public $session 	= 'mw-airforceone-customer-online';

	public function __construct()
	{
		if (\Session::has($this->session)) {
			$customerModel = \Session::get($this->session);
			$this->key 		= $customerModel->key;
			$this->name 	= $customerModel->name;
			$this->email 	= $customerModel->email;
		}
	}

	public function isLogged()
	{
		return \Session::has($this->session);
	}
	public function setUp($customerModel)
	{
        \Session::put($this->session, $customerModel);
	}
	public function getKey()
	{
		return $this->key;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getAvatar()
	{
		return $this->avatar;
	}
	public function getEmail()
	{
		return $this->email;
	}
}