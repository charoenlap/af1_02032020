<?php namespace App\Services\Points;

use App\Services\Points\Point;

class PointRepository {

	public function getEmpty()
	{
		$model = new Point;
		$model->id = 0;
		$model->province = 'กรุงเทพมหานคร';
		return $model;
	}
	public function getByID($id)
	{
		return Point::where('customer_id', $id)->get();
	}

	public function getByCustomerId($customer_id)
	{
		return Point::where('customer_id', $customer_id)->get();
	}

	public function getByIdPoint($id)
	{
		return Point::where('id', $id)->first();
	}

	public function createData($request, $customer_id, $customer_key)
	{
		$model = new Point;

		$model->person = $request->person;
		$model->name = $request->name;
		$model->key = 'xxx';
		$model->customer_id = $customer_id;
		$model->customer_key = $customer_key;
		$model->address = $request->address;
		$model->district = $request->district;
		$model->province = $request->province;
		$model->postcode = $request->postcode;
		$model->mobile = $request->mobile;
		$model->save();

		$model->key = sprintf('D%05d', $model->id);
		$model->save();
		return $model;
	}

	public function updateData($point_id, $request)
	{
		$model = Point::find($point_id);

		if (empty($model)) return false;

		$model->person = $request->person;
		$model->name = $request->name;
		$model->address = $request->address;
		$model->district = $request->district;
		$model->province = $request->province;
		$model->postcode = $request->postcode;
		$model->mobile = $request->mobile;
		$model->save();

		return $model;
	}

	public function createFromExcel($ctmModel, $data)
	{
		$results = [];

		foreach ($data as $row) {

			if (empty($row['A']) || empty($row['B'])) continue;

			$model = new Point;
			$model->customer_id = $ctmModel->id;
			$model->customer_key = $ctmModel->key;
			$model->key = 'xxx';
			$model->person = $row['A'];
			$model->name = $row['B'];
			$model->address = !empty($row['C']) ? $row['C'] : null;
			$model->district = !empty($row['D']) ? $row['D'] : null;
			$model->province = !empty($row['E']) ? $row['E'] : null;
			$model->postcode = !empty($row['F']) ? $row['F'] : null;
			$model->mobile = !empty($row['G']) ? $row['G'] : null;
			$model->save();

			$model->key = sprintf('D%04d', $model->id);
			$model->save();
			$results[] = $model;
		}

		return $results;
	}
}