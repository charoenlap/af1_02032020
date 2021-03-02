<?php namespace App\Services\Auths;

use App\Services\Users\UserAdmin;
use App\Services\Employees\EmployeeRepository;
use App\Services\Positions\PositionRepository;

class AdminAuth {

    public static function attempt($emp_id, $password)
    {
        $userAdmin = new UserAdmin;
        $empRepo = new EmployeeRepository;
        $adminModel = $empRepo->getByEmpKeyAndPassword($emp_id, $password);
        // var_dump($adminModel);
        if (empty($adminModel)) return false;

        // Map to userAdmin.
        $model = [];
        $model['emp_key']   = $adminModel->emp_key;
        $model['nickname']  = $adminModel->nickname;
        $model['firstname'] = $adminModel->firstname;
        $model['lastname']  = $adminModel->lastname;

        // Save User to Session.
        $userAdmin->setUp((object) $model);

        //Return.
        return $adminModel;
    }

    public static function check()
    {
        $result = false;

        $userAdmin = new UserAdmin;

        if (\Session::has($userAdmin->session)) {
        	$result = true;
        }

        return $result;
	}

    public static function clear()
    {
        $userAdmin = new UserAdmin;
        \Session::forget($userAdmin->session);

        $positionRepo = new PositionRepository;
        \Session::forget($positionRepo->session);

        helperClearLang();
    }

}