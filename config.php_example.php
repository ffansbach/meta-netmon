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

// want another style? check http://leaflet-extras.github.io/leaflet-providers/preview/
$tileServerUrl = 'http://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png';
$tileServerAttribution = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';

$mapInitalView = array(
	'latitude' => 49.447733,
	'longitude' => 10.767502,
	'zoom' => 10,
);

/*
 * this will be used for some css and js
 * don't forget to add a slash
 */
$localNetmon = 'https://netmon.freifunk-emskirchen.de/';
