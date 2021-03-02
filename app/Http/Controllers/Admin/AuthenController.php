<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Auths\AdminAuth;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\Logs\LogRepository;
use Illuminate\Support\Facades\DB;
class AuthenController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getLogin()
    {
        if (AdminAuth::check()) {
            return \Redirect::route('admin.booking.index.get');
        }

        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        if ($request->ajax()) {
            // echo "test";exit();
            if (!$request->has('emp_id') || !$request->has('password')) {
                return helperReturnErrorFormRequest('invalid', 'Employee ID and Password not empty.');
            }
            // echo $emp_id;
            $emp_id = $request->input('emp_id');
            $password = $request->input('password');
            $admin = AdminAuth::attempt($emp_id, $password);

            // $users = DB::select('select * from employees limit 0,1');
            // echo $users;
            // if ($admin) {

            //     // LOG.
            //     // $logRepo = new LogRepository;
            //     // $logRepo->put(helperRouteModule(), ['route' => helperRoute(), 'url' => \Request::fullUrl()]);
            //     return \URL::route('admin.dashboard.index.get');
            // }

            // return helperReturnErrorFormRequest('invalid', 'Invalid username or password');

            /*
                // Log on Login Fail.
                $log = new LogRepository;
                $log->put('login_fail', ['ip' => \Request::ip(), 'username' => $username]);
            */
        }
    }

    public function getLogout()
    {
        // LOG.
        // $logRepo = new LogRepository;
        // $logRepo->put('logout', ['route' => helperRoute(), 'url' => \Request::fullUrl()]);

        AdminAuth::clear();
        return \Redirect::route('admin.authen.login.get');
    }


}
