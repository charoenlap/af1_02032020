<?php

function helperReturnErrorFormRequest($field, $message = '')
{
	header('HTTP/1.1 422 Unprocessable Entity');
	$responseText['errors'][$field][0] = $message;
	die(json_encode($responseText));
}

function helperReturnErrorFormRequestArray($fields)
{
    header('HTTP/1.1 422 Unprocessable Entity');

    foreach ($fields as $field => $message) {

        $responseText['errors'][$field][] = $message;
    }

    die(json_encode($responseText));
}