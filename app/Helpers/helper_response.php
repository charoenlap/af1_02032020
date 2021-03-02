<?php

function helperResponsePutSuccess($message)
{
	$data['status'] 	= 'success';
	$data['message'] 	= $message;
	\Session::put('response_alert', $data);
}

function helperResponsePutFail($message)
{
	$data['status'] 	= 'fail';
	$data['message'] 	= $message;
	\Session::put('response_alert', $data);
}

function helperResponseGet()
{
	$result = "";

	if (\Session::has('response_alert'))
	{
		$flash_alert = \Session::get('response_alert');
		$status 	 = $flash_alert['status'];
		$message	 = $flash_alert['message'];
		$result		 = "<div class='mw-flash-alert' data-status='".$status."' data-message='".$message."'></div>";

		\Session::forget('response_alert');
	}

	return $result;
}