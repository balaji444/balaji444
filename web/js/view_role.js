var csrfTokenPage = $('meta[name="csrf-token"]').attr("content");
function getPagesAssignedtoRole(role_id,displayType) { 

	PostUrl = "/sathsang/web/admin/get-pages-assigned-to-role";
	$("#pages_assigned_to_roles").html("");
	$.post(
			PostUrl, {
					role_id:role_id,
					pstDisplayType:displayType,
                    _csrf : csrfTokenPage
				},
				function(ResponseData)
				{
					$("#pages_assigned_to_roles").html(ResponseData);
				}
	);	

}