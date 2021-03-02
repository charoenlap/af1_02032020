<?php namespace App\Http\Controllers\Customer;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Services\Users\UserCustomer;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function view($page, $data = [])
    {
        $userCustomer = new UserCustomer;
        $meta = $this->getMetaTag();
        $data['userCustomer'] = $userCustomer;
        return view('customer.layouts.template', compact('page', 'data', 'meta'));
    }

    private function getMetaTag()
    {
        return (object)[
            'site_name'     => 'AirForceOne',
            'app_id'        => env('FB_APP_ID'),
            'type'          => 'website',
            'title'         => 'AirForceOne บริษัท แอร์ฟอร์ส วัน เอ็กเพรส จำกัด',
            'description'   => 'AirForceOne บริษัท แอร์ฟอร์ส วัน เอ็กเพรส จำกัด',
            'url'           => urlBase(),
            'image'         => urlHomeImage().'/landing_page.png',
        ];
    }
}
