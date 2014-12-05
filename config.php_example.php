<?php

$communities = array(
	'fff'	=> array(
		'name'	=> 'Freifunk Franken',
		'nameShort'	=> 'FF Franken',
		'url'	=> 'https://netmon.freifunk-franken.de'
	),
	'ffans'	=> array(
		'name'	=> 'Freifunk Ansbach',
		'nameShort'	=> 'FF Ansbach',
		'url'	=> 'https://netmon.freifunk-ansbach.de'
	),
	'ffems'	=> array(
		'name'	=> 'Freifunk Emskirchen',
		'nameShort'	=> 'FF Emskirchen',
		'url'	=> 'https://netmon.freifunk-emskirchen.de'
	),
	'fffndt'	=> array(
		'name'	=> 'Freifunk Neuendettelsau',
		'nameShort'	=> 'FF Neuendettelsau',
		'url'	=> 'https://netmon.freifunk-neuendettelsau.de'
	),
	'fffrb'	=> array(
		'name'	=> 'Freifunk Rothenburg',
		'nameShort'	=> 'FF Rothenburg',
		'url'	=> 'http://netmon.freifunk-rothenburg.de/'
	)
);

/*
 * register at https://www.mapbox.com/projects/
 * create a project and configure as pleased,
 * then set project id here
 *
 * something like: $mapboxMapId = 'username.project_id';
 */
$mapboxMapId = '';

/*
 * this will be used for some css and js
 * don't forget to add a slash
 */
$localNetmon = 'https://netmon.freifunk-emskirchen.de/';
