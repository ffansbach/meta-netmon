// docready
$(function() {
	init();
	icons = prepareIcon();
});

var map;
var icons;

/**
 * initialize map
 *
 * prepare mapcontainer
 * set tile-layer
 */
function init()
{
	map = L.map('map').setView([49.447733, 10.767502], 10);

	L.tileLayer('http://{s}.tiles.mapbox.com/v3/'+mapboxMapId+'/{z}/{x}/{y}.png',
	{
	    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
	    maxZoom: 18
	}).addTo(map);

}

/**
 * returns icons for online/offline routers
 * @return {object} object with 2 leaflet-icons
 */
function prepareIcon()
{
	var icon = L.icon({
		iconUrl: 'img/hotspot.png',
		iconSize:     [42, 27], // size of the icon
		iconAnchor:   [21, 13], // point of the icon which will correspond to marker's location
		popupAnchor:  [0, -9] // point from which the popup should open relative to the iconAnchor
	});

	var icon_off = L.icon({
		iconUrl:'img/hotspot_offline.png',
		iconSize:     [42, 27], // size of the icon
		iconAnchor:   [21, 13], // point of the icon which will correspond to marker's location
		popupAnchor:  [0, -9] // point from which the popup should open relative to the iconAnchor
	});

	return {hotspot:icon, hotspotOffline:icon_off};
}

/**
 * parse data and add points to cluster-layer
 * @param {object} data
 */
function addPoints2Map(data)
{
	// prepare clustergroup
	var markers = L.markerClusterGroup(
	{
		maxClusterRadius: function(zoom)
		{
			var clusterRadius = 70;
			if(zoom == 18)
			{
				clusterRadius = 10;
			}
			else if(zoom >= 16)
			{
				clusterRadius = 40;
			}

			return clusterRadius;
		}
	});

	// add all entries to clustergroup
	$.each(data, function(i, router)
	{
		var markerSettings = {
			title: router.name,
			icon : icons.hotspot
		};

		if(router.status != 'online')
		{
			// router is not online. ignore unknown, simply show as offline
			markerSettings.icon = icons.hotspotOffline;
		}

		var marker = L.marker(
			new L.LatLng(router.lat, router.long),
			markerSettings
		);

		marker.bindPopup(getTooltipContent(router));
		markers.addLayer(marker);
	});

	// add layer with clustergroup to map
	map.addLayer(markers);
}

/**
 * get tooltip-html for router
 *
 * @param  {object} routerData
 * @return {string}
 */
function getTooltipContent(routerData)
{
	var thisRouterCommunity = communities[routerData.community];
	var tooltip = '<h3 class="router"><a href="'+thisRouterCommunity.url+'/router.php?router_id='+routerData.id+'" target="netmon_router">'+routerData.name+'</a></h3>';
		tooltip += '<h4 class="comm"><a href="'+thisRouterCommunity.url+'" target="community_netmon">'+thisRouterCommunity.name+'</a></h4>';
		tooltip += '<p>';
		tooltip += 'Benutzer: <a href="'+thisRouterCommunity.url+'/user.php?user_id='+routerData.user.id+'" target="netmon_user">'+routerData.user.name+'</a><br />';
		tooltip += 'verbundene Clients: '+routerData.clients+'<br />';

	if(routerData.status != 'online')
	{
		tooltip += '<span class="errorNote">Router ist offline !</span>';
	}

		tooltip += '</p>';

	return tooltip;
}
