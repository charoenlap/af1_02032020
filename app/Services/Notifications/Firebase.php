<?php namespace app\Services\Notifications;

class Firebase {

	public function schmaInput($token, $body)
	{
		return [
 			'to' => $token,
 			'priority' => 'high',
 			'notification' => [
 				'title'	=> 'Air Forceone Express',
 				'body' 	=> $body,
 				'icon'	=> 'myicon',
 			]
 		];
	}

	public function post($parameters)
	{
		$curl = curl_init(env('FIREBASE_URL'));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
		    'Content-Type: application/json',
		    'Authorization: key='.env('FIREBASE_KEY')
		]);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
}