<?php namespace App\Services\ThaiLocations;

class ThaiLocation {

	public function get()
	{
		if (file_exists(__DIR__.'/data.json')) $data = file_get_contents(__DIR__.'/data.json');
		if (!empty($data)) return json_decode($data);
		return null;
	}

	public function getProvince()
	{
		$provinces = [];
		$data = $this->get();
		if (empty($data)) return null;
		foreach ($data as $i => $d) $provinces[$d->province] = $i;
		if (isset($provinces['กรุงเทพมหานคร'])) unset($provinces['กรุงเทพมหานคร']);
		ksort($provinces);
		$provinces = array_flip($provinces);
		$provinces = array_values($provinces);
		array_unshift($provinces, 'กรุงเทพมหานคร');
		return $provinces;
	}
}