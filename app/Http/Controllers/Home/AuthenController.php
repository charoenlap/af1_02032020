<?php namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Services\Auths\CustomerAuth;
use App\Http\Controllers\Home\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\Logs\LogRepository;

class AuthenController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    private function getMetaTag()
    {
        return (object)[
            'site_name'     => 'AirForceOneExpress',
            'app_id'        => env('FB_APP_ID'),
            'type'          => 'website',
            'title'         => 'AirForce One Express บริษัท แอร์ฟอร์ส วัน เอ็กเพลส จำกัด',
            'description'   => 'AirForceOne Express บริษัท แอร์ฟอร์ส วัน เอ็กเพลส จำกัดบริการรับส่งเอกสารและพัสดุภายในประเทศ',
            'url'           => urlBase(),
            'image'         => urlHomeImage().'/landing_page.png',
        ];
    }

    public function getLogin()
    {
        if (CustomerAuth::check()) {
            return \Redirect::route('home.tracking.index.get');
        }

        // Clear Session.
        CustomerAuth::clear();
        $meta = $this->getMetaTag();
        return view('home.login', compact('meta'));
    }

    public function postLogin(Request $request)
    {
        if (!$request->has('email') || !$request->has('password')) {
            return helperReturnErrorFormRequest('invalid', 'invalid email or password');
        }
        $email   = $request->input('email');
        $password   = $request->input('password');

        if ($user = CustomerAuth::attempt($email, $password)) {
            return \URL::route('home.profile.index.get');
        }

        return helperReturnErrorFormRequest('invalid', 'Invalid email or password');
    }

    public function getLogout()
    {
        CustomerAuth::clear();
        return \Redirect::route('home.main.index.get');
    }

}
