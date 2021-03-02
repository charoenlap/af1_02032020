<?php namespace App\Services\Reports;

class ReportRepository {

	private function data()
	{
		return (object)['label_d' => '', 'label_h' => '', 'start' => '', 'end' => '', 'count' => 0, 'data' => []];
	}

	public function genSetTime($start_date, $end_date = '', $duration = 'd')
	{
		if ($end_date == '') $end_date = date('Y-m-d H:i:s');

		switch ($duration) {
			case 'h':
				$step = 60*60;
				break;
			case 'w':
				$step = 7*24*60*60;
				break;
			default:
				$step = 24*60*60;
				break;
		}

		$sets = [];

		while (strtotime($start_date) <= strtotime($end_date)) {

				$data = $this->data();
				$data->label_d 	= date('d M', strtotime($start_date));
				$data->label_h 	= date('H:i', strtotime($start_date));
                $data->start 	= $start_date;
                $start_date 	= date('Y-m-d H:i:s', strtotime($start_date)+($step));
                $data->end 		= $start_date;

                $sets[] = $data;
		}

		return $sets;
	}

	public function map($sets, $models, $date_field = 'created_at')
	{
		foreach ($sets as $set) {

			foreach ($models as $model)
			{
				$datetime = date('Y-m-d H:i:s', strtotime($model->{$date_field}));
				if ($datetime > $set->start && $datetime <= $set->end) {

					$set->count++;
					$set->data[] = $model;
				}
			}
		}

		return $sets;
	}
}