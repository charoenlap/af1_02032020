<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Services\News\News;

class DashboardController extends Controller
{
	public function getIndex()
	{
		$data = [];
		return $this->view('admin.pages.dashboard.index', $data);
	}
}