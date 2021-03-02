<?php

function helperDateFormat($datetime, $format = 'Y-m-d H:i:s')
{
	return date($format, strtotime($datetime));
}

function helperThaiFormat($datetime, $abbr = false)
{
	$result = '';
	$result .= date('d', strtotime($datetime)).' ';
	$result .= helperGetMonthThai(date('m', strtotime($datetime)), $abbr).' ';
	$result .= helperGetYearThai(date('Y', strtotime($datetime)), $abbr);

	return $result;
}

// YEAR.
function helperGetYear($m, $lang = 'en', $abbr = false)
{
	return ($lang == 'en') ? helperGetYearEng($m, $abbr) : helperGetYearThai($m, $abbr);
}
function helperGetYearThai($y, $abbr = false)
{
	return ($abbr) ? substr($y + 543, 2) : $y + 543;
}
function helperGetYearEng($y, $abbr = false)
{
	return ($abbr) ? substr($y, 2) : $y;
}
function helperGetYears($lang = 'en', $abbr = false, $amount = 10, $offset = 2)
{
	$years = [];
	for ($y = date('Y')-$offset; $y <= date('Y')+$amount-$offset; $y++) {
		$years[] = ($lang == 'en') ? helperGetYearEng($y, $abbr) : helperGetYearThai($y, $abbr);
	}

	return $years;
}

// MONTH.
function helperGetMonthAll($lang = 'en', $abbr = false)
{
	return ($lang == 'en') ? helperGetMonthEngAll($abbr) : helperGetMonthThaiAll($abbr);
}
function helperGetMonthEngAll($abbr = false)
{
	$months = [];
	for ($i = 1; $i <= 12; $i++) {
		$months[] = ($abbr) ? date('M', strtotime('2017-'.$i.'-01')) : date('F', strtotime('2017-'.$i.'-01'));
	}
	return $months;
}
function helperGetMonthThaiAll($abbr = false)
{
	$months = ['1' => 'มกราคม', '2' => 'กุมภาพันธ์', '3' => 'มีนาคม', '4' => 'เมษายน', '5' => 'พฤษภาคม', '6' => 'มิถุนายน',
	 			'7' => 'กรกฎาคม', '8' => 'สิงหาคม', '9' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'];
	if ($abbr) $months = ['1' => 'ม.ค.', '2' => 'ก.พ.', '3' => 'มี.ค.', '4' => 'เม.ย.', '5' => 'พ.ค.', '6' => 'มิ.ย.',
	 			'7' => 'ก.ค.', '8' => 'ส.ค.', '9' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'];
	return $months;
}
function helperGetMonth($m, $lang = 'en', $abbr = false)
{
	return ($lang == 'en') ? helperGetMonthEng($m, $abbr) : helperGetMonthThai($m, $abbr);
}
function helperGetMonthThai($m, $abbr = false)
{
	$month = helperGetMonthThaiAll($abbr);
	return (isset($month[(int)$m])) ? $month[(int)$m] : $m;
}
function helperGetMonthEng($m, $abbr = false)
{
	return ($abbr) ? date('M', strtotime('2017-'.$m.'-01')) : date('F', strtotime('2017-'.$m.'-01'));
}

// PASSED.
function helperDateCountTimePassed($datetime, $lang = 'en', $abbr = false)
{
	if (empty($datetime)) return $datetime;

	$result = '';
	$result .= date('d', strtotime($datetime)).' ';
	$result .= helperGetMonth(date('m', strtotime($datetime)), $lang, $abbr).' ';
	$result .= helperGetYear(date('Y', strtotime($datetime)), $lang, $abbr).' ';

	$time = strtotime($datetime);
	$time = time() - $time;

    $t_ago = date('H:i', strtotime($datetime)).(($lang == 'en') ? '' : ' น.');

    if ($time <= 86400) {

    	$tokens = array (
	        3600 => ($lang == 'en') ? 'hours' : 'ชั่วโมง',
	        60 => ($lang == 'en') ? 'min' : 'นาที',
	        1 => ($lang == 'en') ? 'sec' : 'วินาที'
	    );

	    foreach ($tokens as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);
	        $t_ago = $numberOfUnits.' '.$text.' '.(($lang == 'en') ? 'ago' : 'ที่แล้ว');
	        break;
	    }
    }

	return $result.': '.$t_ago;
}

function helperDateFormatDBToPicker($date)
{
	if (empty($date)) return $date;
	if (count(explode('-', $date)) < 3) return $date;

	list($year, $month, $day) = explode('-', $date);
	return $day.'/'.$month.'/'.$year;
}
function helperDateFormatPickerToDB($date)
{
	if (empty($date)) return $date;
	if (count(explode('/', $date)) < 3) return $date;

	list($day, $month, $year) = explode('/', $date);
	return $year.'-'.$month.'-'.$day;
}

