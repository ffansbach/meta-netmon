<?php

function getXml($communityUrl)
{
	$url = rtrim($communityUrl, '/');
	$url .= '/api/rest/api.php';
	$url .= '?'.http_build_query(
					array(
						'rquest' => 'routerlist',
						'limit' => 1000,			// one day this will be not enough - TODO. add loop
						'sort_by' => 'router_id'
					)
			);

	if($result = simpleCachedCurl($url, 60*30))
	{
		return simplexml_load_string($result);
	}

	return false;
}
