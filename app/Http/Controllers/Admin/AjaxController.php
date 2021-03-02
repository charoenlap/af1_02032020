<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;

class AjaxController extends Controller {

	public function ajaxCenter(Request $request)
	{
		if ($request->ajax()){

			switch ($request->input('method')) {

				case 'ChangeLang':

					helperSetLang($request->input('lang'));
					break;

				case 'ChangeMenubarFold':

					return helperSetMenubar($request->input('fold'));
					break;

				default:
					# code...
					break;
			}
		}
	}
}