<?php

header('Content-Type: text/html; charset=utf-8');

require 'config.php';
require 'lib/simpleCachedCurl.inc.php';
require 'lib/datafetcher.php';

?>
<html>
	<head>

		<link href="<?php echo $localNetmon;?>templates/freifunk_oldenburg/css/central_netmon.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="css/site.css" />
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<script src="<?php echo $localNetmon;?>lib/extern/DataTables/jquery.dataTables.min.js"></script>
		<script src="<?php echo $localNetmon;?>lib/extern/DataTables/jquery.dataTables.Plugin.DateSort.js"></script>
		<script>

		$(document).ready(function() {
			$('#routers').dataTable( {
				"bFilter": false,
				"bInfo": false,
				"bPaginate": false,
				"aoColumns": [ 
					{ "sType": "html" },
					{ "sType": "html" },
					{ "sType": "string" },
					{ "sType": "string" },
					{ "sType": "date-eu" },
					{ "sType": "html" },
					{ "sType": "numeric" },
					{ "sType": "numeric" },
					{ "sType": "numeric" },
				],
				"aaSorting": [[ 0, "asc" ],[ 5, "asc" ]]
			} );
		} );

		</script>
	</head>
<body>
	<div id="page">
		<div id="main" style="margin-bottom:40px;">
			<h2>MetaNetmon Freifunk in Franken</h2>
			<p>Routerliste der fränkischen Freifunk-Netmons<p>
			<table class="display" style="width: 100%;" id="routers">
				<thead>
					<tr>
						<th>Community</th>
						<th>Hostname</th>
						<th>O</th>
						<th>Technik</th>
						<th>Angelegt</th>
						<th>Benutzer</th>
						<th>Up (Std.)</th>
						<th>Clients</th>
						<th>Firmware</th>
					</tr>
				</thead>
				<tbody>
<?php
				foreach($communities AS &$community)
				{
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

					generateRouterRows($routers, $community);
				}

				?>
				</tbody>
			</table>
			<div style="margin: 20px 0; text-align:left;">
				<h2>Zusammenfassung</h2>
				<table class="display" style="width: 100%;" id="routers">
					<thead>
						<tr>
							<th>Community</th>
							<th>Router</th>
							<th>Router Online</th>
							<th>Clients</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$rTotal = 0;
						$rOnline = 0;
						$cCount = 0;

						foreach($communities AS $communityTotal)
						{
							$rTotal += $communityTotal['routerCount'];
							$rOnline += $communityTotal['routerOnlineCount'];
							$cCount += $communityTotal['clientCount'];
							?>
								<tr>
									<td><?php echo $communityTotal['name']; ?></td>
									<td><?php echo $communityTotal['routerCount']; ?></td>
									<td><?php echo $communityTotal['routerOnlineCount']; ?></td>
									<td><?php echo $communityTotal['clientCount']; ?></td>
								</tr>
							<?php
						}
					?>
					<tr>
						<th>Summe</th>
						<th><?php echo $rTotal; ?></th>
						<th><?php echo $rOnline; ?></th>
						<th><?php echo $cCount; ?></th>
					</tr>
					</tbody>
				</table>
			</div>
			<div style="margin: 20px 0; text-align:left;">
				<h2>Hinweis</h2>
				<p>Die Daten werden per API von den jeweiligen Netmons abgerufen. Es ist ein Caching von 30 Minuten implementiert.</p>
			</div>
			<a class="btn" href="map.php" id="toList">zur Karte</a>
		</div>
	</div>
<body>
</html>
<?php

function generateRouterRows($routers, &$community)
{
	$rTotal = 0;
	$rOnline = 0;
	$cCount = 0;
	foreach($routers AS $router)
	{
		?>
			<tr>
				<td title="<?php echo $community['name']; ?>">
					<a href="<?php echo $community['url']; ?>" target="community_netmon"><?php echo $community['nameShort']; ?></a>
				</td>
				<td>
					<a href="<?php echo $community['url'].'/router.php?router_id='.$router->router_id; ?>" target="netmon_router"><?php echo htmlspecialchars($router->hostname); ?></a>
				</td>
				<td>
					<?php
						$isOn = ($router->statusdata->status == 'online');
					?>
					<img src="https://netmon.freifunk-emskirchen.de/templates/freifunk_oldenburg/img/ffmap/<?php echo ($isOn ? 'status_up_small' : 'status_down_small'); ?>.png"
						title="<?php htmlspecialchars($router->statusdata->status); ?>">
				</td>
				<td><?php echo htmlspecialchars($router->chipset->hardware_name); ?></td>
				<td><?php echo date("d.m.Y", (int)$router->create_date); ?></td>
				<td>
					<a href="<?php echo $community['url'].'/user.php?user_id='.(int)$router->user->user_id; ?>" target="netmon_user"><?php echo htmlspecialchars($router->user->nickname); ?></a>
				</td>
				<td><?php echo round(($router->statusdata->uptime/60/60), 1); ?></td>
				<td><?php echo (int)$router->statusdata->client_count; ?></td>
				<td><?php echo htmlspecialchars($router->statusdata->distname).' '.htmlspecialchars($router->statusdata->firmware_version); ?></td>
			</tr>
		<?php

		if($isOn)
		{
			$rOnline ++;
		}

		$rTotal ++;
		$cCount += (int)$router->statusdata->client_count;
	}

	$community['routerCount'] = $rTotal;
	$community['routerOnlineCount'] = $rOnline;
	$community['clientCount'] = $cCount;
}
