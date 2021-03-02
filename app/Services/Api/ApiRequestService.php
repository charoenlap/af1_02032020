<?php namespace App\Services\Api;


class ApiRequestService {

	public function get($url, $parameters)
	{
		$url = $url.'?';

		foreach ($parameters as $key => $value) {
			$url .= '&'.$key.'='.$value;
		}

        $curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	public function post($url, $parameters)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	public function put($url, $parameters)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	public function delete($url, $parameters)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
}