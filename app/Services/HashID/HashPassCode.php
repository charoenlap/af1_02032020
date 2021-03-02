<?php namespace App\Services\HashID;

use Hashids\Hashids;
use App\Services\HashID\HashRandom;

class HashPassCode {

	protected $alphabet	= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	protected $hash_len	= 16;
	protected $hash_key = 'Passcode for AirForceOne by Mewitty';

	private function hashEncode($passcode)
	{
		$hashids = new Hashids($this->hash_key, $this->hash_len, $this->alphabet);
		return $hashids->encode($passcode);
	}

	private function hashDecode($passcode)
	{
		$hashids 	= new Hashids($this->hash_key, $this->hash_len, $this->alphabet);
		$decoded_id = $hashids->decode($passcode);

		return isset($decoded_id[0]) ? $decoded_id[0] : '';
	}

	protected $n;
	protected $c = 6;
	protected $l = 9;

	private function num()
	{
		return date('N');
	}

	public function gen()
	{
		$pscode = '';

		$d_pscode = $this->wrapDaySaltCode($type = 'd');
		$d_hash = $this->hashEncode($d_pscode);

		$m_pscode = $this->wrapDaySaltCode($type = 'm');
		$m_hash = $this->hashEncode($m_pscode);

		$y_pscode = $this->wrapDaySaltCode($type = 'y');
		$y_hash = $this->hashEncode($y_pscode);

		$h_pscode = $this->wrapDaySaltCode($type = 'H');
		$h_hash = $this->hashEncode($h_pscode);

		$i_pscode = $this->wrapDaySaltCode($type = 'i');
		$i_hash = $this->hashEncode($i_pscode);

		$hashRand = new HashRandom;
		$salt0 = $hashRand->hashEncode(rand(100, 999));
		$salt1 = $hashRand->hashEncode(rand(100, 999));

		return $salt0.$d_hash.$m_hash.$y_hash.$h_hash.$i_hash.$salt1;
	}

	public function check($hash_pscode)
	{
		if (empty($hash_pscode)) return false;

		$d_hash = substr($hash_pscode, 8, 16);
		$d_code = $this->hashDecode($d_hash);
		$d = $this->unwrapDaySaltCode($d_code);

		$m_hash = substr($hash_pscode, 24, 16);
		$m_code = $this->hashDecode($m_hash);
		$m = $this->unwrapDaySaltCode($m_code);

		$y_hash = substr($hash_pscode, 40, 16);
		$y_code = $this->hashDecode($y_hash);
		$y = $this->unwrapDaySaltCode($y_code);

		$h_hash = substr($hash_pscode, 56, 16);
		$h_code = $this->hashDecode($h_hash);
		$h = $this->unwrapDaySaltCode($h_code);

		$i_hash = substr($hash_pscode, 72, 16);
		$i_code = $this->hashDecode($i_hash);
		$i = $this->unwrapDaySaltCode($i_code);

		$date0 = date('Y-m-d H:i:s', strtotime($m.'/'.$d.'/'.$y.' '.$h.':'.$i));
		$diffObj = $this->getObjectDiffDate($date0, date('Y-m-d H:i:s'));

		if ($diffObj->y > 0 || $diffObj->m > 0 || $diffObj->d > 0 || $diffObj->h > 1) {
			return false;
		}

		// if ($diffObj->i > 3) {
		// 	return false;
		// }

		return true;
	}

	private function getObjectDiffDate($d0, $d1)
	{
		$date0 = new \DateTime($d0);
	 	$date1 = new \DateTime($d1);

	 	return $date0->diff($date1);
	}

	private function wrapDaySaltCode($type = 'd')
	{
		$d_pscode = range(0, 4);
		list($i0, $i1) = ($this->getRandValue(0, 4, 2));

		$v0 = substr(date($type), 0, 1);
		$v1 = substr(date($type), 1, 1);

		foreach ($d_pscode as $k => $v) {
			if ($k == $i0) $d_pscode[$k] = $v0;
			elseif ($k == $i1) $d_pscode[$k] = $v1;
			else $d_pscode[$k] = rand(0, 9);
		}
		array_unshift($d_pscode, $i0+1);
		array_push($d_pscode, $i1+1);

		return implode('', $d_pscode);
	}

	private function unwrapDaySaltCode($code)
	{
		$i0 = (int)substr($code, 0, 1);
		$i1 = (int)substr($code, -1);
		return substr($code, $i0, 1).substr($code, $i1, 1);
	}

	private function getRandValue($min = 0, $max = 9, $no = 1)
	{
		if ($min > $max) return false;

		$r = range($min, $max);

		for ($i = 0; $i < 100; $i++) {
			$ir = rand(0, $max-$min);
			$rt = $r[$ir];
			$r[$ir] = $r[($ir+1)%($max-$min+1)];
			$r[($ir+1)%($max-$min+1)] = $rt;
		}

		return array_slice($r, 0, $no);
	}

}