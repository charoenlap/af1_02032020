<?php namespace App\Services\Customers;

use App\Services\Customers\Customer;

class CustomerRepository {

	public function getEmpty()
	{
		$model = new Customer;
		$model->id = 0;
		$model->province = 'กรุงเทพมหานคร';
		return $model;
	}
	public function getByID($id)
	{
		return Customer::where('id', $id)->with('points')->first();
	}
	public function getByKey($key)
	{
		return Customer::where('key', $key)->first();
	}
	public function getByKeyWithPoint($key)
	{
		return Customer::where('key', $key)->with('points')->first();
	}
	public function getAll()
	{
		return Customer::with('points')->orderBy('name')->get();
	}
	public function getByEmailAndPassword($email, $pwd)
	{
		return Customer::where('email', $email)->where('password', $pwd)->first();
	}
	public function getSearch($searchs){

		$models = new Customer;

		if (isset($searchs['key'])) {
			$models = $models->where('key', 'like', '%'.$searchs['key'].'%');
		}
		if (isset($searchs['name'])) {
			$models = $models->where('name', 'like', '%'.$searchs['name'].'%');
		}

		return $models->paginate(10);
	}

	public function createData($input)
	{
		$model = new Customer;
		$model->name = isset($input['name']) ? $input['name'] : '';
		$model->email = isset($input['email']) ? $input['email'] : '';
		$model->address = isset($input['address']) ? $input['address'] : '';
		$model->district = isset($input['district']) ? $input['district'] : '';
		$model->province = isset($input['province']) ? $input['province'] : '';
		$model->postcode = isset($input['postcode']) ? $input['postcode'] : '';
		$model->person = isset($input['person']) ? $input['person'] : '';
		$model->mobile = isset($input['mobile']) ? $input['mobile'] : '';
		$model->office_tel = isset($input['office_tel']) ? $input['office_tel'] : '';
		$model->fax = isset($input['fax']) ? $input['fax'] : '';
		$model->key = '1';
		$model->save();

		$model->key = strtoupper($input['key']).sprintf('%05d', $model->id);
		$model->save();
		return $model;
	}

	public function updateData($ctm_id, $input)
	{
		$model = Customer::find($ctm_id);

		if (empty($model)) return false;

		$model->key = strtoupper($input['key']).sprintf('%05d', $model->id);
		$model->name = isset($input['name']) ? $input['name'] : '';
		$model->email = isset($input['email']) ? $input['email'] : '';
		$model->address = isset($input['address']) ? $input['address'] : '';
		$model->district = isset($input['district']) ? $input['district'] : '';
		$model->province = isset($input['province']) ? $input['province'] : '';
		$model->postcode = isset($input['postcode']) ? $input['postcode'] : '';
		$model->person = isset($input['person']) ? $input['person'] : '';
		$model->mobile = isset($input['mobile']) ? $input['mobile'] : '';
		$model->office_tel = isset($input['office_tel']) ? $input['office_tel'] : '';
		$model->fax = isset($input['fax']) ? $input['fax'] : '';
		$model->save();

		return $model;
	}
}