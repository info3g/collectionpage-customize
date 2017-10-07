<!--Revise.js-->
(function(){
var data = $("script[src*='addRevise.js']").attr('src').split('?')[1]; 
$.ajax({
  crossDomain: true,
  url: 'https://revise-app.herokuapp.com/getmeta_json.php?'+data,
  dataType: "jsonp",
  header: {"Access-Control-Allow-Origin": "https://sendd-shipping.myshopify.com"},
  success: function(response){
      //console.log(response['options']);
      var data = response['options'];
      data = data.split(',');
      $.each(data, function(index, value){
        var values = value.split(':');
	value = values[0];
	var classes = values[1];
        var url = window.location.href;
        if(url.indexOf('/products/') > -1 && value == 'product_page'){
          if($('.'+classes).length){
            $('.add_to_cart').after('<a href="#" style="background:#ececec;padding:10px;display:block;text-align:center;margin-top:10px;">Revise</a>');
          }
        } else if(url.indexOf('/collections/') > -1 && value == 'catalog_page'){
          if($('.'+classes).length){
            $('.add_to_cart').after('<a href="#" style="background:#ececec;padding:10px;display:block;text-align:center;margin-top:10px;">Revise</a>');
          }
        } else if(value == 'quick_view'){
          if($('.'+classes).length){
            $('.add_to_cart').after('<a href="#" style="background:#ececec;padding:10px;display:block;text-align:center;margin-top:10px;">Revise</a>');
          }
        }
        
      });
  }
});
})();
