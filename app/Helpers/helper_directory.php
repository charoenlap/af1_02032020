<?php

function helperDirBase()
{
	return base_path();
}


function helperDirPublic()
{
	return base_path().'/public_html';
}

function helperDirContent()
{
	return helperDirPublic().'/contents';
}


