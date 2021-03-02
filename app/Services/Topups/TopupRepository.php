<?php namespace App\Services\Topups;

class TopupRepository {

	public $fifteen_min = 'fifteen_min';
	public $two_point = 'two_point';
	public $label_fifteen_min = 'ใช้เวลาส่งเกิน 15 นาที';
	public $label_two_point = 'จุดส่งเกิน 2 จุด';

	public function getForDropdown()
	{
		$results[] = ['value' => 'none', 'label' => 'ไม่มี'];
		$results[] = ['value' => $this->fifteen_min, 'label' => $this->label_fifteen_min];
		$results[] = ['value' => $this->two_point, 'label' => $this->label_two_point];
		return $results;
	}
}