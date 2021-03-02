<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Positions\PositionRepository;
use App\Http\Controllers\Admin\Controller;

class PositionController extends Controller
{
	public static $requiredPermissions = [
		'getIndex' 		=> 'position.get',
		'postUpdate' 	=> 'position.post',
	];

	public function getIndex()
	{
		$posRepo = new PositionRepository;
		$posModels = $posRepo->getAll();

		foreach ($posModels as $posModel) {
			$posModel->access = $posRepo->buildAccessForView($posModel->key);
			$posModel->except = helperJsonDecodeToArray($posModel->except);
		}

		$permissions = $posRepo->getConfigForView();
		$data = compact('posModels', 'permissions');
		return $this->view('admin.pages.position.index', $data);
	}

	public function postUpdate(Request $request)
	{
		if ($request->ajax()) {

			$posRepo = new PositionRepository;
			$posModel = $posRepo->getById($request->input('position_id'));

			if (empty($posModel)) return ['status' => 'fail'];

			$posModel->label = !empty($request->input('label')) ? $request->input('label') : $posModel->key;
			$posModel->except = !empty($request->input('except')) ? json_encode($request->input('except')) : null;
			$posModel->save();

			helperResponsePutSuccess('Update Complete');
			return ['status' => 'success', 'url' => \URL::route('admin.position.index.get')];
		}
	}
}

