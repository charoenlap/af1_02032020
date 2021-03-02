<?php

function urlBase()
{
	return url('/');
}
function urlPublic()
{
	return urlBase();
}
function urlContent()
{
	return urlBase().'/contents';
}
function urlApi()
{
	return urlBase().'/api/v1';
}

function urlThemes()
{
	return urlBase().'/assets/themes';
}

function urlAdminTheme()
{
	return urlBase().'/assets/admin/themes';
}
function urlAdminImage()
{
	return urlBase().'/assets/admin/images';
}
function urlHomeImage()
{
	return urlBase().'/assets/home/images';
}
function urlHomeTheme()
{
	return urlBase().'/assets/home/themes';
}
