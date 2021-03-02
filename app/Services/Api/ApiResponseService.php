<?php namespace App\Services\Api;

use App\Services\HashID\HashPassCode;

class ApiResponseService {

	public function __construct()
	{
		$this->hashPassCode = new HashPassCode;
	}

	public function checkPermissionBasic($request)
	{
		if (!$request->has('token')) return false;
		if ($request->input('token') !== config('credential.api_passcode')) return false;

		return true;
	}

	public function checkPermission($request)
	{
		if (!$request->has('token')) {
			return false;
		}

		return $this->hashPassCode->check($request->input('token'));
	}

	public function checkValidation($request, $fields)
	{
		$invalids = [];

		foreach ($fields as $field => $attr) {

			if (isset($attr['req']) && isset($attr['type'])) {

				if ((empty($request->input($field)) || !$request->has($field))
					&& $attr['req'] == 'required' && $attr['type'] !== 'variable') {
					$invalids[] = $field;
				}
			}
		}

		return $invalids;
	}

	public function success($data = [])
	{
		return response()->json([
			'code' 		=> '200',
			'status'	=> 'success',
			'message'	=> '200 OK',
			'data' 		=> $data
		]);
	}

	public function permissionFail()
	{
		return response()->json([
			'code'		=> '401',
			'status' 	=> 'fail',
			'message' 	=> 'Permission Denied',
			'data'		=> [['error_display' => 'ไม่อนุญาตให้ใช้งาน']],
		]);
	}

	public function validationFail($fields = [])
	{
		if (is_array($fields)) $fields = implode(',', $fields);

		return response()->json([
			'code'		=> '422',
			'status' 	=> 'fail',
			'message' 	=> 'Validation Failed',
			'data'		=> [['error_display' => 'ข้อมูลไม่ครบ '.$fields]],
		]);
	}

	public function dataNotFound()
	{
		return response()->json([
			'code'		=> '204',
			'status' 	=> 'fail',
			'message' 	=> 'Data not found',
			'data'		=> [['error_display' => 'ไม่พบข้อมูล']],
		]);
	}

	public function foundException()
	{
		return response()->json([
			'code'		=> '404',
			'status' 	=> 'fail',
			'message' 	=> 'Found Exception.',
			'data'		=> [['error_display' => 'เกิดปัญหาบางอย่าง']],
		]);
	}

	public function customFail($msg)
	{
		if (is_array($msg)) $msg = implode(',', $msg);

		return response()->json([
			'code'		=> '400',
			'status' 	=> 'fail',
			'message' 	=> 'Bad Request',
			'data'		=> [['error_display' => $msg]],
		]);
	}
}