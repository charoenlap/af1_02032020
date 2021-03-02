<?php

function helperMenubarIsFold()
{
	if (\Session::has('menubar_fold')) {
		return \Session::get('menubar_fold');
	}

	return helperSetMenubarIsFold(false);
}

function helperSetMenubarIsFold($fold)
{
	return \Session::put('menubar_fold', $fold);
}

function helperClearMenubarFold()
{
	return \Session::forget('menubar_fold');
}