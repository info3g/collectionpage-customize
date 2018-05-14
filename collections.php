<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{     
	$collections = $shopify('GET /admin/custom_collections.json');
	$smartcollection = $shopify('GET /admin/smart_collections.json'); 
	if($collections || $smartcollection){
		echo '<form method="post" name="form" id="getcollection" action="#">';
		echo '<table cellspacing="10" cellpadding="10" border="1">';
		echo '<thead><tr><th>Collection</th><th>Collection bottom content</th><th>Action</th></tr></thead>';
		echo '<tbody>';
	}
	if($collections){
		foreach($collections as $Allcollections)
		{	
			$img = '';
			if($Allcollections['image']) {
				$img = '<img src="'.$Allcollections["image"]["src"].'" alt="collectionimage" />';
			}
			$colId = $Allcollections["id"];
			echo '<tr>';
			echo '<td>'.$img.'<p class="col-title">'.$Allcollections['title'].'</p></td>';
			echo '<td>'.'<textarea class="form-control" id="col-metafield2_'.$colId.'" name="lowerData[]"></textarea>'.'</td>';
			echo '<td>'.'<input type="button" class="collectionSave" value="Save" name="addColData" data-id="'.$colId.'"></td>';
			echo '</tr>';

		}
	}
	if($smartcollection){
		foreach($smartcollection as $smartcollections)
		{	
			$img = '';
			if($smartcollections['image']) {
				$img = '<img src="'.$smartcollections["image"]["src"].'" alt="collectionimage" />';
			}
			$colId = $smartcollections["id"];
			echo '<tr>';
			echo '<td>'.$img.'<p class="col-title">'.$smartcollections['title'].'</p></td>';
			echo '<td>'.'<textarea class="form-control" id="col-metafield2_'.$colId.'" name="lowerData[]"></textarea>'.'</td>';
			echo '<td>'.'<input type="button" class="collectionSave" value="Save" name="addColData" data-id="'.$colId.'"></td>';
			echo '</tr>';
		}
	}
	if($collections || $smartcollection){
		echo '</tbody>';
		echo '</table>';
		echo '</form>';
	} else {
		echo "<div class='no-result'>No collections</div>";
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
