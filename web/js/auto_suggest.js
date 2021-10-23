var csrfTokenPage = $('meta[name="csrf-token"]').attr("content");
function getActionOfController() { 

	var controller_name = $("#controller_name").val()+'Controller';

	PostUrl = "/sathsang/web/admin/get-action-name";
	$.post(
			PostUrl, {
					controller_name:controller_name,
                    _csrf : csrfTokenPage
				},
				function(ResponseData)
				{	
					
					//alert($.trim(ResponseData));	
					var jsonResponse = $.parseJSON(ResponseData);
					
					$("#action_name").empty();
					
					$.each(jsonResponse, function(key, value) {  
					 	$('#action_name')
						.append($("<option></option>")
									.attr("value",value)
									.text(value)); 
					});
					
					
					
				}
	);	

}