<?php
header('Content-Type: text/html; charset=utf-8');

require 'config.php';
require 'lib/simpleCachedCurl.inc.php';
require 'lib/datafetcher.php';

$routerList = array();

foreach($communities AS $communityKey => $community)
{
	// query api, get simplexml
	$xml = getXml($community['url']);

	if(!$xml)
	{
		return;
	}

	$routers = $xml->routerlist->router;

	if(!$routers)
	{
		return;
	}

	foreach($routers AS $router)
	{
		if($router->latitude == '0' || $router->longitude == '0')
		{
			// router has no location
			continue;
		}

		$thisRouter = array(
			'id' => (int)$router->router_id,
			'lat' => (string)$router->latitude,
			'long' => (string)$router->longitude,
			'name' => (string)$router->hostname,
			'community' => $communityKey,
			'status' => (string)$router->statusdata->status,
			'clients' => (int)$router->statusdata->client_count,
			'user' => array('id' => (int)$router->user->user_id, 'name' => (string)$router->user->nickname)
		);

		// add to routerlist for later use in JS
		array_push($routerList, $thisRouter);
	}

}

?>
<html>
	<head>

		<link href="<?php echo $localNetmon;?>templates/freifunk_oldenburg/css/central_netmon.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="css/site.css" />
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>

		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
		<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
		<script src="js/leaflet.markercluster-src.js"></script>
		<link rel="stylesheet" href="css/MarkerCluster.Default.css" />
		<link rel="stylesheet" href="css/MarkerCluster.css" />
		<script src="js/meta_map.js"></script>

		<style>
			html{
				height:100%;
				overflow: hidden;
			}
			body {
				height:99%;
				padding:	10px;
				box-sizing: border-box;
			}
		</style>
	</head>
	<body>
		<div id="map"></div>
		<a class="btn" href="index.php" id="toList">zur Liste</a>
		<script>
			var allTheRouters = <?php echo json_encode($routerList);?>;
			var communities = <?php echo json_encode($communities);?>;
			var mapboxMapId = <?php echo json_encode($mapboxMapId);?>;

			if(mapboxMapId == '') alert("Mapbox-ProjectID fehlt.\n\nDas wird so nicht funktionieren.\nSiehe config.php");

			$(function() {
				// initialize cluster, add points
				addPoints2Map(allTheRouters);
			});
		</script>
	</body>
</html>