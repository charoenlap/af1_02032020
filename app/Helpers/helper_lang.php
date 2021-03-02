<?php

function helperSetLang($lang = '')
{
	$all = helperGetLangAll();

	if (!in_array($lang, $all) || $lang == '') {
		$lang = helperGetLangDefault();
	}

	\Session::put('display_lang', $lang);
	return $lang;
}

function helperLang()
{
	if (!\Session::has('display_lang')) {
		helperSetLang();
	}

	return \Session::get('display_lang');
}

function helperGetLangAll()
{
	$lang = config('app.langs');
	return $lang;
}

function helperGetLangDefault()
{
	$langs = helperGetLangAll();
	$first = $langs[1];
	return $first;
}

function helperClearLang()
{
	\Session::forget('display_lang');
}