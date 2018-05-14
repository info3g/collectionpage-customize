
<?php
session_start();
// Required File Start.........
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
// Required File END...........
error_reporting(E_ALL); 
ini_set('display_errors', 1);
if((isset($_REQUEST['shop'])) && (isset($_REQUEST['code'])) && $_REQUEST['shop']!='' && $_REQUEST['code']!='' )
{
   $access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
}
?>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700" rel="stylesheet">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  rel="stylesheet" type="text/css"/>  
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/richtext.min.css" rel="stylesheet" type="text/css" />
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://use.fontawesome.com/988a7dc35f.js"></script>
	<script src="js/jquery.richtext.min.js"></script>
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
function editColTemplate(access_token,shop){
	$.ajax({
		type: 'GET',
		url: '/editColTemplate.php?access_token='+access_token+'&shop='+shop,
		success: function(response){
			console.log(response);
		}
	});
}
// Fetch Collection Metafield data	
function fetchColmetafield(access_token,shop){
	$('#collection_container table tbody tr').each(function(){
	   var _this = $(this);
	   var colId = $('.collectionSave',this).attr('data-id');
	   $.ajax({
		type: 'GET',
		url: '/getmetafields.php?access_token='+access_token+'&shop='+shop+'&collectionid='+colId,
		success: function(response){
		   if($.trim(response)) {
		    _this.find('textarea').val(response);
		    _this.find('textarea').richText(response);
		   } else {
		    _this.find('textarea').richText();
		   }
		}
	   });
	});
}	

$(document).ready(function(){
	var access_token = '<?php echo $access_token ?>';
	var shop = '<?php echo $_REQUEST['shop'] ?>';
	$.ajax({
		type: 'POST',
		url: '/collections.php?access_token='+access_token+'&shop='+shop,
		dataType: "html",
		success: function(response) { 
		  $("#collection_container").html(response);
		},
		complete: function() {
		  fetchColmetafield(access_token,shop);
		  editColTemplate(access_token,shop);
		}
	});

	$('body').on('click', '.collectionSave', function(e) {
		var colId = $(this).attr('data-id');
		var _this = $(this);
		var metafieldData = $('#col-metafield2_'+colId).val();
		if(metafieldData != '')	{
			$(this).val('Saving...');
			var allData = {
			  'access_token': access_token, 'shop': shop, 'collectionid': colId, 'metafieldData': metafieldData
			};
			$.ajax({
				type: 'POST',
				url: '/metafields.php',
				data: allData,
				success: function(response) { 
				  console.log(response);
				  setTimeout(function(){ _this.val('Save'); }, 1000);
				},
				complete: function() {
				  //fetchColmetafield(access_token,shop);
				  editColTemplate(access_token,shop);
				}
			});
		} else {
		  alert('Please fill collection Fields!');
		}
	});
});		
</script>	

</body>
</html>
