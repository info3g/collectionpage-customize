
<?php
session_start();
// Required File Start.........
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connection.php'; //DB connectivity
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
// Required File END...........
error_reporting(E_ALL); 
ini_set('display_errors', 1);
if((isset($_REQUEST['shop'])) && (isset($_REQUEST['code'])) && $_REQUEST['shop']!='' && $_REQUEST['code']!='' )
{
	$_SESSION['shop']=$_REQUEST['shop'];
	$_SESSION['code']=$_REQUEST['code'];
}
$access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
$server = 'https://'.$_SERVER['SERVER_NAME'];
?>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700" rel="stylesheet">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://use.fontawesome.com/988a7dc35f.js"></script>
	<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  rel="stylesheet" type="text/css"/>  
	<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="logo">
	<h2>APP Logo</h2>
</div>
<div class="content-container">
	<h1 class="title">Welcome to Collection page customize APP</h1>
	<div id="main-container">
		<div id="collection_container"></div>	
	</div>
</div> 
<script>
// Add Script
function addScript(options){
var access_token = '<?php echo $access_token ?>';
var shop = '<?php echo $_REQUEST['shop'] ?>';
var server = '<?php echo $_SERVER['SERVER_NAME']; ?>';
$.ajax({
	url: '/addScript.php?access_token='+access_token+'&shop='+shop+'&options='+options+'&server='+server,
	success: function(data){
		console.log(data);
		if($("#manual_code").is(':checked')){
			$('#generate_code').show().val(data);
		} else if($("#automatic_code").is(':checked')){
			$('#generate_code').hide().val(" ");
		}
	}
});
}	

$(document).ready(function(){
var access_token = '<?php echo $access_token ?>';
var shop = '<?php echo $_REQUEST['shop'] ?>';
var server = '<?php echo $_SERVER['SERVER_NAME']; ?>';
$.ajax({
	type: 'POST',
	url: '/collections.php?access_token='+access_token+'&shop='+shop,
	dataType: "html",
	success: function(data) { 
	  $("#collection_container").html(data);
	}
});
$('body').on('click', '.collectionSave', function(e) {
var colId = $(this).attr('data-id');
var meta2 = $("#col-metafield2").val();
alert(meta2);	
//var meta1 = $('#col-metafield2_'+colId);
//alert(meta1);
if(meta2 != '')	{
	$.ajax({
	type: 'POST',
	 url: '/metafields.php?access_token='+access_token+'&shop='+shop+'&collectionid='+colId+'&meta2='+meta2,
	dataType: "html",
	success: function(responsecollection) { 
	console.log(responsecollection);
	}
	});
} else {
  alert('Please fill collection Fields!');
}
});	
/*********************fetchdata********************************/
function fetchMetafield(){
	console.log('fetch Metafield');
	$.ajax({
		url: '/getmetafields.php?access_token='+access_token+'&shop='+shop+'&collectionid='+colId+'&meta2='+meta2,
		success: function(data){
			
			console.log(data);
		}
	});
}			
/*********************endfetch********************************/
	
});
</script>	

</body>
</html>
