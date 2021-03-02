<?php

function helperJsonEncode($text) {
	if (empty($text)) return '';
	return json_encode($text, JSON_UNESCAPED_UNICODE);
}

function helperJsonDecode($json) {

	if (!empty($json)) return json_decode($json);
	return [];
}

function helperJsonDecodeToArray($json) {

	$array 		= [];
	$objects 	= helperJsonDecode($json);

	if (empty($objects)) return [];
	if (!is_object($objects) && !is_array($objects)) return [];

	foreach ($objects as $key => $value) {
		$array[$key] = $value;
	}

	return $array;
}