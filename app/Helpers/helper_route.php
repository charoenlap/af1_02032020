<?php


function helperRoute()
{
	return Route::currentRouteName();
}

function helperRouteGroup()
{
	$route = explode('.', helperRoute());
	return isset($route[0]) ? $route[0] : '';
}

function helperRouteModule()
{
	$route = explode('.', helperRoute());
	return isset($route[1]) ? $route[1] : '';
}

function helperRouteAction()
{
	$route = explode('.', helperRoute());
	return isset($route[2]) ? $route[2] : '';
}

function helperRouteMethod()
{
	$route = explode('.', helperRoute());
	return isset($route[3]) ? $route[3] : '';
}