<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shop = $_REQUEST['shop'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{	
	$themes = $shopify("GET /admin/themes.json");
	foreach($themes as $theme) {
		if($theme['role'] == 'main') {
		$themeId = $theme['id'];
		$collectionTemplate = $shopify("GET /admin/themes/$themeId/assets.json?asset[key]=templates/collection.liquid&theme_id=$themeId");
			if (strpos($collectionTemplate['value'], '{{ collection.metafields.collectionlower.lowerdata }}') === false) {
				//$colMeta = $collectionTemplate['value'].'{{ collection.metafields.collectionlower.lowerdata }}';
				$colMeta = $collectionTemplate['value'].'<div class="col_lower_main" style="width:100%"><div class="inner_lower_main" style="max-width:1170px;margin:0 auto;">{{ collection.metafields.collectionlower.lowerdata }}</div></div>'; 
				$fields = array( "asset" => array('key' => 'templates/collection.liquid', 'value' => $colMeta));
				$jsonfields = json_encode($fields);
				//$modifyColTemplate = $shopify("PUT /admin/themes/$themeId/assets.json");
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://$shop/admin/themes/$themeId/assets.json",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_CUSTOMREQUEST => "PUT",
					CURLOPT_POSTFIELDS => $jsonfields,
					CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					"content-type: application/json",
					"x-shopify-access-token: $access_token"
					),
				));
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if ($err) {
					//echo "cURL Error #:" . $err;
					echo 'Try again Later!';
				} else {
					//echo $response;
					echo 'Added Metafield Code!';
				}
			} else {
				echo 'Already Added Metafield Code!';
			}
		}
	}
}
catch (shopify\ApiException $e)
{
	# HTTP status code was >= 400 or response contained the key 'errors'
	echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse());
}
?>
