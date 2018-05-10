<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$collectionid = $_REQUEST['collectionid'];
$metafieldData = $_REQUEST['metafieldData'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{	
	print_r($shopify);
	$metafield = array( "metafield" => array('namespace' => 'collectionlower', 'key' => 'lowerdata', 'value' => $metafieldData, 'value_type' => 'string'));
	print_r($metafield);
	//Collection Metafield
	$response = $shopify('POST /admin/collections/'.$collectionid.'/metafields.json',$metafield);
	print_r($response);
	
}
catch (shopify\ApiException $e)
{
	# HTTP status code was >= 400 or response contained the key 'errors'
	echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse());
}

?>
