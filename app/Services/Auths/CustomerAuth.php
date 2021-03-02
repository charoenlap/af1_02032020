<?php namespace App\Services\Auths;

use App\Services\Users\UserCustomer;
use App\Services\Customers\CustomerRepository;

class CustomerAuth {

    public static function attempt($email, $password)
    {
        $customerRepo   = new CustomerRepository;
        $customerModel = $customerRepo->getByEmailAndPassword($email, $password);
        if (empty($customerModel)) return false;

        // Map to userCustomer.
        $model['key'] = $customerModel->key;
        $model['name'] = $customerModel->name;
        $model['email'] = $customerModel->email;
        $model = (object)$model;

        // Save User to Session.
        $userCustomer = new UserCustomer;
        $userCustomer->setUp($model);

        //Return.
        return $model;
    }

    public static function check()
    {
        $userCustomer = new UserCustomer;
        return \Session::has($userCustomer->session);
	}

    public static function clear()
    {
        $userCustomer = new UserCustomer;
        \Session::forget($userCustomer->session);
    }

}