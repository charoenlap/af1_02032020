<?php namespace App\Services\HashID;

use Hashids\Hashids;

class HashTransactionID {

	protected $alphabet	= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	protected $hash_len	= 32;
	protected $hash_key = 'Transaction ID in SAKuNI MEWiTTY';

	public function hashEncode($passcode)
	{
		$hashids = new Hashids($this->hash_key, $this->hash_len, $this->alphabet);
		return $hashids->encode($passcode);
	}

	public function hashDecode($passcode)
	{
		$hashids 	= new Hashids($this->hash_key, $this->hash_len, $this->alphabet);
		$decoded_id = $hashids->decode($passcode);

		return isset($decoded_id[0]) ? $decoded_id[0] : '';
	}
}